<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentService
{

    public function getCommentByRating($from_date = null, $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $comments = Comment::select(
            'rating',
            DB::raw('COUNT(*) as total')
        )
            ->where('status', 1)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('rating')
            ->orderBy('rating', 'asc')
            ->get();

        $result = collect(range(1, 5))->map(function ($rating) use ($comments) {
            $item = $comments->firstWhere('rating', $rating);
            return [
                'rating' => $rating,
                'total' => $item ? $item->total : 0
            ];
        });

        return $result;
    }

    public function getRatingRate($from_date = null, $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $totalDelivered = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->whereIn('status', ['delivered', 'rated'])
            ->count();

        $totalRated = Order::where('status', 'rated')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        if ($totalDelivered === 0) {
            return [
                'labels' => ['Đã đánh giá', 'Chưa đánh giá'],
                'data' => [0, 0],
            ];
        }

        $ratedPercent = round(($totalRated / $totalDelivered) * 100, 2);
        $notRatedPercent = 100 - $ratedPercent;

        return [
            'labels' => ['Đã đánh giá', 'Chưa đánh giá'],
            'data' => [$ratedPercent, $notRatedPercent],
        ];
    }

    public function getTopRating($from_date = null, $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Comment::join('products', 'comments.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('AVG(comments.rating) as avg_rating'),
                DB::raw('COUNT(comments.id) as total_reviews')
            )
            ->where('comments.status', 1)
            ->whereBetween('comments.created_at', [$fromDate, $toDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_reviews')
            ->limit(5)
            ->get();
    }


}
