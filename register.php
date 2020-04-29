<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<title>Register NetFriending</title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">

	<!--header -->
	<div id="header">				
		
		<?php include("header.html")?>
			
		<!-- Menu Tabs -->
		<?php include("menu.php")?>
		
	</div>	
				
	<!-- content-wrap starts here -->
	<div id="content-wrap">		
											
	<div class="headerphoto"></div>
		
		<div id="sidebar" >							
		<?php include("leftmenu.html")?>
		</div>
			
		<div id="main">
			
			<a name="Register for Member"></a>
			<h1>Register for Member</h1>
			<h3>Sign Up Form</h3>
			<p>Please fill in the fields below, and you will become a NetFriending member immediately, all fields are required.</p>
			<?php //Pre-configuration:
			//Require MySQL:
			require("mysqlconnection.php");
			//Require Message:
			include("notification.php");
			
			if (isset($_POST['submit'])){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$gender = $_POST['gender'];
			$birthday = $_POST['birthday'];
			$birthmonth = $_POST['birthmonth'];
			$birthyear = $_POST['birthyear'];
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$dname = $_POST['dname'];
			$timeoffset = $_POST['timeoffset'];
			$location = $_POST['location'];
			$country = $_POST['country'];
			$imgverify = $_POST['imgverify'];
			$signupdate = date("Y-m-d H:i:s");
			$birthdate = "$birthyear-$birthmonth-$birthday";
			
			$entrycontent = array(array(4, $username), array(2, $password), array(4, $email), array(5, $fname), array(5, $lname), array(4, $dname), array(2, $location), array(6, $imgverify));
			include("texteditor.php");
			list($username, $password, $email, $fname, $lname, $dname, $location, $imgverify) = $finalcontent;
						
			//all required fields filled?
			if ($password == "" or $username == "" or $location == "" or $email == "" or $fname == "" or $lname == "" or $dname == "" or $_POST['cpassword'] == "" or $_POST['cemail'] == "" or $_POST['terms'] == "" or $birthday == "" or $birthmonth == "" or $birthyear == "")
			{echo $requiredfields;$invalid = true;}
			else {
			
			//limiting the number of username characters
			if (strlen($username) < 4)
			{echo $username_short; $invalid = true;}
			else {
			
			//invalid characters in username
			if(!eregi("^([_+a-z0-9-])*$",$username))
			{echo $username_invalid; $username = ""; $invalid = true;}
			else {
			
			//username exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE username = '$username'");
			if (mysql_num_rows($read) > 0)
			{echo $username_taken; $username = ""; $invalid = true;}}}
			
			//pass double checked?
			if ($password != $_POST['cpassword'])
			{echo $passwords_diff; $invalid = true;}
			else {
			
			//limiting the number of password characters
			if (strlen($password) < 4)
			{echo $password_short; $invalid = true;}}
			
			//limiting the number of fname characters
			if (strlen($fname) < 3)
			{echo $fname_short; $invalid = true;}
			else {
			
			//invalid characters in fname
			if(!eregi("^([a-z])*$",$fname))
			{echo $fname_invalid; $fname = ""; $invalid = true;}}
			
			//limiting the number of lname characters
			if (strlen($lname) < 3)
			{echo $lname_short; $invalid = true;}
			else {
			
			//invalid characters in lname
			if(!eregi("^([a-z])*$",$lname))
			{echo $lname_invalid; $lname = ""; $invalid = true;}}
			
			//limiting the number of dname characters
			if (strlen($dname) < 3)
			{echo $dname_short; $invalid = true;}
			
			//email double checked?
			if ($email!= $_POST['cemail'])
			{echo $emails_diff; $email = ""; $invalid = true;}
			else {
			
			//email right format, valid?
			$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"."\.([a-z]{2,}){1}$";
			if(!eregi($regex, $email))
			{echo $email_invalid; $email = ""; $invalid = true;}
			else {
			
			//email exists?
			$read = mysql_query("SELECT * FROM userinfo WHERE email = '$email'");
			if (mysql_num_rows($read) > 0)
			{echo $email_inuse; $email = ""; $invalid = true;}}}
			
			//birthdate in the right timeframe?
			if (checkdate($birthmonth, $birthday, $birthyear) == false)
			{echo $birthdate_invalid; $invalid = true;}
			else {
			
			//at least 13 years old?
			$years13ago = strtotime("-13 years");
			$yearsnow = strtotime($birthdate);
			if ($years13ago < $yearsnow)
			{echo $age_tooyoung; $invalid = true;}}
			
			//image verification passed?
			if ($_SESSION['imageverify'] != md5($imgverify))
			{echo $imgverify_failed; $invalid = true;}}
			
			if ($invalid == false)
			{$md5pass = md5($password);
			
			//Activation Code:
			$code_dup = true;
			while ($code_dup == true) {
			$alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$activationcode = substr(str_shuffle($alphanum), 0, 15);
			//Code already exist? Regenerate:
			$read = mysql_query("SELECT * FROM userinfo WHERE activation='$activationcode'");
			if (mysql_num_rows($read) > 0)
			{$code_dup = true;} else {$code_dup = false;}}
			
			$insert1 = mysql_query("INSERT INTO userinfo(username, password, email, gender, firstname, lastname, displayname, birthday, timeoffset, country, location, activation, signupdate) VALUES('$username', '$md5pass', '$email', '$gender', '$fname', '$lname', '$dname', '$birthyear-$birthmonth-$birthday', '$timeoffset', '$country', '$location', '$activationcode', '$signupdate')");
			$insert2 = mysql_query("INSERT INTO usercus(username, displayname) VALUES('$username', '$dname')");
						
			if ($insert1 === true && $insert2 === true)
			{echo $signup_successful;
			
			$to = "$fname $lname <$email>";
			$subject = "Your NetFriending Account Registeration!";
			$textfile = file_get_contents("admin/emails/activation.txt");
			$body = str_replace("%fname%", $fname, $textfile);
			$body = str_replace("%lname%", $lname, $body);
			$body = str_replace("%username%", $username, $body);
			$body = str_replace("%email%", $email, $body);
			$body = str_replace("%code%", $activationcode, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			if (mail($to, $subject, $body, $headers)) {echo $email_successful;} 
			else {echo $email_unsuccessful;}?>
			<body onload="timer=setTimeout('move()',5000)"> 
			<script language="JavaScript"> 
			var time = null 
			function move() { 
			window.location = 'users/activation.php'} 
			</script><?php }
			else {mysql_query("DELETE FROM userinfo WHERE username='$username'");
			mysql_query("DELETE FROM usercus WHERE username='$username'");
			echo $signup_unsuccessful;?>
			<body onload="timer=setTimeout('move()',5000)"> 
			<script language="JavaScript"> 
			var time = null 
			function move() { 
			window.location = 'index.php'} 
			</script><?php 	}
			$username = ""; $password = ""; $email = ""; $gender = ""; $fname = ""; $lname = ""; $dname = ""; $birthday = ""; $birthmonth = ""; $birthyear = ""; $age = ""; $location = ""; $signupdate = ""; $md5pass = ""; $imgverify = "";}}?>
			
			<form name="signup" action="" method="post">
			<script type="text/javascript" src="texteditor.js"></script>			
			<p>			
			<label>Username</label>
			<input name="username" value="<?php echo $username;?>" type="text" size="30" maxlength="20" />
			(permanent, for login use, minimum 4 characters, only alphabets, numbers, hyphens and underscores)
			<label>Password</label>
			<input name="password" type="password" size="30" maxlength="20" />
			(permanent, for login use, minimum 4 characters, same as above, can be changed)
			<label>Confirm Password</label>
			<input name="cpassword" type="password" size="30" maxlength="20" />
			(confirm, please enter the same password for above)
			<label>E-mail</label>
			<input name="email" value="<?php echo $email;?>" type="text" size="30" maxlength="50" />
			(your email address, for login information and further notice, can be changed)
			<label>Confirm E-mail</label>
			<input name="cemail" value="<?php echo $email;?>" type="text" size="30" maxlength="50" />
			(confirm, please enter the same email for above)
			<label>Gender</label>
			<?php if ($gender == "F"){$female = " checked=\"yes\"";} else {$male = " checked=\"yes\"";}?>
			Male<input type="radio" name="gender" value="M"<?php echo $male?> />
			Female<input type="radio" name="gender" value="F"<?php echo $female?> />
			<label>Birthday</label>
			<select name="birthday" size="1">
			<?php echo "<option value=\"\">Day</option>";
			$days = file("users/days.txt");
			foreach ($days as $day) {$day = explode(",", $day);
			if ($day[0] == $birthday) {$select1 = " selected";}
			echo "<option value=\"{$day[0]}\"$select1>{$day[0]}</option>";
			$select1 = "";}?>
			</select>
			<select name="birthmonth" size="1">
			<?php echo "<option value=\"\">Month</option>";
			$months = file("users/months.txt");
			foreach ($months as $month) {$month = explode(",", $month);
			if ($month[0] == $birthmonth) {$select2 = " selected";}
			echo "<option value=\"{$month[0]}\"$select2>{$month[1]}</option>";
			$select2 = "";}?>
			</select>
			<select name="birthyear" size="1">
			<?php echo "<option value=\"\">Year</option>";
			$years = file("users/years.txt");
			foreach ($years as $year) {$year = explode(",", $year);
			if ($year[0] == $birthyear) {$select3 = " selected";}
			echo "<option value=\"{$year[0]}\"$select3>{$year[0]}</option>";
			$select3 = "";}?>
			</select>
			<label>First Name</label>
			<input name="fname" value="<?php echo $fname;?>" type="text" size="30" maxlength="30" />
			(your first name, minimum 3 characters, will not be shown, permanent)
			<label>Last Name</label>
			<input name="lname" value="<?php echo $lname;?>" type="text" size="30" maxlength="30" />
			(your last name, minimum 3 characters, will not be shown, permanent)
			<label>Display Name</label>
			<input name="dname" value="<?php echo $dname;?>" type="text" size="30" maxlength="30" />
			(what you wish to be called, minimum 3 characters, will be shown, permanent)
			<label>Time Zone</label>
			<select name="timeoffset" size="1">
			<?php $timezones = file("users/timezones.txt");
			foreach ($timezones as $timezone) {$timezone = explode("|", $timezone);
			if ($timezone[0] == $timeoffset) {$select4 = " selected";}
			echo "<option value=\"{$timezone[0]}\"$select4>{$timezone[1]}</option>";
			$select4 = "";}?>
			</select>
			(For globe wide convenience, your timezone)
			<label>Country:</label>
			<select name="country" size="1">
			<?php $ipaddress = $_SERVER['REMOTE_ADDR'];
			$explode = explode(".", $ipaddress);
			$ipno = ($explode[0] * 16777216) + ($explode[1] * 65536) + ($explode[2] * 256) + $explode[3];
			$ips = file("ipcountries.txt");
			foreach ($ips as $line) 
			{$explodetxt = explode(",", $line);
			if ($ipno > $explodetxt[0] && $ipno < $explodetxt[1]) {$located = $explodetxt[2];}}
			if ($country != "") {$located = $country;}
			$countries = file("users/countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($located == $parts[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$parts[0]}\"$select>{$parts[1]}</option>";
			$select = "";}?>
			</select>
			(Which country do you live in?)
			<label>Your Location</label>
			<input name="location" value="<?php echo $location;?>" type="text" size="30" maxlength="30" />
			(Please let us know where you are, customziable)
			<label>Agree to the Terms</label>
			<?php if ($_POST['terms'] != "") {$terms = " checked=\"yes\"";}?>
			<input name="terms" value="terms" type="checkbox"<?php echo $terms;?> />
			I agree to the <a href="http://netfriending.co.cc/include.php?page=terms" target="_blank">Terms & Agreement Page</a>
			<label>Image Verification</label>
			<img id="image" src="imgverify.php" onclick="reloadImg();" />&nbsp;Click on image to refresh if you can't see it clearly.<br />
			<input name="imgverify" value="" type="text" size="30" maxlength="5" />
			<br /><br />
			<input class="button" name="submit" type="submit" />		
			</p>		
			</form>
			
		</div>	
			
		<div id="rightbar">
		<?php include("rightmenu.html")?>
		</div>			
			
	<!-- content-wrap ends here -->		
	</div>

<!-- footer starts here -->	
<div id="footer">
	
	<div class="footer-left">
	<?php include("footer-left.html")?>
	</div>
	
	<div class="footer-right">
	<?php include("footer-right.html")?>
	</div>
	
</div>
<!-- footer ends here -->
	
<!-- wrap ends here -->
</div>

</body>
</html>