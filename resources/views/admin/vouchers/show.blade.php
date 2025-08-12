@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5>Chi tiết Voucher: {{ $voucher->code }}</h5>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        <div class="container-fluid mt-3">
            <div class="card p-4 shadow-sm">
                <p><strong>Mã voucher:</strong> {{ $voucher->code }}</p>
                <p><strong>Loại voucher:</strong> {{ ucfirst($voucher->type) }}</p>
                <p><strong>Giá trị:</strong>
                    {{ $voucher->type == 'fixed' ? number_format($voucher->value) . '₫' : $voucher->value . '%' }}</p>
                <p><strong>Đơn hàng tối thiểu:</strong> {{ number_format($voucher->min_order_amount ?? 0) }}₫</p>
                <p><strong>Ngày bắt đầu:</strong>
                    {{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y H:i') : '---' }}</p>
                <p><strong>Ngày hết hạn:</strong>
                    {{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y H:i') : '---' }}</p>
                <p><strong>Số lượt sử dụng:</strong> {{ $voucher->usage_limit ?? '∞' }}</p>
                <p><strong>Trạng thái:</strong> {{ $voucher->active ? 'Hoạt động' : 'Tắt' }}</p>
            </div>
        </div>
    </div>
@endsection
