<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 用户管理
*/
class User extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* @brief 用户登录
	*  <pre>
	*	接受的表单数据：
	*		email				登录邮箱
	*		secret				玩家秘钥+timestamp的MD5
	*		timestamp			当前时间戳(可能需要对时)
	*		version				API版本(可选)
	*  </pre>
	*  <pre>
	*	测试账号: 
	*		Email: ushopkit@gmail.com
	*		秘 钥: c61e12ee7656f8220bc0318344105c0f (明文:ushopkit2015)
	*  </pre>
	* @return 操作结果
	*/
	function login()
	{
		$email		= trim($this->input->get_post('email', TRUE));
		$secret		= trim($this->input->get_post('secret', TRUE));
		$timestamp	= trim($this->input->get_post('timestamp', TRUE));
		$version	= trim($this->input->get_post('version', TRUE));

		if (empty($email) || empty($secret) || empty($timestamp))
		{
			$_RSP['ret'] = 101;
			$_RSP['msg'] = 'missing param(s)';
			exit(json_encode($_RSP));
		}

		$this->load->library('auth');
		$ru = $this->auth->login($email, $secret, $timestamp);
		if (FALSE === $ru)
		{
			$_RSP['ret'] = 500;
			$_RSP['msg'] = 'authentication failed';
			exit(json_encode($_RSP));
		}

		$_RSP['ret'] = 0;
		$_RSP['user'] = array(
			'user_id' 	=> $ru['user_id'],
			'user_name'	=> $ru['user_name']
			);
		exit(json_encode($_RSP));
	}

	/**
	* @brief Logout
	*/
	function logout()
	{
		$this->load->library('auth');
		$this->auth->destroy_session();
	}

	/**
	* @brief 用户注册
	*  <pre>
	*	接受的表单数据：
	*		email				登录邮箱
	*		secret				秘钥约定(算法待定)
	*		name 				昵称
	*		contact 			联系方式
	*		version				API版本(可选)
	*  </pre>
	*/
	function register()
	{
		$email		= trim($this->input->get_post('email', TRUE));
		$secret		= trim($this->input->get_post('secret', TRUE));
		$name 		= trim($this->input->get_post('name', TRUE));
		$contact	= trim($this->input->get_post('contact', TRUE));
		$version	= trim($this->input->get_post('version', TRUE));

		if (empty($email))
		{
			$_RSP['ret'] = 101;
			$_RSP['msg'] = 'mising param(s)';
			exit(json_encode($_RSP));
		}

		if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email))
		{
			$_RSP['ret'] = 100;
			$_RSP['msg'] = 'invalid email';
			exit(json_encode($_RSP));
		}

		if (empty($name))
		{
			$name="user_" . time() % 9999;
		}

		$user = array(
			'user_email' 	=> $email,
			'user_secret'	=> $secret,
			'user_name'		=> $name,
			'user_type'		=> 1,
			'user_location'	=> 0
			);

		$this->load->model('user_model');
		$id = $this->user_model->add($user);
		if (FALSE == $id)
		{
			$_RSP['ret'] = 1000;
			$_RSP['msg'] = 'DB exception';
			exit(json_encode($_RSP));
		}

		unset($user['user_secret']);
		$_RSP['ret'] = 0;
		$_RSP['user'] = array(
			'userr_id'	=> $id,
			'user_name'	=> $name,
			);
		exit(json_encode($_RSP));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
