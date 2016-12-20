function data_Showw()

{
    
    var a=$("#ort").val();
    //alert(a);
    $.ajax({
            url:"http://139.162.61.60/TabGenAdmin/save2.php",
            data:{selected_id:a},
     dataType: "json",
            type:'POST',
           success:function(result){
               
  $("#ipaddt").val(result.DataBasePortAddress);
  //alert(result.DataBasePortAddress);
   $("#portaddt").val(result.DataBasePortAddress);
//alert(result.DataServerIPAdd);
 $("#datusrt").val(result.DatabaseUsername);
//alert(result.DatabaseUsername);
 $("#datpassst").val(result.DatabasePassword);
//alert(result.DatabasePassword);
 
//alert(result.Queries);
 $("#box2").val(result.Queries);

            }
        });
}