@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sửa Voucher</h5>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="container-fluid mt-3">
            <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST"
                class="theme-form theme-form-2 mega-form" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Thông tin voucher --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thông tin Voucher</h5>
                                </div>

                                {{-- Mã voucher --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mã voucher</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code', $voucher->code) }}" placeholder="Nhập mã voucher">
                                        @error('code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Loại voucher --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Loại voucher</label>
                                    <div class="col-sm-10">
                                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                                            <option value="fixed"
                                                {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Giảm số tiền
                                                cố định</option>
                                            <option value="percent"
                                                {{ old('type', $voucher->type) === 'percent' ? 'selected' : '' }}>Giảm theo
                                                phần trăm</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Giá trị --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Giá trị</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="value"
                                            class="form-control @error('value') is-invalid @enderror"
                                            value="{{ old('value', $voucher->value) }}" placeholder="Nhập giá trị giảm">
                                        @error('value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Đơn hàng tối thiểu --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Đơn hàng tối thiểu</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="min_order_amount"
                                            class="form-control @error('min_order_amount') is-invalid @enderror"
                                            value="{{ old('min_order_amount', $voucher->min_order_amount) }}">
                                        @error('min_order_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Ngày bắt đầu --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Ngày bắt đầu</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" name="starts_at"
                                            class="form-control @error('starts_at') is-invalid @enderror"
                                            value="{{ old('starts_at', $voucher->starts_at ? $voucher->starts_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('starts_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Ngày hết hạn --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Ngày hết hạn</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" name="expires_at"
                                            class="form-control @error('expires_at') is-invalid @enderror"
                                            value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('expires_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Số lượt sử dụng --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Số lượt sử dụng</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="usage_limit"
                                            class="form-control @error('usage_limit') is-invalid @enderror"
                                            value="{{ old('usage_limit', $voucher->usage_limit) }}">
                                        @error('usage_limit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Trạng thái</label>
                                    <div class="col-sm-10">
                                        <select name="active" class="form-control @error('active') is-invalid @enderror">
                                            <option value="1"
                                                {{ old('active', $voucher->active) == 1 ? 'selected' : '' }}>Hoạt động
                                            </option>
                                            <option value="0"
                                                {{ old('active', $voucher->active) == 0 ? 'selected' : '' }}>Tắt</option>
                                        </select>
                                        @error('active')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Nút submit --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-end">
                                <button class="btn btn-success px-4 py-2 fw-bold" type="submit">
                                    <i class="fa fa-save me-1"></i> Cập nhật
                                </button>
                                <a href="{{ route('admin.vouchers.index') }}"
                                    class="btn btn-outline-secondary px-4 py-2 fw-bold">
                                    Hủy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
