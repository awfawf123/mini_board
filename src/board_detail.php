<?php
define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
define("URL_DB",DOC_ROOT."src/common/db_common.php");
include_once(URL_DB);

$arr_get = $_GET;
//게시글 정보
$result_info = select_board_info_no($arr_get["board_no"]);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <link rel="stylesheet" href="css/board_detail.css">
</head>
<body>
    <div class="container">
        <h1 class="title">내용</h1>
        <div class="sub-title">
            <h2>게시글 작성</h2>
                <span><a href="board_list.php"><img src="home.png" alt=""></a></span>
        </div>
        <div class="inner">
            <p class="bo_no">글 번호 : <?php echo $result_info["board_no"]?></p>
            <span>작성일: <?php echo $result_info["board_write_date"]?></span>
            <ul>
                <li><p>제목</p><?php echo $result_info["board_title"]?></li>
                <li><p>내용</p><?php echo $result_info["board_contents"]?></li>
            </ul>
            <div class="btn-group">
                <button class="w-btn" type="button"><a href="board_update.php?board_no=<?php echo $result_info["board_no"]?>">수정</a></button>
                <button class="w-btn "type="button"><a href="board_delete.php?board_no=<?php echo $result_info["board_no"]?>">삭제</a></button>
            </div>
        </div>
    </div>
</body>
</html>