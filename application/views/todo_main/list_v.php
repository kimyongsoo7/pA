<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=no" />
        <title>CodeIgniter</title>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <link rel="stylesheet" href="/include/css/bootstrap.css" />
    </head>    
    <body>
        <div id="main">
            
            <header id="header" data-role="header" data-position="fixed">
                <blockquote>
                    <p>CodeIgniter</p>
                </blockquote>
            </header>
            
            <nav id="gnb">
                <ul>
                    <li><a rel="external" href="/main/lists/">todo application</a></li>
                </ul>
            </nav>
            <article id="board_area">
                <header>
                    <h1>Todo 목록</h1>
                </header>
                <table cellspacing="0" cellpadding="0" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">내용</th>
                            <th scope="col">시작일</th>
                            <th scope="col">종료일</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($list as $lt)
{
?>
                        <tr>
                            <th scope="row">
                                <?php echo $lt->id;?>
                            </th>
                            <td><a rel="external" href="/todo_main/view/<?php echo $lt->id;?>"><?php echo $lt->content;?></a></td>
                            <td><time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->created_on));?>"><?php echo $lt->created_on;?></time></td>
                            <td><time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->due_date));?>"><?php echo $lt->due_date;?></time></td>
                        </tr>
<?php                        
}
?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"><a href="/todo_main/write/" class="btn btn-success">쓰기</a></th>
                        </tr>
                    </tfoot>
                </table>
                <div><p></p></div>
                
            </article>
            
            <footer id="footer">
                <blockquote>
                    <p><a class="azubu" href="http://www.cikorea.net/" target="blank">사용자포럼</a></p>
                </blockquote>
            </footer>
            
        </div>
        
    </body>
</html>