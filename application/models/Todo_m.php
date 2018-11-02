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
    
    function get_list()
    {
        $sql = "SELECT * FROM items";
        
        $query = $this->db->query($sql);
        
        $result = $query->result();
        
        return $result;
    }
    
}

