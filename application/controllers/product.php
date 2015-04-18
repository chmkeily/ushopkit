<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 商品
*/
class Product extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
	}
	
	/**
	* @brief 商品查询
	*  <pre>
	*	接受的表单数据：
	*		product_id	商品ID
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$product_id = trim($this->input->get_post('product_id', TRUE));
		
		if (!is_numeric($product_id))
		{
			$_RSP['ret'] = ERR_INVALID_VALUE;
			$_RSP['msg'] = 'invalid product id';
			exit(json_encode($_RSP));
		}

		$product = $this->product_model->get_product_by_id($product_id);
		if (empty($product))
		{
			$_RSP['ret'] = ERR_NO_OBJECT;
			$_RSP['msg'] = 'no such product';
			exit(json_encode($_RSP));
		}
		
		$_RSP['ret'] = SUCCEED;
		$_RSP['product'] = $product;
		exit(json_encode($_RSP));
	}

	/**
	* @brief 服务商商品查询
	*  <pre>
	*	接受的表单数据：
	*		provider_id	服务商ID
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function provider_product()
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
		$products = $this->product_model->get_products_by_providerid($providerid, $length, $offset);
		if (!empty($products))
		{
			$_RSP['products'] = $products;
		}
		else
		{
			$_RSP['products'] = array();
		}
		
		exit(json_encode($_RSP));
	}
}

/* End of file product.php */