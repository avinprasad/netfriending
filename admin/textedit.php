<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Text Editor</title>
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
			
		<div id="admin">
			
			<?php include("menu.php");?>
			<h3>Text Editor</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			$id = $_GET['id'];
			$extension = end(explode(".", $id));
			$filesize = filesize($id);
			if ($filesize < 1024) {$filesize_unit = "bytes";}
			elseif ($filesize < 1048576) {$filesize = $filesize / 1024; $filesize_unit = "KB";}
			else {$filesize = $filesize / 1048576; $filesize_unit = "MB";}
			if (substr($id, 0, 3) == "../") {$filepath = substr($id, 3);}
			else {$filepath = "admin/$id";}
			$updatetime = date("M d, Y h:i A", filemtime($id));
			$fileper = substr(decoct(fileperms($id)), 2);
			
			$load = true;
			
			if ($id == "") {
			echo $file_empty; $load = false;}
			else {
			
			if (file_exists($id) == false) {
			echo $file_notexist; $load = false;}
			else {
			
			if ($extension != "txt") {
			echo $extension_invalid; $load = false;}
			else {
			
			if(filesize($id) > 2097152) {
			echo $file_toobig; $load = false;}}}}
			
			if ($load == true) {
			echo "<p><b>File Extension Type:</b> $extension<br />
			<b>File Size:</b> ". round($filesize, 2) ." $filesize_unit<br />
			<b>File Path URL:</b> <a href=\"http://{$_SERVER['SERVER_NAME']}/$filepath\">$filepath</a><br />
			<b>Last Updated:</b> $updatetime<br />
			<b>File Permission:</b> $fileper<br /></p>";}
			
			if (isset($_POST['submit'])) {
			$message = $_POST['message'];
			
			$message = stripslashes($message);
			
			$file = fopen($id, "w");
			$write = fwrite($file, $message);
			fclose($file);
			
			if($write == true)
			{header("location: confirm.php?confirm=textedit&id=$id");}
			else
			{echo "<p>$db_error</p>";}}
			
			//Recall Data from DB:
			if ($load == true) {
			$message = file_get_contents($id);}
			
			//Set values for form post:
			$form = "texteditor";
			$textarea = "message";?>
			
			<form name="<?php echo $form;?>" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php $subarray1 = array(";seDp#" => "Separate String",
									  ";seDpNL#" => "NewLine String");
			$subarray2 = array("%fname%" => "Firstname",
							   "%lname%" => "Lastname",
							   "%uname%" => "Username",
							   "%username%" => "Username",
							   "%password%" => "Password",
							   "%dname%" => "Displayname",
							   "%photo%" => "Photo Link",
							   "%width%" => "Photo Width",
							   "%height%" => "Photo Height",
							   "%message%" => "Message Content",
							   "%name%" => "Sender Name",
							   "%tname%" => "To Name",
							   "%news%" => "News");
			$main_array = array("Text File Special Strings:" => $subarray1, "Email Text File Special Strings:" => $subarray2);
			
			foreach ($main_array as $text => $subarray) {
			echo "<p><b>$text</b> ";
			foreach ($subarray as $string => $text) {
			echo "<a title=\"$text\" style=\"cursor:pointer;border:0\" onClick=\"insert('$form', '$textarea', '$string', '')\">$string</a> | ";}
			echo "</p>";}?>
			<p>
			<textarea id="admintextarea" name="<?php echo $textarea;?>" rows="15" cols="10"><?echo $message?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />
			</p>
			</form>
			<p>
			<select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($filename, $file){if ($filename == $file) echo " selected=\"selected\"";}?>
				<option value="">Select text filename...</option>
				<?php $dirs = array("*.txt" => "Public Text Files",
									 "emails/*.txt" => "Email Text Files", 
									 "../*.txt" => "Root Text Files",
									 "../users/*.txt" => "User Text Files",
									 "../forum/*.txt" => "Forum Text Files",
									 "../forum/onlinestats/*.txt" => "Forum Stastics Text Files",
									 "../newscomments/*.txt" => "News Comments Text Files");
				foreach ($dirs as $dirpath => $dirname) {echo "<optgroup label=\"$dirname\">";
				$files = glob($dirpath);
				foreach ($files as $file) {$filename = ucwords(str_replace(".txt", "", end(explode("/", $file))));
				echo "<option value=\"{$_SERVER['PHP_SELF']}?id=$file\"";
				echo select($id, $file);
				echo ">$filename</option>";}}?>
			</select>
			</p>
			
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