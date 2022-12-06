<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

session_start();
$page = 'asms_credit';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../functions/currency.php');
include('../functions/gateway.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$quantity = $_POST['quantity'];
$continue = $_POST['continue'];

$xcredit = $_GET['xcredit'];
$confirm = $_GET['confirm'];
$option = $_GET['option'];
$reseller = $_GET['reseller'];

if($reseller == '')
{
if($ure == 'Y')
{
	header("location: sms_credit.php?option=bank&reseller=yes");
}
}

$buy = $_POST['buy'];

if($continue)
{
	$val = new validate();
	$val->numeric($quantity, 'Quantity');
	$val->valid($quantity);
	
	if($val->error_code < 1)
	{
	$mmsel = new select();
	$mmsel->pick('rate', 'min(lower), max(upper)', '', '', '', 'record', '', '', '', '');
	
	if($mmsel->count > 0)
	{
		$mmrow = mysql_fetch_row($mmsel->query);
		//high and low CAP
		if($quantity < $mmrow[0])
		{
			$val->error_code = 22;
			$val->error_msg = error($val->error_code)." below $mmrow[0] Units!";
		}
		elseif($quantity > $mmrow[1])
		{
			$val->error_code = 22;
			$val->error_msg = error($val->error_code)." above $mmrow[1] Units!";
		}
		else
		{
			//session variables
	$_SESSION['scredit'] = $quantity;
		header("location: sms_credit.php?xcredit=$quantity&option=online");
		}
	}
	}
}

$rsel = new select();
$rsel->pick('rate', 'lower, upper, cost, id', '', '', '', 'record', 'cost desc', '', '', '');

if($xcredit != '')
	  {
		  while($prow = mysql_fetch_row($rsel->query))
		  {
			  if($xcredit >= $prow[0] && $xcredit <= $prow[1])
			  {
				  if($rate == '')
				  {
				  $rate = $prow[2];
				  }
			  }
		  }
		  //Get cost
		  $xcost = (int)$xcredit * $rate;
	  }
	  
/*if($buy)
{
	//session variables
	$_SESSION['scredit'] = $xcredit;
	//convert to USD
	$tcur = get_currency('NGN', 'USD', $xcost);
//pay
$pay = gpay($tcur);
}*/

if($confirm != '')
{
	$fcredit = (int)$_SESSION['scredit'];
	
	if($fcredit != '')
	{
		$up = new update();
		//admin deduct
		$up->up('admin', 'balance', 'id', "1", "balance - $fcredit");
		//update user
		$up->up('user', 'balance', 'id', "$auser", "balance + $fcredit");
		
		//log
		$log = new insert();
		$log->input('transaction', 'id, type, credit, user, tuser, date', "0, 'EPAYMENT', $fcredit, 0, $auser, '$now'");
		$log->input('transaction', 'id, type, credit, user, tuser, date', "0, 'EPAYMENT', $fcredit, $auser, 0, '$now'");
		
		//enter smsrequest table
		//$in = new insert();
		//$in->input('smsrequest', 'id, user, quantity, date', "0, $auser, $fcredit, '$now'");
		//if($in->error_code < 1)
		//{
			//Send SMS and email message
			$xsend = new process();
			//get username
			$getuser = new select();
			$getuser->pick('user', 'username, phone, email, balance', 'id', "$auser", '', 'record', '', '', '=', '');
			$getrow = mysql_fetch_row($getuser->query);
			
			$message = "Epayment: USERNAME: $getrow[0], CREDIT: $fcredit Units";
			$xsend->sendsms('SMSRequest', $mynumber, $message);
			
		$umessage = "Your account has been credited with $fcredit Units. Balance: $getrow[3] Units";
			$xsend->sendsms($csite_name, $getrow[1], $umessage);
			//email
			$to = $cemail;
			$uto = $getrow[2];
//$subject = "Credit request($csite_name)";
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>An epayment was made on $csite_name.</p>
<p>DETAILS
<strong>Username:</strong> $getrow[0]<br />
<strong>Credit:</strong> $fcredit</p>";

$message .= "</body></html>";

$umessage = '<html><body>';
$umessage .= '<img src="$logo_link" alt="Logo" />';
$umessage.= "<p>Your account has been credited successfully with $fcredit Units.</p>
<p><strong>Credit balance: </strong>$getrow[3] Units</p>";

$umessage .= "</body></html>";

mail($to, $subject, $message, $headers);
mail($uto, $subject, $umessage, $headers);
//email end
			
			$_SESSION['scredit'] = '';
			
			header("location: sms_credit.php?xcredit=$fcredit&success=yes&option=online");
		//}
		//else
		//{
			//header("location: sms_credit.php");
		//}
	}
}

$success = $_GET['success'];

//get payment info
$pay = new select();
$pay->pick('payment', 'online, bank, rbank', 'id', "1", '', 'record', '', '', '=', '');
$pay_row = @mysql_fetch_row($pay->query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Buy SMS credit</title>
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
 <?php
 if($option == '')
 {
	 ?>
  <li class="active">BUY SMS CREDIT</li>
  <?php
 }
 else
 {
	 if($option == 'online')
	 {
	 ?>
     <li><a href="sms_credit.php">BUY SMS CREDIT</a></li>
     <li class="active">ONLINE PAYMENT</li>
     <?php
	 }
	 elseif($option == 'bank')
	 {
		 ?>
     <li><a href="sms_credit.php">BUY SMS CREDIT</a></li>
     <li class="active">BANK PAYMENT</li>    
         <?php
	 }
 }
 ?>
</ol>
<?php
if($option == '')
{
?>
<!--option-->
 <h4>Select Option:</h4>
<div class="list-group text-center">
  <a href="sms_credit.php?option=online" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="glyphicon glyphicon-credit-card text-primary"></span> ONLINE PAYMENT</h5>
  </a>
  <a href="sms_credit.php?option=bank" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="glyphicon glyphicon-tower text-primary"></span> BANK PAYMENT</h5>
  </a>
</div>
 <!--option end-->
 <?php
}
else
{
?>
<h4>Buy SMS Credit</h4>
  <?php
  if($rsel->count < 1 && $option != 'bank')
  {
	  echo "<div class='alert alert-warning'>Sorry you can't purchase SMS credit at the moment. Kindly check back later.</div>";
  }
  else
  {
	  if($xcredit == '')
	  {
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
  <?php
  if($ure != 'Y')
  {
	  ?>
  <h5><strong>RATES:</strong></h5>
  <?php
	if($rsel->count > 0)
	{
	?>
  <div class="table-responsive">
<table class="table table-striped table-condensed">
<tr>
<th>CREDIT RANGE</th>
<th>SALES RATE PER UNIT</th>
</tr>
<?php
	while($rrow = mysql_fetch_row($rsel->query))
	{
		if($xl == '')
		{
		$xl = $rrow[0];
		}
?>
<tr>
<td><?php echo $rrow[0];?> Units - <?php echo $rrow[1];?> Units</td>
<td>₦<?php echo $rrow[2];?></td>
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
	echo "<div class='alert alert-danger'>".error(4)."</div>";
}
  }//ure
	  }
	  if($option == 'online')
	  {
	  if($success == '')
	  {
	  ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">PURCHASE SMS CREDIT</h3>
  </div>
  <div class="panel-body">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	
	  }//success
	  
	  if($xcredit == '')
	  {
	  ?>
  <form class="form-inline" role="form" name="form1" method="post" action="sms_credit.php?xcredit=<?php echo $xcredit;?>&option=online">
  <?php
	  }
	  else
	  {
		  ?>
          <form class="form-inline" role="form" method='POST' action='https://voguepay.com/pay/'>
          <?php
	  }
	  
  if($xcredit == '')
  {
  ?>
  <div class="table-responsive">
  <table>
  <tr>
  <th>Credit Quantity(<span class="text-success"><?php echo $xl;?> units minimum</span>)</th>
  <th></th>
  </tr>
  <tr>
  <td>
  <div class="form-group"> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="quantity" placeholder="Credit Quantity" name="quantity" value="<?php
      if($val->error_code > 0)
	  {
		  echo $quantity;
	  }
	  ?>">
    </div>
  </div>
 </td>
 <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Continue" name="continue" id="continue">
    </div>
  </div>
  </td>
  </tr>
  </table>
  </div>
  <?php
  }
  else
  {
	  if($success != '')
	  {
	  ?>
      <p><span class="text-success"><strong>Transaction Successful!</strong><br />
      Details:</span></p>
      <?php
	  }
	  ?>
      <div class="table-responsive">
      <?php
	  if($success == '')
	  {
		  ?>
          <span class="text-primary"><strong>NOTE:</strong> After successfully making payment on the Voguepay website, remember to click the "Continue to merchant website" button to complete the payment process.</span><br /><br />
      <table class="alert-info img-rounded" cellpadding="10" width="100%">
      <?php
	  }
	  else
	  {
		  ?>
      <table class="alert-warning img-rounded" cellpadding="10" width="100%">     
          <?php
	  }
	  ?>
      <tr>
      <td valign="top"><strong>RATE:</strong></td>
      <td valign="top"><?php echo $rate;?></td>
      </tr>
       <tr>
      <td valign="top"><strong>CREDIT QUANTITY:</strong></td>
      <td valign="top"><?php echo $xcredit;?> Units</td>
      </tr>
       <tr>
      <td valign="top"><strong>COST:</strong></td>
      <td valign="top">₦<?php echo $xcost;?></td>
      </tr>
      <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">
      <?php
	  if($success == '')
	  {
		  //get username
			$fgetuser = new select();
			$fgetuser->pick('user', 'username', 'id', "$auser", '', 'record', '', '', '=', '');
			$fgetrow = mysql_fetch_row($fgetuser->query);
		  ?>
 <input type="hidden" name="v_merchant_id" value="<?php echo $pay_row[0];?>" />
 <input type="hidden" name="memo" value="Order from <?php echo $fgetrow[0];?>" />
 <input type="hidden" name="success_url" value="<?php echo $csite_url;?>/users/sms_credit.php?confirm=yes&option=online" />
 <input type="hidden" name="item_1" value="SMS Unit" />
 <input type="hidden" name="price_1" value="<?php echo $xcost;?>" />
 <input type="hidden" name="description_1" value="SMS credit unit purchase" /> 

      <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-success" value="Buy" name="buy" id="buy">
    </div>
  </div>
  <?php
	  }
	  if($success != '')
	  {
		  $btc = 'Continue';
	  }
	  else
	  {
		  $btc = 'Cancel';
	  }
	  ?>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
     <a href="sms_credit.php?option=online" class="btn btn-warning"><span class="glyphicon glyphicon-flash"></span> <?php echo $btc;?></a>
    </div>
  </div>
      </td>
      </tr>
      </table>
      </div>
      
      <?php
	  if($success != '')
	  {
		  ?>
      <br />
       <p><span class="text-success"><strong>Your account will be credited.</strong></span></p>
      <?php
	  }
  }
  ?>
  </form>
  
  </div>
</div>
<?php
	  }
	  elseif($option == 'bank')
	  {
		  ?>
          <p class="lead">To perform a bank payment/transfer transaction, kindly make payment to the following accounts:</p>
          <?php
		  //get accounts
		  $account = new select();
		  $account->pick('bank', 'name, acc_name, acc_no', '', '', '', 'record', '', '', '', '');
		  if($account->count > 0)
		  {
			  while($account_row = mysql_fetch_row($account->query))
			  {
		  ?>
          <p><strong class="text-primary">Bank:</strong> <?php echo $account_row[0];?><br />
          <strong class="text-primary">Account Name:</strong> <?php echo $account_row[1];?><br />
          <strong class="text-primary">Account No:</strong> <?php echo $account_row[2];?><br />
          </p>
         <?php
			  }
		  }
		  ?>
          <p><span class="lead">Instructions:</span><br />
          <?php
		  if($ure != 'Y')
		  {
		  echo nl2br($pay_row[1]);
		  }
		  else
		  {
			echo nl2br($pay_row[2]);  
		  }
		  ?>
          <p>&nbsp;</p>
          <?php
	  }//bank
	  
  }
}//option
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