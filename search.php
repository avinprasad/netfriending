<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<title>NetFriending Search</title>
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
			
			<a name="NetFriending Search"></a>
			<h1>NetFriending Search</h1>
			<?php //Pre-configuration:
			//Require Message:
			include("notification.php");
			
			//If posted straight from search:
			$query = $_POST['search_query'];
			if ($query != "") {$query = urlencode($query);
			header("location: {$_SERVER['PHP_SELF']}?q=$query");}
			else {
			//Process the search:
			$query = $_GET['q'];
			$query = urldecode($query);
			$pquery1 = preg_quote($query, "/");
			$pqueries = preg_split("/\s+/", $pquery1);
			$texts = glob("search_db/db/*.txt");
			foreach ($texts as $text) {$result = true;
			$content = file_get_contents($text);
			foreach ($pqueries as $pquery) {
			if (!preg_match("/\b$pquery\b/i", $content)) {$result = false;}}
			if ($result == true) {$matches[] = $text;}}
			//Pagination Script Starts:
			//Set limit of number of entries on each page:
			$limit = 10;
			//Query the database to get total entries:
			$totalrows = count($matches);
			//Special Cases:
			$pagination = true;
			if ($query == "") {$pagination = false;
			echo $query_empty;}
			elseif ($totalrows == 0) {$pagination = false;
			echo $no_results_17;}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Echo out matched result details:
			$total = count($texts);
			echo "<p><i>$totalrows matched results out of $total pages.</i></p>";			
			// Query the database and set the start row and limit
			foreach($matches as $text) {
			//Get filename length
			$ordersort = strlen($text);
			//Get last mod date:
			$filesize = filesize($text);
			//Get file contents:
			$content = file_get_contents($text);
			//Get file title:
			preg_match("/<title>(.*)<\/title>/i", $content, $title);
			//put all in an array:
			$list = array($ordersort, $filesize, $content, $title[1], $text);
			
			//finished sort order********************************************************
			$array[] = implode(";seDp#", $list);}
			
			//order function*************************************************************			
			sort($array);
			//finish order function******************************************************
			
			foreach($array as $text){
			$parts = explode(";seDp#", $text);
			
			//back into order*************************************************************
			$list2 = array($parts[3], $parts[4], $parts[1],$parts[2]);
			//finished back order*********************************************************
			
			$texts2[] = $list2;}
			
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$numofpages = $totalrows / $limit;
			$totalpages = ceil($numofpages);
			if ($page == $totalpages) {$endvalue = $totalrows;}
			else {$endvalue = $startvalue + $limit;}
			
			//Start looping through the entries:
			while ($startvalue < $endvalue){
			
			//Perform organization:
			$nontags = strip_tags($texts2[$startvalue][3]);
			foreach ($pqueries as $pquery) {
			preg_match("/\b$pquery\b/i", $nontags, $matches);
			$strpos = strpos($nontags, $matches[0]);}
			if (($strpos - 150) < 0) {$str = 0;}
			else {$str = $strpos - 150;}
			$shortabv = substr($nontags, $str, 300);
			$title = $texts2[$startvalue][0];
			//highlighting match string:
			foreach ($pqueries as $pquery) {
			$shortabv = preg_replace("/\b($pquery)\b/i", "<font color=\"#FF0000\">$1</font>", $shortabv);
			$title = preg_replace("/\b($pquery)\b/i", "<font color=\"#FF0000\">$1</font>", $title);}
			//date:
			$filesize = round($texts2[$startvalue][2] / 1024);
			//Get url:
			$url = str_replace("search_db/db/", "", $texts2[$startvalue][1]);
			$url = str_replace("_dIR", "/", $url);
			$url = str_replace(".txt", "", $url);
			$url = "http://netfriending.co.cc/" . $url;
			
			//echo text:
			echo "<a href=\"$url\"><h3>$title</h3></a>";
			echo "<p>$shortabv</p>";
			echo "<p><font color=\"#cccccc\">". $url ." - ". $filesize ."kb</font></p>";
			
			$startvalue ++;}
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?q=$query&page=1\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?q=$query&page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
			else {echo "PREV&nbsp;";}
			//Page Range sorting:
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
			else {echo "<a href=\"".$_SERVER['PHP_SELF']."?q=$query&page=$i\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"".$_SERVER['PHP_SELF']."?q=$query&page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"".$_SERVER['PHP_SELF']."?q=$query&page=$totalpages\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}}?>
		
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