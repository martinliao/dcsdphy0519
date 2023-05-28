\<style type="text/css">
    .modal-dialog90 {
        width: 80% !important;
        display: block !important;
    }

    .modal-dialog90 .modal-body {
        height: 70vh;
        overflow-y: auto;
    }

    /*.modal-dialog {
        overflow-y: initial !important
    }

    .modal-body {
        height: 80vh;
        overflow-y: auto;
    }/** */
</style>
<!-- query_available -->
<div class="modal fade" id="available_room" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <!-- modal-lg modal-dialog-centered -->
    <div class="modal-dialog modal-dialog90 ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= $form['class_name'] ?> 第<?= $form['term']; ?>期 訓練期程<?= $form['range'] ?>小時 預約教室</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="query_available" class="form-inline">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                    <input type="hidden" id="seq_no" name="seq_no" value="<?= set_value('seq_no', $form['seq_no']); ?>">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">使用日期</label>
                            <div class="input-daterange input-group" style="width: 320px;">
                                <input type="text" class="form-control datepicker input-sm" id="test1" name="start_date" value="<?= $filter['start_date']; ?>" />
                                <span class="input-group-addon" style="cursor: pointer;" id="test2"><i class="fa fa-calendar"></i></span>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="form-control datepicker input-sm" id="datepicker1" name="end_date" value="<?= $filter['end_date']; ?>" />
                                <span class="input-group-addon" style="cursor: pointer;" id="datepicker2"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">類別</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="room_type" id="Check1" value="A" <?= set_checkbox('class_room_type_A', 'A', $filter['class_room_type_B'] == 'B'); ?>>
                                一般教室
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="room_type" id="Check2" value="B" <?= set_checkbox('class_room_type_B', 'B', $filter['class_room_type_B'] == 'B'); ?>>
                                電腦教室
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">時段</label>
                            <select class="form-control" id="room_time" name="room_time">
                                <!-- onchange="get_place();" -->
                                <option value="">請選擇</option>
                                <?php foreach ($choices['time_list'] as $key => $time) { ?>
                                    <?php if ($key == 16) : ?>
                                        <option value="<?= $key; ?>" selected><?= $time; ?></option>
                                    <?php else : ?>
                                        <option value="<?= $key; ?>"><?= $time; ?></option>
                                    <?php endif; ?>
                                <?php } ?>
                            </select>
                        </div>
                        <button class="btn btn-info btn-sm">查詢</button>
                        <!-- <button type="submit" class="btn btn-primary" id="btn">開始預約教室</button> -->
                        <!-- <a class="btn btn-info btn-sm" onclick="doClear()">清除</a> -->
                    </div>
                </form>
                <hr />
                <div class="card card-primary card-outline">
                    <div class="card-header">
                    </div>
                    <!-- <div class="card-body pad table-responsive"> -->
                    <table class="table table-bordered table-sm" id="available_table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Room nmae</th>
                                <th>Short nmae</th>
                                <th>人數</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="data">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <!-- <button type="submit" class="btn btn-primary" id="btn">開始預約教室</button> -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['jquery', "core/log", "mod_Booking/js2", 'mod_bootstrapbase/bootstrap'], function($, log, booking2) {
        log.debug('available_room.php loading...');
        booking2.init();
    });
</script>