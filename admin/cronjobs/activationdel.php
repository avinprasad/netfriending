<?php 
//Pre-confirguring:
require("../../mysqlconnection.php");
//Getting all the users in the db:
$read = mysql_query("SELECT username FROM userinfo WHERE signupdate < DATE_ADD(NOW(), INTERVAL -2 DAY) AND activation!=''") or die (mysql_error());
while ($r = mysql_fetch_array($read)) {
$read1 = mysql_query("DELETE FROM userinfo WHERE username='{$r['username']}'") or die (mysql_error());
$read2 = mysql_query("DELETE FROM usercus WHERE username='{$r['username']}'") or die (mysql_error());
}
?>