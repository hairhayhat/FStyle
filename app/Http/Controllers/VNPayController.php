<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class VNPayController extends Controller
{

    public function return(Request $request)
    {
        $order = Order::where('code', $request->vnp_TxnRef)->first();

        if (!$order) {
            return redirect()->route('client.checkout.index')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($request->vnp_ResponseCode == '00') {
            $order->payment()->update([
                'status' => 'success',
                'paid_at' => now(),
                'gateway_data' => json_encode($request->all()),
            ]);

            return redirect()->route('client.checkout.detail', ['code' => $order->code])
                ->with('success', 'Thanh toán thành công!');
        } elseif ($request->vnp_ResponseCode == '24') {
            $order->payment()->update(['status' => 'failed']);

            return redirect()->route('client.checkout.edit', ['code' => $request->vnp_TxnRef])
                ->with('error', 'Bạn đã hủy thanh toán. Vui lòng chọn lại phương thức khác.');
        } else {
            $order->payment()->update(['status' => 'failed']);

            return redirect()->route('client.checkout.index')
                ->with('error', 'Thanh toán VNPay thất bại. Mã lỗi: ' . $request->vnp_ResponseCode);
        }
    }




    public function ipn(Request $request)
    {
        $inputData = $request->all();
        $vnp_HashSecret = "OKTG7078VFFC9131AD8YNT6YM9SPXJ2Z";

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
            } else {

            }
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        } else {
            return response()->json(['RspCode' => '97', 'Message' => 'Fail checksum']);
        }
    }
}
