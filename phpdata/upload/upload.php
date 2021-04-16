<?php

if(isset($_POST['submit'])) {
     if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
        $allowedExtensions = array("xls","xlsx");
        $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);

        if(in_array($ext, $allowedExtensions)) {
				// Uploaded file
               $file = "uploads/".$_FILES['uploadFile']['name'];
               $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
			   // check uploaded file
               if($isUploaded) {
					// Include PHPExcel files and database configuration file
                    include("db.php");
					require_once __DIR__ . '/vendor/autoload.php';
                    include(__DIR__ .'/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php');
                    try {
                        // load uploaded file
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                    } catch (Exception $e) {
                         die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
                    }

                    // Specify the excel sheet index
                    $sheet = $objPHPExcel->getSheet(0);
                    $total_rows = $sheet->getHighestRow();
					$highestColumn      = $sheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

					//	loop over the rows
					for ($row = 1; $row <= $total_rows; ++ $row) {
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							$cell = $sheet->getCellByColumnAndRow($col, $row);
							$val = $cell->getValue();
							$records[$row][$col] = $val;
						}
					}
					$html="<table border='1'>";
					$html.="<tr><th>Roll No</th>";
					$html.="<th>Name</th><th>Age</th>";
					$html.="<th>Program</th></tr>";
					foreach($records as $row){
						// HTML content to render on webpage
						$html.="<tr>";
						$rollno = isset($row[0]) ? $row[0] : '';
						$name = isset($row[1]) ? $row[1] : '';
						$age = isset($row[2]) ? $row[2] : '';
						$program = isset($row[3]) ? $row[3] : '';
						$html.="<td>".$rollno."</td>";
						$html.="<td>".$name."</td>";
						$html.="<td>".$age."</td>";
						$html.="<td>".$program."</td>";
						$html.="</tr>";
						// Insert into database
						$query = "INSERT INTO participants (rollno,name,age,program)
								values('".$rollno."','".$name."','".$age."','".$program."')";
						$mysqli->query($query);
					}
					$html.="</table>";
					echo $html;
					echo "<br/>Data inserted in Database";

                    unlink($file);
                } else {
                    echo '<span class="msg">File not uploaded!</span>';
                }
        } else {
            echo '<span class="msg">Please upload excel sheet.</span>';
        }
    } else {
        echo '<span class="msg">Please upload excel file.</span>';
    }
}
?>
