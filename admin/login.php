<?php
$page = 'login';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');

$username = $_POST['username'];
$password = $_POST['password'];
$login = $_POST['login'];

if($login)
{
$username = $_POST['username'];
$password = $_POST['password'];
$xpassword = md5($password);
$login = $_POST['login'];

if($login)
{
	$check = new select();
	$check->pick('admin', '*', 'username,password', "'$username','$xpassword'", '', 'record', '', '', '=,=', 'and');
	if($check->count > 0)
	{
		setcookie('admin', $username, time()+86400, '/');
		header('location: index.php');
	}
	else
	{
		$error = error(13);
	}
}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

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
    <div class="row">
  <div class="col-md-9">
  <?php
  if($page != 'login')
  {
	  ?>
  <ol class="breadcrumb">
  <li class="active">ADMIN AREA</li>
</ol>
<?php
  }
  ?>
  
<form class="form-signin" role="form" name="form1" method="post" action="login.php">
        <h2 class="form-signin-heading">Admin Login</h2>
        <?php
  if($error != '')
	{
	echo "<div class='alert alert-danger'>".$error."</div>";
	}
  ?>
        <input type="text" class="form-control" placeholder="Username" autofocus id="username" name="username">
        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Login" name="login" id="login">
      </form>
  
  </div>
  </div>
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>

  </body>
</html>
<?php
mysql_close($connect);
?>