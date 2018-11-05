<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(__DIR__ . '/Base_controller.php');

//class Todo_test extends CI_Controller {
class Todo_test extends Base_Controller {

    function __construct()
    {
        parent::__construct();
    }
    
    public function _remap($method)
    {
        
        $this->load->view('header_v');
        
        if( method_exists($this, $method) )
        {
            $this->{"{$method}"}();
        }
        
        $this->load->view('footer_v');
    }
    
    /**
     * 폼 검증 테스트
     */
    public function forms()
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->library('form_validation');
        
        
        $this->form_validation->set_rules('username', '아이디', 'required|min_length[5]|max_length[12]');
        $this->form_validation->set_rules('password', '비밀번호', 'required|matches[passconf]');
        $this->form_validation->set_rules('passconf', '비밀번호 확인', 'required');
        $this->form_validation->set_rules('email', '이메일', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('todo_test/forms_v');
        }
        else
        {
            $this->load->view('todo_test/form_success_v');
        }
    }
    
}

