<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 服务商
*/
class Provider extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('provider_model');
	}
	
	/**
	* @brief 服务商列表查询
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

		$_RSP['ret'] = 0;
		$providers = $this->provider_model->get_providers(array(), $length, $offset);
		if (!empty($providers))
		{
			$_RSP['providers'] = $providers;
		}
		
		exit(json_encode($_RSP));
	}

	/**
	* @brief 单个服务商查询
	*  <pre>
	*	接受的表单数据：
	*		provider_id	服务商ID
	*  </pre>
	* @return 操作结果
	*/
	public function details()
	{
		$providerid = trim($this->input->get_post('provider_id', TRUE));

		if (empty($providerid))
		{
			$_RSP['ret'] = ERR_MISSING_PARM;
			$_RSP['msg'] = 'missing provider id';
			exit(json_encode($_RSP));
		}

		if (!is_numeric($providerid))
		{
			$_RSP['ret'] = ERR_INVALID_VALUE;
			$_RSP['msg'] = 'invalid provider id';
			exit(json_encode($_RSP));
		}

		$provider = $this->provider_model->get_provider_by_id($providerid);
		if (FALSE === $provider)
		{
			$_RSP['ret'] = ERR_NO_OBJECT;
			$_RSP['msg'] = 'no such provider';
			exit(json_encode($_RSP));
		}

		$_RSP['ret'] = SUCCEED;
		$_RSP['provider'] = $provider;
		exit(json_encode($_RSP));
	}

	/**
	* @brief 添加服务商 (业务逻辑没明晰，还没做权限控制)
	*  <pre>
	*	接受的表单数据：
	*		provider_name			名称
	*		provider_icon			图标URL
	*		provider_location		城市
	*		provider_address		联系地址
	*		provider_contact		联系方式
	*		provider_brief			简介
	*		provider_intro			详细介绍
	*		provider_casenum		客户数
	*		provider_verified		是否已认证(0表示未认证)
	*		provider_warrant		是否保
	*  </pre>
	* @return 操作结果
	*/
	public function create()
	{
		$name		= trim($this->input->get_post('provider_name', TRUE));
		$iconurl	= trim($this->input->get_post('provider_icon', TRUE));
		$location	= trim($this->input->get_post('provider_location', TRUE));
		$contact	= trim($this->input->get_post('provider_contact', TRUE));
		$address	= trim($this->input->get_post('provider_address', TRUE));
		$brief		= trim($this->input->get_post('provider_brief', TRUE));
		$intro		= trim($this->input->get_post('provider_intro', TRUE));
		$casenum	= trim($this->input->get_post('provider_casenum', TRUE));
		$verified	= trim($this->input->get_post('provider_verified', TRUE));
		$warrant	= trim($this->input->get_post('provider_warrant', TRUE));

		$provider = array(
			'provider_name' 	=> $name,
			'provider_icon'		=> $iconurl,
			'provider_location'	=> $location,
			'provider_contact'	=> $contact,
			'provider_address'	=> $address,
			'provider_brief'	=> $brief,
			'provider_intro'	=> $intro,
			'provider_verified'	=> $verified,
			'provider_warrant'	=> $warrant,
			);

		$id = $this->provider_model->add($provider);
		if (FALSE == $id)
		{
			$_RSP['ret'] = -1;
			$_RSP['msg'] = 'ERROR_DB_OPERATION';
		}
		else
		{
			$_RSP['ret'] = 0;
			$_RSP['provider_id'] = $id;
		}

		exit(json_encode($_RSP));
	}
}

/* End of file welcome.php */