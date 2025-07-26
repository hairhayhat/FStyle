// document.addEventListener("DOMContentLoaded", function () {
//     ClassicEditor.create(document.querySelector("#editor")).catch((error) => {
//         console.error("CKEditor error:", error);
//     });
//     let index = 1;

//     const container = document.getElementById("variant-container");
//     const addBtn = document.getElementById("add-variant");

//     addBtn.addEventListener("click", function () {
//         const firstRow = container.querySelector(".variant-row");
//         const newRow = firstRow.cloneNode(true);

//         // Reset input và cập nhật name với index mới
//         newRow.querySelectorAll("input, select").forEach((el) => {
//             const oldName = el.getAttribute("name");
//             if (!oldName) return;
//             const newName = oldName.replace(/\d+/, index);
//             el.setAttribute("name", newName);
//             el.classList.remove("is-invalid");

//             if (el.tagName === "INPUT") {
//                 el.value = "";
//             } else if (el.tagName === "SELECT") {
//                 el.selectedIndex = 0;
//             }
//         });

//         container.appendChild(newRow);
//         initColorSelector(newRow); // Gắn màu cho dòng mới
//         index++;
//     });

//     document.addEventListener("click", function (e) {
//         if (e.target.classList.contains("remove-variant")) {
//             const rows = document.querySelectorAll(".variant-row");
//             if (rows.length > 1) {
//                 e.target.closest(".variant-row").remove();
//             }
//         }
//     });

//     // Kiểm tra trùng biến thể (color-size) khi submit
//     document.querySelector("form").addEventListener("submit", function (e) {
//         const variants = document.querySelectorAll(".variant-row");
//         const comboMap = new Map(); // Dùng Map để lưu key và vị trí dòng
//         let isDuplicate = false;

//         variants.forEach((row, idx) => {
//             const color = row.querySelector('select[name$="[color_id]"]').value;
//             const size = row.querySelector('select[name$="[size_id]"]').value;

//             const key = `${color}-${size}`;

//             if (comboMap.has(key)) {
//                 const firstIndex = comboMap.get(key);
//                 alert(
//                     `❌ Biến thể ở dòng ${idx + 1} bị trùng với dòng ${
//                         firstIndex + 1
//                     } (cùng màu & size)!`
//                 );
//                 isDuplicate = true;
//             } else {
//                 comboMap.set(key, idx);
//             }
//         });

//         if (isDuplicate) {
//             e.preventDefault(); // Ngăn submit
//             e.stopPropagation(); // Ngăn bubble
//             return false; // Ngăn chắc chắn submit lại
//         }
//     });
//     // === Logic cho ảnh chính ===
//     const mainDropArea = document.getElementById("main-drop-area");
//     const mainImageInput = document.getElementById("main-image-input");
//     const mainPreview = document.getElementById("main-preview");

//     mainDropArea.addEventListener("click", () => mainImageInput.click());

//     mainDropArea.addEventListener("dragover", (e) => {
//         e.preventDefault();
//         mainDropArea.classList.add("bg-light");
//     });

//     mainDropArea.addEventListener("dragleave", () => {
//         mainDropArea.classList.remove("bg-light");
//     });

//     mainDropArea.addEventListener("drop", (e) => {
//         e.preventDefault();
//         mainDropArea.classList.remove("bg-light");
//         const file = e.dataTransfer.files[0];
//         if (file && file.type.startsWith("image/")) {
//             mainImageInput.files = e.dataTransfer.files;
//             showMainPreview(file);
//         }
//     });

//     mainImageInput.addEventListener("change", function () {
//         const file = this.files[0];
//         if (file && file.type.startsWith("image/")) {
//             showMainPreview(file);
//         }
//     });

//     function showMainPreview(file) {
//         const reader = new FileReader();
//         reader.onload = function (e) {
//             mainPreview.src = e.target.result;
//             mainPreview.classList.remove("d-none");
//         };
//         reader.readAsDataURL(file);
//     }

//     // Khởi tạo hiển thị màu cho tất cả các dòng đang có
//     document.querySelectorAll(".variant-row").forEach(initColorSelector);
// });
// // Hàm riêng để khởi tạo chọn màu cho 1 dòng biến thể
// function initColorSelector(row) {
//     const colorSelect = row.querySelector(".color-select");
//     const colorBadge = row.querySelector(".selected-color-display .badge");

//     if (!colorSelect || !colorBadge) return;

//     const updateColor = () => {
//         const selectedOption = colorSelect.options[colorSelect.selectedIndex];
//         const colorCode = selectedOption.getAttribute("data-color");
//         if (colorCode) {
//             colorBadge.style.backgroundColor = colorCode;
//             colorBadge.style.border = "1px solid #ccc";
//         } else {
//             colorBadge.style.backgroundColor = "transparent";
//         }
//     };
//     colorSelect.addEventListener("change", updateColor);
//     updateColor(); // Gọi ban đầu
// }
// //CKEditor
// document.addEventListener("DOMContentLoaded", function () {});
// //
