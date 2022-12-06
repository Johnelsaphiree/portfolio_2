<?php
$auser = (int)$_COOKIE['auser'];
$admin = '';

if($auser == '')
{
	header("location: ../index.php");
}

//check reseller
$cre = mysql_query("select reseller from user where id = $auser");
$cre_row = mysql_fetch_row($cre);
$ure = $cre_row[0];

//user info
$xuinfo = mysql_query("select email, phone from user where id = $auser");
$xuinfo_row = mysql_fetch_row($xuinfo);
$xuemail = $xuinfo_row[0];
$xuphone = $xuinfo_row[1];

?>