<?php

class User_model extends CI_Model
{
	var $TableName = 'tb_user';
    var $FieldMatrix = array(
            'user_id'       => 'ID',
            'user_email'    => 'Email',
            'user_secret'   => 'Secret',
            'user_type'     => 'Type',
            'user_name'     => 'Name',
            'user_location' => 'Location',
            'user_contact'  => 'Contact',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($user)
    {
        $row = XFORMAT($user, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($user_id)
    {
		$this->db->where('ID',$user_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
    function get_user_by_email($email)
    {
        $row = $this->db->where('Email', $email)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }
	
    /**
    * @return array or FALSE
    */
	function get_user_by_id($user_id)
	{
		$row = $this->db->where('ID', $user_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
	}
}