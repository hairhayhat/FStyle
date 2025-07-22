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
            showPreview(files);
        }
    });

    input.addEventListener("change", function () {
        if (this.files.length > 0) {
            showPreview(this.files);
        }
    });

    function showPreview(files) {
        previewContainer.innerHTML = "";

        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("img-thumbnail", "m-1");
                img.style.maxHeight = "150px";
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
});
