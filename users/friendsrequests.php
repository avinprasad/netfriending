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
<?php //Get Page Type, e.g. add, delete, accept?:
$page = $_GET['page'];
//Get user, in relationship with username:
$user = strtolower($_GET['user']);
require("../mysqlconnection.php");
$read1 = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
$r1 = mysql_fetch_array($read1);
$dname = $r1['displayname'];?>
<title>Friend Request</title>
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
			<h4>Friend Request</h4>
			<a href="profiles.php?user=<?php echo $user;?>">View <?php echo $dname;?>'s Profile</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if ($page == "add") {//start add**************************************************************************
			
			if(isset($_POST['submit'])){
			$datetime = date("Y-m-d H:i:s");
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='{$r1['username']}'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist; $invalid = true;}
			else {
			
			//is the user adding himself/herself?
			if ($r1['username'] == $username)
			{echo $add_yourself; $invalid = true;}
			else {
			
			//user in the friend list yet?
			$read = mysql_query("SELECT * FROM friendlist WHERE username='$username' AND user='{$r1['username']}'");
			if (mysql_num_rows($read) > 0)
			{echo $add_duplicate; $invalid = true;}}}
			
			if ($invalid == false)
			{$insert = mysql_query("INSERT INTO friendlist(username, user, status, date) VALUES('$username', '{$r1['username']}', 'pending', '$datetime')");
			//send emails to notify:
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r2 = mysql_fetch_array($read2);
			$read3 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r3 = mysql_fetch_array($read3);
			//whether or not to send notification email:
			$notifications = explode(",", $r3['notification']);
			if ($notifications[1] == 1) {
			$imgsize = getimagesize("images/photo/photo{$r2['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			//below is email:
			$to = "{$r1['firstname']} {$r1['lastname']} <{$r1['email']}>";
			$subject = "$displayname ($username) has sent you a NetFriending friend request!";
			$textfile = file_get_contents("../admin/emails/frsendnotifier.txt");
			$body = str_replace("%fname%", $r1['firstname'], $textfile);
			$body = str_replace("%lname%", $r1['lastname'], $body);
			$body = str_replace("%photo%", $r2['photo'], $body);
			$body = str_replace("%width%", round($width), $body);
			$body = str_replace("%height%", round($height), $body);
			$body = str_replace("%dname%", $displayname, $body);
			$body = str_replace("%uname%", $username, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}
			//if declined the request previously...delete it:
			$read = mysql_query("SELECT * FROM friendlist WHERE username='{$r1['username']}' AND user='$username' AND status='declined'");
			if (mysql_num_rows($read) > 0)
			{$insert1 = mysql_query("DELETE FROM friendlist WHERE username='{$r1['username']}' AND user='$username' AND status='declined'");} else {$insert1 = true;}
			$user = ""; $datetime = "";
			
			if ($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=friendlist_add");}
			else
			{echo"<p><b>$db_error</b></p>";}}}
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM usercus WHERE username='{$r1['username']}'");
			$r2 = mysql_fetch_array($read);
			$imgsize = getimagesize("images/photo/photo". $r2['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}?>
			
			<p><img src="images/photo/photo<?php echo $r2['photo'];?>.jpg" width="<?php echo round($width);?>" height="<?php echo round($height);?>" /></p>
			<p>Are you sure that you want to send a friend request to the user below?</p>
			<p><b><a href="profiles.php?user=<?php echo $r1['username']?>"><?php echo "{$r1['displayname']} ({$r1['username']})";?></a></b></p>
			<form name="friendrequest" action="" method="post">
			<p align=center>
			<input class="button" name="submit" type="submit" value="Yes, ofcourse!" />		
			<input class="button" type="button" value="No, let me reconsider!" onclick="location='javascript: history.go(-1)'" />
			</p>
			</form>
			<?php }//add finished*************************************************************************************
			elseif ($page == "delete") {//start delete****************************************************************
			
			if(isset($_POST['submit'])){
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='{$r1['username']}'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist; $invalid = true;}
			else {
			
			//is user in the friend list?
			$read = mysql_query("SELECT * FROM friendlist WHERE username='$username' AND user='{$r1['username']}'");
			if (mysql_num_rows($read) == 0)
			{echo $delete_notin; $invalid = true;}}
			
			if ($invalid == false)
			{$insert = mysql_query("DELETE FROM friendlist WHERE username='$username' AND user='{$r1['username']}'");
			//if the request was accepted...
			$read = mysql_query("SELECT * FROM friendlist WHERE username='{$r1['username']}' AND user='$username' AND status='accepted'");
			if (mysql_num_rows($read) > 0)
			{$insert1 = mysql_query("UPDATE friendlist SET status='pending' WHERE username='{$r1['username']}' AND user='$username'");} else {$insert1 = true;}
			$user = "";
			
			if ($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=friendlist_delete");}
			else
			{echo"<p><b>$db_error</b></p>";}}}
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM usercus WHERE username='{$r1['username']}'");
			$r2 = mysql_fetch_array($read);
			$imgsize = getimagesize("images/photo/photo". $r2['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}?>
			
			<p><img src="images/photo/photo<?php echo $r2['photo'];?>.jpg" width="<?php echo round($width);?>" height="<?php echo round($height);?>" /></p>
			<p>Are you sure that you want to delete the user below from your friendlist?</p>
			<p><b><a href="profiles.php?user=<?php echo $r1['username']?>"><?php echo "{$r1['displayname']} ({$r1['username']})";?></a></b></p>
			<form name="friendrequest" action="" method="post">
			<p align=center>
			<input class="button" name="submit" type="submit" value="Yes, ofcourse!" />		
			<input class="button" type="button" value="No, let me reconsider!" onclick="location='javascript: history.go(-1)'" />
			</p>
			</form>
			<?php }//finish delete************************************************************************************
			else {//start action**************************************************************************************
			
			if (isset($_POST['accept'])){//accept#####################################################################
			$datetime = date("Y-m-d H:i:s");
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='{$r1['username']}'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist; $invalid = true;}
			else {
			
			//did user had a request?
			$read = mysql_query("SELECT * FROM friendlist WHERE username='{$r1['username']}' AND user='$username' AND status='pending'");
			if (mysql_num_rows($read) == 0)
			{echo $request_notsent; $invalid = true;}}
			
			if ($invalid == false)
			{$insert = mysql_query("UPDATE friendlist SET status='accepted' WHERE username='{$r1['username']}' AND user='$username'");
			//send emails to notify:
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r2 = mysql_fetch_array($read2);
			$read3 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r3 = mysql_fetch_array($read3);
			//whether or not to send notification email:
			$notifications = explode(",", $r3['notification']);
			if ($notifications[1] == 1) {
			$imgsize = getimagesize("images/photo/photo{$r2['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			//below is email:
			$to = "{$r1['firstname']} {$r1['lastname']} <{$r1['email']}>";
			$subject = "$displayname ($username) has accepted your NetFriending friend request!";
			$textfile = file_get_contents("../admin/emails/fracceptnotifier.txt");
			$body = str_replace("%fname%", $r1['firstname'], $textfile);
			$body = str_replace("%lname%", $r1['lastname'], $body);
			$body = str_replace("%photo%", $r2['photo'], $body);
			$body = str_replace("%width%", round($width), $body);
			$body = str_replace("%height%", round($height), $body);
			$body = str_replace("%dname%", $displayname, $body);
			$body = str_replace("%uname%", $username, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}
			//if doesnt have a request yet...
			$read = mysql_query("SELECT * FROM friendlist WHERE username='$username' AND user='{$r1['username']}' AND status='pending'");
			if (mysql_num_rows($read) > 0)
			{$insert1 = mysql_query("UPDATE friendlist SET status='accepted' WHERE username='$username' AND user='{$r1['username']}'");}
			else {$insert1 = mysql_query("INSERT INTO friendlist(username, user, status, date) VALUES('$username', '{$r1['username']}', 'accepted', '$datetime')");}
			$user = ""; $datetime = "";
			
			if ($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=friendlist_accepted");}
			else
			{echo "<p><b>$db_error</b></p>";}}}
			
			if(isset($_POST['decline'])){//decline####################################################################
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='{$r1['username']}'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist; $invalid = true;}
			else {
			
			//did user had a request?
			$read = mysql_query("SELECT * FROM friendlist WHERE username='{$r1['username']}' AND user='$username' AND status='pending'");
			if (mysql_num_rows($read) == 0)
			{echo $request_notsent; $invalid = true;}}
			
			if ($invalid == false)
			{$insert = mysql_query("UPDATE friendlist SET status='declined' WHERE username='{$r1['username']}' AND user='$username'");
			//if has a request too...
			$read = mysql_query("SELECT * FROM friendlist WHERE username='$username' AND user='{$r1['username']}' AND status='pending'");
			if (mysql_num_rows($read) > 0)
			{$insert1 = mysql_query("DELETE FROM friendlist WHERE username='$username' AND user='{$r1['username']}' AND status='pending'");} else {$insert1 = true;}
			$user = "";
			
			if ($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=friendlist_declined");}
			else
			{echo"<p><b>$db_error</b></p>";}}}
			
			$read = mysql_query("SELECT * FROM usercus WHERE username='{$r1['username']}'");
			$r2 = mysql_fetch_array($read);
			$imgsize = getimagesize("images/photo/photo". $r2['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			?>
			<p><img src="images/photo/photo<?php echo $r2['photo'];?>.jpg" width="<?php echo round($width);?>" height="<?php echo round($height);?>" /></p>
			<p>Please select an action to either accept or decline the friend request from:</p>
			<p><b><a href="profiles.php?user=<?php echo $r1['username'];?>"><?php echo "{$r1['displayname']} ({$r1['username']})";?></a></b></p>
			<form name="friendrequest" action="" method="post">
			<p align=center>
			<input class="button" name="accept" type="submit" value="Accept!" />
			<input class="button" name="decline" type="submit" value="Decline!" />
			</p>
			</form>
			<?php }//finish action************************************************************************************
			?>
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