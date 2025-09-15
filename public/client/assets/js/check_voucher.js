$(document).ready(function () {
    // Khi click chọn 1 voucher
    $(document).on("click", ".voucher-option", function (e) {
        e.preventDefault();

        let code = $(this).data("code");
        let orderAmount =
            parseFloat($("#cart-total-display").data("total")) || 0;

        // set input hidden để submit kèm form
        $("#voucher_code_input").val(code);

        // remove active cũ và set active cho thẻ mới
        $(".voucher-option").removeClass("active");
        $(this).addClass("active");

        if (!code) {
            $("#voucher-discount").text("-0đ");
            $("#cart-total-display").text(orderAmount.toLocaleString() + "đ");
            return;
        }

        $.ajax({
            url: "/client/voucher/check",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                code: code,
                order_amount: orderAmount,
            },
            success: function (res) {
                if (res.success) {
                    $("#voucher-discount").text(
                        `-${res.discount.toLocaleString()}đ`
                    );
                    $("#cart-total-display").text(
                        res.new_total.toLocaleString() + "đ"
                    );

                    showSuccessToast("Áp dụng voucher thành công!");
                } else {
                    showErrorToast(res.message);

                    $("#voucher-discount").text("-0đ");
                    $("#cart-total-display").text(
                        orderAmount.toLocaleString() + "đ"
                    );
                }
            },
            error: function () {
                showErrorToast("Có lỗi xảy ra, vui lòng thử lại!");
            },
        });
    });
});

function showSuccessToast(message) {
    Swal.fire({
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        background: "#4CAF50",
        color: "white",
    });
}

function showErrorToast(message) {
    Swal.fire({
        position: "top-end",
        icon: "error",
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        background: "#f44336",
        color: "white",
    });
}
