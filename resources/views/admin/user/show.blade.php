@extends('admin.layouts.app')

@section('content')
<div class="page-body">
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold page-title">Chi tiết người dùng</h5>

            <!-- Thông tin cơ bản -->
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th style="width: 25%">Tên người dùng</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Ảnh người dùng</th>
                        <td>
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar" style="max-height:120px">
                            @else
                                <span class="text-muted">Chưa có ảnh</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>{{ $user->role->name ?? 'N/A' }}</td>
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

           <!-- Đánh giá gần đây -->
            <h6 class="fw-bold mt-4">Đánh giá gần đây</h6>
            @if($recentComments->isEmpty())
                <p class="text-muted">Chưa có đánh giá nào.</p>
            @else
                <div class="row">
                    @foreach($recentComments as $comment)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <strong>Bài viết/SP:</strong> 
                                        {{ $comment->commentable->title ?? 'N/A' }}
                                    </h6>
                                    
                                    <p class="card-text">
                                        <strong>Nội dung:</strong> {{ $comment->content }}
                                    </p>

                                    {{-- Hiển thị ảnh nếu có --}}
                                    @if(!empty($comment->media->file_path))
                                        <div class="mb-2">
                                            <img src="{{ asset('pu/' . $comment->media->file_path) }}" 
                                                alt="Ảnh đánh giá" 
                                                class="img-fluid rounded"
                                                style="max-height:150px; object-fit:cover;">
                                        </div>
                                    @endif

                                    <small class="text-muted">
                                        Viết lúc: {{ $comment->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            <!-- Đơn hàng gần đây -->
            <h6 class="fw-bold mt-4">Đơn hàng gần đây</h6>
            @if($user->orders->isEmpty())
                <p class="text-muted">Chưa có đơn hàng nào.</p>
            @else
                <div class="row mb-4">
                    @foreach($user->orders as $order)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <a href="{{ route('admin.order.detail', $order->code) }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-2">Mã đơn: #{{ $order->code }}</h6>
                                        <p class="mb-1"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ</p>
                                        <p class="mb-1"><strong>Trạng thái:</strong> {{ $order->status }}</p>
                                        <small class="text-muted">Ngày tạo: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif


            <!-- Các đơn đã giao -->
            <h6 class="fw-bold mt-4">Các đơn đã giao thành công</h6>
            @if($completedOrders->isEmpty())
                <p class="text-muted">Chưa có đơn thành công nào.</p>
            @else
                <div class="row">
                    @foreach($completedOrders as $order)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <strong>Mã đơn:</strong> #{{ $order->code }}
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Tổng tiền:</strong> 
                                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Ngày giao:</strong> 
                                        {{ $order->updated_at->format('d/m/Y') }}
                                    </p>
                                    <span class="badge bg-success">Đã giao thành công</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
