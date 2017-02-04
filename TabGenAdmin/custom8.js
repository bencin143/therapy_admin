$( document ).ready(function() {
  $("#database_connection").click(function(){
      $("#his_connectivity_respp").html("<center>Wait Please...</center>");
var org = $("#ort").val();
var add = $("#ipaddt").val();
var por = $("#portaddt").val();
var user = $("#datusrt").val();
var pass = $("#datpassst").val();
var quer = $("#box2").val();
var links = document.getElementsByTagName('boxess[]');

var values = [];
$('.abcd').each(function(){
    values.push($(this).val());
});


//alert("Hello World!");
var dataString = 'ort='+ org + '&ipaddt='+ add + '&portaddt='+ por + '&datusrt='+ user + '&datpassst='+ pass + '&boxess[]='+ values;
//alert(dataString);
if(org==''||add==''||user==''||pass=='')
{
$("#his_connectivity_respp").html("<center>Please Fill All Fields</center>");
}
else
{

$.ajax({
type: "POST",
url: "hiso.php",
data: dataString,

success: function(conn){
    $("#his_connectivity_respp").html("<b>"+conn+"</b>");

}
});
}
return false;
});
});
