<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aapi';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$slink = isset($_POST['slink']) ? $_POST['slink'] : '';
$blink = isset($_POST['blink']) ? $_POST['blink'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$sres = isset($_POST['sres']) ? $_POST['sres'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$xedit = isset($_GET['xedit']) ? $_GET['xedit'] : '';
$delete = isset($_GET['delete']) ? $_GET['delete'] : '';
$xdefault = isset($_GET['xdefault']) ? $_GET['xdefault'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

if($save)
{
	$val = new validate();
	$val->valid("$slink,$blink,$name,$sres");
	if($val->error_code < 1)
	{
		if($xedit == '')
		{
		$in = new insert();
		$in->input('api', 'id, slink, blink, name, sresponse', "0, '$slink', '$blink', '$name', '$sres'");
		}
		else
		{
			$in = new update();
			$in->up('api', 'slink', 'id', "$xedit", "'$slink'");
			$in->up('api', 'blink', 'id', "$xedit", "'$blink'");
			$in->up('api', 'name', 'id', "$xedit", "'$name'");
			$in->up('api', 'sresponse', 'id', "$xedit", "'$sres'");
		}
		//echo mysql_error();
		if($in->error_code < 1)
		{
		$success = success(1);
		header("location: api.php?success=$success");
		exit;
		}
	}
}

if($delete != '')
{
	$del = new delete();
	$del->gone('api', 'id', "$delete");
	$success = success(1);
		header("location: api.php?success=$success");
		exit;
}

if($xedit != '')
{
	//get info
	$gnet = new select();
	$gnet->pick('api', 'slink, blink, name, sresponse', 'id', "$xedit", '', 'record', '', '', '=', '');
	$gnet_row = mysql_fetch_row($gnet->query);
}

if($xdefault != '')
{
	//change all
	$up = new update();
	$xup = mysql_query("update api set xdefault = '0'");
	$up->up('api', 'xdefault', 'id', "$xdefault", '1');
	$success = 'Default changed '.success(1);
		header("location: api.php?success=$success");
		exit;
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

    <title>API</title>
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
  <li class="active">API</li>
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
    <h3 class="panel-title">NEW API</h3>
  </div>
  <div class="panel-body">
  <p><strong>Send link configuration:</strong><br />
  <small>Set values for the following parameters:<br />
  - MESSAGE = @@msg@@<br />
  - SENDER ID/SOURCE = @@sender@@<br />
  - RECIPIENTS/DESTINATION = @@recipient@@<br /><br />
  EXAMPLE:<br />
  http://120.231.192.115/bulksms/bulksms?username=conga-mine&password=bidemi&type=0&dlr=1&destination=@@recipient@@&source=@@sender@@&message=@@msg@@
  </small>
  </p>
  <form class="form-horizontal" role="form" name="form1" method="post" action="api.php?xedit=<?php echo $xedit;?>">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="name" class="control-label">API Name*:</label> 
      <input type="text" class="form-control" id="name" placeholder="API Name*" name="name" value="<?php
      if($xedit != '')
	  {
		  echo $gnet_row[2];
	  }
	  ?>">
  </div>
  
  <div class="form-group">
   <label for="slink" class="control-label">Send Link*:</label> 
      <textarea class="form-control" rows="3" id="slink" name="slink" placeholder="Send Link*"><?php
      if($xedit != '')
	  {
		  echo $gnet_row[0];
	  }
	  else
	  {
		  echo 'http://';
	  }
	  ?></textarea>
  </div>
  
  <div class="form-group">
  <label for="blink" class="control-label">Balance Link*:</label> 
      <textarea class="form-control" rows="3" id="blink" name="blink" placeholder="Balance Link*"><?php
      if($xedit != '')
	  {
		  echo $gnet_row[1];
	  }
	   else
	  {
		  echo 'http://';
	  }
	  ?></textarea>
  </div>
  
   <div class="form-group">
  <label for="sres" class="control-label">Response words on success*:</label> 
      <input type="text" class="form-control" id="sres" placeholder="Response words on success*" name="sres" value="<?php
      if($xedit != '')
	  {
		  echo $gnet_row[3];
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
  <span class="lead">View API(s)</span>
  <?php
  $gn = new select();
  $gn->pick('api', 'id, name, xdefault, blink', '', '', '', 'record', 'name', '', '', '');
  if($gn->count > 0)
  {
  ?>
  <div class="table-responsive">
  <table class="table">
  <tr>
  <th>API NAME</th>
  <th>BALANCE</th>
  <th>DEFAULT</th>
  <th>ACTION</th>
  </tr>
  <?php
  while($gn_row = mysql_fetch_row($gn->query))
  {
  ?>
  <tr>
  <td><?php echo $gn_row[1];?></td>
  <td><a href="<?php echo $gn_row[3];?>">Check Balance</a></td>
  <?php
  if($gn_row[2] == '0')
  {
	  ?>
  <td><a href="api.php?xdefault=<?php echo $gn_row[0];?>">Make Default</a></td>
  <?php
  }
  elseif($gn_row[2] == '1')
  {
	  ?>
    <td><strong class="text-primary">Default</strong></td>  
      <?php
  }
  ?>
  <td><a href="api.php?xedit=<?php echo $gn_row[0];?>">Edit</a> | <a href="api.php?delete=<?php echo $gn_row[0];?>">Delete</a></td>
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