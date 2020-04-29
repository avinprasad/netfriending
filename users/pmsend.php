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
<title>Private Messages Send Message</title>
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
			<h4>Send Private Message</h4>
			<a href="pm.php">Back To Inbox</a>
			<p>You are allowed to send private messages here, but please read the terms of agreements before you are 
			confident. In the recepients box please use comma "," to separate each of your targeted receiver, you 
			can only send up to three people at once.</p>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$icon = $_POST['icon'];
			$user = $_POST['username'];
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message), array(4, $user));
			include("../texteditor.php");
			list($title, $message, $user) = $finalcontent;
						
			//all required fields filled?
			if ($message == "" or $title == "" or $user == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//sorting the mutiple recepients
			$user = str_replace(" ", "", $user);
			$users = explode(",", $user);
			$users1 = $users[0];
			$users2 = $users[1];
			$users3 = $users[2];
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username = '$users1'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_4; $user = ""; $invalid = true;}
			
			if ($users2 != ""){
			$read = mysql_query("SELECT * FROM userinfo WHERE username = '$users2'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_4; $user = ""; $invalid = true;}}
			
			if ($users3 != ""){
			$read = mysql_query("SELECT * FROM userinfo WHERE username = '$users3'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_4; $user = ""; $invalid = true;}}}
			
			if ($invalid == false)
			{$insert = mysql_query("INSERT INTO privatemessages(username, user, entryicon, entrytitle, entrycontent, entrydate, type, flag)	VALUES('$users1', '$username', '$icon', '$title', '$message', '$datetime', 'inbox', 'unread')");
			$allusers[] = $users1;
			if ($users2 != "")
			{$insert1 = mysql_query("INSERT INTO privatemessages(username, user, entryicon, entrytitle, entrycontent, entrydate, type, flag) VALUES('$users2', '$username', '$icon', '$title', '$message', '$datetime', 'inbox', 'unread')");
			$allusers[] = $users2;} else {$insert1 = true;}
			if ($users3 != "")
			{$insert2 = mysql_query("INSERT INTO privatemessages(username, user, entryicon, entrytitle, entrycontent, entrydate, type, flag) VALUES('$users3', '$username', '$icon', '$title', '$message', '$datetime', 'inbox', 'unread')");
			$allusers[] = $users3;} else {$insert2 = true;}
			//send emails to notify:
			$entrycontent = $message;
			$textrcondition = 1;
			$emailcondition = true;
			include("../textreplacer.php");
			foreach ($allusers as $noty_user) {
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$noty_user'");
			$r = mysql_fetch_array($read);
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$noty_user'");
			$r2 = mysql_fetch_array($read2);
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r1 = mysql_fetch_array($read1);
			//whether or not to send notification email:
			$notifications = explode(",", $r2['notification']);
			if ($notifications[1] == 1) {
			$imgsize = getimagesize("images/photo/photo{$r1['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			//below is email:
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "$displayname ($username) has sent you a new message!";
			$textfile = file_get_contents("../admin/emails/pmnotifier.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%photo%", $r1['photo'], $body);
			$body = str_replace("%width%", round($width), $body);
			$body = str_replace("%height%", round($height), $body);
			$body = str_replace("%dname%", $displayname, $body);
			$body = str_replace("%uname%", $username, $body);
			$body = str_replace("%message%", $entrycontent, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}}
			//Enter into sender's own db:
			$users = implode(",", $allusers);
			$insert3 = mysql_query("INSERT INTO privatemessages(username, user, entryicon, entrytitle, entrycontent, entrydate, type, flag) VALUES('$username', '$users', '$icon', '$title', '$message', '$datetime', 'outbox', 'unread')");
			$icon = ""; $user = ""; $title = ""; $message = ""; $datetime = "";
			
			if($insert === true && $insert1 === true && $insert2 === true && $insert3 === true)
			{header("location: confirm.php?confirm=pm");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Recall Data from DB:
			$id = $_GET['id'];
			$user = $_GET['user'];
			if ($id != "") {$read = mysql_query("SELECT * FROM privatemessages WHERE id='$id'");
			$r = mysql_fetch_array($read);
			$icon = $r['entryicon'];
			$user = $r['user'];
			$title = $r['entrytitle'];
			$message = "[quote]{$r['entrycontent']}[/quote]\n";}
			if ($icon == "") {$icon = "xx";}
			
			//Set values for form post:
			$form = "sendpm";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic, $img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx", $icon);?> />
			<img src="images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown", $icon);?> />
			<img src="images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup", $icon);?> />
			<img src="images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation", $icon);?> />
			<img src="images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question", $icon);?> />
			<img src="images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp", $icon);?> />
			<img src="images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>
			<label>Recepients</label>
			<input name="username" value="<?echo $user;?>" type="text" size="30" maxlength="100" />
			<label>Subject</label>
			<input name="title" value="<?echo $title;?>" type="text" size="30" maxlength="40" />
			<label>Entry Content</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $message;?></textarea>
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