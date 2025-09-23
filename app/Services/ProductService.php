<?php

namespace App\Services;

use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getTopSellingProductsByTime($startDate, $endDate, $limit = 5)
    {
        return OrderDetail::join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(order_details.quantity) as total_quantity')
            )
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['delivered', 'rated']);
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    public function getProfitByProduct($productId, $startDate, $endDate)
    {
        $profit = OrderDetail::join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('products.id', $productId)
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['delivered', 'rated']);
            })
            ->select(DB::raw('SUM((product_variants.sale_price - product_variants.import_price) * order_details.quantity) as total_profit'))
            ->value('total_profit');

        return $profit ?? 0;
    }

    public function getSalesPerformance($startDate, $endDate, $limit = 5)
    {
        $totalQuantity = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['delivered', 'rated']);
            })
            ->sum('quantity');

        if ($totalQuantity == 0) {
            return collect();
        }

        $products = OrderDetail::join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(order_details.quantity) as total_quantity')
            )
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['delivered', 'rated']);
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();

        return $products->map(function ($item) use ($totalQuantity, $startDate, $endDate) {
            return [
                'product_name' => $item->product_name,
                'quantity' => $item->total_quantity,
                'performance' => round(($item->total_quantity / $totalQuantity) * 100, 2),
                'profit' => $this->getProfitByProduct($item->product_id, $startDate, $endDate)
            ];
        });
    }
}
