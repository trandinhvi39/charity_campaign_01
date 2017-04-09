$(document).ready(function(){
    $('.btn-filter-hotest').on('click', function() {
        var filterFollow =  $('.btn-filter-hotest').html();
        callAjaxForFilter(filterFollow);
    });

    $('.btn-filter-oldest').on('click', function() {
        var filterFollow =  $('.btn-filter-oldest').html();
        callAjaxForFilter(filterFollow);
    });

    $('.btn-filter-newest').on('click', function() {
        var filterFollow =  $('.btn-filter-newest').html();
        callAjaxForFilter(filterFollow);
    });

    $('.btn-filter-open').on('click', function() {
        var filterFollow =  $('.btn-filter-open').html();
        callAjaxForFilter(filterFollow);
    });

    $('.btn-filter-closed').on('click', function() {
        var filterFollow =  $('.btn-filter-closed').html();
        callAjaxForFilter(filterFollow);
    });
});

function callAjaxForFilter(filterFollow) {
    var route = $('.hide').data('routeFilter');

    $.ajax({
        type: 'POST',
        url: route,
        dataType: 'JSON',
        data: {
            'filter_follow': filterFollow,
        },
        success: function(data){
            if (data.success) {
                $('.list-campaign').html(data.html)
            }
        }
    });
}
