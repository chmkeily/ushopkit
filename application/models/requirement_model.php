<?php

class Requirement_model extends CI_Model
{
	var $TableName = 'tb_requirement';
    var $FieldMatrix = array(
            'requirement_id'            => 'ID',
            'requirement_ownerid'       => 'OwnerID',
            'requirement_ownername'     => 'OwnerName',
            'requirement_ownerphone'    => 'OwnerPhone',
            'requirement_type'          => 'Type',
            'requirement_shopcate'      => 'ShopCategory',
            'requirement_shoparea'      => 'ShopArea',
            'requirement_shopcity'      => 'ShopCity',
            'requirement_budget'        => 'Budget',
            'requirement_title'         => 'Title',
            'requirement_detail'        => 'Detail',
            'requirement_et'            => 'ExpiredTime',
            'requirement_st'            => 'SubmitTime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    ///业务接口
    function add($requirement)
    {
        $row = XFORMAT($requirement, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($requirement_id)
    {
		$this->db->where('ID', $requirement_id)->delete($this->TableName);
    }
    
    function create_query($conditions)
    {

        if( !empty($conditions["requirement_id"]) )
        {
            $this->db->where('ID', $conditions['requirement_id']);
        }

        if( !empty($conditions["requirement_ownerid"]) )
        {
            $this->db->where('OwnerID', $conditions['requirement_ownerid']);
        }

        if( !empty($conditions["requirement_type"]) )
        {
            $this->db->where('Type', $conditions['requirement_type']);
        }

        if( !empty($conditions["requirement_title"]) )
        {
            $this->db->where('Title', $conditions['requirement_title']);
        }
		
		if ( isset($conditions['order_by']) && '' !== $conditions['order_by'] )
		{
			$this->db->order_by($conditions['order_by']);
		}

        return $this->db;
    }
    
    /**
    * @param array
    * @param int
    * @param int
    * @param mixed
    */
    function get_requirements($conditions, $limit, $offset)
    {
        $conditions['order_by'] = 'ExpiredTime ASC';
        $rows = $this->create_query($conditions)->get($this->TableName, $limit, $offset)->result_array();

        $items = array();
        foreach ($rows as $row)
        {
            $r = XFORMAT($row, $this->FieldMatrix, FALSE);
            if (!empty($r))
            {
                $items[] = $r;
            }
        }

        return $items;
    }

    function get_total_num($conditions)
    {
        $conditions = XFORMAT($conditions, $this->FieldMatrix);
        $result = $this->create_query($conditions)->select('Count(*) as total_num')->get($this->TableName)->row();
        return $result->total_num;
    } 

    /**
    * @param int
    * @param array
    */
    function modify($requirement_id, $data)
    {
        $update = $this->XFORMAT($data, $this->FieldMatrix);
        $this->db->where('ID', $requirement_id);
        return $this->db->update($this->TableName, $update);
    }
}
