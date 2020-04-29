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
<title>Blog Entries Manager</title>
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
			<h4>View Blog Entries</h4>
			<a href="blogentry.php">Add New Blog Entry</a> | <a href="bloglayout.php">Edit Blog Layout</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			$noty_subject = "blog";
			include("../notification.php");
			
			//Set number of entries on each page:
			$limit = 5;
			//Query the db to get total entries:
			$read = mysql_query("SELECT * FROM diary WHERE username='$username'");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($read === false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			$read = mysql_query("SELECT * FROM diary WHERE username='$username' ORDER BY entrydate DESC LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$dis_func = 0; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			//Only the first limited no. of characters:
			if (strlen($r['entrycontent']) > 500) {$extension = "...";}
			$entrycontent = "". substr($r['entrycontent'], 0, 500) ."$extension";
			$extension = "";
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td align=center width=\"10%\" height=\"50%\" bgcolor=\"#cccccc\"><img src=\"images/{$r['entryicon']}.gif\"></td><td align=left width=\"90%\" bgcolor=\"#cccccc\"><b>{$r['entrytitle']}</b></td><td align=center width=\"100%\" bgcolor=\"#cccccc\"><a href=\"blogedit.php?id={$r['id']}\">Edit</a></td></tr></table><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><td align=left width=\"100%\" bgcolor=\"#f2f2f2\">Posted on: $date</td></table><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td id=\"views\" align=left width=100% bgcolor=\"#cccccc\">$entrycontent</td></tr></table><br /></p>";}
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=1\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
			else {echo "PREV&nbsp;";}
			//Page range sorting:
			if ($page <= 5) {$pagelowerlim = 1;} else {
			if ($totalpages <= 11) {$pagelowerlim = 1;}
			else {if (($totalpages - $page) < 5) {$pagelowerlim = $totalpages - 10;}
			else {$pagelowerlim = $page - 5;}}}
			if ($page <= 5) {
			if ($totalpages <= 11) {$pageupperlim = $totalpages;} else {$pageupperlim = 11;}}
			else {if (($totalpages - $page) >= 5) {$pageupperlim = $page + 5;} else {$pageupperlim = $totalpages;}}
			//Looping through page numbers:
			$i = $pagelowerlim;
			while ($i <= $pageupperlim) {
			if ($i == $page) {echo "[$i] ";}
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$totalpages\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}?>
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