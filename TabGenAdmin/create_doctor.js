$( document ).ready(function() {
  $("#create_doctor").click(function(){
      $("#create_doctor_resp").html("<center>Wait Please...</center>");
      var emp = $("#emp").val();
var org = $("#select_org_for_rolee").val();
var ou = $("#select_ou_4_rolee").val();
var dept = $("#dept").val();
var doc = $("#doctor").val();





//alert("Hello World!");
var dataString = 'emp='+ emp + '&select_org_for_rolee='+ org + '&select_ou_4_rolee='+ ou + '&dept='+ dept + '&doctor='+ doc;
//alert(dataString);
if(emp==''||dept==''||doc=='')
{
$("#create_doctor_resp").html("<center>Please Fill All Fields</center>");
}
else
{

$.ajax({
type: "POST",
url: "create_doctor.php",
data: dataString,

success: function(result){
    $("#create_doctor_resp").html("<b>"+result+"</b>");

}
});
}
return false;
});
});
