<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'arem';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$sncount = $_GET['sncount'];
$smcount = $_GET['smcount'];
$scost = $_GET['scost'];
$sschedule = $_GET['sschedule'];
$xht = $_SESSION['sht'];

$sid = $_POST['sid'];
$tnum = $_POST['tnum'];
$message = $_POST['message'];
$date_time = $_POST['date_time'];
$send = $_POST['send'];

$schedule = new insert();
$din = new insert();
$dup = new update();
$xsend = new process();
$ncount = '';

if($send)
{
	$xarr = array();
	$arr['network'] = array();
	$arr['code'] = array();
	$arr['tcost'] = array();
	$arr['count'] = array();
	$tcost = 0;
	$tcount = 0;
	//check network charge
	$sval = new validate();
	$sval->valid("$sid,$message,$tnum,$date_time");
	if($sval->error_code < 1)
	{
		if(trim($tnum) == '')
		{
			$sval->error_code = 1;
			$sval->error_msg = error($sval->error_code);
		}
		else
		{
			if($tnum != '')
			{
				$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tnum, 'group');
		$otell = $nval->vnumber;
		$ncount = $nval->cnumber;
			}
			//calculate billing
			if($error == '')
			{
	if($ncount > 0)
	{
		/*if($ncount <= 100000)//xxx
		{*/
			
			$xsend->billing($message, $otell, 0, 'sendnow', $tcost, $arr, $tcount);
			$arr = $xsend->net_arr;
				$mcount = $xsend->msg_count;
				
			$cost = $xsend->cost;
			$tcount = $xsend->net_tcount;
			$cdiff = $ncount - $tcount;
			
			$ht = "<table class='table table-bordered'>
					<tr>
					<th>NETWORK</th>
					<th>COUNT</th>
					<th>COST</th>
					</tr>";
					if(sizeof($arr['network'] > 0))
					{
						foreach($arr['network'] as $k => $kval)
						{
							$ht .= "<tr>
							<td>$kval</td>
							<td>".$arr['count'][$k]."</td>
							<td>".$arr['tcost'][$k]."</td>
							</tr>";
						}
					}
					$ht .= "<tr>
					<td>OTHERS</td>
							<td>$cdiff</td>
							<td>Not sent</td>
					</tr></table>";
			//echo $cost;
			//print_r($arr);
			$_SESSION['sht'] = $ht;
			
			if($xsend->success_code > 0)
			{
					$stat = 'wait';
				
				$schedule->input('schedule', 'id, senderid, destination, message, entrydate, senddate, user, status, credit, no_count', "0, '$sid', '$otell', '$message', '$now', '$date_time', 0, '$stat', $cost, $ncount");
				//update reserve
				$uupdate = new update();
				$uupdate->up('admin', 'reserved', 'id', "1", "reserved + $cost");
				
				if($schedule->success_code > 0)
				{	
					$schedule->success_code = 20;
					$schedule->success_msg = success($schedule->success_code)." <strong>$date_time</strong> to $ncount recipients.";
					
					$xsend->success_code = 0;
					$xsend->error_msg = '';
					
					header("Location: " . $_SERVER["REQUEST_URI"]."&sschedule=".$schedule->success_msg);
    exit;
				}
			
			}//xsend success
		/*}//max recipients
		else
		{
			$sval->error_code = 17;
				$sval->error_msg = error($sval->error_code);
		}*/
			}//ncount
			else
			{
				$sval->error_code = 15;
				$sval->error_msg = error($sval->error_code);
			}
			}//error condition
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

    <title>Appointment Reminder</title>
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
  <li class="active">APPOINTMENT REMINDER</li>
</ol>
 <h4>Appointment Reminder</h4>
  <?php
  if($sval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sval->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($schedule->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$schedule->error_msg."</div>";
	}
	if($sschedule != '')
	{
	echo "<div class='alert alert-success'>".$sschedule."<p>".$xht."</p> <a href='send_sms.php'>Send Another Message</a></div>";
	}
	if($xsend->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$xsend->error_msg."</div>";
	}
	
	if($sschedule == '')
	{
	?>
 <form action="rem.php?resend=<?php echo $resend;?>&dmsg=<?php echo $dmsg;?>" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Sender ID<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  <div class="form-group">
    <label for="sid" class="col-lg-2 control-label">Sender ID</label>
    <div class="col-md-8">
      <input name="sid" type="text" class="form-control input-sm" id="sid" value="<?php
      if($schedule->success_code < 1)
	  {
		  echo stripslashes($sid);
	  }
	  ?>" maxlength="11" placeholder="Sender ID(11 alpha numeric Characters)">
    </div>
  </div>
  
  </div>
  </div>
  
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Recipients<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  
  <div class="panel panel-default">
  <div class="panel-body">
  <div class="form-group">
    <label for="tnum" class="col-lg-10 control-label"><p align="left">Enter/Paste numbers separated by comma(,) OR Enter/Paste numbers line by line <a href="../images/number_format.png">View Example</a></small></p></label>
    <div class="col-lg-10">
     <textarea class="form-control input-sm" rows="5" id="tnum" name="tnum"><?php
      if($schedule->success_code < 1)
	  {
		  echo stripslashes($tnum);
	  }
	  ?></textarea>
    </div>
  </div>
  </div><!--panel1 body-->
  </div><!--panel1-->
 
  </div>
  </div>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Message<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  <div class="form-group">
    <label for="message" class="col-lg-2 control-label">Enter/Paste Message</label> 
    <div class="col-lg-10">
     <textarea class="form-control" rows="5" id="message" name="message"><?php
      if($schedule->success_code < 1)
	  {
		  echo stripslashes($message);
	  }
	  ?></textarea>
     <br />
    <span id="remaining">160 characters remaining</span>
    <span id="messages">1 message(s)</span>
    </div>
  </div>
  
   </div>
  </div>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Date/Time<span class="text-primary"></span></h3>
  </div>
  
  <div class="panel-body">
            <?php
			$dtp_date = date('Y-m-d', time()).'T'.date('H:i:s', time()).'Z';
			?>
            <div class="form-group">
            <p align="center"><strong>DATE/TIME:</strong></p>
              <label for="dtp_input1" class="col-md-4 control-label">Select Date/Time</label>
                <div class="input-group date form_datetime col-md-5" data-date="<?php echo $dtp_date;?>" data-date-format="yyyy-mm-dd h:i:s" data-link-field="dtp_input1">
                    <input class="form-control" type="text" value="<?php
      if($schedule->success_code < 1)
	  {
		  echo stripslashes($date_time);
	  }
	  ?>" readonly name="date_time" id="date_time">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
            <br />
            
          </div>
            </div>
  <table cellpadding="5">
  <tr>
  <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="send" class="btn btn-success" value="Send">
    </div>
  </div>
  </td>
  </tr>
  </table>
    </form>
  <?php
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
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>