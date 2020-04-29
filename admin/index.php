<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Admin Panel</title>
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
			<h3>Index</h3>
			<p><b>Hello admin, You have successfully logged in to the administrator panel! What do you want to do?</b></p>
			<p><b><a href="newsentry.php">News Entry</a></b> - This is to enter the news in the homepage!</p>
			<p><b><a href="faqlist.php">FAQ Manager</a></b> - Manage the FAQs!</p>
			<p><b><a href="textedit.php">Text Editor</a></b> - Modify Raw text files!</p>
			<p><b><a href="usersstats.php">User Statistics</a></b> - See and analysing some user statistics!</p>
			<p><b><a href="webmail.php">Webmail Manager</a></b> - Check E-mail for different accounts.</p>
			
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