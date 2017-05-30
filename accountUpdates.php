<?php 
include 'dbConfig.php';
require 'functions.inc.php';

session_start();
	
	/****  CHANGE PASSWORD CODES   ****/
	if($_POST['action'] == "verifypassword")
	{
		$passQuery = "SELECT `password` FROM `login` WHERE `primary_email`='".$_SESSION['email']."'";
		$passResult = $db->query($passQuery);
		$row = $passResult->fetch_assoc();

		$givenPass = password_encrypt($_POST['data']);
		if($row['password']===$givenPass){
			echo 'success';
		}else{ echo 'error';}
	}
	if($_POST['action'] == "changepassword")
	{
		$newpass = $_POST['TF_NewPass'];
		$confirmpass = $_POST['TF_ConfirmPass'];
		if($newpass==$confirmpass){
			$encryptPass = password_encrypt($newpass);
			$query = "UPDATE `login` SET `password` = '$encryptPass' WHERE `primary_email` = '".$_SESSION['email']."'";
			$update = $db->query($query);
			if($update)
				echo 'success';
			exit;
		}else{
			echo 'Error : Confirm password mismatch, please try again..!';
		}
	}
	
	/****  RECOVER PASSWORD CODES   ****/
	if($_POST['action'] == "recoveraccount")
	{
		$Query = "SELECT `first_name`, `last_name`,`password` FROM `login` WHERE `primary_email`='".$_POST['TF_RegisteredEmail']."'";
		$Result = $db->query($Query);
		$row = $Result->fetch_assoc();
		
			$normal_pass = mt_rand(111111,999999);
			$pass = password_encrypt($normal_pass);
			/*#########// Send the password through Mail //#########*/
			$subject = "Recover Your Account";
			$memberName = ucfirst($row['first_name'].' '.$row['last_name']);
			$user_email = trim($_POST['TF_RegisteredEmail']);
			$host_mailid = 'hellojk.mahapatra@gmail.com';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "From: $host_mailid \r\n";
			$txt="<p>Dear ".ucwords($memberName).", Your account has been recoverd successfully. Your new login password is <strong>".$normal_pass."</strong></p>
			<p>NOTE : We suggest you to change your password after login.</p>
			<br />
			<br />
			<p>Thank You,<br />
			<strong>Team</strong><br />
			&nbsp;</p>";
			$mail_sent = mail($user_email, $subject, $txt, $headers);
		if($mail_sent){
			$query = "UPDATE `login` SET `password`='$pass',`password_changed`=TIMESTAMP(NOW()) WHERE `primary_email`='".$_POST['TF_RegisteredEmail']."'";
			$updated = $db->query($query);

			if($updated){
				echo 'success^Your new account password has been sent to your e-mail id.';
			}else{
				echo 'success^Error : Unable to update your new password..!';
			}
		}else{
			echo 'Error : Can\'t recover your account, once check your internet connection..!';
		}
	}
	
	/****  PERSONAL INFORMATION   ****/
	if($_POST['action'] == "personalinformation")
	{
		if($_POST['profile_type']=='volunteer'){
			$status = 'deactivate';
		}else{
			$status = 'activate';
		}
		//print_r($_POST);
		$normal_pass = mt_rand(111111,999999);
		$pass = password_encrypt($normal_pass);
		/*#########// Send the password through Mail //#########*/
			
			$subject = "Login Password for Hari Hara High School, Aska";
			$memberName = $_POST['TF_FirstName'];
			$user_email = $_POST['TF_EmailId'];
			$host_mailid = 'hariharahighschool@gmail.com';
			/*$headers  = "MIME-Version: 1.0" . PHP_EOL;
			$headers .= "Content-Type: text/html; charset=ISO-8859-1" . PHP_EOL;
			$headers .= "From: $host_mailid" . PHP_EOL;*/
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "From: $host_mailid \r\n";
			$subscription_id = strtoupper(uniqid());
			$txt="<p>Hello ".ucwords($memberName).", You have successfully registered with Hari Hara High School, Aska. And your new login password is <strong>".$normal_pass."</strong>  <br />
			<a href='http://www.hariharahighschool.com/login.php'>Click here to login</a></p>
			<p>Welcome to,<br />
			<strong>Hari Hara High School, Aska</strong><br />
			&nbsp;</p>";
			//$mail_sent = send_mail($user_email, $host_mailid, $subject, $txt, $headers);
			$mail_sent = mail($user_email, $subject, $txt, $headers);
		if($mail_sent){
			mysql_query("INSERT INTO `profile_info`(`ProfileType`, `Name`, `Gender`, `DOB`, `matriculateOn`, `profile_photo`, `occupation`, `occupation_type`, `About`, `Country`, `State`, `Dist`, `City_Pan`, `PIN`, `Mobile`, `Email`, `Address`)VALUES('".$_POST['profile_type']."','".$_POST['TF_FirstName']."','".$_POST['RG_Gender']."','".$_POST['TF_DateOfBirth']."', '".trim($_POST['matriculate'])."', '', '".$_POST['occupation']."', '".$_POST['occupation_type']."','".$_POST['about']."','india','".strtolower($_POST['state'])."','".strtolower($_POST['district'])."','".strtolower($_POST['city'])."','".$_POST['pincode']."','".$_POST['mobile']."','".$_POST['TF_EmailId']."','".$_POST['address']."')");
			mysql_query("INSERT INTO `login_info`(`LI_UserName`, `LI_Password`, `LI_DateCreated`, `LI_Role`, `LI_primaryemail`, `LI_primarymobile`, `LI_registration_type`, `LI_social_authid`, `LI_acountstatus`) VALUES('".$_POST['TF_EmailId']."','$pass',DATE(NOW()),'3','".$_POST['TF_EmailId']."','".$_POST['mobile']."','normal','','$status')");
			mysql_query("INSERT INTO `login_status`(`LS_UserID`, `LS_TypeID`, `LS_Time`) VALUES('".$_POST['TF_EmailId']."','1',TIMESTAMP(NOW()))");
			/*####### // uploading the profile image // ######*/
			if(is_array($_FILES)) 
			{
				if(is_uploaded_file($_FILES['userImage']['tmp_name']))
				{
					$extn = end(explode('.',$_FILES['userImage']['name']));
					$imageName = strtoupper(uniqid('PROF')).'.'.$extn;
					$sourcePath = $_FILES['userImage']['tmp_name'];
					$targetPath = "../Upl_Images/profile";
					if(validate_img($_FILES['userImage']))
					{
						list($width, $height) = getimagesize($sourcePath);
						$img_info = getimagesize($image);
						if($width >= '200' && $height >= '200'){
							upload_image($_FILES['userImage'], $targetPath, $imageName, 200, 200, "no");
							$updated = mysql_query("UPDATE `profile_info` SET `profile_photo`='$imageName', `location` = 'local' WHERE `Email`='".$_POST['TF_EmailId']."'");
						}else{
							echo "Minimum image size 200 X 200, You can update photo later..!";
						}
					}
					else
					{
						echo "Error : Failed to upload photo..!";
					}
				}
			}
			echo 'success^, Your account password sent to your e-mail id. Use that password to Login.';
		}else{
			echo 'Unable to save your information, once check your internet connection..!';
		}
	}
	
	/****  ADDRESS CODES   ****/
	if($_POST['action'] == "addresses")
	{
		/*$res_deafultAdr = mysql_query("SELECT `username` FROM `user_shippingaddress` WHERE `username`='".$_SESSION['user_id']."' AND `default_address`='1'");
		if(mysql_num_rows($res_deafultAdr)>0) $defaultAddr = 0;
		else $defaultAddr = 1;
		$added = mysql_query("INSERT INTO `user_shippingaddress`(`username`, `name`, `street_address`, `landmark`, `city`, `state`, `country`, `pincode`, `contactno`, `default_address`) VALUES('".$_SESSION['user_id']."', '".$_POST['TF_Name']."','".$_POST['TA_Address']."','".$_POST['TF_LandMark']."','".$_POST['TF_City']."','".$_POST['SE_State']."','india','".$_POST['TF_PIN']."','".$_POST['TF_ContactNo']."','$defaultAddr')");
		if($added){
			echo 'success@'.$_POST['action'];
		}else{
			echo 'Error : Unable to add new address..!!';
		}*/
	}
	
	if($_POST['action'] == "modifyaddress")
	{
		/*if($_POST['type']=='remove'){
			$res_deafultAdr = mysql_query("SELECT `username` FROM `user_shippingaddress` WHERE `username`='".$_SESSION['user_id']."' AND `default_address`='1' AND `address_slno`='".$_POST['address_id']."'");
			
			if(mysql_num_rows($res_deafultAdr)>0){
				echo "You cannot remove default address.";
			}else{
				$updated = mysql_query("DELETE FROM `user_shippingaddress` WHERE `username`='".$_SESSION['user_id']."' AND `address_slno`='".$_POST['address_id']."'");
				if($updated){
					echo 'success@addresses';
				}else{
					echo 'error';
				}
			}
		}
		if($_POST['type']=='update'){
			$updated = mysql_query("UPDATE `user_shippingaddress` SET `default_address`='1' WHERE `username`='".$_SESSION['user_id']."' AND `address_slno`='".$_POST['address_id']."'");
			$updated = mysql_query("UPDATE `user_shippingaddress` SET `default_address`='0' WHERE `username`='".$_SESSION['user_id']."' AND `address_slno`!='".$_POST['address_id']."'");
			if($updated){
				echo 'success@addresses';
			}else{
				echo 'error';
			}
		}*/
	}
	
	/****  PRIMARY EMAIL / MOBILE  ****/
	if($_POST['action'] == "updatemobileemail")
	{
		$updated = mysql_query("UPDATE `login_info` SET `LI_primaryemail`='".$_POST['TF_Email']."', `LI_primarymobile`='".$_POST['TF_Mobile']."' WHERE `LI_UserName`='".$_SESSION['user_id']."'");
		if($updated){
			$_SESSION['email']=$_POST['TF_Email'];
			$_SESSION['mobile']=$_POST['TF_Mobile'];
			echo 'success@'.$_POST['action'];
		}else{
			echo 'error';
		}
	}
	
	/****  A/C DEACTIVATION   ****/
	if($_POST['action'] == "deactivateaccount")
	{
		$row_currpassword = mysql_fetch_assoc(mysql_query("SELECT `LI_Password` FROM `login_info` WHERE `LI_UserName`='".$_SESSION['user_id']."'"));
		$givenPass = password_encrypt($_POST['TF_Password']);
		if($row_currpassword['LI_Password']==$givenPass){
			mysql_query("UPDATE `login_info` SET `LI_acountstatus` = 'deactivate' WHERE `LI_UserName` = '".$_SESSION['user_id']."'");
			echo 'success@'.$_POST['action'];
		}else{
			echo 'Error : Password mismatch, please try again..!';
		}
	}
	?>
	
       
    <?php
	#### -- // for HariHar highschool update // - ##
	if($_POST['action'] == "updateprofile")
	{
	//print_r($_POST);
	$updated = mysql_query("UPDATE `profile_info` SET `matriculateOn`='".trim($_POST['matriculate'])."', `ProfileType`='".$_POST['profile_type']."', `Name`='".$_POST['TF_FirstName']."', `Gender`='".$_POST['RG_Gender']."', `DOB`='".$_POST['TF_DateOfBirth']."', `occupation`='".$_POST['occupation']."', `occupation_type`='".$_POST['occupation_type']."', `About`='".$_POST['about']."', `Country`='".strtolower($_POST['country'])."', `State`='".$_POST['state']."', `Dist`='".$_POST['district']."', `City_Pan`='".$_POST['city']."', `PIN`='".$_POST['pincode']."', `Mobile`='".$_POST['mobile']."', `Email`='".$_POST['TF_EmailId']."', `Address`='".$_POST['address']."' WHERE `Email`='".$_SESSION['email']."'");
	
	if($updated){
		echo "success^"."Profile information saved..!";
		$_SESSION['email'] = $_POST['TF_EmailId'];
		$row_profile = mysql_fetch_assoc(mysql_query("SELECT `profile_photo`, `location` FROM `profile_info` WHERE `Email`='".$_SESSION['email']."'"));
		$imageName ='';
		if(is_array($_FILES)) {
			if($_FILES['userImage']['name'] != "")
			{
				$existImage = trim($row_profile['profile_photo']);
				$imageName = ($existImage=='' || $existImage=='0') ? strtoupper(uniqid('PROF')).'.jpg' : (($row_profile['location'] == "local") ? $existImage : strtoupper(uniqid('PROF')).'.jpg');
				if($row_profile['location'] == "local")
				{
					if(file_exists("../Upl_Images/profile/".$imageName)){
						unlink("../Upl_Images/profile/".$imageName);
					}
				}
				$sourcePath = $_FILES['userImage']['tmp_name'];
				$targetPath = "../Upl_Images/profile";
				if(validate_img($_FILES['userImage']))
				{
					list($width, $height) = getimagesize($sourcePath);
					$img_info = getimagesize($image);
					if($width >= '200' && $height >= '200'){
						upload_image($_FILES['userImage'], $targetPath, $imageName, 200, 200, "no");
						$updated = mysql_query("UPDATE `profile_info` SET `profile_photo`='$imageName', `location` = 'local' WHERE `Email`='".$_SESSION['email']."'");
						echo "success^".$imageName;
					}else{
						echo "Image not saved..! Minimum image size 200 X 200";
					}
				}
				else
				{
					echo "Image not saved..!";
				}
			}
		}
	}else{
		echo "Error : Unable to update profile.!!";
	}
		mysql_query("UPDATE `login_info` SET `LI_UserName`='".$_POST['TF_EmailId']."', `LI_primaryemail`='".$_POST['TF_EmailId']."', `LI_primarymobile`='".$_POST['mobile']."' WHERE `LI_UserName`='".$_SESSION['email']."'");
		
	}
	
	
	if($_POST['action'] == "getBatchMates")
	{
		if($_POST['year']!=''){
			$cond = "AND `matriculateOn` = '".$_POST['year']."' ORDER BY `Name` ASC";
		}else{
			$cond = " ORDER BY `Name` ASC";
		}
		$lower = (isset($_POST['lower']) && $_POST['lower']!='') ? $_POST['lower'] : 0;
		$upper = (isset($_POST['upper']) && $_POST['upper']!='') ? $_POST['upper'] : 8;
		$row_tot_profile = mysql_fetch_assoc(mysql_query("SELECT COUNT(`PI_Id`) as `total_members` FROM `profile_info` WHERE `ProfileType` = 'alumni' $cond"));
		$res_profile = mysql_query("SELECT * FROM `profile_info` WHERE `ProfileType` = 'alumni'  $cond LIMIT $lower, $upper");
		?>
	<div class="row">
		<?php
		while($row_profile = mysql_fetch_assoc($res_profile)){
			$res_SocialLink = mysql_query("SELECT `username`, `networktype`, `link`, `display` FROM `user_socialinfo` WHERE `username`='".$row_profile['Email']."' AND `display`='yes'");
			if($row_profile['profile_photo']!=''){
				$profile_img_path = ($row_profile['location'] == "local") ? ROOT_DIR."Upl_Images/profile/".$row_profile['profile_photo'] : $row_profile['profile_photo'];
			}
			else{
				$profile_img_path = ROOT_DIR."assets/img/office.jpg";
			}
			
	?>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-default panel-card wow fadeInRight animation-delay-2">
                <div class="panel-heading">
                    <img src="assets/img/HHHSBG-SM.jpg" />
                    <!--<button class="btn btn-primary btn-ar btn-sm" role="button">Follow</button>-->
                </div>
                <div class="panel-figure">
                    <img class="img-responsive img-circle" src="<?php echo $profile_img_path?>" style="min-height:70px;min-width:70px;height:70px;width:70px" />
                </div>
                <div class="panel-body text-center">
                    <h4 class="panel-header"><a href="http://hariharahighschool.com/PublicProfile.php?prof_id=<?php echo $row_profile['PI_Id']?>"><?php echo ucwords($row_profile['Name'])?></a></h4>
                    <small><?php if($row_profile['occupation']!=''){echo ucwords($row_profile['occupation']); if($row_profile['occupation_type']=='retired'){ echo ' ('.ucwords($row_profile['occupation_type']).')';}}else{ echo '<i style="color:#aaa">No Occupation Detail</i>';}?>&nbsp;</small>
					<div class="row">
						<span class="col-lg-12" style="font-size:12px;font-weight:bold"><i class="fa fa-envelope"></i>  <?php echo $row_profile['Email']?></span>
					</div>
					<!--<div class="row">
						<span class="col-lg-12" style="font-size:12px;font-weight:bold"><i class="fa fa-map-marker"></i>  <?php echo ucwords($row_profile['State'].' ('.$row_profile['PIN'].'), ').ucwords($row_profile['Country']) ?></span>
					</div>-->
				</div>
                <div class="panel-thumbnails">
					<div class="row text-center">
					<?php
					if(mysql_num_rows($res_SocialLink)>0){
						while($row_SocialLink=mysql_fetch_assoc($res_SocialLink)){
					?>
						<a href="<?php echo $row_SocialLink['link']?>" class="social-icon-ar sm <?php echo $row_SocialLink['networktype']?> animated fadeInDown"><i class="fa fa-<?php echo $row_SocialLink['networktype']?>"></i></a>
					<?php
						}
					}else{
						?>
						<a href="javascript:void(0)" class="label label-warning" style="font-size:11px">No social media link added yet..!</a>
						<?php
						}
						?>
					</div>
                </div>
            </div>
        </div>
        <?php
		}
		?>
	</div>
	<div class="row">
		<div class="pull-right">
			<div class="dataTables_paginate paging_bootstrap">
				<ul class="pagination">
					<?php
					//echo mysql_num_rows($res_tot_videos);
					for($i = 0; $i < round($row_tot_profile['total_members']/8); $i++)
					{
						?>
						<li class="<?php if($lower == $i*8) echo 'active'; ?>"><a href="javascript:void(0)" onClick="getBatchMates('<?php echo $_POST['year']?>','<?php echo $i*8 ?>', '8')"><?php echo $i+1; ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
		<?php
	}
	
	/****  VERIFY EMAIL CODES   ****/
	if($_POST['action'] == "checkEmail")
	{
		$curr_email = trim($_POST['email']);
		$checkQuery = "SELECT `username` FROM `login` WHERE `primary_email`='".$curr_email."'";
	    $checkResult = $db->query($checkQuery);
		if($checkResult->num_rows > 0)
		{
			echo 'exist';
		}
		else
		{
			echo 'not exists';
		}
	}
	/****  VERIFY & UPDATE SOCIAL INFO CODES   ****/
	if($_POST['action'] == "socialinfo")
	{
		if($_POST['action_type'] == "add"){
			$linktype = trim($_POST['linktype']);
			$social_link = trim($_POST['TF_SocialLink']);
			$res_SocialLink = mysql_query("SELECT `username` FROM `user_socialinfo` WHERE `username`='".$_SESSION['email']."' AND `networktype`='$linktype'");
			if(mysql_num_rows($res_SocialLink)==0){
				$inserted = mysql_query("INSERT INTO `user_socialinfo` (`username`, `networktype`, `link`, `display`) VALUES('".$_SESSION['email']."','$linktype','$social_link','yes')");
				if($inserted){
					echo 'success';
				}else{
					echo 'Error : Unable to save social link, please send a message from CONTACT page..!';
				}
			}else{
				echo "Already Exist..! Remove the existing link and try to add again.";
			}
		}else if($_POST['action_type'] == "fetch"){
			$res_SocialLink = mysql_query("SELECT `social_slno`, `username`, `networktype`, `link` FROM `user_socialinfo` WHERE `username`='".$_SESSION['email']."'");
			if(mysql_num_rows($res_SocialLink)>0){
			?>
				<div class="form-group">
					<span style="font-size:14px; font-weight:bold">Currently shared</span>
				</div>
			<?php
				while($row_SocialLink = mysql_fetch_assoc($res_SocialLink))
				{
				?>
				<div class="form-group">
					<span class="col-ld-11 col-md-11" style="font-size:12px"><a href="<?php echo $row_SocialLink['link']?>" target="_blank"><i class="fa fa-<?php echo $row_SocialLink['networktype']?>"></i> <?php echo $row_SocialLink['link']?></a></span>
					<span class="col-ld-1 col-md-1"><a href="javascript:void(0)" onClick="removeSocial(<?php echo $row_SocialLink['social_slno']?>)"><i class="fa fa-times-circle text-danger"></i></a></span>
				</div>
			<?php
				}
			}else{
				?>
				<div class="form-group">
					<span class="text-center" style="font-size:14px; font-weight:bold">You have not added any social link yet..!</span>
				</div>
				<?php
			}
		}else if($_POST['action_type'] == "remove"){
			$removed = mysql_query("DELETE FROM `user_socialinfo` WHERE `social_slno`='".$_POST['remove_id']."'");
			mysql_query("OPTIMIZE TABLE `user_socialinfo`");
			if($removed){
				echo 'success';
			}else{
				echo 'ERROR : Failed to remove, please send a message about this from CONTACT page..!';
			}
		}
		
		
	}
	?>
	
	
	
	
	
	