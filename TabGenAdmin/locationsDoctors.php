
<?php
//input -: no input
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
//$token = get_token_from_header();
//if($token==null){
//	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
//}
	
	if($conn){
		$query = "select  distinct LocId,Location,CenterId, Centername from LocAndDoctors";
		$res = $conn->query($query);
		$output=null;
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode(array("status"=>true,"result"=>$output));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Unable to get result, something is wrong."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Failed to connect database."));
	}

?>
