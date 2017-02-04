
<?php 
include('ConnectAPI.php');
include('connect_db.php');
include('tabgen_php_functions.php');

//bulk user creation
if($_GET['file']!=null ||$_GET['file']!=''){
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Europe/London');
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
$type =0;
$pieces[1]="  ";
$objPHPExcel = PHPExcel_IOFactory::load($_GET['file']);
$objWorksheet = $objPHPExcel->getActiveSheet();

$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

for ($row = 2; $row <= $highestRow; ++$row) {

   /*structure of the xls sheet is important*/
/*
 *col3: name
 *col4: Role
 *col5: username
 *col6: password
 *col7: email
 *col8: TeamId
*/
/*
echo "<br>" . $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
echo "  :" . $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
echo "  :" . $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
echo "  :" . $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
echo "  :" . $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
echo "  :" . $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
*/
/*
$team_id=$objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
$email=$objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
$username=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
$password=$objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
$name=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();;
$roles=$objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
$first_name=$objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
*/
echo "test111";

//need to call the api to create each user account
for ($row = 2; $row <= $highestRow; ++$row) {
  echo '<tr>' . "\n";


    $one= $objWorksheet->getCellByColumnAndRow(5, $row)->getValue() ;
    $two= $objWorksheet->getCellByColumnAndRow(6, $row)->getValue() ;
    $three= $objWorksheet->getCellByColumnAndRow(7, $row)->getValue() ;
    $four= $objWorksheet->getCellByColumnAndRow(8, $row)->getValue() ;
 $five= $objWorksheet->getCellByColumnAndRow(2, $row)->getValue() ;
 $six= $objWorksheet->getCellByColumnAndRow(4, $row)->getValue() ;

echo "<br>".$one." : ".$two." : ".$three."  :  ".$four;


}
		if($conn){
			
				$data = array(
				   "team_id" => $four,
					"email" => $three,
					"username" =>$one , 
					"password" => $two,
					"name" => $five,
					"roles"=>$six
					
				);
				
				$url_send ="http://".IP.":8065/api/v1/users/create";
				$str_data = json_encode($data);
				
				$connect = new ConnectAPI();
				$result = $connect->sendPostData($url_send,$str_data);
				if($result!=null){
					}

				}



//$str='PROF.DR.MD. SHOHRAB HOSSAIN SOURAV, MBBS, MS (UROLOGY)';
//$str1=substr(strrchr($str, '.'), 1 );
//$str2=strstr($str1,' ',true);
//$str2=substr($str1,0,strpos($str1,',') );
//$splitter=" ";
//$pieces = explode(" ", $str1);

//echo "\nfirst name : ".$pieces[1];
//echo "\nSubstring 1: ".$str1;
//echo "Substring 2:".$str2;
?>

