$(document).ready(function () {
    const currentUserId = $("#chatPreviewDropdown").data("user-id");
    let totalNewMessages = parseInt($('#chatBadge').text()) || 0;

    $(document).on("click", "#chatPreviewDropdown .media", function () {
        let userId = $(this).data("user");
        let userName = $(this).data("user-name");

        if ($("#chatbox-" + userId).length === 0) {
            openChatBox(userId, userName);
            loadMessages(userId);
        }

        let p = $(this).find('.media-body p');
        let text = p.text();
        let match = text.match(/\+(\d+)/);
        if (match) {
            let count = parseInt(match[1]);
            totalNewMessages -= count;
            if (totalNewMessages < 0) totalNewMessages = 0;
            $('#chatBadge').text(totalNewMessages);
        }

        p.text("Không có tin nhắn mới");
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

    function loadMessages(userId) {
        $.ajax({
            url: `/admin/chat/${userId}`,
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
            url: `/admin/chat/send/${userId}`,
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

        if (msg.status == 1) {
            cls = msg.sender_id == currentUserId ? "own-message-delete" : "other-message-delete";
        }
        if (msg.status == 2) {
            cls = msg.sender_id == currentUserId ? "own-message-edit" : "other-message-edit";
        }

        let time = new Date(msg.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

        let status = "";
        if (msg.sender_id == currentUserId && msg.status != 1) {
            if (msg.is_read) {
                status = `<span class="msg-status read">Đã xem</span>`;
            } else if (msg.is_sent) {
                status = `<span class="msg-status sent">Đã gửi</span>`;
            }
        }

        let textHtml = msg.message && msg.status != 1
            ? `<div class="chat-text" data-id="${msg.id}">${msg.message}</div>`
            : "";


        let mediaHtml = "";
        if (msg.media && msg.media.length > 0 && msg.status != 1) {
            mediaHtml = `<div class="chat-media">`;
            msg.media.forEach(file => {
                let url = "/storage/" + file.path;
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
                    url: `/admin/chat/delete/${id}`,
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

                        bubble.find(".chat-text").remove();
                        bubble.find(".chat-media").remove();

                        if (bubble.hasClass("own-message") || bubble.hasClass("own-message-edit")) {
                            bubble.removeClass("own-message own-message-edit").addClass("own-message-delete");
                        } else {
                            bubble.removeClass("other-message other-message-edit").addClass("other-message-delete");
                        }

                        bubble.find(".chat-actions").remove();
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

    $(document).on("click", ".edit-btn", function () {
        let msgId = $(this).data("id");
        let bubble = $(`.chat-bubble[data-id="${msgId}"]`);
        let messageText = bubble.find(".chat-text").text();

        bubble.data("original-text", messageText);

        let chatbox = bubble.closest(".chatbox");
        let input = chatbox.find("input[id^='newMessage-']");
        input.val(messageText).focus();
        input.data("edit-id", msgId);
        input.data("edit-bubble", bubble);

        input.addClass("edit-mode");

        let sendBtn = chatbox.find(".send-btn");
        sendBtn.text("Lưu").removeClass("send-btn").addClass("update-btn");

        chatbox.find(".file-label, .file-input, #previewBox-" + chatbox.data("userId")).hide();

        if (chatbox.find(".cancel-edit").length === 0) {
            chatbox.find(".chat-input").append(`<button class="cancel-edit">Hủy</button>`);
        }
    });

    $(document).on("click", ".cancel-edit", function () {
        let chatbox = $(this).closest(".chatbox");
        let input = chatbox.find("input[id^='newMessage-']");
        let bubble = input.data("edit-bubble");

        if (bubble) {
            let originalText = bubble.data("original-text");
            bubble.find(".chat-text").text(originalText);
        }

        input.removeData("edit-id")
            .removeData("edit-bubble")
            .removeClass("edit-mode")
            .val("");

        let sendBtn = chatbox.find(".update-btn");
        sendBtn.text("Gửi").removeClass("update-btn").addClass("send-btn");

        $(this).remove();

        chatbox.find(".file-label, .file-input, #previewBox-" + chatbox.data("userId")).show();
    });


    $(document).on("click", ".update-btn", function () {
        let chatbox = $(this).closest(".chatbox");
        let input = chatbox.find("input[id^='newMessage-']");
        let msgId = input.data("edit-id");
        let newText = input.val().trim();
        if (!newText) return;
        $.ajax({
            url: `/admin/chat/edit/${msgId}`,
            method: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: { message: newText },
            success: function (res) {
                let bubble = input.data("edit-bubble");

                bubble.find(".chat-text").text(res.new_message);

                input.val("")
                    .removeData("edit-id")
                    .removeData("edit-bubble")
                    .removeClass("edit-mode");

                let sendBtn = chatbox.find(".update-btn");
                sendBtn.text("Gửi").removeClass("update-btn").addClass("send-btn");

                chatbox.find(".cancel-edit").remove();
                chatbox.find(".file-label, .file-input, #previewBox-" + chatbox.data("userId")).show();
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Chỉnh sửa thất bại!",
                    text: "Đã có lỗi xảy ra, vui lòng thử lại."
                });
            }
        });

    });

    $(document).on("input", "input[id^='newMessage-']", function () {
        let bubble = $(this).data("edit-bubble");
        if (bubble) {
            bubble.find(".chat-text").text($(this).val());
        }
    });

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

    window.Echo.channel(`chat.${currentUserId}`)
        .listen("MessageSent", (e) => {
            if (!e.user) return;
            let sender = e.user;
            let msg = e.message;

            if ($(`#chatbox-${sender.id}`).is(":visible")) {
                let box = $("#messagesBox-" + sender.id);
                renderMessage(box, msg);
                box.scrollTop(box[0].scrollHeight);

                $.ajax({
                    url: `/admin/chat/mark-as-read/${msg.id}`,
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") }
                });
            } else {
                let existing = $("#chatPreviewDropdown .media[data-user='" + sender.id + "']");

                if (existing.length === 0) {
                    let newItem = `
                    <li>
                        <div class="media" data-user="${sender.id}" data-user-name="${sender.name}">
                            <img class="img-fluid rounded-circle me-3"
                                src="${sender.avatar || 'assets/images/default-avatar.png'}"
                                alt="${sender.name}">
                            <div class="status-circle online"></div>
                            <div class="media-body">
                                <span>${sender.name}</span>
                                <p class="f-12 font-success">+1 tin nhắn mới</p>
                            </div>
                        </div>
                    </li>`;
                    $('#chatPreviewDropdown .chat-title').after(newItem);
                } else {
                    let p = existing.find('.media-body p');
                    let text = p.text();
                    let match = text.match(/\+(\d+)/);
                    let newCount = match ? parseInt(match[1]) + 1 : 1;
                    p.text(`+${newCount} tin nhắn mới`);

                    existing.find('.status-circle').removeClass('offline').addClass('online');
                }

                totalNewMessages++;
                $('#chatBadge').text(totalNewMessages).show();
            }
        });


    window.Echo.channel(`chat.${currentUserId}`)
        .listen("MessageDeleted", (e) => {
            let msg = e.message;
            let bubble = $(`.chat-bubble[data-id="${msg.id}"]`);
            if (bubble.length) {
                bubble.find(".chat-text, .chat-media, .chat-actions").remove();
                if (bubble.hasClass("own-message") || bubble.hasClass("own-message-edit")) {
                    bubble.removeClass("own-message own-message-edit").addClass("own-message-delete");
                } else {
                    bubble.removeClass("other-message other-message-edit").addClass("other-message-delete");
                }
            }
        });

    window.Echo.channel(`chat.${currentUserId}`)
        .listen("MessageEdited", (e) => {
            let msg = e.message;

            let bubble = $(`.chat-bubble[data-id="${msg.id}"]`);
            if (bubble.length) {
                bubble.find(".chat-text").text(msg.message);

                if (bubble.hasClass("own-message")) {
                    bubble.addClass("own-message-edit").removeClass("own-message");
                } else if (bubble.hasClass("other-message")) {
                    bubble.addClass("other-message-edit").removeClass("other-message");
                }
            }
        });

    window.Echo.channel(`chat.${currentUserId}`)
        .listen("MessageReaded", (e) => {
            let msg = e.message;
            let bubble = $(`.chat-bubble[data-id="${msg.id}"]`);
            if (bubble.length) {
                bubble.find(".msg-status").remove();
                bubble.find(".chat-time").append(`<span class="msg-status read">Đã xem</span>`);
            }
        });
});
