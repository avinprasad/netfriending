<?php
ob_start();
session_start();
require_once ("../users/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<?php $threadid = $_GET['threadid'];
require("../mysqlconnection.php");
$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='topthread'");
$r = mysql_fetch_array($read);
$category = $r['forum'];
$forums = file("forum.txt");
foreach ($forums as $forum) {
$data = explode(",", $forum);
if ($data[0] == $category) {$label = $data[1];}}
$threadid = $r['threadid'];
$threadtitle = $r['threadtitle'];
if ($r === false) {header("HTTP/1.0 404 Not Found"); $threadtitle = "ErrorDocument 404";}?>
<title><?php echo $threadtitle;?></title>
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
			
		<div id="forum">
			
			<?php if (checkLoggedin()) {include("../users/usernav.php");}?>
			<h3>
			<a href="index.php">Forum Index</a> >>
			<a href="<?php echo "threads.php?cat=$category";?>"><?php echo $label;?></a> >>
			<a href="<?php echo "viewthread.php?{$_SERVER['QUERY_STRING']}";?>"><?php echo $r['threadtitle'];?></a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			//Require Forum Online Stats Record:
			include("onlinestats.php");
			
			//view counter
			//don't record view of NetFriending Search Bot
			if ($_SESSION["threadviews"] != $threadid && $_SERVER['HTTP_USER_AGENT'] != "NetFriending Search Database Update/*Midnight-Bot*/") {
			$read3 = mysql_query("SELECT threadview FROM forum WHERE threadattribute='topthread' AND threadid='$threadid'");
			$r3 = mysql_fetch_array($read3);
			$views = $r3['threadview'] + 1;
			$insert = mysql_query("UPDATE forum SET threadview='$views' WHERE threadattribute='topthread' AND threadid='$threadid'");
			$_SESSION["threadviews"] = "$threadid";}
			
			//customized theme bg
			$theme = $row['theme'];
			if ($theme == "") {$theme = 0;}
			
			//the poll options!
			if ($r['threadpoll'] != "") {
			//start the table:
			echo "<p><table id=\"forumtbl\" cellpadding=\"0\" cellspacing=\"1\" border=\"1\" bordercolor=\"#111111\" style=\"border-collapse: collapse;\">";
			//label:
			echo "<tr height=\"25\"><td width=\"100%\" align=left background=\"../users/images/themes/{$theme}_gradient.gif\"><img src=\"images/normal_poll.gif\"> <b>{$r['threadtitle']} - POLL OPTIONS</b></td></tr><tr><td align=left style=\"padding-left: 15px;\">";
			
			$read4 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND username='$username'");
			$votes = mysql_num_rows($read4);
			
			//Get Different pieces of data:
			$data = explode(";seDp#", $r['threadpoll']);
			$question = $data[0];
			$pollstatus = $data[1];
			$option1 = $data[2];
			$option2 = $data[3];
			$option3 = $data[4];
			$option4 = $data[5];
			$option5 = $data[6];
			
			//VOTING DISABLED FOR THE FORENIGNERS
			if (!checkLoggedin()) {echo "Please login or register to vote!";} else {//IMPORTANT LINE, Modified
			if ($votes == 0 && $r['threadlock'] != "locked") {
			if (isset($_POST['submit'])){
			$options = $_POST['options'];
			$datetime = date("Y-m-d H:i:s");
			
			//all required fields filled?
			if ($options == "")
			{echo $requiredfields; $invalid = true;}
			
			if ($invalid == false)
			{$insert = mysql_query("INSERT INTO forum(forum, threadid, username, threadattribute, threadpoll, threaddate) VALUES ('$category', '$threadid', '$username', 'votethread', '$options', '$datetime')");
			
			if ($insert === true)
			{header("location: ../users/confirm.php?confirm=forum_votethread&threadid=$threadid");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//starting the form:
			echo "<form name=\"votethread\" action=\"\" method=\"post\">";
			//main body:
			echo "<p><b>$question - VOTE!</b></p>";
			if ($option1 != "") {echo "<p><input type=\"radio\" name=\"options\" value=\"1\" /> $option1<br /></p>";}
			if ($option2 != "") {echo "<p><input type=\"radio\" name=\"options\" value=\"2\" /> $option2<br /></p>";}
			if ($option3 != "") {echo "<p><input type=\"radio\" name=\"options\" value=\"3\" /> $option3<br /></p>";}
			if ($option4 != "") {echo "<p><input type=\"radio\" name=\"options\" value=\"4\" /> $option4<br /></p>";}
			if ($option5 != "") {echo "<p><input type=\"radio\" name=\"options\" value=\"5\" /> $option5<br /></p>";}
			echo "<p><input class=\"button\" name=\"submit\" type=\"submit\" /></p></form>";}}
			
			//hide or show stats?
			if (($pollstatus == "hide" && $votes > 0) or ($pollstatus == "show")) {
			//Get Max Votes
			$read5 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread'");
			$totalvotes = mysql_num_rows($read5);
			
			echo "<p><b>$question - STATS!</b><br />";
			
			//option 1 votes:
			$read6 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND threadpoll='1'");
			$votes1 = mysql_num_rows($read6);
			//option 2 votes:
			$read6 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND threadpoll='2'");
			$votes2 = mysql_num_rows($read6);
			//option 3 votes:
			$read6 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND threadpoll='3'");
			$votes3 = mysql_num_rows($read6);
			//option 4 votes:
			$read6 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND threadpoll='4'");
			$votes4 = mysql_num_rows($read6);
			//option 5 votes:
			$read6 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='votethread' AND threadpoll='5'");
			$votes5 = mysql_num_rows($read6);
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
			if ($option1 != "") {$percentage1 = round(($votes1/$totalvotes) * 100, 1);
			echo "<tr><td align=left width=\"50%\"><b>$option1</b></td><td width=\"100%\"><img src=\"images/poll_left.gif\" height=\"12\"><img src=\"images/poll_middle.gif\" height=\"12\" width=\"". $percentage1 ."\"><img src=\"images/poll_right.gif\" height=\"12\"> ($percentage1%)</td></tr>";}
			if ($option2 != "") {$percentage2 = round(($votes2/$totalvotes) * 100, 1);
			echo "<tr><td align=left><b>$option2</b></td><td><img src=\"images/poll_left.gif\" height=\"12\"><img src=\"images/poll_middle.gif\" height=\"12\" width=\"". $percentage2 ."\"><img src=\"images/poll_right.gif\" height=\"12\"> ($percentage2%)</td></tr>";}
			if ($option3 != "") {$percentage3 = round(($votes3/$totalvotes) * 100, 1);
			echo "<tr><td align=left><b>$option3</b></td><td><img src=\"images/poll_left.gif\" height=\"12\"><img src=\"images/poll_middle.gif\" height=\"12\" width=\"". $percentage3 ."\"><img src=\"images/poll_right.gif\" height=\"12\"> ($percentage3%)</td></tr>";}
			if ($option4 != "") {$percentage4 = round(($votes4/$totalvotes) * 100, 1);
			echo "<tr><td align=left><b>$option4</b></td><td><img src=\"images/poll_left.gif\" height=\"12\"><img src=\"images/poll_middle.gif\" height=\"12\" width=\"". $percentage4 ."\"><img src=\"images/poll_right.gif\" height=\"12\"> ($percentage4%)</td></tr>";}
			if ($option5 != "") {$percentage5 = round(($votes5/$totalvotes) * 100, 1);
			echo "<tr><td align=left><b>$option5</b></td><td><img src=\"images/poll_left.gif\" height=\"12\"><img src=\"images/poll_middle.gif\" height=\"12\" width=\"". $percentage5 ."\"><img src=\"images/poll_right.gif\" height=\"12\"> ($percentage5%)</td></tr>";}
			echo "</table></p>";}
			//closing the table:
			echo "</td></tr></table></p>";}
			
			echo "<p><table id=\"forumtbl\" cellpadding=\"0\" cellspacing=\"1\" border=\"1\" bordercolor=\"#111111\" style=\"border-collapse: collapse\">
			<tr height=\"25\"><td width=\"100%\" align=left background=\"../users/images/themes/{$theme}_gradient.gif\" colspan=2>
			<img src=\"../users/images/{$r['threadicon']}.gif\"> <b>{$r['threadtitle']}</b> 
			({$r['threadview']} views) - ";
			if ($r['threadlock'] == "locked") {echo "<b>THIS THREAD IS LOCKED</b></a></td></tr>";}
			else {echo "<a href=\"replythread.php?threadid=$threadid\"><b>REPLY TO THIS THREAD</b></a></td></tr>";}
			
			//Pagination Script Starts:
			//Set number of entries on each page:
			$limit = 10;
			//Query the db to get total entries:
			$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND (threadattribute='topthread' OR threadattribute='conthread')");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $thread_none;}
			elseif ($read === false) {$pagination = false;
			echo "<tr height=\"25\"><td align=left colspan=3>$db_error</td></tr>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			$read = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND (threadattribute='topthread' OR threadattribute='conthread') ORDER BY id LIMIT $startvalue, $limit");
			$read7 = 0;
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			//Collecting Personal Information:
			$user = $r['username'];
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			//sorting out image
			if ($r1['photo'] != 0) {
			$imgsize = getimagesize("../users/images/photo/photo{$r1['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}}
			//online/offline?
			$read2 = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
			$r2 = mysql_fetch_array($read2);
			$deltatime = strtotime("now") - strtotime($r2['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			//alternative row coloring!
			if ($read7 % 2 == 0) {$color = "#f2f2f2";} else {$color = "#cccccc";}
			//time zone offset:
			$dis_func = 0; $date = $r['threaddate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$threaddate = $date;
			//content replacer:
			$entrycontent = $r['threadcontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			//forum classing
			$read9 = mysql_query("SELECT * FROM forum WHERE username='$user' AND (threadattribute='topthread' OR threadattribute='conthread')");
			$posts = mysql_num_rows($read9);
			include("postsclassing.php");
			//star no => stars img
			$read8 = 0;
			while ($read8 < $starno) {$starimg = "$starimg"."<img src=\"images/star.gif\">"; $read8 ++;}
			echo "<tr bgcolor=\"$color\">
				  <td width=\"25%\" align=center valign=top bgcolor=\"$color\" style=\"padding: 10px 0;\">
				  <a href=\"../users/profiles.php?user={$r1['username']}\">
				  <b>{$r1['displayname']} ({$r1['username']})</a></b><br />
				  <b>$level</b><br />$starimg<br />
				  <img src=\"../users/images/$onlinestatus.png\"> ". ucwords($onlinestatus) ."<br />";
			if ($r1['photo'] != 0) {echo "<img src=\"../users/images/photo/photo{$r1['photo']}.jpg\" height=\"". round($height) ."\" width=\"". round($width) ."\">";}
			echo "<br />Posts: $posts<br />
				  <b>{$r1['quote']}</b><br /></td>
				  <td width=\"100%\" valign=top height=\"200\" bgcolor=\"$color\">
				  <table width=\"100%\" height=\"100%\" bgcolor=\"$color\">
				  <tr valign=top height=\"10%\"><td valign=top align=left width=\"95%\">
				  <img src=\"../users/images/{$r['threadicon']}.gif\"> 
				  <b>{$r['threadtitle']}</b><br />{$threaddate}</td>
				  <td align=right valign=center width=\"100%\">";
			if ($r1['username'] == $username) {
			echo "<a href=\"editthread.php?id={$r['id']}\"><img src=\"images/modify.gif\"></a>";}
			echo "</td></tr>
				  <tr valign=top height=\"60%\"><td colspan=2><hr />
				  <div id=\"views\">$entrycontent</div>
				  </td></tr>
				  <tr valign=bottom height=\"20%\"><td valign=bottom colspan=2>
				  <p valign=bottom>";
			if ($r['threadmoddate'] != "0000-00-00 00:00:00") {
			$dis_func = 0; $date = $r['threadmoddate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$threadmoddate = $date;
			
			echo "<div id=\"views\"><b><i>Last Edited: $threadmoddate</i></b></div>";}
			if ($r1['signiture'] != "") {
			$entrycontent = $r1['signiture'];
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<hr />$entrycontent";}
			echo "</p></td></tr></table>
				  </td></tr>\r\n";
			$starimg = "";
			$read7 ++;}
			//Close the table
			echo "</table>";
			//Starts page links:
			echo "<p><table align=center id=\"forumtbl\"><tr><td align=center>";
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
			else {echo "NEXT&nbsp;";}}
			echo "</td></tr></table></p>";?>
			
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