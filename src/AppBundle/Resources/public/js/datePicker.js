

$(function () {
            $("#datepicker").datepicker({
                    minDate: '1D',
                    maxDate: $('#datepicker').data('config-datepicker'),
                    dateFormat: 'yy-mm-dd'
                }
            );
        }
    );
