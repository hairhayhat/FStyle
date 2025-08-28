let lastNotificationId = null;

function fetchNotifications() {
    $.ajax({
        url: "/client/notification/fetch",
        method: "GET",
        success: function (data) {
            // Cập nhật badge thông báo
            $(".notification-badge").text(data.count || '0');

            // Xây dựng HTML cho dropdown thông báo
            let notificationListHtml = '';

            if (data.notifications.length > 0) {
                // Thêm các thông báo vào dropdown
                data.notifications.forEach((item, index) => {
                    notificationListHtml += `
                        <div class="notification-item ${item.is_read == 0 ? 'unread' : ''}">
                            <a type="button" data-id="${item.id}" class="d-flex justify-content-between align-items-center check-notification">
                                <div class="notification-content">
                                    <p class="notification-message ${item.is_read == 0 ? 'unread' : ''}">
                                        ${item.message || ''}
                                    </p>
                                    <span class="notification-time">${item.time_ago || ''}</span>
                                </div>
                                ${item.is_read == 0 ? '<span class="unread-indicator"></span>' : ''}
                            </a>
                        </div>
                    `;

                    // Logic hiển thị toast cho thông báo mới (giữ nguyên)
                    if (index === 0) {
                        if (lastNotificationId === null) {
                            lastNotificationId = item.id;
                        } else if (item.id !== lastNotificationId) {
                            lastNotificationId = item.id;
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: item.title,
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                background: "#4CAF50",
                                color: "white",
                            });
                        }
                    }
                });
            } else {
                notificationListHtml = `
                    <div class="empty-notification">
                        <p>Không có thông báo nào</p>
                    </div>
                `;
            }

            // Cập nhật UI
            $(".notification-list").html(notificationListHtml);

            // Giữ nguyên phần header và footer của dropdown
            // (chúng đã được định nghĩa trong HTML gốc)
        },
    });
}

fetchNotifications();

setInterval(fetchNotifications, 5000);

$(document).on('click', '.check-notification', function (e) {
    e.preventDefault();
    var id = $(this).data('id');

    $.ajax({
        url: '/client/notification/' + id + '/mark-as-read',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.success) {
                window.location.href = res.link;
            }
        },
    });
});
