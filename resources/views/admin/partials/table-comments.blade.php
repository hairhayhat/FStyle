<div class="table-responsive table-desi">
    <table class="user-table table table-striped">
        <thead>
            <tr>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Rating</th>
                <th>Trạng thái</th>
                <th>Ngày đăng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment->user->name }}</td>
                    <td>{{ $comment->product->name ?? 'Đã xóa' }}</td>
                    <td>{{ Str::limit($comment->content, 50) }}</td>
                    <td>{{ $comment->rating }} ★</td>
                    <td>
                        <ul style="list-style:none; padding:0; display:flex; gap:10px;">

                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input toggle-status" type="checkbox" role="switch"
                                    data-id="{{ $comment->id }}" id="switchCheck{{ $comment->id }}"
                                    {{ $comment->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchCheck{{ $comment->id }}">
                                </label>
                            </div>

                        </ul>

                    </td>
                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $comments->links('vendor.pagination.bootstrap-5') }}
</div>
