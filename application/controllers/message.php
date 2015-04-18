<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 用户信息
*/
class Message extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('message_model');
	}
	
	/**
	* @brief 消息查询
	*  <pre>
	*	接受的表单数据：
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$offset		= trim($this->input->get_post('start_idx', TRUE));
		$length		= trim($this->input->get_post('length', TRUE));

		if (!is_numeric($offset))
		{
			$offset = 0;
		}

		if (!is_numeric($length)
			|| 1 > $length || 20 < $length)
		{
			$length = 10;
		}

		$this->load->library('auth');
		$userid = $this->auth->get_userid();
		if (null === $userid)
		{
			$_RSP['ret'] = ERR_NO_SESSION;
			$_RSP['msg'] = 'not logined yet';
			exit(json_encode($_RSP));
		}

		$_RSP['ret'] = 0;
		$messages = $this->message_model->get_messages_by_userid($userid, $length, $offset);
		if (!empty($messages))
		{
			$_RSP['messages'] = $messages;
		}
		else
		{
			$_RSP['messages'] = array();
		}
		
		exit(json_encode($_RSP));
	}
}

/* End of file message.php */