<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 功能：后台二级标题
 * 作者：LZZ
 * 日期：2015.1.2
 */
class Subtitle extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model(array('admin_user_m','article_list_m','article_type_m','statistics_m'));
		if($this->admin_user_m->check_login() === FALSE) {
		    redirect('d=admin&c=login');
		}
		$this->load->library('uploader_ue', 'session');
	}
	
	public function index() 
	{
//		$this->load->view('admin/article_list');
	}
	
	public function  listNews()
	{
	    $pid = $_GET['pid'];
	    $tid = $_GET['tid'];
	    $per_page = 1;
	    if(isset($_GET['per_page']) && $_GET['per_page'] != ''){
	        $start_page = ((int)$_GET['per_page'] - 1) * 10;
	    } else {
	        $start_page = ($per_page - 1) * 10;
	    }
	    $count = $this->article_list_m->num_articles($tid);
	    $temp['base_url'] = base_url('admin/subtitle/listNews?pid='.$pid.'&tid='.$tid);
	    $temp['total_rows'] = (int)$count;
	    $this->article_list_m->pageConfig($temp);
	    $data['username'] = $this->admin_user_m->user->username;
	    $News = $this->article_list_m->query_article($pid,$tid,$start_page);
	    $data['name'] = $this->article_type_m->get_name($tid);
	    $data['News'] = $News;
	    $data['pid'] = $pid;
	    $data['tid'] = $tid;
		if ($tid == 15 || $tid == 16 || $tid == 17){
	      $this->load->view('admin/article_list_of_sec1',$data);
		}else{
	      $this->load->view('admin/article_list_of_sec',$data);
		};
	}
	
	public function addNews()
	{
	    $data['pid'] = $this->input->get('pid');
	    $data['tid'] = $this->input->get('tid');
	    $data['name'] = $this->article_type_m->get_name($data['tid']);
	    $data['username'] = $this->admin_user_m->user->username;
	    $data['types'] = '';
	    $data['article'] = '';
	    if ($data['tid'] == 18)
	    {
	    	$this->load->view('admin/add_news_of_job',$data);
	    } else {
	    	$this->load->view('admin/add_news_of_sec',$data);
	    }
	}
	
	public function editNews()
	{
	    $data['username'] = $this->admin_user_m->user->username;
	    $data['pid'] = $_GET['pid'];
	    $data['tid'] = $_GET['tid'];
	    $data['aid'] = $_GET['aid'];
	    $data['name'] = $this->article_type_m->get_name($data['tid']);
	    $data['types'] = $this->article_type_m->get_children();
	    $data['article'] = $this->article_list_m->get_article($data['aid']);
	    if($data['tid'] == 9) {
	    	 $data['img'] = $this->article_list_m->get_pic($data['aid']);
	    }
	    if ($data['tid'] == 18)
	    {
	    	$this->load->view('admin/add_news_of_job',$data);
	    } else if ($data['tid'] == 15 || $data['tid'] == 16 || $data['tid'] == 17){
	    	$this->load->view('admin/edit_news_of_sec1',$data);
	    }else {
	    	$this->load->view('admin/edit_news_of_sec',$data);
	    }
	}
	
	public function deleteNews()
	{
	    $pid = $_GET['pid'];
	    $tid = $_GET['tid'];
	    $aid = $_GET['aid'];
	    $this->article_list_m->delete_article($aid);
		$this->statistics_m->del_byaid($aid);
	    Header("Location:listNews?pid=".$pid."&tid=".$tid);
	}
	
	public function insertNews()
	{
	    $pid = $_GET['pid'];
	    $tid = $_GET['tid'];
	    $article['title'] = $this->input->post('title');
	    $article['type'] = $this->input->post('type');
	    $article['content'] = "";
	    if(isset($_POST['ue_content']))
	    {
	        $article['content'] = $this->input->post('ue_content');
	    }
	    if($tid == 9) {
	    	$config = array(
	    			"pathFormat" => "upload/{yyyy}{mm}{dd}/{time}{ss}" ,
	    			"maxSize" => 50000000 , //单位KB
	    			"allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp"  )
	    	);
	    	$pic = new Uploader_ue( "pic" , $config);
    		$info = $pic->getFileInfo();
    		if($info['state'] == 'SUCCESS') {
    			$goods_pic = $info['url'];
    		} else {
    			$goods_pic = '';
    		}
	    }
	    $article['username'] = $this->admin_user_m->user->username;
	    $article['add_time'] = time();
	    if($tid == 18) {
	    	$article['address'] = $this->input->post('address');
	    	$article['number'] = $this->input->post('number');
	    }
	    $aid = $this->article_list_m->add_article($article); 
	    if($tid == 9) {
	   		$this->article_list_m->add_pic($aid,$goods_pic);
	    }
	    Header("Location:listNews?pid=".$pid."&tid=".$tid);
	}
	
	public function updateNews()
	{
	    $tid = $_GET['tid'];
	    $article['aid'] = $_GET['aid'];
		if( $tid != '15' && $tid != '16' && $tid != '17') {
			$article['title'] = $_POST['title'];
			$article['type'] = $_POST['type'];
		}
	    $article['content'] = $_POST['ue_content'];
	    if($tid == 9) {
	    	 $img = $this->article_list_m->get_pic($article['aid']);		// 获取图片信息
	    	 $config = array(
	    	 		"pathFormat" => "upload/{yyyy}{mm}{dd}/{time}{ss}" ,
	    	 		"maxSize" => 50000000 , //单位KB
	    	 		"allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp"  )
	    	 );
	    	 $pic = new Uploader_ue( "pic" , $config);
	    	 $info = $pic->getFileInfo();
			 var_dump($info);
	    	 if($info['state'] == 'SUCCESS') {
	    	 	$goods_pic = $info['url'];
	    	 } else {
	    	 	$goods_pic = $img;
	    	 }
	    }
	    $article['username'] = $this->admin_user_m->user->username;
	  
	    $article['add_time'] = time();
	    $this->article_list_m->update_article($article);
	    if($tid == 9) {
	    	$this->article_list_m->update_pic($article['aid'],$goods_pic);
	    }
	   // Header("Location:listNews?pid=".$pid."&tid=".$tid);
	}
	
	public function searchNews()
	{
	    $search = $_POST["search"];
	    $pid = $_GET['pid'];
	    $tid = $_GET['tid'];
	    $data['username'] = $this->admin_user_m->user->username;
	    $data['name'] = $this->article_type_m->get_name($tid);
	    $data['pid'] = $pid;
	    $data['tid'] = $tid;
	    $data["News"] = $this->article_list_m->search_article($tid,$search);
	    $this->load->view('admin/article_list_of_sec',$data);
	}
}