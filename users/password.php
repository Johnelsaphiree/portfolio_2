<?php
$page = 'apassword';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$sup = $_GET['sup'];

$old = $_POST['old'];
$new = $_POST['new'];
$cnew = $_POST['cnew'];
$xnew = md5($new);
$xold = md5($old);
$change = $_POST['change'];

if($change)
{
$p_val = new validate();
$p_val->match($new, $cnew);
$p_val->valid("$old,$new,$cnew");

if($p_val->error_code < 1)
{
	$obj = new select();
	$obj->pick('user', '*', 'id,password', "$auser,'$xold'", '', 'log', '', '', '=,=', 'and');
	if($obj->error_code > 0)
	{
		$obj->error_msg = "Wrong password!";
	}
	
	if($obj->count > 0)
	{
	$up = new update();
	$up->up('user', 'password', 'id,password', "$auser,'$xold'", "'$xnew'");
	
	header("Location: " . $_SERVER["REQUEST_URI"]."?sup=".$up->success_msg);
    exit;	
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

    <title>Password</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  
    <div class="container">
    <?php
	include('../body/head.php');
	?>
    <div class="row">
  <?php
  include('../body/sidex.php');
  ?>
  <div class="col-md-9">
  <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li class="active">PASSWORD</li>
</ol>
  <h4>Password</h4>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">CHANGE PASSWORD</h3>
  </div>
  <div class="panel-body">
  <?php
  if($p_val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$p_val->error_msg."</div>";
	}
	if($sup != '')
	{
	echo "<div class='alert alert-success'>".$sup."</div>";
	}
	
	if($obj->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$obj->error_msg."</div>";
	}
  ?>
  <form class="form-horizontal" role="form" name="form1" method="post" action="password.php">
  <div class="form-group">
  <label for="old" class="col-lg-2 control-label">Old Password*</label> 
    <div class="col-lg-10">
      <input type="password" class="form-control" id="old" placeholder="Old Password" name="old" value="<?php
      if($up->success_code < 1)
	  {
		  echo $old;
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
  <label for="new" class="col-lg-2 control-label">New Password*</label> 
    <div class="col-lg-10">
      <input type="password" class="form-control" id="new" placeholder="New Password" name="new" value="<?php
      if($up->success_code < 1)
	  {
		  echo $new;
	  }
	  ?>">
    </div>
    </div>
    
    <div class="form-group">
  <label for="cnew" class="col-lg-2 control-label">Confirm Password*</label> 
    <div class="col-lg-10">
      <input type="password" class="form-control" id="cnew" placeholder="Confirm Password" name="cnew" value="<?php
      if($up->success_code < 1)
	  {
		  echo $cnew;
	  }
	  ?>">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Change" name="change" id="change">
    </div>
  </div>
  </form>
  
  </div>
</div>

  </div>
  </div>
    </div><!-- /.container -->
    <?php
	include('../body/foot.php');
	?>

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