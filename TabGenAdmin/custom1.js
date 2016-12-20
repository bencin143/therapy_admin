$( document ).ready(function() {
  $("#save_data").click(function(){
      $("#his_connectivity_resp").html("<center>Wait Please...</center>");
var org = $("#choose_org_for_hisconnectivity").val();
var add = $("#ipadd").val();
var por = $("#portadd").val();
var user = $("#datusr").val();
var pass = $("#datpass").val();
var quer = $("#box1").val();
var links = document.getElementsByTagName('boxes[]');

var values = [];
$('.abc').each(function(){
    values.push($(this).val());
});


//alert("Hello World!");
var dataString = 'or='+ org + '&ipadd='+ add + '&portadd='+ por + '&datusr='+ user + '&datpass='+ pass + '&boxes[]='+ values;
//alert(dataString);
if(org==''||add==''||por==''||user==''||pass=='')
{
$("#his_connectivity_resp").html("<center>Please Fill All Fields</center>");
}
else
{

$.ajax({
type: "POST",
url: "save.php",
data: dataString,

success: function(result){
    $("#his_connectivity_resp").html("<b>"+result+"</b>");

}
});
}
return false;
});
});
