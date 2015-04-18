<?php

class Message_model extends CI_Model
{
	var $TableName = 'tb_message';
    var $FieldMatrix = array(
            'message_id'       => 'ID',
            'message_userid'   => 'UserID',
            'message_senderid' => 'SenderID',
            'message_type'     => 'Type',
            'message_referid'  => 'ReferID',
            'message_title'    => 'Title',
            'message_content'  => 'Content',
            'message_time'     => 'MsgTime',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($message)
    {
        $row = XFORMAT($message, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($message_id)
    {
		$this->db->where('ID',$message_id)->delete($this->TableName);
    }
    
    ///查询
    /**
    * @return array or FALSE
    */
	function get_message_by_id($message_id)
	{
		$row = $this->db->where('ID', $message_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
	}

    /**
    * @return array or FALSE
    */
    function get_messages_by_userid($userid, $limit = 10, $offset = 0)
    {
        $this->db->order_by('MsgTime desc');
        $rows = $this->db->where('UserID', $userid)->get($this->TableName, $limit, $offset)->result_array();

        $msgs = array();
        foreach ($rows as $row)
        {
            $msgs[] = XFORMAT($row, $this->FieldMatrix, FALSE);
        }

        return $msgs;
    }
}
