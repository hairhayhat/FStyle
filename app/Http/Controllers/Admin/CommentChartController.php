<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentChartController extends Controller
{
    public function __construct(
        private CommentService $commentService,
    ) {
    }

    public function index()
    {
        return view('admin.chart.chart-comment');
    }

    public function getCommentChartData(Request $request)
    {
        $commentData = $this->commentService->getCommentByRating($request->from_date, $request->to_date);

        $labels = $commentData->pluck('rating');
        $data = $commentData->pluck('total');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function getRatingRateData(Request $request)
    {
        $ratingRateData = $this->commentService->getRatingRate($request->from_date, $request->to_date);

        return response()->json($ratingRateData);
    }

    public function getTopRatingProductsData(Request $request)
    {
        $topRatingProductData = $this->commentService->getTopRating($request->from_date, $request->to_date);

        return response()->json([
            'labels' => $topRatingProductData->pluck('product_name'),
            'data' => $topRatingProductData->pluck('avg_rating'),
        ]);
    }
}
