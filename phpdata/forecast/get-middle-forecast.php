<?php
    // require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    // mysqli_select_db($conn, $config['database']);
       date_default_timezone_set("Asia/Seoul");
       $wfSv = array(
         //서울, 인천, 경기도
         "sk"=>["109"],
         //강원도
         "kg"=>["105"],
         //충청북도 / 대전, 세종, 충청남도
         "ch"=>["131", "133"],
         //전라북도 / 광주, 전라남도
         "jl"=>["146", "156"],
         // 대구, 경상북도 / 부산, 울산, 경상남도
         "ks"=>["143", "159"],
         // 제주도
         "je"=>["184"]
       );
       $land = array(
         //서울, 인천, 경기도
         "sk"=>["11B00000"],
         // 강원도영서 / 강원도영동
         "kg"=>["11D10000", "11D20000"],
         // 충청북도 / 대전, 세종, 충청남도
         "ch"=>["11C10000", "11C20000"],
         // 전라북도 / 광주, 전라남도
         "jl"=>["11F10000", "11F20000"],
         // 대구, 경상북도 / 부산, 울산, 경상남도
         "ks"=>["11H10000", "11H20000"],
         // 제주도
         "je"=>["11H20000"]
       );
       $temp = array(
         //서울/인천/수원/파주/고양/가평
         "sk"=>["11B10101", "11B20201", "11B20601", "11B20305", "11B20302", "11B20404"],
         //춘천/원주/강릉/대관령/철원/삼척
         "kg"=>["11D10301", "11D10401", "11D20501", "11D20201", "11D10101", "11D20602"],
         //대전/서산/세종/청주/태안/단양
         "ch"=>["11C20401", "11C20101", "11C20404", "11C10301", "11C20102", "11C10202"],
         //광주/목포/여수/전주/군산/해남
         "jl"=>["11F20501", "21F20801", "11F20401", "11F10201","21F10501", "11F20302"],
         //부산/울산/창원/대구/안동/포항
         "ks"=>["11H20201", "11H20101", "11H20301", "11H10701", "11H10501", "11H10201"],
         //제주/서귀포/성산/이어도/추자도/고산
         "je"=>["11G00201", "11G00401", "11G00101", "11G00601", "11G00800", "11G00501"]
       );
       $rain_regular = "/rnSt[0-9]{1,2}[AP]?m?/";
       $sky_regular = "/wf[0-9]{1,2}[AP]?m?/";
       $min_regular = "/taMin[0-9]{1,2}$/";
       $max_regular = "/taMax[0-9]{1,2}$/";

    try {
        $location = "ch";
        $result_wfSv = array();
        $result_land = array();
        $result_temp = array();
        $index_land = array();
        $index_temp = array();

        $serviceKey = 'XZ9qC5Af8SKGO4LUvW0VcbAKNyFXzxBu7Hqe51EQnPoYEn9SQLR4tkK5cBbOJGEoidSQS72CYW1KBWSVLqRHHg%3D%3D';
        $base_time = "";
        $flag_location = isset($_GET['loc']);

        $current = strtotime(date('YmdHis'));
        $beforeBase = strtotime(date('Ymd060000'));
        $afterBase = strtotime(date('Ymd180000'));

        //set base time
        if ($current <= $beforeBase) {
            $base_time = date("YmdHi", strtotime($afterBase." -1 days"));
        } elseif ($current > $beforeBase && $current <= $afterBase) {
            $base_time = date('Ymd0600');
        } else {
            $base_time = date('Ymd1800');
        }

        //set location
        if ($flag_location) {
            $location = $_GET['loc'];
        }

        //generate table column names
        $i = 0;
        while($i < 8){
          $P_day = 3+$i;
          $index_day = date('m-d', strtotime($base_time."+ ".$P_day."days"));

          if(!in_array($i, [5,6,7])){
            array_push($index_land, $index_day."일(오전)");
            array_push($index_land, $index_day."일(오후)");
          }else{
            array_push($index_land, $index_day."일");
          }
          array_push($index_temp, $index_day."일");
          $i++;
        }

        $wfSv_url = "http://apis.data.go.kr/1360000/MidFcstInfoService/getMidFcst?serviceKey=".$serviceKey."&pageNo=1&numOfRows=10&dataType=JSON&tmFc=".$base_time."&stnId=";
        $land_url = "http://apis.data.go.kr/1360000/MidFcstInfoService/getMidLandFcst?serviceKey=".$serviceKey."&pageNo=1&numOfRows=10&dataType=JSON&tmFc=".$base_time."&regId=";
        $temp_url = "http://apis.data.go.kr/1360000/MidFcstInfoService/getMidTa?serviceKey=".$serviceKey."&pageNo=1&numOfRows=10&dataType=JSON&tmFc=".$base_time."&regId=";
        $headers = [];
        $agent = 'User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",';
        $accept = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        // $headers[] = $agent;
        $headers[] = $accept;

        //wfSv data extracting
        //curl request init
        $i = 0;
        while($i < count($wfSv[$location])){

          $url = $wfSv_url.$wfSv[$location][$i];
          $ch = curl_init(); //curl 로딩
          curl_setopt($ch, CURLOPT_URL, $url); //curl에 url 셋팅
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정

          //set headers(Server포워드 대상 서버에서 연결을 거부할 수 있음으로 SET Header를 진행.)
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_USERAGENT, $agent);
          // curl_setopt ($ch, CURLOPT_COOKIEJAR, $file);
          curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
          curl_setopt($ch, CURLOPT_AUTOREFERER, true);
          $result = curl_exec($ch); // curl 실행 및 결과값 저장

          $result = json_decode($result, true);
          $response_code = $result['response']['header']['resultCode'];

          if ($response_code == '00'){
            $result = $result['response']['body']['items']['item'];
            $result = $result[0]['wfSv'];
            array_push($result_wfSv, $result);
          }
          else{
            array_push($result_wfSv, "No Data");
          }
          $i++;
        }

        //land data extracting
        //curl request init
        $i = 0;
        while($i < count($land[$location])){

          $url = $land_url.$land[$location][$i];
          $ch = curl_init(); //curl 로딩
          curl_setopt($ch, CURLOPT_URL, $url); //curl에 url 셋팅
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정

          //set headers(Server포워드 대상 서버에서 연결을 거부할 수 있음으로 SET Header를 진행.)
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_USERAGENT, $agent);
          // curl_setopt ($ch, CURLOPT_COOKIEJAR, $file);
          curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
          curl_setopt($ch, CURLOPT_AUTOREFERER, true);
          $result = curl_exec($ch); // curl 실행 및 결과값 저장

          $result = json_decode($result, true);
          $response_code = $result['response']['header']['resultCode'];

          if ($response_code == '00'){
            $result = $result['response']['body']['items']['item'][0];
            array_shift($result);
            $keys = array_keys($result);
            $values = array_values($result);
            $rain = array();
            $sky = array();
            // item  rain and sky 자르기
            $j =0;
            while($j < count($keys)){
              if (preg_match($rain_regular, $keys[$j])){
                array_push($rain, $values[$j]);
              }
              if (preg_match($sky_regular, $keys[$j])){
                array_push($sky, $values[$j]);
              }
              $j++;
            }
            array_push($result_land, array("rain"=> $rain, "sky" => $sky));
          }
          else{
            array_push($result_land, "No Data");
          }
          $i++;
        }


        //Temp data extracting
        //curl request init
        $i = 0;
        while($i < count($temp[$location])){

          $url = $temp_url.$temp[$location][$i];
          $ch = curl_init(); //curl 로딩
          curl_setopt($ch, CURLOPT_URL, $url); //curl에 url 셋팅
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정

          //set headers(Server포워드 대상 서버에서 연결을 거부할 수 있음으로 SET Header를 진행.)
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_USERAGENT, $agent);
          // curl_setopt ($ch, CURLOPT_COOKIEJAR, $file);
          curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
          curl_setopt($ch, CURLOPT_AUTOREFERER, true);
          $result = curl_exec($ch); // curl 실행 및 결과값 저장

          $result = json_decode($result, true);
          $response_code = $result['response']['header']['resultCode'];

          if ($response_code == '00'){
            $result = $result['response']['body']['items']['item'][0];
            array_shift($result);
            $keys = array_keys($result);
            $values = array_values($result);
            $max = array();
            $min = array();
            // print_r($result);
            // print_r($keys);
            // print_r($values);
            // item  rain and sky 자르기
            $j =0;
            while($j < count($keys)){
              if (preg_match($max_regular, $keys[$j])){
                array_push($max, $values[$j]);
              }
              if (preg_match($min_regular, $keys[$j])){
                array_push($min, $values[$j]);
              }
              $j++;
            }
            array_push($result_temp, array("max"=> $max, "min" => $min));
          }
          else{
            array_push($result_temp, "No Data");
          }
          $i++;
        }

        //assemble list data
        $return_data = array(
        'base_time' => date('Y-m-d H:i', strtotime($base_time)),
        'wfSv' => $result_wfSv,
        'land' => $result_land,
        'temp' => $result_temp,
        'index_land' => $index_land,
        'index_temp' => $index_temp,
        );

        $jsonTable = json_encode($return_data, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $jsonTable;
    } catch (Exception $e) {
        echo $e->getMessage();
        echo '</br>';
    } finally {
        // close connect DB
      curl_close($ch); // curl 종료
    }
