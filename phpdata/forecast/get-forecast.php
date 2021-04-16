<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();

    $flag_start = isset($_GET['start']);
    $flag_end = isset($_GET['end']);
    $flag_location = isset($_GET['location']);

    $sql = '';
    if($flag_start && $flag_end){
      $start_datetime = date('YmdHis', strtotime($_GET['start']));
      $end_datetime = date('YmdHis', strtotime($_GET['end']));

      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="'.$location.'" AND FCST_DATETIME >= "'.$start_datetime.'" AND
        FCST_DATETIME <="'.$end_datetime.'" ORDER BY  UPLOAD_TIME DESC, FCST_DATETIME';
        /*
        //SUb query select
        //$sql = 'SELECT *
        // FROM (SELECT * FROM forecast WHERE LOCATION ="'.$location.'" AND FCST_DATE >= "'.$start_date.'" AND FCST_TIME >="'.$start_time.'" ) AS sub
        // WHERE sub.FCST_DATE <= "'.$end_date.'" AND sub.FCST_TIME <= "'.$end_time.'"
        // ORDER BY FCST_DATE, FCST_TIME';
        */
      }
      else{
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="서울" AND FCST_DATETIME >= "'.$start_datetime.'" AND
        FCST_DATETIME <="'.$end_datetime.'" ORDER BY UPLOAD_TIME DESC, FCST_DATETIME';
      }
    }

    else if($flag_start && !$flag_end){
      $start_datetime = date('YmdHis', strtotime($_GET['start']));

      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="'.$location.'" AND
        FCST_DATETIME >= "'.$start_datetime.'" ORDER BY UPLOAD_TIME DESC, FCST_DATETIME';
      }
      else{
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="서울" AND
        FCST_DATETIME >= "'.$start_datetime.'" ORDER BY UPLOAD_TIME DESC, FCST_DATETIME';
      }
    }
    else if(!$flag_start && $flag_end){
      $end_datetime = date('YmdHis', strtotime($_GET['end_date']));

      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM forecast WHERE LOCATION="'.$location.'" AND
        FCST_DATETIME <= "'.$end_datetime.'" ORDER BY FCST_DATETIME';
      }
      else{
        $sql = 'SELECT * FROM forecast WHERE LOCATION="서울" AND
        FCST_DATETIME <= "'.$end_datetime.'" ORDER BY FCST_DATETIME';
      }
    }
    else{
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="'.$location.'" ORDER BY UPLOAD_TIME DESC, FCST_DATETIME LIMIT 10';
      }
      else{
        $sql = 'SELECT * FROM forecast WHERE LOCATION ="서울" ORDER BY UPLOAD_TIME DESC, FCST_DATETIME LIMIT 10';
      }
    }
    $result = mysqli_query($conn, $sql);
    try{
      while($row = mysqli_fetch_assoc($result)){

        $fcst_date = date('Y-m-d', strtotime($row['FCST_DATETIME']));
        // $fcst_date = $row['FCST_DATETIME']
        $location = $row['LOCATION'];
        $sql_rise = 'SELECT * FROM riseset WHERE LOCATION="'.$location.'" AND LOCDATE="'.$fcst_date.'"';
        $result_rise = mysqli_query($conn, $sql_rise);
        $row_rise = mysqli_fetch_assoc($result_rise);
        $data = array(
          'no' => $row['NO'],
          'upload_time' => $row['UPLOAD_TIME'],
          'pop' => $row['POP'],
          'pty' => $row['PTY'],
          'r06' => $row['R06'],
          'reh' => $row['REH'],
          's06' => $row['S06'],
          'sky' => $row['SKY'],
          't3h' => $row['T3H'],
          'tmn' => $row['TMN'],
          'tmx' => $row['TMX'],
          'uuu' => $row['UUU'],
          'vvv' => $row['VVV'],
          'wav' => $row['WAV'],
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
          'base_datetime' => $row['BASE_DATETIME'],
          'fcst_datetime' => $row['FCST_DATETIME']
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
