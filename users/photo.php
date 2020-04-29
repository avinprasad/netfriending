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
<title><?php echo $dname?>'s Photo Gallery</title>
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
			<h4><?php echo $dname?>'s Photo Gallery</h4>
			<a href="profiles.php?user=<?php echo $user;?>">View <?php echo $dname;?>'s Profile</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			//editcomment action:
			$entryid1 = $_GET['entryid'];
			if ($entryid1 != "") {$read3 = mysql_query("SELECT * FROM comment WHERE username='$user' AND id='$entryid1' AND entryattribute='photo'"); $r3 = mysql_fetch_array($read3);}
			if ($r3['user'] == $username) {$act_edit = true; $title = $r3['entrytitle']; $message = $r3['entrycontent'];}
			elseif ($r3['username'] == $username) {$delete = mysql_query("DELETE FROM comment WHERE id='$entryid1' AND username='$username' AND entryattribute='photo'");
			if($delete === false)
			{echo"<p>$db_error</p>";}
			else
			{header("location: confirm.php?confirm=photocomment_delete&user=$user&page=$page");}}

			//Set number of entries on each page:
			$limit = 1;
			//Query the db to get total entries:
			$read = mysql_query("SELECT * FROM photo WHERE username='$user'");
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
			$read = mysql_query("SELECT * FROM photo WHERE username='$user' ORDER BY photodate DESC LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//comment id:
			$entryid = $r['id'];
			//Start looping through the entries:
			$dis_func = 0; $date = $r['photodate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$imgsize = getimagesize("images/photo/photo". $r['photofile'] .".jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 400 or $height > 600) {$width = $width/1.2; $height = $height/1.2;}
			$entrycontent = $r['photocontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<a name=\"{$r['photoname']}\"></a><h1>{$r['photoname']}</h1>
			<p align=center><img src=\"images/photo/photo{$r['photofile']}.jpg\" alt=\"{$r['photoname']}\" height=\"". round($height) ."\" width=\"". round($width) ."\" /></p>
			<p align=center id=\"views2\">$entrycontent</p>
			<p class=\"post-footer align-right\">
			<span class=\"date\">$date</span></p>";}
			//Starts page links:
			echo "<p align=center>";
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
			
			<a name="comments"></a>
			<h1>Comments on Photo</h1>
			
			<?php //Read entries from mysql:
			$read1 = mysql_query("SELECT * FROM comment WHERE username='$user' AND entryid='$entryid' AND entryattribute='photo' ORDER BY entrydate DESC");
			while ($r1 = mysql_fetch_array($read1)) {
			//Start looping through the entries:
			$read2 = mysql_query("SELECT * FROM usercus WHERE username='{$r1['user']}'");
			$r2 = mysql_fetch_array($read2);
			$dis_func = 0; $date = $r1['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$imgsize = getimagesize("images/photo/photo{$r2['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			if ($r1['user'] == $username) {$icon = "_edit"; $alink_1 = "<a href=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&entryid={$r1['id']}#edit\">"; $alink_2 = "</a>";}
			elseif ($r1['username'] == $username) {$icon = "_delete"; $alink_1 = "<a href=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&entryid={$r1['id']}#edit\"  onClick=\"javascript: return checkDelete()\">"; $alink_2 = "</a>";}
			else {$icon = ""; $actlink_1 = ""; $alink_2 = "";}
			$entrycontent = $r1['entrycontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<p><table bgcolor=\"#cccccc\" width=\"100%\" align=center><tr>
			<td bgcolor=\"#f2f2f2\" align=center width=\"35%\" height=\"100%\" valign=top><p align=center><a href=\"profiles.php?user={$r1['user']}\"><b>{$r2['displayname']} ({$r1['user']})</b></a><br /><br /><img src=\"/users/images/photo/photo{$r2['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></p></td>
			<td valign=top height=\"100%\">$alink_1<img src=\"images/icons/comment$icon.gif\">$alink_2<font color=\"#666666\"><b>{$r1['entrytitle']}</b><br /><b>$date</b><hr><div id=\"views\">$entrycontent</div></td>
			</tr></table></p>" . "\n";}?>
			
			<h3>Leave a Comment</h3>
			<?php if (isset($_POST['delete'])) {
			$delete = mysql_query("DELETE FROM comment WHERE id='$entryid1' AND (username='$username' OR user='$username') AND entryattribute='photo'");
			if($delete === false)
			{echo"<p>$db_error</p>";}
			else
			{header("location: confirm.php?confirm=photocomment_delete&user=$user&page=$page");}}
			
			if (isset($_POST['submit'])) {
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title), array(3, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if ($title == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//photo id exist yet?
			$read = mysql_query("SELECT * FROM photo WHERE id='$entryid'");
			if (mysql_num_rows($read) == 0)
			{echo $photoid_notexist; $invalid = true;}
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}}
			
			if ($invalid == false)
			{if ($act_edit == true) {$insert = mysql_query("UPDATE comment SET entrytitle='$title', entrycontent='$message', entrydate='$datetime' WHERE id='$entryid1' AND user='$username' AND entryattribute='photo'"); $insert1 = false;}
			else {$insert1 = mysql_query("INSERT INTO comment(username, user, entryid, entrytitle, entrycontent, entryattribute, entrydate) VALUES('$user', '$username', '$entryid', '$title', '$message', 'photo', '$datetime')");
			//send emails to notify:
			$entrycontent = $message;
			$textrcondition = 1;
			$emailcondition = true;
			include("../textreplacer.php");
			$read5 = mysql_query("SELECT * FROM userinfo WHERE username='$user'");
			$r5 = mysql_fetch_array($read5);
			$read6 = mysql_query("SELECT * FROM usercus WHERE username='$user'");
			$r6 = mysql_fetch_array($read6);
			$read7 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
			$r7 = mysql_fetch_array($read7);
			//whether or not to send notification email:
			$notifications = explode(",", $r6['notification']);
			if ($notifications[2] == 1) {
			$imgsize = getimagesize("images/photo/photo{$r7['photo']}.jpg");
			$width = $imgsize[0];
			$height = $imgsize[1];
			while ($width > 300 or $height > 100) {$width = $width/1.2; $height = $height/1.2;}
			//below is email:
			$to = "{$r5['firstname']} {$r5['lastname']} <{$r5['email']}>";
			$subject = "$displayname ($username) has commented on your photo!";
			$textfile = file_get_contents("../admin/emails/commentnotifier.txt");
			$body = str_replace("%fname%", $r5['firstname'], $textfile);
			$body = str_replace("%lname%", $r5['lastname'], $body);
			$body = str_replace("%photo%", $r7['photo'], $body);
			$body = str_replace("%width%", round($width), $body);
			$body = str_replace("%height%", round($height), $body);
			$body = str_replace("%dname%", $displayname, $body);
			$body = str_replace("%uname%", $username, $body);
			$body = str_replace("%username%", $r5['username'], $body);
			$body = str_replace("%message%", $entrycontent, $body);
			$body = str_replace("%text%", "photo", $body);
			$body = str_replace("%text1%", "photo gallery", $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}
			$insert = false;}
			$title = ""; $message = ""; $datetime = "";
			
			if ($insert === true) {header("location: confirm.php?confirm=photocomment_edit&user=$user&page=$page");}
			elseif ($insert1 === true) {header("location: confirm.php?confirm=photocomment&user=$user&page=$page");}
			else {echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "photocomment";
			$textarea = "message";?>
			
			<a name="edit"></a>
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?php echo $title;?>" type="text" size="30" maxlength="40" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?php echo $message;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />
			<?php if ($act_edit == true) {echo "<input class=\"button\" name=\"delete\" type=\"submit\" value=\"Delete Entry\" onClick=\"javascript: return checkDelete()\" /> 
			<input class=\"button\" type=\"button\" value=\"Cancel Edit\" onClick=\"window.location='{$_SERVER['PHP_SELF']}?user=$user&page=$page'\" />";}?>
			</p>
			</form>
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