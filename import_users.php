<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$shost = 'localhost';
$suser = 'root';
$spass = '';
$sdb = 'vsms';

	$connect = mysql_connect($shost, $suser, $spass);
	mysql_select_db($sdb);
	
	$file = fopen("jos_users.txt", "r");

	while(!feof($file))
	{
		$xfile = trim(fgets($file));
		$exp = explode('|', $xfile);
				$pass = md5($exp[2]);
				$query = mysql_query("insert into user (id, name, username, password, phone, email, balance, reserved, date_created, log_date, reseller, rate) values (0, '$exp[1]', '$exp[2]', '$pass', '', '$exp[3]', $exp[4], 0.00, '$now', '$now', 'N', 0.00)");	
	}
	fclose($file);
	
	echo "<h1>DONE!</h1>";
	?>