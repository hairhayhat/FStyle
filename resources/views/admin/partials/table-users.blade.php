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
                            <img src="{{asset('storage/' . $item->avatar)}}" alt="users">
                        </span>
                    </td>
                    <td>
                        <a href="javascript:void(0)">
                            <span class="d-block ">{{$item->name}}</span>
                        </a>
                    </td>

                    <td> {{$item->email}}</td>

                    <td>{{$item->phone}}</td>

                    <td>
                        @if ($item->email_verified_at == null)
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