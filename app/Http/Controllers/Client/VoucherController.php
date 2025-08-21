<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $code = $request->input('code');
        $orderAmount = (float) $request->input('order_amount');

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher không tồn tại.'
            ]);
        }

        $now = \Carbon\Carbon::now();

        if (!$voucher->active) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher đang bị vô hiệu hoá.'
            ]);
        }

        if ($voucher->starts_at && $now->lt($voucher->starts_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher chưa tới ngày áp dụng.'
            ]);
        }

        if ($voucher->expires_at && $now->gt($voucher->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher đã hết hạn.'
            ]);
        }

        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher đã đạt giới hạn sử dụng.'
            ]);
        }

        if ($voucher->min_order_amount !== null && $orderAmount < $voucher->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để sử dụng voucher.'
            ]);
        }


        $discount = 0;
        if ($voucher->type === 'percent') {
            $discount = ($orderAmount * $voucher->value) / 100;
            $discount = min($discount, $voucher->max_discount_amount);
        } else {
            $discount = min($voucher->value, $voucher->max_discount_amount);
        }

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'new_total' => $orderAmount - $discount
        ]);
    }


}
