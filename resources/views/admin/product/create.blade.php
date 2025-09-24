@extends('admin.layouts.app') {{-- Kế thừa layout admin --}}

@section('content')
    <div class="page-body"> {{-- Nội dung trang thêm sản phẩm --}}
        <div class="title-header">
            <h5>Thêm sản phẩm</h5>
        </div>

        <div class="container-fluid">
            <form class="theme-form theme-form-2 mega-form" method="POST" action="{{ route('admin.product.store') }}" {{-- Form tạo sản phẩm --}}
                enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Cấu hình cho JS (route AJAX, id sản phẩm nếu có) --}}
                <div id="js-config" data-variant-store-route="{{ route('admin.product.variant.store') }}"
                    data-product-id="{{ isset($product) ? $product->id : '' }}"></div>

                <div class="row">
                    {{-- THÔNG TIN SẢN PHẨM: các trường cơ bản --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thông tin sản phẩm</h5>
                                </div>
                                {{-- Tên sản phẩm --}}
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

                                {{-- ẢNH CHÍNH: upload + preview --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Ảnh chính</label>
                                    <div class="col-sm-10">
                                        <div id="main-drop-area"
                                            class="border border-2 border-dashed rounded p-4 text-center">
                                            <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                            <input type="file" id="main-image-input" name="image" accept="image/*"
                                                class="d-none @error('image') is-invalid @enderror">
                                            <div class="mt-3">
                                                <img id="main-preview" alt="Preview" class="img-fluid d-none"
                                                    style="max-height: 200px; border-radius: 5px; border: 1px solid #ddd;">
                                            </div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Danh mục: select danh mục sản phẩm --}}
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

                                {{-- Mô tả: nội dung mô tả sản phẩm --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mô tả sản phẩm</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="editor" rows="4"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả chi tiết sản phẩm">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BIẾN THỂ SẢN PHẨM: màu, size, giá, số lượng --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Biến thể sản phẩm</h5>
                                </div>
                                <div id="variant-container">
                                    @php
                                        $oldVariants = old('variants', [
                                            ['color_id' => '', 'size_id' => '', 'price' => '', 'quantity' => ''],
                                        ]);
                                    @endphp

                                    @foreach ($oldVariants as $i => $variantOld)
                                        <div class="row mb-3 variant-row">
                                            <input type="hidden" name="variants[{{ $i }}][id]"
                                                class="variant-id" value="">

                                            {{-- Màu sắc: chọn color, hiện preview --}}
                                            <div class="col-md-3 d-flex flex-column">
                                                <label class="form-label">Màu sắc</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="variants[{{ $i }}][color_id]"
                                                        class="form-control color-select">
                                                        <option value="">-- Chọn màu --</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->id }}"
                                                                data-color="{{ $color->code }}"
                                                                {{ isset($variantOld['color_id']) && $variantOld['color_id'] == $color->id ? 'selected' : '' }}>
                                                                {{ $color->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @php
                                                        $previewColor =
                                                            $colors->firstWhere('id', $variantOld['color_id'])?->code ??
                                                            '#ffffff';
                                                    @endphp
                                                    <div class="color-preview rounded-circle border"
                                                        style="width:24px; height:24px; flex-shrink:0; background: {{ $previewColor }};">
                                                    </div>
                                                </div>
                                                @error("variants.{$i}.color_id")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Size: chọn size --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Size</label>
                                                <select name="variants[{{ $i }}][size_id]"
                                                    class="form-control size-select">
                                                    <option value="">Chọn size</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ isset($variantOld['size_id']) && $variantOld['size_id'] == $size->id ? 'selected' : '' }}>
                                                            {{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("variants.{$i}.size_id")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Giá nhập: import_price --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Giá nhập</label>
                                                <input type="text" name="variants[{{ $i }}][import_price]"
                                                    class="form-control import-price-input" placeholder="Giá nhập"
                                                    value="{{ $variantOld['import_price'] ?? '' }}">
                                                @error("variants.{$i}.import_price")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Giá bán: sale_price --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Giá bán</label>
                                                <input type="text" name="variants[{{ $i }}][sale_price]"
                                                    class="form-control sale-price-input" placeholder="Giá bán"
                                                    value="{{ $variantOld['sale_price'] ?? '' }}">
                                                @error("variants.{$i}.sale_price")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Số lượng: quantity tồn kho --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" name="variants[{{ $i }}][quantity]"
                                                    class="form-control quantity-input" placeholder="SL"
                                                    value="{{ $variantOld['quantity'] ?? '' }}">
                                                @error("variants.{$i}.quantity")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Xoá: loại bỏ biến thể khỏi form (chỉ UI) --}}
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger remove-variant w-100">X</button>
                                            </div>

                                            {{-- Lỗi trùng chung: thông báo khi trùng màu+size --}}
                                            <div class="col-12 mt-1">
                                                @if ($errors->has("variants.{$i}.color_id") && str_contains($errors->first("variants.{$i}.color_id"), 'trùng'))
                                                    <div class="text-danger small">Biến thể trùng màu + size.</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-outline-primary" id="add-variant">+ Thêm
                                            biến
                                            thể</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- THƯ VIỆN ẢNH: upload nhiều ảnh cho gallery --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thư viện ảnh</h5>
                                </div>
                                <div id="drop-area" class="border border-2 border-dashed rounded p-4 text-center">
                                    <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                    <input type="file" id="imageInput" name="gallery[]" accept="image/*"
                                        class="d-none @error('gallery') is-invalid @enderror" multiple>
                                    <div id="preview-container" class="preview-grid mt-3"></div>
                                </div>
                                @error('gallery')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Nút submit: gửi form tạo sản phẩm --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-end">
                                <button class="btn btn-primary px-4 py-2 fw-bold" type="submit">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts') {{-- JS xử lý CKEditor, thêm/xoá biến thể, preview ảnh --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === CKEditor ===
            if (document.querySelector('#editor')) {
                ClassicEditor.create(document.querySelector('#editor')).catch(console.error);
            }

            // === BIẾN THỂ SẢN PHẨM ===
            let index = 1;
            const container = document.getElementById('variant-container');
            const addBtn = document.getElementById('add-variant');

            // Hàm cập nhật màu preview
            function updateColorPreview(selectEl) {
                const selectedOption = selectEl.options[selectEl.selectedIndex];
                const colorCode = selectedOption?.getAttribute('data-color') || '#ffffff';
                const previewEl = selectEl.closest('.d-flex').querySelector('.color-preview');
                if (previewEl) {
                    previewEl.style.background = colorCode;
                }
            }

            // Gắn sự kiện cho tất cả select màu hiện có
            function attachColorSelectEvents(scope = document) {
                scope.querySelectorAll('.color-select').forEach(selectEl => {
                    selectEl.addEventListener('change', function() {
                        updateColorPreview(this);
                    });
                    updateColorPreview(selectEl); // chạy khi load để hiển thị sẵn màu
                });
            }
            attachColorSelectEvents(document);

            // Thêm biến thể mới
            addBtn.addEventListener('click', function() {
                const firstRow = container.querySelector('.variant-row');
                const newRow = firstRow.cloneNode(true);

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
                attachColorSelectEvents(newRow);
                index++;
            });

            // Xóa biến thể
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant')) {
                    const rows = document.querySelectorAll('.variant-row');
                    if (rows.length > 1) {
                        e.target.closest('.variant-row').remove();
                    }
                }
            });

            // Kiểm tra trùng biến thể
            document.querySelector('form').addEventListener('submit', function(e) {
                const variants = document.querySelectorAll('.variant-row');
                const combos = new Set();
                let isDuplicate = false;

                variants.forEach((row, idx) => {
                    const color = row.querySelector('select[name$="[color_id]"]')?.value || '';
                    const size = row.querySelector('select[name$="[size_id]"]')?.value || '';
                    if (!color || !size) return;
                    const key = `${color}-${size}`;
                    if (combos.has(key)) {
                        isDuplicate = true;
                        let dupElWrapper = row.querySelector('.duplicate-error');
                        if (!dupElWrapper) {
                            dupElWrapper = document.createElement('div');
                            dupElWrapper.className = 'text-danger small duplicate-error';
                            row.appendChild(dupElWrapper);
                        }
                        dupElWrapper.textContent = `Biến thể trùng màu + size tại dòng ${idx + 1}.`;
                    }
                    combos.add(key);
                });

                if (isDuplicate) e.preventDefault();
            });

            // === ẢNH CHÍNH ===
            const mainDropArea = document.getElementById('main-drop-area');
            const mainImageInput = document.getElementById('main-image-input');
            const mainPreview = document.getElementById('main-preview');

            function previewMainImage(file) {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    mainPreview.src = e.target.result;
                    mainPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }

            if (mainDropArea && mainImageInput) {
                mainDropArea.addEventListener('click', () => mainImageInput.click());
                mainImageInput.addEventListener('change', function() {
                    if (this.files[0]) previewMainImage(this.files[0]);
                });

                mainDropArea.addEventListener('dragover', e => {
                    e.preventDefault();
                    mainDropArea.classList.add('bg-light');
                });
                mainDropArea.addEventListener('dragleave', () => mainDropArea.classList.remove('bg-light'));
                mainDropArea.addEventListener('drop', e => {
                    e.preventDefault();
                    mainDropArea.classList.remove('bg-light');
                    if (e.dataTransfer.files[0]) {
                        mainImageInput.files = e.dataTransfer.files;
                        previewMainImage(e.dataTransfer.files[0]);
                    }
                });
            }
        });
    </script>
@endsection
