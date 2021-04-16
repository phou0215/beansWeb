<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();

    $flag_date = isset($_GET['base_date']);
    $flag_location = isset($_GET['location']);

    $sql = '';
    //query에 base date가 있는 경우
    if($flag_date){
      $base_date = date('Y-m-d', strtotime($_GET['base_date']));
      //query에 location 이 없는 경우 default로 서울 지역 정보 출력
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM riseset WHERE LOCATION ="'.$location.'" AND LOCDATE="'.$base_date.'"';
      }
      else{
        $sql = 'SELECT * FROM riseset WHERE LOCATION ="서울" AND LOCDATE="'.$base_date.'"';
      }
    // query에 base date가 없는 경우
    }
    else{
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT * FROM riseset WHERE LOCATION ="'.$location.'" ORDER BY UPLOAD_TIME DESC, LOCDATE LIMIT 10';
      }
      else{
        $sql = 'SELECT * FROM riseset WHERE LOCATION ="서울" ORDER BY UPLOAD_TIME DESC, LOCDATE LIMIT 10';
      }
    }
    $result = mysqli_query($conn, $sql);

    try{
      while($row = mysqli_fetch_assoc($result)){

        $data = array(
          'id' => $row['ID'],
          'upload_time' => $row['UPLOAD_TIME'],
          'loc_date' => $row['LOCDATE'],
          'location' => $row['LOCATION'],
          'long' => $row['LONGITUDE'],
          'long_num' => $row['LONGITUDE_NUM'],
          'lat' => $row['LATITUDE'],
          'lat_num' => $row['LATITUDE_NUM'],
          'sunrise' => $row['SUNRISE'],
          'suntransit' => $row['SUNTRANSIT'],
          'sunset' => $row['SUNSET'],
          'moonrise' => $row['MOONRISE'],
          'moontransit' => $row['MOONTRANSIT'],
          'moonset' => $row['MOONSET'],
          'civilm' => $row['CIVILM'],
          'civile' => $row['CIVILE'],
          'nautm' => $row['NAUTM'],
          'naute' => $row['NAUTE'],
          'astm' => $row['ASTM'],
          'aste' => $row['ASTE']
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
