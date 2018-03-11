$(document).ready(function() {


    // $(document).on('click', '#surgery_and_date_form_submit', function(event) {
    //     $('.ajax-switcher').attr('id', 'reservation-table');
    //     var container = $(this).closest('[data-pjax-container]');
    //     var containerSelector = '#' + container.id;
    //     $.pjax.click(event, {container: containerSelector})
    // });

    $(document).on('click','#welcome', function() {
        $('.ajax-switcher').attr('id', 'surgery-date');
        $( '#surgery-date' ).load('/ajax-router/surgery-date').hide();
        $( '#welcome-box' ).hide();
        $( '#surgery-date' ).fadeIn(2000);
    });


    $(document).on('click','#surgery_and_date_form_submit', function() {
        $('.ajax-switcher').attr('id', 'reservation-table');
            $( '#reservation-table' ).load('/ajax-router/reservation-table').hide();
            $( '#reservation-table' ).fadeIn(2000);
    });

    $(document).on('click','#reservationButton',function() {
        $('.ajax-switcher').attr('id', 'summary');
        $( '#summary' ).load('ajax-router/summary').hide();
        $( '#summary' ).fadeIn(2000);
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

    $(document).on('click','#summary', function() {
        $('.ajax-switcher').attr('id', 'summary');
        $( '#summary_page' ).load('/ajax-router/summary').hide();
        $( '#summary_page' ).fadeIn(2000);
    });


});