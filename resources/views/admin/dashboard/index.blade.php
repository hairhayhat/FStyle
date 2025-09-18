@extends('admin.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Dashboard Thống Kê</h4>
                                <p class="text-muted mb-0">Tổng quan về hoạt động kinh doanh</p>
                            </div>
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0">Từ ngày:</label>
                                    <input type="date" name="from_date" 
                                           class="form-control form-control-sm" 
                                           value="{{ request('from_date') ?? now()->subYear()->format('Y-m-d') }}">
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0">Đến ngày:</label>
                                    <input type="date" name="to_date" 
                                           class="form-control form-control-sm" 
                                           value="{{ request('to_date') ?? now()->format('Y-m-d') }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter me-1"></i> Lọc
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng Doanh Thu
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_revenue']) }}₫
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Tổng Đơn Hàng
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_orders']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Khách Hàng Mới
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_customers']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Giá Trị Đơn Hàng TB
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['avg_order_value']) }}₫
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê bổ sung -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Tổng Sản Phẩm
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_products']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng Danh Mục
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $overviewStats['total_categories'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Giá Trị Tồn Kho
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_inventory_value']) }}₫
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Tổng Bình Luận
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($overviewStats['total_comments']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ chính -->
        <div class="row mb-4">
            <!-- Doanh thu theo ngày -->
            <div class="col-xl-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Doanh Thu & Đơn Hàng Theo Ngày</h6>
                    </div>
                    <div class="card-body">
                        <div id="dailyChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top sản phẩm bán chạy -->
            <div class="col-xl-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Sản Phẩm Bán Chạy</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Đã Bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Str::limit($product->name, 20) }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ thống kê -->
        <div class="row mb-4">
            <!-- Doanh thu theo tháng -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Doanh Thu Theo Tháng</h6>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng theo tháng -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Đơn Hàng Theo Tháng</h6>
                    </div>
                    <div class="card-body">
                        <div id="ordersChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ phân tích -->
        <div class="row mb-4">
            <!-- Trạng thái đơn hàng -->
            <div class="col-xl-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trạng Thái Đơn Hàng</h6>
                    </div>
                    <div class="card-body">
                        <div id="orderStatusChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Phương thức thanh toán -->
            <div class="col-xl-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Phương Thức Thanh Toán</h6>
                    </div>
                    <div class="card-body">
                        <div id="paymentChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Khách hàng theo tháng -->
            <div class="col-xl-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Khách Hàng Theo Tháng</h6>
                    </div>
                    <div class="card-body">
                        <div id="customersChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Các bảng thống kê chi tiết -->
        <div class="row mb-4">
            <!-- Đơn hàng gần đây -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Đơn Hàng Gần Đây</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mã ĐH</th>
                                        <th>Khách Hàng</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($order->total_amount) }}₫</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khách hàng mới -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Khách Hàng Mới</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Ngày ĐK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCustomers as $customer)
                                    <tr>
                                        <td>#{{ $customer->id }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng sản phẩm và bình luận -->
        <div class="row mb-4">
            <!-- Sản phẩm mới -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sản Phẩm Mới</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Danh Mục</th>
                                        <th>Ngày Tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                    <tr>
                                        <td>#{{ $product->id }}</td>
                                        <td>{{ Str::limit($product->name, 25) }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bình luận mới -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bình Luận Mới</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách Hàng</th>
                                        <th>Sản Phẩm</th>
                                        <th>Đánh Giá</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentComments as $comment)
                                    <tr>
                                        <td>#{{ $comment->id }}</td>
                                        <td>{{ $comment->user->name ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($comment->product->name ?? 'N/A', 20) }}</td>
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $comment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê theo danh mục và màu sắc -->
        <div class="row mb-4">
            <!-- Thống kê theo danh mục -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống Kê Theo Danh Mục</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Danh Mục</th>
                                        <th>Sản Phẩm</th>
                                        <th>Đã Bán</th>
                                        <th>Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryTable as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->total_products }}</td>
                                        <td>{{ $category->total_sold }}</td>
                                        <td>{{ number_format($category->total_revenue) }}₫</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê theo màu sắc -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống Kê Theo Màu Sắc</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Màu</th>
                                        <th>Mã Màu</th>
                                        <th>Đã Bán</th>
                                        <th>Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($colorStats as $color)
                                    <tr>
                                        <td>{{ $color->name }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $color->code }}; color: white;">
                                                {{ $color->code }}
                                            </span>
                                        </td>
                                        <td>{{ $color->total_sold }}</td>
                                        <td>{{ number_format($color->total_revenue) }}₫</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê theo kích thước và tồn kho -->
        <div class="row mb-4">
            <!-- Thống kê theo kích thước -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống Kê Theo Kích Thước</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kích Thước</th>
                                        <th>Đã Bán</th>
                                        <th>Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizeStats as $size)
                                    <tr>
                                        <td>{{ $size->name }}</td>
                                        <td>{{ $size->total_sold }}</td>
                                        <td>{{ number_format($size->total_revenue) }}₫</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top sản phẩm đánh giá cao -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Sản Phẩm Đánh Giá Cao</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sản Phẩm</th>
                                        <th>Danh Mục</th>
                                        <th>Đánh Giá TB</th>
                                        <th>Số Đánh Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productRatings as $product)
                                    <tr>
                                        <td>{{ Str::limit($product->name, 20) }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $product->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            ({{ number_format($product->avg_rating, 1) }})
                                        </td>
                                        <td>{{ $product->total_comments }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ thống kê theo giờ -->
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống Kê Theo Giờ Trong Ngày</h6>
                    </div>
                    <div class="card-body">
                        <div id="hourlyChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng tồn kho -->
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tồn Kho Sản Phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sản Phẩm</th>
                                        <th>Màu</th>
                                        <th>Kích Thước</th>
                                        <th>Tồn Kho</th>
                                        <th>Giá Nhập</th>
                                        <th>Giá Bán</th>
                                        <th>Đã Bán</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryTable as $variant)
                                    <tr>
                                        <td>{{ Str::limit($variant->product->name, 25) }}</td>
                                        <td>{{ $variant->color->name ?? 'N/A' }}</td>
                                        <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $variant->quantity > 10 ? 'success' : ($variant->quantity > 0 ? 'warning' : 'danger') }}">
                                                {{ $variant->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($variant->import_price) }}₫</td>
                                        <td>{{ number_format($variant->sale_price) }}₫</td>
                                        <td>{{ $variant->total_sold }}</td>
                                        <td>
                                            @if($variant->quantity == 0)
                                                <span class="badge badge-danger">Hết hàng</span>
                                            @elseif($variant->quantity <= 5)
                                                <span class="badge badge-warning">Sắp hết</span>
                                            @else
                                                <span class="badge badge-success">Còn hàng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Dữ liệu từ controller
const dailyChart = @json($dailyChart);
const revenueChart = @json($revenueChart);
const ordersChart = @json($ordersChart);
const orderStatusStats = @json($orderStatusStats);
const paymentStats = @json($paymentStats);
const customersChart = @json($customersChart);
const hourlyTable = @json($hourlyTable);

// Biểu đồ doanh thu theo ngày
function initDailyChart() {
    const options = {
        series: [{
            name: 'Doanh thu',
            data: dailyChart.revenues,
            type: 'area'
        }, {
            name: 'Số đơn hàng',
            data: dailyChart.orders,
            type: 'line'
        }],
        chart: {
            height: 400,
            type: 'line',
            toolbar: {
                show: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: [3, 2]
        },
        colors: ["#4e73df", "#e74a3b"],
        xaxis: {
            categories: dailyChart.dates,
            labels: {
                format: 'dd/MM'
            }
        },
        tooltip: {
            y: [{
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(value);
                }
            }, {
                formatter: function(value) {
                    return value + ' đơn hàng';
                }
            }]
        },
        fill: {
            type: ['gradient', 'solid'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.2
            }
        },
        legend: {
            position: 'top'
        }
    };

    new ApexCharts(document.querySelector("#dailyChart"), options).render();
}

// Biểu đồ doanh thu theo tháng
function initRevenueChart() {
    const options = {
        series: [{
            name: 'Doanh thu',
            data: revenueChart.revenues
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#4e73df'],
        xaxis: {
            categories: revenueChart.months
        },
        yaxis: {
            title: {
                text: 'Doanh thu (VNĐ)'
            },
            labels: {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        notation: 'compact'
                    }).format(value);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(value);
                }
            }
        }
    };

    new ApexCharts(document.querySelector("#revenueChart"), options).render();
}

// Biểu đồ đơn hàng theo tháng
function initOrdersChart() {
    const options = {
        series: [{
            name: 'Đơn hàng',
            data: ordersChart.orders
        }],
        chart: {
            type: 'line',
            height: 300
        },
        colors: ['#1cc88a'],
        xaxis: {
            categories: ordersChart.months
        },
        yaxis: {
            title: {
                text: 'Số đơn hàng'
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + ' đơn hàng';
                }
            }
        }
    };

    new ApexCharts(document.querySelector("#ordersChart"), options).render();
}

// Biểu đồ trạng thái đơn hàng
function initOrderStatusChart() {
    const statuses = Object.keys(orderStatusStats.counts);
    const counts = Object.values(orderStatusStats.counts);
    
    const options = {
        series: counts,
        chart: {
            type: 'donut',
            height: 300
        },
        labels: statuses,
        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            }
        }
    };

    new ApexCharts(document.querySelector("#orderStatusChart"), options).render();
}

// Biểu đồ phương thức thanh toán
function initPaymentChart() {
    const methods = Object.keys(paymentStats.counts);
    const counts = Object.values(paymentStats.counts);
    
    const options = {
        series: counts,
        chart: {
            type: 'pie',
            height: 300
        },
        labels: methods,
        colors: ['#4e73df', '#1cc88a'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            }
        }
    };

    new ApexCharts(document.querySelector("#paymentChart"), options).render();
}

// Biểu đồ khách hàng theo tháng
function initCustomersChart() {
    const options = {
        series: [{
            name: 'Khách hàng mới',
            data: customersChart.customers
        }],
        chart: {
            type: 'area',
            height: 300
        },
        colors: ['#36b9cc'],
        xaxis: {
            categories: customersChart.months
        },
        yaxis: {
            title: {
                text: 'Số khách hàng'
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + ' khách hàng';
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.2
            }
        }
    };

    new ApexCharts(document.querySelector("#customersChart"), options).render();
}

// Biểu đồ thống kê theo giờ trong ngày
function initHourlyChart() {
    const hours = hourlyTable.map(item => item.hour);
    const orders = hourlyTable.map(item => item.orders);
    const revenues = hourlyTable.map(item => item.revenue);
    const avgOrderValues = hourlyTable.map(item => item.avg_order_value);

    const options = {
        series: [{
            name: 'Số đơn hàng',
            data: orders,
            type: 'column'
        }, {
            name: 'Doanh thu',
            data: revenues,
            type: 'line',
            yAxis: 1
        }, {
            name: 'Giá trị đơn hàng TB',
            data: avgOrderValues,
            type: 'area',
            yAxis: 2
        }],
        chart: {
            height: 400,
            type: 'line',
            toolbar: {
                show: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: [0, 3, 2]
        },
        colors: ['#4e73df', '#1cc88a', '#f6c23e'],
        xaxis: {
            categories: hours,
            title: {
                text: 'Giờ trong ngày'
            }
        },
        yaxis: [{
            title: {
                text: 'Số đơn hàng'
            },
            labels: {
                formatter: function(value) {
                    return Math.round(value);
                }
            }
        }, {
            opposite: true,
            title: {
                text: 'Doanh thu (VNĐ)'
            },
            labels: {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        notation: 'compact'
                    }).format(value);
                }
            }
        }, {
            opposite: true,
            title: {
                text: 'Giá trị đơn hàng TB (VNĐ)'
            },
            labels: {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        notation: 'compact'
                    }).format(value);
                }
            }
        }],
        tooltip: {
            y: [{
                formatter: function(value) {
                    return value + ' đơn hàng';
                }
            }, {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(value);
                }
            }, {
                formatter: function(value) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(value);
                }
            }]
        },
        fill: {
            type: ['solid', 'gradient', 'gradient'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.2,
                stops: [0, 100]
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        plotOptions: {
            bar: {
                columnWidth: '60%'
            }
        }
    };

    new ApexCharts(document.querySelector("#hourlyChart"), options).render();
}

// Khởi tạo tất cả biểu đồ
document.addEventListener('DOMContentLoaded', function() {
    initDailyChart();
    initRevenueChart();
    initOrdersChart();
    initOrderStatusChart();
    initPaymentChart();
    initCustomersChart();
    initHourlyChart();
});
</script>
@endsection