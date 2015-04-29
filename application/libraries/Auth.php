<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth Class
 *
 */
class Auth {

	/**
	 * Constructor
	 */
	function __construct()
	{
		
	}

	/**
	* @brief 登录态校验
	*/
	function check_session()
	{
		if (null === $this->get_session())
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	* @brief 获取session数据
	*/
	function get_session()
	{
		session_start();
		if (isset($_SESSION['user_data']))
		{
			return $_SESSION['user_data'];
		}

		return null;
	}

	/**
	* @brief Set session - expires in x mins
	*/
	function set_session($user_data)
    {
        session_cache_limiter('private');
		session_cache_expire(60);	//1小时
		session_start();
		$_SESSION['user_data'] = $user_data;
	}

	/**
	* @brief
	*/
	function destroy_session()
	{
		session_start();
		session_destroy();
	}

	/**
	* @brief
	*/
	function get_userid()
	{
		$cur_session = $this->get_session();
		if (null === $cur_session || !isset($cur_session['user_id']))
		{
			return null;
		}

		return $cur_session['user_id'];
	}

	/**
	* @brief 用户登录逻辑
	*/
	function login($email, $secret2, $timestamp)
	{
		/*if (300 < abs($timestamp - time()))
		{
			return FALSE;	//防止时间欺骗
		}*/

		///业务检验
		$CI =& get_instance();
		$CI->load->model('user_model');

		$user = $CI->user_model->get_user_by_email($email);
		if (FALSE === $user)
		{
			return FALSE;
		}

		$au_secret = md5($user['user_secret'] . $timestamp);
		if (0 != strcasecmp($au_secret, $secret2))
		{
			return FALSE;	//密码/秘钥校验失败
		}

		//设置SESSION
		unset($user['user_secret']);
		$this->set_session($user);

		return $user;
	}

	/**
	 * 
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		
	}

}
// END Log Class

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
