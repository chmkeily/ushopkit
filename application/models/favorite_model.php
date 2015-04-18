<?php

class Favorite_model extends CI_Model
{
	var $TableName = 'tb_favorite';
    var $FieldMatrix = array(
            'favorite_id'       => 'ID',
            'favorite_userid'   => 'UserID',
            'favorite_type'     => 'Type',
            'favorite_referid'  => 'ReferID',
            'favorite_brief'    => 'Brief',
            'favorite_ftime'    => 'ftime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($favorite)
    {
        $row = XFORMAT($favorite, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($favorite_id)
    {
		$this->db->where('ID', $favorite_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
    function get_favorite_by_id($favorite_id)
    {
        $row = $this->db->where('ID', $favorite_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    /**
    * @return array or FALSE
    */
    function get_favorites_by_userid($userid, $limit = 10, $offset = 0)
    {
        $this->db->order_by('ftime desc');
        $rows = $this->db->where('UserID', $userid)->get($this->TableName, $limit, $offset)->result_array();
        $favorites = array();
        foreach ($rows as $row)
        {
            $favorites[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $favorites;
    }
}
