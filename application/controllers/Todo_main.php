<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(__DIR__ . '/Base_controller.php');


//class Todo_main extends CI_Controller {
class Todo_main extends Base_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('todo_m');
        $this->load->helper('url');
    }
    
    
    
    
    public function index()
    {
        $this->lists();
    }
    
    public function lists()
    {
        $data['list'] = $this->todo_m->get_list();
        
        $this->load->view('todo/list_main_v', $data);
    }
    
}