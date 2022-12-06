<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'arefer';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$success = isset($_GET['success']) ? $_GET['success'] : '';

$send = isset($_POST['send']) ? $_POST['send'] : '';
$tell = isset($_POST['tell']) ? $_POST['tell'] : '';

if($send)
{
	$val = new validate();
	$val->valid($tell);
	if($val->error_code < 1)
	{
		$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tell, 'single');
		$tell = $nval->vnumber;
		//check last
		$check = new select();
		$check->pick('referral', 'unix_timestamp(date)', 'user', "$auser", "0, 1", 'record', 'id desc', '', '=', '');
		$check_row = @mysql_fetch_row($check->query);
		$ctime = date('dmy', $check_row[0]);
		$ttime = date('dmy', time());
		//echo $ctime.' ,'.$ttime.' ,'.$check_row[0];
		if($ttime == $ctime)
		{
			$val->error_code = 44;
			$val->error_msg = error($val->error_code);
		}
		else
		{
			//get cost
		$net = mysql_query("select ncode, ucost, name from network");
		if(mysql_num_rows($net) > 0)
		{
			while($net_row = mysql_fetch_row($net))
			{
				if(substr_count($tell, "$net_row[0]") > 0)
				{
					$gate = 'yes';
					$cal_cost = $net_row[1];
					//get ref info
					$ref = new select();
					$ref->pick('ref_setup', 'credit, message, sender', 'id', "1", '', 'record', '', '', '=', '');
					$ref_row = mysql_fetch_row($ref->query);
					
			$xsend = new process();
			$xsend->pay(0, $cal_cost, 'sendsms');
$xsend->sendsms($ref_row[2], $tell, $ref_row[1]);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$ref_row[2]', '$tell', '$ref_row[1]', $cal_cost, 0, '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $cal_cost, 0, 1, '$now'");
					
					$xsend->pay(0, $ref_row[0], 'transfer');
			//update user
			$up = new update();
			$up->up('user', 'balance', 'id', "$auser", "balance + $ref_row[0]");
			//log
			$tin = new insert();
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'transfer', $ref_row[0], 0, '$auser', '$now'");
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'receive', $ref_row[0], $auser, '0', '$now'");
			//enter referral
			$xin = new insert();
			$xin->input('referral', 'id, user, date, tell', "0, $auser, '$now', '$tell'");
			$success = "Thank you for referring. Your account has been credited with $ref_row[0] units";
			header("location: refer.php?success=$success");
					
				}
		break;
				}
			}
			if($gate != 'yes')
			{
				$val->error_code = 45;
			$val->error_msg = error($val->error_code);
			//echo 'a';
			}
		}
		else
		{
			//echo 'b';
			$val->error_code = 45;
			$val->error_msg = error($val->error_code);
		}
		//
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

    <title>Refer and Earn</title>
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
  <li class="active">REFER AND EARN</li>
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
    <h3 class="panel-title">REFERRAL SETUP</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="refer.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="tell" class="control-label">Phone*:</label> 
      <input type="text" class="form-control" id="tell" placeholder="Phone*" name="tell" value="">
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Send" name="send" id="send">
    </div>
  </div>
</td>
</tr>
</table>
  </form>
  </div>
  </div><br />
  <p class="lead">Help Text</p>
  <?php
  //get help text
  $gt = new select();
  $gt->pick('ref_setup', 'text', 'id', "1", '', 'record', '', '', '=', '');
  if($gt->count > 0)
  {
	  $gt_row = mysql_fetch_row($gt->query);
	  echo $gt_row[0];
  }
  else
  {
	  echo $gt->error_msg;
  }
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