define(['jquery',"core/log"], function($,log) {

var Example = {
    init: function () {
        self= this;
        $("input[name='room_type']").change(function() {
            $.each($("input[name='room_type']"), function() {
                this.setCustomValidity('');
            });
            var checked = $("input[name='room_type']:checked").length;
            if (checked == 0) {
                $("input[name='room_type']")[0].setCustomValidity('請至少選擇1種.');
            }
        });
        $("#query_available").submit(function(e) {
            e.preventDefault();
            console.log('submit!!');
            var checked = $("input[name='room_type']:checked").length;
            if (checked == 0) {
                $("input[name='room_type']")[0].setCustomValidity('請至少選擇1種.');
                $("input[name='room_type']")[0].reportValidity();
                return false;
            }
            var seqNo = $(this).find('input[name="seq_no"]').val();
            // room_type=01&room=&start_date=2023-05-08&end_date=2023-05-15
            self.getRoomSchedule('01', '2023-05-08', '2023-05-15');
            return false;
        });
    },

    getRoomSchedule: function (roomType, startDate, endDate) {
        console.log('getRoomSchedule');
        console.log('startDate: ' + startDate);
        console.log('endDate: ' + endDate);
        self= this;
        // planning/classroom?sort=&room_type=01&room=&start_date=2023-05-08&end_date=2023-05-15
        var dataObj = {
            //"<?= $csrf['name']; ?>" : "<?= $csrf['hash']; ?>",
            'room_type' : roomType,
            'start_date' : startDate,
            'end_date' : endDate
        };
        dataObj[M.cfg.csrfname] = M.cfg.csrfhash;
        if (!$.fn.DataTable.isDataTable('#available_table')) {
            $('#available_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "data": dataObj,
                    "url": M.cfg.wwwroot + 'Room/getAvailable' + '?room_type=01&start_date=2023-05-08&end_date=2023-05-15',
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
            $('#available_table').DataTable().columns([2]).visible(false);
        } else {
            $('#available_table').DataTable().ajax.reload();
        }
    }
}

    return Example;
});