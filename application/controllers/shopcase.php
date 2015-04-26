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
	*		start_idx		开始序号
	*		length		列表长度
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
        
        $shopcases = $this->shopcase_model->get_shopcases(array(), $length, $offset);
        if (!empty($shopcases))
        {
		    $_RSP['shopcases'] = $shopcases;
        }

		$_RSP['ret'] = SUCCEED;
		exit(json_encode($_RSP));
	}

	/**
	* @brief 案例查询
	*  <pre>
	*	接受的请求数据：
	*		caseid		案例ID
	*  </pre>
	* @return 操作结果
	*/
	public function details()
	{
        $shopcaseid = trim($this->input->get_post('caseid', TRUE));

        if (!is_numeric($shopcaseid))
        {
            $_RSP['ret'] = ERR_INVALID_VALUE;
            $_RSP['msg'] = 'invalid case id';
            exit(json_encode($_RSP));
        }

        $shopcase = $this->shopcase_model->get_shopcase_by_id($shopcaseid);
        if (false === $shopcase)
        {
            $_RSP['ret'] = ERR_NO_OBJECT;
            $_RSP['msg'] = 'no such object';
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
