<?php
    define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
    define("URL_DB",DOC_ROOT."src/common/db_common.php");
    include_once(URL_DB);
    // var_dump($_SERVER,$_GET,$_POST);
    $http_method = $_SERVER["REQUEST_METHOD"];
    // print_r($http_method);
    // print_r($page_num);
    if(array_key_exists("page_num",$_GET)){
        $page_num = $_GET["page_num"];
    }else{
        $page_num = 1; //key가 없으면 1로 셋팅
    }
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
        $result_info = select_board_info_no($arr_post["board_no"]);
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <style>
        a{
            text-decoration: none;
            color: #2e2e2e;
        }
        button {
            margin: 15px;
        }       
        .w-btn {
            position: relative;
            border: none;
            display: inline-block;
            padding: 15px 30px;
            border-radius: 15px;
            border:3px solid #6aafe6;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-weight: 600;
            transition: 0.25s;
        }
        .w-btn:hover {
            letter-spacing: 1px;
            transform: scale(1.2);
            cursor: pointer;
        }
        .w-btn:active {
            transform: scale(1.5);
        }
        
    </style>
</head>
<body>
    <form method="post" action="board_update.php">
        <label for="bno">게시글 번호 :</label>
        <input type="text" name="board_no" id="bno" value="<?php echo $result_info['board_no']?>" readonly>
        <br>
        <label for="title">게시글 제목 :</label>
        <input type="text" name="board_title" id="title" value="<?php echo $result_info['board_title']?>">
        <br>
        <label for="contents">게시글 내용 :</label>
        <input type="text" name="board_contents" id="contents" value="<?php echo $result_info['board_contents']?>">
        <br>
        <button class="w-btn w-btn-blue" type="submit">수정</button>
        <button class="w-btn w-btn-blue"><a href="board_list.php?page_num=<?php echo $page_num?>">LIST</a></button>
    </form>
</body>
</html>