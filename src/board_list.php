<?php
    define("DOC_ROOT",$_SERVER["DOCUMENT_ROOT"]."/");
    define("URL_DB",DOC_ROOT."src/common/db_common.php");
    define("URL_HEADER",DOC_ROOT."src/board_header.php");
    include_once(URL_DB);
    $arr_get = $_GET;
    //최초 페이지 열때 페이지 넘버
    if(array_key_exists("page_num",$arr_get)){
        $page_num = $arr_get["page_num"];
    }else{
        $page_num = 1; //key가 없으면 1로 셋팅
    }
    var_dump($page_num);
    //--------------start 페이징 처리-----------------
    $limit_num = 5; //한 페이지당 n개행만 보여줌
    $page_block = 3; //한 페이지에 보여줄 페이지 갯수
    $now_page = ceil($page_num/$page_block); //현재 페이지 블럭수
    $s_page_num = ($now_page-1) * $page_block+1;
    //데이터가 없을 경우 1로
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
    //--------------end페이징 처리-----------------
    //--------------start검색------------------------
    //검색한게 있는지 없는지 확인
    $flg_search = array_key_exists("board_title",$arr_get) ? true : false; //검색했을때 true 아니면 false
    //삭제 안 된 테이블 내용
    if($flg_search){ // true일시 실행
        $arr_search_txt = array("board_title" => $arr_get["board_title"]
                                ,"limit_num" => $limit_num
                                ,"offset" => $offset
                                );
        $result_search = select_board_info_search($arr_search_txt);
    }else{ // false 일시 실행
        $arr_prepare = array("limit_num" => $limit_num
                        ,"offset" => $offset
                        );
        $result_paging = select_board_info_paging($arr_prepare);
    }
    //--------------end검색------------------------
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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    </head>
    <body>
        <div class="container">
            <?php include_once(URL_HEADER)?>
            <!-- <h1>게시판</h1> -->
            <div class="sub-title">
                <button class="w-btn"><a href="board_insert.php">게시글 작성</a></button>
                    <form method="get" action="board_list.php">
                    <select>
                        <option value="title">제목</option>
                    </select>
                        <input type="text" name="board_title" placeholder="검색">
                        <button type="submit">검색</button>
                    </form>
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
                    <?php if($flg_search){ ?>
                        <?php foreach ($result_search as $val) { ?> 
                            <tr>
                                <td><?php echo $val["board_no"]?></td>
                                <td><?php echo $val["board_title"]?></td>
                                <td><?php echo $val["board_write_date"]?></td>
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <?php foreach ($result_paging as $record) { ?> 
                        <tr>
                            <td><?php echo $record["board_no"]?></td>
                            <td><a href="board_detail.php?board_no=<?php echo $record["board_no"] ?>"><?php echo $record["board_title"]?></a></td>
                            <td><?php echo $record["board_write_date"]?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
            </tbody>
        </table>
        <div class="a_btn">
            <!-- 처음 페이지로 -->
            <a class="first-page" href="board_list.php?page_num=<?php echo 1?>">
                <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
            </a>
             <!-- //처음 페이지로 -->
            <!-- 이전 -->
            <?php if($page_num <=1){ ?>
                <a href="board_list.php?page_num=1"></a>
            <?php }else{ ?>
                <a class="btn-prev" href="board_list.php?page_num=<?php echo $page_num-1 ?>">
                    <span class="material-symbols-outlined">arrow_back_ios </span>
                </a>
            <?php } ?>
            <!-- //이전 -->
        <?php for($i=$s_page_num;$i<=$e_page_num; $i++){ 
             if($i == $page_num){  ?>
                <a class="btn btn-outline-primary"><?php echo $i?></a>
            <?php }else{ ?>
                <a class="btn btn-outline-primary" href="board_list.php?page_num=<?php echo $i?>"><?php echo $i?></a>
        <?php } 
        }?>
            <!-- 다음 -->
        <?php if($page_num >=$max_page_num){ ?>
                <!-- 마지막 페이지일시 Next 버튼 삭제-->
            <?php }else{ ?>
                    <a class="btn-next" href="board_list.php?page_num=<?php echo $page_num+1?>">
                        <span class="material-symbols-outlined">arrow_forward_ios</span>
                    </a>
            <?php } ?>
             <!-- //다음 -->
             <!-- 마지막페이지로 -->
             <a class="last-page" href="board_list.php?page_num=<?php echo $max_page_num?>">
                 <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
             </a>
             <!-- //마지막페이지로 -->
        </div>
        </div>
       
    </body>
</html>
