<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $start = isset($_POST['start']) ? $_POST['start'] : '';
    $end = isset($_POST['end']) ? $_POST['end'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
    $color = isset($_POST['color']) ? $_POST['color'] : '';
    $allDay = isset($_POST['allDay']) ? $_POST['allDay'] : '';

    try{
        if($allDay == 'true'){
          $allDay = 1;
          if($start != ''){
            $start = date('Y-m-d', strtotime($start));
          }
          if($end != ''){
            $end = date('Y-m-d', strtotime($end));
          }
        }else{
          $allDay = 0;
          if($start != ''){
            $start = date('Y-m-d H:i:s', strtotime($start));
          }
          if($end != ''){
            $end = date('Y-m-d H:i:s', strtotime($end));
          }
        }
        //ADD EVENT
        $sql = 'INSERT INTO events (EVENT_TYPE, EVENT_NAME, START_DATE, END_DATE, EVENT_ALLDAY, EVENT_COLOR, EVENT_DESCRIPTION) VALUES ("'.$type.'", "'.$title.'", "'.$start.'", "'.$end.'", '.$allDay.', "'.$color.'", "'.$desc.'")';
        $result = mysqli_query($conn, $sql);
        $data = array(
          'rcode' => '20000',
          'rmessage' => 'success'
        );
        $jsonTable = json_encode($data, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $jsonTable;
    }catch(Exception $e){
      echo $e->getMessage();
      echo '</br>';
    }finally{
      //close DB connection
      mysqli_close($conn);
    }

?>
