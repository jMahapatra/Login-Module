<?php
 session_start();
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Facebook Login</title>
	</head>
	<body>
		<table border="1">
			<tr>
				<td rowspan="4"><img src="<?php echo $_SESSION['picture'] ?>" width="120px" /></td>
				<td><?php echo $_SESSION['email']?></td>
				
			</tr>
			<tr>
				<td><?php echo $_SESSION['firstname'].' '.$_SESSION['lastname'].''?></td>
			</tr>
			<tr>
				<td><a href="change_password.php">Change Password</a></td>
			</tr>
			<tr>
				<td><button onclick="document.location.href='login.php'">Logout</button></td>
			</tr>
		</table>
		
		
		
	</body>
</html>