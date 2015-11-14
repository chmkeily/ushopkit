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
	* @brief 查询用户已领取的优惠券
	*  <pre>
	*	接受的表单数据：
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function mine()
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
	* @brief 优惠卷查询
	*  <pre>
	*	接受的表单数据：
	*		provider_id	服务商ID (optional)
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$providerid = trim($this->input->get_post('provider_id', TRUE));
		$offset		= trim($this->input->get_post('start_idx', TRUE));
		$length		= trim($this->input->get_post('length', TRUE));

		$conditions = array();
		if (!empty($providerid) && is_numeric($providerid))
		{
			$conditions['provider_id'] = $providerid;
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
		$coupons = $this->coupon_model->get_coupons($conditions, $length, $offset);
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
	* @brief 领取优惠劵
	*  <pre>
	*	接受的表单数据：
	*		coupon_id	    优惠劵ID
	*		coupon_desc	    简短描述
	*  </pre>
	* @return 操作结果
	*/
	public function pocket()
	{
		$couponid   = trim($this->input->get_post('coupon_id', TRUE));
        $coupondesc = trim($this->input->get_post('coupon_desc', TRUE));

        if (!is_numeric($couponid))
        {
            $_RSP['ret'] = ERR_INVALID_VALUE;
            $_RSP['msg'] = 'invalid coupon id';
            exit(json_encode($_RSP));
        }

		$this->load->library('auth');
        $userid = $this->auth->get_userid();
        if (null === $userid)
        {
            $_RSP['ret'] = ERR_NO_SESSION;
			$_RSP['msg'] = 'not logined yet';
			exit(json_encode($_RSP));
        }

        $coupon = $this->coupon_model->get_coupon_by_id($couponid);
        if (false == $coupon)
        {
            $_RSP['ret'] = ERR_NO_OBJECT;
            $_RSP['msg'] = 'no such coupon';
            exit(json_encode($_RSP));
        }

        if (empty($coupondesc))
        {
            $coupondesc = $coupon['coupon_content'];
        }

        $timestamp = time();

        $usr_coupon = array(
            'coupon_couponid'   => $couponid,
            'coupon_userid'     => $userid,
            'coupon_title'      => $coupon['coupon_title'],
            'coupon_desc'       => $coupondesc,
            'coupon_obtaintime' => $timestamp,
            'coupon_begintime'  => $coupon['coupon_begintime'],
            'coupon_endtime'    => $coupon['coupon_endtime'],
            'coupon_status'     => 0,
        );

        $id = $this->user_coupon_model->add($usr_coupon);
        if (false === $id)
        {
            $_RSP['ret'] = ERR_DB_OPERATION_FAILED;
            $_RSP['msg'] = 'system exception';
            exit(json_encode($_RSP));
        }

        //增加此优惠劵的领取计数
        $this->coupon_model->update_taken_count($couponid, 1);

        $_RSP['ret'] = SUCCEED;
        $_RSP['ucid'] = $id;
        exit(json_encode($_RSP));
    }

	/**
     * @brief 使用优惠劵
     * <pre>
     *  接受表单参数:
     *      ucid   玩家领取的优惠劵记录ID(mine接口中返回的coupon_id，注意不是coupon_couponid)
     * </pre>
     * @return 优惠劵详情
     */
    function redeem()
    {
        $ucid   = trim($this->input->get_post('ucid', TRUE));

		$this->load->library('auth');
        $userid = $this->auth->get_userid();
        if (null === $userid)
        {
            $_RSP['ret'] = ERR_NO_SESSION;
			$_RSP['msg'] = 'not logined yet';
			exit(json_encode($_RSP));
        }

        $coupon = $this->user_coupon_model->get_coupon_by_id($ucid);
        if (false === $coupon)
        {
        	$_RSP['ret'] = ERR_NO_OBJECT;
        	$_RSP['msg'] = 'no such cuopon';
        	exit(json_encode($_RSP));
        }

        /**
        * 已领取优惠劵的状态：
        *  0	未使用
        *  1 	已使用
        *  2 	已失效/过期
        */
        if (0 != $coupon['coupon_status'])
        {
        	$_RSP['ret'] = ERR_IMPROPER_STATUS;
        	$_RSP['msg'] = '优惠劵已使用或已失效';
        	exit(json_encode($_RSP));
        }

        if ($userid != $coupon['coupon_userid'])
        {
        	$_RSP['ret'] = ERR_NO_PERMISSION;
        	$_RSP['msg'] = '你无权使用此优惠劵';
        	exit(json_encode($_RSP));
        }

        $updates = array('coupon_status' => 1);
        if (0 < $this->user_coupon_model->update($ucid, $updates))
        {
        	$_RSP['ret'] = SUCCEED;

            //增加此优惠劵的使用/兑换计数
            $couponid = $coupon['coupon_couponid'];
            $this->coupon_model->update_redeem_count($couponid, 1);
        }
        else
        {
        	$_RSP['ret'] = ERR_DB_OPERATION_FAILED;
        	$_RSP['msg'] = '状态更新失败';
        }

        exit(json_encode($_RSP));
	}

    /**
     * @brief 详情查询
     * <pre>
     *  接受表单参数:
     *      coupon_id   优惠劵ID
     * </pre>
     * @return 优惠劵详情
     */
    function details()
    {
        $couponid   = trim($this->input->get_post('coupon_id', TRUE));

        $coupon = $this->coupon_model->get_coupon_by_id($couponid);
        if (empty($coupon))
        {
            $_RSP['ret'] = ERR_NO_OBJECT;
            $_RSP['msg'] = 'no such coupon';
            exit(json_encode($_RSP));
        }

        $_RSP['ret']    = SUCCEED;
        $_RSP['coupon'] = $coupon;
        exit(json_encode($_RSP));
    }

}

/* End of file coupon.php */
