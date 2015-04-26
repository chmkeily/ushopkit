<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 用户需求
*/
class Requirement extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('requirement_model');
	}
	
	/**
	* @brief 需求查询
	*  <pre>
	*	接受的表单数据：
	*		userid		用户ID
	*		type		需求类型
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$userid		= trim($this->input->get_post('userid', TRUE));
		$type		= trim($this->input->get_post('type', TRUE));
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

        $conditions = array();
        if (!empty($userid))
        {
            $conditions['requirement_ownerid'] = $userid;
        }
        if (!empty($type))
        {
            $conditions['requirement_type'] = $type;
        }

        $_RSP['ret'] = 0;
		$requirements = $this->requirement_model->get_requirements($conditions, $length, $offset);
		if (!empty($requirements))
		{
			$_RSP['requirements'] = $requirements;
		}
		
		exit(json_encode($_RSP));
	}

	/**
	* @brief 添加服务商
	*  <pre>
	*	接受的表单数据：
	*		requirement_type		需求类型 (1:开店需求, 2:技术支持)
	*		requirement_ownername	用户称呼
	*		requirement_ownerphone	联系电话
	*		requirement_shopcate	店铺类型
	*		requirement_shoparea	店铺面积
	*		requirement_shopcity	店铺所在城市
	*		requirement_budget		预算(整数，单位为分)
	*		requirement_title		需求标题
	*		requirement_detail		需求详情(格式未约定,目前只为TEXT)
	*		requirement_et			需求失效时间(时间戳)
	*  </pre>
	* @return 操作结果
	*/
	public function create()
	{
		$type		= trim($this->input->get_post('requirement_type', TRUE));
		$ownername	= trim($this->input->get_post('requirement_ownername', TRUE));
		$ownerphone	= trim($this->input->get_post('requirement_ownerphone', TRUE));
		$shopcate	= trim($this->input->get_post('requirement_shopcate', TRUE));
		$shoparea	= trim($this->input->get_post('requirement_shoparea', TRUE));
		$shopcity	= trim($this->input->get_post('requirement_shopcity', TRUE));
		$budget		= trim($this->input->get_post('requirement_budget', TRUE));
		$title		= trim($this->input->get_post('requirement_title', TRUE));
		$detail		= trim($this->input->get_post('requirement_detail', TRUE));
		$expireT	= trim($this->input->get_post('requirement_et', TRUE));

		if (empty($type) || empty($ownername) || empty($title) || empty($detail))
		{
			$_RSP['ret'] = ERR_MISSING_PARM;
			$_RSP['msg'] = 'missing param(s)';
			exit(json_encode($_RSP));
		}

		if (empty($expireT))
		{
			$expireT = 0;
		}

		$this->load->library('auth');
		$sd = $this->auth->get_session();
		if (null === $sd)
		{
			$_RSP['ret'] = ERR_NO_SESSION;
			$_RSP['msg'] = 'not logined yet';
			exit(json_encode($_RSP));
		}
		$owerid = $sd['user_id'];

		$requirement = array(
			'requirement_type'		=> $type,
			'requirement_ownerid'	=> $owerid,
			'requirement_ownername'	=> $ownername,
			'requirement_ownerphone'=> $ownerphone,
            'requirement_shopcate'  => $shopcate,
            'requirement_shoparea'  => $shoparea,
            'requirement_shopcity'  => $shopcity,
            'requirement_budget'    => $budget,
			'requirement_title'		=> $title,
			'requirement_detail'	=> $detail,
			'requirement_et'		=> $expireT,
			'requirement_st'		=> time(),
			);

		$id = $this->requirement_model->add($requirement);
		if (FALSE == $id)
		{
			$_RSP['ret'] = ERR_DB_OPERATION_FAILED;
			$_RSP['msg'] = 'ERROR_DB_OPERATION';
		}
		else
		{
			$_RSP['ret'] = SUCCEED;
			$_RSP['requirement_id'] = $id;
		}
		exit(json_encode($_RSP));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
