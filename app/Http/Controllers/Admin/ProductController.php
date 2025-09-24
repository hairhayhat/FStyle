<?php // Tệp PHP

namespace App\Http\Controllers\Admin; // Namespace cho nhóm controller quản trị

use Illuminate\Support\Facades\Storage; // Dùng để thao tác lưu/xoá file trong storage
use App\Http\Controllers\Controller; // Lớp cơ sở Controller
use App\Models\Category; // Model Danh mục
use App\Models\Color; // Model Màu sắc
use App\Models\Product; // Model Sản phẩm
use App\Models\ProductVariant; // Model Biến thể sản phẩm
use App\Models\Size; // Model Size
use Illuminate\Http\Request; // Đối tượng Request
use Illuminate\Support\Facades\Validator; // Facade Validator để tự tuỳ biến thông điệp lỗi
use Illuminate\Support\Str; // Hỗ trợ tạo slug

class ProductController extends Controller // Controller quản trị Sản phẩm
{
    public function index(Request $request) // Danh sách sản phẩm với lọc/sắp xếp/phân trang
    {
        $sort = $request->get('sort', 'desc'); // Kiểu sắp xếp theo created_at, mặc định desc
        $perPage = $request->get('per_page', 10); // Số sản phẩm mỗi trang, mặc định 10
        $status = $request->get('status'); // Trạng thái hoạt động/đang bị khoá theo tiêu chí biến thể

        $query = Product::with('category'); // Eager load category để giảm số query N+1

        if ($request->filled('category_id')) { // Lọc theo danh mục nếu có
            $query->where('category_id', $request->category_id); // Điều kiện category_id
        }

        if ($status === 'active') { // Sản phẩm không có biến thể đang được dùng trong giỏ/đơn
            $query->whereDoesntHave('variants', function ($q) {
                $q->whereHas('cartDetails') // Có trong giỏ hàng
                    ->orWhereHas('orderDetails'); // Hoặc đã nằm trong đơn hàng
            });
        } elseif ($status === 'locked') { // Sản phẩm có biến thể đang được dùng (xem như bị khoá)
            $query->whereHas('variants', function ($q) {
                $q->whereHas('cartDetails')
                    ->orWhereHas('orderDetails');
            });
        }

        $products = $query->orderBy('created_at', $sort) // Sắp xếp theo ngày tạo
            ->paginate($perPage) // Phân trang
            ->appends($request->all()); // Giữ query string khi chuyển trang
       
        $categories = Category::all(); // Lấy tất cả danh mục để hiển thị filter

        if ($request->ajax()) { // Nếu là request AJAX (phân trang/lọc động)
            $html = view('admin.partials.table-products', compact('products'))->render(); // Render phần bảng
            return response()->json(['html' => $html]); // Trả về HTML để cập nhật view
        }

        return view('admin.product.index', compact('products', 'categories')); // Trả về trang index
    }

    public function create() // Trang tạo sản phẩm
    {
        $categories = Category::all(); // Tất cả danh mục
        $colors = Color::all(); // Tất cả màu sắc
        $sizes = Size::all(); // Tất cả size
        return view('admin.product.create', compact('categories', 'colors', 'sizes')); // Truyền dữ liệu qua view
    }

    public function store(Request $request) // Xử lý lưu sản phẩm mới
    {
        $validator = Validator::make($request->all(), [ // Tự tạo validator để dùng message tuỳ chỉnh
            'name' => 'required|string|max:255', // Tên bắt buộc
            'category_id' => 'required|exists:categories,id', // Danh mục hợp lệ
            'description' => 'nullable|string', // Mô tả tuỳ chọn
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Ảnh chính bắt buộc
            'gallery' => 'nullable|array', // Thư viện ảnh có thể có
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Mỗi ảnh trong thư viện hợp lệ

            'variants' => 'required|array', // Mảng biến thể bắt buộc
            'variants.*.color_id' => 'required|exists:colors,id', // Màu hợp lệ
            'variants.*.size_id' => 'required|exists:sizes,id', // Size hợp lệ
            'variants.*.import_price' => 'required|numeric|min:0', // Giá nhập >= 0
            'variants.*.sale_price' => 'required|numeric|min:0', // Giá bán >= 0
            'variants.*.quantity' => 'required|integer|min:0', // Số lượng >= 0
        ], [ // Thông điệp lỗi và nhãn hiển thị Tiếng Việt
            'required' => ':attribute không được để trống.',
            'string' => ':attribute phải là chuỗi.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'integer' => ':attribute phải là số nguyên.',
            'numeric' => ':attribute phải là số.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            'exists' => ':attribute không tồn tại trong hệ thống.',
            'image' => ':attribute phải là hình ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'array' => ':attribute phải là một mảng.',
            'name' => 'Tên sản phẩm',
            'category_id' => 'Danh mục',
            'description' => 'Mô tả sản phẩm',
            'gallery' => 'Thư viện ảnh',
            'gallery.*' => 'Ảnh trong thư viện',
            'variants' => 'Biến thể sản phẩm',
            'variants.*.color_id' => 'Màu sắc',
            'variants.*.size_id' => 'Size',
            'variants.*.import_price' => 'Giá nhập',
            'variants.*.sale_price' => 'Giá bán',
            'variants.*.quantity' => 'Số lượng',
        ]);


        if ($validator->fails()) { // Nếu validate thất bại
            return back()->withErrors($validator)->withInput(); // Quay lại kèm lỗi và dữ liệu cũ
        }

        // Lưu ảnh chính
        $mainImagePath = $request->file('image')->store('products', 'public'); // Lưu vào disk public

        $slug = Str::slug($request->name); // Tạo slug từ tên
        $originalSlug = $slug; // Ghi nhớ slug gốc
        $counter = 1; // Bộ đếm để tránh trùng slug

        while (Product::where('slug', $slug)->exists()) { // Nếu slug đã tồn tại
            $slug = $originalSlug . '-' . $counter; // Tạo slug mới kèm số thứ tự
            $counter++; // Tăng đếm
        }


        // Tạo sản phẩm
        $product = Product::create([
            'name' => $request->name, // Tên sản phẩm
            'slug' => $slug, // Slug duy nhất
            'category_id' => $request->category_id, // Danh mục
            'description' => $request->description, // Mô tả
            'image' => $mainImagePath, // Đường dẫn ảnh chính
        ]);

        // Lưu thư viện ảnh
        if ($request->hasFile('gallery')) { // Nếu có upload gallery
            foreach ($request->file('gallery') as $img) { // Duyệt từng ảnh
                $path = $img->store('products/gallery', 'public'); // Lưu ảnh vào thư mục gallery
                $product->galleries()->create(['image' => $path]); // Tạo bản ghi gallery
            }
        }

        // Lưu các biến thể
        foreach ($request->variants as $variant) { // Duyệt từng biến thể từ form
            ProductVariant::create([
                'product_id' => $product->id, // Gắn vào sản phẩm vừa tạo
                'color_id' => $variant['color_id'], // Màu
                'size_id' => $variant['size_id'], // Size
                'import_price' => $variant['import_price'], // Giá nhập
                'sale_price' => $variant['sale_price'], // Giá bán
                'quantity' => $variant['quantity'], // Số lượng tồn
            ]);
        }

        return redirect()->route('admin.product.index') // Điều hướng về danh sách
            ->with('success', 'Thêm sản phẩm thành công!'); // Thông báo thành công
    }

    public function show(Product $product) // Trang chi tiết sản phẩm
    {
        $product->load(['category', 'variants.color', 'variants.size']); // Nạp quan hệ để hiển thị
        return view('admin.product.show', compact('product')); // Trả về view
    }

    public function edit(Product $product) // Trang sửa sản phẩm
    {

        $categories = Category::all(); // Lấy danh sách danh mục
        $colors = Color::all(); // Lấy màu sắc
        $sizes = Size::all(); // Lấy size
        $variants = $product->variants()->get(); // Lấy các biến thể hiện có

        return view('admin.product.edit', compact('product', 'categories', 'colors', 'sizes', 'variants')); // Truyền dữ liệu sang view
    }


    public function update(Request $request, $id) // Cập nhật sản phẩm
    {
        $product = Product::findOrFail($id); // Tìm sản phẩm, 404 nếu không có

        // Validate
        $validator = Validator::make($request->all(), [ // Tạo validator tương tự store
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'keep_gallery' => 'nullable|array', // Danh sách id gallery giữ lại
            'keep_gallery.*' => 'integer|exists:product_galleries,id',

            // Variants
            'variants' => 'required|array',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.import_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) { // Nếu validate lỗi
            return back()->withErrors($validator)->withInput(); // Quay lại form kèm dữ liệu
        }

        // Slug
        $slug = Str::slug($request->name); // Tạo slug mới (không đảm bảo duy nhất, tuỳ yêu cầu)

        // Cập nhật thông tin cơ bản
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        // Ảnh chính
        if ($request->hasFile('image')) { // Nếu có upload ảnh mới
            if ($product->image) { // Nếu có ảnh cũ
                Storage::disk('public')->delete($product->image); // Xoá ảnh cũ khỏi storage
            }
            $path = $request->file('image')->store('products', 'public'); // Lưu ảnh mới
            $product->update(['image' => $path]); // Cập nhật đường dẫn ảnh
        }

        // Gallery
        $keepIds = $request->input('keep_gallery', []); // Danh sách id gallery được giữ lại
        $product->galleries()->whereNotIn('id', $keepIds)->get()->each(function ($img) { // Lấy ảnh cần xoá
            if ($img->image) { // Nếu có file vật lý
                Storage::disk('public')->delete($img->image); // Xoá file khỏi storage
            }
            $img->delete(); // Xoá bản ghi
        });

        if ($request->hasFile('gallery')) { // Thêm ảnh mới vào gallery
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('products/gallery', 'public'); // Lưu file
                $product->galleries()->create(['image' => $path]); // Tạo bản ghi
            }
        }

        // Variants
        $keepVariantIds = []; // Danh sách id biến thể giữ lại
        foreach ($request->variants as $variant) { // Duyệt biến thể gửi lên
            if (!empty($variant['id'])) { // Nếu là biến thể cũ
                // Update variant cũ
                $pv = ProductVariant::find($variant['id']); // Tìm theo id
                if ($pv) { // Nếu tồn tại
                    $pv->update([
                        'color_id' => $variant['color_id'],
                        'size_id' => $variant['size_id'],
                        'import_price' => $variant['import_price'],
                        'sale_price' => $variant['sale_price'],
                        'quantity' => $variant['quantity'],
                    ]);
                    $keepVariantIds[] = $pv->id; // Đánh dấu giữ lại
                }
            } else { // Biến thể mới
                // Tạo variant mới
                $pv = $product->variants()->create([
                    'color_id' => $variant['color_id'],
                    'size_id' => $variant['size_id'],
                    'import_price' => $variant['import_price'],
                    'sale_price' => $variant['sale_price'],
                    'quantity' => $variant['quantity'],
                ]);
                $keepVariantIds[] = $pv->id; // Lưu id vừa tạo để giữ lại
            }
        }

        // Xoá variant không còn
        $product->variants()->whereNotIn('id', $keepVariantIds)->delete(); // Xoá biến thể không nằm trong danh sách giữ lại

        return redirect()->route('admin.product.edit', $product->id) // Quay lại trang edit
            ->with('success', 'Cập nhật sản phẩm thành công!'); // Thông báo thành công
    }


    public function destroy(Product $product) // Xoá sản phẩm
    {
        if ($product->inUse() == true) { // Nếu đang được sử dụng ở nơi khác
            return redirect()
                ->route('admin.product.index')
                ->with('error', 'Sản phẩm này đang được sử dụng nên không thể xóa.'); // Báo lỗi không thể xoá
        }
        $product->delete(); // Xoá mềm hoặc cứng tuỳ model
        return back()->with('success', 'Xoá sản phẩm thành công!'); // Thông báo xoá thành công
    }
}
