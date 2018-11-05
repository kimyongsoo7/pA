<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * todo 모델
 * 
 * 
 * 
 */
class Todo_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_view($id)
    {
        $sql = "SELECT * FROM items WHERE id='".$id."'";
        
        $query = $this->db->query($sql);
        
        $result = $query->row();
        
        return $result;
    }
    
    function get_list()
    {
        $sql = "SELECT * FROM items";
        
        $query = $this->db->query($sql);
        
        $result = $query->result();
        
        return $result;
    }
    
    function insert_todo($content, $created_on, $due_date)
    {
        $sql = "INSERT INTO items (content, created_on, due_date) VALUES ('".$content."', '".$created_on."', '".$due_date."')";
        
        $query = $this->db->query($sql);
    }
    
}

