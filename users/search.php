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
<title>Search Members</title>
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
			<h4>Search Members</h4>
			<?php //Pre-configuration:
			//Require MySQL:
			require("../mysqlconnection.php");
			//Require Message:
			include("../notification.php");
			
			if ($_POST['search_user'] != "" or $_POST['search_name'] != "") {
			$age = urlencode($_POST['age']);
			$country = urlencode($_POST['country']);
			$gender = urlencode($_POST['gender']);
			$photo = urlencode($_POST['photo']);
			if ($_POST['search_user'] != "") {
			$query = urlencode($_POST['search_user']);
			header("location: results.php?search=user&search_query=$query&age=$age&country=$country&gender=$gender&photo=$photo");}
			elseif ($_POST['search_name'] != "") {
			$query = urlencode($_POST['search_name']);
			header("location: results.php?search=name&search_query=$query&age=$age&country=$country&gender=$gender&photo=$photo");}}
			elseif ($_POST['search_email'] != "") {
			$query = urlencode($_POST['search_email']);
			header("location: results.php?search=email&search_query=$query");}
			
			if ($_GET['search'] == "user") {
			$user = urldecode($_GET['search_query']);
			$age = urldecode($_GET['age']);
			$country = urldecode($_GET['country']);
			$gender = urldecode($_GET['gender']);
			$photo = urldecode($_GET['photo']);}
			elseif ($_GET['search'] == "name") {
			$name = urldecode($_GET['search_query']);
			$age1 = urldecode($_GET['age']);
			$country1 = urldecode($_GET['country']);
			$gender1 = urldecode($_GET['gender']);
			$photo1 = urldecode($_GET['photo']);}
			elseif ($_GET['search'] == "email") {
			$email = urldecode($_GET['search_query']);}
			if ($_GET['search'] == "name") {$tabmenu = 2;}
			elseif ($_GET['search'] == "email") {$tabmenu = 3;}
			else {$tabmenu = 1;}?>
			
			<style type="text/css">
			/*Copyright (C) 2005 Ilya S. Lyubinskiy. All rights reserved.
			Technical support: http://www.php-development.ru/*/
			
			div.TabView div.Tabs {
			height: 25px;
			overflow: hidden;
			}
			
			div.TabView div.Tabs a {
			float: left;
			display: block;
			height: 25px;
			width: 110px;
			text-align: center;
			padding: 3px 4px;
			margin-right: 5px;
			border: 1px solid black;
			text-decoration: none;
			color: #4284B0;
			font: bold 12px Arial;
			background: url('images/themes/<?php echo $row['theme'];?>_shade.gif');
			}
			
			div.TabView div.Tabs a:hover, div.TabView div.Tabs a.Active {
			color: #9EC068;
			border-bottom: none;
			background: url('images/themes/<?php echo $row['theme'];?>_shadeactive.gif');
			}
			
			div.TabView div.Pages {
			width: 368px;
			height: 185px;
			border: 1px solid black;
			border-top: none;
			overflow: hidden;
			margin-bottom: 20px;
			}
			</style>
			<script type="text/javascript" src="search.js"></script>
			<div class="TabView" id="TabView" style="margin-left: 20px;">
				<div class="Tabs">
				  <a href="#" rel="searchname">Search Username</a>
				  <a href="#" rel="searchname">Search Name</a>
				  <a href="#" rel="searchname">Search E-mail</a>
				</div>
			
				<div class="Pages">
				  <div class="Pad" style="padding: 8px 20px;">
					<form method="post" class="searchform" action="">
					<table align="center" width="100%">
					<tr><td align="left" width="70%">
					<label>Username</label>
					<input name="search_user" value="<?php echo $user;?>" type="text" maxlength="50" />
					<input type="submit" name="searchuser" class="button" value="Search" /></td>
					<td align="left" width="100%">
					<label>Age</label>
					<select name="age" size="1">
					<?php $ages = array("no preference","under 20","20-29","30-39","40-49","50-59","60 above",);
					foreach ($ages as $ages) {if ($ages == $age) {$select = " selected=\"selected\"";}
					echo "<option value=\"$ages\"$select>$ages</option>\r\n";
					$select = "";}?>
					</select></td></tr></table>
					
					<table align="center" width="100%">
					<tr><td align="left" width="100%">
					<label>Country:</label>
					<select name="country" size="1">
					<option value="">no preference</option>
					<?php $countries = file("countries.txt");
					foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
					$parts1 = str_replace("\r\n","",$parts[1]);
					if ($parts[0] == $country) {$select = " selected=\"selected\"";}
					echo "<option value=\"". $parts[0] ."\"$select>". $parts1 ."</option>\r\n";$select = "";}?>
					</select>
					</td></tr>
					</table>
					
					<table align="center" width="100%">
					<tr><td align="left" width="50%"><label>Gender</label>
					<?php if ($gender == "Girl"){$girl = " checked=\"yes\"";} 
					elseif ($gender == "Boy"){$boy = " checked=\"yes\"";}
					else {$off = " checked=\"yes\"";}?>
					M <input type="radio" name="gender" value="Boy"<?php echo $boy;?> />
					F <input type="radio" name="gender" value="Girl"<?php echo $girl;?> />
					Off <input type="radio" name="gender" value="Off"<?php echo $off;?> /></td>
					<td align="left" width="100%">
					<label>Photo</label>
					<?php if ($photo == "No"){$no = " checked=\"yes\"";} 
					elseif ($photo == "Yes"){$yes = " checked=\"yes\"";}
					else {$off1 = " checked=\"yes\"";}?>
					Yes <input type="radio" name="photo" value="Yes"<?php echo $yes;?> />
					No <input type="radio" name="photo" value="No"<?php echo $no;?> />
					Off <input type="radio" name="photo" value="Off"<?php echo $off1;?> /></td></tr>
					</table>
					</form>
				  </div>
				
				  <div class="Pad" style="padding: 8px 20px;">
					<form method="post" class="searchform" action="">
					<table align="center" width="100%">
					<tr><td align="left" width="70%">
					<label>Name</label>
					<input name="search_name" value="<?php echo $name;?>" type="text" maxlength="50" />
					<input type="submit" name="searchname" class="button" value="Search" /></td>
					<td align="left" width="100%">
					<label>Age</label>
					<select name="age" size="1">
					<?php $ages = array("no preference","under 20","20-29","30-39","40-49","50-59","60 above",);
					foreach ($ages as $ages) {if ($ages == $age1) {$select = " selected=\"selected\"";}
					echo "<option value=\"$ages\"$select>$ages</option>\r\n";
					$select = "";}?>
					</select></td></tr></table>
					
					<table align="center" width="100%">
					<tr><td align="left" width="100%">
					<label>Country:</label>
					<select name="country" size="1">
					<option value="">no preference</option>
					<?php $countries = file("countries.txt");
					foreach ($countries as $ccountry) {$parts = explode(",", $ccountry);
					$parts1 = str_replace("\r\n","",$parts[1]);
					if ($parts[0] == $country1) {$select = " selected=\"selected\"";}
					echo "<option value=\"". $parts[0] ."\"$select>". $parts1 ."</option>\r\n";$select = "";}?>
					</select>
					</td></tr>
					</table>
					
					<table align="center" width="100%">
					<tr><td align="left" width="50%"><label>Gender</label>
					<?php if ($gender1 == "Girl"){$girl1 = " checked=\"yes\"";} 
					elseif ($gender1 == "Boy"){$boy1 = " checked=\"yes\"";}
					else {$off2 = " checked=\"yes\"";}?>
					M <input type="radio" name="gender" value="Boy"<?php echo $boy1;?> />
					F <input type="radio" name="gender" value="Girl"<?php echo $girl1;?> />
					Off <input type="radio" name="gender" value="Off"<?php echo $off2;?> /></td>
					<td align="left" width="100%">
					<label>Photo</label>
					<?php if ($photo1 == "No"){$no1 = " checked=\"yes\"";} 
					elseif ($photo1 == "Yes"){$yes1 = " checked=\"yes\"";}
					else {$off3 = " checked=\"yes\"";}?>
					Yes <input type="radio" name="photo" value="Yes"<?php echo $yes1;?> />
					No <input type="radio" name="photo" value="No"<?php echo $no1;?> />
					Off <input type="radio" name="photo" value="Off"<?php echo $off3;?> /></td></tr>
					</table>
					</form>
				  </div>
				
				  <div class="Pad" style="padding: 8px 20px;">
					<form method="post" class="searchform" action="">
					<table align="center" width="100%">
					<tr><td align="left" width="70%">
					<label>E-mail</label>
					<input name="search_email" value="<?php echo $email;?>" type="text" maxlength="50" />
					<input type="submit" name="searchemail" class="button" value="Search" /></td>
					</tr></table>
					</form>
				  </div>
				</div>
			</div>
			<script type="text/javascript">
			function tabview_initialize(TabViewId) {tabview_aux(TabViewId, <?php echo $tabmenu;?>);}
			tabview_initialize('TabView');
			</script>
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