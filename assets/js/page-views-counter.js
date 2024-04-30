jQuery(document).ready(function($) {
    // Функція для збільшення лічильника переглядів через AJAX
    function increasePageViews(post_id) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'increase_page_views',
                post_id: post_id
            },
            success: function(response) {
                // console.log('Page views increased: ' + response);
                // Встановлюємо значення переглядів на сторінці як відповідь від сервера
                $('#page_views_count').text(response);
            },
            error: function(xhr, status, error) {
                // console.error('AJAX error: ' + error);
            }
        });
    }

    // Викликаємо функцію для збільшення лічильника при завантаженні сторінки
    var postId = $('#post_id').val(); // Замініть на відповідне значення поля post_id
    if(postId) {
        increasePageViews(postId);
    }
});