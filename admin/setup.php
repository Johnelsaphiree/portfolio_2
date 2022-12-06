<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'asetup';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$tell = isset($_POST['tell']) ? $_POST['tell'] : '';
$sname = isset($_POST['sname']) ? $_POST['sname'] : '';
$surl = isset($_POST['surl']) ? $_POST['surl'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

if($save)
{
	$val = new validate();
	$val->numeric($tell, 'Phone');
	
	$val->email($email);
	$val->valid("$sid,$tell,$sname,$surl,$email,$description");
	if($val->error_code < 1)
	{
		$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tell, 'single');
		$tell = $nval->vnumber;
		
			$in = new update();
			$in->up('info', 'sid', 'id', "1", "'$sid'");
			$in->up('info', 'tell', 'id', "1", "$tell");
			$in->up('info', 'sname', 'id', "1", "'$sname'");
			$in->up('info', 'surl', 'id', "1", "'$surl'");
			$in->up('info', 'email', 'id', "1", "'$email'");
			$in->up('info', 'description', 'id', "1", "'$description'");
			
		$success = success(1);
		header("location: setup.php?success=$success");
		exit;
	}
}

	//get info
	$gnet = new select();
	$gnet->pick('info', 'sid, tell, sname, surl, email, description', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Setup</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.css" rel="stylesheet">

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
  <li class="active">SETUP</li>
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
    <h3 class="panel-title">SETUP</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="setup.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="sname" class="control-label">Site Name*:</label> 
      <input type="text" class="form-control" id="sname" placeholder="Site Name*" name="sname" value="<?php
		  echo $gnet_row[2];
	  ?>">
  </div>
  
  <div class="form-group">
  <label for="surl" class="control-label">Site Url*:</label> 
      <input type="text" class="form-control" id="surl" placeholder="Site Url*" name="surl" value="<?php
		  echo $gnet_row[3];
	  ?>">
  <small>E.G: http//www.<em>your-domail-name</em>.com</small>
  </div>
  
  <div class="form-group">
    <label for="description" class="control-label">Site Description*</label> 
     <textarea class="form-control" rows="3" id="description" name="description"><?php
	 echo $gnet_row[5];
	 ?></textarea>
    </div>
  
  <div class="form-group">
  <label for="tell" class="control-label">Phone*:</label> 
      <input type="text" class="form-control" id="tell" placeholder="Phone*" name="tell" value="<?php
		  echo $gnet_row[1];
	  ?>">
  </div>
  
  <div class="form-group">
  <label for="email" class="control-label">Email*:</label> 
      <input type="text" class="form-control" id="email" placeholder="Email*" name="email" value="<?php
		  echo $gnet_row[4];
	  ?>">
  </div>
  
  <div class="form-group">
  <label for="sid" class="control-label">Sender ID*:</label> 
      <input type="text" class="form-control" id="sid" placeholder="Sender ID*" name="sid" value="<?php
		  echo $gnet_row[0];
	  ?>">
  </div>
  
  <br />
  
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