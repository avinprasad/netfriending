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
$read = mysql_query("SELECT displayname FROM usercus WHERE username='$user'");
$r = mysql_fetch_array($read);
$dname = $r['displayname'];
if ($dname == "") {$invalid = true;
$title = "ErrorDocument 404"; header("HTTP/1.0 404 Not Found");}
else {$status = file("storage/$user.txt", FILE_IGNORE_NEW_LINES);
list($lastmoddate, $pagetitle, $banner, $bgcolor, $bgimage, $bgwatermark, $font, $textcolor, $order, $formcolor, $firstcolor, $secondcolor, $header, $footer, $authority) = explode(";seDp#", $status[1]);
$title = "$dname's Guestbook";}?>
<title><?php echo $title;?></title>
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
			
			<?php if ($invalid == true) {
			include ("../notfound.html");}
			else {
			if ($authority == "everyone") {if (checkLoggedin()) {$seccontent = true;} $showcontent = true;}
			else {if (!checkLoggedin()) {require("login.php");} else {$seccontent = true; $showcontent = true;}}
			if ($seccontent == true) {include("usernav.php");}
			if ($showcontent == true) {?>
			<h4><?php echo $dname;?>'s Guestbook</h4>
			<a href="profiles.php?user=<?php echo $user;?>">View <?php echo $dname;?>'s Profile</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			$noty_subject = "guestbook";
			include("../notification.php");
			
			$entrycontent = $header;
			$textrcondition = 0;
			include("../textreplacer.php");
			$header = $entrycontent;
			$entrycontent = $footer;
			$textrcondition = 0;
			include("../textreplacer.php");
			$footer = $entrycontent;?>
			<a name="<?php echo $pagetitle?>"></a>
			<link rel="stylesheet" href="<?php echo $scrollbar?>.css" type="text/css" />
			<style type="text/css">
			#wrap 
			{<?php if ($bgimage == "") {echo "background-color: $bgcolor";}
			else {echo "background-image:
			url('$bgimage');";
			if ($bgwatermark == "fixed") {echo "
			background-repeat: no-repeat;
			background-position: center;";}
			else {echo "
			background-repeat: repeat;";}}
			?>
			}
			h1.title
			{
			font-size: 5;
			font-family: <?php echo $font?>;
			color: <?php echo $textcolor?>;
			}
			p.banner
			{
			font-size: 4;
			font-family: <?php echo $font?>;
			color: <?php echo $textcolor?>;
			}
			table.customize
			{
			background-color: <?php echo $firstcolor?>;
			}
			td.customize
			{
			background-color: <?php echo $secondcolor?>;
			}
			font.customize
			{
			font-family: <?php echo $font?>;
			color: <?php echo $textcolor?>;
			}
			form
			{
			background-color: <?php echo $formcolor?>;
			}
			p.footer,p.header
			{
			font-size: 3;
			font-family: <?php echo $font?>;
			color: <?php echo $textcolor?>;
			}
			p.customize,h3.customize
			{
			color: <?php echo $textcolor?>;
			font-family: <?php echo $font?>;
			}
			</style>
			<h1 class="title"><b><?php echo $pagetitle;?></b></h1>
			<p class="banner"><b><?php echo $banner;?></b></p>
			<p class="header"><?php echo $header;?></p>
			<?php if ($seccontent == true) {
			if (isset($_POST['submit'])){
			$icon = $_POST['icon'];
			$title1 = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title1), array(3, $message));
			include("../texteditor.php");
			list($title1, $message) = $finalcontent;
			
			//all required fields filled?
			if ($message == "" or $title1 == "")
			{echo $requiredfields; $invalid = true;}
			
			if ($invalid == false)
			{$insert = mysql_query("INSERT INTO guestbook(username, user, entryicon, entrytitle, entrycontent, entrydate) VALUES('$user', '$username', '$icon', '$title1', '$message', '$datetime')");
			//send emails to notify:
			$entrycontent = $message;
			$textrcondition = 1;
			$emailcondition = true;
			include("../textreplacer.php");
			$read = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
			$r = mysql_fetch_array($read);
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r2 = mysql_fetch_array($read2);
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r1 = mysql_fetch_array($read1);
			//whether or not to send notification email:
			$notifications = explode(",", $r2['notification']);
			if ($notifications[2] == 1) {
			$imgsize = getimagesize("images/photo/photo{$r1['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			//below is email:
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "$displayname ($username) has wrote on your Guestbook!";
			$textfile = file_get_contents("../admin/emails/gbnotifier.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$body = str_replace("%photo%", $r1['photo'], $body);
			$body = str_replace("%width%", round($width), $body);
			$body = str_replace("%height%", round($height), $body);
			$body = str_replace("%dname%", $displayname, $body);
			$body = str_replace("%uname%", $username, $body);
			$body = str_replace("%username%", $r['username'], $body);
			$body = str_replace("%message%", $entrycontent, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);
			$icon = ""; $title1 = ""; $message = ""; $datetime = "";
			
			if($insert === true)
			{header("location: confirm.php?confirm=gb&user=$user");}
			else
			{echo"<p>$db_error</p>";}}}}
			
			if ($icon == "") {$icon = "xx";}
			
			//Set values for form post:
			$form = "guestbook";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<p>
			<?php function check($pic, $img){if ($img == $pic) {echo " checked=\"yes\"";}}?>
			<input type="radio" name="icon" value="xx"<?php check("xx", $icon);?> />
			<img src="/netfriending/users/images/xx.gif">
			<input type="radio" name="icon" value="thumbdown"<?php check("thumbdown", $icon);?> />
			<img src="/netfriending/users/images/thumbdown.gif">
			<input type="radio" name="icon" value="thumbup"<?php check("thumbup", $icon);?> />
			<img src="/netfriending/users/images/thumbup.gif">
			<input type="radio" name="icon" value="exclamation"<?php check("exclamation", $icon);?> />
			<img src="/netfriending/users/images/exclamation.gif">
			<input type="radio" name="icon" value="question"<?php check("question", $icon);?> />
			<img src="/netfriending/users/images/question.gif">
			<input type="radio" name="icon" value="lamp"<?php check("lamp", $icon);?> />
			<img src="/netfriending/users/images/lamp.gif">
			</p>
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $title1;?>" type="text" size="30" maxlength="40" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $message;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />		
			</p>
			</form>
			<?php }
			if($order == "asc") {$orderby = "ORDER BY entrydate";} else {$orderby = "ORDER BY entrydate DESC";}
			
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 5;
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM guestbook WHERE username='$user'");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo "<p class=\"customize\" align=center><br /><br />$db_noentries</p>";}
			elseif ($read === false) {$pagination = false;
			echo "<p class=\"customize\" align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			$read = mysql_query("SELECT * FROM guestbook WHERE username='$user' $orderby LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$user = $r['user'];
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r1 = mysql_fetch_array($read1);
			$dis_func = 0; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$imgsize = getimagesize("images/photo/photo". $r1['photo'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			$entrycontent = $r['entrycontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<p><table class=\"customize\" width=\"100%\" align=center><tr><td class=\"customize\" align=center width=\"35%\" height=\"100%\" valign=top><p align=center><a href=\"profiles.php?user={$r1['username']}\"><font class=\"customize\"><b>{$r1['displayname']} ({$r1['username']})</b></font></a><br /><br /><img src=\"/netfriending/users/images/photo/photo{$r1['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></p></td><td valign=top height=\"100%\"><img src=\"/netfriending/users/images/{$r['entryicon']}.gif\"><font color=\"#666666\" class=\"customize\"><b>{$r['entrytitle']}</b></font><br /><font class=\"customize\"><b>$date</b></font><hr><div id=\"views\"><font class=\"customize\">$entrycontent</font></div></td></tr></table></p>" . "\r\n";}
			//Starts page links:
			echo "<p class=\"pagination\" align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?user=$user&page=1\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?user=$user&page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?user=$user&page=$i\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?user=$user&page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?user=$user&page=$totalpages\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}?>
			<p class="footer"><?php echo $footer;?></p>
			<?php }}?>
			
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