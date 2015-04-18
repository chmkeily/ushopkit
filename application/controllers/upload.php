<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 文件上传
*/
class Upload extends CI_Controller {

	private var $_ufile_dir = '/data/ussd/';
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* @brief 文件/图片上传接口（未定稿）
	*
	*/
	public function index()
	{
		$userfile =& $_FILES['_ufile'];
		if (empty($userfile) || 0 != $userfile['error'])
		{
			$_RSP['ret']	= -1;
			$_RSP['msg']	= $userfile['error'];
			exit(json_encode($_RSP));
		}

		$fileinfos = pathinfo($userfile['name']);
		$ldir = $this->_ufile_dir . '/' . date('Y-m-d');
		$lfname = date('YmdHis') . '_' . (rand() % 100);
		if (!empty($fileinfos['extension']))
		{
			$lfname .= '.' . $fileinfos['extension'];
		}
		$lfile = $ldir . '/' . $lfname;

		if (!is_dir($ldir) && mkdir($ldir, 0755, true))
		{
			$_RSP['ret']	= -2;
			$_RSP['msg']	= 'failed to mkdir';
			exit(json_encode($_RSP));
		}

		if (false == move_uploaded_file($userfile['tmp_name'], $lfile))
		{
			$_RSP['ret']	= -3;
			$_RSP['msg']	= "failed to move_uploaded_file to '{$lfile}'";
			exit(json_encode($_RSP));
		}

		$_RSP['ret']	= 0;
		$_RSP['path']	= $lfile;
		exit(json_encode($_RSP));
	}
}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */