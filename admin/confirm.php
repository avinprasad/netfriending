<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Confirm Action</title>
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
			<h3>Confirm Page</h3>
			<?php //this is the confirm page, preventing resubmittion:
			//Get the PAGE Request:
			$confirm = $_GET['confirm'];
			//function....:
			function confirm($confirm,$action,$page,$words,$info1,$info2) {
			if ($confirm == $action)
			{if ($info1 != "") {//additional information 1 exists:
			$addinfo = $_GET[$info1];
			$querystring1 = "?$info1=$addinfo";}
			if ($info2 != "") {//additional information 2 exists:
			$addinfo = $_GET[$info2];
			$querystring2 = "&$info2=$addinfo";}			
			echo "$words<p><b>If you are not redirected successfully, please click <a href=\"$page$querystring1$querystring2\">here</a>.</b></p>";?>
			<body onload="timer=setTimeout('move()',5000)"> 
			<script language="JavaScript"> 
			var time = null 
			function move() { 
			window.location = '<?php echo "$page$querystring1$querystring2";?>'} 
			</script>
			<?php }}
			//function ENDED**************************************************************************************
			//Below organized accordingly to the admin nav. menu:
			
			//MENU1 ON THE ADMIN NAV.###############################################################################
			//END WITH MENU1 ON THE USER NAV#########################################################################
			
			//MENU2 ON THE ADMIN NAV.###############################################################################
			//PUBLIC PAGES:
			//add new NEWS ENTRY:
			confirm($confirm, "newsentry", "../news.php", "<p>Your new news entry had been successfully added into the database! Please be patient as you will be redirected back to view your news entry shortly.</p>", "news");
			//edit old NEWS ENTRY:
			confirm($confirm, "newsedit", "../news.php", "<p>You had successfully edited the old news entry and updated the database! Please be patient as you will be redirected back to view your news entry shortly.</p>", "id");
			//approve the edit Board:
			confirm($confirm, "boardedit_approve", "boardview.php", "<p>You have successfully approved the article that was submitted to the public users' board! Please be patient as you will be redirected back to View the article details again.</p>", "id");
			//deny the edit Board:
			confirm($confirm, "boardedit_deny", "boardview.php", "<p>You have successfully denied the article that was submitted to the public users' board! Please be patient as you will be redirected back to view the article detials again.</p>", "id");
			//delete the edit Board:
			confirm($confirm, "boardedit_delete", "boardlist.php", "<p>You have successfully deleted the article that was submitted to the public users' board! Please be patient as you will be redirected back to the Board list shortly.</p>");
			//add new FAQ ENTRY:
			confirm($confirm, "faqentry", "faqlist.php", "<p>You have successfully added a new FAQ entry into our database! Please be patient as you will be redirected back to view the FAQ list shortly.</p>");
			//edit old FAQ ENTRY:
			confirm($confirm, "faqedit", "faqlist.php", "<p>You have successfully edited the old FAQ entry and updated the database! Please be patient as you will be redirected back to view the FAQ list shortly.</p>");
			//delete old FAQ ENTRY:
			confirm($confirm, "faqedit_delete", "faqlist.php", "<p>You have successfully deleted this particular FAQ entry! Please be patient as you will be redirected back to view the FAQ list shortly.</p>");
			//search db Edit:
			confirm($confirm, "searchdb", "searchdb.php", "<p>You have successfully edited the Searcb Database Configurations! Please be patient as you will be redirected back to view the Search Database Manager shortly.</p>");
			//END WITH MENU2 ON THE ADMIN NAV#########################################################################
			
			//MENU3 ON THE ADMIN NAV.###############################################################################
			//PRIVATE PAGES:
			//END WITH MENU3 ON THE ADMIN NAV#########################################################################
			
			//MENU4 ON THE ADMIN NAV.###############################################################################
			//OTHERS:
			//edit all the TEXT FILES:
			confirm($confirm, "textedit", "textedit.php", "<p>You have successfully updated the text file in the database! Please be patient as you will be redirected to view the text file changes shortly.</p>", "id");
			confirm($confirm, "webmail_actions", "webmail.php", "<p>You have successfully updated the flags or moved/copied of your emails! Please be patient as you will be redirected to view the text file changes shortly.</p>", "dir");
			confirm($confirm, "webmail_sendmail", "webmail.php", "<p>You have successfully sent the email! Please be patient as you will be redirected to view the text file changes shortly.</p>", "dir");
			confirm($confirm, "webmail_delete", "webmail.php", "<p>You have successfully deleted your emails! Please be patient as you will be redirected to view the text file changes shortly.</p>", "dir");
			confirm($confirm, "webmail_fld", "webmail.php", "<p>You have successfully created, renamed or deleted a folder! Please be patient as you will be redirected to view the text file changes shortly.</p>", "dir");
			//END WITH MENU4 ON THE ADMIN NAV#########################################################################
			
			//MENU5 ON THE ADMIN NAV.###############################################################################
			//FORUM:
			//entering the THREAD NUMBERS:
			confirm($confirm, "threadno", "threadedit.php", "<p>The system had successfully found the thread that you wish to edit, and will now redirect you to the editing page</p>", "threadid", "postno");
			//edit the THREAD INFORMATION:
			confirm($confirm, "threadedit", "../forum/viewthread.php", "<p>You have successfully updated the thread information in the database! Please be patient as you will be redirected to view the changes shortly.</p>", "threadid");
			//END WITH MENU6 ON THE ADMIN NAV#########################################################################
			?>
			
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