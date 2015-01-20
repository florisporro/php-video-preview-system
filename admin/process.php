<?php
session_start();
	include('../includes/functions.php');
	include('../includes/databasefunctions.php');
	
	connectmysql();
	if(!auth_admin()){
		exit("Ill authenticated request.");
	}

	switch ($_POST['process']) {
		case "newclient":
			echo createclient($_POST['name'],$_POST['password']);
			break;
		case "deleteclient":
			echo deleteclient($_POST['clientid']);
			break;
		case "editclient":
			echo editclient($_POST['clientid'], $_POST['newname'], $_POST['newpassword']);
			break;
			
		case "newvideo":
			echo createvideo($_POST['clientid'], $_POST['name'], $_POST['videolink'], $_POST['password'], $_POST['description']);
			break;
		case "deletevideo":
			echo deletevideo($_POST['videoid']);
			break;
		case "editvideo":
			echo editvideo($_POST['videoid'], $_POST['newname'], $_POST['newlink'], $_POST['newpassword'], $_POST['newdescription']);
			break;
			
		default:
			echo "Ill informed request.";
	}

?>