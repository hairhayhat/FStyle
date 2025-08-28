@extends('client.layouts.app')

@section('content')
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="category-option">
                        <div class="button-close mb-3">
                            <button class="btn p-0"><i data-feather="arrow-left"></i> Close</button>
                        </div>
                        <div class="accordion category-name" id="accordionExample">

                            <div class="accordion-item category-rating">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne">
                                        Danh mục
                                    </button>
                                </h2>
                                @php
                                    $currentSlug = request()->segment(2);
                                @endphp

                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                    <div class="accordion-body category-scroll">
                                        <ul class="category-list">
                                            @foreach ($categories as $item)
                                                <li>
                                                    <div class="form-check ps-0 custome-form-check">
                                                        <input class="checkbox_animated check-it" type="radio"
                                                            name="category" id="category{{ $item->id }}"
                                                            value="{{ $item->id }}"
                                                            {{ $item->slug === $currentSlug ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="category{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </label>
                                                        <p class="font-light">
                                                            ({{ $item->products_count ?? $item->products->count() }})
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            </div>

                            <div class="accordion-item category-color">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree">
                                        Màu sắc
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse show"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="category-list color-filter">
                                            @foreach ($colors as $item)
                                                <li>
                                                    <div class="color-option">
                                                        <input type="radio" id="color_{{ $item->id }}"
                                                            name="color_filter" value="{{ $item->id }}"
                                                            data-name= "{{ $item->name }}" class="color-radio">
                                                        <label for="color_{{ $item->id }}"
                                                            style="background-color: {{ $item->code }}"
                                                            title="{{ $item->name }}">
                                                            <i class="fas fa-check"></i>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item size-options">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree">
                                        Kích cỡ
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse show"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="category-list size-filter">
                                            @foreach ($sizes as $item)
                                                <li>
                                                    <div class="size-option">
                                                        <input type="radio" id="size_{{ $item->id }}"
                                                            name="size_filter" value="{{ $item->id }}"
                                                            data-name= "{{ $item->name }}" class="size-radio">
                                                        <label for="size_{{ $item->id }}" title="{{ $item->name }}">
                                                            {{ $item->name }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item category-price">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour">Price</button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse show"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="range-slider category-list">
                                            <input type="text" class="js-range-slider" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-12 ratio_30">
                    <div class="row g-4 align-items-center">
                        <!-- Bên trái: filter button + active filters -->
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="filter-button me-3">
                                <button class="btn filter-btn p-0">
                                    <i data-feather="align-left"></i> Lọc:
                                </button>
                            </div>
                            <ul class="short-name d-flex flex-wrap mb-0">
                                <li>
                                    <div class="label-tag">
                                        <span>Filter 1</span>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>
                                </li>
                                <li>
                                    <div class="label-tag">
                                        <span>Filter 2</span>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>
                                </li>
                                <li>
                                    <div class="label-tag">
                                        <a href="javascript:void(0)"><span>Clear All</span></a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-4 d-flex justify-content-end align-items-center gap-2">
                            <select class="form-select" aria-label="Sort by">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                            </select>

                            <select class="form-select" aria-label="Products per page">
                                <option value="12">12 / trang</option>
                                <option value="24">24 / trang</option>
                                <option value="48">48 / trang</option>
                            </select>
                        </div>
                    </div>

                    <div class="product-list-section">
                        @include('client.partials.product')
                    </div>

                    <div class="loading-overlay"
                        style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; text-align:center;">
                        <div class="spinner-border text-primary" style="margin-top:20%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
