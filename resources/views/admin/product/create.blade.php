@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Thêm sản phẩm</h5>
        </div>

        <div class="container-fluid">
            <form class="theme-form theme-form-2 mega-form" method="POST" action="{{ route('admin.product.store') }}"
                enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    {{-- CARD 1: THÔNG TIN SẢN PHẨM --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thông tin sản phẩm</h5>
                                </div>
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Tên sản phẩm</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="Tên sản phẩm">
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Hình ảnh</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="image"
                                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Danh mục</label>
                                    <div class="col-sm-10">
                                        <select name="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mô tả sản phẩm</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="editor" rows="4"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả chi tiết sản phẩm">{{ old('description', $product->description ?? '') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: BIẾN THỂ SẢN PHẨM --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Biến thể sản phẩm</h5>
                                </div>
                                <div id="variant-container">
                                    <div class="row mb-3 variant-row">
                                        {{-- Màu sắc --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Màu sắc</label>
                                            <select name="variants[0][color_id]"
                                                class="form-control @error('variants.0.color_id') is-invalid @enderror">
                                                <option value="">Chọn màu</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}"
                                                        {{ old('variants.0.color_id') == $color->id ? 'selected' : '' }}>
                                                        {{ $color->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('variants.0.color_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Size --}}
                                        <div class="col-md-2">
                                            <label class="form-label">Size</label>
                                            <select name="variants[0][size_id]"
                                                class="form-control @error('variants.0.size_id') is-invalid @enderror">
                                                <option value="">Chọn size</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}"
                                                        {{ old('variants.0.size_id') == $size->id ? 'selected' : '' }}>
                                                        {{ $size->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('variants.0.size_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Giá --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Giá</label>
                                            <input type="text" name="variants[0][price]"
                                                class="form-control @error('variants.0.price') is-invalid @enderror"
                                                placeholder="Giá" value="{{ old('variants.0.price') }}">
                                            @error('variants.0.price')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Số lượng --}}
                                        <div class="col-md-2">
                                            <label class="form-label">Số lượng</label>
                                            <input type="number" name="variants[0][quantity]"
                                                class="form-control @error('variants.0.quantity') is-invalid @enderror"
                                                placeholder="SL" value="{{ old('variants.0.quantity') }}">
                                            @error('variants.0.quantity')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Nút xoá --}}
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-variant">Xoá</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Nút thêm biến thể --}}
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-outline-primary" id="add-variant">+ Thêm biến
                                            thể</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-end">
                                <button class="btn btn-primary btn-lg px-4 py-2 fw-bold" type="submit">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
                                </button>
                                <a href="{{ route('admin.product.index') }}"
                                    class="btn btn-outline-dark btn-lg px-4 py-2 fw-bold me-2">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let index = 1;

            const container = document.getElementById('variant-container');
            const addBtn = document.getElementById('add-variant');

            addBtn.addEventListener('click', function() {
                const firstRow = container.querySelector('.variant-row');
                const newRow = firstRow.cloneNode(true);

                // Reset input và cập nhật name với index mới
                newRow.querySelectorAll('input, select').forEach(el => {
                    const oldName = el.getAttribute('name');
                    if (!oldName) return;
                    const newName = oldName.replace(/\d+/, index);
                    el.setAttribute('name', newName);
                    el.classList.remove('is-invalid');

                    if (el.tagName === 'INPUT') {
                        el.value = '';
                    } else if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    }
                });

                container.appendChild(newRow);
                index++;
            });

            // Xoá biến thể
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant')) {
                    const rows = document.querySelectorAll('.variant-row');
                    if (rows.length > 1) {
                        e.target.closest('.variant-row').remove();
                    }
                }
            });

            // Kiểm tra trùng biến thể trước khi submit
            document.querySelector('form').addEventListener('submit', function(e) {
                const variants = document.querySelectorAll('.variant-row');
                const combos = new Set();
                let isDuplicate = false;

                variants.forEach((row, idx) => {
                    const color = row.querySelector('select[name$="[color_id]"]').value;
                    const size = row.querySelector('select[name$="[size_id]"]').value;
                    const key = `${color}-${size}`;
                    if (combos.has(key)) {
                        isDuplicate = true;
                        alert(`❌ Biến thể dòng ${idx + 1} bị trùng màu và size!`);
                    }
                    combos.add(key);
                });

                if (isDuplicate) {
                    e.preventDefault();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection
