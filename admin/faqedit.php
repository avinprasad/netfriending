<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>FAQ Edit</title>
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
			<h3>FAQ Edit</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			$id = $_GET['id'];
			$lines = file("faq.txt", FILE_IGNORE_NEW_LINES);
			$read = 0;
			foreach ($lines as $line) {
			$explodes = explode(";seDp#", $line);
			if ($explodes[0] == $id) {
			$linenumber = $read;
			foreach ($explodes as $explode) {
			$data[] = $explode;}}
			$read ++;}
			
			if (isset($_POST['delete'])){
			unset($lines[$linenumber]);
			$file = fopen("faq.txt", "w");
			$write = fwrite($file, implode("\n", $lines));
			fclose($file);
			if($write == true)
			{header("location: confirm.php?confirm=faqedit_delete");}
			else
			{echo"<p>$db_error</p>";}}
			
			if (isset($_POST['submit'])){
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(0, $title), array(1, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if ($title == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}}
			
			if ($invalid == false)
			{$list = array($data[0], $title, $datetime, $message);
			$comma_separated = implode(";seDp#", $list);
			$lines[$linenumber] = $comma_separated;
			$file = fopen("faq.txt", "w");
			$write1 = fwrite($file, implode("\n", $lines));
			fclose($file);
			$title = ""; $message = ""; $datetime = "";
			
			if($write1 == true)
			{header("location: confirm.php?confirm=faqedit");}
			else
			{echo"<p>$db_error</p>";}}}
			$title = ""; $message = ""; $datetime = "";
			
			//Set values for form post:
			$form = "faqedit";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $data[1];?>" type="text" size="30" maxlength="40" />
			<label>Entry Content</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $data[3];?></textarea>
			<br />	
			<input class="button" name="submit" type="submit" />
			<input class="button" name="delete" type="submit" value="Delete Entry" onClick="javascript: return checkDelete()" />
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