@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>Danh sách bình luận</h5>
            </div>

            <!-- Comment List Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <form action="" method="GET" id="commentFilterForm"
                                        class="d-flex align-items-center gap-2 flex-wrap">

                                        <input type="hidden" name="image" value="{{ request('image') }}">

                                        <select name="sort" class="form-select form-select-sm w-auto">
                                            <option value="desc"
                                                {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>
                                                Mới nhất
                                            </option>
                                            <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>
                                                Cũ nhất
                                            </option>
                                        </select>

                                        <select name="per_page" class="form-select form-select-sm w-auto">
                                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 /
                                                trang</option>
                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                                / trang</option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 /
                                                trang</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 /
                                                trang</option>
                                        </select>

                                        <select name="rating" class="form-select form-select-sm w-auto">
                                            <option value="">Tất cả đánh giá</option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('rating') == $i ? 'selected' : '' }}>
                                                    {{ $i }} sao
                                                </option>
                                            @endfor
                                        </select>

                                        <select name="status" class="form-select form-select-sm w-auto">
                                            <option value="" {{ request()->has('status') ? '' : 'selected' }}>Tất cả
                                            </option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển
                                                thị</option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn
                                            </option>
                                        </select>
                                    </form>

                                    <div class="status-filter d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                            data-status="has_image">
                                            Có ảnh <span class="badge">{{ $statusCounts['active'] ?? 0 }}</span>
                                        </button>

                                        <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                            data-status="no_image">
                                            Không có ảnh <span class="badge">{{ $statusCounts['locked'] ?? 0 }}</span>
                                        </button>

                                        <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                            data-status="">
                                            Tất cả
                                        </button>
                                    </div>

                                </div>

                                <div id="commentTableWrapper" data-url="{{ route('admin.comments.index') }}">
                                    @include('admin.partials.table-comments', ['comments' => $comments])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Comment List End -->
        </div>
    </div>
@endsection
