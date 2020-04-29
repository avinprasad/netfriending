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
<title>Upload Photo</title>
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
			<h4>Upload New Photo</h4>
			<a href="photoview.php">Back to Photo Admin</a>
			<p>You are allowed to have maximum of 20 photos stored at the same time, if you wish to upload new photos, please delete your old ones first if you exceeded the maximum!</p>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$filename = $_FILES["uploadedfile"]["name"];
			$photo = $_POST['photo'];
			$datetime = date("Y-m-d H:i:s");
			
			//all required fields filled?
			if ($filename == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//checks photo max.
			$read = mysql_query("SELECT * FROM photo WHERE username='$username'");
			if (mysql_num_rows($read) >= 20)
			{echo $photoentries_exceed; $invalid = true;}
			else {
			
			//checks file extension, img extension?
			$ext = end(explode(".", $filename));
			if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext !="jpeg" && $ext !="bmp")
			{echo $photo_extension; $invalid = true;}
			else {
			
			//checks the file size
			if ($_FILES["uploadedfile"]["size"] > 1048576)
			{echo $photo_toobig; $invalid = true;}}}}
			
			if ($invalid == false) {
			//Get filename
			$read = mysql_query("SELECT MAX(photofile) AS max_no FROM photo");
			$last_id = mysql_fetch_array($read);
			$number = $last_id['max_no'];
			$number = $number + 1;
			
			//Target Path
			$target_path = "images/photo/photo$number.jpg"; 
			//Photoname
			$photoname = str_replace(".$ext", "", $filename);
			
			if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $target_path)) 
			{$insert = mysql_query("INSERT INTO photo(username, photofile, photoname, photodate) VALUES('$username', '$number', '$photoname', '$datetime')");
			//Get inserted id.
			$read = mysql_query("SELECT * FROM photo WHERE photofile='$number' AND username='$username'");
			$r = mysql_fetch_array($read);
			if ($photo == "photo") {$insert1 = mysql_query("UPDATE usercus SET photo='$number' WHERE username='$username'");} else {$insert1 = true;}
			
			if($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=photoedit_upload&id={$r['id']}");}
			else
			{echo "<p>$db_error</p>";}}
			else
			{echo $photo_upload;}}}?>
			<p><table align=left width="100%">
			<form enctype="multipart/form-data" action="" method="post">
			<tr><td align=left width="40%">Choose a file to upload:</td>
			<td align=left width="100%"><input name="uploadedfile" type="file" /></td></tr>
			<tr><td align=left>Set As My Primary Photo:</td>
			<td align=left><input name="photo" value="photo" type="checkbox"></td></tr><tr></tr>
			<tr><td align=left><input type="submit" name=submit value="Upload File" /></td></tr>
			</form>
			</table></p>
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