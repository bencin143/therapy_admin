$(document).ready(function() {
  $("#test_connection").click(function(){
		$("#his_connectivity_resp").html("<center>Wait Please...</center>");
		var org = $("#choose_org_for_hisconnectivity").val();
		var add = $("#ipadd").val();
                var por = $("#portadd").val();
		var user = $("#datusr").val();
		var pass = $("#datpass").val();
              
		//alert("Hello World!");
		
		var dataString = 'or='+ org + '&ipadd='+ por + '&portadd='+ add + '&datusr='+ user + '&datpass='+ pass;
		//alert(dataString);
		if(org==''||add==''||por==''||user==''||pass=='')
		{
			$("#his_connectivity_resp").html("<center>Please Fill All Fields</center>");
		}
		else
		{
			$.ajax({
				type: "POST",
				url: "limor.php",
				data: dataString,
				success: function(result){
					$("#his_connectivity_resp").html("<b>"+result+"</b>");
				}
			});
		}
		return false;
	});
});