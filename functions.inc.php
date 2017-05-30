<?php
function password_encrypt($pwd1)
{
	$test = str_split($pwd1);
	$temp = "";
	for($i = 0; $i < strlen($pwd1); $i++)
		{
			if($i%2 == 0)
				{
				$temp .= ord(str_rot13($test[$i])).ord('*');
				}
			else
				{
				$temp .= ord($test[$i]).ord('@');
				}
		}
	$source = array('0','1','2','3','4','5','6','7','8','9');
	$replace = array('!','@','#','$','%','^','&','*','(');
	$pwd2 = str_replace($source, $replace, $temp);
	return $pwd2;
}
function change_date($resource)
	{
	$new_dt = date('d-m-Y h:i:s A', strtotime($resource));
	return $new_dt;
	}
function change_newsdate($resource)
	{
	$new_dt = date('D, d M Y', strtotime($resource));
	return $new_dt;
	}

function change_MessageDate($resource)
	{
	$new_dt = date('D, d-M', strtotime($resource));
	return $new_dt;
	}
function change_news_date($resource)
	{
	$new_dt = date('D, M d, Y', strtotime($resource));
	return $new_dt;
	}
function change_CommentDate($resource)
	{
	$new_dt = date('D, h:i A, M d, Y', strtotime($resource));
	return $new_dt;
	}
function trim_body($text, $max_length) 
{
$tail = '...';
$tail_len = strlen($tail);
if (strlen($text) > $max_length) 
	{
	$tmp_text = substr($text, 0, $max_length - $tail_len);
	if (substr($text, $max_length - $tail_len, 1) == ' ') 
		{
		$text = $tmp_text;
		}
	else
		{
		$pos = strrpos($tmp_text, ' ');
		$text = substr($text, 0, $pos);
		}
$text = $text . $tail;
	}	
return $text;
}

function getip()
	{
	if (!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
		 //check for ip from share internet
		 $ip = $_SERVER["HTTP_CLIENT_IP"];
		}
	elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
		 // Check for the Proxy User
		 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
	else
		{
		 $ip = $_SERVER["REMOTE_ADDR"];
		}
	return $ip;
	}
	
function validate_img($resource)
	{
	global $error;
	if($resource['error'] > 0)
	{
	switch($resource['error'])
		{
		case UPLOAD_ERR_INI_SIZE:
		$error[] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
		break;
		case UPLOAD_ERR_FORM_SIZE:
		$error[] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
		break;
		case UPLOAD_ERR_PARTIAL:
		$error[] = "The uploaded file was only partially uploaded. ";
		break;
		case UPLOAD_ERR_NO_FILE:
		$error[] = "Please Upload A File. ";
		break;
		case UPLOAD_ERR_NO_TMP_DIR:
		$error[] = "The server is missing a temporary folder. ";
		break;
		case UPLOAD_ERR_CANT_WRITE:
		$error[] = "The server failed to write the uploaded file to disk. ";
		break;
		case UPLOAD_ERR_EXTENSION:
		$error[] = "File upload stopped by extension. ";
		break;
		}
	}
	if($resource['name'] != "")
	{
	list($width, $height, $type, $attr) = getimagesize($resource['tmp_name']);
	switch($type)
		{
		case IMAGETYPE_GIF:
		 // To keep The Array Empty
		break;
		case IMAGETYPE_JPEG:
		 // To keep The Array Empty
		break;
		case IMAGETYPE_PNG:
		 // To keep The Array Empty
		break;
		default:
		$error[] = "The file you uploaded was not a supported filetype. ";
		}
	}
	if($resource['size'] > 4200000)
		{
		$error[] = "The File Exceeds Maximum Upload Limit. ";	
		}
	}
// VALIDATE IMAGE FOR .PNG FORMAT //
function validate_png_img($resource)
	{
	global $error;
	if($resource['error'] > 0)
	{
	switch($resource['error'])
		{
		case UPLOAD_ERR_INI_SIZE:
		$error[] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
		break;
		case UPLOAD_ERR_FORM_SIZE:
		$error[] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
		break;
		case UPLOAD_ERR_PARTIAL:
		$error[] = "The uploaded file was only partially uploaded. ";
		break;
		case UPLOAD_ERR_NO_FILE:
		$error[] = "Please Upload Photo. ";
		break;
		case UPLOAD_ERR_NO_TMP_DIR:
		$error[] = "The server is missing a temporary folder. ";
		break;
		case UPLOAD_ERR_CANT_WRITE:
		$error[] = "The server failed to write the uploaded file to disk. ";
		break;
		case UPLOAD_ERR_EXTENSION:
		$error[] = "File upload stopped by extension. ";
		break;
		}
	}
	if($resource['name'])
	{
		list($width, $height, $type, $attr) = getimagesize($resource['tmp_name']);
		if($type != IMAGETYPE_PNG)
			{
			$error[] = "Photo must be in .png format";
			}
		if($resource['size'] > 4194304)
			{
			$error[] = "The File Size Exceeds Maximum Upload Limit. ";	
			}
	}
}	
/////////////////////////////////
	
//Upload Image Script
function upload_image($resource, $dir, $image_nm, $new_width, $new_height, $aspectratio)
{
	list($width, $height, $type, $attr) = getimagesize($resource['tmp_name']);
	$image = imagecreatefrompng($resource['tmp_name']);
	if($aspectratio == "yes")
	{	
	$ratio = $width / $height;
	if($new_width / $new_height > $ratio)
		{
		$new_width = $new_height * $ratio;	
		}
	else
		{
		$new_height = $new_width / $ratio;
		}
	}
	$new_image = imagecreatetruecolor($new_width, $new_height);
	
		imagealphablending($image, false);
		imagesavealpha($image,true);
		imagealphablending($new_image, false);
		imagesavealpha($new_image,true);
		$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		
		imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	imagepng($new_image, $dir."/".$image_nm,0);
	imagedestroy($new_image);
	imagedestroy($image);
}
// VALIDATE AUDIO MP3//
function validate_mp3_audio($resource)
{
	global $error;
	if($resource['error'] > 0)
	{
	switch($resource['error'])
		{
		case UPLOAD_ERR_INI_SIZE:
		$error[] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
		break;
		case UPLOAD_ERR_FORM_SIZE:
		$error[] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
		break;
		case UPLOAD_ERR_PARTIAL:
		$error[] = "The uploaded file was only partially uploaded. ";
		break;
		case UPLOAD_ERR_NO_FILE:
		$error[] = "Please Upload Audio File. ";
		break;
		case UPLOAD_ERR_NO_TMP_DIR:
		$error[] = "The server is missing a temporary folder. ";
		break;
		case UPLOAD_ERR_CANT_WRITE:
		$error[] = "The server failed to write the uploaded file to disk. ";
		break;
		case UPLOAD_ERR_EXTENSION:
		$error[] = "File upload stopped by extension. ";
		break;
		default: 
        $error[] = "Unknown upload error"; 
        break; 
		}
	}
	if($resource['name'] != "")
	{
		$type = $resource['type'];
		if ((($type != "audio/mp3" && $type !='audio/mpeg' && $type !="audio/x-mpeg" && $type !="audio/x-mp3" && $type !="audio/mpeg3" && $type !="audio/x-mpeg3" && $type !="audio/x-mpg" && $type !="audio/x-mpegaudio" && $type !="audio/x-mpeg-3")))
		{
		$error[] = "Sorry, but this is not a valid file";
		}
	if($resource['size'] > 25165824)
		{
		$error[] = "The File Size Exceeds Maximum Upload Limit.<br />File Shouldn't be more than 25MB.";	
		}
	}
}	
/////////////////////////////////

?>