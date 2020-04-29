<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Thread Edit</title>
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
			
			<?php include("menu.php");?>
			<h3>Thread Edit</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$threadid = $_GET['threadid'];
			$postno = $_GET['postno'] - 1;
			
			//Recall Data from DB:
			$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND (threadattribute='topthread' OR threadattribute='conthread') ORDER BY id");
			while ($r = mysql_fetch_array($read)) {$array[] = $r;}
			if ($read === false)
			{echo "<p>$db_error</p>";}			
			
			if (isset($_POST['submit'])){
			$icon = $_POST['icon'];
			$title = $_POST['title'];
			$message = $_POST['message'];
			$threadcat = $_POST['threadcat'];
			$threadsticky = $_POST['threadsticky'];
			$threadlock = $_POST['threadlock'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			if ($threadsticky == "") {$threadsticky = 1;}
			
			//all required fields filled?
			if ($message == "" or $title == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//id exist yet?
			$read = mysql_query("SELECT * FROM forum WHERE id='{$array[$postno][0]}'");
			if (mysql_num_rows($read) == 0)
			{echo $thread_notexist; $invalid = true;}}
			
			if ($invalid == false)
			{$insert1 = mysql_query("UPDATE forum SET forum='$threadcat', threadicon='$icon', threadtitle='$title', threadcontent='$message', threadsticky='$threadsticky', threadlock='$threadlock', threadmoddate='$datetime' WHERE id='{$array[$postno][0]}'");
			$insert2 = mysql_query("UPDATE forum SET forum='$threadcat' WHERE threadid='{$array[$postno][2]}'");
			
			$icon = ""; $title = ""; $message = ""; $datetime = ""; $threadcat = ""; $threadsticky = ""; $threadlock = "";
						
			if ($insert1 === false or $insert2 === false)
			{echo "<p>$db_error</p>";}
			else 
			{header("location: confirm.php?confirm=threadedit&threadid={$array[$postno][2]}");}}}
			
			//Set values for form post:
			$form = "threadedit";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic,$img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx",$array[$postno][3])?>>
			<img src="../users/images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown",$array[$postno][3])?>>
			<img src="../users/images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup",$array[$postno][3])?>>
			<img src="../users/images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation",$array[$postno][3])?>>
			<img src="../users/images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question",$array[$postno][3])?>>
			<img src="../users/images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp",$array[$postno][3])?>>
			<img src="../users/images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<?php if ($array[$postno][7] == "topthread") {?>
			<p>
			<label>Thread Category</label>
			<select name="threadcat" size="1">
			<?php $category = $array[$postno][1];
			$forums = file("../forum/forum.txt");
			foreach ($forums as $forum) {
			$data = explode(",", $forum);
			if ($data[0] == $category) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$data[0]}\"$select>{$data[1]}</option>";
			$select = "";}?>
			</select>
			</p>
			<?php }?>
			<?php include("smiliecoder.html");?>
			<p>
			<label>Title</label>
			<input name="title" value="<?echo $array[$postno][5];?>" type="text" size="30" maxlength="60" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $array[$postno][6];?></textarea>
			<?php if ($array[$postno][7] == "topthread") {?>
			<label>Sticky Option</label>
			<input name="threadsticky" value="0" type="checkbox" <?php check("0", $array[$postno][8]);?>><br />
			<label>Lock Thread Option</label>
			<input name="threadlock" value="locked" type="checkbox" <?php check("locked", $array[$postno][9]);?>></br >
			<?php }?>
			<br />
			<input class="button" name="submit" type="submit" />	
			</p>
			</form>
			
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