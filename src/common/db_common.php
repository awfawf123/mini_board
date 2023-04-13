<?php
function db_conn(&$param_conn){
    $host = "localhost";
    $user = "root";
    $pass = "root506";
    $charset = "utf8mb4";
    $db_name = "board";
    $dns = "mysql:host=".$host.";dbname=".$db_name.";charset=".$charset;
    $pdo_option = array(PDO::ATTR_EMULATE_PREPARES => false
                        ,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                        );

    try{
        $param_conn = new PDO($dns,$user,$pass,$pdo_option);
    }catch(Exception $e){
        $param_conn = null;
        throw new Exception($e->getMessage());
    }
}

function select_board_info_paging(&$param_arr){
    $sql=" SELECT board_no
        ,board_title
        ,board_write_date
        FROM board_info
        WHERE board_del_flg = '0'
        ORDER BY board_no desc
        LIMIT :limit_num OFFSET :offset";

    $arr_prepare = array(":limit_num" => $param_arr["limit_num"]
                        ,":offset" => $param_arr["offset"]
                        );

    $conn = null;
    try{
        db_conn($conn);
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_prepare);
        $result = $stmt->fetchAll();
    }catch(Exception $e){
        return false;
    }finally{
        $conn = null;
    }                
  
    return $result;
}

function select_board_info_count(){
    $sql = " SELECT count(*) cnt
             FROM board_info
             WHERE board_del_flg = '0' ";

    $arr_prepare = array();
    
    try{
        db_conn($conn);
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_prepare);
        $result = $stmt->fetchAll();
    }catch(Exception $e){
        return false;
    }finally{
        $conn = null;
    }  

    return $result;
}
//----------------------------------
//함수명 : select_board_info_no()
//기능 : 게시판 특정 게시글 정보 검색
//파라미터값 : INT &$param_no
//리턴값 : Array $result
//----------------------------------
function select_board_info_no(&$param_no){
    $sql=" SELECT board_no
                ,board_title
                ,board_contents
                ,board_write_date 
            FROM board_info
            WHERE board_no = :board_no ";//0412 board_write_date 작성일 추가

    $arr_prepare = array(":board_no" => $param_no);

    $conn = null;

    try{
        db_conn($conn);
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_prepare);
        $result = $stmt->fetchAll();
    }catch(Exception $e){
        return $e->getMessage();
    }finally{
        $conn = null;
    }                
  
    return $result[0];
}
//----------------------------------
//함수명 : update_board_info_no()
//기능 : 게시판 특정 게시글 정보 수정
//파라미터값 : Array &$param_arr
//리턴값 : INT/STRING $result_cnt/ERRMSG
//----------------------------------
function update_board_info_no(&$param_arr){
    $sql=" UPDATE board_info
           SET board_title = :board_title
               ,board_contents = :board_contents
           WHERE board_no =:board_no
           ";

    $arr_prepare = array(":board_title" => $param_arr["board_title"]
                         ,":board_contents" => $param_arr["board_contents"]
                         ,":board_no" => $param_arr["board_no"]      
                        );

    $conn = null;

    try{
        db_conn($conn);
        $conn->beginTransaction();  
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_prepare);
        $result_cnt = $stmt->rowCount(); // update한 행갯수 카운트
        $conn->commit();
    }catch(Exception $e){
        //실패 할 경우 롤백
        $conn->rollback();
        return $e->getMessage();
    }finally{
        $conn = null;
    }                
    return $result_cnt;
}
//----------------------------------
//함수명 : insert_board_info()
//기능 : 게시글 생성
//파라미터값 : 
//리턴값 : 
//----------------------------------
function insert_board_info(&$param_arr){
    $sql=" INSERT INTO board_info(
           board_title
           ,board_contents 
           ,board_write_date
           )
           VALUES(
           :board_title
           ,:board_contents
           ,now()) ";

    $arr_prepare = array(":board_title" => $param_arr["board_title"]
                         ,":board_contents" => $param_arr["board_contents"]
                        );

    $conn = null;

    try{
        db_conn($conn);
        $conn->beginTransaction();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute($arr_prepare);
        $conn->commit();
    }catch(Exception $e){
        //실패 할 경우 롤백
        $conn->rollback();
        return $e->getMessage();
    }finally{
        $conn = null;
    }                
    return $result;
}

//----------------------------------
//함수명 : delete_board_info_no()
//기능 : 게시판 특정 게시글 정보 삭제
//파라미터값 : INT &$param_no
//리턴값 : INT/STRING $result_cnt/ERRMSG
//----------------------------------
function delete_board_info_no(&$param_no){
    $sql=" UPDATE board_info
           SET board_del_flg = '1'
               ,board_del_date = now()
           WHERE board_no =:board_no";

    $arr_prepare = array(":board_no" => $param_no);

    $conn = null;

    try{
        db_conn($conn);
        $conn->beginTransaction();  
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_prepare);
        $result = $stmt->rowCount();
        $conn->commit();
    }catch(Exception $e){
        //실패 할 경우 롤백
        $conn->rollback();
        return $e->getMessage();
    }finally{
        $conn = null;
    }                
    return $result;
}

// TODO : test Start
// $arr = array("limit_num" => 5
//             ,"offset"    => 0
//             );
// $result = select_board_info_paging($arr);
// print_r($result);
// $i=1;
// print_r(select_board_info_no($i));

// $arr=array("board_no"=> 1
//             ,"board_title"=> "test1"
//             ,"board_contents"=> "testeste1");
// echo update_board_info_no($arr);
// TODO : test End

