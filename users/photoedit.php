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
<title>Photo Editing</title>
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
			<h4>Edit Photo Entry</h4>
			<a href="photoview.php">Back to Photo Admin</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$id = $_GET['id'];
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM photo WHERE id='$id' AND username='$username'");
			$r = mysql_fetch_array($read);
			if ($read === false)
			{echo "<p>$db_error</p>";}
			
			if (isset($_POST['delete'])){
			$delete = mysql_query("DELETE FROM photo WHERE id='$id' AND username='$username'");
			$unlink = unlink("images/photo/photo{$r['photofile']}.jpg");
			
			if($delete === false or $unlink == false)
			{echo"<p><b>Sorry, the database is temporary down, please come back later!</b></p>";}
			else
			{header("location: confirm.php?confirm=photoedit_delete");}}
			
			if(isset($_POST['submit'])){
			$title = $_POST['title'];
			$message = $_POST['message'];
			$photo = $_POST['photo'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if ($title == "")
			{echo $requiredfields; $invalid = true;}
			
			if ($invalid == false)
			{$insert = mysql_query("UPDATE photo SET photoname='$title', photocontent='$message', photodate='$datetime' WHERE id='$id' AND username='$username'");
			if ($photo == "photo") {$insert1 = mysql_query("UPDATE usercus SET photo='{$r['photofile']}' WHERE username='$username'");} else {$insert1 = true;}
			$title = ""; $message = ""; $datetime = ""; $photo = "";
			
			if ($insert === true && $insert1 === true)
			{header("location: confirm.php?confirm=photoedit");}
			else
			{echo"<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "photoentry";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $r['photoname'];?>" type="text" size="30" maxlength="40" />
			<label>Comments</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $r['photocontent'];?></textarea>
			<table><tr><td align=left width="80%"><label>Set As My Primary Photo</label></td>
			<td align=left width="100%"><input name="photo" value="photo" type="checkbox"></td></tr></table>
			<br />
			<input class="button" name="submit" type="submit" />
			<input class="button" name="delete" type="submit" value="Delete Entry" onClick="javascript: return checkDelete()" />		
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