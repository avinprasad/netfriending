<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("allpages.php");?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?php include("head.html")?>
<title>NetFriending News List</title>
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
			
			<a name="NetFriending News List"></a>
			<h1>NetFriending News List</h1>
			<?php //Pre-configuration:
			//Require Message:
			$noty_subject = "newslist";
			include("notification.php");
			
			//Set number of entries on each page:
			$limit = 3;
			//Query the database to get total entries:
			$news = file("admin/news.txt");
			$totalrows = count($news);
			//Special Cases:
			$pagination = true;
			if ($totalrows == 0) {$pagination = false;
			echo "<p align=center><br /><br />$db_noentries</p>";}
			elseif ($news == false) {$pagination = false;
			echo "<p align=center><br /><br />$db_error</p>";}
			
			if ($pagination == true) {
			//Page variable set:
			if (isset($_GET['page'])) {$page = $_GET['page'];}
			else {$page = 1;}
			//Organize result details:
			$lines = file("admin/news.txt");
			$lines = array_reverse($lines);
			//Set variables:
			$startvalue = ($page * $limit) - $limit;
			$numofpages = $totalrows / $limit;
			$totalpages = ceil($numofpages);
			if ($page == $totalpages) {$endvalue = $totalrows;}
			else {$endvalue = $startvalue + $limit;}
			
			//Start looping through the entries:
			while ($startvalue < $endvalue){
			
			//Get general data:
			$data = explode(";seDp#", $lines[$startvalue]);
			//Perform organization:
			if (strlen($data[3]) > 500) {$extension = "...";}
			$entrycontent = "". substr($data[3], 0, 500) ."$extension";
			$extension = "";
			$textrcondition = 0;
			include("textreplacer.php");
			//Get Replies:
			$replies = file("newscomments/comments[{$data[2]}].txt");
			$total = count($replies);
			if (!file_exists("newscomments/comments[{$data[2]}].txt")){$total = 0;}
			
			echo "<a name=\"{$data[0]}\"></a>
			<h3>{$data[0]}</h3>
			<p>$entrycontent</p>
			<p class=\"post-footer align-right\">
			<a href=\"news.php?news={$data[2]}\" class=\"readmore\">Read more</a>
			<a href=\"news.php?news={$data[2]}#comments\" class=\"comments\">Comments ($total)</a>
			<span class=\"date\">{$data[1]}</span>
			</p>" . "\r\n";
			$startvalue ++;}
			//Starts page links:
			echo "<p align=center>";
			//Sets link for first page:
			if ($page != 1) {$pageprev = $page - 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=1\"><<</a>&nbsp;&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$pageprev\">PREV&nbsp;</a>&nbsp;";}
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
			else {echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$i\">$i</a> ";}
			$i ++;}
			//Set link for last page:
			if ($page != $totalpages) {$pagenext = $page + 1;
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$pagenext\">NEXT&nbsp;</a>&nbsp;";
			echo "<a href=\"{$_SERVER['PHP_SELF']}?page=$totalpages\">>></a>";}
			else {echo "NEXT&nbsp;";}
			echo "</p>";}?>
			
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