@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <!-- Page Sidebar Ends-->

        <!-- Container-fluid starts-->
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>All Users</h5>
                <form class="d-inline-flex">
                    <a href="add-new-user.html" class="align-items-center btn btn-theme">
                        <i data-feather="plus-square"></i>Add New
                    </a>
                </form>
            </div>
            <!-- All User Table Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive table-desi">
                                        <table class="user-table table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Ảnh đại diện</th>
                                                    <th>Tên</th>
                                                    <th>Email</th>
                                                    <th>Số điện thoại</th>
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
                                </div>
                            </div>

                            <div class="pagination-box">
                                {{ $users->links('pagination::bootstrap-4') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- All User Table Ends-->

        </div>
        <!-- Container-fluid end -->
    </div>

@endsection