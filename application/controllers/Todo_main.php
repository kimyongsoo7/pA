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
    
    function view()
    {
        $id = $this->uri->segment(3);
        
        $data['views'] = $this->todo_m->get_view($id);
        
        $this->load->view('todo_main/view_v', $data);
    }
    
    public function lists()
    {
        $data['list'] = $this->todo_m->get_list();
        
        $this->load->view('todo_main/list_v', $data);
    }
    
}