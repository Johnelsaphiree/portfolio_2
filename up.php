<?php
@$admin = $_COOKIE['admin'];
@$auser = $_COOKIE['auser'];
@$xhome = 'yes';
/*if(@$admin != '')
{
	header("location: admin/index.php");
}
if(@$auser != '')
{
	header("location: users/index.php");
}*/

//check setup

$setup = mysql_query("select stat from info where id = 1");
if(mysql_num_rows($setup) > 0)
{
	$setup_row = mysql_fetch_row($setup);
	if($setup_row[0] == 'setup')
	{
		header("location: setup.php");
		exit;
	}
}
else
{
	header("location: setup.php");
	exit;
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$xpassword = md5($password);
$login = isset($_POST['login']) ? $_POST['login'] : '';

//$large = new large(@$bud);

if($login)
{
	$check = new select();
	$check->pick('user', 'id', 'username,password', "'$username','$xpassword'", '', 'record', '', '', '=,=', 'and');
	if($check->count > 0)
	{
		$crow = mysql_fetch_row($check->query);
		//update log date
		$upd = new update();
		$upd->up('user', 'log_date', 'id', "$crow[0]", "'$now'");
		
		setcookie('auser', $crow[0], time()+86400, '/');
		header('location: users/index.php');
	}
	else
	{
		$error = error(13);
	}
}
?>