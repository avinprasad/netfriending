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
<title>Edit My Account</title>
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
			<h4>Edit My Account Information</h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$password = $_POST['password'];
			$gender = $_POST['gender'];
			$birthday = $_POST['birthday'];
			$birthmonth = $_POST['birthmonth'];
			$birthyear = $_POST['birthyear'];
			$timeoffset = $_POST['timeoffset'];
			$country = $_POST['country'];
			$location = $_POST['location'];
			$birthdate = "$birthyear-$birthmonth-$birthday";
			
			$entrycontent = array(array(2, $password), array(2, $location));
			include("../texteditor.php");
			list($password, $location) = $finalcontent;
			
			//all required fields filled?
			if ($location == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			if ($password != "" && $_POST['cpassword'] != "") {
			//pass double checked?
			if ($password != $_POST['cpassword'])
			{echo $passwords_diff; $invalid = true;}
			else {
			
			//limiting the number of password characters
			$minp_length = 4;
			if (strlen($password) < $minp_length)
			{echo $password_short; $invalid = true;}
			else {$password = md5($password);}}}
			
			else {$password = $row['password']; $_POST['cpassword'] = $row['password'];}
			
			//birthdate in the right timeframe?
			if (checkdate($birthmonth, $birthday, $birthyear) == false)
			{echo $birthdate_invalid; $invalid = true;}
			else {
			
			//at least 13 years old?
			$years13ago = strtotime("-13 years");
			$yearsnow = strtotime($birthdate);
			if ($years13ago < $yearsnow)
			{echo $age_tooyoung; $invalid = true;}}}
			
			if ($invalid == false)
			{$update = mysql_query("UPDATE userinfo SET password='$password', gender='$gender', birthday='$birthdate', timeoffset='$timeoffset', country='$country', location='$location' WHERE username='$username'");
			$password = ""; $gender = ""; $location = ""; $country = ""; $birthday = ""; $birthmonth = ""; $birthyear = "";
			
			if ($update === true)
			{header("location: confirm.php?confirm=accountedit");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
			$row = mysql_fetch_array($read);
			if ($read === false)
			{echo "<p>$db_error</p>";}?>
			
			<form name="editaccount" action="" method="post">			
			<p>			
			<label>Password</label>
			<input name="password" type="password" size="30" maxlength="20" />
			(leave blank if you do not wish to change this)
			<label>Confirm Password</label>
			<input name="cpassword" type="password" size="30" maxlength="20" />
			(confirm, please enter the same password for above)
			<label>Gender</label>
			<?php if ($row['gender'] == "F"){$female = " checked=\"yes\"";} else {$male = " checked=\"yes\"";}?>
			Male<input type="radio" name="gender" value="M"<?php echo $male?> />
			Female<input type="radio" name="gender" value="F"<?php echo $female?> />
			<label>Birthday</label>
			<select name="birthday" size="1">
			<?php $birthdate = explode("-", $row['birthday']);
			$days = file("days.txt");
			foreach ($days as $day) {$day = explode(",", $day);
			if ($day[0] == $birthdate[2]) {$select1 = " selected";}
			echo "<option value=\"{$day[0]}\"$select1>{$day[0]}</option>";
			$select1 = "";}?>
			</select>
			<select name="birthmonth" size="1">
			<?php $months = file("months.txt");
			foreach ($months as $month) {$month = explode(",", $month);
			if ($month[0] == $birthdate[1]) {$select2 = " selected";}
			echo "<option value=\"{$month[0]}\"$select2>{$month[1]}</option>";
			$select2 = "";}?>
			</select>
			<select name="birthyear" size="1">
			<?php $years = file("years.txt");
			foreach ($years as $year) {$year = explode(",", $year);
			if ($year[0] == $birthdate[0]) {$select3 = " selected";}
			echo "<option value=\"{$year[0]}\"$select3>{$year[0]}</option>";
			$select3 = "";}?>
			</select>
			<label>Time Zone</label>
			<select name="timeoffset" size="1">
			<?php $timezones = file("timezones.txt");
			foreach ($timezones as $timezone) {$timezone = explode("|", $timezone);
			if ($timezone[0] == $row['timeoffset']) {$select4 = " selected";}
			echo "<option value=\"{$timezone[0]}\"$select4>{$timezone[1]}</option>";
			$select4 = "";}?>
			</select>
			<label>Country:</label>
			<select name="country" size="1">
			<?php $countries = file("countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($row['country'] == $parts[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$parts[0]}\"$select>{$parts[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Your Location</label>
			<input name="location" value="<?php echo $row['location'];?>" type="text" size="30" maxlength="30" />
			<br /><br />
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