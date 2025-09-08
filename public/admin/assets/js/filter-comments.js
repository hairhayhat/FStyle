$(document).ready(function () {
    $('#commentFilterForm select').on('change', function () {
        loadComments();
    });

    $(document).on('click', '.btn-status', function () {
        let status = $(this).data('status');
        if ($('input[name=image]').length === 0) {
            $('#commentFilterForm').append('<input type="hidden" name="image" value="">');
        }
        $('input[name=image]').val(status);
        loadComments();
    });

    $(document).on('click', '#commentTableWrapper .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        loadComments(url);
    });

    function loadComments(url) {
        let baseUrl = $('#commentTableWrapper').data('url');
        url = url ?? baseUrl;

        let formData = $('#commentFilterForm').serialize();
        $.ajax({
            url: url,
            type: "GET",
            data: formData,
            success: function (res) {
                $('#commentTableWrapper').html(res.html);
            },
            error: function (xhr) {
                console.error("Không tải được dữ liệu:", xhr);
            }
        });
    }
});
