@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5>Chi tiết sản phẩm</h5>
            <a href="{{ route('admin.product.index') }}" class="btn btn-outline-dark">← Quay lại</a>
        </div>

        <div class="container-fluid mt-3">
            <div class="row">
                {{-- Thông tin sản phẩm --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4>{{ $product->name }}</h4>
                            <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa có' }}</p>
                            <p><strong>Lượt xem:</strong> {{ $product->views ?? 0 }}</p>
                            <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>

                            {{-- Mô tả sản phẩm --}}
                            @if (!empty($product->description))
                                <hr>
                                <p><strong>Mô tả sản phẩm:</strong></p>
                                <div>{!! $product->description !!}</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Hình ảnh --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded"
                                    style="max-height: 300px" alt="{{ $product->name }}">
                            @else
                                <p>Chưa có hình ảnh</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biến thể --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Biến thể</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Màu</th>
                                <th>Hiển thị</th>
                                <th>Size</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->color->name ?? 'N/A' }}</td>
                                    <td>
                                        <div
                                            style="width: 25px; height: 25px; background: {{ $variant->color->code ?? '#ccc' }}; border: 1px solid #ccc;">
                                        </div>
                                    </td>
                                    <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($variant->price) }}₫</td>
                                    <td>{{ $variant->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có biến thể</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
