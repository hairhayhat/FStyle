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
                                            <a href="{{ route('client.cart') }}" class="icon">
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
                                <a href="{{ route('client.cart') }}" class="icon">
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

<script>
// Xử lý xóa sản phẩm khỏi danh sách yêu thích
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.remove-favorite');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
                fetch(`/client/products/${productId}/unfavorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Xóa row khỏi bảng
                        this.closest('tr').remove();
                        
                        // Kiểm tra nếu không còn sản phẩm nào
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload(); // Reload để hiển thị thông báo danh sách trống
                        }
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                });
            }
        });
    });
});
</script>
@endsection
