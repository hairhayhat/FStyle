@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>Danh sách Voucher</h5>
                <form class="d-inline-flex">
                    <a href="{{ route('admin.vouchers.create') }}" class="align-items-center btn btn-theme">
                        <i data-feather="plus-square"></i> Thêm Voucher
                    </a>
                </form>
            </div>

            <!-- Voucher Table Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-desi">
                                    <table class="user-table table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th>Loại</th>
                                                <th>Giá trị</th>
                                                <th>Đơn tối thiểu</th>
                                                <th>Ngày bắt đầu</th>
                                                <th>Ngày hết hạn</th>
                                                <th>Lượt sử dụng</th>
                                                <th>Trạng thái</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vouchers as $voucher)
                                                <tr>
                                                    <td>{{ $voucher->code }}</td>
                                                    <td>
                                                        @if ($voucher->type === 'fixed')
                                                            <span class="badge badge-info">Cố định</span>
                                                        @else
                                                            <span class="badge badge-warning">Phần trăm</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($voucher->type === 'fixed')
                                                            {{ number_format($voucher->value, 0, ',', '.') }} đ
                                                        @else
                                                            {{ $voucher->value }}%
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($voucher->min_order_amount ?? 0, 0, ',', '.') }} đ
                                                    </td>
                                                    <td>{{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y H:i') : '---' }}
                                                    </td>
                                                    <td>{{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y H:i') : '---' }}
                                                    </td>
                                                    <td>{{ $voucher->usage_limit ?? '∞' }}</td>
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
                                                                <a href="{{ route('admin.vouchers.show', $voucher->id) }}">
                                                                    <span class="lnr lnr-eye"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}">
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
                                                    <td colspan="9" class="text-center text-muted py-4">
                                                        Chưa có voucher nào.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
            <!-- Voucher Table End -->
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
