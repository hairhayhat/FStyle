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
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                    <div class="accordion-body category-scroll">
                                        <ul class="category-list">
                                            @foreach ($categories as $item)
                                                <li>
                                                    <div class="form-check ps-0 custome-form-check">
                                                        <input class="checkbox_animated check-it" type="checkbox"
                                                            id="flexCheckDefault10">
                                                        <label class="form-check-label"
                                                            for="flexCheckDefault10">{{ $item->name }}</label>
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
                                                            class="color-radio">
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
                                                            class="size-radio">
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
                    <div class="row g-4">
                        <div class="filter-button">
                            <button class="btn filter-btn p-0"><i data-feather="align-left"></i> Filter</button>
                        </div>
                        <!-- filter button -->

                        <!-- label and featured section -->
                        <div class="col-md-12">
                            <ul class="short-name">
                                <li>
                                    <div class="label-tag">
                                        <span>Shirts</span>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>
                                </li>
                                <li>
                                    <div class="label-tag">
                                        <span>Kurtas</span>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>
                                </li>
                                <li>
                                    <div class="label-tag">
                                        <span>Jackets</span>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>
                                </li>
                                <li>
                                    <div class="label-tag">
                                        <span>Blazers</span>
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

                        <div class="col-12">
                            <div class="filter-options">
                                <div class="select-options">
                                    <div class="page-view-filter">
                                        <div class="dropdown select-featured">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                24 Page per view
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Alphabetically
                                                        A-Z</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Alphabetically
                                                        Z-A</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Price, High To
                                                        Low</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Price, Low To
                                                        High</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Date, Old To
                                                        New</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Date, New To
                                                        Old</a>
                                                </li>
                                            </ul>
                                        </div>  
                                    </div>
                                    <div class="dropdown select-featured">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Select Featured
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Jeans</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">T-Shirt</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Shirt</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Jacket</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Hoodie</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- label and featured section -->

                    <!-- Prodcut setion -->
                    <div class="product-list-section">
                        @include('client.partials.product')
                    </div>

                    <nav class="page-section">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" aria-label="Previous">
                                    <span aria-hidden="true">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" aria-label="Next">
                                    <span aria-hidden="true">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
