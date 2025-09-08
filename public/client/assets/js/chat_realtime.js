
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('messageToggle');
    const menu = document.getElementById('messageMenu');

    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        menu.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
        if (!menu.contains(e.target) && !toggleBtn.contains(e.target)) {
            menu.classList.remove('show');
        }
    });

    document.querySelectorAll(".user-item").forEach(function (el) {
        el.addEventListener("click", function () {
            let userId = this.getAttribute("data-id");
            let userName = this.getAttribute("data-name");

            if (document.getElementById("chatbox-" + userId)) return;

            let chatHtml = `
                <div id="chatbox-${userId}" class="chatbox">
                    <div class="chatbox-header">
                        <span>${userName}</span>
                        <span class="chatbox-close" onclick="closeChatbox(${userId})">×</span>
                    </div>
                    <div class="chatbox-body">
                        <div id="messagesBox-${userId}" class="chat-messages"></div>
                        <div class="chat-input">
                            <input type="text" id="newMessage-${userId}" placeholder="Nhập tin nhắn ..." />
                            <button onclick="sendMessage(${userId})">Gửi</button>
                        </div>
                    </div>
                </div>`;
            document.getElementById("chat-container").insertAdjacentHTML("beforeend", chatHtml);

            loadMessages(userId);
        });
    });
});

function closeChatbox(userId) {
    document.getElementById("chatbox-" + userId).remove();
}

function loadMessages(userId) {
    fetch(`/messages/${userId}`)
        .then(res => res.json())
        .then(data => {
            let box = document.getElementById("messagesBox-" + userId);
            data.forEach(msg => {
                let cls = msg.sender_id === {{ auth()->id() }} ? 'own-message' : '';
                box.innerHTML += `<div class="chat-message ${cls}">${msg.message}</div>`;
            });
            box.scrollTop = box.scrollHeight;
        });
}


