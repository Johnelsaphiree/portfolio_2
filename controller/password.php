<?php
$page = 'apassword';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('up.php');

$old = $_POST['old'];
$new = $_POST['new'];
$cnew = $_POST['cnew'];
$xnew = md5($new);
$xold = md5($old);
$ok = $_POST['ok'];

if($ok)
{
$p_val = new validate();
$p_val->match($new, $cnew);
$p_val->valid("$old,$new,$cnew");

if($p_val->error_code < 1)
{
	$obj = new select();
	$obj->pick('controller', '*', 'username,password', "'controller','$xold'", '', 'log', '', '', '=,=', 'and');
	if($obj->error_code > 0)
	{
		$obj->error_msg = "Wrong password!";
	}
	
	if($obj->count > 0)
	{
	$up = new update();
	$up->up('controller', 'password', 'username,password', "'controller','$xold'", "'$xnew'");	
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
    <link rel="shortcut icon" href="../images/favicon.jpg">

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
    <div class="row">
   <?php
   include('side.php')
   ?>
   <div class="col-md-9">
<p><strong class="lead">Password</strong></p>
     <div class="panel panel-primary">
     <div class="panel-heading">Change Password</div>
     <div class="panel-body">
    <?php
	if($p_val->error_code > 0 || $obj->error_code > 0 || $up->success_code > 0)
   {
	   if($up->success_code > 0)
	   {
	   ?>
       <div class="alert alert-success">
       <?php
	   }
	   else
	   {
		   ?>
       <div class="alert alert-danger">
	   <?php
	   }
   echo $p_val->error_msg;
 echo $obj->error_msg;
 echo $up->success_msg;
	   ?>
       </div>
       <?php
   }
	?>
    <form id="form1" name="form1" method="post" action="password.php" role="form">
    <div class="form-group">
    <label for="old">Old Password:</label>
    <input name="old" type="password" class="form-control" id="old" value="<?php
  if($up->success_code < 1 && $obj->error_code != 5)
  {
	 echo $old; 
  }
  ?>"/>
  </div>
  
  <div class="form-group">
  <label for="new">New Password:</label>
  <input name="new" type="password" class="form-control" id="new" value="<?php
  if($up->success_code < 1 && $p_val->error_code != 9)
  {
	 echo $new; 
  }
  ?>"/>
  </div>
  
  <div class="form-group">
    <label for="new">Confirm Password:</label>
     <input name="cnew" type="password" class="form-control" id="cnew" value="<?php
  if($up->success_code < 1 && $p_val->error_code != 9)
  {
	 echo $cnew; 
  }
  ?>"/>
  </div>
  
  <div class="form-group">
     <input type="submit" name="ok" id="ok" value="Change" class="btn btn-primary"/>
     </div>
     
      </form>
</div><!--panel-->
</div><!--panel-->

 </div><!--col-->
   
   </div><!--row-->
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect)
?>