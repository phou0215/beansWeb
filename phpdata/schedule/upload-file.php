<?php

  require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/phpoffice/vendor/autoload.php");
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
  mysqli_select_db($conn, $config['database']);

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
  use PhpOffice\PhpSpreadsheet\Reader\Xls;
  use PhpOffice\PhpSpreadsheet\Reader\Csv;

  try{
    $reader = null;
    $allowed_ext = array("xls", "xlsx", "csv");
    $input_tmpname = $_FILES['file_path']['tmp_name'];
    $input_filename = $_FILES['file_path']['name'];
    $input_ext = pathinfo($input_filename, PATHINFO_EXTENSION);

    if (in_array($input_ext, $allowed_ext)){

      //    확장자에 따른 설정 구분
      //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xml();
      //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
      //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Slk();
      //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Gnumeric();
      //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
      //xls file extension
      if ($input_ext =='xls') {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();
      }
      //xlsx file extension
      else if ($input_ext =='xlsx') {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      }
      //csv file extension
      else{
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
      }
      // uploading file save on $spreadsheet
      $sheetObj = $reader->load($input_tmpname);
      $sheetData = $sheetObj-> getActiveSheet()-> toArray();
      // $sheetData = $spreadsheet-> getSheet(0)-> toArray();
      // $rows = $spreadsheet->getHighestRow();
      // $columnString = $spreadsheet->getHighestColumn();
      // $columns = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnString);
      $rows = count($sheetData);
      $columns = (count($sheetData,1)/count($sheetData))-1;
      $sql = 'INSERT INTO events (EVENT_TYPE, EVENT_NAME, START_DATE, END_DATE, EVENT_ALLDAY, EVENT_COLOR, EVENT_DESCRIPTION) VALUES ';
      $values = '';
      $row_form = '("%1$s","%2$s","%3$s","%4$s","%5$s","%6$s","%7$s")';

      for($r=1; $r < $rows; $r++){
        $row_array =array();
        $event_type = trim($sheetData[$r][0]);
        $event_name = trim($sheetData[$r][1]);
        if($event_type && $event_name){
          //get all column record by one row
          for($c=0; $c < $columns; $c++){
            $record = trim($sheetData[$r][$c]);
            array_push($row_array, $record);
          }
        }
        else{
          continue;
        }
        // when $r is last row number
        $values = $values.vsprintf($row_form, $row_array).",";
      }
      $values = substr($values, 0, -1);
      // insert excel shcedule data to DB 'events'
      // ("'.$type.'", "'.$title.'", "'.$start.'", "'.$end.'", '.$allDay.', "'.$color.'", "'.$desc.'")'
      $result = mysqli_query($conn, $sql.$values);
      echo '<script>
      alert("스케쥴 등록이 완료되었습니다.");
      document.location.href="/beans/schedule/main.php";
      </script>';
    // case of unsupported extension
    }else{
      echo '<script>
      alert("선택한 파일의 확장자를 지원하지 않습니다.");
      document.location.href="/beans/schedule/main.php";
      </script>';
    }

  }
  catch(Exception $e){
    echo $e->getMessage();
    echo '</br>';
  }
  finally{
      //close DB connection
      mysqli_close($conn);
  }



?>
