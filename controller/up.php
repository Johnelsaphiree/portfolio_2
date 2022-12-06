<?php
$controller = $_COOKIE['controller'];

if($controller == '')
{
	header('location: ../index.php');
}
?>