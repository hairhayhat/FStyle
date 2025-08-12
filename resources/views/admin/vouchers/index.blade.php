@extends('admin.layouts.app')

@section('content')
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>Danh sách Voucher</h5>
            </div>
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive table-desi">
                                        <table class="user-table table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Mã</th>
                                                    <th>Loại</th>
                                                    <th>Giá trị</th>
                                                    <th>Giảm tối đa</th>
                                                    <th>Đơn tối thiểu</th>
                                                    <th>Ngày bắt đầu</th>
                                                    <th>Ngày hết hạn</th>
                                                    <th>Số lượt</th>
                                                    <th>Trạng thái</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($vouchers as $voucher)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}">
                                                                <span
                                                                    class="d-block"><strong>{{ $voucher->code }}</strong></span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if ($voucher->type === 'percent')
                                                                <span class="badge badge-info">Phần trăm</span>
                                                            @else
                                                                <span class="badge badge-warning">Cố định</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($voucher->value) }}{{ $voucher->type === 'percent' ? '%' : '₫' }}
                                                        </td>
                                                        <td>
                                                            @if ($voucher->max_discount_amount)
                                                                {{ number_format($voucher->max_discount_amount) }}₫
                                                            @else
                                                                Không giới hạn
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($voucher->min_order_amount) }}₫</td>
                                                        <td>{{ optional($voucher->starts_at)->format('d/m/Y') }}</td>
                                                        <td>{{ optional($voucher->expires_at)->format('d/m/Y') }}</td>
                                                        <td>{{ $voucher->used_count }}/{{ $voucher->usage_limit ?? '∞' }}
                                                        </td>
                                                        <td>
                                                            @if ($voucher->active)
                                                                <span class="badge badge-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge badge-secondary">Tắt</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                <li>
                                                                    <a
                                                                        href="{{ route('admin.vouchers.show', $voucher->id) }}">
                                                                        <span class="lnr lnr-eye"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a
                                                                        href="{{ route('admin.vouchers.edit', $voucher->id) }}">
                                                                        <span class="lnr lnr-pencil"></span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                                                        method="POST" class="delete-form"
                                                                        data-name="{{ $voucher->code }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            style="background:none;border:none;padding:0;color:#dc3545;">
                                                                            <span class="lnr lnr-trash"></span>
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted py-4">
                                                            Không có voucher nào
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @if ($vouchers->hasPages())
                                <div class="pagination-box">
                                    {{ $vouchers->links('pagination::bootstrap-4') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const voucherCode = form.dataset.name || 'voucher';
                    Swal.fire({
                        title: 'Xác nhận xoá?',
                        text: `Bạn có chắc muốn xoá voucher "${voucherCode}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
