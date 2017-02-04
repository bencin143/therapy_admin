<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');

$emp = $_POST['emp'];
$org = $_POST['select_org_for_rolee'];  
$ou = $_POST['select_ou_4_rolee'];

$dept = $_POST['dept'];
$doctor = $_POST['doctor'];



	
	
        
	


        
        
        
        
        if($conn){
	$query = "SELECT COUNT(*) AS count FROM LocAndDoctors WHERE emp_id='$emp'";
	
        
	
	$result = $conn->query($query);
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
                
		$count = (int)$row['count'];
               
		if($count > 0){
			echo "already exists";
		}
		else{
			if($conn){
	
	$query = "insert into LocAndDoctors(emp_id,LocId,Location,CenterId,CenterName,Dept_name,DoctorName)
	values('$emp','$org','$org','$ou','$ou','$dept','$doctor')";
        
        $result = $conn->query($query);
       
	if($result){
			echo "Inserted successfully";
		}
		else{
			echo "error";
		}
	}
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