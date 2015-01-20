<?php
	session_start();
	include('../includes/functions.php');
	include('../includes/databasefunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
	<title>Video Preview Robot Admin</title>
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script src="../includes/jquery-1.4.2.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$(".editform:input").hide();
			$(":submit[value=Save]").val("Edit");
			
			$(':submit[value=Edit]').toggle(function() {		
				$(this).closest('tr').find(".editform:input").slideDown();
				$(":submit[value=Edit]").not(this).fadeOut();
				$(this).val("Save");
			}, function() {
				$(this).closest('tr').find(".editform:input").slideUp();
				$(":submit[value=Edit]").not(this).fadeIn();
				$(":submit[value=Save]").parent().next().html("<img src='ajax-loader.gif'>").fadeIn();
				$.ajax({
					type: "POST",
					url: "process.php",
					data: $(this.form).serialize(),
					success: function(msg){
						if (msg != 1) {
							alert ("Error.");
						} else {
							$(":submit[value=Save]").parent().next().html("<span class='success'>Success!</span>");
							$(":submit[value=Save]").val("Edit");
						}
					}
				});
				
				
			});
			
			$(':submit[value=Delete]').click(function(event) {
				event.preventDefault();
				if(confirm("Are you sure you want to delete this client and all of their videos?")){
					$(this).parent().parent().parent().fadeOut("slow");
					$.ajax({
						type: "POST",
						url: "process.php",
						data: $(this.form).serialize(),
						success: function(msg){
							if (msg != 1) {
								alert ("Error.");
							}
						}
					});
				}
			});
			
			$('#newclient').click(function(event) {
				event.preventDefault();
				$('#newclient').parent().fadeOut();
				$.ajax({
					type: "POST",
					url: "process.php",
					data: $(this.form).serialize(),
					success: function(msg){
						if (msg != 1) {
							alert ("Error.");
						} else {
							$('#newclient').parent().hide().html("<span class='success'>Success!</span>").fadeIn();
						}
					}
				});
			});
		});
	</script>
	
</head>
<?php
	connectmysql();
	if(!auth_admin()){
		exit();
	}
	
	//Get some client details, like the name.
	$clientdetails = mysql_fetch_assoc(getclient($_GET['client']));
	?>
<body>
	<div align="center">
		<img src="../FP_Logo.jpg" id="fp_logo" width="210" height="210" alt="Logo" />
		<img src="../Header.jpg" width="960" height="100" alt="Video Preview Robot" />
	</div>
	
	<h2>List of videos for client "<?=$clientdetails["name"]; ?>" <a id="refreshbutton" class="success" href="videos.php?client=<?=$_GET['client'] ?>">Refresh</a> <a id="refreshbutton" class="success" href="index.php">Back to list of Clients</a></h2>
	
	<!--<p>The edit, add and delete functions all work, but you have to click refresh to see the changes. Don't try to unnecessarily hammer the system, as you <i>will</i> be able to break it.</p>-->
	
<table id="list" width="960"
       border="0"
       cellpadding="5"
       align="center"
       bgColor="white">

<!-- Row <?=$rowcounter=1 ?> -->
<tr>
<th width="5%" align="center" valign="middle">
Name
</th>
<th width="30%" align="center" valign="middle">
Link
</th>
<th width="5%" align="center" valign="middle">
Created on
</th>
<th width="10%" align="center" valign="middle">
Password
</th>
<th width="30%" align="center" valign="middle">
Description
</th>
<th width="10%" align="center" valign="middle">
Edit
</th>
<th width="10%" align="center" valign="middle">
Delete
</th>
</tr>

<?php
	//Get the videos for specified client
	$videolist = getvideos($_GET['client']);

	while ($row = mysql_fetch_assoc($videolist)) {
	?>

<!-- Row <?=$rowcounter=$rowcounter+1 ?> -->
<form action="process.php" method="post" class="editform" accept-charset="utf-8">
<input type="hidden" name="process" value="editvideo"/>
<input type="hidden" name="videoid" value="<?=$row["video_id"]; ?>"/>

<tr>
<td width="10%" align="center" valign="middle">
	<?=$row["name"]; ?><br />
	<input type="input" class="editform" name="newname" value="<?=$row["name"]; ?>">
</td>
<td width="15%" align="center" valign="middle">
	<?=$row["videolink"]; ?><br />
	<input type="input" class="editform" name="newlink" value="<?=$row["videolink"]; ?>">
</td>
<td width="10%" align="center" valign="middle">
<?php
	echo date("m-d-y", $row["created"]);
?>
</td>
<td width="15%" align="center" valign="middle">
	<?=$row["password"]; ?><br />
	<input type="input" class="editform" name="newpassword" value="<?=$row["password"]; ?>">
</td>
<td width="30%" align="center" valign="middle">
	<?=$row["description"]; ?><br />
	<textarea type="input" class="editform" name="newdescription"><?=$row["description"]; ?></textarea>
</td>
<td width="10%" align="center" valign="middle">
	<input type="submit" class="button" value="Save"/>
</form>
</td>
<td width="10%" align="center" valign="middle">
<form action="process.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="process" value="deletevideo"/>
	<input type="hidden" name="videoid" value="<?=$row["video_id"]; ?>"/>
	<input type="submit" class="button" value="Delete"/>
</form>
</td>
</tr>
<?php }
	//STOP PRINTING
?>
<form action="process.php" method="post" accept-charset="utf-8">
<input type="hidden" name="process" value="newvideo"/>
<input type="hidden" name="clientid" value="<?=$_GET['client']?>"/>
<tr>
<td width="10%" align="center" valign="middle">
	<input type="input" name="name">
</td>
<td width="15%" align="center" valign="middle">
	<input type="input" name="videolink">
</td>
<td width="10%" align="center" valign="middle">
<?php
	echo date("m-d-y", time());
?>
</td>
<td width="15%" align="center" valign="middle">
	<input type="input" name="password"/>
</td>
<td width="30%" align="center" valign="middle">
	<textarea type="input" name="description"></textarea>
</td>
<td width="10%" colspan="2" align="center" valign="middle">
	<input type="submit" id="newclient" class="button" value="Add new video &rarr;">
</td>
</tr>
</form>
</table>
<!--<div id="instructions">
	<h2>Here's what you do</h2>
	<ul>
		<li>Log in to vimeo with these account details:</li>
		<ul>
			<li>E-Mail address: mike@trapdoorproductions.com.au</li>
			<li>Password: jesus1</li>
		</ul>
		<li>Go to upload, select the video file. If the video is for client review only, don't worry about setting the title or description. If it's a public video, you might want to set those details as well.</li>
		<li>Go to privacy, select "hide this video from vimeo.com".</li>
		<li>Go to embedding, select the "preview system" preset.</li>
		<li>When the video is done uploading and converting, go to the video page, and copy the full vimeo link. It should look like this: http://www.vimeo.com/11068066</li>
		<li>Paste this link in the link field on the client video admin page.</li>
		<li>Set name and description for the video, and you're done!</li>
	</ul>
</div>-->
</body>