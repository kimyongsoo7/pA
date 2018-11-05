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
    
    function write()
    {
        if ( $_POST )
        {
            $content = $this->input->post('content', TRUE);
            $created_on = $this->input->post('created_on', TRUE);
            $due_date = $this->input->post('due_date', TRUE);
            
            $this->todo_m->insert_todo($content, $created_on, $due_date);
            
            redirect('/../todo_main/lists/');
            
            exit;
        }
        else
        {
            $this->load->view('todo_main/write_v');
        }
        
    }
    
    function delete()
    {
        
        $id = $this->uri->segment(3);
    
        $this->todo_m->delete_todo($id);
        
        redirect('/../todo_main/lists/');
    }
    
}