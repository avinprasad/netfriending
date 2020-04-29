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
<title>Browse Members</title>
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
			<h4>Search Members</h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if ($_GET['search'] != "user" && $_GET['search'] != "name" && $_GET['search'] != "email")
			{echo $invalid_searchtype;}
			###########################################search user####################################################
			if ($_GET['search'] == "user"){
			$user = urldecode($_GET['search_query']);
			$age = urldecode($_GET['age']);
			$country = urldecode($_GET['country']);
			$gender = urldecode($_GET['gender']);
			$photo = urldecode($_GET['photo']);
			
			//Results url string:
			$urlstring = "&search=user&search_query=". urlencode($user) ."&age=". urlencode($age) ."&country=". urlencode($country) ."&gender=". urlencode($gender) ."&photo=". urlencode($photo) ."";
			
			//username query setup, no need for if.
			$userquery = " AND info.username LIKE '%$user%'";
			
			//age query setup, get query from combo first, then use if for query fetch.
			$ages = array('no preference' => '','under 20' => '20','20-29' => '30','30-39' => '40','40-49' => '50','50-59' => '60','60 above' => '70',);
			$age_select = "";
			foreach ($ages as $range => $year) {if ($range == $age) {$age_select = $year;}}
			
			if ($age_select != "") {
			//Get DATE 1
			$datetime1 = date("Y-m-d", strtotime("-$age_select years"));
			if ($age_select == 70) {$datetime1 = date("Y-m-d", strtotime("-100 years"));}
			//Get DATE 2
			$age_select2 = $age_select - 10;
			$datetime2 = date("Y-m-d", strtotime("-$age_select2 years"));
			
			$agequery = " AND info.birthday BETWEEN '$datetime1' AND '$datetime2'";}

			//country query setup, using if statement.
			if ($country != "") {$countryquery = " AND info.country='$country'";}
			
			//gender query setup, using if statements.
			if ($gender != "Off") {
			if ($gender == "Boy") {$genderquery = " AND info.gender='M'";}
			else {$genderquery = " AND info.gender='F'";}}
			
			//photo query setup, using if statements.
			if ($photo != "Off") {
			if ($photo == "Yes") {$photoquery = " AND cus.photo!=''";}
			else {$photoquery = " AND cus.photo=''";}}
			
			$mysql_where = "WHERE info.username = cus.username$userquery$agequery$countryquery$genderquery$photoquery";
			
			//Back to perform search again:
			echo "<p><a href=\"search.php?search=user&search_query=". urlencode($user) ."&age=". urlencode($age) ."&country=". urlencode($country) ."&gender=". urlencode($gender) ."&photo=". urlencode($photo) ."\"><< Back</a> to edit search details</p>";
			
			//Set number of entries on each page:
			$limit = 5;
			//Query the db to get total entries:
			$read = mysql_query("SELECT info.* FROM userinfo info, usercus cus $mysql_where");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $no_results;}
			elseif ($read === false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			$start = $startvalue + 1;
			if ($page != $totalpages) {$end = $startvalue + $limit;} else {$end = $totalrows;}
			echo "<p><b>$totalrows results in total</b> - <i>$start to $end of $totalrows</i></p>";
			echo "<table border=0 width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=center>";
			//Read entries from mysql:
			$read = mysql_query("SELECT info.* FROM userinfo info, usercus cus $mysql_where LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$user = $r['username'];
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			
			$imgsize = getimagesize("images/photo/photo". $r1['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			
			$about = substr($r1['about'], 0, 80);
			
			$deltatime = strtotime("now") - strtotime($r['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			
			$countries = file("countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($parts[0] == $r['country']) {$country = $parts[1];}}
			
			echo "<tr><td align=center valign=center bgcolor=\"#cccccc\" width=\"32%\" height=\"100\"><img src=\"images/photo/photo{$r1['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></td><td align=left valign=center bgcolor=\"#f2f2f2\" width=\"100%\" ><img src=\"images/". $onlinestatus .".png\"><a href=\"profiles.php?user={$r['username']}\"><b>{$r['displayname']} ({$r1['username']})</b></a><br /><b>From:</b> ". $country ."";
			if ($r1['quote'] != "") {echo "<br /><b>\"{$r1['quote']}\"</b>";}
			if ($about !="") {echo "<br />$about...";}
			echo "</td></tr><tr></tr>\n";}
			echo "</table>";
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=1$urlstring\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pageprev$urlstring\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i$urlstring\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pagenext$urlstring\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$totalpages$urlstring\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}}
			
			###########################################search name####################################################
			if ($_GET['search'] == "name"){
			$name = urldecode($_GET['search_query']);
			$age = urldecode($_GET['age']);
			$country = urldecode($_GET['country']);
			$gender = urldecode($_GET['gender']);
			$photo = urldecode($_GET['photo']);
			
			//Results url string:
			$urlstring = "&search=name&search_query=". urlencode($name) ."&age=". urlencode($age) ."&country=". urlencode($country) ."&gender=". urlencode($gender) ."&photo=". urlencode($photo) ."";

			//username query setup, no need for if.
			$namequery = " AND info.displayname LIKE '%$name%'";
			
			//age query setup, get query from combo first, then use if for query fetch.
			$ages = array('no preference' => '','under 20' => '20','20-29' => '30','30-39' => '40','40-49' => '50','50-59' => '60','60 above' => '70',);
			$age_select = "";
			foreach ($ages as $range => $year) {if ($range == $age) {$age_select = $year;}}
			
			if ($age_select != "") {
			//Get DATE 1
			$datetime1 = date("Y-m-d", strtotime("-$age_select years"));
			if ($age_select == 70) {$datetime1 = date("Y-m-d", strtotime("-100 years"));}
			//Get DATE 2
			$age_select2 = $age_select - 10;
			$datetime2 = date("Y-m-d", strtotime("-$age_select2 years"));
			
			$agequery = " AND info.birthday BETWEEN '$datetime1' AND '$datetime2'";}
			
			//country query setup, using if statement.
			if ($country != "") {$countryquery = " AND info.country='$country'";}
			
			//gender query setup, using if statements.
			if ($gender != "Off") {
			if ($gender == "Boy") {$genderquery = " AND info.gender='M'";}
			else {$genderquery = " AND info.gender='F'";}}
			
			//photo query setup, using if statements.
			if ($photo != "Off") {
			if ($photo == "Yes") {$photoquery = " AND cus.photo!=''";}
			else {$photoquery = " AND cus.photo=''";}}
			
			$mysql_where = "WHERE info.username = cus.username$namequery$agequery$countryquery$genderquery$photoquery";
			
			//Back to perform search again:
			echo "<p><a href=\"search.php?search=name&search_query=". urlencode($name) ."&age=". urlencode($age) ."&country=". urlencode($country) ."&gender=". urlencode($gender) ."&photo=". urlencode($photo) ."\"><< Back</a> to edit search details</p>";
			
			//Set number of entries on each page:
			$limit = 5;
			//Query the db to get total entries:
			$read = mysql_query("SELECT info.* FROM userinfo info, usercus cus $mysql_where");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $no_results;}
			elseif ($read === false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			$start = $startvalue + 1;
			if ($page != $totalpages) {$end = $startvalue + $limit;} else {$end = $totalrows;}
			echo "<p><b>$totalrows results in total</b> - <i>$start to $end of $totalrows</i></p>";
			echo "<table border=0 width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=center>";
			//Read entries from mysql:
			$read = mysql_query("SELECT info.* FROM userinfo info, usercus cus $mysql_where LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$user = $r['username'];
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			
			$imgsize = getimagesize("images/photo/photo". $r1['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			
			$about = substr($r1['about'], 0, 80);
			
			$deltatime = strtotime("now") - strtotime($r['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			
			$countries = file("countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($parts[0] == $r['country']) {$country = $parts[1];}}
			
			echo "<tr><td align=center valign=center bgcolor=\"#cccccc\" width=\"32%\" height=\"100\"><img src=\"images/photo/photo{$r1['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></td><td align=left valign=center bgcolor=\"#f2f2f2\" width=\"100%\" ><img src=\"images/". $onlinestatus .".png\"><a href=\"profiles.php?user={$r['username']}\"><b>{$r['displayname']} ({$r1['username']})</b></a><br /><b>From:</b> ". $country ."";
			if ($r1['quote'] != "") {echo "<br /><b>\"{$r1['quote']}\"</b>";}
			if ($about !="") {echo "<br />$about...";}
			echo "</td></tr><tr></tr>\n";}
			echo "</table>";
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=1$urlstring\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pageprev$urlstring\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i$urlstring\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pagenext$urlstring\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$totalpages$urlstring\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}}
			
			###########################################search email###################################################
			if ($_GET['search'] == "email"){
			$email = urldecode($_GET['search_query']);
			
			//Results url string:
			$urlstring = "&search=email&search_query=". urlencode($email) ."";
			
			//username query setup, no need for if.
			$mysql_where = "WHERE email LIKE '%$email%'";
			
			//Back to perform search again:
			echo "<p><a href=\"search.php?search=email&search_query=". urlencode($email) ."\"><< Back</a> to edit search details</p>";
			
			//Set number of entries on each page:
			$limit = 5;
			//Query the db to get total entries:
			$read = mysql_query("SELECT * FROM userinfo $mysql_where");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $no_results;}
			elseif ($read === false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			$start = $startvalue + 1;
			if ($page != $totalpages) {$end = $startvalue + $limit;} else {$end = $totalrows;}
			echo "<p><b>$totalrows results in total</b> - <i>$start to $end of $totalrows</i></p>";
			echo "<table border=0 width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=center>";
			//Read entries from mysql:
			$read = mysql_query("SELECT * FROM userinfo $mysql_where LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$user = $r['username'];
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			
			$imgsize = getimagesize("images/photo/photo". $r1['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			
			$about = substr($r1['about'], 0, 80);
			
			$deltatime = strtotime("now") - strtotime($r['onlinetime']);
			if ($deltatime < 600) {$onlinestatus = "online";} else {$onlinestatus = "offline";}
			
			$countries = file("countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			$parts1 = str_replace("\r\n","",$parts[1]);
			if ($parts[0] == $r['country']) {$country = $parts1;}}
			
			echo "<tr><td align=center valign=center bgcolor=\"#cccccc\" width=\"32%\" height=\"100\"><img src=\"images/photo/photo{$r1['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></td><td align=left valign=center bgcolor=\"#f2f2f2\" width=\"100%\" ><img src=\"images/". $onlinestatus .".png\"><a href=\"profiles.php?user={$r['username']}\"><b>{$r['displayname']} ({$r1['username']})</b></a><br /><b>From:</b> ". $country ."";
			if ($r1['quote'] != "") {echo "<br /><b>\"{$r1['quote']}\"</b>";}
			if ($about !="") {echo "<br />$about...";}
			echo "</td></tr><tr></tr>\n";}
			echo "</table>";
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=1$urlstring\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pageprev$urlstring\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i$urlstring\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$pagenext$urlstring\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$totalpages$urlstring\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}}?>
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