<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<?php $news = $_GET['news'];
$lines = file("admin/news.txt");
$read = 0;
foreach ($lines as $line) {
$explodes = explode(";seDp#", $line);
if ($explodes[2] == $news) {
$linenumber = $read;}
$read ++;}
if (is_int($linenumber) != true) {$invalid = true;
$title = "ErrorDocument 404";}
else {$data = explode(";seDp#", $lines[$linenumber]);
$entrycontent = $data[3];
$textrcondition = 0;
include("textreplacer.php");
$title = "{$data[0]} - NetFriending News";}?>
<title><?php echo $title;?></title>
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
			
			<?php if ($invalid == true) {
			include ("notfound.html");}
			else {?>
			<a name="<?php echo $data[0];?>"></a>
			<h1><?php echo $data[0];?></h1>
			<p><?php echo $entrycontent;?></p>
			<p class="post-footer align-right">
			<span class="date"><?php echo $data[1];?></span>
			</p>
			
			<a name="comments"></a>
			<h1>Comments on News</h1>
			
			<?php //Pre-configuration:
			//Require Message:
			include("notification.php");
			
			$number = $data[2];
			$commentsarray = file("newscomments/comments[$number].txt", FILE_IGNORE_NEW_LINES);
			foreach($commentsarray as $v) {
			list($title1, $datetime1, $ip1, $country1, $message1) = explode(";seDp#",$v);
			$entrycontent = $message1;
			$textrcondition = 0;
			include("textreplacer.php");
			echo "<h3>$title1</h3>
			<p><b>From:</b> $country1</p>
			<p><b>Masked IP:</b> $ip1</p>
			<p>$entrycontent</p>
			<p class=\"post-footer align-right\">
			<span class=\"date\">$datetime1</span>
			</p>
			" . "\n";}?>
			
			<h3>Leave a Comment</h3>
			<?php if (isset($_POST['submit'])){
			$title2 = $_POST['title'];
			$message = $_POST['message'];
			$imgverify = $_POST['imgverify'];
			$datetime = date("M d, Y");
			
			$entrycontent = array(array(0, $title2), array(1, $message), array(6, $imgverify));
			include("texteditor.php");
			list($title2, $message, $imgverify) = $finalcontent;
			
			//all required fields filled?
			if ($title2 == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}
			
			//image verification passed?
			if ($_SESSION['imageverify'] != md5($imgverify))
			{echo $imgverify_failed; $invalid = true;}}
			
			if ($invalid == false)
			{//Get visitor location:
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$explode = explode(".", $ipaddress);
			$ipno = ($explode[0] * 16777216) + ($explode[1] * 65536) + ($explode[2] * 256) + $explode[3];
			$ips = file("ipcountries.txt");
			foreach ($ips as $line) 
			{$explodetxt = explode(",", $line);
			if ($ipno > $explodetxt[0] && $ipno < $explodetxt[1]) {$located = $explodetxt[2];}}
			$countries = file("users/countries.txt");
			foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
			if ($located == $parts[0]) {$country = $parts[1];}}
			if ($located == "") {$country = "Unknown";}
			//Mask IP address:
			$ip = "{$explode[0]}.{$explode[1]}.{$explode[2]}.xxx";
			
			$txtfile = file_get_contents("newscomments/comments[$number].txt");
			$list = array($title2, $datetime, $ip, $country, $message);
			$comma_separated = implode(";seDp#", $list);
			$file = fopen("newscomments/comments[$number].txt", "w+");
			$write = fwrite($file, "$comma_separated\n$txtfile");
			fclose($file);
			$title2 = ""; $message = ""; $datetime = ""; $country = ""; $imgverify = "";
			
			if($write == true) {header("location: include.php?page=newsconfirm&news=$news");}
			else {echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "newscomment";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="texteditor.js"></script>
			<?php include("smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $title2;?>" type="text" size="30" maxlength="40" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $message;?></textarea>
			<label>Image Verification</label>
			<img id="image" src="imgverify.php" onclick="reloadImg();" />&nbsp;Click on image to refresh if you can't see it clearly.<br />
			<input name="imgverify" value="" type="text" size="30" maxlength="5" />
			<br /><br />
			<input class="button" name="submit" type="submit" />		
			</p>
			</form>
			<?php }?>
			
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