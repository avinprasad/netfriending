<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>Webmail Manager</title>
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
			<h3>WebMail</h3>
			<?php //Pre-configuration:
			//Require Message:
			include("../notification.php");
			
			$names = array("NetFriending Support", "NF Admin", "NF Support Group", "No-Reply", "NF WebMaster", "NetFriending", "Ted Chou");
			if ($_GET['logout'] == "true") {unset($_SESSION["mailuser"]); unset($_SESSION["mailpass"]); header("location: {$_SERVER['PHP_SELF']}?logout=logout");}
			
			//Initiate Connection to Mail Server:
			$imap_server = "{mail.netfriending.co.cc/novalidate-cert}";
			$imap_name = $_SESSION["mailname"];
			$imap_user = "{$_SESSION["mailuser"]}+netfriending.co.cc";
			$imap_pass = "{$_SESSION["mailpass"]}";
			
			$connection = imap_open("{$imap_server}", $imap_user, $imap_pass);
			if ($connection === false) {//login faliure:
			if (isset($_POST['submit'])) {
			if ($_POST['user'] == "" or $_POST['pass'] == "") {echo $login_blank_22;}
			else {
			$_SESSION["mailuser"] = strtolower($_POST['user']);
			$_SESSION["mailpass"] = strtolower($_POST['pass']);
			if ($_POST['nametext'] != "") {$_SESSION["mailname"] = $_POST['nametext'];}
			elseif ($_POST['nameselect'] != "") {$_SESSION["mailname"] = $_POST['nameselect'];}
			else {$_SESSION["mailname"] = $names[0];}
			header("location: {$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}");}}
			elseif ($_SESSION['mailuser'] != "" && $_SESSION['mailpass'] != "") {echo $login_invalid_22;}
			elseif ($_GET['logout'] == "logout") {echo $login_logout_22;}?>
			<form name="webmaillogin" action="" method="post">
			<p>	
			<label>Username:</label>
			<input name="user" type="text" value="" size="30" maxlength="10" /> @netfriending.co.cc
			<label>Password</label>
			<input name="pass" type="password" value="" size="30" maxlength="10" />
			<label>Name:</label>
			<input name="nametext" type="text" value="" size="20" maxlength="10" /> or 
			<select name="nameselect" id="nameselect">
				<option value="">Choose name...</option>
				<?php foreach ($names as $name) {
				echo "<option value=\"$name\">$name</option>";}?>
			</select>
			<br /><br />
			<input class="button" name="submit" type="submit" />
			</p>
			</form>
			
			
			<?php } else {//connection success, username, password correct:
			
			$dir = $_GET['dir'];
			//Reset to the chosen folder:
			if ($dir == "") {$dir = "INBOX";}
			$mbox = imap_open("{$imap_server}$dir", $imap_user, $imap_pass);
			
			//Get information of mailbox:
			$mailboxinfo = imap_mailboxmsginfo($mbox);
			//Size change:
			$filesize = $mailboxinfo->Size;
			if ($filesize < 1024) {$filesize_unit = "bytes";}
			elseif ($filesize < 1048576) {$filesize = $filesize / 1024; $filesize_unit = "KB";}
			else {$filesize = $filesize / 1048576; $filesize_unit = "MB";}?>
			
			<script type="text/javascript" src="../texteditor.js"></script>
			<table width="100%" cellpadding="0" cellspacing="1" border="1" bordercolor="#111111" style="border-collapse: collapse;">
			<tr height="25"><td width="100%" align=left background="../users/images/themes/0_gradient.gif">&nbsp;&nbsp;
			<a href="<?php echo "{$_SERVER['PHP_SELF']}?type=folderview&dir=$dir";?>"><b>Mailbox</b></a> | 
			<a href="<?php echo "{$_SERVER['PHP_SELF']}?type=sendmail";?>"><b>Compose New</b></a> | 
			<a href="<?php echo "{$_SERVER['PHP_SELF']}?type=searchmail";?>"><b>Search</b></a> | 
			<a href="<?php echo "{$_SERVER['PHP_SELF']}?logout=true";?>"><b>Logout</b></a> | 
			<a href="javascript: viewMore('webmail');" id="xwebmail"><b>Hide Details</b></a>
			</td></tr>
			<tr><td align=left>
			<body onLoad="mailCookie(status);"></body>
			<p id="webmail" style="display: block; padding-left: 15px;">
			<?php echo "<b>Current Date & Time:</b> ". $mailboxinfo->Date ."<br />";
			echo "<b>Mailbox connection details:</b> ". $mailboxinfo->Driver ."<br />";
			echo "<b>Mailbox Name:</b> ". $mailboxinfo->Mailbox ."<br />";
			echo "<b>Total Messages:</b> ". $mailboxinfo->Nmsgs ."<br />";
			echo "<b>New Messages:</b> ". $mailboxinfo->Recent ."<br />";
			echo "<b>Unread Messages:</b> ". $mailboxinfo->Unread ."<br />";
			echo "<b>Deleted Messages:</b> ". $mailboxinfo->Deleted ."<br />";
			echo "<b>Mailbox Size:</b> ". round($filesize, 2) ." $filesize_unit<br />";?>
			</p>
			</td></tr>
			</table>
			
			<?php //Get Page:
			$type = $_GET['type'];
			
			if ($type == "mailview") {//message mail view, show single message at a time:
			//define required variables:
			$id = $_GET['id'];
			$getchar = $_GET['charset'];
			if ($getchar == "") {$getchar = "UTF-8";}
			
			if (isset($_POST['action'])) {
			$action = $_POST['action'];
			//tag flags, delete, answer, seen, draft
			if (substr($action, 0, 2) == "Un") {
			$insert = imap_clearflag_full($mbox, $id, "\\". substr($action, 2));}
			else {$insert = imap_setflag_full($mbox, $id, "\\$action");}
			if ($insert == true)
			{header("location: confirm.php?confirm=webmail_actions&dir=$dir");}
			else
			{echo "<p>$db_error</p>";}}
			
			$header = imap_header($mbox, $id);
			//Flagged icons:
			if ($header->Flagged == "F") {$flag_file = "flag_red"; $flag_text = "Unflagged Message";}
			else {$flag_file = "flag_blue"; $flag_text = "Flagged Message";}
			if ($header->Deleted == "D") {$deleted_file = "_delete"; $deleted_text = " (Deleted)";}
			else {$deleted_file = ""; $deleted_text = "";}
			if ($header->Answered == "A") {$answered_file = "mail_send"; $answered_text = "Answered Message";}
			else {$answered_file = "mail"; $answered_text = "Unanswered Message";}
			//From:
			$froms = $header->from;
			foreach ($froms as $key => $object) {
			$fromname = $object->personal;
			$fromaddress = $object->mailbox ."@". $object->host;}
			//To:
			$tos = $header->to;
			foreach ($tos as $key => $object) {
			$toname = $object->personal;
			$toaddress = $object->mailbox ."@". $object->host;}
			//Reply To"
			$replys = $header->reply_to;
			foreach ($replys as $key => $object) {
			$replyname = $object->personal;
			$replyaddress = $object->mailbox ."@". $object->host;}
			//Body:
			$body = htmlspecialchars(imap_fetchbody($mbox, $id, "1"));
			$body = nl2br(iconv($getchar, "UTF-8", $body));
			//Date:
			$date = strtotime($header->date);
			$date = date("M d, Y h:i A", $date);
			//Flags:
			if ($header->Flagged == "F") {$flag_action = "UnFlagged"; $flag_caption = "Mark Unimportant";}
			else {$flag_action = "Flagged"; $flag_caption = "Mark Important";}
			if ($header->Deleted == "D") {$deleted_action = "UnDeleted"; $deleted_caption = "Mark Undeleted";}
			else {$deleted_action = "Deleted"; $deleted_caption = "Mark Deleted";}
			if ($header->Answered == "A") {$answered_action = "UnAnswered"; $answered_caption = "Mark Unreplied";}
			else {$answered_action = "Answered"; $answered_caption = "Mark Replied";}
			if ($header->Unseen == "U") {$read_action = "UnSeen"; $read_caption = "Mark Unread";}
			else {$read_action = "Seen"; $read_caption = "Mark Read";}
			if ($header->Draft == "X") {$draft_action = "UnDraft"; $draft_caption = "Mark non Draft";}
			else {$draft_action = "Draft"; $draft_caption = "Mark as Draft";}
			?>
			<table align=center cellpadding="0" cellspacing="1" width="100%" border="1" bordercolor="#000000" style="border-collapse: collapse;  margin-top: 15px;"><tr height="25px"><td width="100%" align=left background="../users/images/themes/0_gradient.gif">&nbsp;&nbsp;&nbsp;<img src="../users/images/icons/<?php echo $flag_file; echo $deleted_file;?>.gif" alt="<?php echo $flag_text; echo $deleted_text;?>" />&nbsp;&nbsp;<img src="../users/images/icons/<?php echo $answered_file;?>.gif" alt="<?php echo $answered_text;?>" /></td></tr></table>
			<div style="border: 1px solid black; border-top: none; overflow-y: hidden; overflow-x: auto;">
			<table align=center cellpadding="0" cellspacing="1" width="100%" id="views">
			<tr><td colspan=2><h1><?php echo htmlspecialchars($header->Subject);?></h1></td></tr>
			<tr><td width="20%"><b>From:</b></td><td><?php echo "$fromname &lt;$fromaddress&gt;";?></td></tr>
			<tr><td><b>To:</b></td><td><?php echo "$toname &lt;$toaddress&gt;";?></td></tr>
			<tr><td><b>Reply To:</b></td><td><?php echo "$replyname &lt;$replyaddress&gt;";?></td></tr>
			<tr><td><b>Size:</b></td><td><?php echo $header-Size;?></td></tr>
			<tr><td><b>Charset:</b></td><td>
			<select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($folder, $dir){if ($folder == $dir) echo " selected=\"selected\"";}?>
				<p><option value="">Body Content Charset...</option>
				<?php $charsets = file("charset.txt", FILE_IGNORE_NEW_LINES);
				foreach ($charsets as $charset) {$charset = explode("|", $charset);
				echo "<option value=\"{$_SERVER['PHP_SELF']}?type=$type&id=$id&dir=$dir&charset={$charset[0]}\"";
				echo select($charset[0], $getchar);
				echo ">{$charset[1]}</option>";}?>
			</select></td></tr>
			<tr width="100%"><td colspan=2><p><?php echo htmlspecialchars_decode($body);?></p></td></tr>
			<tr><td colspan=2><p class="post-footer align-right">
			<span class="date"><?php echo $date;?></span>
			</p></td></tr>
			</table>
			</div>
			<script type="text/javascript" src="../texteditor.js"></script>
			<form name="pmaction" action="" method="post">
			<p align=center>
			<input class="button" name="reply" type="button" value="Reply" onclick="javascript: location='<?php echo "{$_SERVER['PHP_SELF']}?type=sendmail&dir=$dir&id=$id&charset=$getchar&send=re";?>';" />
			<input class="button" name="forward" type="button" value="Forward" onclick="javascript: location='<?php echo "{$_SERVER['PHP_SELF']}?type=sendmail&dir=$dir&id=$id&charset=$getchar&send=fw";?>';" />
			<select name="action" onchange="javascript: this.form.submit(); selectdefault(this);">
				<option value="">Choose an action...</option>
				<option value="<?php echo $read_action;?>"><?php echo $read_caption;?></option>
				<option value="<?php echo $answered_action;?>"><?php echo $answered_caption;?></option>
				<option value="<?php echo $flag_action;?>"><?php echo $flag_caption;?></option>
				<option value="<?php echo $deleted_action;?>"><?php echo $deleted_caption;?></option>
				<option value="<?php echo $draft_action;?>"><?php echo $draft_caption;?></option>
			</select>
			</p>
			</form>
			<?php }
			
			elseif ($type == "sendmail") {//send messages in mail
			//define required variables:
			$id = $_GET['id'];
			$getchar = $_GET['charset'];
			if ($getchar == "") {$getchar = "UTF-8";}
			
			if (isset($id)) {//if id is submitted:
			$header = imap_header($mbox, $id);
			if (!isset($_POST['toemail'])) {
			$replys = $header->reply_to;
			foreach ($replys as $key => $object) {
			$replyname = $object->personal;
			$replymailbox = $object->mailbox;
			$replyhost = $object->host;}
			$froms = $header->from;
			foreach ($froms as $key => $object) {
			$fromname = $object->personal;
			$frommailbox = $object->mailbox;
			$fromhost = $object->host;}
			if ($_GET['send'] == "re") {
			if ($replyname != "" && $replymailbox != "" && $replyhost != "") {
			$toemail = "$replyname <$replymailbox@$replyhost>";}
			else {$toemail = "$fromname <$frommailbox@$fromhost>";}}}
			if (!isset($_POST['subject'])) {$subject = strtoupper($_GET['send']) .":". htmlspecialchars($header->Subject);}
			if (!isset($_POST['message'])) {$message = iconv($getchar, "UTF-8", htmlspecialchars(imap_fetchbody($mbox, $id, "1")));}}
			
			if (isset($_POST['submit'])) {
			$subject = $_POST['subject'];
			$toemail = $_POST['toemail'];
			$ccemail = $_POST['ccemail'];
			$bccemail = $_POST['bccemail'];
			$rtemail = $_POST['rtemail'];
			$message = $_POST['message'];
			$datetime = date("Y-m-d H:i:s");
			
			$entrycontent = array(array(6, $subject), array(6, $toemail), array(6, $message));
			include("../texteditor.php");
			list($subject, $email, $message) = $finalcontent;
			$message = str_replace("\r\n", "<br />", $message);
			
			//all required fields filled?
			if($message == "" or $toemail == "" or $subject == "")
			{echo $requiredfields; $invalid = true;}
			
			$body = htmlspecialchars_decode(htmlentities($message, ENT_NOQUOTES, "UTF-8"));
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$headers .= "From: $imap_name <". str_replace("+", "@", $imap_user) .">" . "\r\n";
			if ($rtemail != "") {$headers .= "Reply-To: $rtemail" . "\r\n";}
			
			if ($invalid == false)
			{if (imap_mail($toemail, $subject, $body, $headers, $ccemail, $bccemail)) 
			{imap_setflag_full($mbox, $id, "\\Answered");
			header("location: confirm.php?confirm=webmail_sendmail&dir=$dir");} 
			else 
			{echo "<p>$db_error</p>";}}}?>
			
			<form name="webmailsend" action="" method="post">
			<script type="text/javascript" src="texteditor.js"></script>
			<p><table border=0 align=center cellpadding="0" cellspacing="1" width="100%">
			<tr><td width="40%"><label>Subject</label></td>
			<td width="100%"><input name="subject" value="<?php echo $subject;?>" type="text" size="40" /></td></tr>
			<tr><td><label>To Address</label></td>
			<td width="100%"><input name="toemail" value="<?php echo htmlspecialchars_decode($toemail);?>" type="text" size="40" /></td></tr>
			<tr><td><label>Cc Address</label></td>
			<td width="100%"><input name="ccemail" value="<?php echo htmlspecialchars_decode($ccemail);?>" type="text" size="40" /></td></tr>
			<tr><td><label>Bcc Address</label></td>
			<td width="100%"><input name="bccemail" value="<?php echo htmlspecialchars_decode($bccemail);?>" type="text" size="40" /></td></tr>
			<tr><td><label>Reply-To Address</label></td>
			<td width="100%"><input name="rtemail" value="<?php echo htmlspecialchars_decode($rtemail);?>" type="text" size="40" /></td></tr>
			</table>
			<label>Message</label>
			<textarea name="message" rows="5" style="width: 100%;"><?php echo $message;?></textarea>
			<br />
			<input class="button" name="submit" type="submit" />
			</p>
			</form>
			<?php }
			
			elseif ($type == "searchmail") {//searches emails in mailbox
			if (isset($_POST['submit'])) {
			$read = urlencode($_POST['read']);
			$replied = urlencode($_POST['replied']);
			$importance = urlencode($_POST['importance']);
			$delete = urlencode($_POST['delete']);
			$keyword = urlencode($_POST['keyword']);
			$keytype = urlencode($_POST['keytype']);
			$to = urlencode($_POST['to']);
			$from = urlencode($_POST['from']);
			$cc = urlencode($_POST['cc']);
			$bcc = urlencode($_POST['bcc']);
			$subject = urlencode($_POST['subject']);
			$body = urlencode($_POST['body']);
			$text = urlencode($_POST['text']);
			$dateaction = urlencode($_POST['dateaction']);
			$date = urlencode(date("d-M-Y", strtotime("{$_POST['month']}/{$_POST['day']}/{$_POST['year']}")));
			
			header("location: {$_SERVER['PHP_SELF']}?type=folderview&search=on&read=$read&replied=$replied&importance=$importance&delete=$delete&keyword=$keyword&keytype=$keytype&to=$to&from=$from&cc=$cc&bcc=$bcc&subject=$subject&body=$body&text=$text&dateaction=$dateaction&date=$date");}?>
			
			<form name="searchmail" action="" method="post">
			<script type="text/javascript" src="texteditor.js"></script>
			<p><table border=0 align=center cellpadding="0" cellspacing="1" width="100%">
			<tr><th rowspan=4 valign=top><label>Tagged Mails</label></th>
			<td height="25px"><select name="read" style="min-width: 150px;">
				<option value="">Read criteria...</option>
				<option value="Seen">Read mails</option>
				<option value="UnSeen">Unread mails</option>
			</select></td></tr>
			<tr><td height="25px"><select name="replied" style="min-width: 150px;">
				<option value="">Replied criteria...</option>
				<option value="Answered">Replied mails</option>
				<option value="UnAnswered">Unreplied mails</option>
			</select></td></tr>
			<tr><td height="25px"><select name="importance" style="min-width: 150px;">
				<option value="">Flagged criteria...</option>
				<option value="Flagged">Flagged mails</option>
				<option value="UnFlagged">Unflagged mails</option>
			</select></td></tr>
			<tr><td height="25px"><select name="delete" style="min-width: 150px;">
				<option value="">Deleted criteria...</option>
				<option value="Deleted">Deleted mails</option>
				<option value="UnDeleted">Undeleted mails</option>
			</select></td></tr>
			<tr><td><label>Keywords</label></td><td><input name="keyword" value="" type="text" size="20" />
			<select name="keytype">
				<option value="include">Inclusive</option>
				<option value="exclude">Exclusive</option>
			</select></td></tr>
			<tr><td><label>To Field</label></td><td><input name="to" value="" type="text" size="30" /></td></tr>
			<tr><td><label>From Field</label></td><td><input name="from" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Cc Field</label></td><td><input name="cc" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Bcc Field</label></td><td><input name="bcc" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Subject Field</label></td><td><input name="subject" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Body Field</label></td><td><input name="body" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Text Field</label></td><td><input name="text" value="" type="text" size="30" /></td></tr>
			<tr><td><label>Date</label></td><td>
			<select name="dateaction" size="1">
				<option value="">Select Date</option>
				<option value="before">Before</option>
				<option value="on">On</option>
				<option value="since">Since</option>
			</select>
			<select name="day" size="1">
				<?php echo "<option value=\"\">Day</option>";
				$days = file("../users/days.txt");
				foreach ($days as $day) {$day = explode(",", $day);
				echo "<option value=\"{$day[0]}\">{$day[0]}</option>";}?>
			</select>
			<select name="month" size="1">
				<?php echo "<option value=\"\">Month</option>";
				$months = file("../users/months.txt");
				foreach ($months as $month) {$month = explode(",", $month);
				echo "<option value=\"{$month[0]}\">{$month[1]}</option>";}?>
			</select>
			<select name="year" size="1">
				<?php echo "<option value=\"\">Year</option>";
				$years = file("../users/years.txt");
				foreach ($years as $year) {$year = explode(",", $year);
				echo "<option value=\"{$year[0]}\">{$year[0]}</option>";}?>
			</select></td></tr>
			
			</table><br />
			<input class="button" name="submit" type="submit" /></p>
			</form>
			<?php }
			else {//folder view, show messages in inbox, junk, draft...etc
			//define required variables:
			$order = $_GET['order'];
			
			$folders = imap_getmailboxes($mbox, $imap_server, "*");
			foreach ($folders as $folder => $foldername) {
			$foldername = preg_replace("/\{(.+?)\}/", "", $foldername->name);
			$foldernames[] = $foldername;}
			
			//Delete messages:******************************************************************
			if (isset($_POST['delete']) && !empty($_POST['id'])) {
			//turn the id array into a comma separated string
			$id_list = implode(",", $_POST['id']);
			//Query to run
			$delete = imap_delete($mbox, $id_list);
			$delete1 = imap_expunge($mbox);
			if ($delete == true && $delete1 == true)
			{header("location: confirm.php?confirm=webmail_delete&dir=$dir");}
			else
			{echo "<p>$db_error</p>";}}
			//Mark messages:********************************************************************
			if (isset($_POST['action']) && !empty($_POST['id'])) {
			$actions = explode(",", $_POST['action']);
			//turn the id array into a comma separated string
			$id_list = implode(",", $_POST['id']);
			$insert = false;
			//move or copy
			if ($actions[0] == "move" or $actions[0] == "copy") {
			if ($actions[0] == "copy") {$insert = imap_mail_copy($mbox, $id_list, $actions[1]);}
			elseif ($actions[0] == "move") {$insert = imap_mail_move($mbox, $id_list, $actions[1]);}}
			//tag flags, delete, answer, seen, draft
			elseif ($actions[0] == "flag") {$action = $actions[1];
			if (substr($action, 0, 2) == "Un") {
			$insert = imap_clearflag_full($mbox, $id_list, "\\". substr($action, 2));}
			else {$insert = imap_setflag_full($mbox, $id_list, "\\$action");}}
			if ($insert == true)
			{header("location: confirm.php?confirm=webmail_actions&dir=$dir");}
			else
			{echo "<p>$db_error</p>";}}
			//Create or Delete folders:*********************************************************
			if (isset($_POST['folderaction'])) {$insert = false;
			if ($_POST['folderaction'] == "create" && $_POST['foldername'] != "") {
			$insert = imap_createmailbox($mbox, "{$imap_server}INBOX.{$_POST['foldername']}");}
			elseif ($_POST['folderaction'] == "rename" && $_POST['foldername'] != "" && $_POST['folder'] != "") {
			$insert = imap_renamemailbox($mbox, "{$imap_server}{$_POST['folder']}", "{$imap_server}INBOX.{$_POST['foldername']}");}
			elseif ($_POST['folderaction'] == "delete" && $_POST['folder'] != "") {
			$insert = imap_deletemailbox($mbox, "{$imap_server}{$_POST['folder']}");}
			if ($insert == true)
			{header("location: confirm.php?confirm=webmail_fld&pm=$dir");}
			else
			{echo "<p>$db_error</p>";}}
			
			//if search results are on:
			if ($_GET['search'] == "on") {$search = "on";
			$read = $_GET['read'];
			$replied = $_GET['replied'];
			$importance = $_GET['importance'];
			$delete = $_GET['delete'];
			$keyword = $_GET['keyword'];
			$keytype = $_GET['keytype'];
			$to = $_GET['to'];
			$from = $_GET['from'];
			$cc = $_GET['cc'];
			$bcc = $_GET['bcc'];
			$subject = $_GET['subject'];
			$body = $_GET['body'];
			$text = $_GET['text'];
			$dateaction = $_GET['dateaction'];
			$date = $_GET['date'];
			
			if ($keyword != "" && $keytype == "exclude") {$search_array[] = "UNKEYWORD \"". urldecode($keyword) ."\"";}
			elseif ($keyword != "") {$search_array[] = "KEYWORD \"". urldecode($keyword) ."\"";}
			if ($to != "") {$search_array[] = "TO \"". urldecode($from) ."\"";}
			if ($from != "") {$search_array[] = "FROM \"". urldecode($from) ."\"";}
			if ($cc != "") {$search_array[] = "CC \"". urldecode($cc) ."\"";}
			if ($bcc != "") {$search_array[] = "BCC \"". urldecode($bcc) ."\"";}
			if ($subject != "") {$search_array[] = "SUBJECT \"". urldecode($subject) ."\"";}
			if ($body != "") {$search_array[] = "BODY \"". urldecode($body) ."\"";}
			if ($text != "") {$search_array[] = "TEXT \"". urldecode($text) ."\"";}
			if ($read != "") {$search_array[] = urldecode($read);}
			if ($replied != "") {$search_array[] = urldecode($replied);}
			if ($importance != "") {$search_array[] = urldecode($importance);}
			if ($delete != "") {$search_array[] = urldecode($delete);}
			if ($dateaction != "") {$search_array[] = "". urldecode($dateaction) ." ". urldecode($date) ."";}
			
			$search_url = "&search=on&read=$read&replied=$replied&importance=$importance&delete=$delete&keyword=$keyword&keytype=$keytype&to=$to&from=$from&cc=$cc&bcc=$bcc&subject=$subject&body=$body&dateaction=$dateaction&date=$date";
			
			if (count($search_array) == 1) {$search_query = $search_array[0];}
			else {$search_query = implode(" ", $search_array);}
			if ($search_query == "") {$search_query = "ALL";}
			$results = imap_search($mbox, $search_query);
			foreach ($results as $result) {
			$header = imap_header($mbox, $result);
			$search_results[] = array($result, $header->subject, $header->fromaddress, $header->date, $header->Flagged, $header->Deleted, $header->Answered, $header->Unseen);}}?>
			
			<form name="mailboxform" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<p></p>
			<p><select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($folder, $dir){if ($folder == $dir) echo " selected=\"selected\"";}?>
				<p><option value="">Select a folder...</option>
				<?php $folders = imap_getmailboxes($mbox, $imap_server, "*");
				foreach ($folders as $folder => $foldername) {
				$foldername = preg_replace("/\{(.+?)\}/", "", $foldername->name);
				echo "<option value=\"{$_SERVER['PHP_SELF']}?type=$type&dir=$foldername\"";
				echo select($foldername, $dir);
				echo ">$foldername</option>";}?>
			</select>
			<select name="action" onchange="this.form.submit();">
				<option value="">Choose an action...</option>
				<optgroup label="Read">
				<option value="flag,Seen">Mark Read</option>
				<option value="flag,UnSeen">Mark Unread</option>
				<optgroup label="Replied">
				<option value="flag,Answered">Mark Replied</option>
				<option value="flag,UnAnswered">Mark Unreplied</option>
				<optgroup label="Importance">
				<option value="flag,Flagged">Mark Important</option>
				<option value="flag,UnFlagged">Mark Unimportant</option>
				<optgroup label="Delete">
				<option value="flag,Deleted">Mark Deleted</option>
				<option value="flag,UnDeleted">Mark Undeleted</option>
				<optgroup label="Draft">
				<option value="flag,Draft">Mark as Draft</option>
				<option value="flag,UnDraft">Mark non Draft</option>
				<?php //copy to the mailbox:
				echo "<optgroup label=\"Copy To\">";
				foreach ($foldernames as $foldername) {
				echo "<option value=\"copy,$foldername\">$foldername</option>";}
				//move to the mailbox:
				echo "<optgroup label=\"Move To\">";
				foreach ($foldernames as $foldername) {
				echo "<option value=\"move,$foldername\">$foldername</option>";}?>
			</select>
			<input class="button" name="delete" type="submit" value="Delete Selected" onClick="javascript: return checkDelete()" /></p>
			<?php //Order Sort
			if ($order == "senderasc") {$menu1 = "v";}
			elseif ($order == "senderdes") {$menu1 = "^";}
			elseif ($order == "subjectasc") {$menu2 = "v";}
			elseif ($order == "subjectdes") {$menu2 = "^";}
			elseif ($order == "dateasc") {$menu3 = "v";}
			else {$order = "datedes";$menu3 = "^";}
			//Link Menu
			if ($order == "senderasc") {$ordersendermenu = "senderdes";}
			else {$ordersendermenu = "senderasc";}
			if ($order == "subjectasc") {$ordersubjectmenu = "subjectdes";}
			else {$ordersubjectmenu = "subjectasc";}
			if ($order == "dateasc") {$orderdatemenu = "datedes";}
			else {$orderdatemenu = "dateasc";}
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Table & Top Links
			echo "<table border=0 align=center cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
						<tr height=\"28px\">
						 <td width=\"28px\" align=center background=\"../users/images/themes/0_gradient.gif\"><img src=\"../users/images/icons/flag_green.gif\"></td>
						 <td width=\"28px\" align=center background=\"../users/images/themes/0_gradient.gif\"><img src=\"../users/images/icons/mail_send.gif\"></td>
						 <td width=\"28px\" align=center background=\"../users/images/themes/0_gradient.gif\">
						 <input type=checkbox value=\"Check All\" onClick=\"this.value=check(this.form)\"></td>
						 <td width=\"80px\" background=\"../users/images/themes/0_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$page&order=$ordersendermenu\"><b>$menu1 Sender</b></a></td>
						 <td width=\"160px\" background=\"../users/images/themes/0_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$page&order=$ordersubjectmenu\"><b>$menu2 Subject</b></a></td>
						 <td width=\"70px\" background=\"../users/images/themes/0_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$page&order=$orderdatemenu\"><b>$menu3 Date</b></a></td>
						</tr>";
			
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 15;
			//Query the database to get total entries:
			if ($search == "on") {$totalrows = count($search_results);}
			else {$totalrows = imap_num_msg($mbox);}
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $webmail_nomessages;}
			elseif ($totalrows == false) {$pagination = false;
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=6 align=center><b>$db_error</b></td></tr>";}
			
			if ($pagination == true) {
			//Organize result details:
			if ($search == "on") {
			foreach ($search_results as $search_result) {
			//sort order*****************************************************************
			//order by the senders
			if ($order == "subjectasc" or $order == "subjectdes") 
			{$sort[] = array($search_result[1], $search_result);}
			elseif ($order == "senderasc" or $order == "senderdes") 
			{$sort[] = array($search_result[2], $search_result);}
			else 
			{$sort[] = array($search_result[3], $search_result);}}
			//finished sort order********************************************************
			//order function*************************************************************
			if ($order == "subjectasc" or $order == "senderasc" or $order == "dateasc") 
			{sort($sort);}
			else
			{rsort($sort);}
			//finish order function******************************************************
			
			foreach($sort as $sorted){
			//back into order*************************************************************
			$search_results1[] = $sorted[1];
			//finished back order*********************************************************
			}}
			
			else {
			//original order:
			$original_order = $mbox;
			//sort order*****************************************************************
			//order by the senders
			if ($order == "subjectasc" or $order == "subjectdes") 
			{$sort = "SORTSUBJECT";}
			elseif ($order == "senderasc" or $order == "senderdes") 
			{$sort = "SORTFROM";}
			else 
			{$sort = "SORTDATE";}
			//finished sort order********************************************************
			//order function*************************************************************
			if ($order == "subjectasc" or $order == "senderasc" or $order == "dateasc") 
			{$sorted_mbox = imap_sort($mbox, $sort, 0);}
			else
			{$sorted_mbox = imap_sort($mbox, $sort, 1);}
			//finish order function******************************************************
			}
			
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$numofpages = $totalrows / $limit;
			$totalpages = ceil($numofpages);
			if ($page == $totalpages) {$endvalue = $totalrows;}
			else {$endvalue = $startvalue + $limit;}
			
			//Start looping through the entries:
			while ($startvalue < $endvalue) {
			if ($search == "on") {$id = $search_results1[$startvalue][0];
			$date = $search_results1[$startvalue][3];
			$subject = $search_results1[$startvalue][1];
			$from = $search_results1[$startvalue][2];
			$flag = $search_results1[$startvalue][4];
			$delete = $search_results1[$startvalue][5];
			$answer = $search_results1[$startvalue][6];
			$seen = $search_results1[$startvalue][7];}
			else {$header = imap_header($mbox, $sorted_mbox[$startvalue]);
			$mid_1 = $header->message_id;
			$i = 0;
			while ($i < ($totalrows + 1)) {$header1 = imap_header($original_order, $i);
			$mid_2 = $header1->message_id;
			if ($mid_1 == $mid_2) {$id = $i;} $i ++;}
			$date = $header->date;
			$subject = $header->subject;
			$from = $header->fromaddress;
			$flag = $header->Flagged;
			$delete = $header->Deleted;
			$answer = $header->Answered;
			$seen = $header->Unseen;}
			$date = strtotime($date);
			$date = date("Y-m-d", $date);
			$subject1 = htmlentities(substr($subject, 0, 30));
			if (strlen($subject) > 30){$subject1 = "$subject1..";}
			if ($subject1 == "") {$subject1 = "(No Subject)";}
			$sender1 = htmlentities(substr($from, 0, 10));
			if (strlen($from) > 10){$sender1 = "$sender1..";}
			if ($flag == "F") {$flag_file = "flag_red"; $flag_text = "Unflagged Message";}
			else {$flag_file = "flag_blue"; $flag_text = "Flagged Message";}
			if ($delete == "D") {$deleted_file = "_delete"; $deleted_text = " (Deleted)";}
			else {$deleted_file = ""; $deleted_text = "";}
			if ($answer == "A") {$answered_file = "mail_send"; $answered_text = "Answered Message";}
			else {$answered_file = "mail"; $answered_text = "Unanswered Message";}
			if ($seen == "U") {$color = "#cccccc";}
			else {$color = "#f2f2f2";}
			echo "<tr height=\"25px\" bgcolor=\"$color\">";
			echo "<td align=center><img src=\"../users/images/icons/$flag_file$deleted_file.gif\" alt=\"$flag_text$deleted_text\" /></td>";
			echo "<td align=center><img src=\"../users/images/icons/$answered_file.gif\" alt=\"$answered_text\" /></td>";
			echo "<td align=center><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
			echo "<td><a href=\"{$_SERVER['PHP_SELF']}?type=mailview&id=$id&dir=$dir\">$sender1</a></td>";
			echo "<td><a href=\"{$_SERVER['PHP_SELF']}?type=mailview&id=$id&dir=$dir\">$subject1</a></td>";
			echo "<td>$date</td>";
			echo "</tr>";
			$startvalue ++;}
			//Close the table
			echo "</table>";
			//Starts page links:
			echo "<p><table align=center width=\"100%\"><tr><td align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=1&order=$order\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$pageprev&order=$order\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$i&order=$order\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$pagenext&order=$order\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?type=$type$search_url&dir=$dir&page=$totalpages&order=$order\">>></a>";}
			else {echo "NEXT&nbsp;";}}
			echo "</td></tr></table></p>";?>
			<p><select name="folder">
				<option value="">Choose a folder...</option>
				<?php foreach ($foldernames as $foldername) {
				echo "<option value=\"$foldername\">$foldername</option>";}?>
			</select>
			<input name="foldername" type="text" size="16" maxlength="20" />
			<select name="folderaction" id="folderaction" onChange="javascript: return checkseletDelete(this.id);">
				<option value="">Choose an action...</option>
				<option value="create">Create Folder</option>
				<option value="delete">Delete Folder</option>
				<option value="rename">Rename Folder</option>
			</select></p>
			</form>
			<?php }
			imap_close($mbox);}?>
			
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