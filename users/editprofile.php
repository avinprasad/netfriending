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
<title>Edit My Profile</title>
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
			<h4>Customize My Profile</h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$photo = $_POST['photo'];
			$quote = $_POST['quote'];
			$about = $_POST['about'];
			$interest = $_POST['interest'];
			$hobby = $_POST['hobby'];
			$favourite = $_POST['favourite'];
			$wish = $_POST['wish'];
			$signiture = $_POST['signiture'];
			$lastmoddate = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $quote), array(2, $about), array(2, $interest), array(2, $hobby), array(2, $favourite), array(2, $wish), array(3, $signiture));
			include("../texteditor.php");
			list($quote, $about, $interest, $hobby, $favourite, $wish, $signiture) = $finalcontent;
			
			$update = mysql_query("UPDATE usercus SET photo='$photo', quote='$quote', about='$about', interest='$interest', hobby='$hobby', favourite='$favourite', wish='$wish', signiture='$signiture', lastmoddate='$lastmoddate' WHERE username='$username'");
			$photo = ""; $quote = ""; $about = ""; $interest = ""; $hobby = "";	$favourite = ""; $wish = ""; $signiture = ""; $lastmoddate = "";
			if ($update === true)
			{header("location: confirm.php?confirm=profileedit");}
			else
			{echo "<p>$db_error</p>";}}
			
			//Recall Data from DB:
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r1 = mysql_fetch_array($read1);
			$read2 = mysql_query("SELECT * FROM photo WHERE username='$username'");
			if ($read1 === false or $read2 === false)
			{echo "<p>$db_error</p>";}?>
			
			<form name="editprofile" action="" method="post">			
			<p>			
			<label>Display Photo</label>
			<select name="photo" size="1">
			<?php if (mysql_num_rows($read2) == 0) 
			{echo "<option value=\"\">No photo present</option>";}
			else {echo "<option value=\"\">No Display Picture</option>";
			while ($r2 = mysql_fetch_array($read2)){
			echo "<option value=\"". $r2['photofile'] ."\"";
			if ($r2['photofile'] == $r1['photo']) echo " selected=\"selected\"";
			echo ">". $r2['photoname'] ."</option>";}}?>
			</select>
			<label>MY Quote</label>
			<input name="quote" value="<?php echo $r1['quote'];?>" type="text" size="30" maxlength="50" />
			<label>About Me</label>
			<textarea name="about" rows="5" cols="5"><?echo $r1['about'];?></textarea>
			<label>MY Interests</label>
			<textarea name="interest" rows="5" cols="5"><?echo $r1['interest'];?></textarea>
			<label>MY Hobbies</label>
			<textarea name="hobby" rows="5" cols="5"><?echo $r1['hobby'];?></textarea>
			<label>MY Favourites</label>
			<textarea name="favourite" rows="5" cols="5"><?echo $r1['favourite'];?></textarea>
			<label>MY Wishes</label>
			<textarea name="wish" rows="5" cols="5"><?echo $r1['wish'];?></textarea>
			<label>MY Signiture</label>
			<textarea name="signiture" rows="5" cols="5"><?echo $r1['signiture'];?></textarea>
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