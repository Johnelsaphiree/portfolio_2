<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'anetwork';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$ncode = isset($_POST['ncode']) ? $_POST['ncode'] : '';
$ucost = isset($_POST['ucost']) ? $_POST['ucost'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';
$bundle = isset($_POST['bundle']) ? $_POST['bundle'] : '';
$bsave = isset($_POST['bsave']) ? $_POST['bsave'] : '';

$xedit = isset($_GET['xedit']) ? $_GET['xedit'] : '';
$delete = isset($_GET['delete']) ? $_GET['delete'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

if($save)
{
	$val = new validate();
	$val->numeric($ncode, 'Network Code');
	$val->numeric($ucost, 'Unit Cost');
	$val->valid("$ncode,$ucost,$name");
	if($val->error_code < 1)
	{
		if($xedit == '')
		{
		$in = new insert();
		$in->input('network', 'id, ncode, ucost, name', "0, $ncode, $ucost, '$name'");
		}
		else
		{
			$in = new update();
			$in->up('network', 'ncode', 'id', "$xedit", "$ncode");
			$in->up('network', 'ucost', 'id', "$xedit", "$ucost");
			$in->up('network', 'name', 'id', "$xedit", "'$name'");
		}
		if($in->error_code < 1)
		{
		$success = success(1);
		header("location: network.php?success=$success");
		exit;
		}
		else
		{
			$val->error_code = 32;
			$val->error_msg = error($val->error_code);
		}
	}
}

if($bsave)
{
	$val = new validate();
	$val->valid($bundle);
	if($val->error_code < 1)
	{
		$exp = explode(',', $bundle);
		foreach($exp as $item)
		{
			$xexp = explode('-', $item);
$in = new insert();
		$in->input('network', 'id, ncode, ucost, name', "0, $xexp[1], $xexp[2], '$xexp[0]'");
		}
		$success = success(1);
		header("location: network.php?success=$success");
	}
}

if($delete != '')
{
	$del = new delete();
	$del->gone('network', 'id', "$delete");
	$success = success(1);
		header("location: network.php?success=$success");
		exit;
}

if($xedit != '')
{
	//get info
	$gnet = new select();
	$gnet->pick('network', 'ncode, ucost, name', 'id', "$xedit", '', 'record', '', '', '=', '');
	$gnet_row = mysql_fetch_row($gnet->query);
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

    <title>Network Charges</title>
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
  <li class="active">NETWORK CHARGES</li>
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
    <h3 class="panel-title">SET CHARGES</h3>
  </div>
  <div class="panel-body">
  
  <div class="panel panel-warning">
  <div class="panel-heading">Option A</div>
  <div class="panel-body">
  <form class="form-inline" role="form" name="form1" method="post" action="network.php?xedit=<?php echo $xedit;?>">
  <div class="form-group">
      <input type="text" class="form-control" id="name" placeholder="Network Name*" name="name" value="<?php
      if($xedit != '')
	  {
		  echo $gnet_row[2];
	  }
	  ?>">
  </div>
  
  <div class="form-group">
      <input type="text" class="form-control" id="ncode" placeholder="Network Code*" name="ncode" value="<?php
      if($xedit != '')
	  {
		  echo $gnet_row[0];
	  }
	  ?>">
  </div>
  
  <div class="form-group">
      <input type="text" class="form-control" id="ucost" placeholder="Unit Cost*" name="ucost" value="<?php
      if($xedit != '')
	  {
		  echo $gnet_row[1];
	  }
	  ?>">
  </div>
  
  <br />
  <br />
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <?php
	if($xedit == '')
	{
		$value = 'Save';
	}
	else
	{
		$value = 'Edit';
	}
	?>
      <input type="submit" class="btn btn-primary" value="<?php echo $value;?>" name="save" id="save">
    </div>
  </div>

  </form>
  </div><!--option A panel-->
  </div><!--option A panel-->
  <?php
  if($xedit == '')
  {
	  ?>
  <div class="panel panel-info">
  <div class="panel-heading">Option B</div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="network.php">
  <table cellpadding="20" width="100%">
  <tr>
  <td>
  <div class="form-group">
  <label for="bundle" class="control-label">Network, Code and Rate*:</label> 
      <textarea class="form-control" rows="4" id="bundle" name="bundle"></textarea>
  </div>
  <small class="text-danger">Enter networks info separated by comma(,) no space, and separating parameters using the minus sign(-). Enter paramenters like: Network-Code-Rate. Example: MTN-234803-2.00,Glo-234805-1.3,Airtel-234802-1.8,Etisalat-234809-2.5</small><br /><br />

  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Save" name="bsave" id="bsave">
  </div>
</td>
</tr>
</table>
  </form>
  </div><!--option B panel-->
  </div><!--option B panel-->
  <?php
  }
  ?>
  </div>
</div>
<?php
if($xedit == '')
{
	?>
  <br />
  <span class="lead">Networks and Charges</span>
  <?php
  //get networks and charges
  $gn = new select();
  $gn->pick('network', 'id, ncode, ucost, name', '', '', '', 'record', 'name', '', '', '');
  if($gn->count > 0)
  {
  ?>
  <div class="table-responsive">
  <table class="table">
  <tr>
  <th>NETWORK NAME</th>
  <th>NETWORK CODE</th>
  <th>CHARGE PER SMS</th>
  <th>ACTION</th>
  </tr>
  <?php
  while($gn_row = mysql_fetch_row($gn->query))
  {
  ?>
  <tr>
  <td><?php echo $gn_row[3];?></td>
  <td><?php echo $gn_row[1];?></td>
  <td><?php echo $gn_row[2];?></td>
  <td><a href="network.php?xedit=<?php echo $gn_row[0];?>">Edit</a> | <a href="network.php?delete=<?php echo $gn_row[0];?>">Delete</a></td>
  </tr>
  <?php
  }
  ?>
  </table>
  </div><!--table responsive-->
  <?php
  }
  else
  {
	  echo $gn->error_msg;
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