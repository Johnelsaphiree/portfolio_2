<?php
$page = 'aprocess';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$credit = $_GET['credit'];
$success = $_GET['success'];

if($credit != '')
{
	$val = new validate();
	//get info
	$gi = new select();
	$gi->pick('smsrequest', 'user, quantity', 'id', "$credit", '', 'record', '', '', '=', '');
	$gi_row = mysql_fetch_row($gi->query);
	
	//verify admin credit
	$vc = new check();
	$vc->vcredit(0, $gi_row[1] + 1);
	if($vc->error_code > 0)
	{
		$val->error_code = $vc->error_code;
		$val->error_msg = $vc->error_msg;
	}
	else
	{
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

		$up = new update();
		//admin deduct
		$up->up('admin', 'balance', 'id', "1", "balance - $gi_row[1]");
		//update user
		$up->up('user', 'balance', 'id', "$gi_row[0]", "balance + $gi_row[1]");
		
		//clear from smsrequest table
		$clear = new delete();
		$clear->gone('smsrequest', 'id', "$credit");
		
		//log
		$log = new insert();
		$log->input('transaction', 'id, type, credit, user, tuser, date', "0, 'EPAYMENT', $gi_row[1], 0, $gi_row[0], '$now'");
		$log->input('transaction', 'id, type, credit, user, tuser, date', "0, 'EPAYMENT', $gi_row[1], $gi_row[0], 0, '$now'");
		
		//get user info
		$guser = new select();
		$guser->pick('user', 'phone, email, balance', 'id', "$gi_row[0]", '', 'record', '', '', '=', '');
		$gurow = mysql_fetch_row($guser->query);
		
		//send SMS
		$message = "Your account has been credited with $gi_row[1] Units. Balance: $gurow[2] Units";
		
		$xsend = new process();
			$xsend->sendsms($csite_name, $gurow[0], $message);
			
			//send Email
			$to = $gurow[1];
//$subject = "Epayment($csite_name)";
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your account has been credited successfully with $gi_row[1] Units.</p>
<p><strong>Credit balance: </strong>$gurow[2] Units</p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);
//email end
header("location: process.php?success=yes");
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

    <title>Process Payments</title>
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
  <li class="active">PROCESS PAYMENTS</li>
</ol>
<h4>Process Payments</h4>
  <?php
  $bal = new select();
$bal->pick('admin', 'balance, reserved', 'id', "1", '', 'record', '', '', '=', '');
$bal_row = mysql_fetch_row($bal->query);
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
  </table>
  <br />
  
  <h5><strong>REQUESTS:</strong></h5>
  <?php
  if($val->error_code > 0)
  {
  echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
  }
  if($success != '')
  {
  echo "<div class='alert alert-success'>Successful!</div>";
  }
  
  $sel = new select();
  $sel->pick('smsrequest', 'id, user, quantity, unix_timestamp(date)', '', '', '', 'record', 'id', '', '', '');
  if($sel->count > 0)
  {
  ?>
  <div class="table-responsive">
<table class="table table-striped">
<tr>
<th>DATE</th>
<th>USERNAME</th>
<th>CREDIT</th>
<th>ACTION</th>
</tr>
<?php
	while($row = mysql_fetch_row($sel->query))
	{
		//get username
		$getuser = new select();
		$getuser->pick('user', 'username', 'id', "$row[1]", '', 'record', '', '', '=', '');
		$getrow = mysql_fetch_row($getuser->query);
?>
<tr>
<td><?php echo date('jS M Y | h:i:s a', $row[3]);?></td>
<td><?php echo $getrow[0];?></td>
<td><?php echo $row[2];?> Units</td>
<td><span class="glyphicon glyphicon-ok"></span> <a href="process.php?credit=<?php echo $row[0];?>">Credit</a></td>
</tr>
<?php
	}
?>
</table>
</div><!--table-responsive-->
<?php
  }
  else
  {
	  echo "<div class='alert alert-danger'>".$sel->error_msg."</div>";
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