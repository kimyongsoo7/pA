<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Todo_board_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_list($table='ci_board', $type='', $offset='', $limit='', $search_word='')
    {
        $sword = '';
        
        if ( $search_word != '' )
        {
            $sword = ' WHERE subject like "%'.$search_word.'%" or contents like "%'.$search_word.'%" ';
        }
        
        $limit_query = '';
        
        if ( $limit != '' OR $offset != '' )
        {
            $limit_query = ' LIMIT '.$offset.', '.$limit;
        }
        
        $sql = "SELECT * FROM ".$table.$sword." ORDER BY board_id DESC".$limit_query;
        $query = $this->db->query($sql);
        
        if ( $type == 'count' )
        {
            $result = $query->num_rows();
            
            //$this->db->count_all($table);
        }
        else
        {
            $result = $query->result();
        }
        
        return $result;
    }
    
    function get_view($table, $id)
    {
        $sql0 = "UPDATE ".$table." SET hits=hits+1 WHERE board_id='".$id."'";
        $this->db->query($sql0);
        
        $sql = "SELECT * FROM ".$table." WHERE board_id='".$id."'";
        $query = $this->db->query($sql);
        
        $result = $query->row();
        
        return $result;
    }
    
    function insert_board($arrays)
    {
        $insert_array = array(
            'board_pid' => 0,
            'user_id' => 'louis',
            'user_name' => '루이스',
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents'],
            'reg_date' => date("Y-m-d H:i:s")
        );
        
        $result = $this->db->insert($arrays['table'], $insert_array);
        
        return $result;
    }
    
}