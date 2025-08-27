$(document).ready(function () {
    $('#userFilterForm select, #userFilterForm input[type=radio]').on('change', function () {
        loadUsers();
    });

    $(document).on('click', '#userTableWrapper .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        loadUsers(url);
    });

    function loadUsers(url) {
        let baseUrl = $('#userTableWrapper').data('url');
        url = url ?? baseUrl;

        let formData = $('#userFilterForm').serialize();
        $.ajax({
            url: url,
            type: "GET",
            data: formData,
            success: function (res) {
                $('#userTableWrapper').html(res.html);
            }
        });
    }
});
