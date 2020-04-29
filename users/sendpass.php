<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Forgot My Password</title>
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
			<h3>Forgot My Password</h3>
			<p>Please fill in the information below correctly inorder to get the password sent to you!</p>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$username = $_POST['username'];
			$email = $_POST['email'];
			
			$entrycontent = array(array(2, $username), array(2, $email));
			include("../texteditor.php");
			list($username, $email) = $finalcontent;
			
			//all required fields filled?
			if ($username == "" or $email == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			if (mysql_num_rows($read) < 1)
			{echo $user_notexist_5; $username = ""; $invalid = true;}
			else {
			
			//email right format, valid?
			$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
			if(!eregi($regex, $email))
			{echo $email_invalid_5; $email = ""; $invalid = true;}
			else {
			
			//email exists?
			$read = mysql_query("SELECT email FROM userinfo WHERE username='$username'");
			$row = mysql_fetch_array($read);
			if ($row['email'] != $email)
			{echo $email_notexist_notusername; $email = ""; $invalid = true;}
			else {
			
			//account activated yet??
			$read = mysql_query("SELECT activation FROM userinfo WHERE username='$username'");
			$row = mysql_fetch_array($read);
			if ($row['activation'] != "")
			{echo $account_inactive; $username = ""; $email = ""; $invalid = true;}}}}}
			
			if ($invalid == false)
			{//generate new password
			$alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password = substr(str_shuffle($alphanum), 0, 8);
			$md5pass = md5($password);
			//save new password into database
			$update = mysql_query("UPDATE userinfo SET password='$md5pass' WHERE username='$username'");
			//send email to notify
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			$r = mysql_fetch_array($read);
			//below is email
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "Your new NetFriending Password!";
			$textfile = file_get_contents("../admin/emails/sendpass.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%password%", $password, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			$username = ""; $email = "";
			
			if (mail($to, $subject, $body, $headers) && $update === true) 
			{echo $email_successful_5;} 
			else 
			{echo $email_unsuccessful_5;}}}?>
			
			<form name="sendpass" action="" method="post">
			<p>
			<label>Username</label>
			<input name="username" value="<?php echo $username;?>" type="text" size="30" maxlength="20" />
			<label>E-mail</label>
			<input name="email" value="<?php echo $email;?>" type="text" size="30" maxlength="50" />
			<br /><br />
			<input class="button" name="submit" type="submit" />
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
