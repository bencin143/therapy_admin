<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');


$or = $_POST['or'];  
$ipadd = $_POST['ipadd'];
$portadd = $_POST['portadd'];
$datusr = $_POST['datusr'];
$datpass = $_POST['datpass'];


if($conn){
	$query = "SELECT COUNT(*) AS count FROM HISConnectivity WHERE OrganisationName='$or'";
	
        
	
	$result = $conn->query($query);
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
                
		$count = (int)$row['count'];
               
		if($count > 0){
			echo "Tested OK";
		}
		else{
			echo "No data found with the given details.";
		}
	}
	else{
		echo "Oops! An error with the query.";
	}
}
else{
	echo "Failed to connect database";
}
/*
$result = mysqli_query('select OrganisationName,DataServerIPAdd,DatabaseUsername,DatabasePassword from HISConnectivity where values = "'. $or .','. $ipadd .','. $datusr .','. $datpass .'"');  
  

if(mysqli_num_rows($result)>0){  
    
    echo "tested ok";  
}else{  
   
  
    echo "error";  
} 
*/
 
?>