<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* @brief 优惠券
*/
class Coupon extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('coupon_model');
		$this->load->model('user_coupon_model');
	}
	
	/**
	* @brief 用户优惠卷查询(user_coupon)
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
		$coupons = $this->user_coupon_model->get_coupons_by_userid($userid, $length, $offset);
		if (!empty($coupons))
		{
			$_RSP['coupons'] = $coupons;
		}
		else
		{
			$_RSP['coupons'] = array();
		}
		
		exit(json_encode($_RSP));
	}

	/**
	* @brief 服务商优惠卷查询
	*  <pre>
	*	接受的表单数据：
	*		provider_id	服务商ID
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function provider_coupon()
	{
		$providerid = trim($this->input->get_post('provider_id', TRUE));
		$offset		= trim($this->input->get_post('start_idx', TRUE));
		$length		= trim($this->input->get_post('length', TRUE));

		if (!is_numeric($providerid))
		{
			$_RSP['ret'] = ERR_INVALID_VALUE;
			$_RSP['msg'] = 'invalid provider id';
			exit(json_encode($_RSP));
		}

		if (!is_numeric($offset))
		{
			$offset = 0;
		}

		if (!is_numeric($length)
			|| 1 > $length || 20 < $length)
		{
			$length = 10;
		}

		$_RSP['ret'] = 0;
		$coupons = $this->coupon_model->get_coupons_by_providerid($providerid, $length, $offset);
		if (!empty($coupons))
		{
			$_RSP['coupons'] = $coupons;
		}
		else
		{
			$_RSP['coupons'] = array();
		}
		
		exit(json_encode($_RSP));
	}
}

/* End of file coupon.php */