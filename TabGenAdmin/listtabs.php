<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connect_db.php');
//include('tabgen_php_functions.php');

function listTabs($notTab){
    try{
    //echo "success";
    $query="select Id,Name from Tab where Id != '$notTab'";
    //echo $query;
    if($conn){
     $conn=query($query);
    print_r("successmessage");
    //while($row = $res->fetch(PDO::FETCH_ASSOC)){
      //  print_r($row['Name']);
    //}
    }
 else {
        print_r("no connection".$conn);    
    }
    
    }
 catch (Exception $e){
        print_r("error occured");
 }
}
listTabs('01b1f01916ba8a6a53f101b135');
?>