@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>Danh sách bình luận</h5>
            </div>

            <!-- Comment List Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-desi">
                                    <table class="user-table table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Người dùng</th>
                                                <th>Sản phẩm</th>
                                                <th>Nội dung</th>
                                                <th>Rating</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày tạo</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($comments as $comment)
                                                <tr>
                                                    <td>{{ $comment->user->name ?? $comment->name }}</td>
                                                    <td>{{ $comment->product->name ?? 'Đã xóa' }}</td>
                                                    <td>{{ Str::limit($comment->content, 50) }}</td>
                                                    <td>{{ $comment->rating }} ★</td>
                                                    <td> <span class="status-text" data-id="{{ $comment->id }}">
                                                            {{ $comment->status ? 'Hiển thị' : 'Ẩn' }}
                                                        </span></td>
                                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <ul style="list-style:none; padding:0; display:flex; gap:10px;">

                                                            <div class="form-check form-switch mt-2">
                                                                <input class="form-check-input toggle-status"
                                                                    type="checkbox" role="switch"
                                                                    data-id="{{ $comment->id }}"
                                                                    id="switchCheck{{ $comment->id }}"
                                                                    {{ $comment->status ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="switchCheck{{ $comment->id }}">
                                                                </label>
                                                            </div>

                                                        </ul>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-box">
                                {{ $comments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Comment List End -->
        </div>
    </div>
@endsection
