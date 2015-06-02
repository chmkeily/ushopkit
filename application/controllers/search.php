<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @brief 搜索
*/
class Search extends CI_Controller {
	
	/**
	* constructor
	*/
	public function __construct()
	{
		parent::__construct();
        $this->load->model('provider_model');
	}
	
	/**
	* @brief 搜索
	*  <pre>
	*	接受的表单数据：
	*		keyword		关键字
	*  </pre>
    * @return 操作结果列表
    *   {"ret":0, entries:[{"type":1,"id":1,"info":"深圳市芊袼科技有限公司"}]}
	*/
	public function index()
	{
        $keyword	= trim($this->input->get_post('keyword', TRUE));

        if (empty($keyword))
        {
            $_RSP['ret'] = -1;
            $_RSP['msg'] = 'please input keyword to search';
            exit(json_encode($_RSP));
        }

        $_RSP['ret'] = 0;
		$providers = $this->provider_model->get_providers_by_keyword($keyword, 10);
		if (!empty($providers))
        {
            $entries = array();
            foreach ($providers as $provider)
            {
                $entries[] = array(
                    'type'  => 1,
                    'id'    => $provider['provider_id'],
                    'info'  => $provider['provider_name']
                );
            }

			$_RSP['entries'] = $entries;
		}
		
		exit(json_encode($_RSP));
	}
}

/* End of file search.php */
