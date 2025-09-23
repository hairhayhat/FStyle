<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductChartController extends Controller
{
    public function __construct(
        private ProductService $productService,
    ) {
    }
    public function index()
    {
        return view('admin.chart.chart-product');
    }

    public function getProductChartData(Request $request)
    {
        $topSellingProducts = $this->productService->getTopSellingProductsByTime(
            $request->from_date,
            $request->to_date,
            5
        );

        $labels = [];
        $data = [];

        foreach ($topSellingProducts as $product) {
            $labels[] = $product->product_name . ' (' . $product->total_quantity . ')';

            $data[] = $this->productService->getProfitByProduct(
                $product->product_id,
                $request->from_date,
                $request->to_date
            );
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


    public function getSalesPerformanceData(Request $request)
    {
        $performanceData = $this->productService->getSalesPerformance($request->from_date, $request->to_date, 5);

        $labels = $performanceData->pluck('product_name');
        $data = $performanceData->pluck('performance');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
