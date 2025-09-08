$(document).ready(function () {
    const $commentBox = $('.list-comments');
    const slug = $commentBox.data('slug');

    function loadComments(page = 1) {
        const rating = $('.rating-filter.active').data('rating') || '';
        const order = $('#commentOrder').val();
        const media = $('#commentMedia').val();

        $.ajax({
            url: `/product/${slug}/comments`,
            type: 'GET',
            data: { page, rating, order, media },
            success: function (data) {
                $commentBox.html(data);
                window.scrollTo({ top: $commentBox.offset().top - 100, behavior: 'smooth' });
            },
            error: function () {
                alert('Không tải được bình luận.');
            }
        });
    }

    $(document).on('click', '.rating-filter', function () {
        $('.rating-filter').removeClass('active');
        $(this).addClass('active');
        loadComments(1);
    });

    $('#commentOrder, #commentMedia').on('change', function () {
        loadComments(1);
    });

    $(document).on('click', '.list-comments .pagination a', function (e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        loadComments(page);
    });
});
