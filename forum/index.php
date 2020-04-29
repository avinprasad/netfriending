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
<title>NetFriending Forum</title>
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
			<a href="index.php">Forum Index</a>
			</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");	
			//Require Forum Online Stats Record:
			include("onlinestats.php");
			
			function forum($string,$time,$description,$dateformat,$timeformat) {
			//getting forum NAME:
			$forums = file("forum.txt");
			foreach($forums as $forum) {$data = explode(",", $forum);
			if ($data[0] == $string) {$forumname = $data[1];}}
			
			//counting posts:
			$read = mysql_query("SELECT * FROM forum WHERE forum='$string' AND threadattribute='topthread'");
			$posts = mysql_num_rows($read);
			
			//counting replies:
			$read = mysql_query("SELECT * FROM forum WHERE forum='$string' AND threadattribute='conthread'");
			$replies = mysql_num_rows($read);
			
			//latest posts
			$read = mysql_query("SELECT * FROM forum WHERE forum='$string' AND threadattribute!='votethread' ORDER BY threaddate DESC LIMIT 1");
			$lastpost = mysql_fetch_array($read);
			//last post date time zone offset:
			$dis_func = 0; $date = $lastpost['threaddate']; $timeoffset = $time; $date_format = $dateformat; $time_format = $timeformat;
			include("../disfunc.php");
			$lastpostdate = $date;
			
			$threadtitle = substr($lastpost['threadtitle'], 0, 16);
			if (strlen($lastpost['threadtitle']) > 16) {$threadtitle = "$threadtitle...";}?>
			<tr height="50" bgcolor="#f2f2f2">
			<td width="10%" align=center><img src="../images/logo.gif" width="30" height="30"></td>
			<td width="50%"><a href="threads.php?cat=<?php echo $string;?>"><b><?php echo $forumname;?></b></a>
			<br /><?php echo $description;?></td>
			<td width="15%" align=center><?php echo $posts?> Posts<br /><?php echo $replies?> Replies</td>
			<td width="100%" align=center><?php if ($lastpost['username'] == "" or $lastpost['threaddate'] == "") 
			{echo "-";}
			else {echo "<b>In:</b> <a href=\"viewthread.php?threadid={$lastpost['threadid']}\">". $threadtitle ."</a><br /><b>By:</b> <a href=\"../users/profiles.php?user={$lastpost['username']}\">{$lastpost['username']}</a> @ $lastpostdate";}
			echo "</td></tr>";}
			
			//Configure customized tab theme
			$theme = $row['theme'];
			if ($theme == "") {$theme = 0;}?>
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Start your Posts HERE!</b></td>
			</tr>
			<?php 			
			forum("new", $row['timeoffset'], "You can introduce yourself here and get to know others", $row['dateformat'], $row['timeformat']);
			forum("instruction", $row['timeoffset'], "You can learn more about how to use NetFriending here", $row['dateformat'], $row['timeformat']);
			?>
			</table>
			</p>
			
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Serious Stuff post HERE</b></td>
			</tr>
			<?php 			
			forum("job", $row['timeoffset'], "Are you looking for jobs? or someone to hire?", $row['dateformat'], $row['timeformat']);
			forum("school", $row['timeoffset'], "Despirately needing help with homework?", $row['dateformat'], $row['timeformat']);
			forum("news", $row['timeoffset'], "Discussing about the current events", $row['dateformat'], $row['timeformat']);
			?>
			</table>
			</p>
			
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Fun AND Entertainments</b></td>
			</tr>
			<?php 			
			forum("comedy", $row['timeoffset'], "Funny videos pictures...", $row['dateformat'], $row['timeformat']);
			forum("music", $row['timeoffset'], "Musical discussions", $row['dateformat'], $row['timeformat']);
			forum("movie", $row['timeoffset'], "Movie discussions", $row['dateformat'], $row['timeformat']);
			forum("game", $row['timeoffset'], "Discuss about the new games or game machines", $row['dateformat'], $row['timeformat']);
			?>
			</table>
			</p>
			
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Others</b></td>
			</tr>
			<?php 			
			forum("tech", $row['timeoffset'], "New technologies", $row['dateformat'], $row['timeformat']);
			forum("sport", $row['timeoffset'], "Exercising your fingers here.", $row['dateformat'], $row['timeformat']);
			forum("holiday", $row['timeoffset'], "Looking for someone who actually knows where you are about to go well?", $row['dateformat'], $row['timeformat']);
			?>
			</table>
			</p>
			
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Only @ NetFriending</b></td>
			</tr>
			<?php 			
			forum("help", $row['timeoffset'], "Need some helps with NetFriending?", $row['dateformat'], $row['timeformat']);
			forum("support", $row['timeoffset'], "Get some public support here", $row['dateformat'], $row['timeformat']);
			forum("comment", $row['timeoffset'], "Any comments or suggestions on NetFriending will be accepted", $row['dateformat'], $row['timeformat']);
			?>
			</table>
			</p>
			
			<?php //posts stats:
			$read = mysql_query("SELECT * FROM forum WHERE threadattribute='topthread' or threadattribute='conthread'");
			$totalposts = mysql_num_rows($read);
			$read = mysql_query("SELECT * FROM forum WHERE threadattribute='topthread'");
			$totaltopics = mysql_num_rows($read);
			$read = mysql_query("SELECT * FROM forum WHERE threadattribute!='votethread' ORDER BY threaddate DESC LIMIT 1");
			$lastpost = mysql_fetch_array($read);
			
			$dis_func = 0; $date = $lastpost['threaddate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$lastthreaddate = $date;
			
			$lastuser = $lastpost['username'];
			
			$read = mysql_query("SELECT displayname FROM userinfo WHERE username='$lastuser'");
			$r1 = mysql_fetch_array($read);
			
			//guests online:
			$lines = file("onlinestats/ipguests.txt");
			foreach ($lines as $line) {
			$data = explode(",", $line);
			$deltatime = strtotime("now") - strtotime($data[1]);
			if ($deltatime < 600) {$guestsonline[] = $data[0];}}
			$guests = count($guestsonline);
			
			//users online:
			$lines = file("onlinestats/usernameusers.txt");
			foreach ($lines as $line) {
			$data = explode(",", $line);
			$deltatime = strtotime("now") - strtotime($data[1]);
			if ($deltatime < 600) {$usersonline[] = $data[0];}}
			$users = count($usersonline);
			//get displaynames from username:
			require("../mysqlconnection.php");
			foreach ($usersonline as $user) {
			$read = mysql_query("SELECT displayname FROM userinfo WHERE username='$user'");
			$r = mysql_fetch_array($read);
			$usersonlinelist[$user] = $r['displayname'];}
			
			//most online records:
			$datetime = date("Y-m-d H:i:s");
			$mostonline = file_get_contents("onlinestats/mostonline.txt");
			$nowonline = $users + $guests;
			$array = explode(",", $mostonline);
			$mostonline = $array[0];
			if ($nowonline >= $mostonline) {
			$file = fopen("onlinestats/mostonline.txt","w");
			$write = fwrite($file,"$nowonline,$datetime,");
			fclose($file);}
			//get most online datetime
			$dis_func = 0; $date = $array[1]; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$datetime1 = $date;
			
			//today most online:
			$todayonline = file("onlinestats/todayonline.txt");
			$lines = count($todayonline) - 1;
			$contents = explode(",", $todayonline[$lines]);
			$timenow = date("Y-m-d");
			if ($timenow == $contents[1]) {
			if ($nowonline > $contents[0]) {
			$todayonline[$lines] = "$nowonline,$timenow,";
			$handle = fopen("onlinestats/todayonline.txt", "w");
			foreach ($todayonline as $val) {$write = fwrite($handle, $val);}
			fclose($handle);}}
			else {$file = fopen("onlinestats/todayonline.txt","a");
			$write = fwrite($file,"\n$nowonline,$timenow,");
			fclose($file);}
			?>
			<p>
			<table id="forumtbl">
			<tr height="30" align=center>
			<td colspan=4 background="../users/images/themes/<?php echo $theme;?>_gradient.gif">
			<b>Forum Statistical Chart</b></td>
			</tr>
			<tr height="70">
			<td bgcolor="#f2f2f2" id="views2">
			<p>Total Posts: <b><?php echo $totalposts;?></b>, Total Topics: <b><?php echo $totaltopics;?></b>
			<b><a href="forumstats.php">[More Stats]</a></b><br />
			Last Post: <b>"<a href="viewthread.php?threadid=<?php echo $lastpost['threadid'];?>"><?php echo $lastpost['threadtitle'];?></a>"</b>
			(<?php echo $lastthreaddate;?>)<br />By: <a href="../users/profiles.php?user=<?php echo $lastuser;?>">
			<b><?php echo $r1['displayname'];?> (<?php echo $lastuser;?>)</b></p>
			</td></tr>
			<tr height="70">
			<td bgcolor="#f2f2f2" id="views2">
			<p><b><?php echo $guests;?> Guests, <?php echo $users;?> Users</b>
			<b><a href="userslocation.php">[Currently Active Users]</a></b><br />
			Users active in the past 10 minutes:<br />
			<?php foreach ($usersonlinelist as $user => $name) {echo "<b><a href=\"../users/profiles.php?user=$user\">$name ($user)</a></b>, ";}?>
			<?php if ($users == 0) {echo "<b>No Users Online!</b>";}?>
			<br />
			Most Online Today: <b><?php echo $contents[0];?></b>, 
			Most Online Ever: <b><?php echo $mostonline;?></b> (<?php echo $datetime1; if ($username == "") {echo ", GMT+8";}?>)
			</p>
			</td></tr>
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