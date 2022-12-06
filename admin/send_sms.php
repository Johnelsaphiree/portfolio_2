<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'asend_sms';

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
$sdraft = $_GET['sdraft'];
$xht = $_SESSION['sht'];

$sid = $_POST['sid'];
$tnum = $_POST['tnum'];
$cnum = $_POST['cnum'];
$path = $_FILES['path']['tmp_name'];
$path_size = $_FILES['path']['size'];
$path_type = $_FILES['path']['type'];
$max = 2000000;
$message = $_POST['message'];
$date_time = $_POST['date_time'];
$report = $_POST['report'];
$send = $_POST['send'];
$draft = $_POST['draft'];

$resend = $_GET['resend'];
$dmsg = $_GET['dmsg'];

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
	$sval->valid("$sid,$message");
	//keywords
	$ksel = new select();
	$ksel->pick('keyword', 'keyword', 'id', "1", '', 'record', '', '', '=', '');
	if($ksel->count > 0)
	{
		$krow = mysql_fetch_row($ksel->query);
		$kexp = explode(',', $krow[0]);
		foreach ($kexp as $karr) {
    if (strpos($message, $karr) !== FALSE) {
        $sval->error_code = 33;
		$sval->error_msg = '"'.$karr.'"'.error($sval->error_code); 
        break;
    }
}
	}
	
	if($sval->error_code < 1)
	{
		if(trim($tnum) == '' && sizeof($cnum) < 1 && $path == '')
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
			elseif($cnum != '')
			{
				$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($cnum, 'group');
		$otell = $nval->vnumber;
		$ncount = $nval->cnumber;
			}
			elseif($path != '')
			{
	//Upload Madness
	$error = '';
	$gnum = '';
	
	$fval = new file_validate();
	$fval->valid($path_type, $path_size, $max, 'text');
	if($fval->error_code < 1)
	{
		$filex = file_get_contents($path);
$nval = new number_val();
$nval->length($filex, 'group');
$otell = $nval->vnumber;
$ncount = $nval->cnumber;
	}
	else
	{
		$error = $fval->error_msg;
	}
			}
			//calculate billing
			if($error == '')
			{
	if($ncount > 0)
	{
		/*if($ncount <= 100000)//xxx
		{*/
	if($report)
	{
		//get cost of one
		$rcost = new select();
		$rcost->pick('network', 'ncode, ucost, name', '', '', '', 'record', '', '', '', '');
		if($rcost->count > 0)
		{
			while($rcost_row = mysql_fetch_row($rcost->query))
			{
				if(stristr($mynumber, "$rcost_row[0]"))
				{
					if(array_search("$rcost_row[2]", $arr['network'])  > -1)
					{
						$key = array_search("$rcost_row[2]", $arr['network']);
						$arr['tcost'][$key] = $arr['tcost'][$key] + $rcost_row[1];
						$arr['count'][$key] = $arr['count'][$key] + 1;
						$tcost = $tcost + $rcost_row[1];
						$tcount = $tcount + 1;
					}
					else
					{
						$arr['network'][] = $rcost_row[2];
						$arr['code'][] = $rcost_row[0];
						$arr['tcost'][] = $rcost_row[1];
						$arr['count'][] = 1;
						$tcost = $tcost + $rcost_row[1];
						$tcount = $tcount + 1;
					}
					//echo $arr['network'][0].', '.$arr['code'][0]."<br />";
					break;
				}
			}
			//echo print_r($arr)."<br />".$tcost;
		}
		
		$ncount = $ncount + 1;
	}
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
				
			if($date_time != '')
			{
				if($report)
				{
					$stat = 'report';
				}
				else
				{
					$stat = 'wait';
				}
				
				$schedule->input('schedule', 'id, senderid, destination, message, entrydate, senddate, user, status, credit, no_count', "0, '$sid', '$otell', '$message', '$now', '$date_time', 0, '$stat', $cost, $ncount");
				//update reserve
				$uupdate = new update();
				$uupdate->up('admin', 'reserved', 'id', "1", "reserved + $cost");
				
				if($schedule->success_code > 0)
				{	
					$schedule->success_code = 7;
					$schedule->success_msg = success($schedule->success_code)." <strong>$date_time</strong> to $ncount recipients.";
					
					$xsend->success_code = 0;
					$xsend->error_msg = '';
					
					//check draft
					if($dmsg != '')
					{
						$dadel = new delete();
						$dadel->gone('draft', 'id', "$dmsg");
					}
					header("Location: " . $_SERVER["REQUEST_URI"]."&sschedule=".$schedule->success_msg);
    exit;
				}
			}
			else
			{
				//If not scheduled
				//Pay
				$xsend->pay(0, $cost, 'sendsms');
				$xsend->sendsms($sid, $otell, $message, $nval->xsession);
				//echo $xsend->error_msg;
				if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$sid', '$otell', '$message', $ncount, 0, '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $cost, 0, '$ncount', '$now'");
					
					if($report)
					{
						$rsender = $csenderid;
						$rmsg = "Your message has been sent successfully to $ncount recipients.";
						$rtell = $mynumber;
						$xsend->sendsms($rsender, $rtell, $rmsg);
					}
					
					//Send Email
					$to = $cemail;
//$subject = 'SMS Report';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your message was sent successfully to $ncount recipients</p>

<p><strong>Message Count:</strong> $mcount<br /></p>
<p><strong>Cost:</strong> $cost<br /></p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);

//check draft
					if($dmsg != '')
					{
						$dadel = new delete();
						$dadel->gone('draft', 'id', "$dmsg");
					}
					//echo $nval->error_msg;
					header("Location: " . $_SERVER["REQUEST_URI"]."&success=".$xsend->success_msg."&sncount=$ncount&smcount=$mcount&scost=$cost");
    exit;
				}
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

//DRAFT
if($draft)
{
	$sval = new validate();
	$sval->valid("$sid,$message");
	if($sval->error_code < 1)
	{
		if(trim($tnum) == '' && sizeof($cnum) < 1 && $path == '')
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
			elseif($cnum != '')
			{
				$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($cnum, 'array');
		$otell = $nval->vnumber;
		$ncount = $nval->cnumber;
			}
			elseif($path != '')
			{
	//Upload Madness
	$error = '';
	$gnum = '';
	
	$fval = new file_validate();
	$fval->valid($path_type, $path_size, $max, 'text');
	if($fval->error_code < 1)
	{
		$filex = file_get_contents($path);
$nval = new number_val();
$nval->length($filex, 'group');
$otell = $nval->vnumber;
$ncount = $nval->cnumber;
	}
	else
	{
		$error = $fval->error_msg;
	}
			}
			if($error == '')
			{
				if($ncount > 0)
				{
					/*if($ncount <= 100000)
					{*/
						if($dmsg != '')
						{
				$dup->up('draft', 'senderid', 'id', "$dmsg", "'$sid'");
				$dup->up('draft', 'destination', 'id', "$dmsg", "'$otell'");
				$dup->up('draft', 'message', 'id', "$dmsg", "'$message'");
						}
						else
						{
				$din->input('draft', 'id, senderid, destination, message, user', "0, '$sid', '$otell', '$message', 0");
						}
						
				if($din->success_code > 0 || $dup->success_code > 0)
				{
					$din->success_code = 8;
					$din->success_msg = success($din->success_code)." <a href='send_sms.php'>Send Another Message</a>";
					
					header("Location: " . $_SERVER["REQUEST_URI"]."&sdraft=".$din->success_msg);
    exit;
				}
				//}//max recipients
		else
		{
			$sval->error_code = 17;
				$sval->error_msg = error($sval->error_code);
		}
				}//ncount
			else
			{
				$sval->error_code = 15;
				$sval->error_msg = error($sval->error_code);
			}
			}
		}//recipient valid
	}//main valid
}

if($resend != '')
{
	$rsel = new select();
	$rsel->pick('smslog', 'senderid, destination, message', 'id', "$resend", '', 'record', '', '', '=', '');
	$rerow = mysql_fetch_row($rsel->query);
}

if($dmsg != '')
{
	$dsel = new select();
	$dsel->pick('draft', 'senderid, destination, message', 'id', "$dmsg", '', 'record', '', '', '=', '');
	$darow = mysql_fetch_row($dsel->query);
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

    <title>Send SMS</title>
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
  <li class="active">SEND SMS</li>
</ol>
 <h4>Send SMS</h4>
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
 <!--<em class="text-warning"><strong>Note:</strong> You can send messages to 100,000 recepients at a time</em>-->
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
	if($success != '')
	{
	echo "<div class='alert alert-success'><p>".$success." to $sncount recipients</p>
	<p><strong>Message Count:</strong> $smcount</p><p>
	<strong>Cost:</strong> $scost Units
	</p>
	<p>
	$xht
	</p>
	<p>
	<a href='send_sms.php'>Send Another Message</a>
	</p></div>";
	}
	if($sdraft != '')
	{
	echo "<div class='alert alert-success'>".stripslashes($sdraft)."</div>";
	}
	
	if($success == '' && $sdraft == '' && $sschedule == '')
	{
	?>
 <form action="send_sms.php?resend=<?php echo $resend;?>&dmsg=<?php echo $dmsg;?>" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Sender ID<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  <div class="form-group">
    <label for="sid" class="col-lg-2 control-label">Sender ID</label>
    <div class="col-md-8">
      <input name="sid" type="text" class="form-control input-sm" id="sid" value="<?php
	  if($resend != '')
	  {
		  echo $rerow[0];
	  }
	  elseif($dmsg != '')
	  {
		  echo $darow[0];
	  }
	  else
	  {
      if($xsend->success_code < 1 && $schedule->success_code < 1)
	  {
		  echo stripslashes($sid);
	  }
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
	 if($resend != '')
	  {
		  echo $rerow[1];
	  }
	   elseif($dmsg != '')
	  {
		  echo $darow[1];
	  }
	  else
	  {
      if($xsend->success_code < 1 && $schedule->success_code < 1)
	  {
		  echo stripslashes($tnum);
	  }
	  }
	  ?></textarea>
    </div>
  </div>
  </div><!--panel1 body-->
  </div><!--panel1-->
  <h3><span class="label label-default">OR</span></h3>
  
  
  <div class="row">
  <div class="col-md-5">
  
  <div class="panel panel-default">
  <div class="panel-body">
  <div class="form-group">
    <label for="cnum" class="col-lg-8 control-label" ><p align="left">Select single or group numbers from address book</p></label>
    <div class="col-lg-10">
  <select class="form-control" name="cnum" size="5" id="cnum">
  <?php
  $cgsel = new select();
  $cgsel->pick('contact', 'name, phone', 'type,user', "'group',0", '', 'record', 'name', '', '=,=', 'and');
  
  if($cgsel->count > 0)
  {
  ?>
  <optgroup label="Group">
  <?php
	  while($cgrow = mysql_fetch_row($cgsel->query))
	  {
  ?>
  <option value="<?php echo $cgrow[1];?>"><?php echo $cgrow[0];?></option>
  <?php
	  }
  ?>
  </optgroup>
  <?php
  }
 
  $cssel = new select();
  $cssel->pick('contact', 'name, phone', 'type,user', "'single',0", '', 'record', 'name', '', '=,=', 'and');
  
  if($cssel->count > 0)
  {
  ?>
  <optgroup label="Single">
   <?php
	  while($csrow = mysql_fetch_row($cssel->query))
	  {
  ?>
  <option value="<?php echo $csrow[1];?>"><?php echo $csrow[0];?></option>
  <?php
	  }
  ?>
  </optgroup>
   <?php
  }
  ?>
</select>
  </div>
  </div>
  </div><!--panel2 body-->
  </div><!--panel2-->
  
  </div>
  
  <div class="col-md-2">
   <h3 align="center"><span class="label label-default">OR</span></h3>
  </div>
  
  <div class="col-md-5">
  
  <div class="panel panel-default">
  <div class="panel-body">
  <div class="form-group">
  <div class="col-lg-offset-2 col-lg-10"> 
    <label for="path">Upload numbers from Text file</label>
    <p align="left">Numbers should be separated by comma(,) OR Numbers should be arrainged line by line <a href="../images/number_format.png">View Example</a></small></p>  
    <input type="file" name="path" id="path">
    <p><em class="help-block"><strong>NOTE: </strong>Max upload size is 2MB</em></p>
    </div>
  </div>
  </div><!--panel3 body-->
  </div><!--panel3-->
  </div>
</div>
  <br />
 
  </div>
  </div>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SMS Message<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  <div class="form-group">
    <label for="message" class="col-lg-2 control-label">Enter/Paste Message</label> 
    <div class="col-lg-10">
     <textarea class="form-control" rows="5" id="message" name="message"><?php
	 if($resend != '')
	  {
		  echo $rerow[2];
	  }
	   elseif($dmsg != '')
	  {
		  echo $darow[2];
	  }
	  else
	  {
      if($xsend->success_code < 1 && $schedule->success_code < 1)
	  {
		  echo stripslashes($message);
	  }
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
    <h3 class="panel-title">Others<span class="text-primary">(Otional)</span></h3>
  </div>
  
  <div class="panel-body">
            <?php
			$dtp_date = date('Y-m-d', time()).'T'.date('H:i:s', time()).'Z';
			?>
            <div class="form-group">
            <p align="center"><strong>SCHEDULE MESSAGE:</strong></p>
              <label for="dtp_input1" class="col-md-4 control-label">Select Date/Time</label>
                <div class="input-group date form_datetime col-md-5" data-date="<?php echo $dtp_date;?>" data-date-format="yyyy-mm-dd h:i:s" data-link-field="dtp_input1">
                    <input class="form-control" type="text" value="<?php
      if($xsend->success_code < 1 && $schedule->success_code < 1)
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
           
            <div class="form-group">
              <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="report" id="report"> Send report as SMS(<span class="text-primary">Additional Charges</span>)
        </label>
      </div>
    </div>
</div>
            
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
  <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="draft" class="btn btn-warning" value="Save as Draft">
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