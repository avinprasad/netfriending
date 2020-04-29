<?php 
//Pre-confirguring:
require("../../mysqlconnection.php");
//Getting all the users with birthday:
$read = mysql_query("SELECT * FROM userinfo");
while ($r = mysql_fetch_array($read)) {
$array[$r['username']] = $r['birthday'];}
//Sort which is near:
foreach ($array as $username => $birthday) {
$read2 = mysql_query("SELECT * FROM usercus WHERE username='$username'");
$r2 = mysql_fetch_array($read2);
//whether or not to send notification email:
$notifications = explode(",", $r2['notification']);
if ($notifications[3] == 1) {
$birthday = explode("-",$birthday);
if (date("m-d") == "{$birthday[1]}-{$birthday[2]}") {
$read1 = mysql_query("SELECT * FROM userinfo WHERE username='$username'");
$r1 = mysql_fetch_array($read1);
//Send email to the birthdays:
$to = "{$r1['firstname']} {$r1['lastname']} <{$r1['email']}>";
$subject = "Happy Birthday!!! {$r1['firstname']} {$r1['lastname']}";
$textfile = file_get_contents("../emails/bdnotifier.txt");
$body = str_replace("%fname%", $r1['firstname'], $textfile);
$body = str_replace("%lname%", $r1['lastname'], $body);

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";

mail($to, $subject, $body, $headers);}}}
?>