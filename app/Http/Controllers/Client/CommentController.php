<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comments = $request->input('comments', []);

        foreach ($comments as $orderDetailId => $data) {

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'product_id' => $data['product_id'],
                'content' => $data['content'] ?? '',
                'status' => true,
                'rating' => $data['rating'] ?? 0,
            ]);

            if ($request->hasFile("comments.$orderDetailId.media")) {
                foreach ($request->file("comments.$orderDetailId.media") as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('uploads/comments', 'public');
                        $type = in_array($file->extension(), ['mp4', 'mov', 'avi']) ? 'video' : 'image';

                        CommentMedia::create([
                            'comment_id' => $comment->id,
                            'file_path' => $path,
                            'type' => $type,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
    }
}
