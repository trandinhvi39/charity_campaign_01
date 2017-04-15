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

    var dataTags = $('.hide-tags').data('tags');
    var arrTags = [];

    if (dataTags) {
        for (var i = 0; i < dataTags.length; i++) {
            arrTags.push(dataTags[i].name);
        }

        var citynames = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: $.map(arrTags, function (city) {
                return {
                    name: city
                };
            })
        });
        citynames.initialize();

        $('#category').tagsinput({
            typeaheadjs: [{
                minLength: 1,
                highlight: true,
            },{
                minlength: 1,
                name: 'citynames',
                displayKey: 'name',
                valueKey: 'name',
                source: citynames.ttAdapter()
            }],
            freeInput: true
        });
    }
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
