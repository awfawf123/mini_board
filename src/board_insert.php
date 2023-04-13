<?php
define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
define("URL_DB",DOC_ROOT."src/common/db_common.php");
define("URL_HEADER",DOC_ROOT."src/board_header.php");
include_once(URL_DB);

$http_method = $_SERVER["REQUEST_METHOD"];

if($http_method === "POST"){
    $arr_post = $_POST;
    insert_board_info($arr_post);
    header("Location: board_list.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 작성</title>
    <link rel="stylesheet" href="css/board_insert.css">
</head>
<body>
    <div class="container">
        <?php include_once(URL_HEADER)?>
        <form method="post" action="board_insert.php">
            <div class="inner">
                <ul>
                    <li>
                        <p>제목</p>
                        <input type="text" name="board_title">
                    </li>
                    <li>
                        <p>내용</p> 
                        <input type="textarea" name="board_contents">
                    </li>
                </ul>
                <div class="btn-group">
                    <button class="w-btn" type="submit">저장</a></button>
                    <button class="w-btn w-btn-blue"><a href="board_list.php">취소</a></button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>