<?php //Team id: xx9cqu5dkffbj8ixhxw5j64dww 
/* 
{
 "team_id":"aypj6bea1jboxn3f9cd8q8juch",
 "email": "test@nowhere.com",
 "username":"prashant",
 "password":"123456",
 "name": "betty",
 "type": "O"
}
*/
?>
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
$type=1;

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
					"password" => "hello",
					"name" => "ABMH",
					"roles"=>"Nero",
					"first_name"=>$one
				);
                            
				$url_send ="http://".IP.":8065/api/v1/users/create";
				$str_data = json_encode($data);
				
				$connect = new ConnectAPI();
				$result = $connect->sendPostData($url_send,$str_data);
                                print_r($result);
                                //die();
/*
				if($result!=null){
					try{
						$responseData = json_decode($result);
						if($connect->httpResponseCode==200){
							$role=$_POST['Role'];
							$role_id=$_POST['role_id'];	
							userUniversalAccess($conn,$responseData->id,$type);
							$ou_id = findOUId($conn,$org_unit_name);
							
							if(updateUserRoleAndDisplayName($responseData->id,$conn,$role,$user_displayname)){
								if(mapUserwithOU($conn,$responseData->id,$ou_id,$role_id,$type)){
									echo "true";
print_r("Test2");
								}
								else{
									echo "Internal Server error: Unable to map User with OU, 
											please contact the system administrator";
print_r("Test3");
								}
							}
							else{
								echo "false";
print_r("Test4");
							}
						}else if($connect->httpResponseCode==0){
							echo "Unable to communicate with the API";
print_r("Test5");
						}
						else 
							echo $responseData->message;
					}catch(Exception $e){
						echo "Exception: ".$e->getMessage();
print_r("Test6");
					}
				}
				else 
					echo "Oops! There may be a problem at the server. Try again later.";
print_r("Test7");
		}
	*/
	
	
                
}                
}



function validateUserDetails(){
	if(empty($_POST['username'])){
		echo "Username is blank";
		return false;
	}
	else if($_POST['password']!=$_POST['conf_pwd']){
		echo "Password does not match Confirm Password";
		return false;
	}
	else if(empty($_POST['email'])){
		echo "Email is blank";
		return false;
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		echo "Please enter a valid email.";
		return false;
	}
	else if(empty($_POST['org_unit'])){
		echo "Select an Organisation Unit";
		return false;
	}
	else if(empty($_POST['Role'])){
		echo "Select a role";
		return false;
	}
	else if(empty($_POST['team_id'])){
		echo "Team id not available.";
		return false;
	}
	else 
		return true;	
}

?>
