<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\NotificationService;
class UserController extends Controller
{

    public function __construct(
        private NotificationService $notificationService
    ) {
    }
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 10);
        $status = $request->get('email_verified');

        $query = User::query();

        if (!empty($status)) {
            if ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', $sort)
            ->where('id', '!=', auth()->id())
            ->paginate($perPage)
            ->appends($request->all());

        if ($request->ajax()) {
            $html = view('admin.partials.table-users', compact('users'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.user.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật vai trò thành công');
    }
    public function show(User $user)
    {
        // Lấy 5 đơn hàng gần đây
        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();




        // Lấy 5 comment gần đây
        $recentComments = $user->comments()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.user.show', compact(
            'user',
            'recentOrders',
            'recentComments'
        ));
    }

    public function lock(User $user)
    {
        // Không cho phép khóa admin
        if ($user->role_id == 1) {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản quản trị viên!');
        }

        DB::transaction(function () use ($user) {
            $user->lock();

            // Hủy các đơn đang hoạt động và hoàn kho
            $orders = Order::with('orderDetails.productVariant')
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'packaging'])
                ->get();

            foreach ($orders as $order) {
                // Nếu đơn đang ở trạng thái confirmed, packaging, shipped… đã trừ kho trước đó -> hoàn kho
                if (in_array($order->status, ['confirmed', 'packaging', 'pending'])) {
                    foreach ($order->orderDetails as $item) {

                        $variant = $item->productVariant;
                        if ($variant) {
                            $variant->quantity += $item->quantity;
                            $variant->save();
                        }
                    }
                }

                // Đưa đơn về cancelled
                $order->status = 'cancelled';
                $order->note = 'Đã hủy đơn hàng vì tài khoản đã bị khóa';
                $order->save();

                $user = $order->user;

                $this->notificationService->notifyUser(
                    $user,
                    'Cập nhật đơn hàng',
                    "Đã hủy đơn hàng vì tài khoản đã bị khóa",
                    "/client/checkout/{$order->code}"
                );

            }
        });

        return redirect()->back()->with('success', 'Đã khóa tài khoản và hủy các đơn đang xử lý, hoàn kho thành công!');
    }

    public function unlock(User $user)
    {
        $user->unlock();

        return redirect()->back()->with('success', 'Đã mở khóa tài khoản người dùng thành công!');
    }

}
