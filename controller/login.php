<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'login';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$xpassword = md5($password);
$login = isset($_POST['login']) ? $_POST['login'] : '';

if($login)
{
	$val = new validate();
	$val->valid("$username,$password");
	if($val->error_code > 0)
	{
		$error = error(5);
	}
	else
	{
			$sel = new select();
			$sel->pick('controller', '*', 'username,password', "'$username','$xpassword'", '', 'log', '', '', '=,=', 'and');
			if($sel->count > 0)
			{
				setcookie('controller', 'controller', time()+86400, '/');
				header("location: index.php");
			}
			else
			{
				$error = $sel->error_msg;
			}
	}//val
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../images/favicon.jpg">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
   <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="../css/signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
<form action="login.php" method="post" enctype="multipart/form-data" name="login_form" class="form-signin">
        <h2 class="form-signin-heading">Login</h2>
        <?php
	if(@$error != '')
	{
		?>
        <div class="alert alert-danger"><?php echo $error;?></div>
        <?php
	}
	?>
        <input name="username" type="text" class="form-control" placeholder="ID" autofocus>
        <input name="password" type="password" class="form-control" placeholder="Password" autofocus>
              
   <input name="login" type="submit" value="Login" class="btn btn-lg btn-primary btn-block">
      </form>

    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect)
?>