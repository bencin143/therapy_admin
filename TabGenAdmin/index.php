<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
	<title>Conttext Login</title>

	<link href="http://fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
       
	<link rel="stylesheet" type="text/css" href="css/main.css">
	
	<script src="js/jquery.min.js"></script>

	<script src="js/npm.js"></script>
	
	<script type="text/javascript" src="login.js"></script>
        <script type="text/JavaScript">
		isSessionAlive();
		
		function SubmitFrm(){   
			window.location = "home.php";
		}
		
		function isSessionAlive(){
			var js_session = sessionStorage.getItem('user_details');
			if(js_session!="null" && js_session!="" && js_session!=null){
				//alert("Session not null: "+js_session);
				window.location.assign("home.php");
			}
			else{
				$.ajax({
					url: "getUserSession.php",
					type: "GET",
					success:function(data){
						//alert(data);//displaying alert
						if(data.trim()!="null"){
							sessionStorage.setItem('user_details',data);
							window.location.assign('home.php');	
						}
					},
					error:function(error_data,y,z){
		
					}
				});
			}
		}
	</script>
        
	<script type="text/JavaScript">
		
		var login_email;
		var login_password;
		function validate(){
			var state=true;
			if(login_email.length==0){
				document.getElementById("emailErrorMsg").innerHTML="Please fill up your username or email";
				document.getElementById("emailErrorMsg").style.color="red";
				state=false;
			}else{
				document.getElementById("emailErrorMsg").innerHTML=" ";
			}
			if(login_password.length==0){
				document.getElementById("passwdErrorMsg").innerHTML="Please fill up your password";
				document.getElementById("passwdErrorMsg").style.color="red";
				state=false;
			}else{
				document.getElementById("passwdErrorMsg").innerHTML=" ";
			}
			return state;
		}
		function login(){	
			login_email = document.getElementById("uname").value;
			login_password = document.getElementById("password").value;	
			if(validate()==true){
				document.getElementById("login_response").innerHTML="<p align='center'><img src='img/loading.gif'/> Wait please...</P>";
				//document.forms.submit();
				$.ajax({
					type: "POST",
					url: "authenticate.php",
					data: "username="+login_email+"&password="+login_password,
					success: function(resp){
						//alert(resp);
						var jarr = jQuery.parseJSON(resp);		
						if(jarr.state=="true"){
							window.location.assign(""+jarr.location);
							sessionStorage.setItem('user_details', jarr.user_details);
						}
						else{
							document.getElementById("login_response").innerHTML=""+
							"<div class='isa_error' align='center'>"+jarr.message+"</div>";
						}
					},
					error: function(x,y,z){
						//alert(z);
						document.getElementById("login_response").innerHTML="<div class='isa_error' align='center'>Oops! An unknown problem occurs, try again later</P>";
					}
				});
			}
		}
	
		function redirect(url)
		{
			window.location.assign(url);
		}

	</script>
        <script type="text/javascript"> 
                $(document).ready( function() {
                    $('#login_response').delay(3000).fadeOut();
                });
        </script>
</head>
<body>
	
			
					<section class="container login-form">
				<section>
					<form name="forms" action="authenticate.php"  method="POST" class="form-signin"
							background="img/bg_screen_small.png">
						<h1 id="bb342">Therapy</h1>
			
						<div class="form-group">
                                                        <label for="inputEmail3"></label>
							<input type="text" name="username" id="uname" required class="form-control" placeholder="username or email" />
                                                        <center><span id="emailErrorMsg"></span></center>
						</div>
				
						<div class="form-group">
                                                        <label for="inputPassword3"></label>
							<input type="text" name="password" id="password" required class="form-control" placeholder="Password" />
                                                        <center><span id="passwdErrorMsg"></span></center>
							
						</div>
                                                <div class="form-group">
                                                        <button type="submit" onclick="login(); return false;" class="btn btn-lg btn-primary btn-block" >Log In</button>							
                                                        <br/>
                                                        <a href="#" class="text-right new-account">Need help signing in?</a>
                                                </div>
                                                <div id="loader-icon" style="display:none;"><img src="LoaderIcon.gif" /></div>
                                                <div class="response_body" id="login_response">
                                                    <?php 
                                                            if(!empty($_GET['status']))
                                                                {
                                                             echo '<div>You have successfully logged out</div>';
                                                                }
                                                    ?>
                                                </div>
				
			</form>
		</section>
	</section>
			
				
			
	
	
	<footer class="footer">
		 

      <div class="container">
        
         <span class="text-muted">© 2016 Conttext Messaging. All rights reserved.</span>
		 
		
		 
      </div>
	  
    </footer>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
	<script>
	<!--
	$( document ).ready(function() {
	    $('#tooltip').tooltip();
	});
	-->
	</script>

</body>
</html>