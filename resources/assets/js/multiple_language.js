$(document).ready(function(){

    /* multiple language */
    $("#countries").msDropdown();

    $('.btn-multiple-language').change(function(e) {
        e.preventDefault();
        divChangeAmount = $(this).parent();
        var route = $('.hide_language').data('route');
        var lang = $('.btn-multiple-language').val();
        var token = $('.hide_language').data('token');

        $.ajax({
            type: 'POST',
            url: route,
            dataType: 'JSON',
            data: {
                'lang': lang,
                '_token': token,
            },
            success: function(data){
                if (data.success) {
                    window.location.href = data.url_back;
                }
            }
        });
    });

    $('.edit-save-note').on('click', function() {
        if ($('.edit-content-note').val() == '') {
            $('.edit-message-note').html($('.message-note').data('messageNote'));
        } else {
            $('#edit-note-campaign').submit();
        }
    });

    $('.create-save-note').on('click', function() {
        if ($('.create-content-note').val() == '') {
            $('.create-message-note').html($('.message-note').data('messageNote'));
        } else {
            $('#create-note-campaign').submit();
        }
    });
});

