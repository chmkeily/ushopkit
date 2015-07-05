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
	*		start_idx		开始序号 (默认值:0)
	*		length		    列表长度 (默认值:10)
	*		providerid		服务商ID (可选)
	*  </pre>
	* @return 操作结果
	*/
	public function index()
	{
		$offset		= trim($this->input->get_post('start_idx', TRUE));
		$length		= trim($this->input->get_post('length', TRUE));
		$providerid	= trim($this->input->get_post('providerid', TRUE));
        
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
        if (is_numeric($providerid))
        {
            $conditions['shopcase_providerid'] = $providerid;
        }
        
        $shopcases = $this->shopcase_model->get_shopcases($conditions, $length, $offset);
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
	* @brief 服务商案例查询 (已废弃，请使用index接口+服务商id参数)
    * @deprecated
	*  <pre>
	*	接受的请求数据：
	*		providerid		服务商ID
	*  </pre>
	* @return 操作结果
	*/
	public function provider_case()
	{
		$provider_id = trim($this->input->get_post('providerid', TRUE));
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


    /**
     * @brief 提交/创建新案例
     * <pre>
     * 接受的请求数据：
     *      case_name		案例名称/标题
     *      case_image		案例封面/主图片
     *      case_intro      案例简介
     *      case_providerid 服务商ID
     *      case_content    案例详细内容(展示层自定义内容格式, 使用base64对其进行编码)
     * </pre>
     * @return 操作结果
     * <pre>
     *      {"ret":0,"id":1}
     * </pre>
     */
    function create()
    {
		$case_name       = trim($this->input->get_post('case_name', TRUE));
		$case_image      = trim($this->input->get_post('case_image', TRUE));
		$case_intro      = trim($this->input->get_post('case_intro', TRUE));
		$case_providerid = trim($this->input->get_post('case_providerid', TRUE));
        $case_content    = trim($this->input->get_post('case_content', TRUE));

        if (empty($case_providerid) || !is_numeric($case_providerid))
        {
            $_RSP['ret']    = ERR_INVALID_VALUE;
            $_RSP['msg']    = 'invalid provider id';
            exit(json_encode($_RSP));
        }

        $shopcase = array(
            'shopcase_name'         => $case_name,
            'shopcase_image'        => $case_image,
            'shopcase_intro'        => $case_intro,
            'shopcase_providerid'   => $case_providerid,
            'shopcase_content'      => $case_content,
        );

        $id = $this->shopcase_model->add($shopcase);
        if (false === $id)
        {
            $_RSP['ret']    = ERR_DB_OPERATION_FAILED;
            $_RSP['msg']    = 'database exception';
            exit(json_encode($_RSP));
        }

        $_RSP['ret'] = SUCCEED;
        $_RSP['id']  = $id;
        exit(json_encode($_RSP));
    }

    /**
     * @brief 更新字段内容
     * <pre>
     *  接受的请求数据：
     *      case_id		    案例ID
     *      case_name		案例名称/标题
     *      case_image		案例封面/主图片
     *      case_intro      案例简介
     *      case_content    案例详细内容(展示层自定义内容格式, 使用base64对其进行编码)
     * </pre>
     * @return 操作结果
     * <pre>
     *      {"ret":0}
     * </pre>
     */
    function update()
    {
        $case_id         = trim($this->input->get_post('case_id', TRUE));
        $case_name       = trim($this->input->get_post('case_name', TRUE));
		$case_image      = trim($this->input->get_post('case_image', TRUE));
		$case_intro      = trim($this->input->get_post('case_intro', TRUE));
        $case_content    = trim($this->input->get_post('case_content', TRUE));

        if (empty($case_id) || !is_numeric($case_id))
        {
            $_RSP['ret'] = ERR_INVALID_VALUE;
            $_RSP['msg'] = 'invalide shopcase id';
            exit(json_encode($_RSP));
        }

        $updates = array();
        if (!empty($case_name))
        {
            $updates['shopcase_name'] = $case_name;
        }
        if (!empty($case_image))
        {
            $updates['shopcase_image'] = $case_image;
        }
        if (!empty($case_intro))
        {
            $updates['shopcase_intro'] = $case_intro;
        }
        if (!empty($case_content))
        {
            $updates['shopcase_content'] = $case_content;
        }

        $ret = $this->shopcase_model->update(case_id, $updates);
        if (false === $ret)
        {
            $_RSP['ret'] = ERR_DB_OPERATION_FAILED;
            $_RSP['msg'] = 'database exception';
            exit(json_encode($_RSP));
        }

        $_RSP['ret'] = SUCCEED;
        exit(json_encode($_RSP));
    }
}

/* End of file shopcase.php */
