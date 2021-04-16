<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    //find out request method ==> $_SERVER['REQUEST_METHOD']
    parse_str(file_get_contents("php://input"), $_PUT);

    $id = $_PUT['id'];
    $title = $_PUT['title'];
    $type = $_PUT['type'];
    $start = $_PUT['start'];
    $end = $_PUT['end'];
    $desc = $_PUT['desc'];
    $color = $_PUT['color'];
    $allDay = $_PUT['allDay'];

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

      //UPDATE EVENT
      $sql = 'UPDATE events SET EVENT_TYPE="'.$type.'",EVENT_NAME="'.$title.'",START_DATE="'.$start.'",END_DATE="'.$end.'",EVENT_ALLDAY='.$allDay.',EVENT_COLOR="'.$color.'",EVENT_DESCRIPTION="'.$desc.'" WHERE EVENT_ID="'.$id.'"';
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
      // close connect DB;
      mysqli_close($conn);
    }
?>
