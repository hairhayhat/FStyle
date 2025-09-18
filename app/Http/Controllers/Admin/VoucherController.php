<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        // Chuẩn hoá input
        $request->merge([
            'code' => Str::upper(trim((string) $request->input('code'))),
            'active' => $request->boolean('active'),
        ]);

        $request->validate([
            'code' => ['required', 'max:50', 'unique:vouchers,code'],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            // 'active' đã convert bằng boolean(), không cần required
        ], [
            'code.required' => 'Mã voucher không được để trống.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'type.required' => 'Loại voucher không được để trống.',
            'type.in' => 'Loại voucher phải là cố định hoặc phần trăm.',
            'value.required' => 'Giá trị giảm không được để trống.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'max_discount_amount.numeric' => 'Giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Giảm tối đa phải lớn hơn hoặc bằng 0.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'starts_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày bắt đầu.',
            'usage_limit.integer' => 'Số lần sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lần sử dụng phải lớn hơn hoặc bằng 0.',
        ]);

        // Ràng buộc theo type
        if ($request->type === 'percent') {
            if ($request->value <= 0 || $request->value > 100) {
                return back()->withInput()->with('error', 'Voucher phần trăm phải trong khoảng 1–100%.');
            }
        } elseif ($request->type === 'fixed') {
            if (!is_null($request->min_order_amount) && $request->value >= $request->min_order_amount) {
                return back()->withInput()->with('error', 'Giá trị voucher cố định phải nhỏ hơn giá trị đơn hàng tối thiểu.');
            }
            if ($request->max_discount_amount > $request->value) {
                return back()->withInput()->with('error', 'Giá trị giảm tối đa không lớn hơn giá trị voucher.');
            }
        }
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
        if ($voucher->isUsed()) {
            return redirect()->route('admin.vouchers.index')
                ->with('error', 'Voucher này đang được sử dụng, không thể sửa.');
        }

        return view('admin.vouchers.edit', compact('voucher'));
    }


    /**
     * Cập nhật voucher
     */
    public function update(Request $request, Voucher $voucher)
    {
        // Chuẩn hoá input
        $request->merge([
            'code' => Str::upper(trim((string) $request->input('code'))),
            'active' => $request->boolean('active'),
        ]);

        $request->validate([
            'code' => ['required', 'max:50', Rule::unique('vouchers', 'code')->ignore($voucher->id)],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
        ], [
            'code.required' => 'Mã voucher không được để trống.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'type.required' => 'Loại voucher không được để trống.',
            'type.in' => 'Loại voucher phải là cố định hoặc phần trăm.',
            'value.required' => 'Giá trị giảm không được để trống.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'max_discount_amount.numeric' => 'Giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Giảm tối đa phải lớn hơn hoặc bằng 0.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'starts_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày bắt đầu.',
            'usage_limit.integer' => 'Số lần sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Số lần sử dụng phải lớn hơn hoặc bằng 0.',
        ]);

        if ($request->type === 'percent') {
            if ($request->value <= 0 || $request->value > 100) {
                return back()->withInput()->with('error', 'Voucher phần trăm phải trong khoảng 1–100%.');
            }
        } elseif ($request->type === 'fixed') {
            if (!is_null($request->min_order_amount) && $request->value >= $request->min_order_amount) {
                return back()->withInput()->with('error', 'Giá trị voucher cố định phải nhỏ hơn giá trị đơn hàng tối thiểu.');
            }
        }

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
        if ((int) $voucher->used_count > 0) {
            return back()->with('error', 'Không thể xóa voucher đã được sử dụng (đã dùng '
                . (int) $voucher->used_count . ' lần). Bạn có thể tắt voucher thay vì xóa.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa voucher thành công.');
    }
}
