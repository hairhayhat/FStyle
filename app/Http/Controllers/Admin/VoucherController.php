<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'active' => 'required|boolean',
        ]);

        Voucher::create($request->only([
            'code',
            'type',
            'value',
            'max_discount_amount',
            'min_order_amount',
            'starts_at',
            'expires_at',
            'usage_limit',
            'active'
        ]));

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công');
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
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'active' => 'required|boolean',
        ]);

        $voucher->update($request->only([
            'code',
            'type',
            'value',
            'max_discount_amount',
            'min_order_amount',
            'starts_at',
            'expires_at',
            'usage_limit',
            'active'
        ]));

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công');
    }

    /**
     * Xóa voucher
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa voucher thành công.');
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
            // Giảm cố định, không vượt quá tổng tiền
            $discount = min((float) $voucher->value, $orderAmount);
        } else {
            // Giảm phần trăm
            $discount = round($orderAmount * ((float) $voucher->value / 100), 2);

            // Áp giới hạn max_discount_amount nếu có
            if ($voucher->max_discount_amount !== null) {
                $discount = min($discount, (float) $voucher->max_discount_amount);
            }
        }

        $newTotal = max(0, $orderAmount - $discount);

        return back()->with('success', "Voucher hợp lệ! Giảm: " . number_format($discount) . "₫. Tổng mới: " . number_format($newTotal) . "₫");
    }
}
