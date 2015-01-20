<?php
function connectmysql() {
	$dbhost = 'localhost';
	$dbuser = '';
	$dbpass = '';
	$dbname = '';
	
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	
	mysql_select_db($dbname);
}
function disconnectmysql() {
	mysql_close($conn);
}

function getclient($client_id) {
	$sql = 'SELECT *
	FROM `clients`
	WHERE `client_id` = '.$client_id.'';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
	return $result;

    
}
function getclients() {
	$sql = 'SELECT * FROM `clients`';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
	return $result;

    
}
function getclientbypassword($password) {
	$sql = 'SELECT *
	FROM `clients`
	WHERE `password` = \''.$password.'\'';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
	return $result;
}
function createclient($name, $password) {
	$sql = 'INSERT INTO `deb13353n2_video`.`clients` (
	`client_id`, 
	`name`, 
	`created`, 
	`password`)
	
	VALUES(
		NULL,
		\''.$name.'\',
		\''.time().'\',
		\''.$password.'\'
		);'; 
		
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    return $result;
}
function deleteclient($client_id) {
	$sql = 'DELETE FROM `clients` WHERE `clients`.`client_id` = '.$client_id.' LIMIT 1;'; 
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
    
    unset ($sql);
    //We're also deleting all the videos this client has.
    $sql = 'DELETE FROM `videos` WHERE `videos`.`client_id` = '.$client_id.';'; 
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    return $result;
}
function editclient($client_id, $newname, $newpassword) {
	$sql = 'UPDATE `deb13353n2_video`.`clients`
		SET `name` = \''.$newname.'\',
		`password` = \''.$newpassword.'\'
		WHERE `clients`.`client_id` = '.$client_id.'
		LIMIT 1;';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
    return $result;
}

function getvideos($client_id) {
	$sql = 'SELECT *
	FROM `videos`
	WHERE `client_id` = '.$client_id.'';

	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
	return $result;
}
function createvideo($client_id, $name, $videolink, $password, $description) {
	$sql = 'INSERT INTO`deb13353n2_video`.`videos` (
	`video_id`,
	`client_id`,
	`name`,
	`videolink`,
	`created`,
	`password`,
	`description`)

	VALUES (
	NULL,
	\''.$client_id.'\',
	\''.$name.'\',
	\''.$videolink.'\',
	\''.time().'\',
	\''.$password.'\',
	\''.$description.'\');';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    return $result;
}
function deletevideo($video_id) {
	$sql = 'DELETE FROM `videos` WHERE `videos`.`video_id` = '.$video_id.' LIMIT 1;'; 
	$result = mysql_query($sql);
	
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    return $result;
}
function editvideo($video_id, $newname, $newlink, $newpassword, $newdescription) {
	$sql = 'UPDATE `deb13353n2_video`.`videos`
	SET `name` = \''.$newname.'\',
	`videolink` = \''.$newlink.'\',
	`password` = \''.$newpassword.'\',
	`description` = \''.$newdescription.'\'
	WHERE `videos`.`video_id` = '.$video_id.'
	LIMIT 1;';
	
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
    return $result;
}

function getsetting($settingname) {
	$sql = 'SELECT * 
		FROM `settings`
		WHERE `setting` = \''.$settingname.'\'
		'; 
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
    $result_value = mysql_fetch_assoc($result);
    return $result_value['value'];
    
    
}
function editsetting($settingname, $newvalue) {
	$sql = 'UPDATE `deb13353n2_video`.`settings`
		SET `value` = \''.$newvalue.'\'
		WHERE `settings`.`setting` = \''.$settingname.'\'
		LIMIT 1;';
		
	$result = mysql_query($sql);
	if (!$result) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $sql;
	    die($message);
    }
    
    return $result;
}

?>