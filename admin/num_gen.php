<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'anum_gen';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];

$set = isset($_POST['set']) ? $_POST['set'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : '';

if($set)
{
	$val = new validate();
	$val->numeric($limit, 'Limit');
	$val->valid($limit);
	if($val->error_code < 1)
	{
		$up = new update();
		$up->up('info', 'num_gen', 'id', "1", "$limit");
		$success = success(1);
		header("location: num_gen.php?success=$success");
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

    <title>Number Generator</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
  <li class="active">NUMBER GENERATOR</li>
</ol>
<h4>Number Generator</h4>
<table width="70%">
<tr>
<td>
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	?>
 <form action="num_gen.php" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
 
  <div class="form-group">
    <label for="limit" class="control-label">Set Number Limit*:</label>
      <input name="limit" type="text" class="form-control input-sm" id="limit" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($limit);
	  }
	  ?>" placeholder="Number Limit">
  </div>
           
  <div class="form-group">
      <input type="submit" name="set" class="btn btn-success" value="Set">
  </div>
    </form>
    </td>
    </tr>
    </table>

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