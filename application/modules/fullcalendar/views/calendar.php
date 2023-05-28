<!-- fullCalendar -->
<!-- <link rel="stylesheet" href="<?= HTTP_PLUGIN; ?>fullcalendar/dist/fullcalendar.min.css"> -->
<!-- <link rel="stylesheet" href="<?= HTTP_PLUGIN; ?>fullcalendar/dist/fullcalendar.print.min.css" media="print"> -->
<!-- Theme style -->
<!-- <link rel="stylesheet" href="<?= HTTP_CSS; ?>AdminLTE.min.css"> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left: 0;" id="show_data">
</div>
<!-- /.content-wrapper -->

<!-- Page specific script -->
<script type="text/javascript">
    /*$(document).ready(function() {
        //setTimeout(function() {
        //    $(".preloader").fadeOut();
        //}, 1000);
    });/** */
    // require(['jquery', "core/log", "mod_Booking/js", 'mod_bootstrapbase/bootstrap'], function($, log, booking) {
    //     booking.getBookingLists(<?= set_value('seq_no', $form['seq_no']); ?>);
    //     log.debug('index.php loading...');
    //     booking.init();
    // });
    document.addEventListener("DOMContentLoaded", () => {
        //require(['jquery', "core/log", "mod_fullcalendar/js", 'mod_bootstrapbase/bootstrap', 'css!static/plugin/fullcalendar/fullcalendar.css', 'fullcalendar'], function($, log, cal) {
        require(['jquery', "core/log", "mod_fullcalendar/js", 'mod_bootstrapbase/bootstrap'], function($, log, fullcal) {
            $(document).ready(function() { // on document ready
                log.debug('ready...');
                fullcal.init();
                log.debug('After calendar.php.init()');
                //var page = window.location.hash.substr(1);
                //if (page == "") page = "fullcalendar/smallCalendar";
                //$('#show_data').load('<?= site_url() ?>' + '/fullcalendar/smallCalendar');
            });
        });
    });
</script>