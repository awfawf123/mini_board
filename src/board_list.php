<?php
    define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
    define("URL_DB",DOC_ROOT."src/common/db_common.php");
    include_once(URL_DB);
    //최초 페이지 열때 페이지 넘버
    if(array_key_exists("page_num",$_GET)){
        $page_num = $_GET["page_num"];
    }else{
        $page_num = 1; //key가 없으면 1로 셋팅
    }
    //한 페이지당 5개행만 보여줌
    $limit_num = 10;
    //보여줄 페이지 갯수
    $page_block = 5;
    $now_page = ceil($page_num/$page_block);
    $s_page_num = ($now_page-1) * $page_block+1;
    //데이터가 없을 경우
    if($s_page_num<=0){
        $s_page_num=1;
    }
    $e_page_num = $now_page * $page_block;
    //삭제 안 된 테이블 레코드 총 갯수 카운트
    $result_cnt = select_board_info_count();

    //1페이지일때 0, 2페이지 일때5, 3페이지 일때 10 ...
    $offset = ($page_num * $limit_num)- $limit_num;
    // 반올림,int로 형변환
    $max_page_num = ceil((int)$result_cnt[0]["cnt"]/$limit_num);

    $arr_prepare = array("limit_num" => $limit_num
                        ,"offset" => $offset
                        );
    //삭제 안 된 테이블 내용
    $result_paging = select_board_info_paging($arr_prepare);
    // print_r($max_page_num);
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <title>게시판</title>
        <link rel="stylesheet" href="css/board_list.css">
    </head>
    <body>
        <div class="container">
            <h1>게시판</h1>
            <div class="sub-title">
                <h2>List</h2>
                    <span><a href="board_list.php"><img src="home.png" alt=""></a></span>
            </div>
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>게시글 번호</th>
                        <th>게시글 제목</th>
                        <th>작성일자</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result_paging as $record) {
                        ?>  
                    <tr>
                        <td><?php echo $record["board_no"]?></td>
                        <td><a href="board_detail.php?board_no=<?php echo $record["board_no"] ?>&page_num=<?php echo $page_num ?>"><?php echo $record["board_title"]?></a></td>
                        <td><?php echo $record["board_write_date"]?></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <div class="a_btn">
            <?php if($page_num <=1){ ?>
                <a href="board_list.php?page_num=1" class="btn-prev"></a>
            <?php }else{ ?>
                <a class="btn-prev" href="board_list.php?page_num=<?php echo $page_num-1 ?>"></a>
            <?php } ?>
        <?php for($i=$s_page_num;$i<=$e_page_num; $i++){ //max 페이지 넘버 기준으로 루프 ?>
            <a class="btn btn-outline-primary" href="board_list.php?page_num=<?php echo $i?>"><?php echo $i?></a>
        <?php } ?>
        <?php if($page_num >=$max_page_num){ ?>
                <a class="btn-next" href="board_list.php?page_num=<?php echo $max_page_num?>"></a>
            <?php }else{ ?>
                    <a class="btn-next" href="board_list.php?page_num=<?php echo $page_num+1?>"></a>
            <?php } ?>
        </div>
        </div>
       
    </body>
</html>
