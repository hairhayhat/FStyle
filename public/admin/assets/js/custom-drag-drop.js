document.addEventListener("DOMContentLoaded", function () {
    const dropArea = document.getElementById("drop-area");
    const input = document.getElementById("imageInput");
    const preview = document.getElementById("preview");

    // Click để mở chọn ảnh
    dropArea.addEventListener("click", () => input.click());

    // Dragover hiệu ứng
    dropArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropArea.classList.add("dragover");
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("dragover");
    });

    // Drop file
    dropArea.addEventListener("drop", (e) => {
        e.preventDefault();
        dropArea.classList.remove("dragover");
        const file = e.dataTransfer.files[0];
        if (file) {
            input.files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    // Hiển thị ảnh xem trước
    input.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            showPreview(this.files[0]);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove("d-none");
        };
        reader.readAsDataURL(file);
    }
});
