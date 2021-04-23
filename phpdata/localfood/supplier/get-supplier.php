<?php
    // require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    // mysqli_select_db($conn, $config['database']);


    try{

      $result = null;
      // select all schedule
      $flag_comp = isset($_GET['comp']);
      $url = "http://localfood.chungnam.go.kr/localfood/openApi03.do?";
      $headers = [];
      $agent = 'User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",';
      $accept = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
      // $headers[] = $agent;
      $headers[] = $accept;

      if($flag_comp){
        $comp = $_GET['comp'];
        $url = $url.'&comp='.$comp;
      }
      //curl request init
      $ch = curl_init(); //curl 로딩
      curl_setopt($ch, CURLOPT_URL, $url); //curl에 url 셋팅
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 1 고정

      //set headers(Server포워드 대상 서버에서 연결을 거부할 수 있음으로 SET Header를 진행.)
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      // curl_setopt ($ch, CURLOPT_COOKIEJAR, $file);
      curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
      curl_setopt($ch, CURLOPT_AUTOREFERER, true);

      //list 값만 가져오기
      $result = curl_exec($ch); // curl 실행 및 결과값 저장
      $result = json_decode($result, true);
      $result = $result['list'];

      // get supplier and items static Counts
      $i = 0;
      while($i < count($result)){

        //null check
        $supp_tel = $result[$i]['TEL_NO'];
        $supp_address = $result[$i]['ADDR'];

        if(!$supp_tel || trim($supp_tel) == ""){
          $result[$i]['TEL_NO'] = '정보없음';
        }
        if(!$supp_address || trim($supp_address) == ""){
          $result[$i]['ADDR'] = '정보없음';
        }
        $i++;
      }

      // send json format
      $return_data = array(
        'tables' => $result,
      );

      $jsonTable = json_encode($return_data, JSON_UNESCAPED_UNICODE);
      header('Content-Type: application/json');
      echo $jsonTable;

    }catch(Exception $e){
      echo $e->getMessage();
      echo '</br>';
    }finally{
      // close connect DB
      curl_close ($ch); // curl 종료
    }
?>
