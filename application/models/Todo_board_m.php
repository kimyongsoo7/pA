<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 공통 게시판 모델 (이미지 포함)
 * 
 * 
 * 
 */
class Todo_board_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 게시물 목록 가져오기
     * 
     * 
     * @param string $table 게시판 테이블
     * @param string $type 총 게시물 수 또는 게시물 배열을 반환할 지를 결정하는 구분자
     * @param string $offset 게시물 가져올 순서
     * @param string $limit 한 화면에 표시할 게시물 갯수
     * @param string $search_word 검색어
     * @return array
     */
    function get_list($table='ci_board', $type='', $offset='', $limit='', $search_word='')
    {
        $sword= '';
        
        if ( $search_word != '' )
        {
            //검색어가 있을 경우의 처리
            $sword = ' WHERE subject like "%'.$search_word.'%" or contents like "%'.$search_word.'%" ';
        }
        
        $limit_query = '';
        
        if ( $limit != '' OR $offset != '' )
        {
            //페이징이 있을 경우의 처리
            $limit_query = ' LIMIT '.$offset.', '.$limit;
        }
        
        $sql = "SELECT * FROM ".$table.$sword." ORDER BY board_id DESC".$limit_query;
        $query = $this->db->query($sql);
        
        if ( $type == 'count' )
        {
            //리스트를 반환하는 것이 아니라 전체 게시물의 갯수를 반환
            $result = $query->num_rows();
            
            //$this->db->count_all($table);
        }
        else
        {
            //게시물 리스트 반환
            $result = $query->result();
        }
        
        return $result;
    }
    
}