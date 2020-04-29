<?php 
$query = $_GET['page'];
function menucurrent($query,$que){if ($query == $que){echo " id=\"current\"";}}
?>
<ul>
	<li<?php if ($_SERVER['PHP_SELF'] == "/index.php"){echo " id=\"current\"";}?>>
	<a href="http://localhost/netfriending/"><span>Home</span></a></li>
	
	<li<?php menucurrent($query,"faq")?>>
	<a href="http://localhost/netfriending/include.php?page=faq"><span>FAQ</span></a></li>
	
	<li<?php menucurrent($query,"services")?>>
	<a href="http://localhost/netfriending/include.php?page=services"><span>Services</span></a></li>
	
	<li<?php menucurrent($query,"support")?>>
	<a href="http://localhost/netfriending/include.php?page=support"><span>Support</span></a></li>
	
	<li<?php menucurrent($query,"about")?>>
	<a href="http://localhost/netfriending/include.php?page=about"><span>About</span></a></li>		
</ul>