<?php

class Coupon_model extends CI_Model
{
	var $TableName = 'tb_coupon';
    var $FieldMatrix = array(
            'coupon_id'         => 'ID',
            'coupon_providerid' => 'ProviderID',
            'coupon_title'      => 'Title',
            'coupon_content'    => 'Content',
            'coupon_begintime'  => 'BeginTime',
            'coupon_endtime'    => 'EndTime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($coupon)
    {
        $row = XFORMAT($coupon, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($coupon_id)
    {
		$this->db->where('ID', $coupon_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
    function get_coupon_by_id($coupon_id)
    {
        $row = $this->db->where('ID', $coupon_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    /**
    * @return array or FALSE
    */
    function get_coupons_by_providerid($providerid, $limit = 10, $offset = 0)
    {
        $rows = $this->db->where('ProviderID', $providerid)->get($this->TableName, $limit, $offset)->result_array();
        $coupons = array();
        foreach ($rows as $row)
        {
            $coupons[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $coupons;
    }
}