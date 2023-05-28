<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservation extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        /*if ($this->flags->is_login === FALSE) {
            redirect(base_url('welcome'));
        }/** */

        $this->load->model('planning/booking_place_model');
        $this->load->model('data/place_category_model');
        $this->load->model('data/reservation_time_model');
        $this->load->model('planning/createclass_model');

        $this->data['choices']['room_type'] = $this->place_category_model->getChoices();
        $this->data['choices']['time_list'] = $this->reservation_time_model->getChoices();

        if (empty($this->data['filter']['start_date'])) {
            $this->data['filter']['start_date'] = date('Y-m-d', time() - (86400 * 7));
        }

        if (empty($this->data['filter']['end_date'])) {
            $this->data['filter']['end_date'] = date('Y-m-d', time());
        }
        if (empty($this->data['filter']['room_type'])) {
            $this->data['filter']['room_type'] = '';
        }
        if (empty($this->data['filter']['room'])) {
            $this->data['filter']['room'] = '';
        }

        $this->load->helper('common');
    }

    public function getREventData()
    {
        $result= [];
        $conditions = array();
        $conditions['start_date'] = $this->data['filter']['start_date'];
        $conditions['end_date'] = $this->data['filter']['end_date'];
        $conditions['cat_id'] = $this->data['filter']['room_type'];
        if ($this->data['filter']['room'] != '') {
            $conditions['room_id'] = $this->data['filter']['room'];
        }
        $result= $this->booking_place_model->select_usage_list($conditions);
        echo json_encode($result);
    }

    public function getREventData0()
    {
        $result= [];
        $today = getdate();
        date("Y/m/d");
	    $year= $today["year"];
	    $month= $today["mon"];
	    $day= $today["mday"];
        $result[] = array(
            'title' => 'All Day Event',
            'start' => date("$year-$month-1"),
            'backgroundColor' => '#f56954',
            'borderColor' => '#f56954'
        );
        //echo "day : " . $day;
        //echo "intval: " . strval(intval($day)-5);
        //echo " 5: " . intval($day)-5;
        $result[] = array(
            'title' => 'Long Event',
            'start' => date("$year-$month-" . strval(intval($day)-5)),
            'end' => date("$year-$month-" . strval(intval($day)-2)),
            'backgroundColor' => '#f39c12',
            'borderColor' => '#f39c12'
        );
        echo json_encode($result);
    }

    public function index()
    {
        $this->data['page_name'] = 'index';
        $conditions = array();
//debugBreak();
        if ($this->data['filter']['start_date'] != '') {
            $conditions['start_date'] = $this->data['filter']['start_date'];
        }
        if ($this->data['filter']['end_date'] != '') {
            $conditions['end_date'] = $this->data['filter']['end_date'];
        }
        if ($this->data['filter']['room_type'] != '') {
            $conditions['cat_id'] = $this->data['filter']['room_type'];
            $this->data['choices']['room'] = $this->booking_place_model->get_room($this->data['filter']['room_type'], TRUE);
        } else {
            $this->data['filter']['room'] = '';
            $this->data['choices']['room'] = array();
            if (isset($this->data['filter']['sort']) && $this->data['filter']['room_type'] == '') {
                $this->setAlert(3, '請選擇場地類別');
                redirect(base_url("planning/classroom"));
            }
        }
        if ($this->data['filter']['room'] != '') {
            $conditions['room_id'] = $this->data['filter']['room'];
        }

        if ($this->data['filter']['room_type'] != '') {
            if (isDate($conditions['start_date']) && isDate($conditions['end_date'])) {
                $days = ((strtotime($conditions['end_date']) - strtotime($conditions['start_date'])) / 86400) + 1;
debugBreak();
                if ($days > 30) {
                    $this->setAlert(3, '搜尋日期請勿超過30天');
                    //redirect(base_url("planning/classroom"));
                    redirect();
                }
                $this->data['list'] = $this->booking_place_model->select_booking($conditions);
            } else {
                $this->setAlert(3, '日期錯誤');
                redirect(base_url("planning/classroom"));
            }
        } else {
            $this->data['list'] = array();
        }

        $this->data['link_add'] = base_url("planning/classroom/add/?{$_SERVER['QUERY_STRING']}");
        $this->data['link_refresh'] = base_url("planning/classroom/");

        //$this->layout->view('planning/classroom/list', $this->data);
        $this->render_page('reservation', $this->data);
    }
}
