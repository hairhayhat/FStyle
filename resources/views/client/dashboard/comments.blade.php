@extends('client.dashboard.layouts.app')

@section('content')
    <!-- Recent Reviews Section Start -->
    <div class="col-lg-9">
        <div class="row mb-3">
            <div class="col-md-6">
                <h4>Đánh giá gần đây</h4>
            </div>
            <div class="col-md-6 text-end">
                <form method="GET" action="{{ route('client.comments') }}">
                    <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline">
                        <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="asc" {{ $sort == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 table-responsive">
                @if ($comments->count() > 0)
                    <table class="table cart-table wishlist-table">
                        <thead>
                            <tr class="table-head">
                                <th>Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Đánh giá</th>
                                <th>Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <td>
                                        <a href="{{ route('product.detail', $comment->product->slug) }}">
                                            <img src="{{ asset('storage/' . $comment->product->image) }}"
                                                 class="blur-up lazyload" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('product.detail', $comment->product->slug) }}"
                                           class="font-light">{{ $comment->product->name }}</a>
                                    </td>
                                    <td>{{ $comment->content }}</td>
                                    <td>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $comment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $comments->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h4>Bạn chưa có đánh giá nào</h4>
                        <p>Hãy mua sản phẩm và để lại đánh giá.</p>
                        <a href="{{ route('client.welcome') }}" class="btn btn-solid-default">Tiếp tục mua sắm</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Recent Reviews Section End -->
@endsection
