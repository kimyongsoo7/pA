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
        $this->load->model('todo_board_m');
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
    
    /**
     * 목록 불러오기
     */
    public function lists()
    {
        $this->output->enable_profiler(TRUE);
        //검색어 초기화
        $search_word = $page_url = '';
        $uri_segment = 5;
        
        //주소중에서 q(검색어) 세그먼트가 있는지 검사하기 위해 주소를 배열로 변환
        $uri_array = $this->segment_explode($this->uri->uri_string());
        
        if( in_array('q', $uri_array) ) {
            //주소에 검색어가 있을 경우의 처리, 즉 검색시
            $search_word = urldecode($this->url_explode($uri_array, 'q'));
            
            //페이지네이션용 주소
            $page_url = '/q/'.$search_word;
            $uri_segment = 7;
        }
        
        //페이지네이션 라이브러리 로딩 추가
        $this->load->library('pagination');
        
        //페이지네이션 설정
        $config['base_url'] = '/todo_board/lists/ci_board'.$page_url.'/page/'; //페이징 주소
        $config['total_rows'] = $this->todo_board_m->get_list($this->uri->segment(3), 'count', '', '', $search_word); //게시물의 전체 갯수
        $config['per_page'] = 5; //한 페이지에 표시할 게시물 수
        $config['uri_segment'] = $uri_segment; //페이지 번호가 위치한 세그먼트
        
        //페이지네이션 초기화
        $this->pagination->initialize($config);
        //페이징 링크를 생성하여 view에서 사용할 변수에 할당
        $data['pagination'] = $this->pagination->create_links();
        
        //게시판 목록을 불러오기 위한 offset, limit 값 가져오기
        $data['page'] = $page = $this->uri->segment($uri_segment, 1);
        
        if ( $page > 1 )
        {
            $start = (($page/$config['per_page'])) * $config['per_page'];
        }
        else
        {
            $start = ($page-1) * $config['per_page'];
        }
        
        $limit = $config['per_page'];
        
        $data['list'] = $this->todo_board_m->get_list($this->uri->segment(3), '', $start, $limit, $search_word);
        $this->load->view('todo/list_v', $data);
    }
}