<?php

class CMSUser_model extends CI_Model
{
	var $TableName = 'tb_cms_user';
    var $FieldMatrix = array(
            'user_id'       => 'ID',
            'user_email'    => 'Email',
            'user_secret'   => 'Secret',
            'user_type'     => 'Type',
            'user_name'     => 'Name',
            'user_icon'     => 'Icon',
            'user_phone'    => 'Phone',
            'user_contact'  => 'Contact',
            'user_address'  => 'Address',
            'user_location' => 'Location',
            'user_status'   => 'Status',
            'user_brief'    => 'Brief',
            'user_intro'    => 'Intro',
            'user_license'  => 'License',
            'user_pid'      => 'ProviderID',
            'user_verified' => 'Verified',
            'user_warranted' => 'Warranted',
            'user_fieldtag' => 'FieldTga',
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

    /**
     * @brief 更新信息
     */
    function update($userid, $updates = array());
    {
        $ufields = XFORMAT($updates, $this->FieldMatrix);
        if (empty($ufields))
        {
            return false;
        }

        $this->db->where('ID', $userid)->update($this->TableName, $ufields);
        return $this->db->affected_rows();
    }
}
