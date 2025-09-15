@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Chỉnh sửa voucher</h5>
        </div>

        <div class="container-fluid">
            <form class="theme-form theme-form-2 mega-form" method="POST"
                action="{{ route('admin.vouchers.update', $voucher->id) }}" novalidate autocomplete="off">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- THÔNG TIN VOUCHER --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thông tin voucher</h5>
                                </div>

                                {{-- Mã voucher --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mã voucher</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="code" id="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code', $voucher->code) }}" placeholder="Nhập mã voucher"
                                            maxlength="50" spellcheck="false">
                                        @error('code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Loại voucher --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Loại voucher</label>
                                    <div class="col-sm-10">
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror">
                                            <option value="fixed"
                                                {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>
                                                Giảm số tiền cố định
                                            </option>
                                            <option value="percent"
                                                {{ old('type', $voucher->type) === 'percent' ? 'selected' : '' }}>
                                                Giảm theo phần trăm
                                            </option>
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
                                        <input type="number" step="0.01" min="0" name="value" id="value"
                                            class="form-control @error('value') is-invalid @enderror"
                                            value="{{ old('value', $voucher->value) }}" placeholder="Nhập giá trị giảm"
                                            inputmode="decimal">
                                        @error('value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Đơn hàng tối thiểu --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Đơn hàng tối thiểu</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="1000" min="0" name="min_order_amount"
                                            class="form-control @error('min_order_amount') is-invalid @enderror"
                                            value="{{ old('min_order_amount', $voucher->min_order_amount) }}"
                                            placeholder="0 (không bắt buộc)" inputmode="decimal">
                                        @error('min_order_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Giá trị giảm tối đa --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Giảm tối đa</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="1000" min="0" name="max_discount_amount"
                                            id="max_discount_amount"
                                            class="form-control @error('max_discount_amount') is-invalid @enderror"
                                            value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}"
                                            placeholder="Ví dụ: 500000" inputmode="decimal">
                                        @error('max_discount_amount')
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
                                        <input type="number" name="usage_limit" min="0" step="1"
                                            inputmode="numeric"
                                            class="form-control @error('usage_limit') is-invalid @enderror"
                                            value="{{ old('usage_limit', $voucher->usage_limit) }}" placeholder="0">
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
                                                {{ old('active', $voucher->active) == 1 ? 'selected' : '' }}>
                                                Hoạt động
                                            </option>
                                            <option value="0"
                                                {{ old('active', $voucher->active) == 0 ? 'selected' : '' }}>
                                                Tắt
                                            </option>
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
                                <button class="btn btn-primary px-4 py-2 fw-bold" type="submit">
                                    <i class="bi bi-save me-1"></i> Cập nhật voucher
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            const codeEl = document.getElementById('code');
            const typeEl = document.getElementById('type');
            const valueEl = document.getElementById('value');
            const maxEl = document.getElementById('max_discount_amount');
            const valueHelp = document.getElementById('valueHelp');
            const maxHelp = document.getElementById('maxHelp');

            // Auto uppercase mã voucher + loại bỏ khoảng trắng
            if (codeEl) {
                codeEl.addEventListener('input', () => {
                    const caret = codeEl.selectionStart;
                    codeEl.value = codeEl.value.toUpperCase().replace(/\s+/g, '').trim();
                    codeEl.setSelectionRange(caret, caret);
                });
            }

            function applyTypeUI() {
                const type = typeEl?.value || 'fixed';
                if (type === 'percent') {
                    valueEl.min = '1';
                    valueEl.max = '100';
                    valueEl.step = '1';
                    valueEl.placeholder = 'Nhập % (1–100)';
                    if (maxHelp) maxHelp.classList.remove('text-muted');
                    if (maxEl) maxEl.disabled = false;
                } else {
                    valueEl.min = '0';
                    valueEl.removeAttribute('max');
                    valueEl.step = '1000';
                    valueEl.placeholder = 'Nhập số tiền giảm';
                    if (maxHelp) maxHelp.classList.add('text-muted');
                    // vẫn cho nhập max giảm, không bắt buộc
                }
            }

            function clampNumberInputs(e) {
                const el = e.target;
                if (el.type === 'number') {
                    const min = el.min !== '' ? parseFloat(el.min) : null;
                    const max = el.max !== '' ? parseFloat(el.max) : null;
                    let val = el.value === '' ? '' : parseFloat(el.value);
                    if (val !== '' && !Number.isNaN(val)) {
                        if (min !== null && val < min) val = min;
                        if (max !== null && val > max) val = max;
                        el.value = val;
                    }
                }
            }

            typeEl?.addEventListener('change', applyTypeUI);
            valueEl?.addEventListener('blur', clampNumberInputs);
            maxEl?.addEventListener('blur', clampNumberInputs);

            // Khởi tạo theo old('type') / giá trị hiện có
            applyTypeUI();
        })();
    </script>
@endsection
