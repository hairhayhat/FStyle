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
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 5);
        $image = $request->get('image');

        $query = Comment::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($image === 'has_image') {
            $query->whereHas('media');
        } elseif ($image === 'no_image') {
            $query->doesntHave('media');
        }

        $comments = $query->orderBy('created_at', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        $statusCounts = [
            'active' => Comment::whereHas('media')->count(),
            'locked' => Comment::doesntHave('media')->count(),
        ];

        $products = Product::all();

        if ($request->ajax()) {
            $html = view('admin.partials.table-comments', compact('comments'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.comment.index', compact('comments', 'products', 'statusCounts'));
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
