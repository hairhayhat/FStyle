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

                {{-- config cho JS --}}
                <div id="js-config" data-variant-store-route="{{ route('admin.product.variant.store') }}"
                    data-product-id="{{ $product->id }}">
                </div>

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

                                {{-- ẢNH CHÍNH --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Ảnh chính</label>
                                    <div class="col-sm-10">
                                        <div id="main-drop-area"
                                            class="border border-2 border-dashed rounded p-4 text-center">
                                            <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                            <input type="file" id="main-image-input" name="image" accept="image/*"
                                                class="d-none @error('image') is-invalid @enderror">
                                            <div class="mt-3">
                                                <img id="main-preview"
                                                    src="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                                    alt="Preview" class="img-fluid {{ $product->image ? '' : 'd-none' }}"
                                                    style="max-height: 200px; border-radius: 5px; border: 1px solid #ddd;">
                                            </div>
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

                                {{-- Mô tả --}}
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-2 mb-0">Mô tả sản phẩm</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="editor" rows="4"
                                            class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả chi tiết sản phẩm">{{ old('description', $product->description) }}</textarea>
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
                                    @php
                                        $oldVariants =
                                            old('variants') ??
                                            ($product->variants?->toArray() ?? [
                                                ['color_id' => '', 'size_id' => '', 'price' => '', 'quantity' => ''],
                                            ]);
                                    @endphp

                                    @foreach ($oldVariants as $i => $variantOld)
                                        <div class="row mb-3 variant-row">
                                            <input type="hidden" name="variants[{{ $i }}][id]"
                                                class="variant-id" value="{{ $variantOld['id'] ?? '' }}">

                                            {{-- Màu sắc --}}
                                            <div class="col-md-3 d-flex flex-column">
                                                <label class="form-label">Màu sắc</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="variants[{{ $i }}][color_id]"
                                                        class="form-control color-select">
                                                        <option value="">-- Chọn màu --</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->id }}"
                                                                data-color="{{ $color->code }}"
                                                                {{ ($variantOld['color_id'] ?? null) == $color->id ? 'selected' : '' }}>
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

                                            {{-- Size --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Size</label>
                                                <select name="variants[{{ $i }}][size_id]"
                                                    class="form-control size-select">
                                                    <option value="">Chọn size</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ ($variantOld['size_id'] ?? null) == $size->id ? 'selected' : '' }}>
                                                            {{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("variants.{$i}.size_id")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Giá --}}
                                            <div class="col-md-3 d-flex flex-column">
                                                <label class="form-label">Giá</label>
                                                <input type="text" name="variants[{{ $i }}][price]"
                                                    class="form-control price-input" placeholder="Giá"
                                                    value="{{ $variantOld['price'] ?? '' }}">
                                                @error("variants.{$i}.price")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Số lượng --}}
                                            <div class="col-md-2 d-flex flex-column">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" name="variants[{{ $i }}][quantity]"
                                                    class="form-control quantity-input" placeholder="SL"
                                                    value="{{ $variantOld['quantity'] ?? '' }}">
                                                @error("variants.{$i}.quantity")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Xoá --}}
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger remove-variant">Xoá</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-outline-primary" id="add-variant">+ Thêm
                                            biến thể</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- THƯ VIỆN ẢNH --}}
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
                                    <div id="preview-container" class="preview-grid mt-3">
                                        @if ($product->gallery)
                                            @foreach ($product->gallery as $img)
                                                <img src="{{ asset('storage/' . $img->path) }}" style="max-height:100px"
                                                    class="me-2 mb-2">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @error('gallery')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Nút submit --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-end">
                                <button class="btn btn-primary px-4 py-2 fw-bold" type="submit">
                                    <i class="bi bi-save me-1"></i> Cập nhật sản phẩm
                                </button>
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
            if (document.querySelector('#editor')) {
                ClassicEditor.create(document.querySelector('#editor')).catch(console.error);
            }

            let index = {{ count($oldVariants) }};
            const container = document.getElementById('variant-container');
            const addBtn = document.getElementById('add-variant');

            function updateColorPreview(selectEl) {
                const selectedOption = selectEl.options[selectEl.selectedIndex];
                const colorCode = selectedOption?.getAttribute('data-color') || '#ffffff';
                const previewEl = selectEl.closest('.d-flex').querySelector('.color-preview');
                if (previewEl) previewEl.style.background = colorCode;
            }

            function attachColorSelectEvents(scope = document) {
                scope.querySelectorAll('.color-select').forEach(selectEl => {
                    selectEl.addEventListener('change', function() {
                        updateColorPreview(this);
                    });
                    updateColorPreview(selectEl);
                });
            }
            attachColorSelectEvents(document);

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

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variant')) {
                    const rows = document.querySelectorAll('.variant-row');
                    if (rows.length > 1) e.target.closest('.variant-row').remove();
                }
            });

            // Ảnh chính
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

            // Thư viện ảnh
            const dropArea = document.getElementById('drop-area');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('preview-container');
            let suppressNextGalleryChange = false;

            function handleGalleryFiles(files) {
                previewContainer.innerHTML = '';
                Array.from(files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxHeight = '100px';
                        img.classList.add('me-2', 'mb-2');
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (dropArea && imageInput) {
                dropArea.addEventListener('click', () => imageInput.click());
                imageInput.addEventListener('change', function() {
                    if (suppressNextGalleryChange) {
                        suppressNextGalleryChange = false;
                        return;
                    }
                    handleGalleryFiles(this.files);
                });

                dropArea.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropArea.classList.add('bg-light');
                });
                dropArea.addEventListener('dragleave', () => dropArea.classList.remove('bg-light'));
                dropArea.addEventListener('drop', e => {
                    e.preventDefault();
                    dropArea.classList.remove('bg-light');
                    if (e.dataTransfer.files.length > 0) {
                        suppressNextGalleryChange = true;
                        imageInput.files = e.dataTransfer.files;
                        handleGalleryFiles(e.dataTransfer.files);
                    }
                });
            }
        });
    </script>
@endsection
