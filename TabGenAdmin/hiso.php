<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');


$or = $_POST['ort'];  




$boxes = $_POST['boxess'];
 $id = $_POST['selected_id'];
$each_val = implode(',', $boxes);



	
        

// Create connection

      
       
$server   = $_POST['ipaddt'];
//$port = $_POST['portaddt'];
$username = $_POST['datusrt'];
$password = $_POST['datpassst'];

$conn = mysqli_connect($server, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else {
    echo "Connection Successful";
}
mysqli_close($conn);

        
        
        
	

/*
$result = mysqli_query('select OrganisationName,DataServerIPAdd,DatabaseUsername,DatabasePassword from HISConnectivity where values = "'. $or .','. $ipadd .','. $datusr .','. $datpass .'"');  
  

if(mysqli_num_rows($result)>0){  
    
    echo "tested ok";  
}else{  
   
  
    echo "error";  
} 
*/
 ?>
