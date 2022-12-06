<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'ahome';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$credit = $_POST['credit'];
$add = $_POST['add'];
$subtract = $_POST['subtract'];

if($add)
{
	$aval = new validate();
	$aval->numeric($credit, 'Credit');
	$aval->valid($credit);
	if($aval->error_code < 1)
	{
		$aup = new update();
		$aup->up('admin', 'total', 'id', "1", "total + $credit");
		$aup->up('admin', 'balance', 'id', "1", "balance + $credit");
	}
}

if($subtract)
{
	$sval = new validate();
	$sval->numeric($credit, 'Credit');
	$sval->valid($credit);
	if($sval->error_code < 1)
	{
		$sup = new update();
		$sup->up('admin', 'total', 'id', "1", "total - $credit");
		$sup->up('admin', 'balance', 'id', "1", "balance - $credit");
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

    <title>Admin Home</title>
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
  <li class="active">ADMIN AREA</li>
</ol>
  <?php
  $bal = new select();
$bal->pick('admin', 'balance, reserved', 'id', "1", '', 'record', '', '', '=', '');
$bal_row = mysql_fetch_row($bal->query);

$prequest = new select();
$prequest->pick('smsrequest', '*', '', '', '', 'record', '', '', '', '');

//users total unit
$ut = new select();
$ut->pick('user', 'sum(balance)', '', '', '', 'record', '', '', '', '');
if($ut->count > 0)
{
	$ut_row = mysql_fetch_row($ut->query);
}
  ?>
  <table cellpadding="5">
  <tr>
  <td valign="bottom"><strong>Balance:</strong></td>
  <td><span class="label label-default"><?php echo $bal_row[0];?></span></td>
  <td><small>Units</small></td>
  </tr>
  <tr>
  <td valign="bottom"><strong>Reserved:</strong></td>
  <td><span class="label label-default"><?php echo $bal_row[1];?></span></td>
  <td><small>Units</small></td>
  </tr>
  <tr>
  <td valign="bottom"><strong>Total SMS units in Users accounts:</strong></td>
  <td><span class="label label-default"><?php echo $ut_row[0];?></span></td>
  <td><small>Units</small></td>
  </tr>
  <!--<tr>
  <td valign="bottom"><strong>Request:</strong></td>
  <td><span class="label label-default"><?php echo $prequest->count;?></span></td>
  <td><small class="text-primary">Pending</small></td>
  </tr>-->
  </table>
  <br />
  
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">ALTER CREDIT</h3>
  </div>
  <div class="panel-body">
  <?php
  if($aval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$aval->error_msg."</div>";
	}
	
	if($sval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sval->error_msg."</div>";
	}
	
	if($aup->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$aup->success_msg."</div>";
	}
	
	if($sup->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$sup->success_msg."</div>";
	}
  ?>
  <form class="form-inline" role="form" name="form1" method="post" action="index.php">
  <div class="form-group">
    <div class="col-lg-10">
      <input type="text" class="form-control" id="credit" placeholder="Credit" name="credit">
    </div>
  </div>
  <br />
  <br />
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Add" name="add" id="add">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-warning" value="Subtract" name="subtract" id="subtract">
    </div>
  </div>
  </form>
  
  </div>
</div>
  
  <h2>Dashboard</h2>
  <table cellpadding="10" align="center">
  <tr valign="top" align="center">
    <td><a href="send_sms.php"><img src="../images/dashboard/sms.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Send SMS</a></td>
   <td><a href="target.php"><img src="../images/dashboard/targeted.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Targeted SMS</a></td>
   <td><a href="rem.php"><img src="../images/dashboard/appointment.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Appointment Reminder</a></td>
   <td><a href="address.php"><img src="../images/dashboard/address.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Address Book</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="card.php"><img src="../images/dashboard/business_card.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Business Card</a></td>
   <td><a href="transfer.php"><img src="../images/dashboard/transfer.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Transfer</a></td>
   <td><a href="sent_msg.php"><img src="../images/dashboard/sent_msg.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Sent Messages</a></td>
   <td><a href="draft.php"><img src="../images/dashboard/draft.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Draft</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="rates.php"><img src="../images/dashboard/rate.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Rates</a></td>
   <td><a href="network.php"><img src="../images/dashboard/net_charge.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Network Charges</a></td>
   <td><a href="schedule.php"><img src="../images/dashboard/schedule.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Schedule Messages</a></td>
   <td><a href="treport.php"><img src="../images/dashboard/transaction.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Transaction Report</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="process.php"><img src="../images/dashboard/process.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Process Payment</a></td>
   <td><a href="users.php"><img src="../images/dashboard/user.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Users</a></td>
   <td><a href="newsletter.php"><img src="../images/dashboard/newsletter.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Newsletter</a></td>
   <td><a href="api.php"><img src="../images/dashboard/api.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />API</a></td>
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