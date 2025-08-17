let lastNotificationId = null;

function fetchNotifications() {
    $.ajax({
        url: '/admin/notification/fetch',
        method: "GET",
        success: function (data) {
            $(".notification-box .badge").text(data.count);

            let html = `
                    <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                        <h6 class="f-18 mb-0">
                            <span class="lnr lnr-alarm me-2"></span> Thông báo
                        </h6>
                        <a href="{{ route('admin.notifications.index') }}" class="small">Xem tất cả</a>
                    </li>
                `;

            if (data.notifications.length > 0) {
                data.notifications.forEach((item, index) => {
                    html += `
                            <li class="p-2 border-bottom">
                                <a href="${item.link ?? '#'}" class="d-flex justify-content-between text-decoration-none">
                                    <div>
                                        <small class="text-muted d-block">${item.message ?? ''}</small>
                                    </div>
                                    <div class="text-end ms-2">
                                        <small class="text-muted">${item.time_ago ?? ''}</small>
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
                                position: 'top-end',
                                icon: 'success',
                                title: item.title,
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                background: '#4CAF50',
                                color: 'white'
                            });
                        }
                    }
                });
            } else {
                html += `<li><p class="text-center text-muted mb-0">Không có thông báo nào</p></li>`;
            }

            $(".notification-dropdown").html(html);
        },
        error: function (err) {
            console.error("Error fetching notifications", err);
        }
    });
}

fetchNotifications();

setInterval(fetchNotifications, 20000);
