@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5>Chi tiết đánh giá #{{ $comment->id }}</h5>
            <ul style="list-style:none; padding:0; display:flex; gap:10px;">

                <div class="form-check form-switch mt-2">
                    <input class="form-check-input toggle-status" type="checkbox" role="switch" data-id="{{ $comment->id }}"
                        id="switchCheck{{ $comment->id }}" {{ $comment->status ? 'checked' : '' }}>
                    <label class="form-check-label" for="switchCheck{{ $comment->id }}">
                    </label>
                </div>
            </ul>
        </div>

        <div class="container-fluid mt-3">
            <div class="row">

                {{-- Thông tin bình luận --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p><strong>ID:</strong> {{ $comment->id }}</p>
                            <p><strong>Người dùng:</strong> {{ $comment->user->name ?? 'N/A' }}</p>
                            <p><strong>Sản phẩm:</strong> {{ $comment->product->name ?? 'N/A' }}</p>
                            <p><strong>Biến thể:</strong>
                                {{ $comment->variant->color->name ?? 'N/A' }} -
                                {{ $comment->variant->size->name ?? 'N/A' }}
                            </p>
                            <p><strong>Nội dung:</strong> {{ $comment->content }}</p>
                            <p><strong>Đánh giá:</strong> {{ $comment->rating ?? '-' }}</p>
                            <p><strong>Đúng mô tả:</strong> {{ $comment->is_accurate_text }}</p>
                            <p><strong>Trạng thái:</strong> {{ $comment->status ?? '-' }}</p>
                            <p><strong>Ngày tạo:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>

                            {{-- Media đính kèm --}}
                            @if ($comment->media->count())
                                <hr>
                                <p><strong>Media đính kèm:</strong></p>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach ($comment->media as $media)
                                        <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $media->file_path) }}" alt="Media"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc;">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Không có media đính kèm</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
