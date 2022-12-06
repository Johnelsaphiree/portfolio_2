<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'asocial';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$facebook = isset($_POST['facebook']) ? $_POST['facebook'] : '';
$twitter = isset($_POST['twitter']) ? $_POST['twitter'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

//get info
	$gnet = new select();
	$gnet->pick('social', 'facebook, twitter', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);
	
if($save)
{
$val = new validate();
$val->valid("$facebook,$twitter");
if($val->error_code < 1)
{
	if($gnet->count > 0)
	{
		$up = new update();
		$up->up('social', 'facebook', 'id', "1", "'$facebook'");
		$up->up('social', 'twitter', 'id', "1", "'$twitter'");
		
	}
	else
	{
		$up = new insert();
		$up->input('social', 'id, facebook, twitter', "0, '$facebook', '$twitter'");
	}
	$success = success(1);
	header("location: social.php?success=$success");
	exit;
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

    <title>Social</title>
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
  <li><a href="index.php">DASHBOARD</a></li>
  <li class="active">SOCIALS</li>
</ol>
   <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
  ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SOCIALS</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="social.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <table cellpadding="10">
  <tr>
  <td><img src="../images/facebook.png" width="32" height="32"></td>
  <td><div class="form-group">
  <label for="facebook" class="control-label">Facebook Link*:</label> 
      <input type="text" class="form-control" id="facebook" placeholder="Facebook Link*" name="facebook" value="<?php
	  if($gnet->count > 0)
	  {
		  echo $gnet_row[0];
	  }
	  else
	  {
		  echo 'https://';
	  }
	  ?>">
  </div></td>
  </tr>
  <tr>
  <td><img src="../images/twitter.png" width="32" height="32"></td>
  <td><div class="form-group">
  <label for="twitter" class="control-label">Twitter Link*:</label> 
      <input type="text" class="form-control" id="twitter" placeholder="Twitter Link*" name="twitter" value="<?php
	  if($gnet->count > 0)
	  {
		  echo $gnet_row[1];
		  }
	  else
	  {
		  echo 'https://';
	  }
	  ?>">
  </div></td>
  </tr>
  </table>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Save" name="save" id="save">
    </div>
  </div>
</td>
</tr>
</table>
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