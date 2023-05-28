<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_evaluate_score extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('old_report/old_report_model');

        if (!isset($this->data['filter']['page'])) {
            $this->data['filter']['page'] = '1';
        }
    }
    public function index()
    {
        $thisyear = date("Y")-1911;
        $year = isset($_GET['year'])?intval($_GET['year']):$thisyear;
        $season = isset($_GET['season'])?intval($_GET['season']):"";
        $type = isset($_GET['type'])?intval($_GET['type']):"";
        $startMonth = isset($_GET['startMonth'])?intval($_GET['startMonth']):"";
        $endMonth = isset($_GET['endMonth'])?intval($_GET['endMonth']):"";
        $start_date = isset($_GET['start_date'])?addslashes($_GET['start_date']):"";
        $end_date = isset($_GET['end_date'])?addslashes($_GET['end_date']):"";
        $class_no = isset($_GET['query_class_no'])?addslashes($_GET['query_class_no']):"";
        $class_name = isset($_GET['query_class_name'])?addslashes($_GET['query_class_name']):"";
        $ssd = $start_date;
        $sed = $end_date;

        if($type == 1 || $type == 2  ){
            $dateRange = $this->old_report_model->getDataRange($year,$type,$season,$startMonth,$endMonth);
            $ssd = $dateRange[0]; 
            $sed = $dateRange[1]; 
        }
        else if($type == 0){
            if($year != ""){
                $dateRange = $this->old_report_model->getOneYear($year);
                $ssd = $dateRange[0]; 
                $sed = $dateRange[1]; 
            } 
        }
        
        $this->load->library('pagination');
        $page = $this->data['filter']['page'];
        $rows = $this->data['filter']['rows'];
        $total = 0;
        $this->data['sess_year'] = $year;
        $this->data['sess_season'] = $season;
        $this->data['sess_type'] = $type;
        $this->data['sess_startMonth'] = $startMonth;
        $this->data['sess_endMonth'] = $endMonth;
        $this->data['sess_start_date'] = $start_date;
        $this->data['sess_end_date'] = $end_date;
        $this->data['sess_class_no'] = $class_no;
        $this->data['sess_class_name'] = $class_name;
        $this->data['link_refresh'] = base_url("old_report/class_evaluate_score/");
        $this->data['datas'] = array();
        if($year != ""){
            $list = $this->old_report_model->getScoreList($year,$ssd,$sed,$class_no,$class_name);

            $this->data['filter']['total'] = $total = count($list);
            $this->data['filter']['offset'] = $offset = ($page -1) * $rows;

            if(isset($_GET['iscsv'])  && $_GET['iscsv'] != 1  ){
                $list = $this->old_report_model->getScoreList($year,$ssd,$sed,$class_no,$class_name,$rows, $offset);
            }

            for($i=0;$i<count($list);$i++){
                $cnt = $this->old_report_model->get_cnt_gender_avg($list[$i]['class_no'],$list[$i]['year'],$list[$i]['term']);
                $list[$i]['score'] = $this->old_report_model->getScore($list[$i]['class_no'],$list[$i]['year'],$list[$i]['term']);
                $list[$i]['male'] = round($cnt['m_agg'], 2);
                $list[$i]['female'] = round($cnt['f_agg'], 2);

                if($list[$i]['map1'] == '1'){
                    $list[$i]['topic'] = 'A營造永續環境';
                } else if($list[$i]['map2'] == '1'){
                    $list[$i]['topic'] = 'B健全都市發展';
                } else if($list[$i]['map3'] == '1'){
                    $list[$i]['topic'] = 'C發展多元文化';
                } else if($list[$i]['map4'] == '1'){
                    $list[$i]['topic'] = 'D優化產業勞動';
                } else if($list[$i]['map5'] == '1'){
                    $list[$i]['topic'] = 'E強化社會支持';
                } else if($list[$i]['map6'] == '1'){
                    $list[$i]['topic'] = 'F打造優質教育';
                } else if($list[$i]['map7'] == '1'){
                    $list[$i]['topic'] = 'G精進健康安全';
                } else if($list[$i]['map8'] == '1'){
                    $list[$i]['topic'] = 'H精實良善治理';
                }

                $list[$i]["full_class_name"] = $list[$i]["year"].'年 '.$list[$i]["class_name"].' 第'.$list[$i]["term"].'期';

                if(!empty($list[$i]["question_id"]) && !empty($list[$i]["qid"])){
                    $list[$i]["export_url"] = base_url("old_report/class_evaluate_score/exportCsv?question_id={$list[$i]['question_id']}&qid={$list[$i]['qid']}&y={$list[$i]['year']}&c={$list[$i]['class_no']}&t={$list[$i]['term']}&type=5");
                } else {
                    $list[$i]["export_url"] = '';
                }
            }          

            $this->data['datas'] = $list;
            $this->data['dayOfWeek'] = $this->old_report_model->getDayOfWeek();
            if(isset($_GET['iscsv'])  && $_GET['iscsv'] == 1  ){
                $filename = date("Ymd").'.csv';
                header("Content-type: application/vnd.ms-excel");  
                header("Content-Disposition: attachment; filename=$filename");
                echo iconv('UTF-8', 'BIG5', "臺北市政府公務人員訓練處,");
                echo iconv('UTF-8', 'BIG5', "班期評估分數查詢\r\n");
                echo iconv('UTF-8', 'BIG5', "{$ssd}至{$sed}\r\n");
                echo iconv('UTF-8', 'BIG5', "班期類別,");
                echo iconv('UTF-8', 'BIG5', "次類別,");
                echo iconv('UTF-8', 'BIG5', "局處,");
                echo iconv('UTF-8', 'BIG5', "策略主題,");
                echo iconv('UTF-8', 'BIG5', "承辦人,");
                echo iconv('UTF-8', 'BIG5', "班期名稱,");
                echo iconv('UTF-8', 'BIG5', "開班起日,");
                echo iconv('UTF-8', 'BIG5', "開班迄日,");
                echo iconv('UTF-8', 'BIG5', "評估平均分數,");
                echo iconv('UTF-8', 'BIG5', "平均分數(男),");
                echo iconv('UTF-8', 'BIG5', "平均分數(女),");
                echo iconv('UTF-8', 'BIG5', "\r\n");  
                for($i=0;$i<count($list);$i++){
                    echo iconv('UTF-8', 'BIG5', $list[$i]['master_cate']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['sub_cate']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['bname']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['topic']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['worker_name']).',';
                    echo iconv('UTF-8', 'BIG5', "{$list[$i]['year']}年 {$list[$i]['class_name']} 第{$list[$i]['term']}期").',';
                    echo iconv('UTF-8', 'BIG5', date('Y-m-d',strtotime($list[$i]["start_date1"]))).',';
                    echo iconv('UTF-8', 'BIG5', date('Y-m-d',strtotime($list[$i]["end_date1"]))).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['score']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['male']).',';
                    echo iconv('UTF-8', 'BIG5', $list[$i]['female']).',';
                    echo "\r\n";
                }
            }
            else{
                $config['total_rows'] = $total;
                $config['per_page'] = $rows;
                $this->pagination->initialize($config);
                $this->layout->view('old_report/class_evaluate_score/list',$this->data);
            }
        }
        else{
            $config['total_rows'] = $total;
            $config['per_page'] = $rows;
            $this->pagination->initialize($config);
            $this->layout->view('old_report/class_evaluate_score/list',$this->data);
        }
        
    }

    public function exportCsv()
    {
        $questionId=intval(trim($_GET['question_id']));
        $qid=intval(trim($_GET['qid']));
        $year=intval(trim($_GET['y']));
        $term=intval(trim($_GET['t']));
        $type=intval(trim($_GET['type']));
        $c_no=addslashes(trim($_GET['c']));

        $dataList = $this->old_report_model->getContent($qid>0?$qid:0);
        $Q_ary = array();
        $where = "";
        foreach ($dataList as $key => $v) {
            if($v['aid']=='999999') {
                $Q_ary['CID'][] = $v['cid'];
                $where .= "'".intval($v['cid'])."',";
                $Q_ary['NAME'][] = $v['name3'];
            }
        }
        $where = substr($where, 0, strlen($where)-1);

        $dataList = $this->old_report_model->getContent_17I_excel($c_no, $year, $term, $where);

        $data_output = array();
        for($i=0;$i<count($Q_ary['CID']);$i++) {
            for($j=0;$j<count($dataList);$j++) {
                if($Q_ary['CID'][$i]==$dataList[$j]['ITEM']) {
                    $ans_ary = array();
                    $ans_ary['question'] = $Q_ary['NAME'][$i];
                    $ans_ary['answer'] = $dataList[$j]['ANSWER'];
                    $ans_ary['cnt'] = $dataList[$j]['cnt'];
                    array_push($data_output, $ans_ary);
                }
            }
        }

        header("Content-type: application/csv");    
        header("Content-Disposition: attachment; filename=17I_type1.csv;charset=UTF-8");
        header("Pragma: no-cache");
        header("Expires: 0");

    
        $title = "題目,";
        $title .= "答案,";
        $title .= "人次\r\n";
        echo mb_convert_encoding($title , 'Big5', 'UTF-8');
        
        $output = '';
        foreach($data_output as $v) {
            //$csv .= '"' . $row['id'] . '",';
            $output .= '"'.$v['question'].'",';
            $output .= '"'.preg_replace('/\s+/', ' ',$v['answer']).'",'; // remove white space
            $output .= '"'.$v['cnt'].'"';
            $output .= PHP_EOL;
        }
        echo mb_convert_encoding($output , 'Big5', 'UTF-8');
        exit;
    }  
    

}
