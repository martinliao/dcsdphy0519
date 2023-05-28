define(['jquery', 'core/log', 'mod_Booking/js', 'datatables'], function($, log, booking) {
    //define(['jquery', 'core/log', 'mod_Booking/js', 'datatables', 'daterangepicker'], function($, log, booking) {
    $('#tambah').click(function() {
        var seqNo = $(this).data('seq_no');
        /*$("#booking_room").on('shown.bs.modal', function (e){
            log.debug('shown');
            $('#show_data').load(M.cfg.wwwroot + 'Booking/add/' + seqNo);
        });/** */

        $('#show_booking_data').load(M.cfg.wwwroot + 'Booking/add/' + seqNo);
        $("#booking_room").modal("show");
    });
    /*$("#booking_room").on("show", function () {
        $("body").addClass("modal-open");
    }).on("hidden", function () {
        $("body").removeClass("modal-open")
    });/** */
    $("#booking_room").on('shown.bs.modal', function (e) {
        log.debug('booking_room is shown.');
    });
});