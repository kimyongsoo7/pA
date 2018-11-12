<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(__DIR__ . '/Base_controller.php');

//class Bbs_auth extends CI_Controller {
class Bbs_auth extends Base_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('bbs_auth_m');
        $this->load->helper('form');
    }
    
    public function index()
    {
        $this->login();
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
    
    public function login()
    {
        $this->load->library('form_validation');
        
        $this->load->helper('alert');
        
        $this->form_validation->set_rules('username', '아이디', 'required|alpha_numeric');
        $this->form_validation->set_rules('password', '비밀번호', 'required');
        
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        
        if ( $this->form_validation->run() == TRUE )
        {
            $auth_data = array(
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password', TRUE)
            );
            
            $result = $this->bbs_auth_m->login($auth_data);
            
            if ( $result )
            {
                /*$newdata = array(
                    'username' => $result->username,
                    'email' => $result->email,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($newdata);*/
                
                $_SESSION['username'] = $result->username;
                $_SESSION['email'] = $result->email;
                $_SESSION['logged_in'] = TRUE;
                
                alert('로그인 되었습니다.', '/bbs_board/lists/ci_board/page/1');
                exit;
            }
            else
            {
                alert('아이디나 비밀번호를 확인해 주세요.', '/bbs_board/lists/ci_board/page/1');
                exit;
            }
            
        }
        else
        {
            $this->load->view('bbs_auth/login_v');
        }
    }
    
}