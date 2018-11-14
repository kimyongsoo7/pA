<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(__DIR__ . '/Base_controller.php');

//class Ajax_board extends CI_Controller {
class Ajax_board extends Base_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function ajax_comment_add()
    {
        //if( @$this->session->userdata('logged_in') == TRUE )
        if( @$_SESSION['logged_in'] == TRUE )
        {
            $this->load->model('bbs_board_m');
            
            $table = $this->input->post("table", TRUE);
            $board_id = $this->input->post("board_id", TRUE);
            $comment_contents = $this->input->post("comment_contents", TRUE);
            
            if ( $comment_contents != '')
            {
                $write_data = array(
                    'table' => $table,
                    'board_pid' => $board_id,
                    'subject' => '',
                    'contents' => $comment_contents,
                    'user_id' => $_SESSION['username']
                );
             
                $result = $this->bbs_board_m->insert_comment($write_data);
                
                if ( $result )
                {
                    $sql = "SELECT * FROM ".$table." WHERE board_pid = '".$board_id."' ORDER BY board_id DESC";
                    $query = $this->db->query($sql);
?>
        <table cellspacing="0" cellpadding="0" class="table table-striped" id="comment_table">
<?php
foreach ($query->result() as $lt)
{
?>
            <tr id="row_num_<?php echo $lt->board_id;?>">
                <th scope="row">
                    <?php echo $lt->user_id;?>
                </th>
                <td><?php echo $lt->contents;?></a></td>
                <td><time datetime="<?php echo mdate("%Y-%M-j", human_to_unix($lt->reg_date));?>"><?php echo $lt->reg_date;?></time></td>
                <td><a href="#" class="comment_delete" vals="<?php echo $lt->board_id;?>"><i class="icon-trash"></i>삭제</a></td>
            </tr>
<?php            
}    
?>
        </table>
<?php
                }
                else
                {
                    echo "2000";
                }
            }
            else
            {
                echo "1000";
            }
        }
        else
        {
            echo "9000";
        }
    }
    
    public function ajax_comment_delete()
    {
        if( @$_SESSION['logged_in'] == TRUE )
        {
            $this->load->model('bbs_board_m');
            
            $table = $this->input->post("table", TRUE);
            $board_id = $this->input->post("board_id", TRUE);
            
            $writer_id = $this->bbs_board_m->writer_check($table, $board_id);
            
            if( $writer_id->user_id != $_SESSION['username'])
            {
                echo "8000"; //본인 작성글이 아님
            }
            else
            {
                $result = $this->bbs_board_m->delete_content($table, $board_id);
                
                if ( $result )
                {
                    echo $board_id;
                }
                else
                {
                    //글 실패시
                    echo "2000";
                }
                
            }
        }
        else
        {
            echo "9000"; //로그인 필요 에러
        }
    }
    
    
}


