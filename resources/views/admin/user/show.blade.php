{{-- @extends('admin.layouts.app')
@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Chi tiết người dùng</h5>
        </div>

        @foreach($user->getAttributes() as $key => $value)
            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-2 mb-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">
                        {{ is_null($value) ? 'Không có dữ liệu' : $value }}
                    </p>
                </div>
            </div>
        @endforeach

        <div class="row">
            <div class="offset-sm-2 col-sm-10">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
@endsection --}}
@extends('admin.layouts.app')
@section('content')
<div class="page-body">
    <div class="title-header mb-4">
        <h5 class="fw-bold">Chi tiết người dùng</h5>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 25%">Tên người dùng</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th style="width: 25%">Ảnh người dùng</th>
                        <td><img src="{{asset('storage/' . $user->avatar)}}" alt="users"></td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>{{  $user->role->name  }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật gần nhất</th>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

