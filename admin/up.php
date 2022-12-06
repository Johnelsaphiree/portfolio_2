<?php
$admin = $_COOKIE['admin'];
$auser = '';

if($admin == '')
{
	header("location: login.php");
}
?>