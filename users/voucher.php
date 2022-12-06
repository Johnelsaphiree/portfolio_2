<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'avoucher';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$code = isset($_POST['code']) ? $_POST['code'] : '';
$ok = isset($_POST['ok']) ? $_POST['ok'] : '';
$success = $_GET['success'];
if($ok)
{
	$val = new validate();
	//check code
	$check = new select();
	$check->pick('voucher', 'id, pin, unit', 'pin', "'$code'", '', 'record', '', '', '=', '');
	if($check->count < 1)
	{
		$val->error_code = 50;
		$val->error_msg = error($val->error_code);
	}
	else
	{
		$check_row = mysql_fetch_row($check->query);
			//credit and debit admin
			$sms = new process();
			$sms->pay(0, $check_row[2], 'transfer');
			$up = new update();
			$up->up('user', 'balance', 'id', "$auser", "balance + $check_row[2]");
			//log transaction and usage
			$in = new insert();
			$in->input('transaction', 'id, type, credit, user, tuser, date', "0, 'voucher_transfer($code)', $check_row[2], 0, '$auser', '$now'");
			$in->input('transaction', 'id, type, credit, user, tuser, date', "0, 'voucher_receive($code)', $check_row[2], $auser, '0', '$now'");
			$del = new delete();
			$del->gone('voucher', 'id', "$check_row[0]");
			$send = new process();
			$msg = "Voucher load successful. Your account has been credited with $check_row[2] Units.";
			$send->sendsms($csenderid, $xuphone, $msg);
			$success = "Your account has been credited with $check_row[2] Units";
			header("location: voucher.php?success=$success");
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

    <title>Load Voucher</title>
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
  <li><a href="index.php">USER AREA</a></li>
  <li class="active">LOAD VOUCHER</li>
</ol>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Load Voucher</h3>
  </div>
  <div class="panel-body">
  <table cellpadding="20" width="80%">
  <tr>
  <td>
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
  <form class="form-horizontal" role="form" name="form1" method="post" action="voucher.php">
  <div class="form-group">
  <label for="code" class="control-label">Voucher Pin*</label> 
      <input type="code" class="form-control" id="code" placeholder="Voucher Pin" name="code" value="">
  </div>
  
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="OK" name="ok" id="ok">
  </div>
  </form>
  </td>
  </tr>
  </table>
  
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