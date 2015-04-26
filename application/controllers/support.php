<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @brief 技术支持需求(deprecated)
 * @deprecated
 */
class Support extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('support_model');
	}
	
	/**
	* @brief 技术支持需求
	*  <pre>
	*	接受的表单数据：
	*		start_idx	列表开始下标
	*		length		页大小/列表长度
	*		supportid	技术支持需求id
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
		$supports = $this->support_model->get_supports(null, $length, $offset);
		if (!empty($supports))
		{
			$_RSP['supports'] = $supports;
		}
		else
		{
			$_RSP['supports'] = array();
		}
		
		exit(json_encode($_RSP));
    }
	
	/**
	* @brief 提交技术支持需求
	*  <pre>
	*	接受的表单数据：
    *		type	类型
	*		title	标题
	*		contact	联系方式
    *		content 需求内容
	*  </pre>
	* @return 操作结果
	*/
    public function create()
    {
        $type       = trim($this->input->get_post('type', true));
        $title      = trim($this->input->get_post('title', true));
        $contact    = trim($this->input->get_post('contact', true));
        $content    = trim($this->input->get_post('content', true));

        if (empty($type) || empty($content))
        {
            $_RSP['ret'] = ERR_MISSING_PARM;
            $_RSP['msg'] = 'missing title or content';
            exit(json_encode($_RSP));
        }

        if (empty($type))
        {
            $type = 0;
        }

        $this->load->library('auth');
        $userid = $this->auth->get_userid();
        if (null !== $userid)
        {
            $userid = 0;    //未知用户
        }

        $support = array(
            'support_title'     => $title,
            'support_type'      => $type,
            'support_content'   => $content,
            'support_userid'    => $userid,
        );
        $ret = $this->support_model->add($support);
        if (false === $ret)
        {
            $_RSP['ret'] = ERR_DB_OPERATION_FAILED;
            $_RSP['msg'] = 'database exception';
            exit(json_encode($_RSP));
        }

        $_RSP['ret'] = 0;
        $_RSP['id']  = $ret;
        exit(json_encode($_RSP));
    }

}

/* End of file support.php */
