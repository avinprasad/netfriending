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
<title>Edit My Preferences</title>
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
			<h4>Customize my Preferences</h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])) {
			$theme = $_POST['theme'];
			$ens = array($_POST['en0'], $_POST['en1'], $_POST['en2'], $_POST['en3'], $_POST['en4']);
			$dateformat = $_POST['dateformat'];
			$timeformat = $_POST['timeformat'];
			
			$lastmoddate = date("Y-m-d H:i:s");
			foreach ($ens as $en) {if ($en == "") {$en = 0;} $ens1[] = $en;}
			$ens2 = implode(",", $ens1);
			
			$update = mysql_query("UPDATE usercus SET theme='$theme', notification='$ens2', dateformat='$dateformat', timeformat='$timeformat' WHERE username='$username'");
			$theme = "";
			
			if ($update === true)
			{header("location: confirm.php?confirm=preferenceedit");}
			else
			{echo "<p>$db_error</p>";}}
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r = mysql_fetch_array($read);
			if ($read === false)
			{echo "<p>$db_error</p>";}?>
			
			<form name="editpreference" action="" method="post">			
			<p>			
			<label>Tab Themes</label>
			<select name="theme" size="1">
			<?php $themes = file("themes.txt", FILE_IGNORE_NEW_LINES);
			foreach ($themes as $theme) 
			{$themes1 = explode(",", $theme);
			echo "<option value=\"{$themes1[0]}\"";
			if ($themes1[0] == $r['theme']) echo " selected=\"selected\"";
			echo ">{$themes1[1]}</option>";}?>
			</select>
			<label>Date Format</label>
			<select name="dateformat" size="1">
			<?php echo "<option value=\"\">MMM DD, YYYY (Default)</option>";
			$dates = file("dateformat.txt", FILE_IGNORE_NEW_LINES);
			foreach ($dates as $date) {$date = explode(",", $date);
			if ($date[0] == $r['dateformat']) {$select1 = " selected";}
			echo "<option value=\"{$date[0]}\"$select1>{$date[1]}</option>";
			$select1 = "";}?>
			</select>
			<label>Time Format</label>
			<select name="timeformat" size="1">
			<?php echo "<option value=\"\">HH:MM A/PM (Default)</option>";
			$times = file("timeformat.txt", FILE_IGNORE_NEW_LINES);
			foreach ($times as $time) {$time = explode(",", $time);
			if ($time[0] == $r['timeformat']) {$select1 = " selected";}
			echo "<option value=\"{$time[0]}\"$select1>{$time[1]}</option>";
			$select1 = "";}?>
			</select>
			<label>E-mail Notifications</label>
			<?php function check($val){if ($val == 1) {echo " checked";}}
			$e_notifications = explode(",", $r['notification']);?>
			<table border="0">
			<tr height="25px"><td width="8%"><input type="checkbox" name="en0" value="1"<?php check($e_notifications[0]);?> /></td><td>Birthday Notifications</td></tr>
			<tr height="25px"><td><input type="checkbox" name="en1" value="1"<?php check($e_notifications[1]);?> /></td><td>Personal Messages, Friends Requests</td></tr>
			<tr height="25px"><td><input type="checkbox" name="en2" value="1"<?php check($e_notifications[2]);?> /></td><td>Comments on your Photos, Blog &amp; Guestbook</td></tr>
			<tr height="25px"><td><input type="checkbox" name="en3" value="1"<?php check($e_notifications[3]);?> /></td><td>News Letter &amp; Your Birthday Congrats</td></tr>
			<tr height="25px"><td><input type="checkbox" name="en4" value="1"<?php check($e_notifications[4]);?> /></td><td>Other Notifications</td></tr>
			</table>
			<br />
			<input class="button" name="submit" type="submit" />		
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