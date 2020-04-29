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
<title>Guestbook Editing</title>
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
			<h4>Edit Guestbook Entry</h4>
			<a href="gbview.php">Back to Guestbook Admin</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$id = $_GET['id'];
			
			if (isset($_POST['delete'])){
			$delete = mysql_query("DELETE FROM guestbook WHERE id='$id' AND username='$username'");
			if($delete === false)
			{echo"<p>$db_error</p>";}
			else
			{header("location: confirm.php?confirm=gbedit_delete");}}
			
			if (isset($_POST['submit'])){
			$icon = $_POST['icon'];
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if ($message == "" or $title == "")
			{echo $requiredfields; $invalid = true;}
			
			if ($invalid == false)
			{$insert = mysql_query("UPDATE guestbook SET entryicon='$icon', entrytitle='$title', entrycontent='$message', entrydate='$datetime' WHERE id='$id' AND username='$username'");
			$icon = ""; $title = ""; $message = ""; $datetime = "";
			
			if ($insert === true)
			{header("location: confirm.php?confirm=gbedit");}
			else
			{echo"<p>$db_error</p>";}}}
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM guestbook WHERE id='$id' AND username='$username'");
			$r = mysql_fetch_array($read);
			if ($read === false)
			{echo "<p>$db_error</p>";}
			if ($r['user'] == $username) {$showedit = true;} else {$showedit = false;}
			
			//Set values for form post:
			$form = "gbedit";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic, $img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx", $r['entryicon']);?> />
			<img src="images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown", $r['entryicon']);?> />
			<img src="images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup", $r['entryicon']);?> />
			<img src="images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation", $r['entryicon']);?> />
			<img src="images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question", $r['entryicon']);?> />
			<img src="images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp", $r['entryicon']);?> />
			<img src="images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $r['entrytitle'];?>" type="text" size="30" maxlength="40" />
			<label>Entry Content</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5" <?php if ($showedit == false) {echo "readonly";}?>><?echo $r['entrycontent'];?></textarea>
			<br />	
			<?php if ($showedit == true) {?><input class="button" name="submit" type="submit"  <?php if ($showedit == false) {echo "readonly";}?>/><?php }?>
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