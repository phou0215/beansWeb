<?php
    // require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    // mysqli_select_db($conn, $config['database']);


    try{

      $result = null;
      $datas = array();
      $demand_type = array(
        "B01" => "센터",
        "B02" => "학교"
      );
      // select all schedule
      $flag_comp = isset($_GET['comp']);
      $flag_dema = isset($_GET['dema']);
      $flag_supp = isset($_GET['supp']);
      $url = "http://localfood.chungnam.go.kr/localfood/openApi02.do?";
      $headers = [];
      $agent = 'User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",';
      $accept = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
      // $headers[] = $agent;
      $headers[] = $accept;

      if($flag_comp){
        $comp = $_GET['comp'];
        $url = $url.'&comp='.$comp;
      }
      if($flag_dema){
        $dema = $_GET['dema'];
        $url = $url.'&dema='.$dema;
      }
      if($flag_supp){
        $supp = $_GET['supp'];
        $url = $url.'&supp='.$supp;
      }
      //curl request init
      $ch = curl_init(); //curl 로딩
      curl_setopt($ch, CURLOPT_URL, $url); //curl에 url 셋팅
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음

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
      $temp_demand_type = array();
      $temp_supply_type = array();

      $cus_sc = 0;
      $cus_kn = 0;
      $cus_cc = 0;
      $cus_etc = 0;

      while($i < count($result)){

        //null check
        $cus_no = $result[$i]['BIZ_NO'];
        $cus_tel = $result[$i]['TEL_NO'];
        $cus_address = $result[$i]['ADDR'];
        $cus_homepage = $result[$i]['HOME_PAGE'];

        if(!$cus_no || trim($cus_no) == ""){
          $result[$i]['BIZ_NO'] = '정보없음';
        }
        if(!$cus_tel || trim($cus_tel) == ""){
          $result[$i]['TEL_NO'] = '정보없음';
        }
        if(!$cus_address || trim($cus_tel) == ""){
          $result[$i]['ADDR'] = '정보없음';
        }
        if(!$cus_homepage || trim($cus_no) == ""){
          $result[$i]['HOME_PAGE'] = "정보없음";
        }

        // array push for static graph
        $demand = $demand_type[$result[$i]['DEMA_TYPE']];
        $result[$i]['DEMA_TYPE'] = $demand;
        $supply = trim($result[$i]['CUST_KIND_NM']);

        if($demand){
          array_push($temp_demand_type, trim($demand));
        }
        if($supply){
          array_push($temp_supply_type, trim($supply));
        }

        // customer name sort static count
        $customer = trim($result[$i]['CUST_NM']);
        if(strpos($customer, "학교")){
          $cus_sc++;
        }
        else if(strpos($customer, "유치원")){
          $cus_kn++;
        }
        else if(strpos($customer, "어린이집")){
          $cus_cc++;
        }
        else{
          $cus_etc++;
        }

        $i++;
      }
      // sort suppliers and items by values DESC
      $dict_demand_type = array_count_values($temp_demand_type);
      $dict_supply_type = array_count_values($temp_supply_type);

      arsort($dict_demand_type);
      arsort($dict_supply_type);


      //key and value extract
      $index_demand = array_keys($dict_demand_type);
      $values_demand = array_values($dict_demand_type);
      $index_supply = array_keys($dict_supply_type);
      $values_supply = array_values($dict_supply_type);
      $index_custom = array("학교","유치원","어린이집","기타");
      $values_custom = array($cus_sc, $cus_kn, $cus_cc, $cus_etc);


      // send json format
      $return_data = array(
        'tables' => $result,
        'index_demand' => $index_demand,
        'index_supply' => $index_supply,
        'index_custom' => $index_custom,
        'values_demand' => $values_demand,
        'values_supply' => $values_supply,
        'values_custom' => $values_custom
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
