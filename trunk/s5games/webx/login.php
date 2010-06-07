<?php
session_start();

switch(trim($_POST['user_pass']))
{
	case 's5games':
		$userName = 'General';
		break;
		
	case 'soccer894':
		$userName = 'Admin';
		break;
		
	default:
		$userName = 'Public';
}
$_SESSION['user_name'] = $userName;

header("location: schedule.php");
?>