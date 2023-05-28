<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_code extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ($this->flags->is_login === FALSE) {
			redirect(base_url('welcome'));
		}

		$this->load->model('data/course_code_model');

        if (!isset($this->data['filter']['page'])) {
            $this->data['filter']['page'] = '1';
        }
        if (!isset($this->data['filter']['sort'])) {
            $this->data['filter']['sort'] = '';
        }
        if (!isset($this->data['filter']['q'])) {
            $this->data['filter']['q'] = '';
        }
	}

	public function index()
	{
		$this->data['page_name'] = 'list';

        $page = $this->data['filter']['page'];
        $rows = $this->data['filter']['rows'];

        $conditions = array();

		$attrs = array(
            'conditions' => $conditions,
        );
        if ($this->data['filter']['q'] !== '' ) {
            $attrs['q'] = $this->data['filter']['q'];
        }

        $this->data['filter']['total'] = $total = $this->course_code_model->getListCount($attrs);
        $this->data['filter']['offset'] = $offset = ($page -1) * $rows;

        $attrs = array(
            'conditions' => $conditions,
            'rows' => $rows,
            'offset' => $offset,
        );
        if ($this->data['filter']['q'] !== '' ) {
            $attrs['q'] = $this->data['filter']['q'];
        }
        if ($this->data['filter']['sort'] !== '' ) {
            $attrs['sort'] = $this->data['filter']['sort'];
        }

		$this->data['list'] = $this->course_code_model->getList($attrs);
		foreach ($this->data['list'] as & $row) {
			$row['link_edit'] = base_url("data/course_code/edit/{$row['id']}/?{$_SERVER['QUERY_STRING']}");
		}
		$this->load->library('pagination');
        $config['base_url'] = base_url("data/course_code?". $this->getQueryString(array(), array('page')));
        $config['total_rows'] = $total;
        $config['per_page'] = $rows;
        $this->pagination->initialize($config);

		$this->data['link_add'] = base_url("data/course_code/add/?{$_SERVER['QUERY_STRING']}");
		$this->data['link_delete'] = base_url("data/course_code/delete/?{$_SERVER['QUERY_STRING']}");
		$this->data['link_refresh'] = base_url("data/course_code/");

		$this->layout->view('data/course_code/list', $this->data);
	}

	public function add()
	{
		$this->data['page_name'] = 'add';
		$this->data['form'] = $this->course_code_model->getFormDefault();

		if ($post = $this->input->post()) {
			if ($this->_isVerify('add') == TRUE) {
				$post['create_time'] = date('Y-m-d H:i:s');
				$post['create_user'] = $this->flags->user['id'];
				$post['modify_time'] = date('Y-m-d H:i:s');
				$post['modify_user'] = $this->flags->user['id'];
				$saved_id = $this->course_code_model->_insert($post);
				if ($saved_id) {
					$this->setAlert(1, '資料新增成功');
				}

				redirect(base_url('data/course_code'));
			}
		}

		$this->data['link_save'] = base_url("data/course_code/add/?{$_SERVER['QUERY_STRING']}");
		$this->data['link_cancel'] = base_url("data/course_code/?{$_SERVER['QUERY_STRING']}");
		$this->layout->view('data/course_code/add', $this->data);
	}

	public function edit($id=NULL)
	{
		$this->data['page_name'] = 'edit';
		$this->data['form'] = $this->course_code_model->getFormDefault($this->course_code_model->get($id));

		if ($post = $this->input->post()) {
			$old_data = $this->course_code_model->get($id);
			if ($this->_isVerify('edit', $old_data) == TRUE) {
				$post['modify_time'] = date('Y-m-d H:i:s');
				$post['modify_user'] = $this->flags->user['id'];
				$rs = $this->course_code_model->_update($id, $post);
				if ($rs) {
					$this->setAlert(2, '資料編輯成功');
				}
				redirect(base_url("data/course_code/?{$_SERVER['QUERY_STRING']}"));
			}
		}

		$this->data['link_save'] = base_url("data/course_code/edit/{$id}/?{$_SERVER['QUERY_STRING']}");
		$this->data['link_cancel'] = base_url("data/course_code/?{$_SERVER['QUERY_STRING']}");
		$this->layout->view('data/course_code/edit', $this->data);
	}

	public function delete()
	{
		if ($post = $this->input->post()) {
			foreach ($post['rowid'] as $id) {
				$rs = $this->course_code_model->delete($id);
			}
			$this->setAlert(2, '資料刪除成功');
		}

		redirect(base_url("data/course_code/?{$_SERVER['QUERY_STRING']}"));
	}

	private function _isVerify($action='add', $old_data=array())
	{
		$config = $this->course_code_model->getVerifyConfig();

		if ($action == 'edit') {
			if($old_data['item_id'] == $this->input->post('item_id')){
				$config['item_id']['rules'] = '';
			}
		}

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		// $this->form_validation->set_message('required', '請勿空白');

		return ($this->form_validation->run() == FALSE)? FALSE : TRUE;
	}
}
