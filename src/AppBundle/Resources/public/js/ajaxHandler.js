$(document).ready(function() {

    $(document).on('change','#surgerySelector',function() {
        $('#surgery').val($(this).val());
        $('#surgerySelector').show();
        $('#dateSelector').show();
        $('#forwardButton').show();
    });

    $(document).on('click','#toTableButton',function() {
        $('#reservationTable').load('/reservation_table?date='+$('#datepicker').val()+'&surgery='+$('#surgerySelector').val());
        $('#surgerySelector').hide();
        $('#dateSelector').hide();
        $('#forwardButton').hide();

    });

    $(document).on('click','#reservationButton',function() {
        $('#reservationForm').load('/reservation_table?date='+$('#datepicker').val()+'&surgery='+$('#surgerySelector').val()+'&hour='+$(this).data('hour'));
        $('#table').hide();
    });

});