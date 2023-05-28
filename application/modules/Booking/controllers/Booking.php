<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Booking extends AdminController
{
	protected $choices = array();

	public function __construct()
	{
		parent::__construct();
		//$this->logged_in();
		//$this->smarty_acl->authorized('roles'); // Even do authorize check. 最新的檢查
		// 預約場地/教室
		$this->load->model('planning/booking_place_model');
		$this->load->model('planning/createclass_model');
		$this->load->model('data/reservation_time_model');
		
		$this->choices['time_list'] = $this->reservation_time_model->getChoices();
		$this->load->model('booking_model', 'model');

		if (empty($this->data['filter']['start_date'])) {
            $this->data['filter']['start_date'] = date('Y-m-d', time() - (86400 * 7));
        }

        if (empty($this->data['filter']['end_date'])) {
            $this->data['filter']['end_date'] = date('Y-m-d', time() );
        }
	}

	public function add($seqNo=null)
	{
		$id= $seqNo; //27973;
		$data = [
			'title' => 'Booking Room',
			'form' => $this->booking_place_model->getFormDefault(),
			'filter' => $this->data['filter']
		];
		$old_data = null;
		$bookRecords = [];
		if(!empty($id)){
            $conditions = array(
                'seq_no' => $id,
            );
            $old_data = $this->createclass_model->get($conditions); // select * from require where seq_no = $id
            $data['form'] = array(
                'year' => $old_data['year'],
                'class_no' => $old_data['class_no'],
                'term' => $old_data['term'],
                'class_name' => $old_data['class_name'],
                'start_date' => $old_data['start_date1'],
                'end_date' => $old_data['end_date1'],
                'seq_no' => $old_data['seq_no'],
				'range' => $old_data['range'],
				'no_persons' => $old_data['no_persons']
            );
            //$bookRecords = $this->booking_place_model->getBooking($id);
        } else {
			$data['form']['no_persons'] = 0; // 本期人數
			$data['form']['range'] = 0; // 訓練期程(小時)
		}
		//$data['booking'] = $bookRecords;
		$data['choices'] = $this->choices;
		
		//$this->load->view('_layout/general/head', $data);
		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$this->load->view('index', $data); // query_bookingroom
		$this->load->view('modal/available_room', $data);
	}

	function getLists($id=NULL)
	{
		$data = array();
		$this->data['form'] = $this->booking_place_model->getFormDefault();
		$old_data = null;
		$bookRecords = [];
		if(!empty($id)){
            $conditions = array(
                'seq_no' => $id,
            );
            $old_data = $this->createclass_model->get($conditions);
            $this->data['form'] = array(
                'year' => $old_data['year'],
                'class_no' => $old_data['class_no'],
                'term' => $old_data['term'],
                'class_name' => $old_data['class_name'],
                'start_date' => $old_data['start_date1'],
                'end_date' => $old_data['end_date1'],
                'seq_no' => $old_data['seq_no'],
            );
            $bookRecords = $this->booking_place_model->getBooking($id);
        }
		//$this->data['booking'] = $data = $this->booking_place_model->getBooking($id);
		$i = 0;
		if (sizeof($bookRecords) > 0) {
			$i = $_POST['start'];
			foreach ($bookRecords as $rec) {
				$d = (object)$rec;
				$i++;
				$btn_edit = '<button type="button" class="btn btn-warning btn-xs edit" data-cat_id="' . $d->cat_id .'" data-class_no="' . $d->class_no . '" data-seq_no="' . $d->seq_no . '"><i class="fas fa-fw fa-pen"></i> 修改</button>';
				$btn_hapus = '<button type="button" class="btn btn-danger btn-xs hapus"  data-cat_id="' . $d->cat_id . '"> 刪除</button>';
				
				$_period = $this->choices['time_list'][$d->booking_period];
				$roomType = $this->choices['room_type'][$d->cat_id];

				$data[] = array($i, $d->cat_id, $d->seq_no, $d->start_date, $d->end_date, $d->room_name, $_period, $btn_edit . ' ' . $btn_hapus);
				//$data[] = array($i, $d->start_date, $d->end_date, $d->room_name, $btn_edit . ' ' . $btn_hapus);
			}
		}
		/*$i++;
		$btn_add = '<button type="button" class="btn btn-warning btn-xs edit" data-role="' . $d->name . '" data-id_role="' . $d->id . '"><i class="fas fa-fw fa-pen"></i> 預約</button>';
		$data[] = array($i++, '2', $old_data['seq_no'], $old_data['start_date1'], $old_data['end_date1'], '', '', $btn_add);
		$data[] = array($i++, '3', $old_data['seq_no'], $old_data['start_date1'], $old_data['end_date1'], '', '', $btn_add);
		$data[] = array($i++, '4', $old_data['seq_no'], $old_data['start_date1'], $old_data['end_date1'], '', '', $btn_add);/** */

		$output = array(
			//"draw" => $_POST['draw'],
			//"recordsTotal" => $this->model->countAll(),
			//"recordsFiltered" => $this->model->countFiltered($_POST),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function getAvailable() {

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$this->load->view('_layout/general/head', $data);
		$this->load->view('index', $data);

	}

}
