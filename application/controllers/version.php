<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 版本控制
*/
class Version extends CI_Controller {

	//当前最新版本号
	private $_CURRENT_VERSION = 1;
	private $_CURRENT_URL     = "http://119.29.58.104/cdn/ushopapp_v1.apk";
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* @brief 版本信息
	*  <pre>
	*		ver		当前客户端版本号
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$clientversion	= trim($this->input->get_post('ver', TRUE));

		if (empty($clientversion) ||$this->_CURRENT_VERSION > $clientversion)
		{
			$_RSP["ret"] = 1;
			$_RSP["pkg"] = array("version" => $this->_CURRENT_VERSION, "url" => $this->_CURRENT_URL);
		}
		else
		{
			$_RSP["ret"] = 0;
		}

		exit(json_encode($_RSP));
	}
}

/* End of file version.php */
