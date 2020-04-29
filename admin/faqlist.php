<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("../allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("../head.html")?>
<title>All FAQ Entries</title>
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
			<h3>FAQs List</h3>
			<p><a href="faqentry.php">New FAQ Entry</a></p>
			<?php //Pre-configuration:
			//Require Message:
			$noty_subject = "faqlist";
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
			if ($order == "questionasc") {$menu1 = "v";}
			elseif ($order == "questiondes") {$menu1 = "^";}
			elseif ($order == "dateasc") {$menu2 = "v";}
			else {$order = "datedes"; $menu2 = "^";}
			//Link Menu
			if ($order == "questionasc") {$orderquestionmenu = "questiondes";}
			else {$orderquestionmenu = "questionasc";}
			if ($order == "dateasc") {$orderdatemenu = "datedes";}
			else {$orderdatemenu = "dateasc";}
			//Table & Top Links
			echo "<p><table id=\"admintbl\" border=0 cellpadding=\"0\" cellspacing=\"1\">
						<tr height=\"30px\">
						 <td width=\"70%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderquestionmenu\">
						 <b>$menu1 Question</b></a></td>
						 <td width=\"100%\" class=\"label\">
						 <a href=\"{$_SERVER['PHP_SELF']}?page=$page&order=$orderdatemenu\">
						 <b>$menu2 Date</b></a></td>
						</tr>";
			
			//Query the database to get total entries:
			$lines = file("faq.txt", FILE_IGNORE_NEW_LINES);
			$totalrows = count($lines);
			//Special Cases:
			if ($totalrows == 0) {
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=2 align=center>$db_noentries</td></tr>";}
			elseif ($lines == false) {
			echo "<tr height=30 bgcolor=\"#f2f2f2\"><td colspan=2 align=center>$db_error</td></tr>";}
			else {
			
			//Read entries from file:
			foreach($lines as $line) {
			$parts = explode(";seDp#", $line);
			
			//sort order*****************************************************************
			//order by the questions
			if ($order == "questionasc" or $order == "questiondes") 
			{$list = array($parts[1],$parts[0],$parts[2]);}
			else 
			{$list = array($parts[2],$parts[0],$parts[1]);}
			//finished sort order********************************************************
			$array[] = implode(";seDp#", $list);}
			//order function*************************************************************			
			if ($order == "questionasc" or $order == "dateasc") 
			{sort($array);}
			else
			{rsort($array);}
			//finish order function******************************************************
			
			foreach($array as $line){
			$parts = explode(";seDp#", $line);
			//back into order*************************************************************
			if ($order == "questionasc" or $order == "questiondes") 
			{$list2 = array($parts[1],$parts[0],$parts[2]);}
			else 
			{$list2 = array($parts[1],$parts[2],$parts[0]);}
			//finished back order*********************************************************
			$data[] = $list2;}
			
			// Start loop through records
			foreach($data as $data){
			if ($read % 2 == "0") {$color = "#f2f2f2";}
			else {$color = "#cccccc";}
			
			// Echo out the records with wordwrap, remove if you like
			echo "<tr height=\"25px\" bgcolor=\"$color\">";
			echo "<td>&nbsp;<a href=\"faqedit.php?id={$data[0]}\">{$data[1]}</a></td>";
			echo "<td align=\"center\">{$data[2]}</td>";
			echo "</tr>";
			$read ++;}}
			// Close the table
			echo "</table></p>";?>
			
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