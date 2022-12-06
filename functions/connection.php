<?php
$aut_host = 'host';//e.g localhost
$aut_user = 'database username';
$aut_pass = 'database password';
$aut_db = 'database';

$connect = mysql_pconnect($aut_host, $aut_user, $aut_pass);
$xtzone = mysql_query("SET time_zone = 'Africa/Lagos'");
mysql_select_db($aut_db);

/*$aut_host = 'localhost';//e.g localhost
$aut_user = 'root';
$aut_pass = '';
$aut_db = 'vsms';*/

//$connect = mysql_pconnect('localhost', 'root', '');
//mysql_select_db('vsms');

if($page != 'setup')
{
//get num
$con_num = mysql_query("select tell, sid, surl, sname, email, description, style from info where id = 1");
$con_num_row = mysql_fetch_row($con_num);

$mynumber = "$con_num_row[0]";
$csite_name = $con_num_row[3];
$csenderid = $con_num_row[1];
$csite_url = $con_num_row[2];
$cemail = $con_num_row[4];
$logo_link = $csite_url.'/images/logo.png';
$cdescription = $con_num_row[5];
$cstyle = $con_num_row[6];
}
?>