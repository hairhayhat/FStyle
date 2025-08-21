<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'product']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(15);

        $products = Product::all();

        return view('admin.comment.index', compact('comments', 'products'));
    }

    public function toggleStatus(Request $request, Comment $comment)
    {
        $comment->status = $request->status;
        $comment->save();

        return response()->json([
            'success' => true,
            'status' => $comment->status
        ]);
    }


}
