<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<link rel="shortcut icon" href="favicon.ico" />
<title>NetFriending Online!</title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">

	<!--header -->
	<div id="header">

		<?php include("header.html")?>

		<!-- Menu Tabs -->
		<?php include("menu.php")?>

	</div>

	<!-- content-wrap starts here -->
	<div id="content-wrap">

	<div class="headerphoto"></div>

		<div id="sidebar" >
		<?php include("leftmenu.html")?>
		</div>

		<div id="main">

			<?php //Pre-configuration:
			//Require MySQL:
			require("mysqlconnection.php");
			//Require Message:
			$noty_subject = "database";
			include("notification.php");

			//##Welcome and News articles##
			//Query the database to get total entries:
			$lines = file("admin/news.txt");
			$totalrows = count($lines);
			//Special Cases:
			if ($totalrows == 0) {
			$error_message = "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($lines == false) {
			$error_message = "<p align=center><br /><br />$db_error</p>";}
			else {
			//Gather replies for 1st entry:
			$data = explode(";seDp#", $lines[0]);
			//Entry content conventional
			$entrycontent = $data[3];
			$textrcondition = 0;
			include("textreplacer.php");
			$newscontent = $entrycontent;
			//Gather information for latest entry:
			$linenumber = count($lines) - 1;
			$data1 = explode(";seDp#", $lines[$linenumber]);
			//Entry content conventional
			if (strlen($data1[3]) > 500) {$extension = "...";}
			$entrycontent = "". substr($data1[3], 0, 500) ."$extension";
			$extension = "";
			$textrcondition = 0;
			include("textreplacer.php");
			$newscontent1 = $entrycontent;
			//Number of replies
			$replies1 = file("newscomments/comments[{$data1[2]}].txt");
			$total1 = count($replies1);
			if (!file_exists("newscomments/comments[{$data1[2]}].txt")){$total1 = 0;}}
			//##Cool People##
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM usercus WHERE photo!=''");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			if ($totalrows == 0) {
			$error_message1 = "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($read === false) {
			$error_message1 = "<p align=center><br /><br />$db_error</p>";}
			else {
			$read = mysql_query("SELECT * FROM usercus WHERE photo!='' ORDER BY RAND() LIMIT 2");
			$i = 0;
			while ($r = mysql_fetch_array($read)) {
			$user[$i] = $r['username'];
			$displayname[$i] = $r['displayname'];
			$photo[$i] = $r['photo'];
			$i ++;}}
			//##Updated Blogs##
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM diary");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			if ($totalrows == 0) {
			$error_message2 = "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($read === false) {
			$error_message2 = "<p align=center><br /><br />$db_error</p>";}
			else {
			$read = mysql_query("SELECT * FROM diary ORDER BY entrydate DESC LIMIT 1");
			$r = mysql_fetch_array($read);
			$blogauthoruser = $r['username'];
			$read = mysql_query("SELECT displayname FROM usercus WHERE username='$blogauthoruser'");
			$r1 = mysql_fetch_array($read);
			$blogauthordis = $r1['displayname'];
			$blogtitle = $r['entrytitle'];
			$dis_func = 0; $date = $r['entrydate'];
			include("disfunc.php");
			if (strlen($r['entrycontent']) > 380) {$extension = "...";}
			$entrycontent = "". substr($r['entrycontent'], 0, 380) ."$extension";
			$extension = "";
			$textrcondition = 1;
			include("textreplacer.php");
			$blogcontent = strip_tags($entrycontent);}
			//##Latest GBs##
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM guestbook");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			if ($totalrows == 0) {
			$error_message3 = "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($read === false) {
			$error_message3 = "<p align=center><br /><br />$db_error</p>";}
			else {
			$read1 = mysql_query("SELECT * FROM guestbook ORDER BY entrydate DESC LIMIT 3");}?>
			<script type="text/javascript" src="images/tabmenu.js"></script>
			<link rel="stylesheet" href="images/tabmenu.css" type="text/css" />
			<script type="text/javascript">
			ddtabmenu.definemenu("tabmenu", 0)
			</script>
			<p>
			<div id="tabmenu" class="ddcolortabs">
			<ul>
			<li><a href="news.php?news=<?php echo $data[2];?>" rel="st1"><span>WELCOME!</span></a></li>
			<li><a rel="st2"><span>COOL PEOPLE</span></a></li>
			<li><a href="users/blog.php?user=<?php echo $blogauthoruser;?>" rel="st3"><span>UPDATED BLOGS</span></a></li>
			<li><a rel="st4"><span>LATEST GBS</span></a></li>
			</ul>
			</div>
			<div class="tabcontainer" style="margin-bottom: 20px;">
			<div id="st1" class="tabcontent">
			<?php if ($error_message != "") {echo $error_message;}
			else {echo "<a name=\"{$data[0]}\"></a>
			<h1>{$data[0]}</h1>
			<p>$newscontent</p>";}?>
			</div>
			<div id="st2" class="tabcontent">
			<?php if ($error_message1 != "") {echo $error_message1;}
			else {?>
			<table width="100%" height="100%"><tr width="100%">
			<?php $read = 0;
			foreach ($photo as $foto) {$imgsize = getimagesize("users/images/photo/photo$foto.jpg");
			$width = $imgsize[0]; $height = $imgsize[1];
			while ($width > 180 or $height > 200) {$width = $width / 1.2; $height = $height / 1.2;}
			echo "<td width=\"50%\" align=center valign=middle><img src=\"users/images/photo/photo$foto.jpg\" width=\"". round($width) ."\" height=\"". round($height) ."\" /><br /><b><a href=\"users/profiles.php?user={$user[$read]}\">{$displayname[$read]} ({$user[$read]})</a></b></td>";
			$read ++;}?>
			</tr></table>
			<?php }?>
			</div>
			<div id="st3" class="tabcontent">
			<?php if ($error_message2 != "") {echo $error_message2;}
			else {?>
			<a href="users/blog.php?user=<?php echo $blogauthoruser;?>"><h3><?php echo $blogtitle;?></h3></a>
			<p><b>By: <a href="users/profiles.php?user=<?php echo $blogauthoruser;?>"><?php echo "$blogauthordis ($blogauthoruser)";?></a></b> @ <b><?php echo $date;?></b><br /><br />
			<?php echo $blogcontent;?><br /><br />
			<i>Note: links and pictures are removed for security purpose. Click on the title to read the full version.</i></p>
			<?php }?>
			</div>
			<div id="st4" class="tabcontent">
			<?php if ($error_message3 != "") {echo $error_message3;}
			else {
			while ($r = mysql_fetch_array($read1)) {
			$read = mysql_query("SELECT displayname FROM usercus WHERE username='{$r['username']}'");
			$r1 = mysql_fetch_array($read);
			$usernamedis = $r1['displayname'];
			$read = mysql_query("SELECT displayname FROM usercus WHERE username='{$r['user']}'");
			$r2 = mysql_fetch_array($read);
			$userdis = $r2['displayname'];
			$dis_func = 0; $date = $r['entrydate'];
			include("disfunc.php");
			echo "<a href=\"users/gb.php?user={$r['username']}\"><h3>{$r['entrytitle']}</h3></a>
			<p><b><a href=\"users/profiles.php?user={$r['user']}\">$userdis ({$r['user']})</a></b> posted in <b><a href=\"users/profiles.php?user={$r['username']}\">$usernamedis ({$r['username']})</a></b>'s guestbook @ <b>$date</b></p>";}}?>
			</div>
			</div>
			</p>
			<?php if ($error_message != "") {echo $error_message;}
			else {?>
			<a name="NetFriending News!"></a>
			<a href="newslist.php"><h1>NetFriending News!</h1></a>
			<h3><?php echo $data1[0];?></h3>
			<p><?php echo $newscontent1;?></p>
			<p class="post-footer align-right">
			<a href="news.php?news=<?php echo $data1[2];?>" class="readmore">Read more</a>
			<a href="news.php?news=<?php echo $data1[2];?>#comments" class="comments">Comments (<?php echo $total1;?>)</a>
			<span class="date"><?php echo $data1[1];?></span>
			</p>
			<?php }?>
			<br />

		</div>

		<div id="rightbar">
		<?php include("rightmenu.html")?>
		</div>

	<!-- content-wrap ends here -->
	</div>

<!-- footer starts here -->
<div id="footer">

	<div class="footer-left">
	<?php include("footer-left.html")?>
	</div>

	<div class="footer-right">
	<?php include("footer-right.html")?>
	</div>

</div>
<!-- footer ends here -->

<!-- wrap ends here -->
</div>

</body>
</html>
