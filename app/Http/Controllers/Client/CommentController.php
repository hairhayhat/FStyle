<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentMedia;
use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comments = $request->input('comments', []);

        foreach ($comments as $orderDetailId => $data) {

            // Lưu comment
            $comment = Comment::create([
                'user_id'    => Auth::id(),
                'product_id' => $data['product_id'],
                'content'    => $data['content'] ?? '',
                'status'     => true,
                'rating'     => $data['rating'] ?? 0,
            ]);

            // Lưu media (nếu có)
            if ($request->hasFile("comments.$orderDetailId.media")) {
                foreach ($request->file("comments.$orderDetailId.media") as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('uploads/comments', 'public');
                        $type = in_array($file->extension(), ['mp4', 'mov', 'avi']) ? 'video' : 'image';

                        CommentMedia::create([
                            'comment_id' => $comment->id,
                            'file_path'  => $path,
                            'type'       => $type,
                        ]);
                    }
                }
            }

            //  Đổi trạng thái đơn hàng sang 'rated'
            $orderDetail = OrderDetail::find($orderDetailId);
            if ($orderDetail && $orderDetail->order) {
                $order = $orderDetail->order;

                // Chỉ update khi đơn hàng đã "delivered"
                if ($order->status === 'delivered') {
                    $order->update(['status' => 'rated']);
                }
            }
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
    }
}
