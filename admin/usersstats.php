<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>User Statistics</title>
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
			<h3>User Statistics</h3>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");?>
			
			<style type="text/css">
			td.label
			{
			background-image:
			url('../users/images/themes/0_gradient.gif');
			text-align: center;
			}
			</style>			
			<?php $order = $_GET['order'];
			//Order Sort
			if ($order == "userasc") {$orderby = "ORDER BY username"; $menu1 = "v";}
			elseif ($order == "userdes") {$orderby = "ORDER BY username DESC"; $menu1 = "^";}
			elseif ($order == "emailasc") {$orderby = "ORDER BY email"; $menu2 = "^";}
			elseif ($order == "emaildes") {$orderby = "ORDER BY email DESC"; $menu2 = "^";}
			elseif ($order == "lonasc") {$orderby = "ORDER BY onlinetime"; $menu3 = "^";}
			elseif ($order == "londes") {$orderby = "ORDER BY onlinetime DESC"; $menu3 = "^";}
			elseif ($order == "actasc") {$orderby = "ORDER BY activation"; $menu4 = "^";}
			elseif ($order == "actdes") {$orderby = "ORDER BY activation DESC"; $menu4 = "^";}
			elseif ($order == "dateasc") {$orderby = "ORDER BY signupdate"; $menu5 = "v";}
			else {$order = "datedes";$orderby = "ORDER BY signupdate DESC"; $menu5 = "^";}
			//Link Menu
			if ($order == "userasc") {$orderusermenu = "userdes";}
			else {$orderusermenu = "userasc";}
			if ($order == "emailasc") {$orderemailmenu = "emaildes";}
			else {$orderemailmenu = "emailasc";}
			if ($order == "lonasc") {$orderlonmenu = "londes";}
			else {$orderlonmenu = "lonasc";}
			if ($order == "actasc") {$orderactmenu = "actdes";}
			else {$orderactmenu = "actasc";}
			if ($order == "dateasc") {$orderdatemenu = "datedes";}
			else {$orderdatemenu = "dateasc";}
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Table & Top Links
			echo "<table id=\"admintbl\" border=0 cellpadding=\"0\" cellspacing=\"1\">
						<tr height=\"30px\">
						 <td width=\"20%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderusermenu\"><b>$menu1 Username</b></a></td>
						 <td width=\"25%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderemailmenu\"><b>$menu2 E-mail</b></a></td>
						 <td width=\"25%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderlonmenu\"><b>$menu3 Last Online</b></a></td>
						 <td width=\"6%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderactmenu\"><b>$menu4 Act</b></a></td>
						 <td width=\"100%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderdatemenu\"><b>$menu5 Signup</b></a></td>
						</tr>";
			
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 10;
			//Query the database to get total entries:
			$read2 = mysql_query("SELECT * FROM userinfo");
			$totalrows = mysql_num_rows($read2);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo $pm_nomessages;}
			elseif ($read2 === false) {$pagination = false;
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=5 align=center><b>$db_error</b></td></tr>";}
			
			if ($pagination == true) {
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$totalpages = ceil($totalrows / $limit);
			//Read entries from mysql:
			$read1 = mysql_query("SELECT * FROM userinfo $orderby LIMIT $startvalue, $limit");
			while ($r1 = mysql_fetch_array($read1)) {
			//Start looping through the entries:
			$date = strtotime($r1['onlinetime']);
			$date = date("Y-m-d h:i A", $date);
			$date1 = strtotime($r1['signupdate']);
			$date1 = date("Y-m-d h:i A", $date1);
			if ($r1['activation'] == "") {$activation = "Y";}
			else {$activation = "N";}
			$email = substr($r1['email'], 0, 18);
			if (strlen($r1['email']) > 18){$email = "$email..";}
			if ($read % 2 == "0") {$color = "#f2f2f2";}
			else {$color = "#cccccc";}
			echo "<tr height=\"25px\" bgcolor=\"$color\">";
			echo "<td align=center><a href=\"../users/profiles.php?user={$r1['username']}\">{$r1['username']}</a></td>";
			echo "<td>$email</a></td>";
			echo "<td align=center>$date</a></td>";
			echo "<td align=center>$activation</td>";
			echo "<td align=center>$date1</td>";
			echo "</tr>";
			$read ++;}
			//Close the table
			echo "</table>";
			//Starts page links:
			echo "<p><table align=center width=\"100%\"><tr><td align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=1&order=$order\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$pageprev&order=$order\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$i&order=$order\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$pagenext&order=$order\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$totalpages&order=$order\">>></a>";}
			else {echo "NEXT&nbsp;";}}
			echo "</td></tr></table></p>";?>
			
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