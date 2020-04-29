<?php
ini_set('display_errors', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();

/*##############################################################*\
|#                                    By: Shabbir H. A. Bhimani #|
|# This code is copyright(c) of GlobalDevelopers.net,all rights #|
|# reserved and this file may not be  redistributed in whole or #|
|# significant part.       Author: shabbir@globaldevelopers.net #|
\*##############################################################*/

require_once ("functions.php");

$returnurl = urlencode(isset($_GET["returnurl"])?$_GET["returnurl"]:"");
if($returnurl == "")
	$returnurl = urlencode(isset($_POST["returnurl"])?$_POST["returnurl"]:"");

$do = isset($_GET["do"])?$_GET["do"]:"";

$do = strtolower($do);

switch($do)
{
case "":
	if (checkLoggedin())
	{include("usernav.php"); include("userindex.html");} else {include("userlogin.html");
	if ($_SERVER['QUERY_STRING'] == "") {$page = "{$_SERVER['PHP_SELF']}";}
	else {$page = "{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}";}
	$_SESSION['page'] = $page;
	}
	break;
case "login":
	$username = isset($_POST["username"])?$_POST["username"]:"";
	$password = isset($_POST["password"])?$_POST["password"]:"";
	$username = strtolower($username);
	require("../mysqlconnection.php");
	$result = mysql_query("SELECT activation FROM userinfo WHERE username='$username'");
	$row = mysql_fetch_assoc($result);

	if ($username == "" or $password == "")
	{
		clearsessionscookies();
		header("location: /users/index.php?invalidlogin=blank");
	}
	elseif ($row['activation'] != "")
	{
		clearsessionscookies();
		header("location: /users/index.php?invalidlogin=inactivate");
	}
	else
	{	$md5pass = md5($password);
		if(confirmuser($username, $md5pass))
		{
			createsessions($username, $md5pass);
			if ($returnurl != "")
				header("location: /users/$returnurl");
			else
			{
				header("location: {$_SESSION['page']}");
				$datetime = date("Y-m-d H:i:s");
				$update = mysql_query("UPDATE userinfo SET lastlogin='$datetime' WHERE username='$username'");
			}
		}
		else
		{
			clearsessionscookies();
			header("location: /users/index.php?invalidlogin=invalid");
		}
	}
	break;
case "logout":
	clearsessionscookies();
	header("location: /users/index.php?invalidlogin=logout");
	break;
}
?>
