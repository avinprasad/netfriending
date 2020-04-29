<?php
ob_start();
session_start();
require_once ("functions.php");
?>
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
			
			<?php if (!checkLoggedin()) {require("login.php");} else {
			include("usernav.php");?>
			<h3>Confirm Page</h3>
			<?php //this is the confirm page, preventing resubmittion:
			//Get the PAGE Request:
			$confirm = $_GET['confirm'];
			//function....:
			function confirm($confirm,$action,$page,$words,$info1, $info2) {
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
			//Below organized accordingly to the user nav. menu:
			
			//MENU1 ON THE USER NAV.###############################################################################
			//Edit My Profile comes first:
			confirm($confirm, "profileedit", "index.php", "<p>Your profile has been successfully updated in our database! Please be patient as you will be redirected back to the user panel index.</p>");
			//Then is Edit My Account Info:
			confirm($confirm, "accountedit", "index.php", "<p>Your account information has been successfully updated in our database! Please be patient as you will be redirected back to the user panel index.</p>");
			//Edit My Preferences comes first:
			confirm($confirm, "preferenceedit", "index.php", "<p>Your preferences have been successfully updated in our database! Please be patient as you will be redirected back to the user panel index.</p>");
			//Then are few of the FRIEND LIST REQUEST (DIFF. TYPES).************************************************
			confirm($confirm, "friendlist_add", "friends.php", "<p>You have just successfully sent a friend request, and will be soon redirected to view your friend list. Please be patient.</p>");
			confirm($confirm, "friendlist_delete", "friends.php", "<p>You have just successfully deleted a friend request, and will be soon redirected to view your friend list. Please be patient.</p>");
			confirm($confirm, "friendlist_accepted", "friends.php", "<p>You have agreed to accept a friend request, and will soon be redirected to view your friend list. Please be patient.</p>");
			confirm($confirm, "friendlist_declined", "friends.php", "<p>You have declined to accept a friend request, and will soon be redirected to view your friend list. Please be patient.</p>");
			//End with different TYPES OF FRIEND REQUETS*************************************************************
			//END WITH MENU1 ON THE USER NAV#########################################################################
			
			//MENU2 ON THE USER NAV.###############################################################################
			//EVERYTHING IS ABOUT BLOG:
			//BLOG LAYOUT FIRST:
			confirm($confirm, "bloglayout", "blog.php?user=$username", "<p>Your blog layout has been successfully updated in our database! Please be patient as you will be redirected back to view your blog shortly.</p>");
			//Layout Entry, new:
			confirm($confirm, "blogentry", "blogview.php", "<p>Your new blog entry has successfully been added into the database! Please be patient as you will be redirected to the view blogs page to see your new entry.</p>");
			//EDIT old Blog Entries:
			confirm($confirm, "blogedit", "blogview.php", "<p>Your new Blog has been successfully updated in our database! Please be patient as you will be redirected to the view blogs page to see the changes you have made.</p>");
			//DELETE old Blog Entries:
			confirm($confirm, "blogedit_delete", "blogview.php", "<p>Your Blog entry has been successfully deleted from our database! Please be patient as you will be redirected back to view your Blog entries.</p>");
			//BLOG Comments:
			confirm($confirm, "blogcomment", "blog.php", "<p>The new comment that you added has successfully added in our database! Please be patient as you will be redirected to see your comments.</p>", "user", "id");
			//EDIT BLOG Comments:
			confirm($confirm, "blogcomment_edit", "blog.php", "<p>You have successfully updated the comment that you had previously posted! Please be patient as you will be redirected to see your new comments.</p>", "user", "id");
			//DELETE BLOG Comments:
			confirm($confirm, "blogcomment_delete", "blog.php", "<p>You have successfully deleted the comment in our database! Please be patient as you will be redirected to see your comments.</p>", "user", "id");
			//END WITH MENU2 ON THE USER NAV#########################################################################
			
			//MENU3 ON THE USER NAV.###############################################################################
			//EVERYTHING IS ABOUT GUESTBOOK:
			//GUESTBOOK GENERAL foreign entries, (*INCLUDING A FIFTH VARIABLE):
			confirm($confirm, "gb", "gb.php", "<p>Your entry have successfully entered into a guestbook! Please be patient as you will be redirected back to review the guestbook entries.</p>", "user");
			//GUESTBOOK LAYOUT:
			confirm($confirm, "gblayout", "gb.php?user=$username", "<p>Your Guestbook layout has been successfully updated in our database! Please be patient as you will be redirected back to view your Guestbook.</p>");
			//EDIT old Guestbook Entries:
			confirm($confirm, "gbedit", "gbview.php", "<p>Your Guestbook entry has been successfully updated in our database! Please be patient as you will be redirected back to view your Guestbook.</p>");
			//DELETE old Guestbook Entries:
			confirm($confirm, "gbedit_delete", "gbview.php", "<p>Your Guestbook entry has been successfully deleted from our database! Please be patient as you will be redirected back to view your Guestbook.</p>");
			//END WITH MENU3 ON THE USER NAV#########################################################################
			
			//MENU4 ON THE USER NAV.###############################################################################
			//EVERYTHING IS ABOUT PHOTO:
			//PHOTO UPLOAD:
			confirm($confirm, "photoedit_upload", "photoedit.php", "<p>Your Photo has been successfully uploaded into our database! Please be patient as you will be redirected to add the title and comments for it.</p>", "id");
			//EDIT old PHOTO Entries:
			confirm($confirm, "photoedit", "photoview.php", "<p>The information for your photo has successfully been updated in our database! Please be patient as you will be redirected to see the changes.</p>");
			//DELETE old Photo Entries:
			confirm($confirm, "photoedit_delete", "photoview.php", "<p>Your photo and entry have been successfully deleted from our database! Please be patient as you will be redirected to see the changes.</p>");
			//PHOTO GALLERY Comments:
			confirm($confirm, "photocomment", "photo.php", "<p>The new comment that you added has successfully added in our database! Please be patient as you will be redirected to see your comments.</p>", "user", "page");
			//EDIT PHOTO GALLERY Comments:
			confirm($confirm, "photocomment_edit", "photo.php", "<p>You have successfully updated the comment that you had previously posted! Please be patient as you will be redirected to see your new comments.</p>", "user", "page");
			//DELETE PHOTO GALLERY Comments:
			confirm($confirm, "photocomment_delete", "photo.php", "<p>You have successfully deleted the comment in our database! Please be patient as you will be redirected to see your comments.</p>", "user", "page");
			//END WITH MENU4 ON THE USER NAV#########################################################################
			
			//MENU5 ON THE USER NAV.###############################################################################
			//THESE ARE THE EXTRAS:
			//PMs first:::: (PM SEND):
			confirm($confirm, "pm", "pm.php?pm=outbox", "<p>You have successfully sent a private message out to someone, you will soon be redirected to your outbox to view the sent pm.</p>");
			//PM DELETE:
			confirm($confirm, "pm_delete", "pm.php", "<p>Your selected private messages have been successfully deleted from our database, you will be soon redirected back to view the changes.</p>", "pm");
			//PM UNREAD:
			confirm($confirm, "pm_unread", "pm.php", "<p>Your selected private messages have been successfully marked as unread in our database, you will be soon redirected back to view the changes.</p>", "pm");
			//PM Finished*************
			//Submitting articles.....
			confirm($confirm, "submitboard", "index.php", "<p>You have successfully submitted an article to our administrators as a request to post on to the front page, you will now be redirected back to user index.</p>");
			//END WITH MENU5 ON THE USER NAV#########################################################################
			
			//MENU6 ON THE USER NAV.###############################################################################
			//THESE ARE THE FORUM ACTIONS:
			//ADD THREAD:
			confirm($confirm, "forum_addthread", "../forum/threads.php", "<p>You have succesfully posted a new thread into this category, now you will be redirect back to view the threads page.</p>", "cat");
			//REPLY THREAD:
			confirm($confirm, "forum_replythread", "../forum/viewthread.php", "<p>You have succesfully reply to this thread, now you will be redirect back to view the thread replies.</p>", "threadid");
			//VOTE THREAD:
			confirm($confirm, "forum_votethread", "../forum/viewthread.php", "<p>You have succesfully voted for this thread, now you will be redirect back to view the thread votes.</p>", "threadid");
			//EDIT THREAD:
			confirm($confirm, "forum_editthread", "../forum/viewthread.php", "<p>You have succesfully edit this thread, noted that edited threads are indicated.</p>", "threadid");
			
			//END WITH MENU6 ON THE USER NAV#########################################################################
			?>
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