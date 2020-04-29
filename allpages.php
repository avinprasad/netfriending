<?php 
//turn all errors off, security issue
error_reporting(0);
//set time limit of load, big text files
set_time_limit(0);
//set default timezone, doesn't change with server
date_default_timezone_set("Asia/Taipei");

//determine if is searchengine or not, and destroy sessions or not
$useragent = $_SERVER['HTTP_USER_AGENT'];
$result = false;
$searchengines = array("bot", "crawler", "spider", "google", "yahoo", "msn", "ask", "ia_archiver");
foreach ($searchengines as $searchengine) {
$match = "/$searchengine/i";
if (preg_match($match, $useragent)) {$result = true;}}
if ($result == true) {session_destroy();}
?>