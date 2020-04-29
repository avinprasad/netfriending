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
<?php $user = $_GET['user'];
$user = strtolower($user);
require("../mysqlconnection.php");
$read = mysql_query("SELECT displayname FROM userinfo WHERE username='$user'");
$r = mysql_fetch_array($read);
$dname = $r['displayname'];?>
<title><?php echo $dname?>'s Profile</title>
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
			<h4><?php echo "$dname's Profile";?></h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$read1 = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			
			//Get Age
			function birthday($birthday) 
			{list($year,$month,$day) = explode("-",$birthday);
			$year_diff  = date("Y") - $year;
			$month_diff = date("m") - $month;
			$day_diff   = date("d") - $day;
			if ($month_diff < 0) $year_diff--;
			elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
			return $year_diff;}
			$age = birthday($r1['birthday']);
			
			//Get birthdate explode
			$birthday = strtotime($r1['birthday']);
			$birthday = date("F d",$birthday);
			
			//Get local time
			$dis_func = 2; $date = "now"; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$localtime = $date;
			
			//Get online status
			$deltatime = strtotime("now") - strtotime($r1['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			
			//Last Active
			if ($r1['onlinetime'] == "0000-00-00 00:00:00") {$lastactive = "Never";}
			else{$dis_func = 0; $date = $r1['onlinetime']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$lastactive = $date;}
			
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r2 = mysql_fetch_array($read2);
			
			//photo width and length setup
			$imgsize = getimagesize("images/photo/photo{$r2['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 250 or $height > 375) {$width = $width/1.2; $height = $height/1.2;}
			
			//get country name
			$countries = file("countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($parts[0] == $r1['country']) {$country = $parts[1];}}
			
			//Get user's Friends
			$read3 = mysql_query("SELECT * FROM friendlist WHERE username='$user' AND status='accepted' ORDER BY RAND() LIMIT 0, 4");
			$read5 = mysql_query("SELECT * FROM friendlist WHERE username='$user' AND status='accepted'");
			$friends = mysql_num_rows($read5);
			
			//signiture replace text decorations:
			$entrycontent = $r2['signiture'];
			$textrcondition = 1;
			include("../textreplacer.php");
			
			//gender finalization:
			if ($r1['gender'] == "F") {$gender = "Female";}
			else {$gender = "Male";}
			
			//get friends status:
			$read6 = mysql_query("SELECT * FROM friendlist WHERE username='$username' AND user='$user' AND status='accepted'");
			$friendstatus = mysql_num_rows($read6);
			if ($friendstatus == 0) {$friendstitle = "Send Friend Request"; $friendsicon = "friends_add";}
			else {$friendstitle = "Delete Friend Request"; $friendsicon = "friends_delete";}
			
			//get photo gallery:
			$read7 = mysql_query("SELECT * FROM photo WHERE username='$user' AND photofile!='{$r2['photo']}' ORDER BY RAND() LIMIT 0, 3");
			
			//get update status:
			$read9 = mysql_query("(SELECT id, username, 'null' AS user, entrytitle AS title, entrydate AS dateorder, 'diary_1' AS attribute FROM diary WHERE username='$user') UNION (SELECT id, username, user, entrytitle AS title, entrydate AS dateorder, 'gb_1' AS attribute FROM guestbook WHERE user='$user') UNION (SELECT id, username, 'null' AS user, photoname AS title, photodate AS dateorder, 'photo_1' AS attribute FROM photo WHERE username='$user') UNION (SELECT id, username, user, entrytitle AS title, entrydate AS dateorder, entryattribute AS attribute FROM comment WHERE user='$user') UNION (SELECT threadid AS id, username, 'null' AS user, threadtitle AS title, threaddate AS dateorder, 'forum_1' AS attribute FROM forum WHERE (username='$user' AND threadattribute!='votethread')) UNION (SELECT id, username, user, 'null' AS title, date AS dateorder, 'friend_1' AS attribute FROM friendlist WHERE (username='$user' AND status='accepted')) ORDER BY dateorder DESC LIMIT 0, 10");?>
			
			<p><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
			<td align=center width="50%" valign=center><img src="images/photo/photo<?php echo $r2['photo'];?>.jpg" height="<?php echo round($height);?>" width="<?php echo round($width);?>" /></td>
			<td align=center width="100%" valign=center><?php while ($r7 = mysql_fetch_array($read7)) {
			$imgsize = getimagesize("images/photo/photo{$r7['photofile']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = round($width/1.2); $height = round($height/1.2);}
			echo "<img src=\"images/photo/photo{$r7['photofile']}.jpg\" width=\"$width)\" height=\"$height\" alt=\"{$r7['photoname']}\" style=\"border: 5px solid #cccccc; margin: 5px 0px 5px 0\" /><br />";}?>
			</tr></table>
	
			
			<p><img src="images/<?php echo $onlinestatus;?>.png"> 
			<?php echo $dname;?> is <?php echo ucwords($onlinestatus);?></p>
			
			<p>
			<a href="friendsrequests.php?page=add&user=<?php echo $r1['username'];?>" title="<?php echo $friendstitle;?>"><img src="images/icons/<?php echo $friendsicon;?>.gif" alt="" /></a>&nbsp;&nbsp;
			<a href="pmsend.php?user=<?php echo $r1['username'];?>" title="Send a Private Message"><img src="images/icons/mail.gif" alt="" /></a>&nbsp;&nbsp; 
			<a href="blog.php?user=<?php echo $r1['username'];?>" title="<?php echo $dname;?>'s Blog"><img src="images/icons/blog.gif" alt="" /></a>&nbsp;&nbsp;
			<a href="gb.php?user=<?php echo $r1['username'];?>" title="<?php echo $dname;?>'s Guestbook"><img src="images/icons/gb.gif" alt="" /></a>&nbsp;&nbsp;
			<a href="photo.php?user=<?php echo $r1['username'];?>" title="<?php echo $dname;?>'s Photo Gallery"><img src="images/icons/photo.gif" alt="" /></a>&nbsp;&nbsp;
			<a href="/forum/usersthreads.php?user=<?php echo $r1['username'];?>" title="<?php echo $dname;?>'s Posts"><img src="images/icons/post.gif" alt="" /></a>
			</p>
			
			<p><table align=center cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr><td id="views2" align=left valign=top bgcolor="#cccccc" width="30%"><b>Username</b></td>
			<td id="views2" align=left valign=top bgcolor="#f2f2f2" width="100%"><?php echo $r1['username'];?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#f2f2f2"><b>Name</b></td>
			<td id="views2" align=left valign=top bgcolor="#cccccc"><?php echo $r1['displayname'];?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#cccccc"><b>Gender</b></td>
			<td id="views2" align=left valign=top bgcolor="#f2f2f2"><?php echo $gender;?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#f2f2f2"><b>Age</b></td>
			<td id="views2" align=left valign=top bgcolor="#cccccc"><?php echo $age;?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#cccccc"><b>Birthday</b></td>
			<td id="views2" align=left valign=top bgcolor="#f2f2f2"><?php echo $birthday;?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#f2f2f2"><b>Local Time</b></td>
			<td id="views2" align=left valign=top bgcolor="#cccccc"><?php echo $localtime;?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#cccccc"><b>Country</b></td>
			<td id="views2" align=left valign=top bgcolor="#f2f2f2"><?php echo $country?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#f2f2f2"><b>Location</b></td>
			<td id="views2" align=left valign=top bgcolor="#cccccc"><?php echo $r1['location'];?></td></tr>
			<tr><td id="views2" align=left valign=top bgcolor="#cccccc"><b>Last Active</b></td>
			<td id="views2" align=left valign=top bgcolor="#f2f2f2"><?php echo $lastactive;?></td></tr>
			</table></p>
			
			<?php $entrycontent = $r2['signiture'];
			$textrcondition = 1;
			include("../textreplacer.php");

			$parray = array("Quote" => $r2['quote'], "About" => $r2['about'], "Hobbies" => $r2['hobby'], "Favourites" => $r2['favourite'], "Wishlist" => $r2['wish'], "Signiture" => $entrycontent);
			$printall = false;
			foreach ($parray as $pkey => $pstring) {if ($pstring != "") {$printall = true;}}
			if ($printall == true) {echo "<p><table align=center cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">";
			foreach ($parray as $pkey1 => $pstring1) {if ($pstring1 != "") {echo "<tr><td id=\"views2\" align=left valign=top bgcolor=\"#cccccc\" width=\"100%\">
			<b>$dname's $pkey1</b></td></tr>
			<tr><td id=\"views\" align=left valign=top bgcolor=\"#f2f2f2\" width=\"100%\">$pstring1</td></tr>";}}
			echo "</table></p>";}?>
			
			<?php $read8 = 0; echo "<p><table align=center cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">";
			while ($updates = mysql_fetch_array($read9)) {
			$dis_func = 0; $date = $updates['dateorder']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$read4 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$updates['username']}'");
			$r3 = mysql_fetch_array($read4);
			if ($read8 % 2 == "0") {$color = "f2f2f2";}
			else {$color = "cccccc";}
			if ($updates['attribute'] == "diary_1") {$update1 = "made a new diary entry <a href=\"blog.php?user={$updates['username']}&id={$updates['id']}\"><b>{$updates['title']}</b></a> post"; $icon = "blog_add";}
			elseif ($updates['attribute'] == "gb_1") {$read11 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$updates['user']}'");
			$r2 = mysql_fetch_array($read11);
			$update1 = "commented <a href=\"gb.php?user={$updates['username']}\"><b>{$updates['title']}</b></a> in <a href=\"profiles.php?user={$updates['user']}\"><b>{$r2['displayname']} ({$updates['user']})</a></b>'s guestbook"; $icon = "gb_add";}
			elseif ($updates['attribute'] == "photo_1") {$update1 = "posted <a href=\"photo.php?user={$updates['username']}\"><b>{$updates['title']}</b></a> in photo gallery"; $icon = "photo_add";}
			elseif ($updates['attribute'] == "friend_1") {$read11 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$updates['user']}'");
			$r2 = mysql_fetch_array($read11);
			$update1 = "became friends with <a href=\"profiles.php?user={$updates['user']}\"><b>{$r2['displayname']} ({$updates['user']})</b></a>"; $icon = "friends_add";}
			elseif ($updates['attribute'] == "forum_1") {$update1 = "posted <a href=\"/forum/viewthread.php?threadid={$updates['id']}\"><b>{$updates['title']}</b></a> in forum"; $icon = "post_add";}
			else {$read11 = mysql_query("SELECT displayname FROM userinfo WHERE username='{$updates['user']}'");
			$r2 = mysql_fetch_array($read11);
			$update1 = "commented <a href=\"viewthread.php?threadid={$updates['id']}\"><b>{$updates['title']}</b></a> in <a href=\"profiles.php?user={$updates['user']}\"><b>{$r2['displayname']} ({$updates['user']})</b></a>'s {$updates['attribute']}"; $icon = "comment_add";}
			echo "<tr bgcolor=\"#$color\"><td align=left valign=top width=\"30%\" style=\"padding: 5px 5px 5px 5px;\"><img src=\"images/icons/$icon.gif\" \> <a href=\"profiles.php?user={$updates['username']}\"><b>{$r3['displayname']} ({$updates['username']})</b></a> has $update1 on $date</td></tr>";
			$read8 ++;}
			echo "</table>";?>
						
			<p align=center>
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr><td id="views2" bgcolor="#cccccc" align=left><b><?php echo $dname;?> has <?php echo $friends;?> friends</b></td>
			<td id="views3" bgcolor="#f2f2f2" align=right><a href="friendslist.php?user=<?php echo $r1['username'];?>"><b>More friends of <?php echo $dname;?></b></a></td></tr>
			<?php $read = 0;
			while ($r3 = mysql_fetch_array($read3)) {
			$user2 = $r3['user'];
			
			$read4 = mysql_query("SELECT * FROM usercus WHERE username='$user2'");
			$r4 = mysql_fetch_array($read4);
						
			//img size...
			$imgsize = getimagesize("images/photo/photo{$r4['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 400 or $height > 120) {$width = $width/1.2; $height = $height/1.2;}
			if ($read == 0 or $read == 3) {$color = "#f2f2f2";} 
			else {$color = "#cccccc";}
			if ($read % 2 == 0) {echo "<tr height=\"150px\">";}
			echo "<td align=center width=\"50%\" bgcolor=\"$color\"><a href=\"profiles.php?user=$user2\"><b>{$r4['displayname']} ($user2)</b></a><br />
			<a href=\"profiles.php?user=$user2\">
			<img src=\"images/photo/photo{$r4['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></a></td>";
			if ($read % 2 != 0) {echo "</tr>";}
			$read ++;}?>
			</table>
			</p>	
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