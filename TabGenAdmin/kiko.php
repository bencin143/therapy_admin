<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');


$or = $_POST['ort'];  
$ipadd = $_POST['ipaddt'];

$portadd = $_POST['portaddt'];
$datusr = $_POST['datusrt'];
$datpass = $_POST['datpassst'];
$boxes = $_POST['boxess'];
 $id = $_POST['selected_id'];
$each_val = implode(',', $boxes);

if($conn){
	$time = time()*1000;
        $id = randId(26);
//creating unique id, this function is coded inside tabgen_php_functions.php file
	
        
        $query="UPDATE HISConnectivity SET CreateAt='$time',UpdateAt='$time',DeleteAt='0',DataServerIPAdd='$ipadd',DataBasePortAddress='$portadd',DatabaseUsername='$datusr',DatabasePassword='$datpass',Queries='$each_val' WHERE Id='$or'";
        
        
        $result = $conn->query($query);
        
	if($result){
			echo "Updated successfully";
		}
		else{
			echo "error";
		}
	}
	else{
		echo "Oops! An error with the query.";
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