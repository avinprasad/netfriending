<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>News Entry!</title>
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
			
			<?php include("menu.php");?>
			<h3>News Entry Form</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if (isset($_POST['submit'])){
			$title = $_POST['title'];
			$message = $_POST['message'];
			$datetime = date("M d, Y");
			
			$entrycontent = array(array(0, $title), array(1, $message));
			include("../texteditor.php");
			list($title, $message) = $finalcontent;
			
			//all required fields filled?
			if($title == "" or $message == "")
			{echo $requiredfields; $invalid = true;}
			else {
			
			//message exceeds 5000?
			if (strlen($message) > 5000)
			{echo $textarea_exceed; $invalid = true;}}
			
			if ($invalid == false)
			{$lines = file("news.txt");
			foreach ($lines as $line) {
			$explodes = explode(";seDp#", $line);
			$numbers[] = $explodes[2];}
			$number = max($numbers) + 1;
			$txtfile = file_get_contents("faq.txt");
			$list = array($title, $datetime, $number, $message);
			$comma_separated = implode(";seDp#", $list);
			$file = fopen("news.txt","a");
			$write = fwrite($file, "$comma_separated\n");
			fclose($file);
			
			$read = mysql_query("SELECT * FROM userinfo");
			while ($r = mysql_fetch_array($read)) {
			$read1 = mysql_query("SELECT * FROM usercus WHERE username='{$r['username']}'");
			$r1 = mysql_fetch_array($read1);
			//whether or not to send notification email:
			$notifications = explode(",", $r1['notification']);
			if ($notifications[3] == 1) {
			$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
			$subject = "NetFriending Newsletter";
			$textfile = file_get_contents("emails/newsletter.txt");
			$body = str_replace("%fname%", $r['firstname'], $textfile);
			$body = str_replace("%lname%", $r['lastname'], $body);
			$entrycontent = $message;
			$textrcondition = 0;
			$emailcondition = true;
			include("../textreplacer.php");
			$body = str_replace("%news%", $entrycontent, $body);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";
			
			mail($to, $subject, $body, $headers);}}
			$title = ""; $message = "";
			
			if($write == true)
			{header("location: confirm.php?confirm=newsentry&news=$number");}
			else
			{echo "<p>$db_error</p>";}}}
			
			//Set values for form post:
			$form = "newsentry";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php include("../smiliecoder.html");?>
			<p>			
			<label>Title</label>
			<input name="title" value="<?echo $title;?>" type="text" size="30" maxlength="40" />
			<label>Message</label>
			<textarea name="<?php echo $textarea;?>" rows="5" cols="5"><?echo $message;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />		
			</p>		
			</form>
			
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