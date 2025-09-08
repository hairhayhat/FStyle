@forelse($comments as $comment)
    <div class="customer-section mb-4">
        <div class="customer-profile">
            <img src="{{ $comment->user->avatar ?? 'assets/images/default-avatar.jpg' }}"
                class="img-fluid blur-up lazyload" alt="">
        </div>

        <div class="customer-details">
            <h5>{{ $comment->user->name ?? $comment->name }}</h5>

            <ul class="rating my-2 d-inline-block">
                @for ($i = 1; $i <= 5; $i++)
                    <li>
                        <i class="fas fa-star {{ $i <= $comment->rating ? 'theme-color' : '' }}"></i>
                    </li>
                @endfor
            </ul>

            <p class="date-custo font-light">
                {{ $comment->created_at->format('H:i d/m/Y') }} | Phân loại
                hàng: {{ $comment->variant->color->name ?? null }},
                {{ $comment->variant->size->name ?? null }}</p>

            <p class="font-light">
                Sản phẩm đúng mô tả:
                {{ $comment->is_accurate == 1 ? 'Đúng' : 'Sai' }}
            </p>

            <p class="font-strong mt-2">- {{ $comment->content }}</p>

            @if ($comment->media->count())
                <div class="comment-media d-flex flex-wrap mt-2">
                    @foreach ($comment->media->where('type', 'image') as $media)
                        <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid me-2 mb-2"
                            style="width:120px; height:80px; object-fit:cover;" alt="">
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@empty
    <p>Chưa có đánh giá nào.</p>
@endforelse

<div class="mt-3">
    {{ $comments->links('vendor.pagination.bootstrap-5') }}
</div>
