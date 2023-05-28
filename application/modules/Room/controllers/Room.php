<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room extends AdminController
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
		$this->load->model('room_model', 'model');

		if (empty($this->data['filter']['start_date'])) {
			$this->data['filter']['start_date'] = date('Y-m-d', time() - (86400 * 7));
		}

		if (empty($this->data['filter']['end_date'])) {
			$this->data['filter']['end_date'] = date('Y-m-d', time());
		}
	}

	public function add($seqNo = null)
	{
		$id = $seqNo; //27973;
		$data = [
			'title' => 'Booking Room',
			'form' => $this->booking_place_model->getFormDefault(),
			'filter' => $this->data['filter']
		];
		$old_data = null;
		$bookRecords = [];
		if (!empty($id)) {
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

		$this->load->view('_layout/general/head', $data);
		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		//$this->load->view('core/modals', $data);
		//$this->load->view('core/js', $data);
		$this->load->view('core/modal2', $data);
		$this->load->view('index', $data);
		//$this->load->view('core/js2', $data);
		//$this->load->view('core/init', $data);
	}

	function getLists($id = NULL)
	{
		$data = array();
		$this->data['form'] = $this->booking_place_model->getFormDefault();
		$old_data = null;
		$bookRecords = [];
		if (!empty($id)) {
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
				$btn_edit = '<button type="button" class="btn btn-warning btn-xs edit" data-cat_id="' . $d->cat_id . '" data-class_no="' . $d->class_no . '" data-seq_no="' . $d->seq_no . '"><i class="fas fa-fw fa-pen"></i> 修改</button>';
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

	function getAvailableRoom()
	{
		$_post = $this->input->post();
		$conditions = array(
			"room_time" => addslashes($_post['room_time']),
			"room_type" => addslashes($_post['room_type']),
			"start_date" => addslashes($_post['start_date']),
			"end_date" => addslashes($_post['end_date']),
		);
		$availableRecords= $this->booking_place_model->getPlace($conditions);
		$i = 0;
		$data = array();
		if (sizeof($availableRecords) > 0) {
			foreach ($availableRecords as $rec) {
				$d = (object)$rec;
				$i++;
				$_dataTag = $this->_dataTag([
					'data-room_id' => $d->room_id,
					'data-room_sname' => $d->room_sname,
					'data-room_cap' => $d->room_cap
				]);
				$btn_book = '<button type="button" class="btn btn-warning btn-xs edit" ' .  $_dataTag . '><i class="fas fa-fw fa-pen"></i> 訂!!</button>';
				$data[] = array($i, $d->room_id, $d->room_name, $d->room_sname, $d->room_cap, $btn_book);
			}
		}
		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	/**
	 * Ref from: Classroom_usage_list
	 * 	/planning/classroom_usage_list/?room_type=01&start_date=2023-05-22&end_date=2023-05-28
	 */
	public function getReservation()
    {
		// room_type=01&start_date=2023-05-17&end_date=2023-05-27
        $this->data['page_name'] = 'index';
        $conditions = array();
		if ($this->data['filter']['start_date'] != '') {
            $conditions['start_date'] = $this->data['filter']['start_date'];
        }
        if ($this->data['filter']['end_date'] != '') {
            $conditions['end_date'] = $this->data['filter']['end_date'];
        }
        if ($this->data['filter']['class_room_type_B'] != '') {
            $conditions['class_room_type']['B'] = $this->data['filter']['class_room_type_B'];
        }
        if ($this->data['filter']['class_room_type_C'] != '') {
            $conditions['class_room_type']['C'] = $this->data['filter']['class_room_type_C'];
        }
        if ($this->data['filter']['class_room_type_E'] != '') {
            $conditions['class_room_type']['E'] = $this->data['filter']['class_room_type_E'];
        }
        if ($this->data['filter']['red_class'] != '') {
            $conditions['red_class'] = $this->data['filter']['red_class'];
        }
        if ($this->data['filter']['only_time'] != '') {
            $conditions['only_time'] = $this->data['filter']['only_time'];
        }

        if ($this->data['filter']['room_type'] != '') {
            $conditions['cat_id'] = $this->data['filter']['room_type'];
            $this->data['choices']['room'] = $this->booking_place_model->get_room($this->data['filter']['room_type'], TRUE);
        }else{
            $this->data['filter']['room'] = '';
            $this->data['choices']['room'] = array();
            if(isset($this->data['filter']['sort']) && $this->data['filter']['room_type']==''){
                $this->setAlert(3, '請選擇場地類別');
                redirect(base_url("planning/classroom_usage_list"));
            }
        }
        if ($this->data['filter']['room'] != '') {
            $conditions['room_id'] = $this->data['filter']['room'];
        }

        if($this->data['filter']['room_type'] != ''){
            if(isDate($conditions['start_date']) && isDate($conditions['end_date'])){
                $days = ((strtotime($conditions['end_date'])-strtotime($conditions['start_date'])) / 86400) + 1;
                if($days>300){
                    $this->setAlert(3, '為避免查詢時間過久,請設定日期區間在300日內');
                    redirect(base_url("planning/classroom_usage_list"));
                }
                $this->data['list'] = $this->booking_place_model->select_usage_list($conditions);
                if($this->data['filter']['only_time']=='Y'){
                    for($j=0;$j<count($this->data['list']);$j++){
                        foreach ($this->data['list'][$j] as $key =>& $value) {

                            if(strpos($key,'-')==true){
                                
                                $index=[];
                                for($i=count($value)-1;$i>=1;$i--){
                                    if($value[$i]['CNAME']==$value[$i-1]['CNAME']&&$value[$i]['BOOKING_DATE']==$value[$i-1]['BOOKING_DATE']&&
                                        $value[$i]['CLASS_NAME']==$value[$i-1]['CLASS_NAME']&&$value[$i]['Year']==$value[$i-1]['Year']&&
                                        $value[$i]['TERM']==$value[$i-1]['TERM']&&$value[$i-1]["BTYPE"]=='3'){
                                                $value[$i-1]["TO_TIME"]=$value[$i]["TO_TIME"];
                                                array_push($index,$i);
                                            
                                    }

                                }
                                
                                if(!empty($index)){
                                    for($z=0;$z<count($index);$z++){
                                        unset($value[$index[$z]]);
                                    }
                                    $value=array_values($value);
                                    
                                }
                            
                            }
                        }
                    }
                }
            }else{
                $this->setAlert(3, '日期錯誤');
                redirect(base_url("planning/classroom_usage_list"));
            }
        }else{
            $this->data['list'] = array();
        }

		#$this->data['link_refresh'] = base_url("planning/classroom_usage_list/");
		#$this->data['room_export'] = base_url("planning/classroom_usage_list/export/?{$_SERVER['QUERY_STRING']}");
        #$this->data['select_url'] = base_url("planning/classroom_usage_list/?{$_SERVER['QUERY_STRING']}");
        #$this->layout->view('planning/classroom_usage_list/list', $this->data);
    }

	function getAvailable0()
	{
debugBreak();
		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$post = $this->input->post();
		$result = array(
			'status' => FALSE,
			'data' => array(),
		);
		$rs = NULL;
		$error = FALSE;
		$fields = '';

		if (empty($post['start_date'])) {
			$error = TRUE;
		}
		if (empty($post['end_date'])) {
			$error = TRUE;
		}
		if (empty($post['room_type'])) {
			$error = TRUE;
		}
		if (empty($post['room_time'])) {
			$error = TRUE;
		}

		if ($error === TRUE) {
			$result['data'] = '';
		} else {
			$conditions = array(
				"room_time" => addslashes($post['room_time']),
				"room_type" => addslashes($post['room_type']),
				"start_date" => addslashes($post['start_date']),
				"end_date" => addslashes($post['end_date']),
			);
			$data = $this->booking_place_model->getPlace($conditions);
			$result['status'] = TRUE;
			$result['data'] = $data;
		}
		//$this->load->view('_layout/general/head', $data);
		//$this->load->view('index', $data);
		echo json_encode($result);
	}

	function _dataTag($array) {
		return str_replace("=", '="', http_build_query($array, null, '" ', PHP_QUERY_RFC3986)).'"';
		/*$btn_book = <<<EOL
			<button type="button" class="btn btn-warning btn-xs edit" 
				data-room_id="' . $d->room_id . '" 
				data-room_sname="' . $d->room_sname . '" 
				data-room_cap="' . $d->room_cap . '"
			>
				<i class="fas fa-fw fa-pen"></i> 訂!!
			</button>
EOL;/** */
	}

}
