<div class="table-responsive table-desi">
    <table class="user-table table table-striped">
        <thead>
            <tr>
                <th>Ảnh đại diện</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $item)
                <tr>
                    <td>
                        <span>
                            @if ($item->provider_id && $item->avatar)
                                <img src="{{ $item->avatar }}" alt="users">
                            @else
                                <img src="{{ asset('storage/avatar/' . $item->avatar) }}" alt="users">
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="javascript:void(0)">
                            <span class="d-block ">{{ $item->name }}</span>
                        </a>
                    </td>

                    <td> {{ $item->email }}</td>

                    <td>{{ $item->phone }}</td>

                    <td>
                        @if ($item->is_locked)
                            <span class="badge badge-danger">Tài khoản bị khóa</span>
                        @elseif ($item->email_verified_at == null)
                            <span class="text-danger">Chưa xác minh</span>
                        @else
                            <span class="text-success">Đã xác minh</span>
                        @endif
                    </td>

                    <td>
                        @if ($item->role_id == 1)
                            <span class="badge badge-primary">Quản trị viên</span>
                        @elseif ($item->role_id == 2)
                            <span class="badge badge-success">Người dùng</span>
                        @else
                            <span class="badge badge-info">Quản lý</span>
                        @endif
                    </td>
                    <td>
                        <ul>
                            <li>
                                <a href="{{ route('admin.users.show', $item->id) }}">
                                    <span class="lnr lnr-eye"></span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.users.edit', $item->id) }}">
                                    <span class="lnr lnr-pencil"></span>
                                </a>
                            </li>

                            @if ($item->role_id != 1)
                                <li>
                                    @if ($item->is_locked)
                                        <form action="{{ route('admin.users.unlock', $item->id) }}" method="POST"
                                            class="js-lock-form" data-action="unlock" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                title="Mở khóa tài khoản">
                                                <span class="lnr lnr-unlock"></span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.lock', $item->id) }}" method="POST"
                                            class="js-lock-form" data-action="lock" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Khóa tài khoản">
                                                <span class="lnr lnr-lock"></span>
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endif

                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $users->links('vendor.pagination.bootstrap-5') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-lock-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var action = form.getAttribute('data-action');
                var isLock = action === 'lock';
                if (typeof Swal === 'undefined') {
                    if (confirm(isLock ? 'Bạn có chắc chắn muốn khóa tài khoản này?' :
                            'Bạn có chắc chắn muốn mở khóa tài khoản này?')) {
                        form.submit();
                    }
                    return;
                }
                Swal.fire({
                    title: isLock ? 'Khóa tài khoản?' : 'Mở khóa tài khoản?',
                    text: isLock ? 'Tài khoản sẽ không thể mua hàng hoặc thêm giỏ.' :
                        'Tài khoản sẽ có thể mua hàng bình thường.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: isLock ? 'Khóa' : 'Mở khóa',
                    cancelButtonText: 'Hủy',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
