<?php 
class Provider_model extends CI_Model
{
	var $TableName = 'tb_provider';
    var $FieldMatrix = array(
            'provider_id'       => 'ID',
            'provider_name'     => 'Name',
            'provider_icon'     => 'Icon',
            'provider_license'  => 'License',
            'provider_location' => 'Location',
            'provider_contact'  => 'Contact',
            'provider_address'  => 'Address',
            'provider_brief'    => 'Brief',
            'provider_intro'    => 'Intro',
            'provider_rating'   => 'Rating',
            'provider_status'   => 'Status',
            'provider_casenum'  => 'CaseNum',
            'provider_verified' => 'Verified',
            'provider_warrant'  => 'Warranted',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    ///业务接口
    //增加
    function add($provider)
    {
        $row = XFORMAT($provider, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($provider_id)
    {
		$this->db->where('ID', $provider_id)->delete($this->TableName);
    }
    
    ///查询
    function create_query($data)
    {
        if( !empty($data["provider_id"]) )
        {
            $this->db->where('ID', $data['provider_id']);
            return $this->db;
        }

        if( !empty($data["provider_name"]) )
        {
            $this->db->where('Name', $data['provider_name']);
        }

        if( !empty($data["provider_location"]) )
        {
            $this->db->where('Location', $data['provider_location']);
        }
		
		if ( isset($data['order_by']) && '' !== $data['order_by'] )
		{
			$this->db->order_by($data['order_by']);
		}

        return $this->db;
    }
    
    /**
    * @brief 查询服务商摘要列表
    */
    function get_providers($conditions = array(), $limit = 10, $offset = 0)
    {
        $this->db->select('ID,Name,Icon,Location,Address,Contact,Brief,Rating,CaseNum,Verified,Warranted');
		$conditions['order_by'] = 'Rating DESC';
		$rows = $this->create_query($conditions)->get($this->TableName, $limit, $offset)->result_array();

        $items = array();
        foreach ($rows as $row)
        {
            $items[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $items;
    }

    function get_total_num($conditions)
    {   
        $result=$this->create_query($conditions)->select('count(*) as total_num')->get($this->TableName)->row();
        return $result->total_num;
    }
    
    ///更新
    function update($provider_id, $data)
    {
        $this->db->where('ID', $provider_id);
        $updates = XFORMAT($data, $this->FieldMatrix);
        $this->db->update($this->TableName, $updates);
    }
	
	function get_provider_by_id($provider_id)
	{
		$row = $this->db->where('ID', $provider_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    function get_providers_by_keyword($keyword, $limit = 10)
    {
        $rows = $this->db->query('select ID,Name,Icon from '. $this->TableName
                                .' where Name like \"'. $keyword '\" limit ' . $limit)->result_array();

        $items = array();
        foreach ($rows as $row)
        {
            $items[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $items;
    }
}
