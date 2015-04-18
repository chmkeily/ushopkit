<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class BaseCtrl extends CI_Controller {

	/*
	* indicates whether to check the authentication of the current visit
	*/
	private $m_check_session = TRUE;
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();

		if ($this->m_check_session)
		{
			$this->load->library('auth');
			$this->auth->check_session();
		}
	}

	/**
	* set the m_check_session option to $flag
	*/
	function set_check_session($flag = TRUE)
	{
		$this->m_check_session = $flag;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */