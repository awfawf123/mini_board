<?php
    define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
    define("URL_DB",DOC_ROOT."src/common/db_common.php");
    define("URL_HEADER",DOC_ROOT."src/board_header.php");
    include_once(URL_DB);
    // var_dump($_SERVER,$_GET,$_POST);
    //GET방식인지 POST방식인지
    $http_method = $_SERVER["REQUEST_METHOD"];
    // print_r($http_method);
    // print_r($page_num);
    
    //GET 체크
    if($http_method  === "GET"){
        $board_no = 1;
        if(array_key_exists("board_no",$_GET)){
            $board_no = $_GET["board_no"];
        }else{
            $board_no = 1; //key가 없으면 1로 셋팅
        }
        $result_info = select_board_info_no($board_no);
    }else{
        //POST 일때
        $arr_post = $_POST;
        $arr_info = array("board_no" => $arr_post["board_no"]
                        ,"board_title" => $arr_post["board_title"]
                        ,"board_contents" => $arr_post["board_contents"]
                        );
        //update
        $result_cnt = update_board_info_no($arr_info);
        //update후 다시 select
        //  $result_info = select_board_info_no($arr_post["board_no"]); //0412 delete

        header("Location: board_detail.php?board_no=".$arr_post["board_no"]);
        //redirect 했기 때문에 이후의 소스코드는 실행할 필요 없음
        exit();
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" href="css/board_update.css">
</head>
<body>
    <div class="container">
    <?php include_once(URL_HEADER)?>
    <form method="post" action="board_update.php">
        <div class="inner">
                <p class="bo_no">글 번호 : <?php echo $result_info["board_no"]?></p>
                <span>작성일: <?php echo $result_info["board_write_date"]?></span>
                <ul>
                    <li>
                        <p>제목</p>
                        <input type="text" name="board_title" id="title" value="<?php echo $result_info['board_title']?>">
                    </li>
                    <li>
                        <p>내용</p> 
                        <input type="textarea" name="board_contents" id="contents" value="<?php echo $result_info['board_contents']?>">
                    </li>
                        <input type="hidden" name="board_no"  value="<?php echo $result_info['board_no']?>">
                </ul>
                <div class="btn-group">
                    <button class="w-btn" type="submit">저장</a></button>
                    <button class="w-btn"><a href="board_detail.php?board_no=<?php echo $result_info["board_no"]?>">취소</a></button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>