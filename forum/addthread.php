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
<?php $category = $_GET['cat'];
$forums = file("forum.txt");
foreach ($forums as $forum) {
$data = explode(",", $forum);
$forumlist[] = $data[0];
if ($data[0] == $category) {$label = $data[1];}}?>
<title>Post New Thread</title>
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
			<a href="<?php echo "threads.php?{$_SERVER['QUERY_STRING']}";?>"><?php echo $label;?></a> >>
			<a href="<?php echo "addthread.php?{$_SERVER['QUERY_STRING']}";?>">Post New Thread</a>
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
			$question = $_POST['question'];
			$option1 = $_POST['option1'];
			$option2 = $_POST['option2'];
			$option3 = $_POST['option3'];
			$option4 = $_POST['option4'];
			$option5 = $_POST['option5'];
			$poll = $_POST['poll'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message), array(0, $question), array(0, $option1), array(0, $option2), array(0, $option3), array(0, $option4), array(0, $option5));
			include("../texteditor.php");
			list($title, $message, $question, $option1, $option2, $option3, $option4, $option5) = $finalcontent;
			
			//all required fields filled?
			if ($message == "" or $title == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//category included in url?
			$category = $_GET['cat'];
			if ($category == "")
			{echo $no_category; $invalid = true;}
			else {
			
			//category valid?
			$result = array_keys($forumlist, $category);
			if ("". $result[0] ."" == "")
			{echo $category_invalid; $invalid = true;}}
			
			//polls!!!
			if ($question != "")
			{$list[] = $question;
			$list[] = $poll;
			$pollcount = 0;
			if ($option1 != "") {$pollcount ++; $list[] = $option1;}
			if ($option2 != "") {$pollcount ++; $list[] = $option2;}
			if ($option3 != "") {$pollcount ++; $list[] = $option3;}
			if ($option4 != "") {$pollcount ++; $list[] = $option4;}
			if ($option5 != "") {$pollcount ++; $list[] = $option5;}
			$divided = implode(";seDp#", $list);
			if ($pollcount < 2)
			{echo $poll_tooless; $invalid = true;}}}
						
			if ($invalid == false)
			{$read = mysql_query("SELECT * FROM forum ORDER BY threadid DESC LIMIT 0, 1");
			$r = mysql_fetch_array($read);
			$count = $r['threadid'] + 1;
			
			$insert = mysql_query("INSERT INTO forum(forum, threadid, threadicon, username, threadtitle, threadcontent, threadattribute, threadsticky, threadpoll, threaddate) VALUES ('$category', '$count', '$icon', '$username', '$title', '$message', 'topthread', '1', '$divided', '$datetime')");
			
			$icon = ""; $title = ""; $message = ""; $datetime = ""; $question = ""; $count = ""; $option1 = ""; $option2 = ""; $option3 = ""; $option4 = ""; $option5 = ""; $divided = "";
			
			if ($insert === true)
			{header("location: ../users/confirm.php?confirm=forum_addthread&cat=$category");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//poll options menu!:
			if ($_GET['poll'] == "block") {$pollmenu = "block";}
			else {$pollmenu = "none";}
			
			if ($icon == "") {$icon = "xx";}
			
			//Set values for form post:
			$form = "addthread";
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
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?php echo $message;?></textarea>
			<br />
			<a href="javascript:viewMore('poll');" id="xpoll">Poll Options</a>
			<p id="poll" style="display:<?php echo $pollmenu;?>">
			<label>Poll Question</label>
			<input name="question" value="<?php echo $question;?>" type="text" size="50" maxlength="40" /><br />
			<label>Poll Options</label>
			Option 1 <input name="option1" value="<?php echo $option1;?>" type="text" size="20" maxlength="40" /><br />
			Option 2 <input name="option2" value="<?php echo $option2;?>" type="text" size="20" maxlength="40" /><br />
			Option 3 <input name="option3" value="<?php echo $option3;?>" type="text" size="20" maxlength="40" /><br />
			Option 4 <input name="option4" value="<?php echo $option4;?>" type="text" size="20" maxlength="40" /><br />
			Option 5 <input name="option5" value="<?php echo $option5;?>" type="text" size="20" maxlength="40" /><br />
			<label>Poll Status</label>
			<?php if ($poll == "hide"){$hide = " checked=\"yes\"";} else {$show = " checked=\"yes\"";}?>
			Show the results publically<input type="radio" name="poll" value="show"<?php echo $show;?> /><br />
			Show only after the users have voted<input type="radio" name="poll" value="hide"<?php echo $hide;?> />
			</p></p>
			<p>
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