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
            'shopcase_productNum' => 'ProductNum',
            'shopcase_products'   => 'Products',
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
    * @return array or FALSE
    */
    function get_shopcases_by_providerid($providerid, $limit = 10, $offset = 0)
    {
        $rows = $this->db->where('ProviderID', $providerid)->get($this->TableName, $limit, $offset)->result_array();
        $shopcases = array();
        foreach ($rows as $row)
        {
            $shopcases[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $shopcases;
    }
}
