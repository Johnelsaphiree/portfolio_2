<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aavoucher';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$generate = $_POST['generate'];
$quantity = $_POST['quantity'];
$naira = $_POST['naira'];
$unit = $_POST['unit'];

$success = $_GET['success'];
$finish = $_GET['finish'];

if($generate)
{
	$val = new validate();
	$val->numeric($naira, 'Naira value');
	$val->numeric($unit, 'Unit');
	$val->valid("$quantity,$naira,$unit");
	if($val->error_code < 1)
	{
	if($quantity != 'Select Quantity')
	{
		for($x = 0; $x < $quantity; $x++)
	{
		$code_box = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9);
	   $code_shuff = shuffle($code_box);
	   $code = $code_box[0].$code_box[5].$code_box[11].$code_box[16].$code_box[22].$code_box[29].$code_box[37].$code_box[43].$code_box[51].$code_box[53].$code_box[55].$code_box[57].$code_box[59];
	   $check = new select();
	   $check->pick('voucher', '*', 'pin', "'$code'", '', 'record', '', '', '=', '');
	   if($check->count > 0)
	   {
		   while($check->count > 0)
		   {
			   $check->count = 0;
			   
			   $code_shuff = shuffle($code_box);
	    $code = $code_box[0].$code_box[5].$code_box[11].$code_box[17].$code_box[23].$code_box[30].$code_box[38].$code_box[45].$code_box[53].$code_box[55].$code_box[57].$code_box[59].$code_box[61];
	   $check = new select();
	   $check->pick('voucher', '*', 'pin', "'$code'", '', 'record', '', '', '=', '');
		   }
	   }
	   $pin_in = new insert();
	   $pin_in->input('voucher', 'pin, stat, date, id, value, unit', "'$code', 'new', '$now', 0, $naira, $unit");
	}
	header("location: avoucher.php?success=Successful!");
	}
	else
	{
		$error = "Select Quantity!";
	}
	}//val
	else
	{
		$error = $val->error_msg;
	}
}

if($finish != '')
{
	$up = mysql_query("update voucher set stat = 'active'");
	header("location: avoucher.php?success=Vouchers activated!");
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

    <title>Generate Voucher</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
  <li class="active">GENERATE VOUCHER</li>
</ol>
<h4>Generate Voucher</h4>
<table width="100%">
<tr>
<td>
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	$new_ticket = new select();
	$new_ticket->pick('voucher', 'pin, value', 'stat', "'new'", '', 'record', '', '', '=', '');
	if($new_ticket->count > 0)
	{
  ?>
  <div class="panel panel-info">
    <div class="panel-heading">New Vouchers</div>
    <div class="panel-body">
    <form id="form1" name="form1" method="post" action="avoucher.php">
    <table class="mystyle" cellpadding="5" width="100%">
   <?php
	$no = 0;
	while($ticket_row = mysql_fetch_row($new_ticket->query))
	{
		if($no == 0)
		{
		?>
        <tr>
        <?php
		}
		?>
        <td>
<img src="../images/logo.jpg" width="20" height="20"> <?php echo $csite_name;?><br />
        <?php
		echo "<strong>Pin: </strong>".$ticket_row[0]."<br /><small class='text-danger'>Value = N".number_format($ticket_row[1])."</small>";
		$no = $no + 1;
		?>
        <br />
        <br />
        </td>
        <?php
		if($no == 5)
		{
		?>
        </tr>
        <?php
		$no = 0;
		}
	}
    ?>
    <tr>
    <td><a href="print.php" class="btn btn-default">Print</a><br /><br /><a href="avoucher.php?finish=yes" class="btn btn-success">Finish</a></td>
    </tr>
      </table>
      </form>
      </div><!--panel-->
      </div><!--panel-->
  <br />
  <?php
	}
	?>
<table>
    <tr>
    <td>
    <form id="form1" name="form1" method="post" action="avoucher.php" role="form">
    <table class="mystyle" cellpadding="10" width="100%">
   <tr>
    <td>
    <div class="ddl_style">
    <select name="quantity" id="quantity" class="form-control">
      <option value="Select Quantity">Select Quantity</option>
      <option value="10">10</option>
      <option value="20">20</option>
      <option value="30">30</option>
      <option value="40">40</option>
      <option value="50">50</option>
    </select></div></td>
    <td>*</td>
     </tr>
     <tr>
     <td>
      <input name="unit" type="text" class="form-control input-sm" id="unit" value="" placeholder="Unit Value">
     </td>
     <td>*</td>
     </tr>
     <tr>
     <td>
     <input name="naira" type="text" class="form-control" id="naira" placeholder="Naira value"/>
     </td>
     <td>*</td>
     </tr>
      <tr>
     <td><input type="submit" name="generate" id="generate" value="Generate" class="btn btn-warning"/></td>
      </tr>
      </table>
      </form>
      </td>
    </tr>
    </table>
    
    </td>
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

<script>
$(document).ready(function(){
    var $remaining = $('#remaining'),
        $messages = $remaining.next();

    $('#message').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 3,
		forceParse: 0,
        showMeridian: 1
    });
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>