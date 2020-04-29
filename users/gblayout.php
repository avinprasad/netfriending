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
<title>Change Guestbook Layout</title>
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
			<h4>Customize MY Page</h4>
			<a href="gbview.php">Back to Guestbook Admin</a>
			<p>Please feel free to use the BBCodes in header and footer boxes, although there is no buttons available, but the BBCodes do come out on the actual page.</p>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			$file_path = "storage/$username.txt";
			
			if (isset($_POST['submit'])){
			$title = $_POST['title'];
			$banner = $_POST['banner'];
			$bgcolor = $_POST['bgcolor'];
			$bgimage = $_POST['bgimage'];
			$bgwatermark = $_POST['bgwatermark'];
			$font = $_POST['font'];
			$textcolor = $_POST['textcolor'];
			$formcolor = $_POST['formcolor'];
			$firstcolor = $_POST['firstcolor'];
			$secondcolor = $_POST['secondcolor'];
			$order = $_POST['order'];
			$header = $_POST['header'];
			$footer = $_POST['footer'];
			$authority = $_POST['authority'];
			$lastmoddate = date("Y-m-d H:i:s");
						
			$entrycontent = array(array(0, $title), array(0, $banner), array(0, $bgimage), array(1, $header), array(1, $footer));
			include("../texteditor.php");
			list($title, $banner, $bgimage, $header, $footer) = $finalcontent;
			
			//message exceeds 5000?
			if (strlen($header) > 5000 or strlen($footer) > 5000)
			{echo $textarea_exceed; $invalid = true;}
			
			if ($invalid == false)
			{$list = array($lastmoddate, $title, $banner, $bgcolor, $bgimage, $bgwatermark, $font, $textcolor, $order, $formcolor, $firstcolor, $secondcolor, $header, $footer, $authority);
			$comma_separated = implode(";seDp#", $list);
			if (file_exists($file_path) && substr(sprintf('%o', fileperms($file_path)), -4) != 0755) {chmod($file_path, 0755);}
			$files = file($file_path, FILE_IGNORE_NEW_LINES);
			$file = array($files[0], $comma_separated);
			$comma_separated = implode("\n", $file);
			$write = fwrite(fopen($file_path, "w"), $comma_separated);
			if($write == true) {header("location: confirm.php?confirm=gblayout");}
			else {echo "<p>$db_error</p>";}}}
			
			$status = file($file_path, FILE_IGNORE_NEW_LINES);
			list($lastmoddatea, $titlea, $bannera, $bgcolora, $bgimagea, $bgwatermarka, $fonta, $textcolora, $ordera, $formcolora, $firstcolora, $secondcolora, $headera, $footera, $authoritya) = explode(";seDp#", $status[1]);
			$headera = str_replace(";seDpNL#", "\n", $headera);
			$footera = str_replace(";seDpNL#", "\n", $footera);?>
			
			<form name="editpage" action="" method="post">			
			<p>			
			<label>Page Title</label>
			<input name="title" value="<?php echo $titlea;?>" type="text" size="30" maxlength="40" />
			<label>Banner</label>
			<input name="banner" value="<?php echo $bannera;?>" type="text" size="30" maxlength="50" />
			<label>Background Color</label>
			<select name="bgcolor" size="1">
			<?php 
			$bgcolors = file("colors.txt");
			foreach ($bgcolors as $line) 
			{$explodetxt = explode(",", $line);
			if ($bgcolora == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Background Image URL</label>
			<input name="bgimage" value="<?php echo $bgimagea;?>" type="text" size="50" maxlength="100" />
			<? if ($bgimagea != ""){?><label>Background Watermark</label><?php 
			if ($bgwatermarka == "Yes") {$bgpsy = " checked=\"yes\"";} else {$bgpsn = " checked=\"yes\"";}?>
			Yes<input type="radio" name="bgwatermark" value="fixed"<?php echo $bgpsy;?> />
			No<input type="radio" name="bgwatermark" value="unfixed"<?php echo $bgpsn;?> /><?php }?>
			<label>Font</label>
			<select name="font" size="1">
			<?php 
			$fonts = file("fonts.txt");
			foreach ($fonts as $line) 
			{$explodetxt = explode(",", $line);
			if ($fonta == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[0]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Text Color</label>
			<select name="textcolor" size="1">
			<?php 
			$bgcolors = file("colors.txt");
			foreach ($bgcolors as $line) 
			{$explodetxt = explode(",", $line);
			if ($textcolora == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Entries Order</label>
			<?php if ($ordera == "asc") {$asc = " checked=\"yes\"";} else {$des = " checked=\"yes\"";}?>
			Chronogical<input type="radio" name="order" value="asc"<?php echo $asc?> />
			Reverse-Chronogical<input type="radio" name="order" value="des"<?php echo $des?> />
			<label>Authority</label>
			<?php if ($authoritya == "members") {$members = " checked=\"yes\"";} else {$everyone = " checked=\"yes\"";}?>
			Members Only<input type="radio" name="authority" value="members"<?php echo $members?> />
			Everyone<input type="radio" name="authority" value="everyone"<?php echo $everyone?> />
			<label>Form Color</label>
			<select name="formcolor" size="1">
			<?php 
			$bgcolors = file("colors.txt");
			foreach ($bgcolors as $line) 
			{$explodetxt = explode(",", $line);
			if ($formcolora == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>First Entries Color</label>
			<select name="firstcolor" size="1">
			<?php 
			$bgcolors = file("colors.txt");
			foreach ($bgcolors as $line) 
			{$explodetxt = explode(",", $line);
			if ($firstcolora == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Second Entries Color</label>
			<select name="secondcolor" size="1">
			<?php 
			$bgcolors = file("colors.txt");
			foreach ($bgcolors as $line) 
			{$explodetxt = explode(",", $line);
			if ($secondcolora == $explodetxt[0]) {$select = " selected=\"selected\"";}
			echo "<option value=\"{$explodetxt[0]}\"$select>{$explodetxt[1]}</option>\r\n";
			$select = "";}?>
			</select>
			<label>Header</label>
			<textarea name="header" rows="5" cols="5"><?echo $headera;?></textarea>
			<label>Footer</label>
			<textarea name="footer" rows="5" cols="5"><?echo $footera;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />		
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