$(document).ready(function() {


    // $(document).on('click', '#surgery_and_date_form_submit', function(event) {
    //     $('.ajax-switcher').attr('id', 'reservation-table');
    //     var container = $(this).closest('[data-pjax-container]');
    //     var containerSelector = '#' + container.id;
    //     $.pjax.click(event, {container: containerSelector})
    // });

    $(document).on('click','#welcome', function() {
        $('.ajax-switcher').attr('id', 'surgery-date');
        $( '#surgery-date' ).load('/ajax-router/surgery-date');
        $( '#welcome-box' ).hide();
        $( '#surgery-date' ).fadeIn(2000);
    });


    $(document).on('click','#surgery_and_date_form_submit', function() {
        $('.ajax-switcher').attr('id', 'reservation-table');
            $( '#reservation-table' ).load('/ajax-router/reservation-table?' + 'surgery=' + $('#surgery_and_date_form_surgery').val() + '&date=' + $('#surgery_and_date_form_reservation_date').val()).hide();
            $( '#reservation-table' ).fadeIn(2000);
    });

    $(document).on('click','#surgery_date', function() {
        $('.ajax-switcher').attr('id', 'surgery-date');
        $( '#surgery-date' ).load('/ajax-router/surgery-date').hide();
        $( '#surgery-date' ).fadeIn(2000);
    });

    $(document).on('click','#reservation_table', function() {
        $('.ajax-switcher').attr('id', 'reservation-table');
        $( '#reservation-table' ).load('/ajax-router/reservation-table').hide();
        $( '#reservation-table' ).fadeIn(2000);
    });

    $(document).on('click','#reservation_delete',function() {
        $('.ajax-switcher').attr('id', 'reservation-table');
        $( '#reservation-table' ).load('ajax-router/delete-reservation?' + 'id=' + $(this).data('reservation-id') + '&hour=' + $(this).data('hour')).hide();
        $( '#reservation-table' ).fadeIn(2000);
    });

    $(document).on('click','#reservationButton',function() {
        $('.ajax-switcher').attr('id', 'summary');
        $( '#summary' ).load('ajax-router/summary?' + 'hour=' + $(this).data('hour')).hide();
        $( '#summary' ).fadeIn(2000);
    });

    $(document).on('click','#reserve',function() {
        $('.ajax-switcher').attr('id', 'success');
        $( '#success' ).load('ajax-router/reservation-success').hide();
        $( '#success' ).fadeIn(2000);
    });

    $(document).on('adminAjaxNext','#adminAjaxTable',function() {
        $( '#success' ).load('ajax-router/reservation-success').hide();
        $( '#success' ).fadeIn(1000);
    });

    $(document).on('click','#admin-ajax-previous',function() {
        $('#admin-ajax-next').show();

        var page = $('#admin-ajax-table').attr('data-page');
        var fromDate = $('#admin-ajax-table').attr('data-from-date');
        var toDate = $('#admin-ajax-table').attr('data-to-date');

        $('#admin-ajax-table').attr('data-page', --page);

        if(page <= 1){
            $('#admin-ajax-previous').hide();
        }

        $( '#admin-ajax-table' ).load('admin-ajax-table' + '?page=' + page + '&fromDate=' + fromDate + '&toDate=' + toDate);
    });

    $(document).on('click','#admin-ajax-next',function() {

        $('#admin-ajax-previous').show();

        var page = $('#admin-ajax-table').attr('data-page');
        var fromDate = $('#admin-ajax-table').attr('data-from-date');
        var toDate = $('#admin-ajax-table').attr('data-to-date');

        $('#admin-ajax-table').attr('data-page', ++page);

        if(page == $('#admin-ajax-table').attr("data-page-count")){
            $('#admin-ajax-next').hide();
        }


        $( '#admin-ajax-table' ).load('admin-ajax-table' + '?page=' + page + '&fromDate=' + fromDate + '&toDate=' + toDate);
    });


});