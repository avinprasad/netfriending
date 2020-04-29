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
<title>NetFriending Forum Statistics</title>
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
			<a href="forumstats.php">Forum Statistics</a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Forum Online Stats Record:
			include("onlinestats.php");

			//customized theme bg:
			$theme = $row['theme'];
			if ($theme == "") {$theme = 0;}

			function stats($title, $querytotal, $querydb, $number, $linkcondition, $unit, $theme1) {?>
			<p>
			<table id="forumtbl">
			<tr height="30">
			<td align=center width="100%" colspan=3 background="../users/images/themes/<?php echo $theme1;?>_gradient.gif"><b><?php echo $title;?></b></a></td>
			</tr>
			<?php //Get top 5 posters:
			$read = mysql_query($querytotal);
			$total = 0;
			while ($r = mysql_fetch_array($read)) {
			$total += $r[$number];}
			$read = mysql_query($querydb);
			while ($r = mysql_fetch_array($read)) {
			//Different links for each:
			if ($linkcondition == 0) {$read1 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$r['username']}'");
			$r1 = mysql_fetch_array($read1);
			$url = "../users/profiles.php?user={$r['username']}"; $urlname = "{$r1['displayname']} ({$r['username']})";}
			elseif ($linkcondition == 1) {$cats = file("forum.txt");
			foreach ($cats as $cat) {$explode = explode(",", $cat);
			if ($r['forum'] == $explode[0]) {$forum = $explode[1];}}
			$url = "threads.php?cat={$r['forum']}"; $urlname = $forum;}
			elseif ($linkcondition == 2) {$read1 = mysql_query("SELECT threadtitle FROM forum WHERE threadid='{$r['threadid']}' AND threadattribute='topthread'");
			$r1 = mysql_fetch_array($read1);
			$url = "viewthread.php?threadid={$r['threadid']}"; $urlname = $r1['threadtitle'];}
			elseif ($linkcondition == 3) {$url = "viewthread.php?threadid={$r['threadid']}"; $urlname = $r['threadtitle'];}
			elseif ($linkcondition == 4) {$read1 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$r['username']}'");
			$r1 = mysql_fetch_array($read1);
			$url = "../users/profiles.php?user={$r['username']}"; $urlname = "{$r1['displayname']} ({$r['username']})";}
			//Show the table:
			echo "<tr height=\"30\"><td bgcolor=\"#f2f2f2\" width=\"40%\" style=\"padding-left: 10px;\">
			<b><a href=\"$url\">$urlname</a></b></td>";
			$width = round(($r[$number]/$total) * 180);
			echo "<td bgcolor=\"#f2f2f2\" width=\"43%\">
			<img src=\"images/bar.gif\" width=\"$width\" height=\"20\"></td>";
			echo "<td bgcolor=\"#f2f2f2\" width=\"100%\" align=center><b>{$r[$number]} $unit</b></td></tr>";}?>
			</table>
			</p>
			<?php }?>
			
			<?php stats("Top 5 Posters", "SELECT COUNT(id) FROM forum WHERE threadattribute='topthread' OR threadattribute='conthread' GROUP BY username ORDER BY COUNT(id) DESC LIMIT 5", "SELECT username, COUNT(id) FROM forum WHERE threadattribute='topthread' OR threadattribute='conthread' GROUP BY username ORDER BY COUNT(id) DESC LIMIT 5", "COUNT(id)", 0, "Posts", $theme);
			stats("Top 5 Forum Categories", "SELECT COUNT(id) FROM forum WHERE threadattribute='topthread' GROUP BY forum ORDER BY COUNT(id) DESC LIMIT 5", "SELECT forum, COUNT(id) FROM forum WHERE threadattribute='topthread' GROUP BY forum ORDER BY COUNT(id) DESC LIMIT 5", "COUNT(id)", 1, "Posts", $theme);
			stats("Top 5 Topics (by Replies)", "SELECT COUNT(id) FROM forum WHERE threadattribute='conthread' GROUP BY threadid ORDER BY COUNT(id) DESC LIMIT 5", "SELECT threadid, COUNT(id) FROM forum WHERE threadattribute='conthread' GROUP BY threadid ORDER BY COUNT(id) DESC LIMIT 5", "COUNT(id)", 2, "Replies", $theme);
			stats("Top 5 Topics (by Views)", "SELECT threadview FROM forum WHERE threadattribute='topthread' ORDER BY threadview DESC LIMIT 5", "SELECT * FROM forum WHERE threadattribute='topthread' ORDER BY threadview DESC LIMIT 5", "threadview", 3, "Views", $theme);
			stats("Top 5 Topic Starters", "SELECT COUNT(id) FROM forum WHERE threadattribute='topthread' GROUP BY username ORDER BY COUNT(id) DESC LIMIT 5", "SELECT username, COUNT(id) FROM forum WHERE threadattribute='topthread' GROUP BY username ORDER BY COUNT(id) DESC LIMIT 5", "COUNT(id)", 4, "Topics", $theme);?>
			
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