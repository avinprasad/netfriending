<?php //display functions:
if ($dis_func == 0 or $dis_func == 1 or $dis_func == 2 or $dis_func == 3) {//time display formats:
//default time format:
if ($date_format == "") {$date_format = "%b %d, %Y";}
if ($time_format == "") {$time_format = "%I:%M %p";}
if ($dis_func == 3) {//forum userlocation, no date
$date = strftime($time_format, strtotime($date) + $timeoffset);}
elseif ($dis_func == 2) {//local time, does not switch to user friendly date:
$date = strtotime($date) + $timeoffset;
$date = strftime("$date_format $time_format", $date);}
elseif ($dis_func == 1) {//pm only date:
$date = strftime($date_format, strtotime($date) + $timeoffset);}
else {//normal date func:
$entry_date = date("d/m/y", strtotime($date) + $timeoffset);
$today_date = date("d/m/y", strtotime("now") + $timeoffset);
$yesterday_date = date("d/m/y", strtotime("-1 day") + $timeoffset);
if ($entry_date == $today_date) {$date1 = "Today at";}
elseif ($entry_date == $yesterday_date) {$date1 = "Yesterday at";}
else {$date1 = strftime($date_format, strtotime($date) + $timeoffset);}
$date2 = strftime($time_format, strtotime($date) + $timeoffset);
$date = $date1 . " " . $date2;}}
?>