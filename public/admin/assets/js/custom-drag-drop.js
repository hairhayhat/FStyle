document.addEventListener("DOMContentLoaded", function () {
    const dropArea = document.getElementById("drop-area");
    const input = document.getElementById("imageInput");
    const previewImg = document.getElementById("preview");

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
            showPreview(files[0]);
        }
    });

    input.addEventListener("change", function () {
        if (this.files.length > 0) {
            showPreview(this.files[0]);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove("d-none");
        };

        reader.readAsDataURL(file);
    }
});
