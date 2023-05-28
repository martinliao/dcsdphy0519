<!-- Include Date Range Picker -->
<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" /> -->

<script type="text/javascript">
    //document.addEventListener("DOMContentLoaded", () => {
        $("#query_bookingroom").submit(function(e) {
            e.preventDefault();
            console.log('submit!!');
            var seqNo = $(this).find('input[name="seq_no"]').val();
            getBookingLists(seqNo);
            return false;
        });
    //});

    function getBookingLists(seqNo) {
        //console.log('getLists');
        //console.log('seqNo: ' + seqNo);
        var csrfObj = {
            "<?= $csrf['name']; ?>": "<?= $csrf['hash']; ?>"
        };
        //var tmp = $('#booking_table');
        if (!$.fn.DataTable.isDataTable('#booking_table')) {
            $('#booking_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "data": csrfObj,
                    "url": "<?php echo site_url('Room/getLists'); ?>"+"/"+seqNo,
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
            });
            $('#booking_table').DataTable().columns([2]).visible(false);
        } else {
            $('#booking_table').DataTable().ajax.reload();
        }
    }

    function cb(start, end) {
        $('input[name="daterange"] span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    function initDaterange() {
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
    }

    // 最簡單的 + 最常用功能
    function initDaterange1(start, end) {
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
    }

    // 最簡單的
    function initDaterange0() {
        $('input[name="daterange"]').daterangepicker({
            timePicker: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    }

    //const form = $('.modal-body').html();
    $('#booking_table').on('click', '.edit', function() {
//debugger;
        initDaterange0();
        //initDaterange1('<?=$form['start_date']?>', '<?=$form['end_date']?>');
        catId = $(this).data('cat_id');
        seqNo = $(this).data('seq_no');
        //var tmp = $("#querybooking").modal("show");
        var tmp = $("#available_room").modal("show");
        //console.log(tmp);
        //$('#show_data').load('<?= site_url('Room') ?>');
    });
</script>
