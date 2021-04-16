<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    //find out request method ==> $_SERVER['REQUEST_METHOD']
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    try{
      //DELTE EVENT
      $sql = 'DELETE FROM events WHERE EVENT_ID="'.$id.'"';
      $result = mysqli_query($conn, $sql);
      $data = array(
        "rcode" => "20000",
        "rmessage" => "success"
      );
      $jsonTable = json_encode($data, JSON_UNESCAPED_UNICODE);
      header('Content-Type: application/json');
      echo $jsonTable;
    }catch(Exception $e){
      echo $e->getMessage();
      echo '</br>';
    }finally{
      //disconnect DB server
      mysqli_close($conn);
    }
?>
