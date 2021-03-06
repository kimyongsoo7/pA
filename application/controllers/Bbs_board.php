<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(__DIR__ . '/Base_controller.php');

//class Bbs_board extends CI_Controller {
class Bbs_board extends Base_Controller {
    
    function __construct()
    {
        parent::__construct();
        //$this->load->database();
        $this->load->model('bbs_board_m');
        $this->load->helper('form');
    }
    
    public function index()
    {
        $this->lists();
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
    
    public function lists()
    {
        $this->output->enable_profiler(TRUE);
        
        $search_word = $page_url = '';
        $uri_segment = 5;
        
        $uri_array = $this->segment_explode($this->uri->uri_string());
        
        if( in_array('q', $uri_array) ) {
            
            $search_word = urldecode($this->url_explode($uri_array, 'q'));
            
            $page_url = '/q/'.$search_word;
            $uri_segment = 7;
        }
        
        $this->load->library('pagination');
        
        $config['base_url'] = '/bbs_board/lists/ci_board'.$page_url.'/page/';
        $config['total_rows'] = $this->bbs_board_m->get_list($this->uri->segment(3), 'count', '', '', $search_word);
        $config['per_page'] = 5;
        $config['uri_segment'] = $uri_segment;
        
        $this->pagination->initialize($config);
        
        $data['pagination'] = $this->pagination->create_links();
        
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
        
        $data['list'] = $this->bbs_board_m->get_list($this->uri->segment(3), '', $start, $limit, $search_word);
        $this->load->view('bbs_board/list_v', $data);
    }
    
    function view()
    {
        $table = $this->uri->segment(3);
        $board_id = $this->uri->segment(5);
        
        $data['views'] = $this->bbs_board_m->get_view($table, $board_id);
        
        $data['comment_list'] = $this->bbs_board_m->get_comment($table, $board_id);
        
        $this->load->view('bbs_board/view_v', $data);
    }
    
    function write()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        
        //if( @$this->session->userdata('logged_in') == TRUE )
        if( @$_SESSION['logged_in'] == TRUE )
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('subject', '제목', 'required');
            $this->form_validation->set_rules('contents', '내용', 'required');
            
            if ( $this->form_validation->run() == TRUE )
            {
                $uri_array = $this->segment_explode($this->uri->uri_string());
                
                if( in_array('page', $uri_array) )
                {
                    $pages = urldecode($this->url_explode($uri_array, 'page'));
                }
                else
                {
                    $pages = 1;
                }
                
                $write_data = array(
                    'table' => $this->uri->segment(3),
                    'subject' => $this->input->post('subject', TRUE),
                    'contents' => $this->input->post('contents', TRUE),
                    'user_id' => $_SESSION['username']
                    //'user_id' => $this->session->userdata('username')
                );
                
                $result = $this->bbs_board_m->insert_board($write_data);
                
                if ( $result )
                {
                    alert('입력되었습니다.', '/bbs_board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                    exit;
                }
                else
                {
                    alert('다시 입력해 주세요.', '/bbs_board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                    exit;
                }
                
            }
            else
            {
                $this->load->view('bbs_board/write_v');
            }
        }
        else
        {
            alert('로그인후 작성하세요', '/bbs_auth/login/');
            exit;
        }
    }
    
    function modify()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        
        $uri_array = $this->segment_explode($this->uri->uri_string());
        
        if( in_array('page', $uri_array) )
        {
            $pages = urldecode($this->url_explode($uri_array, 'page'));
        }
        else
        {
            $pages = 1;
        }
        
        if( @$_SESSION['logged_in'] == TRUE )
        {
            $writer_id = $this->bbs_board_m->writer_check($this->uri->segment(3), $this->uri->segment(5));
            
            if( $writer_id->user_id != $_SESSION['username'] )
            {
                alert('본인이 작성한 글이 아닙니다.', '/bbs_board/view/'.$this->uri->segment(3).'/board_id/'.$this->uri->segment(5).'/page/'.$pages);
                exit;
            }
            
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('subject', '제목', 'required');
            $this->form_validation->set_rules('contents', '내용', 'required');
            
            if ( $this->form_validation->run() == TRUE )
            {
                if ( !$this->input->post('subject', TRUE) AND !$this->input->post('contents', TRUE) )
                {
                    alert('비정상적인 접근입니다.', '/bbs_board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                    exit;
                }
                
                //var_dump($_POST)
                $modify_data = array(
                    'table' => $this->uri->segment(3),
                    'board_id' => $this->uri->segment(5),
                    'subject' => $this->input->post('subject', TRUE),
                    'contents' => $this->input->post('contents', TRUE)
                );
                
                $result = $this->bbs_board_m->modify_board($modify_data);
                
                if ( $result )
                {
                    alert('수정되었습니다.', '/bbs_board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                    exit;
                }
                else
                {
                    alert('다시 수정해 주세요.', '/bbs_baord/view/'.$this->uri->segment(3).'/board_id/'.$this->uri->segment(5).'/page/'.$pages);
                    exit;
                }
                
            }
            else
            {
                $data['views'] = $this->bbs_board_m->get_view($this->uri->segment(3), $this->uri->segment(5));
                
                $this->load->view('bbs_board/modify_v', $data);
            }
        }
        else
        {
            alert('로그인후 수정하세요.', '/bbs_board/auth/login/');
            exit;
        }
    }
    
    function delete()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' ;
        
        if( @$_SESSION['logged_in'] == TRUE )
        {
            $table = $this->uri->segment(3);
            $board_id = $this->uri->segment(5);
            
            $writer_id = $this->bbs_board_m->writer_check($table, $board_id);
            
            if( $writer_id->user_id != $_SESSION['username'] )
            {
                alert('본인이 작성한 글이 아닙니다.', '/bbs_board/view/'.$this->uri->segment(3).'/board_id/'.$this->uri->segment(5).'/page/'.$this->uri->segment(7));
                exit;
            }
            
            $return = $this->bbs_board_m->delete_content($this->uri->segment(3), $this->uri->segment(5));
            
            if ( $return )
            {
                alert('삭제되었습니다.', '/bbs_board/lists/'.$this->uri->segment(3).'/page/'.$this->uri->segment(7));
            }
            else
            {
                alert('삭제 실패하였습니다.', '/bbs_board/view/'.$this->uri->segment(3).'/board_id/'.$this->uri->segment(5).'/page/'.$this->uri->segment(7));
            }
        }
        else
        {
            alert('로그인후 삭제하세요', '/bbs_auth/login/');
            exit;
        }
    }
    
    
    
    
    
    
    function url_explode($url, $key)
    {
        $cnt = count($url);
        for($i=0; $cnt>$i; $i++ )
        {
            if($url[$i] ==$key)
            {
                $k = $i+1;
                return $url[$k];
            }
        }
    }
    
    function segment_explode($seg)
    {
        $len = strlen($seg);
        if(substr($seg, 0, 1) == '/')
        {
            $seg = substr($seg, 1, $len);
        }
        $len = strlen($seg);
        if(substr($seg, -1) == '/')
        {
            $seg = substr($seg, 0, $len-1);
        }
        $seg_exp = explode("/", $seg);
        return $seg_exp;
    }
}












































