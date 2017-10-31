$(document).on('click', 'button.ajax', function(){

    $('#times').load('/reservation_table?date='+$('#datepicker').val()+'&surgey='+$('#surgeySelector').val());

});


$(document).ready(function() {

    $(document).on('click','.hourSelectorButton',function() {
       $('#hour').val($(this).data('hour'));
       $('#step3').show();

    });

    $(document).on('change','#surgeySelector',function() {
        $('#surgey').val($(this).val());
        $('#step1').show();
        $('#step2').show();
    });

});