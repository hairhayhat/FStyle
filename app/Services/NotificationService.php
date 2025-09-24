<?php // Tệp PHP

namespace App\Services; // Namespace cho các service ứng dụng

use App\Models\Notification; // Model Notification (thông báo)
use App\Models\NotificationUser; // Bảng trung gian giữa thông báo và người nhận
use App\Models\User; // Model User

class NotificationService // Cung cấp các hàm tiện ích để gửi/đánh dấu thông báo
{
    /**
     * Tạo notification gửi tới admin (ví dụ: người dùng yêu cầu hỗ trợ)
     *
     * @param string $title Tiêu đề thông báo
     * @param string $message Nội dung thông báo
     * @param string|null $link Đường dẫn liên quan
     * @param int|null $relatedId Khoá ngoại liên quan (đơn hàng, bình luận...)
     * @return Notification Bản ghi Notification đã tạo
     */
    public function notifyAdmins(string $title, string $message, string $link = null, int $relatedId = null)
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'user_to_admin', // Phân loại: người dùng -> admin
            'related_id' => $relatedId,
        ]);

        $admin = User::where('role_id', 1)->first(); // Lấy 1 admin (role_id = 1)

        NotificationUser::create([
            'notification_id' => $notification->id, // Gắn thông báo
            'user_id' => $admin->id, // Người nhận là admin
            'is_read' => false, // Chưa đọc
            'assigned_admin_id' => null, // Chưa gán xử lý
        ]);

        return $notification; // Trả về bản ghi thông báo
    }

    /**
     * Tạo notification cá nhân cho user (khách hàng)
     *
     * @param User $user Người nhận
     * @param string $title Tiêu đề
     * @param string $message Nội dung
     * @param string|null $link Đường dẫn
     * @param int|null $relatedId Khoá ngoại liên quan
     * @return Notification
     */
    public function notifyUser(User $user, string $title, string $message, string $link = null, int $relatedId = null)
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'admin_to_user', // Phân loại: admin -> người dùng
            'related_id' => $relatedId,
        ]);

        NotificationUser::create([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'is_read' => false,
            'assigned_admin_id' => null,
        ]);

        return $notification;
    }


    public function markAsRead(int $notificationId, int $userId) // Đánh dấu thông báo đã đọc cho 1 user
    {
        $notifUser = NotificationUser::where('notification_id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notifUser) { // Nếu tồn tại bản ghi liên kết
            $notifUser->is_read = true; // Đánh dấu đã đọc
            $notifUser->save(); // Lưu thay đổi
        }
    }

    public function startProcessing(int $notificationId, int $adminId) // Admin bắt đầu xử lý một thông báo
    {
        $notifUser = NotificationUser::where('notification_id', $notificationId)
            ->where('user_id', $adminId)
            ->first();

        if ($notifUser && $notifUser->assigned_admin_id === null) { // Nếu chưa có admin nhận
            $notifUser->assigned_admin_id = $adminId; // Gán admin xử lý
            $notifUser->processed_at = now(); // Ghi nhận thời điểm bắt đầu xử lý
            $notifUser->save();
            return true; // Báo thành công
        }

        return false; // Không thể gán (đã có người xử lý)
    }
}
