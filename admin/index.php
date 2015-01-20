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
				$('#newclient').parent().prev().html("<img src='ajax-loader.gif'>").fadeIn();
				$.ajax({
					type: "POST",
					url: "process.php",
					data: $(this.form).serialize(),
					success: function(msg){
						if (msg != 1) {
							alert ("Error.");
						} else {
							$('#newclient').parent().prev().hide().html("<span class='success'>Success!</span>").fadeIn();
						}
					}
				});
			});
		});
	</script>
</head>
	<body>
	<div align="center">
		<img src="../FP_Logo.jpg" id="fp_logo" width="210" height="210" alt="Logo" />
		<img src="../Header.jpg" width="960" height="100" alt="Video Preview Robot" />
	</div>
<?php
	connectmysql();
	if(!auth_admin()){
		exit();
	}
	
	?>

	<h2>List of clients <a id="refreshbutton" class="success" href="index.php">Refresh</a> <a id="refreshbutton" class="success" href="../">To Website</a></h2>
	
	<!--<p>The edit, add and delete functions all work, but you have to click refresh to see the changes. Don't try to unnecessarily hammer the system, as you <i>will</i> be able to break it.</p>-->
	
	<table id="list" width="960"
       border="0"
       cellpadding="5"
       align="center"
       bgColor="white">

<!-- Row <?=$rowcounter=1 ?> -->
<tr>
<th width="10%" align="center" valign="middle">
Name
</th>
<th width="10%" align="center" valign="middle">
Created on
</th>
<th width="10%" align="center" valign="middle">
Number of videos
</th>
<th width="15%" align="center" valign="middle">
Password
</th>
<th width="10%" align="center" valign="middle">

</th>
<th width="10%" align="center" valign="middle">

</th>
</tr>

	<?php
	//PRINT LIST OF CLIENTS
$clientlist = getclients();
while ($row = mysql_fetch_assoc($clientlist)) {?>
<!-- Row <?=$rowcounter=$rowcounter+1 ?> -->
<form action="process.php" method="post" class="editform" accept-charset="utf-8">
<input type="hidden" name="process" value="editclient"/>
<input type="hidden" name="clientid" value="<?=$row["client_id"]; ?>"/>
<tr>
<td align="center" valign="middle">
	<p><a href="videos.php?client=<?=$row["client_id"]; ?>" class="clientbutton"><?=$row["name"]; ?></a></p>
	<p><input type="input" class="editform" name="newname" value="<?=$row["name"]; ?>"></p>
</td>
<td align="center" valign="middle">
<?php
	echo date("d-m-y", $row["created"]);
?>
</td>
<td align="center" valign="middle">
	<?php
		echo mysql_num_rows(getvideos($row["client_id"]))
	?>
</td>
<td align="center" valign="middle">
		<?=$row["password"]; ?><br />
<input type="input" class="editform" name="newpassword" value="<?=$row["password"]; ?>">
</td>
<td align="center" valign="middle">
	<input type="submit" class="button" value="Save"/>
</form>
</td>
<td align="center" valign="middle">
<form action="process.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="process" value="deleteclient"/>
	<input type="hidden" name="clientid" value="<?=$row["client_id"]; ?>"/>
	<input type="submit" class="button" value="Delete"/>
</form>
</td>
</tr>    
<?php }
	//STOP PRINTING
?>
<form action="process.php" method="post" accept-charset="utf-8">
<input type="hidden" name="process" value="newclient"/>

<!-- Row <?=$rowcounter=$rowcounter+1 ?> -->
<tr>
<td align="center" valign="middle">
<input type="input" name="name" value="" id="name">	
</td>
<td align="center" valign="middle">
<?php
	echo date("d-m-y", time());
?>
</td>
<td align="center" valign="middle">

</td>
<td align="center" valign="middle">
<input type="input" name="password" value="" id="password">
</td>
<td align="center" valign="middle">

</td>
<td align="center" valign="middle">
<input type="submit" id="newclient" class="button" value="Add new client &rarr;">
</td>
</tr>
</form>
</table>

</body>