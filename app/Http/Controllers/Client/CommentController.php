<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

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
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
    }

}
