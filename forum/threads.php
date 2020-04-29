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
<?php $category = $_GET['cat'];
$forums = file("forum.txt");
foreach ($forums as $forum) {
$data = explode(",", $forum);
if ($data[0] == $category) {$label = "{$data[1]} Forum";}}
if ($label == "") {$invalid = true;
$label = "ErrorDocument 404";}?>
<title><?php echo $label;?></title>
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
			<a href="<?php echo "threads.php?{$_SERVER['QUERY_STRING']}";?>"><?php echo $label;?></a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			//Require Forum Online Stats Record:
			include("onlinestats.php");
			//Configure customized tab theme
			$theme = $row['theme'];
			if ($theme == "") {$theme = 0;}?>
			<p><a href="addthread.php?cat=<?php echo $category;?>">Post New Thread</a> | 
			<a href="addthread.php?cat=<?php echo $category;?>&poll=block">Post New Poll</a>
			</p>
			<style type="text/css">
			font.customize
			{
			color: #000000;
			}
			font.label
			{
			font-size: 1em;
			color: #000000;
			text-decoration: bold;
			}
			td.label
			{
			background-image:
			url('../users/images/themes/<?php echo $theme;?>_gradient.gif');
			text-align: center;
			}
			</style>
			<p>
			<?php $order = $_GET['order'];
			//Order Sort
			if ($order == "userasc") {$orderby = "ORDER BY threadsticky, username"; $menu1 = "v";}
			elseif ($order == "userdes") {$orderby = "ORDER BY threadsticky, username DESC"; $menu1 = "^";}
			elseif ($order == "titleasc") {$orderby = "ORDER BY threadsticky, threadtitle"; $menu2 = "v";}
			elseif ($order == "titledes") {$orderby = "ORDER BY threadsticky, threadtitle DESC"; $menu2 = "^";}
			elseif ($order == "viewasc") {$orderby = "ORDER BY threadsticky, threadview"; $menu3 = "v";}
			elseif ($order == "viewdes") {$orderby = "ORDER BY threadsticky, threadview DESC"; $menu3 = "^";}
			//diff from now on (more complicated, because need to have the latest conthread left join):
			elseif ($order == "replyasc") {$sql = "SELECT t1.*, COUNT(t2.threadid) AS num_replies FROM forum AS t1 LEFT JOIN forum AS t2 ON t1.threadid=t2.threadid AND t2.threadattribute='conthread' WHERE t1.threadattribute='topthread' AND t1.forum='$category' GROUP BY t1.threadid ORDER BY t1.threadsticky, num_replies"; $menu4 = "v";}
			elseif ($order == "replydes") {$sql = "SELECT t1.*, COUNT(t2.threadid) AS num_replies FROM forum AS t1 LEFT JOIN forum AS t2 ON t1.threadid=t2.threadid AND t2.threadattribute='conthread' WHERE t1.threadattribute='topthread' AND t1.forum='$category' GROUP BY t1.threadid ORDER BY t1.threadsticky, num_replies DESC"; $menu4 = "^";}
			elseif ($order == "dateasc") {$sql = "SELECT t1.*, MAX(t2.threaddate) AS last_reply FROM forum AS t1 LEFT JOIN forum AS t2 ON t1.threadid=t2.threadid AND (t2.threadattribute='conthread' OR t2.threadattribute='topthread') WHERE t1.threadattribute='topthread' AND t1.forum='$category' GROUP BY t1.threadid ORDER BY t1.threadsticky, last_reply"; $menu5 = "v";}
			else {$order = "datedes";$sql = "SELECT t1.*, MAX(t2.threaddate) AS last_reply FROM forum AS t1 LEFT JOIN forum AS t2 ON t1.threadid=t2.threadid AND (t2.threadattribute='conthread' OR t2.threadattribute='topthread') WHERE t1.threadattribute='topthread' AND t1.forum='$category' GROUP BY t1.threadid ORDER BY t1.threadsticky, last_reply DESC"; $menu5 = "^";}
			//Link Menu
			if ($order == "userasc") {$orderusermenu = "userdes";}
			else {$orderusermenu = "userasc";}
			if ($order == "titleasc") {$ordertitlemenu = "titledes";}
			else {$ordertitlemenu = "titleasc";}
			if ($order == "viewasc") {$orderviewmenu = "viewdes";}
			else {$orderviewmenu = "viewasc";}
			if ($order == "replyasc") {$orderreplymenu = "replydes";}
			else {$orderreplymenu = "replyasc";}
			if ($order == "dateasc") {$orderdatemenu = "datedes";}
			else {$orderdatemenu = "dateasc";}
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Table & Top Links
			echo "<table id=\"forumtbl\">
						<tr height=30>
						  <td width=\"6%\" class=\"label\"><font class=\"label\"></td>
						  <td width=\"6%\" class=\"label\"><font class=\"label\"></td>
						  <td width=\"28%\" class=\"label\"><font class=\"label\">
						  <a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$page&order=$ordertitlemenu\"><b>$menu2 TITLE</b></a></td>
						  <td width=\"16%\" class=\"label\"><font class=\"label\">
						  <a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$page&order=$orderusermenu\"><b>$menu1 STARTER</b></a></td>
						  <td width=\"12%\" class=\"label\"><font class=\"label\">
						  <a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$page&order=$orderreplymenu\"><b>$menu4 REPLIES</b></a></td>
						  <td width=\"10%\" class=\"label\"><font class=\"label\">
						  <a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$page&order=$orderviewmenu\"><b>$menu3 VIEWS</b></a></td>
						  <td width=\"100%\" class=\"label\"><font class=\"label\">
						  <a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$page&order=$orderdatemenu\"><b>$menu5 LAST POST</b></a></td>
						</tr>";
			
			//Pagination Script Starts:
			//Set number of entries on each page:
			$limit = 10;
			//Query the db to get total entries:
			$read = mysql_query("SELECT * FROM forum WHERE forum='$category' AND threadattribute='topthread'");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $threads_nothreads;}
			elseif ($read === false) {$pagination = false;
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=7 align=center>$db_error</td></tr>";}
			
			if ($pagination == true) {
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			//MySQL Query, basic structure:
			if ($order == "userasc" or $order == "userdes" or $order == "titleasc" or $order == "titledes" or $order == "viewasc" or $order == "viewdes") {$sql = "SELECT * FROM forum WHERE forum='$category' AND threadattribute='topthread' $orderby";}
			$read = mysql_query("$sql LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			//latest posts
			$threadid = $r['threadid'];
			$read1 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute!='votethread' ORDER BY threaddate DESC LIMIT 1");
			$r1 = mysql_fetch_array($read1);
			//time zone offset:
			$dis_func = 0; $date = $r1['threaddate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$threaddate = $date;
			//thread sticky coloring!
			if ($r['threadsticky'] == 0) {$color = "#cccccc";} else {$color = "#f2f2f2";}
			//counting replies:
			$read2 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='conthread'");
			$replies = mysql_num_rows($read2);
			//Get the image**************************************************:
			//STARTING with USERNAME INVOLVEMENT
			$read3 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND username='$username'");
			$userinvolvement = mysql_num_rows($read3);
			if ($userinvolvement > 0) {$uiimg = "my_";}
			//GOING on to REPLY FREQUENCY
			$read4 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadattribute='conthread'");
			$threadfrequency = mysql_num_rows($read4);
			if ($threadfrequency >= 20) {$tfimg = "veryhot_";}
			elseif ($threadfrequency >= 10 && $threadfrequency < 20) {$tfimg = "hot_";}
			else {$tfimg = "normal_";}
			//LASTLY is it POLL or POST?
			$read5 = mysql_query("SELECT * FROM forum WHERE threadid='$threadid' AND threadpoll!=''");
			$threadpoll = mysql_num_rows($read5);
			if ($threadpoll > 0) {$tpimg = "poll.gif";}
			else {$tpimg = "post.gif";}
			//IMG Getting DONE***********************************************
			echo "<tr height=25 bgcolor=\"$color\">
			<td align=center><font class=\"customize\"><img src=\"images/$uiimg$tfimg$tpimg\"></td>
			<td align=center><font class=\"customize\"><img src=\"../users/images/{$r['threadicon']}.gif\"></td>
			<td valign=top><font class=\"customize\"><a href=\"viewthread.php?threadid={$r['threadid']}\">{$r['threadtitle']}</a>";
			if ($r['threadsticky'] == 0) {echo "<img src=\"images/sticky.gif\" align=right>";}
			if ($r['threadlock'] == "locked") {echo "<img src=\"images/lock.gif\" align=right>";}
			echo "</td>
			<td align=center><font class=\"customize\"><a href=\"../users/profiles.php?user={$r['username']}\">{$r['username']}</a></td>
			<td align=center><font class=\"customize\">$replies</td>
			<td align=center><font class=\"customize\">{$r['threadview']}</td>
			<td align=center><font class=\"customize\"><img src=\"images/last_post.gif\">
			<a href=\"../users/profiles.php?user={$r1['username']}\">{$r1['username']}</a> @ $threaddate</td></tr>\n";
			$uiimg = ""; $tfimg = ""; $tpimg = "";}
			//Close the table
			echo "</table>";
			//Starts page links:
			echo "<p><table id=\"forumtbl\" align=center><tr><td align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=1&order=$order\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$pageprev&order=$order\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$i&order=$order\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$pagenext&order=$order\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?cat=$category&page=$totalpages&order=$order\">>></a>";}
			else {echo "NEXT&nbsp;";}}
			echo "</td></tr></table></p>";?>
			</p><p></p>
			<p>
			<table id="forumtbl">
			<tr height="30">
			<td width="100%" align=center background="../users/images/themes/<?php echo $theme;?>_gradient.gif" colspan=2> 
			<b>IMAGE KEYS</b></td></tr>
			<tr bgcolor="#f2f2f2">
			<td style="padding: 20px; vertical-align: top;" height="97" width="418">
			<img alt src="images/my_normal_post.gif" align="middle" width="20" height="20"> 
			Topic you have posted in<br>
			<img alt src="images/normal_post.gif" align="middle" width="20" height="20"> 
			Normal Topic<br>
			<img alt src="images/hot_post.gif" align="middle" width="20" height="20"> 
			Hot Topic (More than 10 replies)<br>
			<img alt src="images/veryhot_post.gif" align="middle" width="20" height="20"> 
			Very Hot Topic (More than 20 replies)</p></td>
			<td style="padding: 20px; vertical-align: top;" height="97" width="439">
			<p><img alt src="images/lock.gif" align="middle" width="16" height="16"> 
			Locked Topic<br>
			<img alt src="images/sticky.gif" align="middle" width="16" height="16">
			Sticky Topic<br>
			<img alt src="images/normal_poll.gif" align="middle" width="20" height="20"> 
			Poll</td>
			</tr>
			</table>
			</p>
			
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