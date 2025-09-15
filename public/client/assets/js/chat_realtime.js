$(document).ready(function () {
    const currentUserId = $("#chat-container").data("user-id");

    $("#messageToggle").on("click", function (e) {
        e.preventDefault();
        $("#messageMenu").toggleClass("show");
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest("#messageMenu, #messageToggle").length) {
            $("#messageMenu").removeClass("show");
        }
    });

    $(".user-item").on("click", function () {
        let userId = $(this).data("id");
        let userName = $(this).data("name");

        if ($("#chatbox-" + userId).length) return;

        openChatBox(userId, userName);
        loadMessages(userId);
    });

    $(document).on("click", ".chatbox-close", function () {
        let userId = $(this).data("id");
        $("#chatbox-" + userId).remove();
    });

    $(document).on("click", ".send-btn", function () {
        let userId = $(this).data("id");
        sendMessage(userId);
    });

    $(document).on("keydown", "input[id^='newMessage-']", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            let userId = $(this).attr("id").split("-")[1];
            sendMessage(userId);
        }
    });

    $(document).on("change", ".file-input", function () {
        let userId = $(this).data("id");
        let files = Array.from(this.files);
        let previewBox = $(`#previewBox-${userId}`);
        previewBox.html("");

        files.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                previewBox.append(`
                <div class="preview-item" data-index="${index}">
                    <img src="${e.target.result}" alt="preview" />
                    <button type="button" class="remove-preview" data-id="${userId}" data-index="${index}">×</button>
                </div>
            `);
            };
            reader.readAsDataURL(file);
        });
    });

    $(document).on("click", ".remove-preview", function () {
        let userId = $(this).data("id");
        let indexToRemove = $(this).data("index");
        let input = $(`#fileInput-${userId}`)[0];
        let dt = new DataTransfer();

        Array.from(input.files).forEach((file, i) => {
            if (i !== indexToRemove) {
                dt.items.add(file);
            }
        });

        input.files = dt.files;

        $(this).closest(".preview-item").remove();
    });

    function openChatBox(userId, userName) {
        let chatHtml = `
    <div id="chatbox-${userId}" class="chatbox">
        <div class="chatbox-header">
            <span>${userName}</span>
            <span class="chatbox-close" data-id="${userId}">×</span>
        </div>
        <div class="chatbox-body">
            <div id="messagesBox-${userId}" class="chat-messages"></div>

            <!-- vùng preview ảnh -->
            <div id="previewBox-${userId}" class="chat-preview"></div>

            <div class="chat-input">
                <label for="fileInput-${userId}" class="file-label">
                    <i class="fa fa-paperclip"></i>
                </label>
                <input type="file" id="fileInput-${userId}"
                       class="file-input" data-id="${userId}"
                       multiple hidden accept="image/*" />

                <input type="text" id="newMessage-${userId}" placeholder="Nhập tin nhắn ..." />
                <button class="send-btn" data-id="${userId}">Gửi</button>
            </div>
        </div>
    </div>`;
        $("#chat-container").append(chatHtml);
    }

    $(document).on("click", ".chat-img", function () {
        let src = $(this).attr("src");
        $("#modalImg").attr("src", src);
        $("#imageModal").fadeIn(200);
    });

    $(document).on("click", ".image-modal .close", function () {
        $("#imageModal").fadeOut(200);
    });

    $(document).on("click", "#imageModal", function (e) {
        if (e.target.id === "imageModal") {
            $("#imageModal").fadeOut(200);
        }
    });


    function loadMessages(userId) {
        $.ajax({
            url: `/client/chat/${userId}`,
            method: "GET",
            success: function (data) {
                let box = $("#messagesBox-" + userId);
                box.empty();

                data.forEach(msg => renderMessage(box, msg));
                box.scrollTop(box[0].scrollHeight);
            }
        });
    }

    function sendMessage(userId) {
        let input = $("#newMessage-" + userId);
        let message = input.val().trim();
        let fileInput = $("#fileInput-" + userId)[0];
        let media = fileInput.files;

        if (!message && media.length === 0) return;

        let formData = new FormData();
        formData.append("message", message);
        for (let i = 0; i < media.length; i++) {
            formData.append("media[]", media[i]);
        }

        $.ajax({
            url: `/client/chat/send/${userId}`,
            method: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                let box = $("#messagesBox-" + userId);
                renderMessage(box, data);
                box.scrollTop(box[0].scrollHeight);

                input.val("");
                fileInput.value = "";
                $(`#previewBox-${userId}`).html("");
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function renderMessage(box, msg) {
        let cls = msg.sender_id == currentUserId ? "own-message" : "other-message";
        let time = new Date(msg.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

        let status = "";
        if (msg.sender_id == currentUserId) {
            if (msg.is_read) {
                status = `<span class="msg-status read">Đã xem</span>`;
            } else if (msg.is_sent) {
                status = `<span class="msg-status sent">Đã gửi</span>`;
            }
        }

        let textHtml = msg.message
            ? `<div class="chat-text">${msg.message}</div>`
            : "";

        let mediaHtml = "";
        if (msg.media && msg.media.length > 0) {
            mediaHtml = `<div class="chat-media">`;
            msg.media.forEach(file => {
                url = "/storage/" + file.path;
                mediaHtml += `<img src="${url}" class="chat-img" alt="img" />`;
            });
            mediaHtml += `</div>`;
        }

        box.append(`
    <div class="chat-bubble ${cls}" data-id="${msg.id}">
        ${textHtml}
        ${mediaHtml}
        <div class="chat-time">${time} ${status}</div>
        <div class="chat-actions">
            <button class="edit-btn" data-id="${msg.id}"><i class="fa fa-pen"></i></button>
            <button class="delete-btn" data-id="${msg.id}"><i class="fa fa-trash"></i></button>
        </div>
    </div>
`);

    }

    $(document).on("click", ".delete-btn", function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Tin nhắn này sẽ bị xóa!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/client/chat/delete/${id}`,
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: function (res) {
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: "Tin nhắn đã được xóa",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        let bubble = $(`.chat-bubble[data-id="${res.id}"]`);
                        bubble.find(".chat-text").text(res.new_message);
                        bubble.find(".chat-media").remove();
                        bubble.addClass("delete-message");
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Xóa thất bại!",
                            text: "Đã có lỗi xảy ra, vui lòng thử lại."
                        });
                    }
                });
            }
        });
    });

    if (typeof window.Echo === "undefined") {
        window.Echo = new window.Echo({
            broadcaster: 'pusher',
            key: '777b3c737a36e1ea77c8',
            cluster: 'ap1',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        });
    }

    window.Echo.channel(`chat.${currentUserId}`)
        .listen("MessageSent", (e) => {
            let sender = e.user;
            let msg = e.message;

            if ($("#chatbox-" + sender.id).length === 0) {
                openChatBox(sender.id, sender.name);
            }

            let box = $("#messagesBox-" + sender.id);
            renderMessage(box, msg);
            box.scrollTop(box[0].scrollHeight);

        });
});
