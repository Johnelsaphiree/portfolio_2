<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'atransfer';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$sup = $_GET['sup'];

$credit = $_POST['credit'];
$username = $_POST['username'];
$transfer = $_POST['transfer'];
$notify = $_POST['notify'];

if($transfer)
{
	$val = new validate();
	$val->numeric($credit, 'Credit');
	$val->valid("$credit,$username");
	if($val->error_code < 1)
	{
		//check credit and validate username
		$credit = (int)$credit;
		$ccheck = new check();
		$ccheck->vcredit(0, $credit);
		$ccheck->ckuser($username);
		if($ccheck->error_code > 0)
		{
			$val->error_code = $ccheck->error_code;
			$val->error_msg = $ccheck->error_msg;
		}
		else
		{
			if($notify == 'sms')
			{
			//deduct
			$deduct = new process();
			$credit = $credit - 1;//plus report message to be sent
			}
			//$deduct->pay(0, $xcredit, 'transfer');
			//update user
			$up = new update();
			$up->up('user', 'balance', 'username', "'$username'", "balance + $credit");
			
			if($up->success_code > 0)
			{
				$up->success_code = 9;
				$up->success_msg = success($up->success_code)." $username";
			}
			//log
			$tuser = $ccheck->uid;
			
			$tin = new insert();
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'transfer', $credit, 0, '$tuser', '$now'");
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'receive', $credit, $tuser, '0', '$now'");
			if($notify == 'sms')
			{
			//send message
			$sms_msg = "You have been credited with $credit SMS units by ADMIN.";
			$deduct->sendsms($csenderid, $ccheck->uphone, $sms_msg);
			}
			elseif($notify == 'email')
			{
			//send email
			$ubal = $ccheck->ubalance + $credit;
			
			$to = $ccheck->uemail;
//$subject = 'Credit Transfer';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>You have been credited with $credit SMS units by ADMIN. Your credit balance is: $ubal Units</p>
";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);
			}

header("Location: " . $_SERVER["REQUEST_URI"]."?sup=".$up->success_msg);
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

    <title>Transfer</title>
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
  <li class="active">TRANSFER</li>
</ol>

<h4>Transfer Credit</h4>
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
  
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">TRANSFER CREDIT</h3>
  </div>
  <div class="panel-body">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($sup != '')
	{
	echo "<div class='alert alert-success'>".$sup."</div>";
	}
	
	if($up->error_code > 0)
	{
	echo "<div class='alert alert-success'>".$up->error_msg."</div>";
	}
  ?>
  <form role="form" name="form1" method="post" action="transfer.php">
  <div class="form-group">
  <label for="credit" class="control-label">Credit quantity*</label> 
      <input type="text" class="form-control" id="credit" placeholder="Credit" name="credit" value="<?php
      if($up->success_code < 1)
	  {
		  echo $credit;
	  }
	  ?>">
  </div>
  
  <div class="form-group">
    <label for="username" class="control-label" >Select User*:</label>
  <select class="form-control" name="username" size="5" id="username">
  <?php
  $cgsel = new select();
  $cgsel->pick('user', 'username', '', "", '', 'record', 'username', '', '', '');
  
  if($cgsel->count > 0)
  {
	  while($cgrow = mysql_fetch_row($cgsel->query))
	  {
		  
if($val->error_code > 0)
  {
	  if($username == $cgrow[0])
	  {
	   ?>
  <option value="<?php echo $username;?>" selected><?php echo $username;?></option>
  <?php
	  }
	  else
	  {
	 ?>
  <option value="<?php echo $cgrow[0];?>"><?php echo $cgrow[0];?></option>
  <?php	  
	  }
  }
  else
  {
  ?>
  <option value="<?php echo $cgrow[0];?>"><?php echo $cgrow[0];?></option>
  <?php
  }
	  }
  }
 ?>
</select>
  </div>
  
  <!--<div class="form-group">
  <label for="username" class="control-label">Recipient's Username*</label> 
      <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php
      if($up->success_code < 1)
	  {
		  echo stripslashes($username);
	  }
	  ?>">
  </div>-->
  
  <span class="lead">Notification</span><br />
<div class="radio">
  <label>
    <input type="radio" name="notify" id="notify1" value="sms" checked>
    SMS
  </label>
</div>
<div class="radio">
  <label>
    <input type="radio" name="notify" id="notify2" value="email">
    Email
  </label>
</div>

  <br />
  <br />
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Transfer" name="transfer" id="transfer">
  </div>
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