<style type="text/css">
    .modal-dialog80 {
        width: 80% !important;
    }
    .modal-command {
        padding: 15px;
        text-align: right;
        border-top: 1px solid #e5e5e5;
    }
</style>
<!-- modal -->
<div class="modal fade" id="available_room" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog80 modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= $form['class_name'] ?> 第<?= $form['term']; ?>期 訓練期程<?= $form['range'] ?>小時 預約教室</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                    <input type="hidden" id="seq_no" name="seq_no" value="<?= set_value('seq_no', $form['seq_no']); ?>">
                    <div class="row">
                        <label class="col-xs-4 control-label text-right">使用日期: </label>
                        <div class="col-xs-6">
                            <div class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control <?= form_error('start_date') ? 'has-error' : ''; ?> datepicker" name="daterange" value="<?= $form['start_date'] ?> - <?= $form['end_date'] ?>" />
                                    <!--input type="text" name="daterange" value="01/01/2015 - 01/31/2015" /-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group form-check col-xs-6 required ">
                            <label class="control-label">教室類別</label>
                            <div class="form-check">
                                <div class="checkbox-inline">
                                    <input class="form-check-input" type="checkbox" name="room_type" id="Check1" value="A" <?= set_checkbox('class_room_type_A', 'A', $filter['class_room_type_B'] == 'B'); ?>>
                                    <label class="form-check-label" for="Check1">一般教室</label>
                                </div>
                                <div class="checkbox-inline">
                                    <input class="form-check-input" type="checkbox" name="room_type" id="Check2" value="B" <?= set_checkbox('class_room_type_B', 'B', $filter['class_room_type_B'] == 'B'); ?>>
                                    <label class="form-check-label" for="Check2">電腦教室</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-command">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="query_available">查詢</button>
                    </div>
                </div>
                <div class="card-body pad table-responsive">
                    <table class="table table-bordered table-sm" id="myData" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary" id="btn">開始預約教室</button-->
            </div>
        </div>
    </div>
</div>