<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'abank';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$acc_name = isset($_POST['acc_name']) ? $_POST['acc_name'] : '';
$acc_no = isset($_POST['acc_no']) ? $_POST['acc_no'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$logo = isset($_FILES['logo']['tmp_name']) ? $_FILES['logo']['tmp_name'] : '';
$logo_type = isset($_FILES['logo']['type']) ? $_FILES['logo']['type'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$delete = isset($_GET['delete']) ? $_GET['delete'] : '';
$xedit = isset($_GET['xedit']) ? $_GET['xedit'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
	
	if($xedit != '')
  {
	  $esel = new select();
	  $esel->pick('bank', 'acc_name, acc_no, logo, name', 'id', "$xedit", '', 'record', '', '', '=', '');
	  $esel_row = mysql_fetch_row($esel->query);
  }
	
if($save)
{
$val = new validate();
if($xedit == '')
{
$val->valid("$acc_name,$acc_no,$logo,$name");
}
else
{
	$val->valid("$acc_name,$acc_no,$name");
}
if($val->error_code < 1)
{
	//img
	$img_val = new image_validate();
	if($logo != '')
	{
	$img_val->valid('Logo', $logo_type);
	}
	if($img_val->error_code > 0)
	{
		$val->error_code = $img_val->error_code;
		$val->error_msg = error($val->error_code);
	}
	else
	{
		if($logo != '')
		{
		//upload img
		$img_up = new now_upload();
		if($xedit == '')
		{
		$img_up->up($logo, 'bank_images', '../images/bank/', 'bank', '', 'add', '');
		$img_arr = $img_up->img_name_array[0];
		}
		else
		{
			$img_up->up($logo, '', '../images/bank/', 'bank', '', 'edit', $esel_row[2]);
		}
		}
		
		if($xedit == '')
		{
		//insert
		$in = new insert();
		$in->input('bank', 'id, acc_name, acc_no, logo, name', "0, '$acc_name', '$acc_no', $img_arr, '$name'");
		}
		else
		{
			$in = new update();
			$in->up('bank', 'acc_name', 'id', "$xedit", "'$acc_name'");
			$in->up('bank', 'acc_no', 'id', "$xedit", "'$acc_no'");
			$in->up('bank', 'name', 'id', "$xedit", "'$name'");
		}
		
		$success = success(1);
		header("location: bank.php?success=$success");
		exit;
	}
}

}

if($delete != '')
{
	//get image
	$gimg = new select();
	$gimg->pick('bank', 'logo', 'id', "$delete", '', 'record', '', '', '=', '');
	$gimg_row = mysql_fetch_row($gimg->query);
	//wipe
	$wiper = new wipe();
	$wiper->file_wipe($gimg_row[0], '../images/bank/', '.jpg');
	
	$del = new delete();
	$del->gone('bank_images', 'img_no', "$gimg_row[0]");
	$del->gone('bank', 'id', "$delete");
	
	$success = success(1);
	header("location: bank.php?success=$success");
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

    <title>Bank</title>
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
  <li class="active">BANK</li>
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
    <h3 class="panel-title">NEW BANK</h3>
  </div>
  <div class="panel-body">
  <form action="bank.php?xedit=<?php echo $xedit;?>" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="name" class="control-label">Bank Name*:</label> 
      <input type="text" class="form-control" id="name" placeholder="Bank Name*" name="name" value="<?php
	  if($xedit != '')
	  {
		  echo $esel_row[3];
	  }
	  elseif($success == '')
	  {
		  echo stripslashes($name);
	  }
	  ?>">
  </div>
  
  <div class="form-group">
  <label for="acc_name" class="control-label">Account Name*:</label> 
      <input type="text" class="form-control" id="acc_name" placeholder="Account Name*" name="acc_name" value="<?php
	  if($xedit != '')
	  {
		  echo $esel_row[0];
	  }
	  elseif($success == '')
	  {
		  echo stripslashes($acc_name);
	  }
	  ?>">
  </div>
  
  <div class="form-group">
  <label for="acc_no" class="control-label">Account No*:</label> 
      <input type="text" class="form-control" id="acc_no" placeholder="Account No*" name="acc_no" value="<?php
	  if($xedit != '')
	  {
		  echo $esel_row[1];
	  }
	  elseif($success == '')
	  {
		  echo stripslashes($acc_no);
	  }
	  ?>">
  </div>
  
  <div class="form-group">
    <label for="logo">Bank Logo<?php
    if($xedit == '')
	{
		echo '*';
	}
	?></label>
    <input name="logo" type="file">
    <p class="help-block">50x50px</p>
  </div>
  
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
<?php
if($xedit == '')
{
	?>
<br />
<h5><strong>View Banks</strong></h5>
  <?php
  //bank info
  $gb = new select();
  $gb->pick('bank', 'id, acc_name, acc_no, logo, name', '', '', '', 'record', '', '', '', '');
  if($gb->count > 0)
  {
  ?>
  <div class="table-responsive">
  <table class="table table-bordered">
  <?php
  while($gb_row = mysql_fetch_row($gb->query))
  {
	  ?>
      <tr valign="top">
    <td><img src="../images/bank/<?php echo $gb_row[3];?>.jpg" width="50" height="50"></td>
    <td><strong>BANK NAME:</strong> <?php echo $gb_row[4];?><br />
    <strong>ACC NAME:</strong> <?php echo $gb_row[1];?><br />
    <strong>ACC NO:</strong> <?php echo $gb_row[2];?></td>
    <td><a href="bank.php?xedit=<?php echo $gb_row[0];?>">Edit</a> | <a href="bank.php?delete=<?php echo $gb_row[0];?>">Delete</a></td>  
      </tr>
      <?php
  }
  ?>
  </table>
  </div>
  <?php
  }
  else
  {
	  echo "<p>".$gb->error_msg."</p>";
  }
}//xedit
  ?>
  
  
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