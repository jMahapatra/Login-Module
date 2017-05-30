<?php
//Load the database configuration file
include 'dbConfig.php';
require 'functions.inc.php';

session_start();

//Convert JSON data into PHP variable
$userData = json_decode($_POST['userData']);
$oauth_provider = $_POST['oauth_provider'];


if(!empty($userData))
{    
    if($oauth_provider=='facebook')
    {
	    //Check whether user data already exists in database
	    $prevQuery = "SELECT * FROM login WHERE oauth_provider = '".$oauth_provider."' AND social_oauthid = '".$userData->id."'";

	    $prevResult = $db->query($prevQuery);
	    if($prevResult->num_rows > 0)
	    { 
	    	
	    	$row = $prevResult->fetch_assoc();

	        //Update user data if already exists
	        $query = "UPDATE login SET last_access = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$userData->id."'";
	        $update = $db->query($query);
	        
	        	$_SESSION['email'] = $row['primary_email'];
	        	$_SESSION['username'] = $row['username'];
	        	$_SESSION['userrole'] = $row['user_role'];
	        	$_SESSION['firstname'] = $row['first_name'];
	        	$_SESSION['lastname'] = $row['last_name'];
	        	$_SESSION['picture'] = $row['profile_picture'];
	        
	        echo 'success';
	    }else{
	    	
	        //Insert user data
	        $stmt = $db->prepare("INSERT INTO `login`(`username`, `password`, `first_name`, `last_name`, `gender`, `profile_picture`, `created`, `user_role`, `primary_email`, `primary_mobile`, `oauth_provider`, `social_oauthid`, `social_link`, `account_status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

	        $stmt->bind_param("ssssssssssssss",$username, $password, $firstname, $lastname, $gender, $picture, $created, $role, $email, $mobile, $oauth_provider, $oauth_uid, $link, $status);


	        $firstname= $userData->first_name;
	        $lastname= $userData->last_name;
	        $username= strtolower($firstname[0].$lastname).uniqid();
	        $password= password_encrypt(123456);
	        $gender= $userData->gender;
	        $picture= $userData->picture->data->url;
	        $created= date("Y-m-d H:i:s");
	        $role= 'user';
	        $email= $userData->email;
	        $mobile= '';
	        $oauth_provider= $oauth_provider;
	        $oauth_uid= $userData->id;
	        $link= $userData->link;
	        $status= 'activate';

	        $success = $stmt->execute();
	        if($success){ 
	        	
	        	$_SESSION['email'] = $email;
	        	$_SESSION['username'] = $username;
	        	$_SESSION['userrole'] = $role;
	        	$_SESSION['firstname'] = $firstname;
	        	$_SESSION['lastname'] = $lastname;
	        	$_SESSION['picture'] = $picture;
	        	echo 'success';
	        } 
	        else{ 
	        	session_destroy();
	        	echo 'failed';
	        }
	        
	    }
	}
	else if($oauth_provider=='google')
	{
		
		$oauth_uid = $userData->Eea;

		//Check whether user data already exists in database
	    $prevQuery = "SELECT * FROM login WHERE oauth_provider = '".$oauth_provider."' AND social_oauthid = '".$oauth_uid."'";

	    $prevResult = $db->query($prevQuery);
	    if($prevResult->num_rows > 0)
	    { 
	    	
	    	$row = $prevResult->fetch_assoc();

	        //Update user data if already exists
	        $query = "UPDATE login SET last_access = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'";
	        $update = $db->query($query);
	        
	        	$_SESSION['email'] = $row['primary_email'];
	        	$_SESSION['username'] = $row['username'];
	        	$_SESSION['userrole'] = $row['user_role'];
	        	$_SESSION['firstname'] = $row['first_name'];
	        	$_SESSION['lastname'] = $row['last_name'];
	        	$_SESSION['picture'] = $row['profile_picture'];
	        
	        echo 'success';
	    }else{
	    	//Insert user data
	        $stmt = $db->prepare("INSERT INTO `login`(`username`, `password`, `first_name`, `last_name`, `gender`, `profile_picture`, `created`, `user_role`, `primary_email`, `primary_mobile`, `oauth_provider`, `social_oauthid`, `social_link`, `account_status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	        $stmt->bind_param("ssssssssssssss",$username, $password, $firstname, $lastname, $gender, $picture, $created, $role, $email, $mobile, $oauth_provider, $oauth_uid, $link, $status);

	        $firstname= $userData->ofa;
	        $lastname= $userData->wea;
	        $username= strtolower($firstname[0].$lastname).uniqid();
	        $password= password_encrypt(123456);
	        $gender= '';
	        $picture= $userData->Paa;
	        $created= date("Y-m-d H:i:s");
	        $role= 'user';
	        $email= $userData->U3;
	        $mobile= '';
	        $oauth_provider= $oauth_provider;
	        $oauth_uid= $userData->Eea;
	        $link= '';
	        $status= 'activate';


	        $success = $stmt->execute();
	        if($success){ 
	        	
	        	$_SESSION['email'] = $email;
	        	$_SESSION['username'] = $username;
	        	$_SESSION['userrole'] = $role;
	        	$_SESSION['firstname'] = $firstname;
	        	$_SESSION['lastname'] = $lastname;
	        	$_SESSION['picture'] = $picture;
	        	echo 'success';
	        } 
	        else{ 
	        	session_destroy();
	        	echo 'failed';
	        }
	        
	    }
	}	
}

if($oauth_provider =='website')
{
		$email = $_POST['login_id'];
		$password = password_encrypt($_POST['password']);

		//Check whether user data already exists in database
	    $prevQuery = "SELECT * FROM login WHERE primary_email = '".$email."'";
	    $prevResult = $db->query($prevQuery);
	    if($prevResult->num_rows > 0)
	    { 
	    	$row = $prevResult->fetch_assoc();
		
			if($row['password'] === $password)
			{
				if($row['account_status'] == "activate")
				{
					
						session_start();
						$_SESSION['email'] = $row['primary_email'];
			        	$_SESSION['username'] = $row['username'];
			        	$_SESSION['userrole'] = $row['user_role'];
			        	$_SESSION['firstname'] = $row['first_name'];
			        	$_SESSION['lastname'] = $row['last_name'];
			        	$_SESSION['picture'] = $row['profile_picture'];
			        	echo 'success';
						exit;
				}
				else
				{
					die("Sorry, Your account is not active..!");
				}
			}
			else
			{
				die("Sorry, password mismatch..!");
			}
		}
		else
		{
			die("Email id doesn't exist..! <a href='javascript:void(0)'>Click here to register</a>");
		}
}
?>