<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aitarget';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('../objects/upload_download.php');
include('up.php');

$state = isset($_POST['state']) ? $_POST['state'] : '';
$import = isset($_POST['import']) ? $_POST['import'] : '';
$item = isset($_FILES['item']['tmp_name']) ? $_FILES['item']['tmp_name'] : '';
$item_type = isset($_FILES['item']['type']) ? $_FILES['item']['type'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

if($import)
{
	$val = new validate();
	$val->valid($item);
	if($val->error_code < 1)
	{
		$fval = new file_validate();
		$fval->valid($item_type, 0, 1, 'text_num');
		if($fval->error_code > 0)
		{
			$val->error_code = $fval->error_code;
			$val->error_msg = $fval->error_msg;
		}
		else
		{
			$file = file_get_contents($item);
$exp = explode("\r\n", $file);
foreach($exp as $xitem)
{
	$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($xitem, 'single');
		$xitem = $nval->vnumber;
	//insert
	$in = new insert();
	$in->input('target', 'id, num, state', "0, '$xitem', '$state'");
}
$success = success(18);
	
	header("location: itarget.php?success=$success");
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

    <title>Import Numbers</title>
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
  <li class="active">IMPORT NUMBERS</li>
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
    <h3 class="panel-title">Import Numbers</h3>
  </div>
  <div class="panel-body">
  <form action="itarget.php" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="state" class="control-label">Select State*</label>
      <select class="form-control" name="state" id="state">
            <option value="Abia">Abia</option>
            <option value="Abuja">Abuja</option>
            <option value="Adamawa">Adamawa</option>
            <option value="Akwa Ibom">Akwa Ibom</option>
            <option value="Anambra">Anambra</option>
            <option value="Bauchi">Bauchi</option>
            <option value="Bayelsa">Bayelsa</option>
            <option value="Benue">Benue</option>
            <option value="Borno">Borno</option>
            <option value="Cross River">Cross River</option>
            <option value="Delta">Delta</option>
            <option value="Ebonyi">Ebonyi</option>
            <option value="Edo">Edo</option>
            <option value="Ekiti">Ekiti</option>
            <option value="Enugu">Enugu</option>
            <option value="Gombe">Gombe</option>
            <option value="Imo">Imo</option>
            <option value="Jigawa">Jigawa</option>
            <option value="Kaduna">Kaduna</option>
            <option value="Kano">Kano</option>
            <option value="Kastina">Kastina</option>
            <option value="Kebbi">Kebbi</option>
            <option value="Kogi">Kogi</option>
            <option value="Kwara">Kwara</option>
            <option value="Lagos">Lagos</option>
            <option value="Nassarawa">Nassarawa</option>
            <option value="Niger">Niger</option>
            <option value="Ogun">Ogun</option>
            <option value="Ondo">Ondo</option>
            <option value="Osun">Osun</option>
            <option value="Oyo">Oyo</option>
            <option value="Plateau">Plateau</option>
            <option value="Rivers">Rivers</option>
            <option value="Sokoto">Sokoto</option>
            <option value="Taraba">Taraba</option>
            <option value="Yobe">Yobe</option>
            <option value="Zamfara">Zamfara</option>
</select>
  </div>
  
  <div class="form-group">
  <label for="sender" class="control-label">Import File*:</label> 
      <input name="item" type="file">
      <p class="help-block">Numbers arranged line by line in a notepad file(.txt)</p>
  </div>
  
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Import" name="import" id="import">
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