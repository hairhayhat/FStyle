<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherController extends Controller
{
    /**
     * Danh sách voucher
     */
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Form tạo voucher
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Lưu voucher mới
     */
    public function store(StoreVoucherRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Tạo voucher thành công.');
    }

    /**
     * Xem chi tiết voucher
     */
    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }

    /**
     * Form chỉnh sửa voucher
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Cập nhật voucher
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);
        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Cập nhật voucher thành công.');
    }

    /**
     * Xóa voucher
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Xóa voucher thành công.');
    }

    /**
     * Áp dụng voucher (test trong admin hoặc dùng cho checkout)
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0',
        ], [
            'code.required' => 'Vui lòng nhập mã voucher.',
            'order_amount.required' => 'Vui lòng nhập giá trị đơn hàng.',
            'order_amount.numeric' => 'Giá trị đơn hàng phải là số.',
        ]);

        $code = strtoupper($request->code);
        $orderAmount = (float) $request->order_amount;

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'code' => 'Mã voucher không tồn tại.'
            ]);
        }

        if (!$voucher->isValidForAmount($orderAmount)) {
            throw ValidationException::withMessages([
                'code' => 'Voucher không hợp lệ hoặc không đủ điều kiện áp dụng.'
            ]);
        }

        // Tính giảm giá
        if ($voucher->type === 'fixed') {
            $discount = min((float) $voucher->value, $orderAmount);
        } else {
            $discount = round($orderAmount * ((float) $voucher->value / 100), 2);

            if (!empty($voucher->meta['max_discount'])) {
                $discount = min($discount, (float) $voucher->meta['max_discount']);
            }
        }

        $newTotal = max(0, $orderAmount - $discount);

        return back()->with('success', "Voucher hợp lệ! Giảm: " . number_format($discount) . "₫. Tổng mới: " . number_format($newTotal) . "₫");
    }
}
