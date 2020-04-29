<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>New FAQ Entry</title>
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
			<h3>FAQ Entry</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(0, $title), array(1, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if($title == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}}
						
			if ($invalid == false)
			{$lines = file("faq.txt");
			foreach ($lines as $line) {
			$explodes = explode(";seDp#", $line);
			$numbers[] = $explodes[0];}
			$number = max($numbers) + 1;
			$txtfile = file_get_contents("faq.txt");
			$list = array($number, $title, $datetime, $message);
			$comma_separated = implode(";seDp#", $list);
			$file = fopen("faq.txt","w");
			$write = fwrite($file,"$comma_separated\n$txtfile");
			$title = ""; $message = ""; $datetime = "";
			
			if($write == true)
			{header("location: confirm.php?confirm=faqentry");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "faqentry";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
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