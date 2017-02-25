$(document).ready(function(){

    $(document).on( 'click', '.add-image', function (e) {
        e.preventDefault();

        divChangeAmount = $(this).parent();
        var addImageLayouts = divChangeAmount.data('addImageLayouts');
        $('.add-images').append(addImageLayouts);
    });

    $(document).on( 'click', '.add-schedule', function (e) {
        e.preventDefault();

        divChangeAmount = $(this).parent();
        var addScheduleLayouts = divChangeAmount.data('addScheduleLayouts');
        $('.list-schedules').append(addScheduleLayouts);
    });
});
