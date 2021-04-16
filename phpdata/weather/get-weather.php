<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();
    // select all schedule
    $flag_start = isset($_GET['start']);
    $flag_end = isset($_GET['end']);
    $flag_location = isset($_GET['location']);

    $sql = '';

    if($flag_start && $flag_end){
      $start_datetime = date('YmdHis', strtotime($_GET['start']));
      $end_datetime = date('YmdHis', strtotime($_GET['end']));

      //if location param is in query
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM weather WHERE LOCATION ="'.$location.'" AND BASE_DATETIME >= "'.$start_datetime.'"
        AND BASE_DATETIME <= "'.$end_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }
      else{
        $sql = 'SELECT * FROM weather WHERE LOCATION ="서울" AND BASE_DATETIME >= "'.$start_datetime.'"
        AND BASE_DATETIME <= "'.$end_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }

    }
    else if($flag_start && !$flag_end){
      $start_datetime = date('YmdHis', strtotime($_GET['start']));
      //if location param is in query
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM weather WHERE LOCATION ="'.$location.'" AND
        BASE_DATETIME >= "'.$start_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }
      else{
        $sql = 'SELECT * FROM weather WHERE LOCATION ="서울" AND
        BASE_DATETIME >= "'.$start_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }

    }
    else if(!$flag_start && $flag_end){
      $end_datetime = date('YmdHis', strtotime($_GET['end']));

      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM weather WHERE LOCATION ="'.$location.'" AND
        BASE_DATETIME <= "'.$end_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }
      else{
        $sql = 'SELECT * FROM weather WHERE LOCATION ="서울" AND
        BASE_DATETIME <= "'.$end_datetime.'" ORDER BY UPLOAD_TIME DESC';
      }

    }
    else{
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM weather WHERE LOCATION ="'.$location.'" ORDER BY UPLOAD_TIME DESC LIMIT 10';
      }
      else{
        $sql = 'SELECT * FROM weather WHERE LOCATION ="서울" ORDER BY UPLOAD_TIME DESC LIMIT 10';
      }
    }
    $result = mysqli_query($conn, $sql);

    try{
      while($row = mysqli_fetch_assoc($result)){

        $fcst_date = date('Y-m-d', strtotime($row['BASE_DATETIME']));
        // $fcst_date = $row['FCST_DATETIME']
        $location = $row['LOCATION'];
        $sql_rise = 'SELECT * FROM riseset WHERE LOCATION="'.$location.'" AND LOCDATE="'.$fcst_date.'"';
        $result_rise = mysqli_query($conn, $sql_rise);
        $row_rise = mysqli_fetch_assoc($result_rise);

        $data = array(
          'no' => $row['NO'],
          'upload_time' => $row['UPLOAD_TIME'],
          't1h' => $row['T1H'],
          'rn1' => $row['RN1'],
          'uuu' => $row['UUU'],
          'vvv' => $row['VVV'],
          'reh' => $row['REH'],
          'pty' => $row['PTY'],
          'vec' => $row['VEC'],
          'wsd' => $row['WSD'],
          'sunrise' =>$row_rise['SUNRISE'],
          'sunset' =>$row_rise['SUNSET'],
          'sun_transit' =>$row_rise['SUNTRANSIT'],
          'moonrise' =>$row_rise['MOONRISE'],
          'moonset' =>$row_rise['MOONSET'],
          'moontransit' =>$row_rise['MOONTRANSIT'],
          'nx' => $row['NX'],
          'ny' => $row['NY'],
          'state1' => $row['STATE1'],
          'state2' => $row['STATE2'],
          'state3' => $row['STATE3'],
          'location' => $row['LOCATION'],
          'base_datetime' => $row['BASE_DATETIME']
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
