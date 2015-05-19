<?php

class Deal_model extends CI_Model
{
	var $TableName = 'tb_deal';
    var $FieldMatrix = array(
            'deal_id'       => 'ID',
            'deal_reqid'    => 'RequirementID',
            'deal_uid'      => 'UserID',
            'deal_pid'      => 'ProviderID',
            'deal_status'   => 'Status',
            'deal_uptime'   => 'UpdateTime',
            'deal_oplog'    => 'OpLog',
        );

    public function __construct()
    {
        parent::__construct();
        $this->load->database('ushopkit');
    }

    //增加
    function add($deal)
    {
        $row = XFORMAT($deal, $this->FieldMatrix);
        if(FALSE == $this->db->insert($this->TableName, $row))
        {   
            return FALSE;
        }
        
        return $this->db->insert_id();
    }
    
    function remove($deal_id)
    {
		$this->db->where('ID',$deal_id)->delete($this->TableName);
    }
    
    /**
    * @return array or FALSE
    */
	function get_deal_by_id($deal_id)
	{
		$row = $this->db->where('ID', $deal_id)->get($this->TableName)->row_array();
        if (empty($row))
        {
            return FALSE;
        }

        return XFORMAT($row, $this->FieldMatrix, FALSE);
    }

    /**
     * @brief 更新信息
     */
    function update($dealid, $updates = array())
    {
        $ufields = XFORMAT($updates, $this->FieldMatrix);
        if (empty($ufields))
        {
            return false;
        }

        $this->db->where('ID', $dealid)->update($this->TableName, $ufields);
        return $this->db->affected_rows();
    }

    /**
     * @brief 查询
     */
    function get_deals($conditions = array(), $limit = 10, $offset=0)
    {

    }
}
