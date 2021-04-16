<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();
    // select all schedule
    $flag = isset($_GET['start']);
    $sql = '';
    if($flag){
      $start = date('Y-m-d', strtotime($_GET['start']));
      $end = date('Y-m-d', strtotime($_GET['end']));
      // $sql = 'SELECT * FROM events WHERE START_DATE >="'.$start.'" AND END_DATE <="'.$end.'" ORDER BY EVENT_ID';
      $sql = 'SELECT * FROM events WHERE END_DATE BETWEEN "'.$start.'" AND "'.$end.'" ORDER BY EVENT_ID';
    }else{
      $sql = 'SELECT * FROM events ORDER BY EVENT_ID';
    }

    $result = mysqli_query($conn, $sql);

    try{
      while($row = mysqli_fetch_assoc($result)){
        $allDay =  true;
        if($row['EVENT_ALLDAY'] != 1){
          $allDay = false;
        }
        $data = array(
          'id' => $row['EVENT_ID'],
          'title' => $row['EVENT_NAME'],
          'start' => $row['START_DATE'],
          'end' => $row['END_DATE'],
          'type' => $row['EVENT_TYPE'],
          'desc' => $row['EVENT_DESCRIPTION'],
          'color' => $row['EVENT_COLOR'],
          'allDay' => $allDay
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
