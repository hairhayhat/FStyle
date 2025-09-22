<?php

namespace App\Http\Controllers\Client;

use App\Events\UpdateOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentMedia;
use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{

    public function __construct
    (
        private NotificationService $notificationService,
    ) {
    }
    public function ajaxComments(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $commentsQuery = $product->ActiveComments();

        if ($request->filled('rating')) {
            $commentsQuery->where('rating', $request->rating);
        }

        if ($request->filled('media')) {
            if ($request->media === 'has_image') {
                $commentsQuery->whereHas('media');
            } elseif ($request->media === 'no_image') {
                $commentsQuery->whereDoesntHave('media');
            }
        }

        if ($request->filled('order')) {
            if ($request->order === 'newest') {
                $commentsQuery->orderBy('created_at', 'desc');
            } elseif ($request->order === 'oldest') {
                $commentsQuery->orderBy('created_at', 'asc');
            }
        } else {
            $commentsQuery->latest();
        }

        $comments = $commentsQuery->paginate(3)->withQueryString();

        return view('client.partials.list-comments', compact('comments'));
    }

    public function store(Request $request)
    {
        $comments = $request->input('comments', []);

        foreach ($comments as $orderDetailId => $data) {

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'product_id' => $data['product_id'],
                'product_variant_id' => $data['variant_id'] ?? null,
                'content' => $data['content'] ?? '',
                'status' => true,
                'rating' => $data['rating'] ?? 0,
                'is_accurate' => isset($data['is_accurate']) ? 1 : 0,
            ]);

            if ($request->hasFile("comments.$orderDetailId.media")) {
                foreach ($request->file("comments.$orderDetailId.media") as $file) {
                    if ($file && $file->isValid()) {
                        if (!in_array($file->extension(), ['mp4', 'mov', 'avi', 'webm', 'ogg'])) {
                            $path = $file->store('comments', 'public');

                            CommentMedia::create([
                                'comment_id' => $comment->id,
                                'file_path' => $path,
                                'type' => 'image',
                            ]);
                        }
                    }
                }
            }

            $this->notificationService->notifyAdmins(
                'Bình luận mới',
                "Tài khoản {$comment->user->name} đã để lại bình luận",
                '/admin/comment/' . $comment->id,
                $comment->id
            );
            
            $orderDetail = OrderDetail::find($orderDetailId);
            if ($orderDetail && $orderDetail->order) {
                $order = $orderDetail->order;

                if ($order->status === 'delivered') {
                    $order->update(['status' => 'rated']);

                    broadcast(new UpdateOrderStatus($order));
                }
            }
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
    }

    public function index(Request $request)
    {
        // Lấy option sắp xếp từ query string (default mới nhất)
        $sort = $request->get('sort', 'desc'); // asc = cũ nhất, desc = mới nhất

        // Lấy comments theo user hiện tại + join product
        $comments = auth()->user()
            ->comments()
            ->with('product')
            ->orderBy('created_at', $sort)
            ->paginate(5) // 5 đánh giá / trang
            ->appends(['sort' => $sort]); // giữ tham số sort khi chuyển trang

        return view('client.dashboard.comments', compact('comments', 'sort'));
    }
}
