<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bbs_board_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_list($table='ci_board', $type='', $offset='', $limit='', $search_word='')
    {
        $sword = ' WHERE 1=1 ';
        
        if ( $search_word != '' )
        {
            $sword = ' WHERE subject like "%'.$search_word.'%" or contents like "%'.$search_word.'%" ';
        }
        
        $limit_query = '';
        
        if ( $limit != '' OR $offset != '' )
        {
            $limit_query = ' LIMIT '.$offset.', '.$limit;
        }
        
        $sql = "SELECT * FROM ".$table.$sword." AND board_pid = '0' ORDER BY board_id DESC".$limit_query;
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
            'user_id' => $arrays['user_id'],
            'user_name' => $arrays['user_id'],
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents'],
            'reg_date' => date("Y-m-d H:i:s")
        );
        
        $result = $this->db->insert($arrays['table'], $insert_array);
        
        return $result;
    }
    
    function get_comment($table, $id)
    {
        $sql = "SELECT * FROM ".$table." WHERE board_pid='".$id."' ORDER BY board_id DESC";
        $query = $this->db->query($sql);
        
        $result = $query->result();
        
        return $result;
    }
    
    function modify_board($arrays)
    {
        $modify_array = array(
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents']
        );
        
        $where = array(
            'board_id' => $arrays['board_id']
        );
        
        $result = $this->db->update($arrays['table'], $modify_array, $where);
        
        return $result;
    }
    
    function delete_content($table, $no)
    {
        $delete_array = array(
            'board_id' => $no
        );
        
        $result = $this->db->delete($table, $delete_array);
        
        return $result;
    }
    
    function writer_check($table, $board_id)
    {
        $sql = "SELECT user_id FROM ".$table." WHERE board_id = '".$board_id."'";
        
        $query = $this->db->query($sql);
        
        return $query->row();
    }
    
    function insert_comment($arrays)
    {
        $insert_array = array(
            'board_pid' => $arrays['board_pid'],
            'user_id' => $arrays['user_id'],
            'user_name' => $arrays['user_id'],
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents'],
            'reg_date' => date("Y-m-d H:i:s")
        );
        
        $this->db->insert($arrays['table'], $insert_array);
        
        $board_id = $this->db->insert_id();
        
        return $board_id;
    }
    
    
}




































