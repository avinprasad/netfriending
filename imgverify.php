<?php session_start();
//send img to header as jpg file
header('Content-type: image/jpeg');

//generate random string:
$alphanum = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
$string = substr(str_shuffle($alphanum), 0, 5);

//store in session (md5)
$_SESSION['imageverify'] = md5($string);

//create image from existing bgs
$bgno = rand(1, 4);
$image = imagecreatefromjpeg("images/background$bgno.jpg");

//white bg, black text
$bgcolor = imagecolorallocate($image, 255, 255, 255);
$textcolor = imagecolorallocate($image, 0, 0, 0);

//producing image (img, font, x, y, text, color)
imagestring($image, 5, 7, 8, $string, $textcolor);

imagejpeg($image);
imagedestroy($image);?>