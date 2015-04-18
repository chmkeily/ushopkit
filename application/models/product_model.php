<?php

class Product_model extends CI_Model
{
	var $TableName = 'tb_product';
    var $FieldMatrix = array(
            'product_id'         => 'ID',
            'product_providerid' => 'ProviderID',
            'product_name'       => 'Name',
            'product_detail'     => 'Detail',
            'product_price'      => 'Price',
            'product_quantity'   => 'Quantity',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($product)
    {
        $row = XFORMAT($product, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($product_id)
    {
		$this->db->where('ID', $product_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
    function get_product_by_id($product_id)
    {
        $row = $this->db->where('ID', $product_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    /**
    * @return array or FALSE
    */
    function get_products_by_providerid($providerid, $limit = 10, $offset = 0)
    {
        $rows = $this->db->where('ProviderID', $providerid)->get($this->TableName, $limit, $offset)->result_array();
        $products = array();
        foreach ($rows as $row)
        {
            $products[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $products;
    }
}
