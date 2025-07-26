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
                {{-- Hình ảnh (ảnh đại diện + thư viện) --}}
                <div class="col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            @php
                                $mainImage = $product->image;
                                $galleryImages = $product->galleries->pluck('image')->toArray();
                                $allImages = array_merge([$mainImage], $galleryImages);
                                $jsImages = json_encode(array_map(fn($img) => asset('storage/' . $img), $allImages));
                            @endphp

                            @if ($mainImage || count($galleryImages))
                                {{-- Ảnh chính --}}
                                <div class="position-relative overflow-hidden rounded"
                                    style="width: 100%; height: 350px; background-color: #f5f5f5;">
                                    <img id="mainImage" src="{{ asset('storage/' . $mainImage) }}" class="w-100 h-100"
                                        style="object-fit: contain;" alt="Ảnh chính">

                                    {{-- Nút chuyển trái --}}
                                    <button id="prevBtn"
                                        class="btn btn-white shadow position-absolute top-50 start-0 translate-middle-y d-flex align-items-center justify-content-center"
                                        style="border-radius: 50%; width: 40px; height: 40px; padding: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L6.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </button>

                                    {{-- Nút chuyển phải --}}
                                    <button id="nextBtn"
                                        class="btn btn-white shadow position-absolute top-50 end-0 translate-middle-y d-flex align-items-center justify-content-center"
                                        style="border-radius: 50%; width: 40px; height: 40px; padding: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 1 1-.708-.708L9.293 7 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Thumbnail --}}
                                <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                                    @foreach ($allImages as $index => $img)
                                        <img src="{{ asset('storage/' . $img) }}"
                                            onclick="switchImage('{{ asset('storage/' . $img) }}', this)"
                                            class="thumbnail-image rounded border"
                                            style="width: 64px; height: 64px; object-fit: cover; cursor: pointer; border: 2px solid #ccc;"
                                            alt="Thumbnail">
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Chưa có hình ảnh</p>
                            @endif
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
    </div>
@endsection
@section('scripts')
    <script>
        function switchImage(src) {
            document.getElementById('mainImage').src = src;
        }
        // Chuyển hình ảnh
        const allImages = {!! $jsImages !!};
        let currentIndex = 0;

        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail-image');

        function updateActiveThumbnail() {
            thumbnails.forEach((thumb, i) => {
                thumb.style.border = i === currentIndex ? '2px solid #333' : '2px solid #ccc';
            });
        }

        function switchImage(src, element = null) {
            mainImage.src = src;
            if (element) {
                currentIndex = [...thumbnails].indexOf(element);
                updateActiveThumbnail();
            }
        }

        document.getElementById('prevBtn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
            switchImage(allImages[currentIndex]);
            updateActiveThumbnail();
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % allImages.length;
            switchImage(allImages[currentIndex]);
            updateActiveThumbnail();
        });

        // Gán mặc định border ảnh đầu
        updateActiveThumbnail();
    </script>
@endsection
