<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 开店案例
*/
class Shopcase extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('shopcase_model');
	}
	
	/**
	* @brief 案例查询
	*  <pre>
	*	接受的请求数据：
	*		shopcase_id		店铺案例ID
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$shopcase_id = trim($this->input->get_post('shopcase_id', TRUE));
		
		if (!is_numeric($shopcase_id))
		{
			$_RSP['ret'] = ERR_INVALID_VALUE;
			$_RSP['msg'] = 'invalid shopcase id';
			exit(json_encode($_RSP));
		}

		$shopcase = $this->shopcase_model->get_shopcase_by_id($shopcase_id);
		if (empty($shopcase))
		{
			$_RSP['ret'] = ERR_NO_OBJECT;
			$_RSP['msg'] = 'no such shopcase';
			exit(json_encode($_RSP));
		}
		
		$_RSP['ret'] = SUCCEED;
		$_RSP['shopcase'] = $shopcase;
		exit(json_encode($_RSP));
	}

	/**
	* @brief 服务商案例查询
	*  <pre>
	*	接受的请求数据：
	*		provider_id		服务商ID
	*  </pre>
	* @return 操作结果
	*/
	public function provider_case()
	{
		$provider_id = trim($this->input->get_post('provider_id', TRUE));
		if (!is_numeric($provider_id))
		{
			$_RSP['ret'] = ERR_INVALID_VALUE;
			$_RSP['msg'] = 'invalid provider id';
			exit(json_encode($_RSP));
		}

		$_RSP['ret'] = SUCCEED;
		$shopcases = $this->shopcase_model->get_shopcases_by_providerid($provider_id);
		if (!empty($shopcases))
		{
			$_RSP['shopcases'] = $shopcases;
		}
		else
		{
			$_RSP['shopcases'] = array();
		}
		
		exit(json_encode($_RSP));
	}
}

/* End of file shopcase.php */