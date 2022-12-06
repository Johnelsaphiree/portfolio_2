<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'alfimg';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$logo = isset($_FILES['logo']['tmp_name']) ? $_FILES['logo']['tmp_name'] : '';
$fav = isset($_FILES['fav']['tmp_name']) ? $_FILES['fav']['tmp_name'] : '';
$logo_type = isset($_FILES['logo']['type']) ? $_FILES['logo']['type'] : '';
$fav_type = isset($_FILES['fav']['type']) ? $_FILES['fav']['type'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';
	
if($save)
{
$val = new image_validate();
$up = new now_upload();
if($logo != '')
{
	$val->valid('Logo', $logo_type);
}
if($fav != '')
{
	$val->valid('Favicon', $fav_type);
}

if($val->error_code < 1)
	{
		if($logo != '')
{
		$up->up($logo, '', '../images/', 'logo', '', 'edit', 'logo');
		$success = success(1);
}
if($fav != '')
{
		$up->up($fav, '', '../assets/ico/', 'favicon', '', 'edit', 'favicon');
		$success = success(1);
}
if($success != '')
{
	header("location: lfimg.php?success=$success");
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

    <title>Logo/Favicon</title>
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
  <li class="active">LOGO/FAVICON</li>
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
    <h3 class="panel-title">LOGO/FAVICON</h3>
  </div>
  <div class="panel-body">
  <form action="lfimg.php" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <table cellpadding="10" class="table-bordered">
  <tr>
  <td><img src="../images/logo.jpg" class="img-responsive"></td>
  <td>
  <input name="logo" type="file">
  <em>Width: 150px, Height: 50px</em>
  </td>
  </tr>
  <tr>
  <td><img src="../assets/ico/favicon.jpg" class="img-responsive"></td>
  <td>
  <input name="fav" type="file">
  <em>Width: 32px, Height: 32px</em>
  </td>
  </tr>
  </table>
  <br />
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Save" name="save" id="save">
      <small class="text-warning">Refresh page if image does not change after SAVE</small>
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