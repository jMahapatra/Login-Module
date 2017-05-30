<?php
include 'dbConfig.php';
require 'functions.inc.php';
session_start();
if(empty($_SESSION['userrole'])){ header('location:login.php');}
	
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
    <h3>Change Password</h3>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" id="login-block">

    <form class="" id="form-changepass" name="form-changepass" method="post">
		<div class="form-group">
	        <div class="">
	            <input class="form-control" id="TF_CurrentPass" name="TF_CurrentPass" placeholder="Current Password" type="password" onBlur="verifyPassword()">
	        </div>
	        <div id="verify_response" style="display:none" class="has-error"><span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-times-circle"></i>&nbsp;Please enter correct password..!</span>
	        </div>
	    </div>
	    <div class="form-group">
	        <div class="">
	            <input class="form-control" id="TF_NewPass" name="TF_NewPass" placeholder="New Password" type="password">
	        </div>
	    </div>
	    <div class="form-group">
	        <div class="">
	            <input class="form-control" id="TF_ConfirmPass" name="TF_ConfirmPass" placeholder="Confirm Password" type="password">
	        </div>
	    </div>
	    <div class="form-group">
	        <label class="control-label">
	            <input name="CB_ShowPass" id="CB_ShowPass" onClick="togglePassword()" type="checkbox" value="1">&nbsp;&nbsp;Show Password
	        </label>
			<div id="data-response" style="display:none" ></div>
	    </div>
	    <div class="form-group">
	        <div class="col-xs-6">
	        	<input type="button" onclick="accountUpdate()" class="btn btn-primary btn-block btn-flat" name="BTN_SaveChanges" id="BTN_SaveChanges" value="Save Changes" />
				<input type="hidden" name="action" value="changepassword" />
	        </div>
	        <div class="col-xs-6">
	        	<input type="button" onclick="document.location.href='home.php'" class="btn btn-warning btn-block btn-flat" name="BTN_Cancel" id="BTN_Cancel" value="Back" />
	        </div>
	    </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>

function accountUpdate()
{
	var formString = $('form').serialize()
	$('#BTN_SaveChanges').addClass('disabled').val('Please wait, saving...')
	$.post('accountUpdates.php', formString, function(html){
		var value = html.trim().split('@')
		
			if(value[0]=='success'){
				$('#data-response').removeClass('has-error')
				$('#data-response').addClass('has-success')
				$('#data-response').html('<span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-check"></i> Password successfully changed..</span>')
				$('#data-response').show()
				$('#TF_ConfirmPass,#TF_NewPass,#TF_CurrentPass').prop('value','');
			}else{
				$('#data-response').show()
				$('#data-response').addClass('has-error')
				$('#data-response').html('<span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-times-circle"></i> '+value[0]+'</span>')
			}
		$('#BTN_SaveChanges').removeClass('disabled').val('Save Changes')	
	});
}
function togglePassword() {
	$('#TF_ConfirmPass,#TF_NewPass,#TF_CurrentPass').prop('type',  $('#CB_ShowPass').prop('checked') ? 'text' : 'password');
}
function verifyPassword(){
	var currPass = $('#TF_CurrentPass').val() 
	dataCont = 'action=verifypassword&data='+currPass
	$.post('accountUpdates.php', dataCont, function(html){
		if(html.trim()=='success'){
			$('#verify_response').hide()
		}else{
			$('#verify_response').show()
			$('#TF_CurrentPass').val('').focus()
		}
	});
}
 
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
