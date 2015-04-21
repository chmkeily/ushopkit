<?php

class Support_model extends CI_Model
{
	var $TableName = 'tb_support';
    var $FieldMatrix = array(
            'support_id'       => 'ID',
            'support_userid'   => 'UserID',
            'support_type'     => 'Type',
            'support_title'    => 'Title',
            'support_contact'  => 'Contact',
            'support_content'  => 'Content',
            'support_time'     => 'CreateTime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($support)
    {
        $row = XFORMAT($support, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($support_id)
    {
		$this->db->where('ID',$support_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
	function get_support_by_id($support_id)
	{
		$row = $this->db->where('ID', $support_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
	}

    /**
    * @return array or FALSE
    */
    function get_supports_by_userid($userid, $limit = 10, $offset = 0)
    {
        $this->db->order_by('CreateTime desc');
        $rows = $this->db->where('UserID', $userid)->get($this->TableName, $limit, $offset)->result_array();

        $msgs = array();
        foreach ($rows as $row)
        {
            $msgs[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $msgs;
    }

    /**
    * @return array or FALSE
    */
    function get_supports($conditions, $limit = 10, $offset = 0)
    {
        $this->db->order_by('CreateTime desc');
        $rows = $this->db->get($this->TableName, $limit, $offset)->result_array();

        $msgs = array();
        foreach ($rows as $row)
        {
            $msgs[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $msgs;
    }
}
