<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $result = null;
    $datas = array();

    $flag_base = isset($_GET['base']);
    $flag_location = isset($_GET['location']);
    $scalar_sub = '
    (SELECT SUNRISE FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS SUNRISE,
    (SELECT SUNSET FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS SUNSET,
    (SELECT SUNTRANSIT FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS SUNTRANSIT,
    (SELECT MOONRISE FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS MOONRISE,
    (SELECT MOONSET FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS MOONSET,
    (SELECT MOONTRANSIT FROM riseset WHERE LOCATION="%1$s" AND LOCDATE="%2$s") AS MOONTRANSIT';

    // $matches = ["123", "987", "121"];
    // $result = 'My phone number is %1$s and police number is %3$s';
    // $res = vsprintf($result, $matches);


    $sql = '';
    if($flag_base){
      $base_datetime = date('YmdHis', strtotime($_GET['base']));
      $rise_date = date('Y-m-d', strtotime($_GET['base']));

      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT sh.*,'.vsprintf($scalar_sub, [$location, $rise_date]).'
        FROM shortcast AS sh
        WHERE sh.LOCATION ="'.$location.'" AND sh.BASE_DATETIME = "'.$base_datetime.'"
        ORDER BY sh.FCST_DATETIME';
      }
      else{
        $sql = 'SELECT sh.*,'.vsprintf($scalar_sub, ['서울', $rise_date]).'
        FROM shortcast AS sh
        WHERE sh.LOCATION ="서울" AND sh.BASE_DATETIME = "'.$base_datetime.'"
        ORDER BY sh.FCST_DATETIME';
      }
    }
    else{
      $rise_date = date('Y-m-d');
      if($flag_location){
        $location = $_GET['location'];
        $sql = 'SELECT sh.*,'.vsprintf($scalar_sub, [$location, $rise_date]).'
        FROM shortcast AS sh WHERE LOCATION ="'.$location.'"
        ORDER BY UPLOAD_TIME DESC, FCST_DATETIME LIMIT 1';
      }
      else{
        $sql = 'SELECT sh.*,'.vsprintf($scalar_sub, ['서울', $rise_date]).'
        FROM shortcast AS sh WHERE LOCATION ="서울"
        ORDER BY UPLOAD_TIME DESC, FCST_DATETIME LIMIT 1';
      }
    }
    $result = mysqli_query($conn, $sql);

    try{
      while($row = mysqli_fetch_assoc($result)){

        $data = array(
          'no' => $row['NO'],
          'upload_time' => $row['UPLOAD_TIME'],
          't1h' => $row['T1H'],
          'rn1' => $row['RN1'],
          'sky' => $row['SKY'],
          'uuu' => $row['UUU'],
          'vvv' => $row['VVV'],
          'reh' => $row['REH'],
          'pty' => $row['PTY'],
          'lgt' => $row['LGT'],
          'vec' => $row['VEC'],
          'wsd' => $row['WSD'],
          'sunrise' =>$row['SUNRISE'],
          'sunset' =>$row['SUNSET'],
          'sun_transit' =>$row['SUNTRANSIT'],
          'moonrise' =>$row['MOONRISE'],
          'moonset' =>$row['MOONSET'],
          'moontransit' =>$row['MOONTRANSIT'],
          'nx' => $row['NX'],
          'ny' => $row['NY'],
          'state1' => $row['STATE1'],
          'state2' => $row['STATE2'],
          'state3' => $row['STATE3'],
          'location' =>$row['LOCATION'],
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
