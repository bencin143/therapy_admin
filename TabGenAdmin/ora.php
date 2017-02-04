<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');


$or = $_POST['ort'];  




$boxes = $_POST['boxess'];
 $id = $_POST['selected_id'];
$each_val = implode(',', $boxes);



	
        

// Create connection

      
       
$server   = $_POST['ipaddt'];
$port = $_POST['portaddt'];
$username = $_POST['datusrt'];
$password = $_POST['datpassst'];
$sernamee = $_POST['sernamee'];

$tns = " (DESCRIPTION =
(ADDRESS_LIST =
(ADDRESS = (PROTOCOL = TCP)(HOST = ".$server.")(PORT = ".$port."))
)
(CONNECT_DATA =
(SERVICE_NAME = ".$sernamee.")
)
)";

// Attempt to connect to your database.
$c = @oci_connect($username, $password, $tns);
if (!$c) {
print "Sorry! The connection to the database failed. Please try again later.";
die();
}
else {
print "Congrats! You've connected to an Oracle database!";
oci_close($c);
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
