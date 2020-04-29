<?php //setup curl initiation:
set_time_limit(0);
$files = file("config.txt");
$referer = "http://localhost/netfriending/search_db/crawler.php";
$useragent = $files[1];
$limitonpage = $files[0];
$bot = curl_init();

//read links from links.txt:
$files = file("links.txt", FILE_IGNORE_NEW_LINES);
//randomize gather new partial arrays:
shuffle($files);
$files1 = array_slice($files, 0, $limitonpage);
$newdbtxt = array_slice($files, $limitonpage);
//check if file exists in db folder already:
foreach ($files1 as $file) {
$filename = str_replace("/", "_dIR", $file);
//first make sure the link in the links.txt works:
//if file exists in the db folder, make sure it still works:
curl_setopt($bot, CURLOPT_URL, "http://localhost/netfriending/$file");
curl_setopt($bot, CURLOPT_REFERER, $referer);
curl_setopt($bot, CURLOPT_USERAGENT, $useragent);
curl_setopt($bot, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($bot);
//if file still works, update it:
if (is_int(strpos($content, "<title>ErrorDocument 404</title>")) != true && end(explode(".", parse_url($file, PHP_URL_PATH))) == "php") {
$file1 = fopen("db/$filename.txt", "w");
$write = fwrite($file1, $content);
fclose($file1);
//put it into new links.txt array:
$newdbtxt[] = $file;}
else {//if file doesnt work anymore and it is in db folder already, delete it:
if (file_exists("db/$filename.txt")) {unlink("db/$filename.txt");}}}

$files = glob("db/*.txt");
//randomize and limit:
shuffle($files);
$files = array_slice($files, 0, $limitonpage);
//Rip the new urls of the texts:######################################################################################################################
foreach ($files as $file) {
$content = file_get_contents($file);
//Later use:
preg_match_all("/a[\s]+[^>]*?href[\s]?=[\s\"\']+(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", $content, $link);
$links = $link[1];
//Each url is modified:
foreach ($links as $link) {
$url = parse_url($link);
//If there is host, only netfriending.co.cc domain:
if ($url['host'] == "netfriending.co.cc") {
if (substr($url['path'], 0, 1) == "/") {
$link1 = substr($url['path'], 1);}
else {$link1 = $url['path'];}}
//If no host, needed to be sorted out:
elseif ($url['host'] == "") {
//If the txt file locate not in root:
if (is_int(strpos($file, "_dIR")) == true) {
$file1 = str_replace("db/", "", $file);
$parts = explode("_dIR", $file1);
$number = count($parts);
$filename = $parts[$number - 1];
$dirlength = strpos($file1, $filename);
$dirname = substr($file1, 0, $dirlength);
$dirname = str_replace("_dIR", "/", $dirname);
//If there are pre-dirs ..:
if (substr($url['path'], 0, 3) == "../") {
$parts1 = explode("../", $url['path']);
$number1 = count($parts1) - 1;
$filepath = "{$parts[0]}/"; $i = 1;
while ($i != ($number - $number1)) {$filepath .= "{$parts[$i]}/"; $i ++;}
$link1 = "$filepath{$parts1[$number1]}";}
else {$link1 = "$dirname{$url['path']}";}}
elseif (substr($url['path'], 0, 1) == "/") {
$link1 = substr($url['path'], 1);}
else {if (substr($url['path'], 0, 3) != "../") {$link1 = $url['path'];}}}
//Queries, after ?:
if ($url['query'] == "") {$links1[] = $link1;}
else {$links1[] = "$link1?{$url['query']}";}
//Clear url, so strings does not stay in it:
unset($url);}}
//Last edit, write into links.txt:
foreach ($links1 as $link) {
$link = str_replace("\n", "", $link);
$link = str_replace("\r", "", $link);
$link = str_replace(" ", "", $link);
//make sure the link works
curl_setopt($bot, CURLOPT_URL, "http://localhost/netfriending/$link");
curl_setopt($bot, CURLOPT_REFERER, $referer);
curl_setopt($bot, CURLOPT_USERAGENT, $useragent);
curl_setopt($bot, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($bot);
if (is_int(strpos($content, "<title>ErrorDocument 404</title>")) != true && end(explode(".", parse_url($link, PHP_URL_PATH))) == "php") {
//put it into new links.txt array if link works:
$newdbtxt1[] = $link;}}

//combine two arrays, make links unique:
$files = array_merge($newdbtxt, $newdbtxt1);
$files = array_unique($files);
sort($files);
//write into links.txt:
$file = fopen("links.txt", "w");
$write = fwrite($file, implode("\n", $files));
fclose($file);

curl_close($bot);?>