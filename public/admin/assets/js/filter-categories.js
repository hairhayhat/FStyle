
$(document).ready(function () {
    $('#filterForm select').on('change', function () {
        loadOrders();
    });
    $(document).on('click', '#orderTableWrapper .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        loadOrders(url);
    });
    function loadOrders(url) {
        let baseUrl = $('#orderTableWrapper').data('url');
        url = url ?? baseUrl;

        let formData = $('#filterForm').serialize();
        $.ajax({
            url: url,
            type: "GET",
            data: formData,
            success: function (res) {
                $('#orderTableWrapper').html(res.html);
            }
        });
    }

});

