<?php 
ob_start();
session_start();

//Choosing clientside or serverside:
//if ajax:
if ($client_side == false) {
//get the run_ajax $var:
if ($run_ajax == "") {$run_ajax = $_GET['ajax'];}

//Pre-configuration:
//Require MySQL:
require("../mysqlconnection.php");

//notices************************************************************************************************************************************************
if ($run_ajax == "notices") {
//tmp username:
$username = $_SESSION['nfusername'];
$id = $_GET['id'];
$set = $_GET['set'];
$datetime = date("Y-m-d H:i:s");

//check to see id and set is valid:
$load = true;
$read = mysql_query("SELECT * FROM notice WHERE id='$id'");
if (mysql_num_rows($read) == 0) {$load = false;}
if ($set != 0 && $set != 1 && $set != 2) {$load = false;}
//reset notices:
if ($load == true && isset($id)) {
$read = mysql_query("SELECT * FROM usernotice WHERE noticeid='$id' AND username='$username'");
if (mysql_num_rows($read) == 0) {$insert = mysql_query("INSERT INTO usernotice(username, noticeid, noticeset, noticedate) VALUES('$username', '$id', '$set', '$datetime')");}
else {$insert1 = mysql_query("UPDATE usernotice SET noticeset='$set', noticedate='$datetime' WHERE noticeid='$id' AND username='$username'");}}}
//End of notices#########################################################################################################################################
}

//For Both clientside or serverside:
//notices************************************************************************************************************************************************
if ($run_ajax == "notices") {
//get notices:
$read4 = mysql_query("SELECT * FROM notice");
while ($notices = mysql_fetch_array($read4)) {$load = true;
$read5 = mysql_query("SELECT * FROM usernotice WHERE username='$username' AND noticeid='{$notices['id']}'");
$notice = mysql_fetch_array($read5);
if (($notice['noticeset'] == 1 && (strtotime("now") - strtotime($notice['noticedate']) < 60*60*24)) || $notice['noticeset'] == 2) {
$load = false;}
if ($load == true) {$notices1[$notices['id']] = $notices['noticecontent'];}}

if (count($notices1) != 0) { 
foreach ($notices1 as $key1 => $notice1) {?>
<div style="width:460px; text-align:right; background-color:#cccccc; padding:3px 5px 5px 5px; margin-bottom: 10px;"><a style="float:left;" onclick="javascript:ajaxNotices(<?php echo $key1;?>,1);">[Ask me Later]</a><a onclick="javascript:ajaxNotices(<?php echo $key1;?>,2);">[X]</a>
<div style="width:440px; text-align:left; background-color:#f2f2f2; margin-top:3px; padding:10px 10px 10px 10px;">
<?php $entrycontent = $notice1;
$textrcondition = 1;
include("../textreplacer.php");
echo $entrycontent;?>
</div></div>
<?php }}}
//End of notices#########################################################################################################################################
?>