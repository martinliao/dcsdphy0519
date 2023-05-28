define(['jquery',"core/log",'mod_Room/js2', 'mod_bootstrapbase/bootstrap','datatables'], function($, log, js2) {
var Example = {
    //document.addEventListener("DOMContentLoaded", () => {
    init: function() {
        self= this;
        $("#query_bookingroom").submit(function(e) {
            e.preventDefault();
            console.log('submit!!');
            var seqNo = $(this).find('input[name="seq_no"]').val();
            self.getBookingLists(seqNo);
            return false;
        });

        //const form = $('.modal-body').html();
        $('#booking_table').on('click', '.edit', function() {
            //initDaterange0();
            //initDaterange1('<?=$form['start_date']?>', '<?=$form['end_date']?>');
            catId = $(this).data('cat_id');
            seqNo = $(this).data('seq_no');
            //var tmp = $("#querybooking").modal("show");
            var tmp = $("#available_room").modal("show");
            //console.log(tmp);
            //$('#show_data').load('<?= site_url('Room') ?>');
            debugger;
            js2.init();
        });
    },

    getBookingLists: function(seqNo) {
        //console.log('getLists');
        //console.log('seqNo: ' + seqNo);
        var csrfObj = { };
        //"<?= $csrf['name']; ?>": "<?= $csrf['hash']; ?>"
        csrfObj[M.cfg.csrfname] = M.cfg.csrfhash;
        //var tmp = $('#booking_table');
        if (!$.fn.DataTable.isDataTable('#booking_table')) {
            self = this;
            $('#booking_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "data": csrfObj,
                    "url": M.cfg.wwwroot + 'Room/getLists' + '/' + seqNo,
                    "type": "POST"
                },
                "columnDefs": [{
                    "targets": [0,1],
                    "orderable": false,
                    "width": '5%'
                }],
                'info': false,
                'paging': false,
                'searching': false,
                "drawCallback": function( settings ) {
                    self.init();
                }
            });
            $('#booking_table').DataTable().columns([2]).visible(false);
        } else {
            $('#booking_table').DataTable().ajax.reload();
        }
    },

    cb: function (start, end) {
        $('input[name="daterange"] span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    },

    initDaterange: function () {
        var start = moment().subtract(29, 'days');
        var end = moment();

        $('input[name="daterange"]').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    },

    // 最簡單的 + 最常用功能
    initDaterange1: function (start, end) {
        $('input[name="daterange"]').daterangepicker({
            timePicker: false,
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: start,
            endDate: end,
            ranges: {
                '今天': [moment(), moment()],
                '往回1個月': [moment().subtract(29, 'days'), moment()],
                '往前1個月': [moment(), moment().add(29, 'days')],
                '這個月': [moment().startOf('month'), moment().endOf('month')],
                '下個月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
    },

    // 最簡單的
    initDaterange0: function () {
        $('input[name="daterange"]').daterangepicker({
            timePicker: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    }
}

    return Example;
});