@extends('admin.layouts.app')
@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Sửa người dùng</h5>
        </div>

        <form action="{{route('admin.users.update', $user->id)}}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">
            @csrf
            

            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-2 mb-0">Tên người dùng</label>
                <div class="col-sm-10">
                    <input class="form-control" name="name" type="text" value="{{ old('name', $user->name) }}" readonly>
                </div>
            </div>
            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-2 mb-0">Vai trò</label>
                <div class="col-sm-10">
                    <select name="role_id" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->id == old('role_id', $user->role_id) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
@endsection