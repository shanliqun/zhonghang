<?php
/**
 * 后台首页控制器
 * 
 * @author lizzyphy
 * @version 1.0 2014-12-30
 */
 /**
 * 添加登录信息
 * 
 * @modify lyb
 * @version 1.1 2014-1-3
 */

class Index extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('form');
		$this->load->model(array('admin_user_m','statistics_m'));
		if($this->admin_user_m->check_login() === FALSE) {
			redirect('d=admin&c=login');
		}
	}
	
	public function index() 
	{
		$data['username'] = $this->admin_user_m->user->username;
		$this->load->view('admin/admin_header', $data);
	}
	
	public function index_image()
	{
	    $data['username'] = $this->admin_user_m->user->username;
		$data['upload']   = $this->statistics_m->get_upload();
		$data['article']  = $this->statistics_m->get_articles();
		$data['article_num'] = $this->statistics_m->get_articles_num();
		$data['click_all'] = $this->statistics_m->get_click_count();
		$data['upload_num'] = $this->statistics_m->get_upload_all();
	    $this->load->view('admin/index_image', $data);
	}
}