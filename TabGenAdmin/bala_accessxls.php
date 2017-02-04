<?php
error_reporting(E_ALL);
include('ConnectAPI.php');
include('connect_db.php');
include('tabgen_php_functions.php');
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//echo $_POST['name'];
//die();
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
//$objPHPExcel = PHPExcel_IOFactory::load("sample_bulkusercreate.xls");
//$activeSheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

//echo $activeSheetData['A'];

//var_dump($activeSheetData);
//require_once '/Classes/PHPExcel.php';
//require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
//$objReader = PHPExcel_IOFactory::createReader('Excel2007');
//$objReader->setReadDataOnly(true);
$objPHPExcel = PHPExcel_IOFactory::load("Gulshan Dr names and Specialities.xls");
$objWorksheet = $objPHPExcel->getActiveSheet();

$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);


for ($row = 2; $row <= $highestRow; ++$row) {
  echo '<tr>' . "\n";


    $one= $objWorksheet->getCellByColumnAndRow(5, $row)->getValue() ;
    $two= $objWorksheet->getCellByColumnAndRow(6, $row)->getValue() ;
    $three= $objWorksheet->getCellByColumnAndRow(7, $row)->getValue() ;
    $four= $objWorksheet->getCellByColumnAndRow(8, $row)->getValue() ;

if($conn){
	echo "connection successful";
	$data = array("team_id" => $four,
					"email" => $three,
					"username" => $one, 
					"password" => $two,
					"name" => "dsds",
					"roles"=>"Consultant",
					"first_name"=>$one
				);
$url_send ="http://localhost:8065/api/v1/users/create";
$str_data = json_encode($data);
$connect = new ConnectAPI();
$result = $connect->sendPostData($url_send,$str_data);
echo "result :yes";
}
else{
echo "no connection established";
}
echo "<br>".$one." : ".$two." : ".$three."  :  ".$four;


}




//echo $highestColumnIndex;
//echo $objWorksheet->getCellByColumnAndRow($highestColumnIndex-1, $highestRow)->getValue();
echo "hello";

?>
