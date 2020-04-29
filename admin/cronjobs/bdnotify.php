<?php 
//Pre-confirguring:
require("../../mysqlconnection.php");
//Getting all the users in the db:
$read = mysql_query("SELECT * FROM userinfo");
while ($r = mysql_fetch_array($read)) {
$read1 = mysql_query("SELECT * FROM usercus WHERE username='{$r['username']}'");
$r1 = mysql_fetch_array($read1);
//whether or not to send notification email:
$notifications = explode(",", $r1['notification']);
if ($notifications[0] == 1) {//if bday notification wanted, proceed:
$read2 = mysql_query("SELECT user FROM friendlist WHERE username='{$r['username']}' AND status='accepted'");
while ($r2 = mysql_fetch_array($read2)) {
$read3 = mysql_query("SELECT * FROM userinfo WHERE username='{$r2['user']}'");
$r3 = mysql_fetch_array($read3);
$birthday = explode("-", $r3['birthday']);
if (date("m") == "{$birthday[1]}") {$bdays[] = array($r3['username'], $r3['displayname']);}}
//If the list is not empty, send messages:
if (count($bdays) != 0) {//Prepare for some variables:
foreach ($bdays as $bday) {$links[] = "<b><a href=\"http://netfriending.co.cc/users/profiles.php?user={$bday[0]}\">{$bday[1]} ({$bday[0]})</a></b>";}
$link = implode(",", $links);
$month = date("F");
//send emails to notify the person about friend's bdays:
$to = "{$r['firstname']} {$r['lastname']} <{$r['email']}>";
$subject = "Hi! {$r['firstname']} {$r['lastname']}, here is the list of $month Birthdays!";
$textfile = file_get_contents("../emails/friendsbdnotifier.txt");
$body = str_replace("%fname%", $r['firstname'], $textfile);
$body = str_replace("%lname%", $r['lastname'], $body);
$body = str_replace("%month%", $month, $body);
$body = str_replace("%friendslist%", $link, $body);

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: NetFriending Support <noreply@netfriending.co.cc>" . "\r\n";

mail($to, $subject, $body, $headers);}}}
?>