<div class="table-responsive table-desi">
    <table class="user-table table table-striped">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>
                        <span>
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="javascript:void(0)">
                            <span class="d-block">{{ $category->name }}</span>
                        </a>
                    </td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <ul>
                            <li>
                                <a href="{{ route('admin.category.edit', $category->id) }}">
                                    <span class="lnr lnr-pencil"></span>
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                    class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;padding:0;color:#dc3545;">
                                        <span class="lnr lnr-trash"></span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $categories->links('vendor.pagination.bootstrap-5') }}
</div>