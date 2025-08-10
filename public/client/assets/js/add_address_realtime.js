$(document).ready(function () {
    // Khi modal hiển thị, focus vào input đầu tiên
    $('#addAddress').on('shown.bs.modal', function () {
        $('#full_name').focus();
    });

    // Khi modal ẩn đi, focus trở về nút mở modal
    $('#addAddress').on('hidden.bs.modal', function () {
        $('[data-bs-target="#addAddress"]').focus();
    });

    // Xử lý submit form
    $('#addAddressForm').on('submit', function (e) {
        e.preventDefault();

        // Xóa lỗi cũ
        $('#addAddressForm .text-danger').remove();
        $('#addAddressForm .is-invalid').removeClass('is-invalid');

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    let newAddressHtml = `
        <div class="col-xl-4 col-md-6">
            <label class="save-details">
                <input type="radio" name="selected_address" value="${response.data.id}" ${response.data.is_default ? 'checked' : ''}>
                <span class="badge-nickname">${response.data.nickname}</span>
                <div class="save-name"></div>
                <div class="save-address">
                    <p><strong>Địa chỉ:</strong> ${response.data.address}</p>
                </div>
                <div class="mobile">
                    <p>Họ và tên: ${response.data.full_name}</p>
                </div>
                <div class="mobile">
                    <p>Sđt: ${response.data.phone}</p>
                </div>
            </label>
        </div>`;

                    // Thêm địa chỉ mới vào đầu danh sách
                    $('#addressList .row').prepend(newAddressHtml);

                    // Nếu là địa chỉ mặc định, bỏ chọn tất cả các địa chỉ khác
                    if (response.data.is_default) {
                        $('input[name="selected_address"]').not(`[value="${response.data.id}"]`).prop('checked', false);
                    }

                    // Đóng modal và reset form
                    $('#addAddress').modal('hide');
                    $('#addAddressForm')[0].reset();
                    $('#is_default').prop('checked', false);

                    // Hiệu ứng thêm mới (tuỳ chọn)
                    $('html, body').animate({
                        scrollTop: $('#addressList').offset().top
                    }, 500);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        let input = $(`#addAddressForm [name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="text-danger mt-1 small">${errors[field][0]}</div>`);
                    }
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            }
        });
    });
});
