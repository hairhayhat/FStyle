@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Chi tiết Voucher</h5>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">{{ $voucher->code }}</h4>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Loại voucher</h6>
                                        <p>
                                            @if ($voucher->type === 'fixed')
                                                <span class="badge bg-primary">Giảm cố định</span>
                                            @else
                                                <span class="badge bg-info">Giảm phần trăm</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Giá trị</h6>
                                        <p class="fw-bold">
                                            @if ($voucher->type === 'fixed')
                                                {{ number_format($voucher->value, 0, ',', '.') }} ₫
                                            @else
                                                {{ $voucher->value }}%
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Đơn tối thiểu</h6>
                                        <p>{{ number_format($voucher->min_order_amount ?? 0, 0, ',', '.') }} ₫</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Giảm tối đa</h6>
                                        <p>
                                            @if ($voucher->max_discount_amount)
                                                {{ number_format($voucher->max_discount_amount, 0, ',', '.') }} ₫
                                            @else
                                                Không giới hạn
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Ngày bắt đầu</h6>
                                        <p>{{ optional($voucher->starts_at)->format('d/m/Y H:i') ?? '---' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Ngày hết hạn</h6>
                                        <p>{{ optional($voucher->expires_at)->format('d/m/Y H:i') ?? '---' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Lượt sử dụng</h6>
                                        <p>{{ $voucher->usage_limit ?? '∞' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Trạng thái</h6>
                                        <p>
                                            @if ($voucher->active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Tắt</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
