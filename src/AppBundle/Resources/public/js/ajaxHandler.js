$(document).ready(function() {

    $(document).on('change','#surgerySelector',function() {
        $('#surgery').val($(this).val());
        $('#step1').show();
        $('#step2').show();
    });

    $(document).on('click','#toTableButton',function() {
        $('#table').show();
    });

    $(document).on('click','#reservationButton',function() {
        $('#reservationForm').load('/reservation_table?date='+$('#datepicker').val()+'&surgery='+$('#surgerySelector').val()+'&hour='+$(this).data('hour'));

    });

});