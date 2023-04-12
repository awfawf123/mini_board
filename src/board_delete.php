<?php
define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
define("URL_DB",DOC_ROOT."src/common/db_common.php");
include_once(URL_DB);

$arr_get = $_GET;

$result = delete_board_info_no($arr_get["board_no"]);
header("Location: board_list.php");
//redirect 했기 때문에 이후의 소스코드는 실행할 필요 없음
exit();
?>