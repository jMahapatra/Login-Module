<?php 
session_start();
if(isset($_SESSION)){session_destroy();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://apis.google.com/js/api:client.js"></script>
  <title>Sign In</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <style>
    /* Paste this css to your style sheet file or under head tag */
    /* This only works with JavaScript, 
    if it's not present, don't show loader */
    .no-js #loader { display: none;  }
    /*.js #loader { display: block; position: absolute; left: 100px; top: 0; }*/
    
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
    // GOOGLE OAUTH //
    /*function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);

        saveGoogleUserData(profile)
      };*/

    var googleUser = {};
      var startApp = function() {
        gapi.load('auth2', function(){
          // Retrieve the singleton for the GoogleAuth library and set up the client.
          auth2 = gapi.auth2.init({
            client_id: '306636384838-cquar1u11uqd73rnt10b0ki3frrpfl0u.apps.googleusercontent.com',
            cookiepolicy: 'single_host_origin',
            // Request scopes in addition to 'profile' and 'email'
            scope: 'profile email'
          });
          attachSignin(document.getElementById('customBtn'));
        });
      };

      function attachSignin(element) {
        auth2.attachClickHandler(element, {},
            function(googleUser) {
                var profile = googleUser.getBasicProfile();
                //document.getElementById('name').innerText = "Signed in: " + googleUser.getBasicProfile().getName();
                saveGoogleUserData(profile)
            }, function(error) {
              alert(JSON.stringify(error, undefined, 2));
            });
      }
    function saveGoogleUserData(userData){
        $.post('userData.php', {oauth_provider:'google',userData: JSON.stringify(userData)}, function(data){ 
            console.log(data)
            data = $.trim(data)
            if(data==='success') window.location.href = 'home.php'
        });
    }
    function signOut() {
       var auth2 = gapi.auth2.getAuthInstance();
       auth2.signOut().then(function () {
         console.log('User signed out.');
       });
     }
    startApp();




    // FACEBOOK OAUTH2 //
    function saveFbUserData(userData){
        $.post('userData.php', {oauth_provider:'facebook',userData: JSON.stringify(userData)}, function(data){ 
            console.log(data)
            data = $.trim(data)
            if(data==='success') window.location.href = 'home.php'
        });
    }
    function getFbUserData(){
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture.width(120).height(120)'},function (response) {
            /*document.getElementById('fbLink').setAttribute("onclick","fbLogout()");
            document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
            document.getElementById('userData').innerHTML = '<p><b>FB ID:</b> '+response.id+'</p><p><b>Name:</b> '+response.first_name+' '+response.last_name+'</p><p><b>Email:</b> '+response.email+'</p><p><b>Gender:</b> '+response.gender+'</p><p><b>Locale:</b> '+response.locale+'</p><p><b>Picture:</b> <img src="'+response.picture.data.url+'"/></p><p><b>FB Profile:</b> <a target="_blank" href="'+response.link+'">click to view profile</a></p>';*/

            //Save User Data
            saveFbUserData(response);
            //console.log(response)
        });
        
    }
    // Facebook login with JavaScript SDK
    function fbLogin() {
        FB.login(function (response) {
            if (response.authResponse) {
                getFbUserData();
            } else {
                document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
            }
        }, {scope: 'public_profile,email'});
    }   
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '418518015200587',
            cookie     : true,  // enable cookies to allow the server to access 
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.8' // use graph api version 2.8
        });
    };

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    
    </script>
<body>
    
    <div class="login-box">
        <div class="login-box-body" id="login-block">
            <div class="se-pre-con"></div>
            <p class="login-box-msg">Sign in to start your session</p>
            <form class="" id="form-login" name="form-login" method="post" action="userData.php">
              <div class="form-group has-feedback">
                <input type="hidden" id="oauth_provider" name="oauth_provider" value="website"/>
                <div id="divLoginId">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="login_id" name="login_id" placeholder="Login ID">
                    </div>
                    <div style="display:none" id="alertLoginId"></div>
                </div>
              </div>
              <div class="form-group has-feedback">
                <div id="divPassword">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div style="display:none" id="alertPassword"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12" id="alertResponse" style="display:none"></div>
              </div>
              <div class="row">
                <div class="col-xs-8">
                    <a href="account_recover.php">Recover my account !</a>
                  <!--<div class="checkbox icheck">
                    <label>
                      <div aria-disabled="false" aria-checked="false" style="position: relative;" class="icheckbox_square-blue"><input style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;" type="checkbox"><ins style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div> Remember Me
                    </label>
                  </div>-->
                </div>
                <div class="col-xs-4">
                  <button id="btn_login" name="btn_login" type="submit" class="btn btn-primary btn-block btn-flat">SIGN IN</button>
                </div>
                <!-- /.col -->
              </div>
              <div class="social-auth-links text-center">
                <p>- OR -</p>
                <div onclick="fbLogin()" id="fbLink" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                Facebook</div>
              
                <div id="customBtn" class="customGPlusSignIn btn btn-block btn-social btn-google btn-flat">
                    <i class="fa fa-google-plus"></i> Sign in using Google+
                </div>
                
              </div>
            </form>
        
            
        </div>

        

        <!-- HTML for render Google Sign-In button
<div class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
          <a href="javascript:void(0)" id="signOut" onclick="signOut()">Signout From Google</a>
    </div> -->
<script>
  $(function () {
    $('#form-login').submit(function(e) {
        url = $(this).prop('action')
        formData = $(this).serialize()
        if($.trim($('#login_id').val())!=''){
            $('#alertLoginId').css('display', 'none')
            $('#divLoginId').removeClass('has-error')
            if($('#password').val()!=''){
                $('#alertPassword').css('display', 'none')
                $('#divPassword').removeClass('has-error')
                $('#btn_login').html('Validating. . .').prop('disabled',1)
                $.post(url,formData,function(html){
                    response = $.trim(html)
                    console.log(response)
                    if(response=='success'){
                        $('#alertResponse').removeClass('has-error')
                        $('#alertResponse').css('display','none')
                        $(".se-pre-con").fadeIn()
                        window.location.href = 'home.php'
                        
                    }else{
                        $('#alertResponse').addClass('has-error')
                        $('#alertResponse').html('<span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-times-circle"></i>&nbsp;'+response+'</span>')
                        $('#alertResponse').css('display','block')
                    }
                    $('#btn_login').html('SIGN IN').prop('disabled',0)
                });
            }
            else
            {
                $('#password').focus()
                $('#divPassword').addClass('has-error')
                $('#alertPassword').html('<span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-times-circle"></i>&nbsp;Please enter password..!</span>')
                $('#alertPassword').css('display', 'block')
                
            }
        }
        else
        {
            $('#login_id').focus()
            $('#divLoginId').addClass('has-error')
            $('#alertLoginId').html('<span class="help-block" style="font-size:12px; font-weight:700px"><i class="fa fa-times-circle"></i>&nbsp;Please provide your Login Id</span>')
            $('#alertLoginId').css('display', 'block')
        }
    
    return false
    });  
      
    
  });

  $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
    });
</script>
  
</body>
</html>