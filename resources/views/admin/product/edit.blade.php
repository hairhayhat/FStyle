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

                                {{-- Tên --}}
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

                                {{-- Ảnh chính --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Ảnh chính</label>
                                    <div class="col-sm-10">
                                        <div id="main-drop-area"
                                            class="border border-2 border-dashed rounded p-4 text-center">
                                            <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                            <input type="file" id="main-image-input" name="image" accept="image/*"
                                                class="d-none @error('image') is-invalid @enderror">
                                            <img id="main-preview"
                                                src="{{ $product->image ? asset('storage/' . $product->image) : '#' }}"
                                                alt="Preview" class="img-fluid mt-3 {{ $product->image ? '' : 'd-none' }}"
                                                style="max-height: 200px;">
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Danh mục --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Danh mục</label>
                                    <div class="col-sm-10">
                                        <select name="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror select2">
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

                                {{-- Mô tả --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mô tả sản phẩm</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" class="editor form-control @error('description') is-invalid @enderror" rows="4"
                                            placeholder="Mô tả chi tiết sản phẩm">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
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
                                            {{-- Màu --}}
                                            <div class="col-md-3">
                                                <label class="form-label">Màu sắc</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="variants[{{ $key }}][color_id]"
                                                        class="form-control color-select select2">
                                                        <option value="">Chọn màu</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->id }}"
                                                                data-color="{{ $color->code }}"
                                                                {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                                {{ $color->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <span class="color-badge ms-2 d-inline-block rounded-circle"
                                                        style="width: 20px; height: 18px; border: 1px solid #ccc; background-color: {{ optional($colors->firstWhere('id', $variant->color_id))->code ?? '#fff' }}"></span>
                                                </div>
                                            </div>

                                            {{-- Size --}}
                                            <div class="col-md-2">
                                                <label class="form-label">Size</label>
                                                <select name="variants[{{ $key }}][size_id]"
                                                    class="form-control select2">
                                                    <option value="">Chọn size</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                                            {{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Giá --}}
                                            <div class="col-md-3">
                                                <label class="form-label">Giá</label>
                                                <input type="text" name="variants[{{ $key }}][price]"
                                                    class="form-control" value="{{ $variant->price }}">
                                            </div>

                                            {{-- Số lượng --}}
                                            <div class="col-md-2">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" name="variants[{{ $key }}][quantity]"
                                                    class="form-control" value="{{ $variant->quantity }}">
                                            </div>

                                            {{-- Xoá biến thể --}}
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm btn-danger delete-variant-btn"
                                                    data-variant-id="{{ $variant->id ?? '' }}">
                                                    Xoá
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-outline-primary" id="addVariantBtn">+ Thêm
                                            biến
                                            thể</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Thư viện ảnh --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thư viện ảnh sản phẩm</h5>
                                </div>
                                <div id="gallery-drop-area" class="border border-2 border-dashed rounded p-4 text-center">
                                    <p>Kéo thả ảnh vào đây hoặc bấm để chọn ảnh mới</p>
                                    <input type="file" id="gallery-image-input" name="gallery_new[]" accept="image/*"
                                        class="d-none" multiple>

                                    {{-- Hiển thị ảnh cũ, mỗi ảnh có nút xoá (đánh dấu để gửi về backend) --}}
                                    <div id="old-gallery-preview" class="d-flex flex-wrap gap-2 mt-3"
                                        style="max-height: 200px;">
                                        @if ($product->gallery)
                                            @foreach ($product->gallery as $img)
                                                <div class="position-relative old-image-wrapper"
                                                    style="width: 120px; height: 120px;">
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Ảnh cũ"
                                                        class="img-thumbnail"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                    {{-- Hidden input giữ lại ảnh nếu không xoá --}}
                                                    <input type="hidden" name="gallery_old[]"
                                                        value="{{ $img }}">
                                                    {{-- Nút xoá ảnh --}}
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-old-image"
                                                        style="z-index:10;">&times;</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    {{-- Preview ảnh mới upload --}}
                                    <div id="new-gallery-preview" class="d-flex flex-wrap gap-2 mt-3"
                                        style="max-height: 200px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Nút submit --}}
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
@endsection

@section('scripts')
    <script>
        // Hàm tạo HTML hiển thị màu trong select2
        function formatColorOption(state) {
            if (!state.id) return state.text;

            const colorCode = $(state.element).data('color');
            const $color = `
            <span style="display:inline-flex;align-items:center;">
                <span style="width:12px;height:12px;border-radius:50%;background-color:${colorCode};display:inline-block;margin-right:6px;border:1px solid #ccc;"></span>
                ${state.text}
            </span>
        `;
            return $color;
        }

        // Hiển thị badge màu bên cạnh select
        function initColorSelector(container) {
            const colorSelect = container.querySelector('.color-select');
            if (!colorSelect) return;

            const selectedOption = colorSelect.options[colorSelect.selectedIndex];
            const colorBadge = container.querySelector('.color-badge');

            if (selectedOption) {
                const colorCode = selectedOption.getAttribute('data-color');
                if (colorCode && colorBadge) {
                    colorBadge.style.backgroundColor = colorCode;
                    colorBadge.classList.remove('d-none');
                } else if (colorBadge) {
                    colorBadge.classList.add('d-none');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            let index = document.querySelectorAll('.variant-row').length;
            const container = document.getElementById('variant-container');
            const addBtn = document.getElementById('addVariantBtn');

            // Khởi tạo Select2 cho các select ban đầu
            $('.select2').each(function() {
                if ($(this).hasClass('color-select')) {
                    $(this).select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: "Chọn...",
                        templateResult: formatColorOption,
                        templateSelection: formatColorOption,
                        escapeMarkup: markup => markup
                    });
                } else {
                    $(this).select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: "Chọn..."
                    });
                }
            });

            // Gán badge màu khi chọn màu
            $('.color-select').on('change', function() {
                const colorCode = $(this).find(':selected').data('color');
                const badge = $(this).closest('.variant-row').find('.color-badge');
                if (colorCode) {
                    badge.css('background-color', colorCode).removeClass('d-none');
                } else {
                    badge.addClass('d-none');
                }
            });

            // Xử lý thêm dòng biến thể
            addBtn.addEventListener('click', function() {
                const firstRow = container.querySelector('.variant-row');

                // 1. Hủy Select2 trên dòng gốc
                $(firstRow).find('select.select2').select2('destroy');

                // 2. Clone dòng
                const newRow = firstRow.cloneNode(true);

                // 3. Reset input, update name
                newRow.querySelectorAll('input, select').forEach(el => {
                    const oldName = el.getAttribute('name');
                    if (!oldName) return;

                    const newName = oldName.replace(/variants\[\d+\]/, `variants[${index}]`);
                    el.setAttribute('name', newName);
                    el.classList.remove('is-invalid');

                    if (el.tagName === 'INPUT') {
                        el.value = '';
                    } else if (el.tagName === 'SELECT') {
                        $(el).val(null).trigger('change');
                    }
                });

                // 4. Gắn dòng mới
                container.appendChild(newRow);

                // 5. Reinit select2
                $(newRow).find('select.select2').each(function() {
                    if ($(this).hasClass('color-select')) {
                        $(this).select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: "Chọn...",
                            templateResult: formatColorOption,
                            templateSelection: formatColorOption,
                            escapeMarkup: markup => markup
                        });
                    } else {
                        $(this).select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: "Chọn..."
                        });
                    }
                });

                // 6. Badge màu
                initColorSelector(newRow);

                // 7. Gán sự kiện badge khi chọn lại màu
                $(newRow).find('.color-select').on('change', function() {
                    const colorCode = $(this).find(':selected').data('color');
                    const badge = $(this).closest('.variant-row').find('.color-badge');
                    if (colorCode) {
                        badge.css('background-color', colorCode).removeClass('d-none');
                    } else {
                        badge.addClass('d-none');
                    }
                });

                index++;
            });

            // Xử lý nút xoá dòng
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant-btn')) {
                    const row = e.target.closest('.variant-row');
                    if (container.querySelectorAll('.variant-row').length > 1) {
                        row.remove();
                    } else {
                        alert('Phải có ít nhất 1 biến thể.');
                    }
                }
            });
        });
        $(document).on('click', '.delete-variant-btn', function() {
            const btn = $(this);
            const variantId = btn.data('variant-id');

            // Nếu có ID → gọi AJAX xoá trong CSDL
            if (variantId) {
                if (!confirm('Bạn có chắc chắn muốn xoá biến thể này?')) return;

                $.ajax({
                    url: `/admin/product-variants/${variantId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // Xoá khỏi giao diện
                        btn.closest('.variant-row').remove();

                        // Thông báo
                        alert('Đã xoá biến thể thành công!');
                    },
                    error: function(xhr) {
                        alert('Không thể xoá biến thể!');
                    }
                });
            } else {
                // Nếu không có ID (dòng vừa thêm) → chỉ xoá dòng giao diện
                btn.closest('.variant-row').remove();
            }
        });
    </script>
@endsection
