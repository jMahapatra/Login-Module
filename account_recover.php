<?php
include 'dbConfig.php';
require 'functions.inc.php';

session_start();
//echo password_encrypt('12345');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Recover Account</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
	/* Paste this css to your style sheet file or under head tag */
	/* This only works with JavaScript, 
	if it's not present, don't show loader */
	.no-js #loader { display: none;  }
	.se-pre-con {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        overflow: visible;
        background: url(assets/dist/image/Preloader_2.gif) center center no-repeat #333;
    }

    .login-box, .register-box {
        width: 360px;
        margin: 7% auto;
    }
    .social-auth-links {
        margin: 10px 0;
    }
    .btn.btn-flat {
    border-radius: 0;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    border-width: 1px;
    }
    .btn-block + .btn-block {
        margin-top: 5px;
    }
    .btn-google {
        color: #fff;
        background-color: #dd4b39;
        border-color: rgba(0,0,0,0.2);
    }
    .btn-google:hover {
        color: #fff;
        background-color: #b13627;
        border-color: rgba(0,0,0,0.2);
    }
    .btn-facebook {
        color: #fff;
        background-color: #3b5998;
        border-color: rgba(0,0,0,0.2);
    }
    .btn-facebook:hover {
        color: #fff;
        background-color: #233966;
        border-color: rgba(0,0,0,0.2);
    }
    .btn-social {
        position: relative;
        padding-left: 44px;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn {
        border-radius: 3px;
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid transparent;
    }
</style>

</head>
<body class="hold-transition login-page">
<div class="se-pre-con"></div>

<div class="login-box">
  <div class="login-logo">
    <h3>Account Recovery</h3>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" id="login-block">

    <form class="" id="form-recover-pass" name="form-recover-pass" method="post">
		<div class="form-group has-feedback">
			<!--<div id="divMobile">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-mobile"></i></span>
					<input class="form-control" id="TF_RegisteredMobile" value="" name="TF_RegisteredMobile" placeholder="Your registered mobile no." type="text">
				</div>
				<div style="display:none" id="alertMobile"></div>
			</div>-->
			<div id="divEmail">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input class="form-control" id="TF_RegisteredEmail" value="" name="TF_RegisteredEmail" placeholder="Your registered email id" type="email">
				</div>
				<div style="display:none" id="alertEmail"></div>
			</div>
		</div>
		<div class="form-group" style="display:none" id="divResponse"></div>
		<div class="form-group has-feedback">
			<input type="hidden" id="action" name="action" value="recoveraccount"/>
			<input type="button" onclick="verifyEmail()" class="btn btn-primary btn-block btn-flat" name="BTN_SaveChanges" id="BTN_SaveChanges" value="Recover"/>
		</div>
    </form>

    
	<div class="social-auth-links text-center">
		<a href="login.php">Back To Login</a><br>
	</div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
function verifyEmail(){
	$('#BTN_SaveChanges').val('Please Wait. . .').addClass('disabled')
	var email = $.trim($('#TF_RegisteredEmail').val()) 
	if(ValidateEmail($('#TF_RegisteredEmail').val()))
	{
		$('#divResponse').css('display','none')
		dataCont = 'action=checkEmail&email='+email
		$.post('accountUpdates.php', dataCont, function(html){
			if(html.trim()==='exist'){
				recoverAccount()
			}else{
				$('#divResponse').show()
				$('#divResponse').html('<span class="text-danger" style="font-size:11px; font-weight:700px"><i class="fa fa-times-circle"></i> Sorry, no record found with this Email id !</span>')
				$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
			}
		});
	}else{
		$('#divResponse').show()
		$('#divResponse').html('<span class="text-danger" style="font-size:11px; font-weight:700px"><i class="fa fa-times-circle"></i> Error : Please enter a valid email id..!</span>')
		$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
	}
	
}
function ValidateEmail(mail)   
{  
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))  
	{  
		return (true)  
	}  
	return (false)  
}
function recoverAccount()
{
	$('#BTN_SaveChanges').val('Please Wait. . .').addClass('disabled')
	var dataString = $('#form-recover-pass').serialize()
	$.post('accountUpdates.php', dataString, function(html){
		var value = html.trim().split('^')
		if(value[0]=='success'){
			$('#TF_RegisteredEmail').val('').focus()
			$('#divResponse').show()
			$('#divResponse').html('<span class="text-success" style="font-size:11px; font-weight:700px"><i class="fa fa-check"></i> '+ value[1] +'</span>')
			$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
		}else{
			$('#divResponse').show()
			$('#divResponse').html('<span class="text-success" style="font-size:11px; font-weight:700px"><i class="fa fa-check"></i> '+ value[0]+'</span>')
			$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
		}
	});
}
/*function verifyMobile()
{
	$('#BTN_SaveChanges').val('Please Wait. . .').addClass('disabled')
	var mobile = $.trim($('#TF_RegisteredMobile').val()) 
	if(IsMobileNumber($('#TF_RegisteredMobile').prop('id')))
	{
		dataCont = 'action=checkMobile&mobile='+mobile
		$.post('codes/accountUpdates.php', dataCont, function(html){
			if(html.trim()==='exist'){
				recoverAccount()
			}else{
				$('#divResponse').show()
				$('#divResponse').html('<span class="text-danger" style="font-size:11px; font-weight:700px"><i class="fa fa-times-circle"></i> Sorry, you are not registered with Sri Sri Gurukrupa Ashram..!</span>')
				$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
			}
		});
	}else{
		$('#divResponse').show()
		$('#divResponse').html('<span class="text-danger" style="font-size:11px; font-weight:700px"><i class="fa fa-times-circle"></i> Error : Please enter a valid mobile number..!</span>')
		$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
	}
}
function IsMobileNumber(txtMobId) 
	{
	  var mob = /^[7-9]{1}[0-9]{9}$/;
	  var txtMobile = document.getElementById(txtMobId);
	  if (mob.test(txtMobile.value) == false) {
		txtMobile.focus();
		return false;
	  }
	  $('#mobile').removeClass('error')
	  return true;
	}
function ValidateEmail(mail)   
{  
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))  
	{  
		return (true)  
	}  
	return (false)  
}
function recoverAccount()
{
	$('#BTN_SaveChanges').val('Please Wait. . .').addClass('disabled')
	var dataString = $('#form-recover-pass').serialize()
	$.post('codes/accountUpdates.php', dataString, function(html){
		var value = html.trim().split('^')
		if(value[0]=='success'){
			$('#TF_RegisteredMobile').val('').focus()
			$('#divResponse').show()
			$('#divResponse').html('<span class="text-success" style="font-size:11px; font-weight:700px"><i class="fa fa-check"></i> '+ value[1] +'</span>')
			$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
		}else{
			$('#divResponse').show()
			$('#divResponse').html('<span class="text-success" style="font-size:11px; font-weight:700px"><i class="fa fa-check"></i> '+ value[0]+'</span>')
			$('#BTN_SaveChanges').val('Recover').removeClass('disabled')
		}
	});
}*/
 
</script>
<script>
	//paste this code under head tag or in a seperate js file.
	// Wait for window load
	$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
</script>

</body>
</html>
