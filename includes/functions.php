<?php
function writevideo($name, $description, $created, $link)
{
	$vimeo_id = explode("/", $link);
	$vimeo_id = $vimeo_id[3];
	
	$original_width = 1024;
	$width = 960;
	$height = 576 / $original_width * $width;

	?>
	<h3><?=$name?></h3>
	<small>Added on <?=date("d-m-y",$created);?></small>
	<p><?=$description?></p>
	
		<iframe src="http://player.vimeo.com/video/<?=$vimeo_id?>" width="<?=$width ?>" height="<?=$height ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		
		<!--
	
		<object width="<?=$width ?>" height="<?=$height ?>">
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="always" />
			<param name="movie" value="
				http://vimeo.com/moogaloop.swf?clip_id=<?=$vimeo_id?>&amp;
				server=vimeo.com&amp;
				hd_off=1&amp;
				show_title=0&amp;
				show_byline=0&amp;
				show_portrait=0&amp;
				color=00ADEF&amp;
				fullscreen=1"/>
			<embed src="
				http://vimeo.com/moogaloop.swf?clip_id=<?=$vimeo_id?>&amp;
				server=vimeo.com&amp;
				hd_off=1&amp;
				show_title=0&amp;
				show_byline=0&amp;
				show_portrait=0&amp;
				color=00ADEF&amp;
				fullscreen=1"
				type="application/x-shockwave-flash"
				allowfullscreen="true"
				allowscriptaccess="always"
				width="<?=$width ?>"
				height="<?=$height ?>">
			</embed>
		</object>
		
		-->
		
			<?
}
function auth_client() {
	if (mysql_num_rows(getclientbypassword($_POST['password'])) == 1) {
		//If entering the password actually resulted in a client being found, get some client details.
		$clientdetails = getclientbypassword($_POST['password']);
		$clientdetails = mysql_fetch_array($clientdetails);
		if ($_POST['password'] == $clientdetails['password']) {
			$_SESSION['clientname'] = $clientdetails['name'];
			$_SESSION['client_id'] = $clientdetails['client_id'];
			$_SESSION['password'] = $clientdetails['password'];
		}
	} elseif(isset($_SESSION['password'])) {
		//If there was no password entered, let's check the session for results
		$clientdetails = getclientbypassword($_SESSION['password']);
		$clientdetails = mysql_fetch_array($clientdetails);
		if ($_POST['password'] == $clientdetails['password']) {
			$_SESSION['clientname'] = $clientdetails['name'];
			$_SESSION['client_id'] = $clientdetails['client_id'];
			$_SESSION['password'] = $clientdetails['password'];
		}
	} else {
		//And finally, let's print the form
		?>
		<h2>Please enter the password that was given to you.</h2>
		<p>This password is for your consideration. I ask you to use this password within your company only. The videos on these pages may be altered or removed at any time and must not be linked to. This system is built to aid in previewing videos and is not to be used for final delivery. All activity is logged.</p>
		<?php
			if(isset($_POST['password'])){
				echo "<p>Incorrect password.</p>";
			}
		?>
		<div id="enterpassword">
			<form action="index.php" method="post" accept-charset="utf-8">
			<input type="password" name="password" value="" id="password">		
			<input type="submit" value="Continue &rarr;">
			</form>
		</div>
	<?
		exit();
	}
}
function auth_admin() {
	$authed_session = getsetting('authed_session');
	if (session_id() == $authed_session) {
		return true;
	} else {
		$adminpassword = getsetting('adminpassword');
	
		//If password is entered correctly, set auth to true.
		if (isset($_POST['password']) AND $_POST['password'] == "$adminpassword") {
			editsetting('authed_session',session_id());
			return true;
		}

		if (getsetting('authed_session') != session_id()) {
			//Print the form
			?>
		<h2>Enter password to continue...</h2>
		<form action="index.php" method="post" accept-charset="utf-8">
		<input type="password" name="password" value="" id="password">		
		<p><input type="submit" value="Continue &rarr;"></p>
		</form>
	<?php
		return false;
		}
	}
}

?>