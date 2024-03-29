<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 文章管理模型层
 * 
 * @author 风格独特
 */

class Article_m extends CI_Model 
{
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('article_type_m');
	}
	
	/**
	 * 获取单个文章内容
	 * 
	 * @param integer $aid
	 */
	public function get($aid)
	{
		$aid = intval($aid);
		$this->db->where('aid', $aid);
		$query = $this->db->get('article');
		if($query->num_rows() == 1) {
			$row = $query->row_array();
			$query->free_result();
			return array(
						'aid'		=>	$aid,
						'type'		=>	$row['type'],
						'type_name'	=>	$this->article_type_m->get_name($row['type']),
						'title'		=>	$row['title'],
						'content'	=>	$row['content'],
						'add_date'	=>	$row['add_date'],
						'add_time'	=>	$row['add_time'],
						'add_user'	=>	$row['add_user'],
					);
		}
		return FALSE;
	}
	
	public function get_tid($tid){
		$this->db->where('tid', $tid);
		$query = $this->db->get('articles_type');
		if($query->num_rows() != 0){
			$row = $query->row_array();
			return $row;
		}
	}
	/*通过tid找到pid*/
	public function get_pid($pid){
		$pid = intval($pid);
		$this->db->where('pid', $pid);
		$query = $this->db->get('articles_type');
		$result = array();
		$i = 0;
		if($query->num_rows() > 0 ){
			$row = $query->result_array();
			foreach ($row as $row_second_name){
				$result[$i]['title'] = $row_second_name['name'];
				$result[$i]['tid'] = $row_second_name['tid'];
				$i++;
			}
		}
		return $result;
	}
	
	public function get_type($type)
	{
		$type = intval($type);
		$this->db->where('type', $type);
		$query = $this->db->get('articles');
		if($query->num_rows() == 1) {
			$row = $query->row_array();
			$query->free_result();
			return array(
						'type'		=>	$type,
						'type_name'	=>	$this->article_type_m->get_name($row['type']),
						'title'		=>	$row['title'],
						'content'	=>	$row['content'],
						'add_date'	=>	$row['add_date'],
						'add_time'	=>	$row['add_time'],
						'add_user'	=>	$row['add_user'],
					);
		}
		return FALSE;
	}
	
	/**
	 * 获取文章列表，不包括文章内容字段
	 * 
	 * @param integer $num
	 * @param integer $offset
	 * @param integer $type
	 * @param string $order
	 */
	public function search($keywords,$limit, $offset = 0)
	{
		$i = 0;
		$return = array();
		$this->db->like('title', $keywords);
		$this->db->order_by('aid desc,type asc');
		$query = $this->db->get('zh_articles', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			$return[$i]['type_name2'] = $this->article_type_m->get_name($row['type']);
			$return[$i]['type_name1'] = $this->article_type_m->get_name_parent($row['type']);
			++$i;
		}
		return $return;
	}
	
	public function count_all($keywords)
	{
		$this->db->like('title', $keywords);
		$query = $this->db->get('zh_articles');
		$num = $query->num_rows();
		return $num;
	}
	
	public function get_list($limit, $offset = 0, $type = 0, $order = 'type ASC,ord DESC,aid DESC,add_date DESC')
	{
		$i = 0;
		$return = array();
		if($type > 0) {
			$this->db->where('type', (int) $type);
		}
		$this->db->select('aid,ord, type, title, add_time, add_date, add_user');
		$this->db->order_by($order);
		$query = $this->db->get('article', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			$return[$i]['type_name'] = $this->article_type_m->get_name($row['type']);
			++$i;
		}
		return $return;
	}
	public function get_list3($limit, $offset = 0, $type = 0, $order = 'add_date ASC,add_time ASC')
	{
		$i = 0;
		$return = array();
		if($type > 0) {
			$this->db->where('type', (int) $type);
		}
		$this->db->select('aid,ord, type, title, add_time, add_date, add_user');
		$this->db->order_by($order);
		$query = $this->db->get('article', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			$return[$i]['type_name'] = $this->article_type_m->get_name($row['type']);
			++$i;
		}
		return $return;
	}
	public function get_list2($type)
	{
		$i = 0;
		if($type > 0) {
			$this->db->where('pid', (int) $type);
		}
		$this->db->select('tid,name');
		$query = $this->db->get('article_type');
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			$i++;
		}
		return $return;
	}
	public function get_list_link($limit, $offset = 0)
	{
		$i = 0;
		$return = array();	
		$this->db->select('lid, name, url, title');
		$query = $this->db->get('link', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			++$i;
		}
		return $return;
	}
	public function get_list_img($limit, $offset = 0)
	{
		$i = 0;
		$return = array();
		$this->db->where('type',1);
		$this->db->order_by('order');
		$query = $this->db->get('index_img', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			++$i;
		}
		return $return;
	}
	public function get_list_flash($limit, $offset = 0)
	{
		$i = 0;
		$return = array();	
		$this->db->select('path');
		$this->db->where('type',2);
		$this->db->order_by('id DESC');
		$query = $this->db->get('index_img', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			++$i;
		}
		return $return;
	}
	public function set_list_type($type = 0) 
	{
		if($type != 0) {
			$this->db->where('type', $type);
		}
		return $this;
	}
	
	/**
	 * 
	 * @param integer $type
	 * @param string $name_keyword
	 * @param string $content_keyword
	 * @return integer 文章总数
	 * @todo 完成标题关键字，和内容关键字查询
	 */
	public function get_num($type = 0, $name_keyword = '', $content_keyword = '') 
	{
		if($type != 0) {
			$this->db->where('type', $type);
		}
		if($name_keyword != '') {
			$this->db->like('title', $name_keyword);
		}
		if($content_keyword != '') {
			
		}
		return $this->db->count_all_results('article');
	}
	
	public function get_search($keyword = '', $limit, $offset = 0, $type = 0) 
	{
		$return = array();
		$i = 0;
		$this->db->select('aid,ord, type, title, add_time, add_date, add_user');
		$this->db->like('title', $keyword);
		$this->db->order_by('aid DESC');
		if($type != 0) {
			$this->db->where('type', $type);
		}
		$query = $this->db->get('article', $limit, $offset);
		foreach ($query->result_array() as $row) {
			$return[$i] = $row;
			$return[$i]['type_name'] = $this->article_type_m->get_name($row['type']);
			++$i;
		}
		return $return;
	}
	
	public function add($title, $type, $content, $add_user) 
	{
		if($this->article_type_m->get_name($type) === FALSE) {
			return FALSE;
		}
		$data = array(
					'title'		=>	$title,
					'type'		=>	$type,
					'content'	=>	$content,
					'add_date'	=>	date('Y-m-d', time()),
					'add_time'	=>	time(),
					'add_user'	=>	$add_user,
				);
		if($this->db->insert('article', $data) === FALSE) {
			return FALSE;
		}
		$aid = $this->db->insert_id();
		$data2 = array(
			      'ord'   =>   $aid
		);
		$this->db->where('aid',$aid);
		$this->db->update('article', $data2);
		return $aid;
		
		
	}
	public function get_second_name($aid)
	{
		$data = array(
			'first_name'	=> '',
			'second_name'	=> '',
		);
		
		$aid = (int) $aid;
		$this->db->select('pid,name');
		$this->db->where('tid', $aid);
		$query = $this->db->get('article_type');
		if($query->num_rows() > 0) {
			//return $query->row_array();
			$arr=$query->row_array();
			$data['second_name'] = $arr['name'];
			$arr1 = $arr['pid'];
			$data['first_name'] = $this->get_first_name($arr1);	
		}
		return $data;
	}
	
	public function get_first_name($pid)
	{
		$pid = (int) $pid;
		$this->db->select('name');
		$this->db->where('tid', $pid);
		$query = $this->db->get('article_type');
		if($query->num_rows() > 0) {
			//return $query->row_array();
			$arr=$query->row_array();
			$first_name = $arr['name'];				
		}
		
		return $first_name;
	}
	public function edit($aid, $data) 
	{
		$aid = (int) $aid;
		$this->db->where('aid', $aid);
		$this->db->update('article', $data);
	}
	
	public function del($aid) 
	{
		$this->db->where('aid', $aid);
		if($this->db->delete('article') === FALSE) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function up($ord,$type)
	{
		$ord = (int) $ord;
		$type = (int) $type;
		$this->db->where('type', $type);
		$this->db->where('ord >', $ord);
		$this->db->select_min('ord');
		$query = $this->db->get('article');
		foreach ($query->result() as $row)
		{
			$ord2 = $row->ord;
		}
		//if ($query->num_rows() == 1)
		if (!empty($ord2))
		{			
			$ord1 = 0;
			$data1 = array('ord' => $ord1);
			$this->db->where('ord', $ord);
			$this->db->update('article', $data1);
			$data2 = array('ord' => $ord);
			$this->db->where('ord', $ord2);
			$this->db->update('article', $data2);
			$data = array('ord' => $ord2);
			$this->db->where('ord', $ord1);
			$this->db->update('article', $data);
			return TRUE;
		}
		else
		{
			$data1 = array('ord' => $ord);
			$this->db->where('ord', $ord);
			return TRUE;
		}
		
	}
	
	public function down($ord,$type)
	{
		$ord = (int) $ord;
		$type = (int) $type;
		$this->db->where('type', $type);
		$this->db->where('ord <', $ord);
		$this->db->select_max('ord');
		$query = $this->db->get('article');
		foreach ($query->result() as $row)
		{
			$ord2 = $row->ord;
		}
		//if ($query->num_rows() == 1)
		if (!empty($ord2))
		{
			$ord1 = 0;
			$data1 = array('ord' => $ord1);
			$this->db->where('ord', $ord);
			$this->db->update('article', $data1);
			$data2 = array('ord' => $ord);
			$this->db->where('ord', $ord2);
			$this->db->update('article', $data2);
			$data = array('ord' => $ord2);
			$this->db->where('ord', $ord1);
			$this->db->update('article', $data);
			return TRUE;
		}
		else
		{
			$data1 = array('ord' => $ord);
			$this->db->where('ord', $ord);
			return TRUE;
		}
	}
	
}
