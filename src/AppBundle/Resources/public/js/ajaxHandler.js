$(document).ready(function() {

    $(document).on('change','#surgerySelector',function() {
        $('#surgery').val($(this).val());
        $('#surgerySelector').slideDown();
        $('#dateSelector').slideDown();
        $('#forwardButton').slideDown();
    });

    $(document).on('click','#toTableButton',function() {
        $('#reservationTable').load('/reservation-table?date='+$('#datepicker').val()+'&surgery='+$('#surgerySelector').val());
        $('#surgerySelector').slideUp();
        $('#dateSelector').slideUp();
        $('#forwardButton').slideUp();

    });

    $(document).on('click','#reservationButton',function() {
        $('#reservationForm').load('/patient-form?date='+$('#datepicker').val()+'&surgery='+$('#surgerySelector').val()+'&hour='+$(this).data('hour'));
        $('#table').slideUp();
    });

});