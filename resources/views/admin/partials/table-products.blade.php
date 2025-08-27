<div class="table-responsive table-desi">
    <table class="user-table table table-striped">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Lượt xem</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        <span>
                            @if ($product->image && file_exists(storage_path('app/public/' . $product->image)))
                                <img src="{{ asset('storage/' . $product->image) }}" alt="product" width="60" height="60">
                            @else
                                <img src="{{ asset('images/default-product.png') }}" alt="no image" width="60" height="60">
                            @endif
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('admin.product.show', $product->id) }}">
                            <span class="d-block">{{ $product->name }}</span>
                        </a>
                    </td>

                    <td>{{ $product->category->name ?? '---' }}</td>

                    <td>{{ number_format($product->views ?? 0) }}</td>

                    <td>
                        <ul>
                            <li>
                                <a href="{{ route('admin.product.show', $product->id) }}">
                                    <span class="lnr lnr-eye"></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.product.edit', $product->id) }}">
                                    <span class="lnr lnr-pencil"></span>
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                                      class="delete-form" data-name="{{ $product->name }}">
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
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Không có sản phẩm nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $products->links('vendor.pagination.bootstrap-5') }}
</div>
