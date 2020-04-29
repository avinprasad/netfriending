<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<?php $page = $_GET['page'];
$pagenames = file("pagetitles.txt");
foreach ($pagenames as $pagename) {
$parts = explode(",", $pagename);
if ($parts[0] == $page) {$title = $parts[1];}}
if ($title == "") {$invalid = true;
$title = "ErrorDocument 404"; header("HTTP/1.0 404 Not Found");}?>
<title><?php echo $title;?></title>
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
			
			<?php if ($invalid == true) {
			include ("notfound.html");}
			else {include ("$page.html");}?>
		
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