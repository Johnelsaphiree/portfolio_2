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
		$ccheck->vcredit($auser, $credit);
		$ccheck->ckuser($username);
		if($ccheck->error_code > 0)
		{
			$val->error_code = $ccheck->error_code;
			$val->error_msg = $ccheck->error_msg;
		}
		else
		{
			//deduct
			$deduct = new process();
			$deduct->pay($auser, $credit, 'transfer');
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
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'transfer', $credit, $auser, '$tuser', '$now'");
			$tin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'receive', $credit, $tuser, '$auser', '$now'");
			//send email
			//get username
			$gauser = new select();
			$gauser->pick('user', 'username', 'id', "$auser", '', 'record', '', '', '=', '');
			$garow = mysql_fetch_row($gauser->query);
			
			$ubal = $ccheck->ubalance + $credit;
			
			$to = $ccheck->uemail;
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>You have been credited with $credit SMS units by $garow[0]. Your credit balance is: $ubal Units</p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);

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
  <li><a href="index.php">USER AREA</a></li>
  <li class="active">TRANSFER</li>
</ol>

<h4>Transfer Credit</h4>
  <?php
  $bal = new select();
$bal->pick('user', 'balance, reserved', 'id', "$auser", '', 'record', '', '', '=', '');
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
  <form class="form-inline" role="form" name="form1" method="post" action="transfer.php">
  <div class="form-group">
  <label for="credit" class="col-lg-4 control-label">Credit quantity*</label> 
    <div class="col-lg-12">
      <input type="text" class="form-control" id="credit" placeholder="Credit" name="credit" value="<?php
      if($up->success_code < 1)
	  {
		  echo $credit;
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
  <label for="username" class="col-lg-4 control-label">Recipient's Username*</label> 
    <div class="col-lg-12">
      <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php
      if($up->success_code < 1)
	  {
		  echo stripslashes($username);
	  }
	  ?>">
    </div>
  </div>
  <br />
  <br />
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Transfer" name="transfer" id="transfer">
    </div>
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