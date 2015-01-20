<?php
	session_start();
	include('includes/functions.php');
	include('includes/databasefunctions.php');
	
	connectmysql();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>Video Preview Robot</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
	<div align="center">
		<img src="FP_Logo.jpg" id="fp_logo" width="210" height="210" alt="Logo" />
	</div>
	<?php
		if ($_GET['logout'] == true) {
				session_unset();
				unset($_POST['password']);
		}
		auth_client();
	?>
	
	<h2>Hello, <?php echo $_SESSION['clientname']; ?>. <a id="refreshbutton" class="success" href="?logout=true">Logout</a></h2>
	<?php
		$videos = getvideos($_SESSION['client_id']);
		if (mysql_num_rows($videos) == 1) {
			//If we only have one video, let's just put it on the screen.
			$video = mysql_fetch_array($videos);
			writevideo($video["name"], $video["description"], $video["created"], $video["videolink"]);
			?><div style="height: 200px;"></div><?
		} else {
			while ($video = mysql_fetch_array($videos)) {
				writevideo($video["name"], $video["description"], $video["created"], $video["videolink"]);
			}
			?><div style="height: 60px;"></div><?
		}
	?>
	
	<div style="height: 30px;"></div>

</body>