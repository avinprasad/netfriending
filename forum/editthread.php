<?php
ob_start();
session_start();
require_once ("../users/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<?php $id = $_GET['id'];
require("../mysqlconnection.php");
$read = mysql_query("SELECT * FROM forum WHERE id='$id'");
$r = mysql_fetch_array($read);
$category = $r['forum'];
$forums = file("forum.txt");
foreach ($forums as $forum) {
$data = explode(",", $forum);
if ($data[0] == $category) {$label = $data[1];}}
$id = $r['id'];?>
<title>Edit My Thread</title>
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
			
			<?php if (!checkLoggedin()) {require("../users/login.php");} else {
			include("../users/usernav.php");?>
			<h3>
			<a href="index.php">Forum Index</a> >>
			<a href="<?php echo "threads.php?cat=$category";?>"><?php echo $label;?></a> >>
			<a href="<?php echo "viewthread.php?threadid={$r['threadid']}";?>"><?php echo $r['threadtitle'];?></a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			//Require Forum Online Stats Record:
			include("onlinestats.php");
			
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
			else {
			
			//id exist, belong to user?
			$read = mysql_query("SELECT * FROM forum WHERE id='$id' AND username='$username'");
			if (mysql_num_rows($read) == 0)
			{echo $thread_notexist_notusername; $invalid = true;}}
			
			if ($invalid == false)
			{$insert = mysql_query("UPDATE forum SET threadicon='$icon', threadtitle='$title', threadcontent='$message', threadmoddate='$datetime' WHERE id='$id' AND username='$username'");
			
			$icon = ""; $title = ""; $message = ""; $datetime = "";
						
			if ($insert === true)
			{header("location: ../users/confirm.php?confirm=forum_editthread&threadid={$r['threadid']}");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "editthread";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic, $img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx", $r['threadicon']);?> />
			<img src="../users/images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown", $r['threadicon']);?> />
			<img src="../users/images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup", $r['threadicon']);?> />
			<img src="../users/images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation", $r['threadicon']);?> />
			<img src="../users/images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question", $r['threadicon']);?> />
			<img src="../users/images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp", $r['threadicon']);?> />
			<img src="../users/images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?php echo $r['threadtitle'];?>" type="text" size="30" maxlength="60" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?php echo $r['threadcontent'];?></textarea>
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