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
<?php $threadid = $_GET['threadid'];
require("../mysqlconnection.php");
$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='topthread'");
$r = mysql_fetch_array($read);
if ($title == "") {$title = "RE: {$r['threadtitle']}";}
$category = $r['forum'];
$forums = file("forum.txt");
foreach ($forums as $forum) {
$data = explode(",", $forum);
if ($data[0] == $category) {$label = $data[1];}}
$threadid = $r['threadid'];?>
<title>Post New Reply</title>
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
			<a href="<?php echo "viewthread.php?{$_SERVER['QUERY_STRING']}";?>"><?php echo $r['threadtitle'];?></a>
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
			
			//threadid exist yet?
			$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid'");
			if (mysql_num_rows($read) == 0)
			{echo $threadid_notexist; $invalid = true;}
			
			//is the thread locked?
			if ($r['threadlock'] == "locked")
			{echo $thread_locked; $invalid = true;}}
			
			if ($invalid == false)
			{$insert = mysql_query("INSERT INTO forum(forum, threadid, threadicon, username, threadtitle, threadcontent, threadattribute, threaddate) VALUES ('$category', '$threadid', '$icon', '$username', '$title', '$message', 'conthread', '$datetime')");
			
			$icon = ""; $title = ""; $message = ""; $datetime = "";
						
			if ($insert === true)
			{header("location: ../users/confirm.php?confirm=forum_replythread&threadid=$threadid");}
			else
			{echo"<p>$db_error</p>";}}}
			
			if ($icon == "") {$icon = "xx";}
			
			//Set values for form post:
			$form = "replythread";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic, $img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx", $icon);?> />
			<img src="../users/images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown", $icon);?> />
			<img src="../users/images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup", $icon);?> />
			<img src="../users/images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation", $icon);?> />
			<img src="../users/images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question", $icon);?> />
			<img src="../users/images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp", $icon);?> />
			<img src="../users/images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>
			<label>Title</label>
			<input name="title" value="<?php echo $title;?>" type="text" size="30" maxlength="60" />
			<label>Message</label>
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