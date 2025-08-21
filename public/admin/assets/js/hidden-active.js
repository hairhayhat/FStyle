$(document).ready(function () {
    $('.toggle-status').change(function () {
        var commentId = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '/admin/comments/toggle-status/' + commentId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { status: status },
            success: function (response) {
                if (response.success) {
                    $('span.status-text[data-id="' + commentId + '"]').text(response.status ? 'Hiển thị' : 'Ẩn');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.status ? 'Bình luận đã hiển thị' : 'Bình luận đã ẩn',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            }
        });
    });

});

