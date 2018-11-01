<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 게시판 메인 controller.
 * 
 * 
 */
class Todo_board extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('board_m');
    }
    
    /**
     * 주소에서 메소드가 생략되었을 때 실행되는 기본 메소드
     */
    public function index()
    {
        $this->lists();
    }
    
    /**
     * 사이트 헤더, 푸터를 자동으로 추가해준다.
     * 
     */
    public function __remap($method)
    {
        //헤더 include
        $this->load->view('header_v');
        
        if( method_exists($this, $method) )
        {
            $this->{"{$method}"}();
        }
        
        //푸터 include
        $this->load->view('footer_v');
    }
}