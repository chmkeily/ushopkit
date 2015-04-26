<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* @brief 用户收藏
*/
class Favorite extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('favorite_model');
	}
	
	/**
	* @brief 用户收藏查询
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
		$favorites = $this->favorite_model->get_favorites_by_userid($userid, $length, $offset);
		if (!empty($favorites))
		{
			$_RSP['favorites'] = $favorites;
		}
		else
		{
			$_RSP['favorites'] = array();
		}
		
		exit(json_encode($_RSP));
    }

	/**
	* @brief 用户收藏查询
	*  <pre>
	*	接受的表单数据：
	*		type		收藏类型
	*		referid		对象id
	*		userid		用户id (可选，默认为当前登陆用户)
	*  </pre>
    * @return 操作结果
    * <pre>
    *   {"ret":0,"favorite_id":"1"}
    * </pre>
	*/
    public function check()
    {
		$type       = trim($this->input->get_post('type', TRUE));
		$referid    = trim($this->input->get_post('referid', TRUE));
        $userid	    = trim($this->input->get_post('userid', TRUE));

        if (empty($userid))
        {
            //若userid参数留空，则默认取当前登陆用户
     	    $this->load->library('auth');
            $userid = $this->auth->get_userid();
            if (null === $userid)
            {
                $_RSP['ret'] = ERR_NO_SESSION;
                $_RSP['msg'] = 'not logined yet';
                exit(json_encode($_RSP));
            }
        }

        $favoriteid = $this->favorite_model->check_favorite($userid, $type, $referid);
        if (false === $favoriteid)
        {
            $_RSP['ret'] = -1;
            $_RSP['msg'] = 'not such favorite';
            exit(json_encode($_RSP));
        }

        $_RSP['ret'] = 0;
        $_RSP['favorite_id'] = $favoriteid;
        exit(json_encode($_RSP));
    }

	/**
	* @brief 收藏项目
	*  <pre>
	*	接受的表单数据：
	*		type	  	收藏类型(1:服务商, 2:案例, 2:商品)
	*		referid		收藏对象ID
	*  </pre>
	*/
	public function pin()
	{
		$type		= trim($this->input->get_post('type', TRUE));
		$referid	= trim($this->input->get_post('referid', TRUE));

		$this->load->library('auth');
		$userid = $this->auth->get_userid();
		if (null === $userid)
		{
			$_RSP['ret'] = ERR_NO_SESSION;
			$_RSP['msg'] = 'not logined yet';
			exit(json_encode($_RSP));
		}

		$ret = false;
		switch ($type) {
			case FAVORITE_PROVIDER:
				$this->pin_provider($userid, $referid);
				break;

			case FAVORITE_SHOPCASE:
				$this->pin_shopcase($userid, $referid);
				break;

			case FAVORITE_PRODUCT:
				$this->pin_product($userid, $referid);
				break;

			default:
				$ret = false;
				break;
		}

		if (false === $ret)
		{
			$_RSP['ret']	= ERR_FAILED;
			$_RSP['msg']	= 'PIN failed';
			exit(json_encode($_RSP));
		}

		$_RSP['ret'] = SUCCEED;
		$_RSP['msg'] = 'PIN succeed';
		exit(json_encode($_RSP));
	}

	/**
	* @brief 收藏服务商
	*/
	private function pin_provider($userid, $providerid)
	{
		$this->load->model('provider_model');
		$provider = $this->provider_model->get_provider_by_id($providerid);
		if (FALSE === $provider)
		{
			$_RSP['ret'] = ERR_INVALIDE_OBJECT;
			$_RSP['msg'] = 'no such provider';
			exit(json_encode($_RSP));
		}

		$favorite = array(
            'favorite_userid'   => $userid,
            'favorite_type'     => FAVORITE_PROVIDER,
            'favorite_referid'  => $providerid,
            'favorite_brief'    => $provider['provider_name'],
            'favorite_ftime'    => time(),
        );

        $id = $this->favorite_model->add($favorite);
		if (FALSE === $id)
		{
			$_RSP['ret'] = ERR_DB_OPERATION_FAILED;
			$_RSP['msg'] = 'ERROR_DB_OPERATION';
		}
		else
		{
			$_RSP['ret']        = SUCCEED;
			$_RSP['favoriteid'] = $id;
		}

		exit(json_encode($_RSP));
	}

	/**
	* @brief 收藏案例
	*/
	private function pin_shopcase($userid, $caseid)
    {
        $this->load->model('shopcase_model');
        $shopcase = $this->shopcase_model->get_shopcase_by_id($caseid);
        if (false === $shopcase)
        {
            $_RSP['ret'] = ERR_INVALIDE_OBJECT;
            $_RSP['msg'] = 'no such shopcase';
		    exit(json_encode($_RSP));
        }
        
        $favorite = array(
            'favorite_userid'   => $userid,
            'favorite_type'     => FAVORITE_SHOPCASE,
            'favorite_referid'  => $caseid,
            'favorite_brief'    => $shopcase['shopcase_name'],
            'favorite_ftime'    => time(),
        );
        
        $id = $this->favorite_model->add($favorite);
		if (FALSE === $id)
		{
			$_RSP['ret'] = ERR_DB_OPERATION_FAILED;
			$_RSP['msg'] = 'ERROR_DB_OPERATION';
		}
		else
		{
			$_RSP['ret']        = SUCCEED;
			$_RSP['favoriteid'] = $id;
		}
        
        exit(json_encode($_RSP));
	}

	/**
	* @brief 收藏商品
	*/
	private function pin_product($userid, $productid)
	{
		$this->load->model('product_model');
		$product = $this->product_model->get_product_by_id($productid);
		if (FALSE === $product)
		{
			$_RSP['ret'] = ERR_INVALIDE_OBJECT;
			$_RSP['msg'] = 'no such product';
			exit(json_encode($_RSP));
		}

		$favorite = array(
            'favorite_userid'   => $userid,
            'favorite_type'     => FAVORITE_PRODUCT,
            'favorite_referid'  => $productid,
            'favorite_brief'    => $product['product_name'],
            'favorite_ftime'    => time(),
        );

        $id = $this->favorite_model->add($favorite);
		if (FALSE === $id)
		{
			$_RSP['ret'] = ERR_DB_OPERATION_FAILED;
			$_RSP['msg'] = 'ERROR_DB_OPERATION';
		}
		else
		{
			$_RSP['ret']        = SUCCEED;
			$_RSP['favoriteid'] = $id;
		}

		exit(json_encode($_RSP));
	}
}

/* End of file coupon.php */
