<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<title><?php echo "ErrorDocument $error";?></title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">

	<!--header -->
	<div id="header">				
		
		<?php include("header.html")?>
			
		<!-- Menu Tabs -->
		<?php include("menu.php")?>
		
	</div>	
				
	<!-- content-wrap starts here -->
	<div id="content-wrap">		
											
	<div class="headerphoto"></div>
		
		<div id="sidebar" >							
		<?php include("leftmenu.html")?>
		</div>
			
		<div id="main">
			
			<?php echo "<a name=\"ErrorDocument $error\"></a>
			<h1>ErrorDocument $error</h1>
			$errorpage";?>
		
		</div>	
			
		<div id="rightbar">
		<?php include("rightmenu.html")?>
		</div>			
			
	<!-- content-wrap ends here -->		
	</div>

<!-- footer starts here -->	
<div id="footer">
	
	<div class="footer-left">
	<?php include("footer-left.html")?>
	</div>
	
	<div class="footer-right">
	<?php include("footer-right.html")?>
	</div>
	
</div>
<!-- footer ends here -->
	
<!-- wrap ends here -->
</div>

</body>
</html>