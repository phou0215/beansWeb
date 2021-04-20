<?php
    // require_once($_SERVER['DOCUMENT_ROOT'].'/config/config_beans.php');
    // 에러 리포트로 상용 시 주석처리해야 함
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    // mysqli_select_db($conn, $config['database']);


    try{

      $result = null;
      $index_centers = null;
      $index_items = null;
      $values_centers = null;
      $values_items = null;
      $values_category = null;
      $datas = array();

      // select all schedule
      $flag_sdate = isset($_GET['sDate']);
      $flag_edate = isset($_GET['eDate']);
      $flag_comp = isset($_GET['comp']);
      $flag_item = isset($_GET['itemcls']);
      $url = "http://localfood.chungnam.go.kr/localfood/openApi01.do?";
      $headers = [];
      $agent = 'User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",';
      $accept = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
      // $headers[] = $agent;
      $headers[] = $accept;

      if($flag_sdate && $flag_edate){
        $sDate = $_GET['sDate'];
        $eDate = $_GET['eDate'];
        $url = $url.'&sdate='.$sDate.'&edate='.$eDate;
      }
      if($flag_comp){
        $comp = $_GET['comp'];
        $url = $url.'&comp='.$comp;
      }
      if($flag_item){
        $item = $_GET['itemcls'];
        $url = $url.'&itemcls='.$item;
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
      $temp_suppliers = array();
      $temp_items = array();
      $temp_categories = array();
      //regular express of items
      $regular = "/([\(\/].*)/";
      while($i < count($result)){

        $supplier = $result[$i]['PU_CUST_NM'];
        $item = $result[$i]['ITEMCLS3_NM'];
        $category = $result[$i]['ITEMCLS2_NM'];
        //substr item special character
        $filter_item =  preg_replace($regular,"",str_replace("\\\\\\","",$item));

        if($supplier){
          array_push($temp_suppliers, trim($supplier));
        }
        if($filter_item){
          array_push($temp_items, trim($filter_item));
        }
        if($category){
          array_push($temp_categories, trim($category));
        }
        $i++;
      }
      // sort suppliers and items by values DESC
      $dict_suppliers = array_count_values($temp_suppliers);
      $dict_items = array_count_values($temp_items);
      $dict_categories = array_count_values($temp_categories);
      arsort($dict_suppliers);
      arsort($dict_items);
      arsort($dict_categories);

      $i = 0;
      $array_categories = array();
      $keys_categories = array_keys($dict_categories);
      $values_categories = array_values($dict_categories);
      // set Category table datas
      while($i < count($keys_categories)){
        $temp = array(
          'class' => $keys_categories[$i],
          'count' => $values_categories[$i]
        );
        array_push($array_categories, $temp);
        $i++;
      }

      //key and value extract
      $index_centers = array_keys($dict_suppliers);
      $values_centers = array_values($dict_suppliers);
      $index_items = array_keys($dict_items);
      $values_items = array_values($dict_items);
      //Top 20 slice array
      $index_centers = array_slice($index_centers, 0, 30);
      $index_items = array_slice($index_items, 0, 30);
      $values_centers = array_slice($values_centers, 0, 30);
      $values_items = array_slice($values_items, 0, 30);

      // send json format
      $return_data = array(
        'tables' => $result,
        'index_centers' => $index_centers,
        'index_items' => $index_items,
        'values_centers' => $values_centers,
        'values_items' => $values_items,
        'categories' => $array_categories
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
