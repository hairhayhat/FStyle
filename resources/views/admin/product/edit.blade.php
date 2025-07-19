@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Chỉnh sửa sản phẩm</h5>
        </div>

        <div class="container-fluid">
            <form class="theme-form theme-form-2 mega-form" method="POST"
                action="{{ route('admin.product.update', $product->id) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="row">

                    {{-- THÔNG TIN SẢN PHẨM --}}
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
                                            value="{{ old('name', $product->name) }}" placeholder="Tên sản phẩm">
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
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="mt-2"
                                                width="100">
                                        @endif
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
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BIẾN THỂ SẢN PHẨM --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Biến thể sản phẩm</h5>
                                </div>
                                <div id="variant-container">
                                    @foreach ($product->variants as $key => $variant)
                                        <div class="row mb-3 variant-row">
                                            <input type="hidden" name="variants[{{ $key }}][id]"
                                                value="{{ $variant->id }}">

                                            <div class="col-md-3">
                                                <label class="form-label">Màu sắc</label>
                                                <select name="variants[{{ $key }}][color_id]"
                                                    class="form-control">
                                                    <option value="">Chọn màu</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}"
                                                            {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                            {{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Size</label>
                                                <select name="variants[{{ $key }}][size_id]" class="form-control">
                                                    <option value="">Chọn size</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                                            {{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Giá</label>
                                                <input type="text" name="variants[{{ $key }}][price]"
                                                    class="form-control" value="{{ $variant->price }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" name="variants[{{ $key }}][quantity]"
                                                    class="form-control" value="{{ $variant->quantity }}">
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end">
                                                <form action="{{ route('admin.product-variant.destroy', $variant->id) }}"
                                                    method="POST" class="variant-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-danger btn-delete-variant">Xoá</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

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
                                    <i class="bi bi-save me-1"></i> Cập nhật sản phẩm
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let index = {{ count($product->variants) }};

                const container = document.getElementById('variant-container');
                const addBtn = document.getElementById('add-variant');

                addBtn.addEventListener('click', function() {
                    const firstRow = container.querySelector('.variant-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll('input, select').forEach(el => {
                        const oldName = el.getAttribute('name');
                        if (!oldName) return;

                        const newName = oldName.replace(/variants\[\d+\]/, `variants[${index}]`);
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

                // SweetAlert2 confirm delete variant
                document.querySelectorAll('.btn-delete-variant').forEach(button => {
                    button.addEventListener('click', function() {
                        const form = this.closest('form');
                        Swal.fire({
                            title: 'Bạn có chắc chắn?',
                            text: "Biến thể này sẽ bị xoá vĩnh viễn!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Xoá',
                            cancelButtonText: 'Huỷ'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
