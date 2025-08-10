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
                                                                      <span class="badge badge-secondary">Người dùng</span>
                                                                      @else
                                                                      <span class="badge badge-info">Quản lý</span>
                                                                      @endif
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                <li>
                                                                    <a href="order-detail.html">
                                                                        <span class="lnr lnr-eye"></span>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="{{ route('admin.users.edit', $item->id) }}">
                                                                        <span class="lnr lnr-pencil"></span>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="javascript:void(0)">
                                                                        <span class="lnr lnr-trash"></span>
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
                                    <nav class="ms-auto me-auto " aria-label="...">
                                        <ul class="pagination pagination-primary">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:void(0)">Previous</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="javascript:void(0)">1 </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- All User Table Ends-->

                <div class="container-fluid">
                    <!-- footer start-->
                    <footer class="footer">
                        <div class="row">
                            <div class="col-md-12 footer-copyright text-center">
                                <p class="mb-0">Copyright 2021 © Voxo theme by pixelstrap</p>
                            </div>
                        </div>
                    </footer>
                    <!-- footer end-->
                </div>
            </div>
            <!-- Container-fluid end -->
        </div>
    
@endsection