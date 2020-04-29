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
<title>Add Blog Entry</title>
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
			<h4>Add Blog Entry</h4>
			<a href="blogview.php">Back to Blog Admin</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
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
			{$insert = mysql_query("INSERT INTO diary(username, entryicon, entrytitle, entrycontent, entrydate) VALUES('$username', '$icon', '$title', '$message', '$datetime')");
			$icon = ""; $title = ""; $message = ""; $datetime = "";
			
			if($insert === true)
			{header("location: confirm.php?confirm=blogentry");}
			else
			{echo "<p>$db_error</p>";}}}
			
			if ($icon == "") {$icon = "xx";}
			
			//Set values for form post:
			$form = "diaryentry";
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
			<label>Title</label>
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