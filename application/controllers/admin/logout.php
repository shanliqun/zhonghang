<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 功能：后台登出控制器
 * 作者：lizzyphy
 * 日期：2014.12.29
 */
class Logout extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function index() 
	{
		$this->load->view('admin/login');
	}
}