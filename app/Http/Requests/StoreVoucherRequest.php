<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:vouchers,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'active' => 'boolean',
            'meta' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã voucher không được để trống.',
            'code.string' => 'Mã voucher phải là chuỗi ký tự.',
            'code.max' => 'Mã voucher không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã voucher này đã tồn tại.',

            'type.required' => 'Vui lòng chọn loại voucher.',
            'type.in' => 'Loại voucher chỉ có thể là "fixed" (giảm số tiền cố định) hoặc "percent" (giảm theo %).',

            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',

            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',

            'starts_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',

            'usage_limit.integer' => 'Số lượt sử dụng tối đa phải là số nguyên.',
            'usage_limit.min' => 'Số lượt sử dụng tối đa phải lớn hơn 0.',

            'active.boolean' => 'Trạng thái hoạt động không hợp lệ.',
            'meta.array' => 'Thông tin bổ sung phải là mảng.',
        ];
    }
}
