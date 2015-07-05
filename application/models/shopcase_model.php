<?php

class Shopcase_model extends CI_Model
{
	var $TableName = 'tb_shopcase';
    var $FieldMatrix = array(
            'shopcase_id'         => 'ID',
            'shopcase_name'       => 'Name',
            'shopcase_image'      => 'Image',
            'shopcase_intro'      => 'Intro',
            'shopcase_providerid' => 'ProviderID',
            'shopcase_content'    => 'Content',
            'shopcase_ctime'      => 'CreatedTime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($shopcase)
    {
        $row = XFORMAT($shopcase, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($shopcase_id)
    {
		$this->db->where('ID', $shopcase_id)->delete($this->TableName);
    }
    
    ///查询
    /**
     * 选择摘要列
     */
    function select_abstract()
    {
        $this->db->select('ID,Name,Image,Intro,ProviderID,CreatedTIme');
    }
    /**
    * @return array or FALSE
    */
    function get_shopcase_by_id($shopcase_id)
    {
        $row = $this->db->where('ID', $shopcase_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    /**
    * @return array
    */
    function get_shopcases_by_providerid($providerid, $limit = 10, $offset = 0)
    {
        $this->select_abstract();
        $rows = $this->db->where('ProviderID', $providerid)->get($this->TableName, $limit, $offset)->result_array();
        $shopcases = array();
        foreach ($rows as $row)
        {
            $shopcases[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $shopcases;
    }

    /**
     * @return array
     */
    function create_query($conditions)
    {
        if( !empty($conditions["shopcase_id"]) )
        {
            $this->db->where('ID', $conditions['shopcase_id']);
            return $this->db;
        }

        if( !empty($conditions["shopcase_providerid"]) )
        {
            $this->db->where('ProviderID', $conditions['shopcase_providerid']);
        }

        return $this->db;
    }

    /**
    * @return array or FALSE
    */
    function get_shopcases($conditions, $limit = 10, $offset = 0)
    {
        $this->select_abstract();
        $rows = $this->create_query($conditions)->get($this->TableName, $limit, $offset)->result_array();
        $shopcases = array();
        foreach ($rows as $row)
        {
            $shopcases[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $shopcases;
    }

    /**
     * @brief 更新
     */
    function update($caseid, $updates = array())
    {
        $ufields = XFORMAT($updates, $this->FieldMatrix);
        if (empty($ufields))
        {
            return false;
        }

        $this->db->where('ID', $caseid)->update($this->TableName, $ufields);
        return $this->db->affected_rows();
    }
}
