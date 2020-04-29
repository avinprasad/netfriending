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
list($lastmoddate, $title1, $banner, $bgcolor, $bgimage, $bgwatermark, $font, $textcolor, $datecolor, $order, $header, $footer, $authority) = explode(";seDp#", $status[0]);
$title = "$dname's Blog";}?>
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
			if ($authority == "everyone") {if (checkLoggedin()) {include("usernav.php");} $showcontent = true;}
			else {if (!checkLoggedin()) {require("login.php");} else {include("usernav.php"); $showcontent = true;}}
			if ($showcontent == true) {?>
			<h4><?php echo $dname;?>'s Blog</h4>
			<a href="profiles.php?user=<?php echo $user;?>">View <?php echo $dname;?>'s Profile</a><?php if (isset($_GET['id'])) {echo " | <a href=\"blog.php?user=$user\">Back to $dname's Blog</a>";}?>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			$noty_subject = "blog";
			include("../notification.php");
			
			//editcomment action:
			$entryid1 = $_GET['entryid'];
			if ($entryid1 != "") {$read3 = mysql_query("SELECT * FROM comment WHERE username='$user' AND id='$entryid1' AND entryattribute='blog'"); $r3 = mysql_fetch_array($read3);}
			if ($r3['user'] == $username) {$act_edit = true; $title2 = $r3['entrytitle']; $message = $r3['entrycontent'];}
			elseif ($r3['username'] == $username) {$delete = mysql_query("DELETE FROM comment WHERE id='$entryid1' AND username='$username' AND entryattribute='blog'");
			if($delete === false)
			{echo"<p>$db_error</p>";}
			else
			{header("location: confirm.php?confirm=blogcomment_delete&user=$user&id=$id");}}
			
			$entrycontent = $header;
			$textrcondition = 0;
			include("../textreplacer.php");
			$header = $entrycontent;
			$entrycontent = $footer;
			$textrcondition = 0;
			include("../textreplacer.php");
			$footer = $entrycontent;?>
			<a name="<?php echo $title1?>"></a>
			<style type="text/css">
			#wrap 
			{<?php if ($bgimage == "") {echo "background-color: $bgcolor";}
			else {echo "background-image:
			url('$bgimage');";
			if ($bgwatermark == "fixed") {echo "
			background-repeat: no-repeat;
			background-position: center;";}
			else {echo "
			background-repeat: repeat;";}}?>
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
			p.post-footer.align-right.customize
			{
			background-color: <?php echo $datecolor?>;
			}
			p.pagination
			{
			color: <?php echo $textcolor?>;
			font-family: <?php echo $font?>;
			}
			</style>
			<h1 class="title"><b><?php echo $title1;?></b></h1>
			<p class="banner"><b><?php echo $banner;?></b></p>
			<p class="header"><?php echo $header;?></p>
			<?php if (isset($_GET['id'])) {//ID single style:
			$id = $_GET['id'];
			$read = mysql_query("SELECT * FROM diary WHERE username='$user' AND id='$id'");
			$r = mysql_fetch_array($read);
			//Start looping through the entries:
			$dis_func = 0; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat']; $time_format = $row['timeformat'];
			include("../disfunc.php");
			$entrycontent = $r['entrycontent'];
			$textrcondition = 1;
			include("../textreplacer.php");
			echo "<h3 class=\"customize\"><img src=\"/netfriending/users/images/{$r['entryicon']}.gif\">{$r['entrytitle']}</h3>
			<p class=\"customize\" id=\"views\">$entrycontent</p>
			<p class=\"post-footer align-right customize\">
			<span class=\"date\">$date</span>
			</p>\n";
			if (checkLoggedin()) {?>
			<a name="comments"></a>
			<h1>Comments on Blog</h1>
			
			<?php //Read entries from mysql:
			$read1 = mysql_query("SELECT * FROM comment WHERE username='$user' AND entryid='$id' AND entryattribute='blog' ORDER BY entrydate DESC");
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
			<td bgcolor=\"#f2f2f2\" align=center width=\"35%\" height=\"100%\" valign=top><p align=center><a href=\"profiles.php?user={$r1['user']}\"><b>{$r2['displayname']} ({$r1['user']})</b></a><br /><br /><img src=\"/netfriending/users/images/photo/photo{$r2['photo']}.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\"></p></td>
			<td valign=top height=\"100%\">$alink_1<img src=\"images/icons/comment$icon.gif\">$alink_2<font color=\"#666666\"><b>{$r1['entrytitle']}</b><br /><b>$date</b><hr><div id=\"views\">$entrycontent</div></td>
			</tr></table></p>" . "\n";}?>
			
			<h3>Leave a Comment</h3>
			<?php if (isset($_POST['delete'])) {
			$delete = mysql_query("DELETE FROM comment WHERE id='$entryid1' AND (username='$username' OR user='$username') AND entryattribute='blog'");
			if($delete === false)
			{echo"<p>$db_error</p>";}
			else
			{header("location: confirm.php?confirm=blogcomment_delete&user=$user&id=$id");}}
			
			if (isset($_POST['submit'])) {
			$title2 = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(2, $title2), array(3, $message));
			include("../texteditor.php");
			list($title2, $message) = $finalcontent;
			
			//all required fields filled?
			if ($title2 == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//diary id exist yet?
			$read = mysql_query("SELECT * FROM diary WHERE id='$id'");
			if (mysql_num_rows($read) == 0)
			{echo $blogid_notexist; $invalid = true;}
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}}
			
			if ($invalid == false)
			{if ($act_edit == true) {$insert = mysql_query("UPDATE comment SET entrytitle='$title2', entrycontent='$message', entrydate='$datetime' WHERE id='$entryid1' AND user='$username' AND entryattribute='blog'"); $insert1 = false;}
			else {$insert1 = mysql_query("INSERT INTO comment(username, user, entryid, entrytitle, entrycontent, entryattribute, entrydate) VALUES('$user', '$username', '$id', '$title2', '$message', 'blog', '$datetime')");
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
			$subject = "$displayname ($username) has commented on your blog!";
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
			$body = str_replace("%text%", "blog", $body);
			$body = str_replace("%text1%", "blog entries", $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}
			$insert = false;}
			$title2 = ""; $message = ""; $datetime = "";
			
			if ($insert === true) {header("location: confirm.php?confirm=blogcomment_edit&user=$user&id=$id");}
			elseif ($insert1 === true) {header("location: confirm.php?confirm=blogcomment&user=$user&id=$id");}
			else {echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "blogcomment";
			$textarea = "message";?>
			
			<a name="edit"></a>
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?php echo $title2;?>" type="text" size="30" maxlength="40" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?php echo $message;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />
			<?php if ($act_edit == true) {echo "<input class=\"button\" name=\"delete\" type=\"submit\" value=\"Delete Entry\" onClick=\"javascript: return checkDelete()\" /> 
			<input class=\"button\" type=\"button\" value=\"Cancel Edit\" onClick=\"window.location='{$_SERVER['PHP_SELF']}?user=$user&id=$id'\" />";}?>
			</p>
			</form>
			<?php }}
			else {//Pagination style:
			if($order == "asc") {$orderby = "ORDER BY entrydate";} else {$orderby = "ORDER BY entrydate DESC";}
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 5;
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM diary WHERE username='$user'");
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
			$read = mysql_query("SELECT * FROM diary WHERE username='$user' $orderby LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$dis_func = 1; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat'];
			include("../disfunc.php");
			//Only the first limited no. of characters:
			if (strlen($r['entrycontent']) > 700) {$extension = "...";}
			$entrycontent = "". substr($r['entrycontent'], 0, 700) ."$extension";
			$extension = "";
			$textrcondition = 1;
			include("../textreplacer.php");
			//No. of comments:
			$read1 = mysql_query("SELECT * FROM comment WHERE username='$user' AND entryid='{$r['id']}' AND entryattribute='blog'");
			$comments = mysql_num_rows($read1);
			echo "
			<h3 class=\"customize\"><img src=\"/netfriending/users/images/{$r['entryicon']}.gif\">{$r['entrytitle']}</h3>
			<p class=\"customize\" id=\"views\">$entrycontent</p>
			<p class=\"post-footer align-right customize\">
			<a href=\"{$_SERVER['PHP_SELF']}?user=$user&id={$r['id']}\" class=\"readmore\">Read more</a>
			<a href=\"{$_SERVER['PHP_SELF']}?user=$user&id={$r['id']}#comments\" class=\"comments\">Comments ($comments)</a>
			<span class=\"date\">$date</span>
			</p>" . "\n";}
			
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
			echo "</p>";}}?>
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