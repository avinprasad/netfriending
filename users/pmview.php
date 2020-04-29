<?php
ob_start();
session_start();
require_once ("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Private Messages View</title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">

	<!--header -->
	<div id="header">				
		
		<?php include("../header.html")?>
			
		<!-- Menu Tabs -->
		<?php include("../menu.php")?>
		
	</div>	
				
	<!-- content-wrap starts here -->
	<div id="content-wrap">		
											
	<div class="headerphoto"></div>
		
		<div id="sidebar" >							
		<?php include("../leftmenu.html")?>
		</div>
			
		<div id="main">
			
			<?php if (!checkLoggedin()) {require("login.php");} else {
			include("usernav.php");?>
			<h4>View Private Messages</h4>
			<a href="pm.php">Back To Inbox</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$id = $_GET['id'];
			mysql_query("UPDATE privatemessages SET flag='read' WHERE id='$id' AND username='$username'");
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM privatemessages WHERE id='$id' AND username='$username'");
			$r = mysql_fetch_array($read);
			//sorting the mutiple recepients
			$users = explode(",", $r['user']);
			$i = 0;
			foreach ($users as $user) {
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			$usernames[$i] = $r1['username'];
			$displaynames[$i] = $r1['displayname'];
			$photos[$i] = $r1['photo'];
			$i ++;}
			$dis_func = 0; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$entrycontent = $r['entrycontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			
			//Delete Message:
			if(isset($_POST['delete'])){
			$delete = mysql_query("DELETE FROM privatemessages WHERE id='$id' AND username='$username'");
			if($delete === true)
			{header("location: confirm.php?confirm=pm_delete&pm={$r['type']}");}
			else
			{echo"<p>$db_error</p>";}}
			//Mark Unread:
			if(isset($_POST['unread'])){
			$insert = mysql_query("UPDATE privatemessages SET flag='unread' WHERE id='$id' AND username='$username'");
			if($insert === true)
			{header("location: confirm.php?confirm=pm_unread&pm={$r['type']}");}
			else
			{echo"<p>$db_error</p>";}}?>
			<table align=center cellpadding="0" cellspacing="1" width="100%" border="1" bordercolor="#000000" style="border-collapse: collapse"><tr height="25px"><td width="100%" align=left background="images/themes/<?php echo $row['theme'];?>_gradient.gif">&nbsp;&nbsp;&nbsp;<img src="images/<?php echo $r['entryicon'];?>.gif"></td></tr></table>
			<div style="border: 1px solid black; border-top: none;"  id="views">
			<table align=center cellpadding="0" cellspacing="1" width="100%">
			<tr><td><h1><?php echo $r['entrytitle'];?></h1></td></tr>
			<tr><td><p>
			<?php foreach ($photos as $foto) {
			$imgsize = getimagesize("images/photo/photo$foto.jpg");
			$width = $imgsize[0]; $height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			echo "<img src=\"/users/images/photo/photo$foto.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\">&nbsp;&nbsp;";}
			if ($r['type'] == "outbox") {echo "<br />To:";}
			else {echo "<br />From:";}
			$read = 0;
			foreach ($usernames as $user) {if ($read != 0) {echo ", ";}
			echo " <b><a href=\"profiles.php?user=$user\">{$displaynames[$read]} ($user)</a></b>";
			$read ++;}?></p></td></tr>
			<tr><td><p><?php echo $entrycontent;?></p></td></tr>
			<tr><td><p class="post-footer align-right">
			<span class="date"><?php echo $date;?></span>
			</p></td></tr>
			</table>
			</div>
			<script type="text/javascript" src="../texteditor.js"></script>
			<form name="pmaction" action="" method="post">
			<p align=center>
			<?php if ($r['type'] == "outbox") {$button = "Send";}
			else {$button = "Reply";}?>
			<input class="button" name="reply" type="button" value="<?php echo $button;?>" onclick="javascript: location='pmsend.php?id=<?php echo $r['id'];?>';" />
			<input class="button" name="delete" type="submit" value="Delete" onClick="javascript: return checkDelete()" />
			<input class="button" name="unread" type="submit" value="Mark Unread" />
			</p>
			</form>
			<?php }?>
			
		</div>	
			
		<div id="rightbar">
		<?php include("../rightmenu.html")?>
		</div>			
			
	<!-- content-wrap ends here -->		
	</div>

<!-- footer starts here -->	
<div id="footer">
	
	<div class="footer-left">
	<?php include("../footer-left.html")?>
	</div>
	
	<div class="footer-right">
	<?php include("../footer-right.html")?>
	</div>
	
</div>
<!-- footer ends here -->
	
<!-- wrap ends here -->
</div>

</body>
</html>