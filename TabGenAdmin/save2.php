<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');

$connect = mysqli_connect("139.162.61.60", "mmuser", "mostest", "mattermost_test");  


$id = $_POST['selected_id'];



            $sql = "select * from HISConnectivity where Id='$id'";  


           
    
            $result = mysqli_query($connect, $sql);  
            $row = mysqli_fetch_array($result); 
           
           
              echo json_encode($row);
                

 ?>