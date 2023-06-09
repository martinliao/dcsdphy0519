
    <!-- jQuery 3 -->
    <script src="<?= HTTP_PLUGIN; ?>jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= HTTP_PLUGIN; ?>bootstrap-3.4.1-dist/js/bootstrap.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?= HTTP_PLUGIN; ?>jquery-ui/jquery-ui.min.js"></script>
    <!-- Slimscroll
    <script src="<?= HTTP_PLUGIN; ?>jquery-slimscroll/jquery.slimscroll.min.js"></script> -->
    <!-- FastClick
    <script src="<?= HTTP_PLUGIN; ?>fastclick/lib/fastclick.js"></script> -->
    <!-- AdminLTE App
    <script src="<?= HTTP_JS; ?>adminlte.min.js"></script> -->

    <!-- fullCalendar -->
    <!-- <script src="<?= HTTP_PLUGIN; ?>moment/moment.js"></script> -->

    
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=HTTP_PLUGIN;?>metisMenu/dist/metisMenu.min.js"></script>
    <!-- Add fancyBox main JS and CSS files -->
    <script type="text/javascript" src="<?=HTTP_PLUGIN;?>fancybox/jquery.fancybox.js?v=2.1.5"></script>

    <? if (!empty($site_js)) : ?>
		<? foreach ($site_js as $js) : ?>
		    <script type="text/javascript" src="<?=base_url() . $js;?>"></script>
		<? endforeach; ?>
	<? endif; ?>

    <script type="text/javascript">
        var _json = { _ALERT : {} };
        <?php if (isset($_JSON)): ?>
            _json = <?=json_encode($_JSON);?>;
        <?php endif; ?>
        var CI = CI || _json || {};
        $(document).ready(function(){
            $("a[rel=fancybox_group]").fancybox({
                prevEffect : 'none',
                nextEffect : 'none',
                closeBtn  : true,
            });
        });
    </script>
    <!-- foot -->
    <!-- <script src="<?= HTTP_PLUGIN; ?>datepicker/js/jquery-ui-datepicker.js"></script> -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("active");
        });
    </script>