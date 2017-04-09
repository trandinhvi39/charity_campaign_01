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

    var dataTags = $('.hide-tags').data('tags');
    var arrTags = [];

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
});

