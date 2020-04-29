<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Forum Edit - Choose Thread</title>
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
			<h3>Forum Edit</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$threadid = $_POST['threadid'];
			$postno = $_POST['postno'];
			
			//all required fields filled?
			if ($threadid == "" or $postno == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//is input valid (digits)?
			if (is_numeric($threadid) == false or is_numeric($postno) == false)
			{echo $integers_only; $invalid = true;}}
			
			if ($invalid == false)
			{header("location: confirm.php?confirm=threadno&threadid=$threadid&postno=$postno");}}
			
			$form = "forumedit";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>	
			<label>Thread ID</label>
			<input name="threadid" value="<?php echo $threadid;?>" type="text" size="30" maxlength="10" />
			<label>Post Number</label>
			<input name="postno" value="<?php echo $postno;?>" type="text" size="30" maxlength="10" />
			<br /><br />
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