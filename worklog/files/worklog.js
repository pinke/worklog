$(document).ready(function () {
    $("input[name=log_begin]:not(:read-only),input[name=log_end]:not(:read-only)")
        .datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true});
});
