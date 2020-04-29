<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Verify My Account</title>
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
			
			<a name="User Panel"></a>
			<h1>User Panel</h1>
			<h3>Verify My Account</h3>
			<p>Please enter your email address and the verification code that was send to you through E-mail Below:</p>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			//If by url:
			if (isset($_GET['username']) && isset($_GET['email']) && isset($_GET['code'])) {
			$username = $_GET['username'];
			$email = $_GET['email'];
			$activation = $_GET['code'];
			$_POST['submit'] = true; $urlact = true;}
			
			if (isset($_POST['submit']) or isset($_POST['resend']) or isset($_POST['changeemail'])) {
			if ($urlact != true) {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$activation = $_POST['activation'];}
			
			$entrycontent = array(array(2, $username), array(2, $email), array(2, $activation));
			include("../texteditor.php");
			list($username, $email, $activation) = $finalcontent;
			
			if (isset($_POST['changeemail'])) {
			//all required fields filled?
			if ($username == "" or $email == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_25; $username = ""; $invalid = true;}
			else {
			
			//account activated already?
			$read = mysql_query("SELECT activation FROM userinfo WHERE username='$username'");
			$r = mysql_fetch_array($read);
			if ($r['activation'] == "")
			{echo $account_activated; $username = ""; $email = ""; $activation = ""; $invalid = true;}
			else {
			
			//email right format, valid?
			$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
			if(!eregi($regex, $email))
			{echo $email_invalid_25; $email = ""; $invalid = true;}
			else {
			
			//email exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE email='$email'");
			if (mysql_num_rows($read) > 0)
			{echo $email_inuse; $email = ""; $invalid = true;}}}}}}
			
			elseif (isset($_POST['resend'])) {
			//all required fields filled?
			if ($username == "" or $email == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_25; $username = ""; $invalid = true;}
			else {
			
			//account activated already?
			$read = mysql_query("SELECT activation FROM userinfo WHERE username='$username'");
			$r = mysql_fetch_array($read);
			if ($r['activation'] == "")
			{echo $account_activated; $username = ""; $email = ""; $activation = ""; $invalid = true;}
			else {
			
			//email right format, valid?
			$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
			if(!eregi($regex, $email))
			{echo $email_invalid_25; $email = ""; $invalid = true;}
			else {
			
			//email & activation exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE email='$email'");
			if (mysql_num_rows($read) < 1)
			{echo $email_notexist_25; $email = ""; $invalid = true;}}}}}}
			
			else {
			//all required fields filled?
			if ($username == "" or $email == "" or $activation == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_25; $username = ""; $invalid = true;}
			else {
			
			//account activated already?
			$read = mysql_query("SELECT activation FROM userinfo WHERE username='$username'");
			$r = mysql_fetch_array($read);
			if ($r['activation'] == "")
			{echo $account_activated; $username = ""; $email = ""; $activation = ""; $invalid = true;}
			else {
			
			//email right format, valid?
			$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
			if(!eregi($regex, $email))
			{echo $email_invalid_25; $email = ""; $invalid = true;}
			else {
			
			//email & activation exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE email='$email' AND activation='$activation'");
			if (mysql_num_rows($read) < 1)
			{echo $emailactivation_noexist; $email = ""; $activation = ""; $invalid = true;}}}}}}
			
			if ($invalid == false)
			{if (isset($_POST['changeemail'])) {
			//Activation Code:
			$code_dup = true;
			while ($code_dup == true) {
			$alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$activationcode = substr(str_shuffle($alphanum), 0, 15);
			//Code already exist? Regenerate:
			$read = mysql_query("SELECT * FROM userinfo WHERE activation='$activationcode'");
			if (mysql_num_rows($read) > 0)
			{$code_dup = true;} else {$code_dup = false;}}
			
			//change new email:
			//update database for new email:
			$update = mysql_query("UPDATE userinfo SET email='$email', activation='$activationcode' WHERE username='$username'");
			
			//send email for verification:
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username' AND email='$email'");
			$r = mysql_fetch_array($read);
			
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "Your NetFriending Account Registeration!";
			$textfile = file_get_contents("../admin/emails/activation.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%username%", $r['username'], $body);
			$body = str_replace("%email%", $r['email'], $body);
			$body = str_replace("%code%", $activationcode, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			if (mail($to, $subject, $body, $headers)) {echo $email_successful_25_1;} 
			else {echo $email_unsuccessful_25_1;}}
			
			elseif (isset($_POST['resend'])) {
			//send new verification email:
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username' AND email='$email'");
			$r = mysql_fetch_array($read);
			
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "Your NetFriending Account Registeration!";
			$textfile = file_get_contents("../admin/emails/activation.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%username%", $r['username'], $body);
			$body = str_replace("%email%", $r['email'], $body);
			$body = str_replace("%code%", $r['activation'], $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			if (mail($to, $subject, $body, $headers)) {echo $email_successful_25_2;} 
			else {echo $email_unsuccessful_25_2;}}
			
			else {
			//Update database to activate account:
			$update = mysql_query("UPDATE userinfo SET activation='' WHERE email='$email'");
			
			//send email to notify
			$read = mysql_query("SELECT * FROM userinfo WHERE email='$email'");
			$r = mysql_fetch_array($read);
			//below is email
			$to = "{$r['firstname']} {$r['lastname']} <$email>";
			$subject = "Your NetFriending Account Registeration!";
			$textfile = file_get_contents("../admin/emails/regnotifier.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%username%", $r['username'], $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			$username = ""; $email = ""; $activation = "";
			
			if (mail($to, $subject, $body, $headers)) {echo $email_successful_25;} 
			else {echo $email_unsuccessful_25;}}}}?>
			
			<form name="activation" action="" method="post">
			<p>
			<label>Username</label>
			<input name="username" value="<?php echo $username;?>" type="text" size="30" maxlength="20" />
			<label>E-mail</label>
			<input name="email" value="<?php echo $email;?>" type="text" size="30" maxlength="50" />
			<label>Activation Code</label>
			<input name="activation" value="<?php echo $activation;?>" type="text" size="30" maxlength="15" />
			<br /><br />
			<input class="button" name="submit" type="submit" />
			<input class="button" name="resend" type="submit" value="Resend E-mail" />
			<input class="button" name="changeemail" type="submit" value="Change New E-mail" />
			</p>
			</form>
			
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
