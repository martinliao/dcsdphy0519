define(['jquery',"core/log"], function($,log) {

var Example = {
    init: function () {
        self= this;
        log.debug($("#query_available"));
debugger;
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
            // ToDo: No need these more.
            var seqNo = $(this).find('input[name="seq_no"]').val();
            var startDate = $(this).find('input[name="start_date"]').val();
            var endDate = $(this).find('input[name="end_date"]').val();
            // $(this).find('input[name="room_type"]').val()
            var timeId = $(this).find('select[name="room_time"]').val();
            self.getRoomSchedule('01', parseInt(timeId), startDate, endDate);
            return false;
            // room_type=01&room=&start_date=2023-05-08&end_date=2023-05-15
            // self.getRoomSchedule('01', 16, '2023-05-08', '2023-05-15');
        });
    },

    getRoomSchedule: function (roomType, timeId, startDate, endDate) {
        console.log('timeId: ' + timeId);
        // console.log('startDate: ' + startDate);
        // console.log('endDate: ' + endDate);
        self= this;
        // planning/classroom?sort=&room_type=01&room=&start_date=2023-05-08&end_date=2023-05-15
        if (!$.fn.DataTable.isDataTable('#available_table')) {

            $('#available_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": M.cfg.wwwroot + 'Room/getAvailableRoom',
                    "type": "POST",
                    "data": function(d){
                        var dataObj = {
                            'room_type' : '01',
                            "start_date": $("#query_available").find('input[name="start_date"]').val(),
                            "end_date": $("#query_available").find('input[name="end_date"]').val(),
                            "room_time": $("#query_available").find('select[name="room_time"]').val(),
                        };
                        dataObj[M.cfg.csrfname] = M.cfg.csrfhash;
                        return $.extend( {}, d, dataObj);
                    }
                },
                "columnDefs": [{
                    "targets": [0,1,5],
                    "orderable": false,
                    "width": '5%'
                }],
                'info': false,
                'paging': false,
                'searching': false,
                "drawCallback": function( settings ) {
                    //self.init();
                    log.debug('draw2 done. ToDo: check booking.');
                }
            });
            //$('#available_table').DataTable().columns([2]).visible(false);
        } else {
            $('#available_table').DataTable().ajax.reload();
        }
    }
}

    return Example;
});