<?php // Tệp PHP
namespace App\Http\Controllers\Admin; // Khai báo namespace cho nhóm Controller phía Admin
use App\Http\Controllers\Controller; // Import lớp cơ sở Controller của Laravel
use App\Models\User; // Import model User
use App\Models\Order; // Import model Order để thao tác đơn hàng
use Illuminate\Support\Facades\DB; // Import Facade DB để dùng transaction
use App\Models\Role; // Import model Role để lấy danh sách vai trò
use Illuminate\Http\Request; // Import Request để nhận dữ liệu từ HTTP request
use App\Services\NotificationService; // Import service gửi thông báo người dùng
class UserController extends Controller // Định nghĩa lớp Controller quản trị người dùng, kế thừa Controller
{
    
    public function __construct(
        private NotificationService $notificationService // Tiêm NotificationService qua constructor (thuộc tính readonly theo PHP 8.1+ style)
    ) {
    }
    public function index(Request $request) // Trang danh sách người dùng với lọc/sắp xếp/phân trang
    {
        $sort = $request->get('sort', 'desc'); // Lấy tham số sắp xếp, mặc định desc theo created_at
        $perPage = $request->get('per_page', 10); // Lấy số bản ghi mỗi trang, mặc định 10
        $status = $request->get('email_verified'); // Lọc theo trạng thái xác minh email: verified/unverified

        $query = User::query(); // Khởi tạo query builder cho User

        if (!empty($status)) { // Nếu có truyền tham số trạng thái
            if ($status === 'verified') { // Nếu yêu cầu người đã xác minh
                $query->whereNotNull('email_verified_at'); // Điều kiện email_verified_at không null
            } elseif ($status === 'unverified') { // Nếu yêu cầu người chưa xác minh
                $query->whereNull('email_verified_at'); // Điều kiện email_verified_at là null
            }
        }

        $users = $query->orderBy('created_at', $sort) // Sắp xếp theo ngày tạo
            ->paginate($perPage) // Phân trang theo perPage
            ->appends($request->all()); // Giữ lại tham số query trên link phân trang

        if ($request->ajax()) { // Nếu là yêu cầu AJAX (phân trang/ lọc dynamic)
            $html = view('admin.partials.table-users', compact('users'))->render(); // Render phần bảng người dùng
            return response()->json(['html' => $html]); // Trả về JSON chứa HTML để cập nhật phần view
        }

        return view('admin.user.index', compact('users')); // Trả về view danh sách người dùng đầy đủ
    }

    public function edit(User $user) // Trang sửa vai trò người dùng (route model binding User)
    {
        $roles = Role::all(); // Lấy toàn bộ vai trò
        return view('admin.user.edit', compact('user', 'roles')); // Truyền user và roles sang view
    }
    public function update(Request $request, User $user) // Cập nhật vai trò người dùng
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id', // role_id bắt buộc và phải tồn tại trong bảng roles
        ]);

        $user->role_id = $request->role_id; // Gán vai trò mới cho user
        $user->save(); // Lưu thay đổi

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật vai trò thành công'); // Điều hướng về danh sách với thông báo
    }
    public function show(User $user) // Trang chi tiết người dùng
{
    // Lấy 5 đơn hàng gần đây
    $recentOrders = $user->orders() // Quan hệ orders của user
        ->latest() // Sắp xếp mới nhất theo created_at
        ->take(5) // Giới hạn 5 bản ghi
        ->get(); // Thực thi truy vấn




    // Lấy 5 comment gần đây
    $recentComments = $user->comments() // Quan hệ comments của user
        ->latest() // Sắp xếp mới nhất
        ->take(5) // Giới hạn 5 comment
        ->get(); // Lấy dữ liệu

    return view('admin.user.show', compact(
        'user', // Đối tượng người dùng
        'recentOrders', // Danh sách 5 đơn hàng gần đây
        'recentComments' // Danh sách 5 bình luận gần đây
    )); // Trả về view chi tiết
}

public function lock(User $user) // Khóa tài khoản người dùng và xử lý đơn đang hoạt động
{
    // Không cho phép khóa admin
    if ($user->role_id == 1) { // Nếu là tài khoản quản trị (role_id = 1)
        return redirect()->back()->with('error', 'Không thể khóa tài khoản quản trị viên!'); // Quay lại với thông báo lỗi
    }

    DB::transaction(function () use ($user) { // Dùng transaction để đảm bảo tính toàn vẹn dữ liệu
        $user->lock(); // Gọi phương thức lock() trên model User để khóa tài khoản

        // Hủy các đơn đang hoạt động và hoàn kho
        $orders = Order::with('orderDetails.productVariant') // Eager load chi tiết đơn và biến thể sản phẩm
            ->where('user_id', $user->id) // Đơn của người dùng này
            ->whereIn('status', ['pending', 'confirmed', 'packaging']) // Chỉ các đơn đang xử lý
            ->get(); // Lấy danh sách đơn

        foreach ($orders as $order) { // Duyệt từng đơn để xử lý
            // Nếu đơn đang ở trạng thái confirmed, packaging, pending… đã trừ kho trước đó -> hoàn kho
            if (in_array($order->status, ['confirmed', 'packaging', 'pending'])) { // Các trạng thái có thể đã trừ kho
                foreach ($order->orderDetails as $item) { // Duyệt từng dòng sản phẩm trong đơn
                     
                    $variant = $item->productVariant; // Lấy biến thể sản phẩm tương ứng
                    if ($variant) { // Nếu tồn tại biến thể (phòng trường hợp null)
                        $variant->quantity += $item->quantity; // Cộng trả lại số lượng vào kho
                        $variant->save(); // Lưu số lượng mới
                    }
                }
            }

            // Đưa đơn về cancelled
            $order->status = 'cancelled'; // Cập nhật trạng thái đơn là hủy
            $order->note = 'Đã hủy đơn hàng vì tài khoản đã bị khóa'; // Ghi chú lý do hủy
            $order->save(); // Lưu thay đổi đơn hàng

            $user = $order->user; // Lấy lại user từ đơn (đảm bảo đúng tham chiếu)
            
                $this->notificationService->notifyUser( // Gửi thông báo cho người dùng về cập nhật đơn
                    $user, // Người nhận
                    'Cập nhật đơn hàng', // Tiêu đề thông báo
                  "Đã hủy đơn hàng vì tài khoản đã bị khóa", // Nội dung thông báo
                    "/client/checkout/{$order->code}" // Đường dẫn chi tiết đơn hàng cho người dùng
                );
            
        }
    }); // Kết thúc transaction

    return redirect()->back()->with('success', 'Đã khóa tài khoản và hủy các đơn đang xử lý, hoàn kho thành công!'); // Thông báo thành công
}

public function unlock(User $user) // Mở khóa tài khoản người dùng
{
    $user->unlock(); // Gọi phương thức mở khóa trên model User
    
    return redirect()->back()->with('success', 'Đã mở khóa tài khoản người dùng thành công!'); // Thông báo thành công và quay lại
}

} // Kết thúc lớp UserController