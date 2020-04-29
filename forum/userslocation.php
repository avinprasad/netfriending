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
<title>Users Locations - Who's Online!?</title>
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
			<a href="userslocation.php">Users Locations</a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Forum Online Stats Record:
			include("onlinestats.php");
			
			//customized theme bg:
			$theme = $row['theme'];
			if ($theme == "") {$theme = 0;}
			
			//Users array sorting...
			$data = file("onlinestats/usernameusers.txt");
			foreach ($data as $line) {
			$explode = explode(",", $line);
			$deltatime = strtotime("now") - strtotime($explode[1]);
			if ($deltatime < 600) {
			$onlinearrays[] = array($explode[0], "User", $explode[1], $explode[2]);}}
			
			//Guests array sorting...
			$data = file("onlinestats/ipguests.txt");
			foreach ($data as $line) {
			$explode = explode(",", $line);
			$deltatime = strtotime("now") - strtotime($explode[1]);
			if ($deltatime < 600) {
			$onlinearrays[] = array($explode[0], "Guest", $explode[1], $explode[2]);}}
			
			//Users first...
			foreach ($onlinearrays as $onlinearray) {
			
			//For Users Only!!
			if ($onlinearray[1] == "User") {
			//Get displaynames from username:
			$user = $onlinearray[0];
			$read = mysql_query("SELECT displayname FROM userinfo WHERE username='$user'");
			$r = mysql_fetch_array($read);
			$dname = $r['displayname'];
			//Get profiles url!!
			$profurl = "../users/profiles.php?user={$onlinearray[0]}";}
			
			//Get the correct time:
			$dis_func = 3; $date = $onlinearray[2]; $timeoffset = $row['timeoffset']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			
			//Get Page URL, Query...Sort users page title accordingly to url quires:
			$query = explode("?", $onlinearray[3]);
			//Index Page...*****************************************************START###
			if ($query[0] == "index.php") {$location = "Forum Index"; $url = "index.php";}
			//Threads Page...
			elseif ($query[0] == "threads.php") {
			$pos = strpos($query[1], "cat=") + 4;
			while ($query[1][$pos] != "&" && $pos <= strlen($query[1])) {
			$cat = "$cat". $query[1][$pos] ."";
			$pos ++;}
			$cats = file("forum.txt");
			foreach ($cats as $category) {
			$explode = explode(",", $category);
			if ($explode[0] == $cat) {$location = $explode[1];}}
			$url = "threads.php?cat=$cat";}
			//View Threads Page...
			elseif ($query[0] == "viewthread.php") {
			$pos = strpos($query[1], "threadid=") + 9;
			while ($query[1]{$pos} != "&" && $pos <= strlen($query[1])) {
			$threadid = "$threadid". $query[1]{$pos} ."";
			$pos ++;}
			$read = mysql_query("SELECT threadtitle FROM forum WHERE threadid='$threadid' AND threadattribute='topthread'");
			$r = mysql_fetch_array($read); $location = $r['threadtitle']; $url = "viewthread.php?threadid=$threadid";}
			//Users Locations Page...
			elseif ($query[0] == "userslocation.php") {$location = "Users Locations"; $url = "userslocation.php";}
			elseif ($query[0] == "forumstats.php") {$location = "Forum Statistics"; $url = "forumstats.php";}
			elseif ($query[0] == "addthread.php") {$location = "Posting New Thread";}
			elseif ($query[0] == "editthread.php") {$location = "Editing Thread";}
			elseif ($query[0] == "replythread.php") {$location = "Replying Thread";}
			//*****************************************************FINISH###
			
			//Put Everything Back into an array...
			$sortedonlines[] = array($onlinearray[0], $location, $url, $date, $profurl, $dname);
			$location = ""; $url = ""; $date = ""; $profurl = ""; $dname = "";}?>
			<p>
			<table id="forumtbl">
			<tr height="30">
			<td align=center width="30%" background="../users/images/themes/<?php echo $theme;?>_gradient.gif"><b>Username/IP Address</b></a>
			<td align=center width="20%" background="../users/images/themes/<?php echo $theme;?>_gradient.gif"><b>Time Active</b></a>
			<td align=center width="50%" background="../users/images/themes/<?php echo $theme;?>_gradient.gif"><b>Page Location</b></a>
			</tr>
			<?php 
			foreach ($sortedonlines as $sortedonline) {
			echo "<tr height=\"25\"><td align=center bgcolor=\"#f2f2f2\">";
			if ($sortedonline[4] != "") {echo "<b><a href=\"". $sortedonline[4] ."\">". $sortedonline[5] ." (". $sortedonline[0] .")</a></b></td>";}
			else {echo "". $sortedonline[0] ."</td>";}
			echo "<td align=center bgcolor=\"#f2f2f2\">". $sortedonline[3] ."</td>";
			if ($sortedonline[2] != "") {echo "<td align=center bgcolor=\"#f2f2f2\"><a href=\"". $sortedonline[2] ."\">". $sortedonline[1] ."</a></td></tr>";}
			else {echo "<td align=center bgcolor=\"#f2f2f2\">". $sortedonline[1] ."</td></tr>";}}?>
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