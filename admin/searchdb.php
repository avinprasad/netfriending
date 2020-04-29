<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Search Database Manager</title>
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
			
		<div id="admin">
			
			<?php include("menu.php");?>
			<h3>Search Database Manager</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			//Link Database Details:
			$files = file("../search_db/links.txt");
			$number = count($files);
			$time = filemtime("../search_db/links.txt");
			$time = date("Y-m-d h:i A", $time);
			//Database Details:
			$files = glob("../search_db/db/*.txt");
			$number1 = count($files);
			foreach ($files as $file) {
			$time1 = filemtime($file);
			$filestarray[] = $time1;}
			rsort($filestarray);
			$time1 = date("Y-m-d h:i A", $filestarray[0]);
			
			echo "<p><b>Link Database Count:</b> $number<br />
			<b>Link Database Last Updated:</b> $time<br />
			<b>Database Count:</b> $number1<br />
			<b>Database Last Updated:</b> {$time1}<br /></p>";
			
			//path to config.txt:
			$path_config = "../search_db/config.txt";
			$load = true;
			if (file_exists($path_config) == false) {
			echo $file_notexist; $load = false;}
			
			if (isset($_POST['submit'])) {
			$ua = $_POST['ua'];
			$limit = $_POST['limit'];
			
			//all required fields filled?
			if($ua == "" or $limit == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//is limit an int value?
			if (!is_numeric($limit))
			{echo $integers_only; $invalid = true;}}
			
			if ($invalid == false)
			{$file = fopen($path_config, "w");
			$write = fwrite($file, "$limit\n$ua");
			fclose($file);
			
			if($write == true)
			{header("location: confirm.php?confirm=searchdb");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Recall Data from DB:
			if ($load == true) {$contents = file($path_config);
			$limit = $contents[0]; $ua = $contents[1];}
			
			//Set values for form post:
			$form = "dbconfigeditor";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<p>
			<label>User Agent</label>
			<input name="ua" value="<?echo $ua;?>" type="text" size="30" maxlength="100" />
			<label>Limit of Pages per Load</label>
			<input name="limit" value="<?echo $limit;?>" type="text" size="30" maxlength="3" /><br /><br />
			<input class="button" name="submit" type="submit" />
			</p>
			</form>
			
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