document.addEventListener("DOMContentLoaded", function () {
    const dropArea = document.getElementById("drop-area");
    const input = document.getElementById("imageInput");
    const previewContainer = document.getElementById("preview-container");

    dropArea.addEventListener("click", () => input.click());

    dropArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropArea.classList.add("dragover");
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("dragover");
    });

    dropArea.addEventListener("drop", (e) => {
        e.preventDefault();
        dropArea.classList.remove("dragover");

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            showPreviews(files);
        }
    });

    input.addEventListener("change", function () {
        if (this.files.length > 0) {
            showPreviews(this.files);
        }
    });

    function showPreviews(files) {
        previewContainer.innerHTML = ""; // Xoá preview cũ
        Array.from(files).forEach(file => {
            if (!file.type.startsWith("image/")) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("img-preview", "m-1");
                img.style.maxHeight = "100px";
                img.style.borderRadius = "5px";
                img.style.border = "1px solid #ddd";
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
});
