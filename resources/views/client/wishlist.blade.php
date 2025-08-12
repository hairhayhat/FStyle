@extends('client.layouts.app')
@section('content')
 <!-- Cart Section Start -->
 <section class="wish-list-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 table-responsive">
                @if($products->count() > 0)
                    <table class="table cart-table wishlist-table">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">image</th>
                                <th scope="col">product name</th>
                                <th scope="col">price</th>
                                <th scope="col">availability</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                <a href="{{ route('product.detail', $product->slug) }}">
                                    @if($product->galleries->count() > 0)
                                        <img src="{{ asset('storage/' . $product->galleries->first()->image_path) }}" 
                                             class="blur-up lazyload" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('client/assets/assets/images/fashion/product/front/1.jpg') }}" 
                                             class="blur-up lazyload" alt="{{ $product->name }}">
                                    @endif
                                    </a>
                                </td>
                                <td>
                                <a href="{{ route('product.detail', $product->slug) }}" class="font-light">{{ $product->name }}</a>
                                    <div class="mobile-cart-content row">
                                        <div class="col">
                                        <p>{{ $product->total_stock > 0 ? 'In Stock' : 'Out Of Stock' }}</p>
                                        </div>
                                        <div class="col">
                                        <p class="fw-bold">${{ number_format($product->min_price, 2) }}</p>
                                        </div>
                                        <div class="col">
                                            <h2 class="td-color">
                                            <a href="javascript:void(0)" class="icon remove-favorite" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </h2>
                                            <h2 class="td-color">
                                            <a href="javascript:void(0)" class="icon add-to-cart-btn" 
                                               data-product-id="{{ $product->id }}" 
                                               data-product-name="{{ $product->name }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                            </h2>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                <p class="fw-bold">${{ number_format($product->min_price, 2) }}</p>
                                </td>
                                <td>
                                <p>{{ $product->total_stock > 0 ? 'In Stock' : 'Out Of Stock' }}</p>
                                </td>
                                <td>
                                <a href="javascript:void(0)" class="icon remove-favorite" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <a href="javascript:void(0)" class="icon add-to-cart-btn" 
                                   data-product-id="{{ $product->id }}" 
                                   data-product-name="{{ $product->name }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <h4>Danh sách yêu thích trống</h4>
                    <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                    <a href="{{ route('client.welcome') }}" class="btn btn-solid-default">Tiếp tục mua sắm</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- Cart Section End -->

<!-- Modal Chọn Biến Thể Sản Phẩm -->
<div class="modal fade" id="productVariantsModal" tabindex="-1" aria-labelledby="productVariantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productVariantsModalLabel">Chọn Biến Thể Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="product-image-container text-center mb-3">
                            <img id="modalProductImage" src="" alt="" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        <h6 id="modalProductName" class="text-center"></h6>
                    </div>
                    <div class="col-md-8">
                        <div class="variants-selection">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="colorSelect" class="form-label">Màu sắc:</label>
                                    <select class="form-select" id="colorSelect">
                                        <option value="">Chọn màu sắc</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="sizeSelect" class="form-label">Kích thước:</label>
                                    <select class="form-select" id="sizeSelect">
                                        <option value="">Chọn kích thước</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="selected-variant-info mb-3" id="selectedVariantInfo" style="display: none;">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Giá:</strong> <span id="variantPrice"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Còn lại:</strong> <span id="variantQuantity"></span>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                            
                            <div class="quantity-selection mb-3" id="quantitySelection" style="display: none;">
                                <label for="quantityInput" class="form-label">Số lượng:</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity">-</button>
                                    <input type="number" class="form-control text-center" id="quantityInput" value="1" min="1">
                                    <button type="button" class="btn btn-outline-secondary" id="increaseQuantity">+</button>
                                </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                
                <div class="variants-table mt-4" id="variantsTable" style="display: none;">
                    <h6>Danh sách biến thể có sẵn:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Màu sắc</th>
                                    <th>Kích thước</th>
                                    <th>Giá</th>
                                    <th>Còn lại</th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody id="variantsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="addToCartBtn" disabled>
                    <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('client/assets/js/favorite.js') }}"></script>
<script>
// Debug: Kiểm tra modal có hoạt động không
$(document).ready(function() {
    console.log('Wishlist page loaded');
    
    // Kiểm tra modal có tồn tại không
    if ($('#productVariantsModal').length > 0) {
        console.log('Modal found');
    } else {
        console.log('Modal not found');
    }
    
    // Kiểm tra nút add to cart có tồn tại không
    if ($('#addToCartBtn').length > 0) {
        console.log('Add to cart button found');
    } else {
        console.log('Add to cart button not found');
    }
});
</script>
<style>
/* Modal styling */
#productVariantsModal .modal-lg {
    max-width: 800px;
}

#productVariantsModal .product-image-container img {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#productVariantsModal .variants-selection {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

#productVariantsModal .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

#productVariantsModal .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: border-color 0.2s ease;
}

#productVariantsModal .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

#productVariantsModal .selected-variant-info .alert {
    border: none;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
}

#productVariantsModal .quantity-selection .input-group {
    max-width: 200px;
}

#productVariantsModal .quantity-selection .btn {
    border: 2px solid #e9ecef;
    background: white;
    color: #495057;
    font-weight: 600;
    min-width: 40px;
}

#productVariantsModal .quantity-selection .btn:hover {
    background: #f8f9fa;
    border-color: #007bff;
}

#productVariantsModal .quantity-selection .form-control {
    border: 2px solid #e9ecef;
    text-align: center;
    font-weight: 600;
}

#productVariantsModal .variants-table {
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

#productVariantsModal .variants-table .table {
    margin-bottom: 0;
}

#productVariantsModal .variants-table th {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: #495057;
}

#productVariantsModal .select-variant-btn {
    border-radius: 20px;
    font-size: 12px;
    padding: 4px 12px;
}

#productVariantsModal .modal-footer .btn {
    border-radius: 6px;
    font-weight: 600;
    padding: 10px 24px;
}

#productVariantsModal .modal-footer .btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

#productVariantsModal .modal-footer .btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
}

/* Mobile optimization */
@media (max-width: 768px) {
    #productVariantsModal .modal-lg {
        max-width: 95%;
        margin: 10px;
    }
    
    #productVariantsModal .variants-selection {
        padding: 15px;
    }
    
    #productVariantsModal .variants-table {
        font-size: 14px;
    }
    
    #productVariantsModal .select-variant-btn {
        font-size: 11px;
        padding: 3px 8px;
    }
}

/* Loading states */
.processing {
    opacity: 0.6;
    pointer-events: none;
}

/* Animation */
#productVariantsModal .modal-content {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
@endsection
