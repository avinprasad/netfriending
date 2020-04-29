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
<title>My Friend List</title>
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
			<?php $type = $_GET['type'];
			if ($type == "outgoing") {echo "<h4>Outgoing Friend Requests</h4>";}
			elseif ($type == "incoming") {echo "<h4>Incoming Friend Requests</h4>";}
			else {echo "<h4>My Friends List</h4>";}
			
			//Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			$order = $_GET['order'];
			if ($order == "userasc") {$orderby = "ORDER BY user";}
			elseif ($order == "userdes") {$orderby = "ORDER BY user DESC";}
			elseif ($order == "dateasc") {$orderby = "ORDER BY date";}
			else {$order = "datedes";$orderby = "ORDER BY date DESC";}
			
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 5;
			//Query the database to get total entries:
			if ($type == "outgoing") {$query_count = "SELECT * FROM friendlist WHERE username='$username' AND (status='pending' OR status='declined')";}
			elseif ($type == "incoming") {$query_count = "SELECT * FROM friendlist WHERE user='$username' AND status='pending'";}
			else {$query_count = "SELECT * FROM friendlist WHERE username='$username' AND status='accepted'";}
			$read = mysql_query($query_count);
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $friends_noresults;}
			elseif ($read === false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			echo "<p><b>$totalrows results in this section!</b></p>";
			echo "<table border=0 width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=center>";
			//Read entries from mysql:
			if ($type == "outgoing") {$query = "SELECT * FROM friendlist WHERE username='$username' AND (status='pending' OR status='declined') $orderby LIMIT $startvalue, $limit";}
			elseif ($type == "incoming") {$query = "SELECT * FROM friendlist WHERE user='$username' AND status='pending' $orderby LIMIT $startvalue, $limit";}
			else {$query = "SELECT * FROM friendlist WHERE username='$username' AND status='accepted' $orderby LIMIT $startvalue, $limit";}
			$read = mysql_query($query);
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			if ($type == "incoming" ) {$user = $r['username'];}
			else {$user = $r['user'];}
			$read1 = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r2 = mysql_fetch_array($read2);
			$imgsize = getimagesize("images/photo/photo{$r2['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			$about = substr($r2['about'], 0, 160);
			$deltatime = strtotime("now") - strtotime($r1['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			$dis_func = 0; $date = $r['date']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			echo "<tr height=\"100px\"><td align=center valign=center bgcolor=\"#cccccc\" width=\"32%\"><img src=\"images/photo/photo{$r2['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></td><td align=left valign=center bgcolor=\"#f2f2f2\"width=\"100%\" ><img src=\"images/$onlinestatus.png\"><a href=\"profiles.php?user={$r1['username']}\"><b>{$r1['displayname']} ({$r1['username']})</b></a><br /><b>Friends Since:</b> $date<br /><b>Status:</b> ". ucwords($r['status']) ."\n";
			if ($type == "outgoing") {echo "<br /><a href=\"friendsrequests.php?page=delete&user={$r1['username']}\">Delete the Request</a>";}
			elseif ($type == "incoming") {echo "<br /><a href=\"friendsrequests.php?page=action&user={$r1['username']}\">Pending the Request</a>";}
			else {echo "<br /><a href=\"friendsrequests.php?page=delete&user={$r1['username']}\">Delete from friends</a>";}
			echo "</td></tr><tr></tr>\n";}
			echo "</table>";
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?type=$type&page=1&order=$order\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?type=$type&page=$pageprev&order=$order\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?type=$type&page=$i&order=$order\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?type=$type&page=$pagenext&order=$order\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?type=$type&page=$totalpages&order=$order\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}?>
			<p>
			<select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($order, $type){if ($order == $type) echo "selected=\"selected\"";}?>
				<option value="">Sort Order...</option>
				<option value="friends.php?type=<?php echo $type?>&order=userasc" <?php select($order, "userasc");?>>Username Accending</option>
				<option value="friends.php?type=<?php echo $type?>&order=userdes" <?php select($order, "userdes");?>>Username Deccending</option>
				<option value="friends.php?type=<?php echo $type?>&order=dateasc" <?php select($order, "dateasc");?>>Date Accending</option>
				<option value="friends.php?type=<?php echo $type?>&order=datedes" <?php select($order, "datedes");?>>Date Deccending</option>
			</select>
			<select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
				<option value="">Pending Requests...</option>
				<option value="friends.php" <?php select($type, "");?>>Friends List</option>
				<option value="friends.php?type=incoming" <?php select($type, "incoming");?>>Incoming Friend Requests</option>
				<option value="friends.php?type=outgoing" <?php select($type, "outgoing");?>>Outgoing Friend Requests</option>
			</select>
			</p>
			<br />
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