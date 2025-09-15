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
                                <div class="d-flex align-items-center gap-2">
                                    <h4 class="mb-0" id="voucher-code">{{ $voucher->code }}</h4>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-copy-code"
                                        title="Sao chép mã">
                                        <i class="bi bi-clipboard">COPY</i>
                                    </button>
                                </div>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>

                            @php
                                $now = now();
                                $isActiveFlag = (bool) $voucher->active;
                                $starts = $voucher->starts_at;
                                $expires = $voucher->expires_at;
                                $notStarted = $starts && $now->lt($starts);
                                $expired = $expires && $now->gt($expires);
                                // Trạng thái thông minh
                                if (!$isActiveFlag) {
                                    $statusBadge = ['label' => 'Tắt', 'class' => 'bg-secondary'];
                                } elseif ($expired) {
                                    $statusBadge = ['label' => 'Hết hạn', 'class' => 'bg-danger'];
                                } elseif ($notStarted) {
                                    $statusBadge = ['label' => 'Sắp hiệu lực', 'class' => 'bg-warning text-dark'];
                                } else {
                                    $statusBadge = ['label' => 'Đang hoạt động', 'class' => 'bg-success'];
                                }
                                $used = (int) ($voucher->used_count ?? 0);
                                $limit = $voucher->usage_limit; // null = ∞
                                $remaining = is_null($limit) ? '∞' : max(0, $limit - $used);
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Loại voucher</h6>
                                        <p class="mb-0">
                                            @if ($voucher->type === 'fixed')
                                                <span class="badge bg-primary">Giảm cố định</span>
                                            @else
                                                <span class="badge bg-info text-dark">Giảm phần trăm</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Giá trị</h6>
                                        <p class="fw-bold mb-0">
                                            @if ($voucher->type === 'fixed')
                                                {{ number_format($voucher->value, 0, ',', '.') }} ₫
                                            @else
                                                {{ rtrim(rtrim(number_format($voucher->value, 2, ',', '.'), '0'), ',') }}%
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Đơn tối thiểu</h6>
                                        <p class="mb-0">
                                            {{ number_format($voucher->min_order_amount ?? 0, 0, ',', '.') }} ₫
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Giảm tối đa</h6>
                                        <p class="mb-0">
                                            @if (!is_null($voucher->max_discount_amount))
                                                {{ number_format($voucher->max_discount_amount, 0, ',', '.') }} ₫
                                            @else
                                                Không giới hạn
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Ngày bắt đầu</h6>
                                        <p class="mb-0">
                                            {{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y H:i') : '---' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="text-muted">Ngày hết hạn</h6>
                                        <p class="mb-0">
                                            {{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y H:i') : '---' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Lượt sử dụng</h6>
                                        <p class="mb-1">
                                            Đã dùng: <b>{{ number_format($used) }}</b>
                                            @if (is_null($limit))
                                                / <b>∞</b>
                                            @else
                                                / Giới hạn: <b>{{ number_format($limit) }}</b>
                                            @endif
                                        </p>
                                        <p class="text-muted mb-0">
                                            Còn lại:
                                            <b>
                                                @if (is_null($limit))
                                                    ∞
                                                @else
                                                    {{ number_format($remaining) }}
                                                @endif
                                            </b>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Trạng thái</h6>
                                        <p class="mb-0">
                                            <span
                                                class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- (Tuỳ chọn) Điều kiện áp dụng tóm tắt --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-light border d-flex align-items-center gap-2">
                                        <i class="bi bi-info-circle"></i>
                                        <div>
                                            <div>
                                                <b>Điều kiện áp dụng:</b>
                                                @if ($voucher->type === 'percent')
                                                    Giảm
                                                    {{ rtrim(rtrim(number_format($voucher->value, 2, ',', '.'), '0'), ',') }}%
                                                    @if (!is_null($voucher->max_discount_amount))
                                                        (tối đa
                                                        {{ number_format($voucher->max_discount_amount, 0, ',', '.') }} ₫)
                                                    @endif
                                                @else
                                                    Giảm {{ number_format($voucher->value, 0, ',', '.') }} ₫
                                                @endif
                                                @if (!is_null($voucher->min_order_amount))
                                                    , đơn hàng tối thiểu
                                                    {{ number_format($voucher->min_order_amount, 0, ',', '.') }} ₫
                                                @endif
                                                .
                                            </div>
                                            <div>
                                                Hiệu lực:
                                                @if ($voucher->starts_at)
                                                    từ {{ $voucher->starts_at->format('d/m/Y H:i') }}
                                                @else
                                                    từ hiện tại
                                                @endif
                                                @if ($voucher->expires_at)
                                                    đến {{ $voucher->expires_at->format('d/m/Y H:i') }}
                                                @else
                                                    (không thời hạn)
                                                @endif
                                                .
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- /Tuỳ chọn --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        (function() {
            const btnCopy = document.getElementById('btn-copy-code');
            const codeEl = document.getElementById('voucher-code');
            if (btnCopy && codeEl) {
                btnCopy.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(codeEl.textContent.trim());
                        btnCopy.innerHTML = '<i class="bi bi-clipboard-check"></i>';
                        btnCopy.classList.remove('btn-outline-primary');
                        btnCopy.classList.add('btn-success');
                        setTimeout(() => {
                            btnCopy.innerHTML = '<i class="bi bi-clipboard"></i>';
                            btnCopy.classList.add('btn-outline-primary');
                            btnCopy.classList.remove('btn-success');
                        }, 1500);
                    } catch (e) {
                        alert('Không thể sao chép mã. Vui lòng thử lại.');
                    }
                });
            }
        })();
    </script>
@endsection
