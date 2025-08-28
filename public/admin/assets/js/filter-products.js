$(document).ready(function () {
    $('#productFilterForm select, #productFilterForm input[type=radio]').on('change', function () {
        loadOrders();
    });

    $(document).on('click', '.btn-status', function () {
        let image = $(this).data('status');
        if ($('input[name=image]').length === 0) {
            $('#productFilterForm').append('<input type="hidden" name="image" value="">');
        }
        $('input[name=image]').val(image);
        loadOrders();
    });


    $(document).on('click', '#productTableWrapper .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        loadOrders(url);
    });

    function loadOrders(url) {
        let baseUrl = $('#productTableWrapper').data('url');
        url = url ?? baseUrl;

        let formData = $('#productFilterForm').serialize();
        $.ajax({
            url: url,
            type: "GET",
            data: formData,
            success: function (res) {
                $('#productTableWrapper').html(res.html);
            }
        });
    }
});
