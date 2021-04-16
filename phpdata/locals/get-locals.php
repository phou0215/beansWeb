<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();
    // select all schedule
    $flag_used = isset($_GET['used']);
    $sql = '';

    if($flag_used){
      if($flag_used == '1'){
        $sql = 'SELECT * FROM locals WHERE FALG=1 ORDER BY LOCAL ASC';
      }else{
        $sql = 'SELECT * FROM locals WHERE FALG=0 ORDER BY LOCAL ASC';
      }
    }else{
      $sql = 'SELECT * FROM locals ORDER BY LOCAL ASC';
    }
    $result = mysqli_query($conn, $sql);

    try{
      while($row = mysqli_fetch_assoc($result)){

        $data = array(

          'id' => $row['ID'],
          'state1' => $row['STATE1'],
          'state2' => $row['STATE2'],
          'state3' => $row['STATE3'],
          'local' => $row['LOCAL'],
          'flag' => $row['FLAG'],
        );
        array_push($datas, $data);
      }

      //send json format
      $jsonTable = json_encode($datas, JSON_UNESCAPED_UNICODE);
      header('Content-Type: application/json');
      echo $jsonTable;
    }catch(Exception $e){
      echo $e->getMessage();
      echo '</br>';
    }finally{
      // close connect DB
      mysqli_free_result($result);
      mysqli_close($conn);
    }
?>
