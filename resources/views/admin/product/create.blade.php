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
                                            value="{{ old('name') }}" placeholder="Tên sản phẩm">
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
                                            <img id="main-preview" src="#" alt="Preview"
                                                class="img-fluid mt-3 d-none" style="max-height: 200px;">
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
                                            class="form-control select2 @error('category_id') is-invalid @enderror">
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

                                {{-- Mô tả --}}
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

                    {{-- BIẾN THỂ SẢN PHẨM --}}
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
                                            <select name="variants[0][color_id]" class="form-control select2 color-select">
                                                <option value="">-- Chọn màu --</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->id }}" data-color="{{ $color->code }}">
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
                                            <select name="variants[0][size_id]" class="form-control select2">
                                                <option value="">Chọn size</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Giá --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Giá</label>
                                            <input type="text" name="variants[0][price]" class="form-control"
                                                placeholder="Giá">
                                        </div>

                                        {{-- Số lượng --}}
                                        <div class="col-md-2">
                                            <label class="form-label">Số lượng</label>
                                            <input type="number" name="variants[0][quantity]" class="form-control"
                                                placeholder="SL">
                                        </div>

                                        {{-- Xoá --}}
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-variant">Xoá</button>
                                        </div>
                                    </div>
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

                    {{-- THƯ VIỆN ẢNH SẢN PHẨM --}}
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Thư viện ảnh sản phẩm</h5>
                                </div>
                                <div class="mb-4 row align-items-center">
                                    <div id="drop-area" class="border border-2 border-dashed rounded p-4 text-center">
                                        <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                        <input type="file" id="imageInput" name="gallery[]" accept="image/*"
                                            class="d-none @error('gallery') is-invalid @enderror" multiple>
                                        <div id="preview-container" class="d-flex flex-wrap gap-2 mt-3"
                                            style="max-height: 200px;"></div>
                                    </div>
                                    @error('gallery')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nút submit --}}
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

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // CKEditor
            ClassicEditor.create(document.querySelector("#editor")).catch((error) => console.error(
                "CKEditor error:", error));

            // Format màu cho select2
            function formatColorOption(state) {
                if (!state.id) return state.text;
                const colorCode = $(state.element).data('color');
                if (!colorCode) return state.text;
                return $(
                    `<span><span style="display:inline-block;width:14px;height:14px;background:${colorCode};margin-right:8px;border:1px solid #ccc;"></span>${state.text}</span>`
                );
            }

            // Khởi tạo select2
            function initSelect2(el) {
                const $el = $(el);
                if ($el.hasClass('color-select')) {
                    $el.select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: "Chọn màu",
                        templateResult: formatColorOption,
                        templateSelection: formatColorOption,
                        escapeMarkup: m => m
                    });
                } else {
                    $el.select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: "Chọn..."
                    });
                }
            }

            $('.select2').each(function() {
                initSelect2(this);
            });

            let index = 1;
            const container = document.getElementById("variant-container");

            // Thêm biến thể
            document.getElementById("add-variant").addEventListener("click", function() {
                const firstRow = container.querySelector(".variant-row");
                const newRow = firstRow.cloneNode(true);

                // Xoá select2 cũ
                $(newRow).find("select.select2").select2("destroy");

                newRow.querySelectorAll("input, select").forEach((el) => {
                    const name = el.getAttribute("name");
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        el.setAttribute("name", newName);
                    }

                    el.classList.remove("is-invalid");

                    if (el.tagName === "INPUT") {
                        el.value = "";
                    } else if (el.tagName === "SELECT") {
                        el.selectedIndex = 0;
                    }
                });

                // Re-init select2 cho row mới
                $(newRow).find("select.select2").each(function() {
                    initSelect2(this);
                });

                container.appendChild(newRow);
                index++;
            });

            // Xoá biến thể
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-variant")) {
                    const rows = document.querySelectorAll(".variant-row");
                    if (rows.length > 1) {
                        e.target.closest(".variant-row").remove();
                    }
                }
            });

            // Kiểm tra trùng biến thể
            document.querySelector("form").addEventListener("submit", function(e) {
                const rows = document.querySelectorAll(".variant-row");
                const combo = new Set();
                let duplicate = false;

                rows.forEach((row, idx) => {
                    const color = row.querySelector('select[name$="[color_id]"]').value;
                    const size = row.querySelector('select[name$="[size_id]"]').value;
                    const key = `${color}-${size}`;

                    if (combo.has(key)) {
                        alert(`Biến thể trùng nhau tại dòng ${idx + 1}`);
                        duplicate = true;
                    } else {
                        combo.add(key);
                    }
                });

                if (duplicate) {
                    e.preventDefault();
                }
            });

            // Ảnh chính preview
            const mainDropArea = document.getElementById("main-drop-area");
            const mainImageInput = document.getElementById("main-image-input");
            const mainPreview = document.getElementById("main-preview");

            mainDropArea.addEventListener("click", () => mainImageInput.click());
            mainImageInput.addEventListener("change", function() {
                const file = this.files[0];
                if (file && file.type.startsWith("image/")) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mainPreview.src = e.target.result;
                        mainPreview.classList.remove("d-none");
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Xử lý ảnh thư viện
            const dropArea = document.getElementById("drop-area");
            const imageInput = document.getElementById("imageInput");
            const previewContainer = document.getElementById("preview-container");

            dropArea.addEventListener("click", () => imageInput.click());
            imageInput.addEventListener("change", function() {
                handleGalleryFiles(this.files);
            });

            function handleGalleryFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (!file.type.startsWith("image/")) continue;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-thumbnail");
                        img.style.maxHeight = "100px";
                        img.style.marginRight = "10px";
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endsection
