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
<?php $pm = strtolower($_GET['pm']);
if ($pm == "outbox") {$opp = "Receiver";}
else {$pm = "inbox"; $opp = "Sender";}
$ucpm = ucwords($pm);?>
<title>Private Messages <?php echo $ucpm;?></title>
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
			<h4>My <?php echo $ucpm;?></h4>
			<a href="pmsend.php">Compose new message</a>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			//Delete messages:
			if (isset($_POST['delete'])){
			if (!empty($_POST['id'])){
			// turn the id array into a comma separated string
			$id_list = implode(",", $_POST['id']);
			// Query to run
			$delete = mysql_query("DELETE FROM privatemessages WHERE id IN($id_list)");
			if ($delete === true)
			{header("location: confirm.php?confirm=pm_delete&pm=$pm");}
			else
			{echo "<p>$db_error</p>";}}}
			//Mark messages as unread:
			if (isset($_POST['unread'])){
			if (!empty($_POST['id'])){
			// turn the id array into a comma separated string
			$id_list = implode(",", $_POST['id']);
			// Query to run
			$insert = mysql_query("UPDATE privatemessages SET flag='unread' WHERE id IN($id_list)");
			if ($insert === true)
			{header("location: confirm.php?confirm=pm_unread&pm=$pm");}
			else
			{echo "<p>$db_error</p>";}}}
			?>
			
			<form name="deletepm" action="" method="post">
			<script type="text/javascript" src="../texteditor.js"></script>
			<?php $order = $_GET['order'];
			//Order Sort
			if ($order == "senderasc") {$orderby = "ORDER BY username"; $menu1 = "v";}
			elseif ($order == "senderdes") {$orderby = "ORDER BY username DESC"; $menu1 = "^";}
			elseif ($order == "subjectasc") {$orderby = "ORDER BY entrytitle"; $menu2 = "v";}
			elseif ($order == "subjectdes") {$orderby = "ORDER BY entrytitle DESC"; $menu2 = "^";}
			elseif ($order == "dateasc") {$orderby = "ORDER BY entrydate"; $menu3 = "v";}
			else {$order = "datedes";$orderby = "ORDER BY entrydate DESC"; $menu3 = "^";}
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
						 <td width=\"28px\" align=center background=\"images/themes/{$row['theme']}_gradient.gif\"><img src=\"images/xx.gif\"></td>
						 <td width=\"28px\" align=center background=\"images/themes/{$row['theme']}_gradient.gif\">
						 <input type=checkbox value=\"Check All\" onClick=\"this.value=check(this.form)\"></td>
						 <td width=\"80px\" background=\"images/themes/{$row['theme']}_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?pm=$pm&page=$page&order=$ordersendermenu\"><b>$menu1 $opp</b></a></td>
						 <td width=\"160px\" background=\"images/themes/{$row['theme']}_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?pm=$pm&page=$page&order=$ordersubjectmenu\"><b>$menu2 Subject</b></a></td>
						 <td width=\"70px\" background=\"images/themes/{$row['theme']}_gradient.gif\">
						 <a href=\"{$_SERVER['PHP_SELF']}?pm=$pm&page=$page&order=$orderdatemenu\"><b>$menu3 Date</b></a></td>
						</tr>";
			
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 15;
			//Query the database to get total entries:
			$read = mysql_query("SELECT * FROM privatemessages WHERE username='$username' AND type='$pm'");
			$totalrows = mysql_num_rows($read);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $pm_nomessages;}
			elseif ($read === false) {$pagination = false;
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>$db_error</b></td></tr>";}
			
			if ($pagination == true) {
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			$read = mysql_query("SELECT * FROM privatemessages WHERE username='$username' AND type='$pm' $orderby LIMIT $startvalue, $limit");
			while ($r = mysql_fetch_array($read)) {
			//Start looping through the entries:
			$dis_func = 1; $date = $r['entrydate']; $timeoffset = $row['timeoffset']; $date_format = $row['dateformat'];
			include("../disfunc.php");
			$subject = substr($r['entrytitle'], 0, 30);
			if (strlen($r['entrytitle']) > 30){$subject = "$subject..";}
			$user = substr($r['user'], 0, 10);
			if (strlen($r['user']) > 10){$user = "$user..";}
			if ($r['flag'] == "unread") {$color = "#cccccc";}
			else {$color = "#f2f2f2";}
			echo "<tr height=\"25px\" bgcolor=\"$color\">";
			echo "<td align=center><img src=\"images/{$r['entryicon']}.gif\"></td>";
			echo "<td align=center><input type=\"checkbox\" name=\"id[]\" value=\"{$r['id']}\"></td>";
			echo "<td><a href=\"profiles.php?user=$user\">$user</a></td>";
			echo "<td><a href=\"pmview.php?id={$r['id']}\">$subject</a></td>";
			echo "<td>$date</td>";
			echo "</tr>";}
			//Close the table
			echo "</table>";
			//Starts page links:
			echo "<p><table align=center width=\"100%\"><tr><td align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?pm=$pm&page=1&order=$order\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?pm=$pm&page=$pageprev&order=$order\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?pm=$pm&page=$i&order=$order\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?pm=$pm&page=$pagenext&order=$order\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?pm=$pm&page=$totalpages&order=$order\">>></a>";}
			else {echo "NEXT&nbsp;";}}
			echo "</td></tr></table></p>";?>
			<input class="button" name="delete" type="submit" value="Delete Selected" onClick="javascript: return checkDelete()" />
			<input class="button" name="unread" type="submit" value="Mark Unread" />
			<select onchange="window.open(this.options[this.selectedIndex].value,'_top')">
			<?php function select($pm, $type){if ($pm == $type) echo " selected=\"selected\"";}?>
				<option value="pm.php?pm=inbox"<?php select($pm, "inbox");?>>Inbox</option>
				<option value="pm.php?pm=outbox"<?php select($pm, "outbox");?>>Outbox</option>
			</select>
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