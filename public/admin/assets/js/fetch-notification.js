let lastNotificationId = null;

function fetchNotifications() {
    $.ajax({
        url: "/admin/notification/fetch",
        method: "GET",
        success: function (data) {
            $(".notification-box .badge").text(data.count);

            let html = `
                <li>
                    <span class="lnr lnr-alarm"></span>
                    <h6 class="f-18 mb-0">Thông báo</h6>
                </li>
            `;

            if (data.notifications.length > 0) {
                data.notifications.forEach((item, index) => {
                    html += `
                        <li class="p-2 border-bottom">
                            <a data-id="${item.id}"
                                    type="button"
                                    class="notification-item d-flex justify-content-between align-items-center text-decoration-none">
                                <div class="d-flex align-items-center">
                                    ${item.is_read == 0
                            ? '<span class="me-2"></span><small style="color:#000; font-weight:700;" class="d-block">' + (item.message ?? "") + "</small>"
                            : '<small class="text-muted d-block">' + (item.message ?? "") + "</small>"
                        }
                                </div>
                                <div class="text-end ms-2">
                                    <small class="text-muted">${item.time_ago ?? ""}</small>
                                </div>
                            </a>
                        </li>
                    `;

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
                html += `
                    <li>
                        <p class="text-center text-muted mb-0">Không có thông báo nào</p>
                    </li>
                `;
            }

            html += `
                <li>
                    <a class="btn btn-primary w-100" href="/admin/notifications">Kiểm tra toàn bộ thông báo</a>
                </li>
            `;

            $(".notification-dropdown").html(html);
        },
        error: function (err) {
            console.error("Error fetching notifications", err);
        },
    });
}

fetchNotifications();

setInterval(fetchNotifications, 5000);

$(document).on('click', '.notification-item', function (e) {
    e.preventDefault();
    var id = $(this).data('id');

    $.ajax({
        url: '/admin/notification/' + id + '/mark-as-read',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.success) {
                window.location.href = res.link;
            }
        },
        error: function (err) {
            console.error('Error marking notification as read', err);
        }
    });
});
