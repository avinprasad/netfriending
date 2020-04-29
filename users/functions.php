<?php
function createsessions($username, $password)
{
	//Add additional member to Session array as per requirement
	session_start();

	$_SESSION["nfusername"] = strtolower($username);
	$_SESSION["nfpassword"] = $password;

	if(isset($_POST['remme']))
	{
		//Add additional member to cookie array as per requirement
		setcookie("nfusername", $_SESSION['nfusername'], time()+60*60*24*30, "/");
		setcookie("nfpassword", $_SESSION['nfpassword'], time()+60*60*24*30, "/");
		return;
	}
}

function clearsessionscookies()
{
    	unset($_SESSION['nfusername']);
	unset($_SESSION['nfpassword']);

	setcookie("nfusername", $_SESSION['nfusername'], time()-60*60*24*30, "/");
	setcookie("nfpassword", $_SESSION['nfpassword'], time()-60*60*24*30, "/");
}

function confirmUser($username, $password)
{
	/* Validate from the database but as for now just demo username and password */
	require("../mysqlconnection.php");
	$result = mysql_query("SELECT username, password, activation FROM userinfo WHERE username = '$username'");
	if (mysql_num_rows($result) > 0)
	{$row = mysql_fetch_assoc($result);
	$pass = $row['password'];
	if ($password == $pass && $row['activation'] == "")
		return true;
	else
		return false;}
}

function checkLoggedin()
{
	if(isset($_SESSION['nfusername']) && isset($_SESSION['nfpassword']))
		return true;
	elseif(isset($_COOKIE['nfusername']) && isset($_COOKIE['nfpassword']))
	{
		if(confirmUser($_COOKIE['nfusername'], $_COOKIE['nfpassword']))
		{
			createsessions($_COOKIE['nfusername'], $_COOKIE['nfpassword']);
			return true;
		}
		else
		{
			clearsessionscookies();
			return false;
		}
	}
	else
		return false;
}
?>
