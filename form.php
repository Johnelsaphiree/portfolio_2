<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'form';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');
include('up.php');

$fid = isset($_POST['fid']) ? $_POST['fid'] : '';
$rec_name = isset($_POST['rec_name']) ? $_POST['rec_name'] : '';
$msg_rec = isset($_POST['msg_rec']) ? $_POST['msg_rec'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

if (!empty($_POST))
{
	$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($msg_rec, 'single');
		$msg_rec = $nval->vnumber;
		
	//get form info
	$form = new select();
	$form->pick('form', 'name, mno, sid, user', 'id', "$fid", '', 'record', '', '', '=', '');
	$form_row = mysql_fetch_row($form->query);
	$fitem = new select();
	$fitem->pick('form_item', 'id, fmessage, fwhen, ftime', 'form', "$fid", '', 'record', '', '', '=', '');
	while($fitem_row = mysql_fetch_row($fitem->query))
	{
		if($fitem_row[3] == '1am')
			{
				$time = '1:00:00';
			}
			elseif($fitem_row[3] == '2am')
			{
				$time = '2:00:00';
			}
			elseif($fitem_row[3] == '3am')
			{
				$time = '3:00:00';
			}
			elseif($fitem_row[3] == '4am')
			{
				$time = '4:00:00';
			}
			elseif($fitem_row[3] == '5am')
			{
				$time ='5:00:00';
			}
			elseif($fitem_row[3] == '6am')
			{
				$time = '6:00:00';
			}
			elseif($fitem_row[3] == '7am')
			{
				$time = '7:00:00';
			}
			elseif($fitem_row[3] == '8am')
			{
				$time = '8:00:00';
			}
			elseif($fitem_row[3] == '9am')
			{
				$time = '9:00:00';
			}
			elseif($fitem_row[3] == '10am')
			{
				$time = '10:00:00';
			}
			elseif($fitem_row[3] == '11am')
			{
				$time = '11:00:00';
			}
			elseif($fitem_row[3] == '1pm')
			{
				$time = '13:00:00';
			}
			elseif($fitem_row[3] == '2pm')
			{
				$time = '14:00:00';
			}
			elseif($fitem_row[3] == '3pm')
			{
				$time = '15:00:00';
			}
			elseif($fitem_row[3] == '4pm')
			{
				$time = '16:00:00';
			}
			elseif($fitem_row[3] == '5pm')
			{
				$time = '17:00:00';
			}
			elseif($fitem_row[3] == '6pm')
			{
				$time = '18:00:00';
			}
			elseif($fitem_row[3] == '7pm')
			{
				$time = '19:00:00';
			}
			elseif($fitem_row[3] == '8pm')
			{
				$time = '20:00:00';
			}
			elseif($fitem_row[3] == '9pm')
			{
				$time = '21:00:00';
			}
			elseif($fitem_row[3] == '10pm')
			{
				$time = '22:00:00';
			}
			elseif($fitem_row[3] == '11pm')
			{
				$time = '23:00:00';
			}
			
		switch($fitem_row[2])
		{
			case 'Instantly':
			$count = strlen($fitem_row[1]);
		$xcount = ceil($count/160);
		//get cost
		$net = mysql_query("select ncode, ucost, name from network");
		if(mysql_num_rows($net) > 0)
		{
			while($net_row = mysql_fetch_row($net))
			{
				if(substr_count($msg_rec, "$net_row[0]") > 0)
				{
					$cal_cost = $net_row[1];
					$ccheck = new check();
			$ccheck->vcredit($form_row[3], $cal_cost);
			if($ccheck->error_code < 1)
		{	
			$xsend = new process();
			$xsend->pay($form_row[3], $cal_cost, 'sendsms');
$xsend->sendsms($form_row[2], $msg_rec, $fitem_row[1]);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$form_row[2]', '$msg_rec', '$fitem_row[1]', $cal_cost, $form_row[3], '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $cal_cost, $form_row[3], 1, '$now'");
				}
		}
					break;
				}
			}
		}
			break;
			case '1 day later':
			//get time
			$ct = time() + 86400;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '2 days later':
			//get time
			$ct = time() + 172800;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '3 days later':
			//get time
			$ct = time() + 259200;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '4 days later':
			//get time
			$ct = time() + 345600;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '5 days later':
			//get time
			$ct = time() + 432000;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '6 days later':
			//get time
			$ct = time() + 518400;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '7 days later':
			//get time
			$ct = time() + 604800;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '8 days later':
			//get time
			$ct = time() + 691200;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '9 days later':
			//get time
			$ct = time() + 777600;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '10 days later':
			//get time
			$ct = time() + 864000;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '11 days later':
			//get time
			$ct = time() + 950400;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '12 days later':
			//get time
			$ct = time() + 1036800;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '13 days later':
			//get time
			$ct = time() + 1123200;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '14 days later':
			//get time
			$ct = time() + 1209600;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '15 days later':
			//get time
			$ct = time() + 1296000;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '16 days later':
			//get time
			$ct = time() + 1382400;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '17 days later':
			//get time
			$ct = time() + 1468800;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '18 days later':
			//get time
			$ct = time() + 1555200;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '19 days later':
			//get time
			$ct = time() + 1641600;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
			case '20 days later':
			//get time
			$ct = time() + 1728000;
				$ct1 = date('Y-m-d', $ct);
			$xtime = $ct1.' '.$time;
			break;
		}//switch
		if($fitem_row[2] != 'Instantly')
		{
		$count = strlen($fitem_row[1]);
		$xcount = ceil($count/160);
		//get cost
		$net = mysql_query("select ncode, ucost, name from network");
		if(mysql_num_rows($net) > 0)
		{
			while($net_row = mysql_fetch_row($net))
			{
				if(substr_count($msg_rec, "$net_row[0]") > 0)
				{
					$cal_cost = $net_row[1];
					//insert
					$in = new insert();
					$in->input('form_job', 'id, senderid, destination, message, entrydate, senddate, user, credit', "0, '$form_row[2]', '$msg_rec', '$fitem_row[1]', '$now', '$xtime', $form_row[3], $cal_cost");
					break;
				}
			}
		}
		}//if not instant
	
	}
		//enter recipient
		$ent = new insert();
		$ent->input('form_recipient', 'id, name, tell, form', "0, '$rec_name', '$msg_rec', $fid");
		//send mail
		$ge = new select();
		$ge->pick('user', 'email', 'id', "$form_row[3]", '', 'record', '', '', '=', '');
		$ge_row = mysql_fetch_row($ge->query);
		
					$to = $ge_row[0];
//$subject = 'Auto Responder';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Someone just filled a form you created</p>

<p><strong>DETAILS:</strong><br />
<strong>Form name:</strong> $form_row[0]<br />
<strong>Recipient name:</strong> $rec_name<br />
<strong>Recipient number:</strong> $msg_rec</p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);

$success = success(14);
header("location: form.php?success=$success");
exit;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Form</title>
    <!-- Bootstrap core CSS -->
    <link href="dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
 
  
  <!--avHtFe9bYjI_xfz1HftxNEGKH8w-->
</head>

  <body>
  
    <div class="container">
    <?php
	if($success != '')
	{
		?>
     <div class="alert alert-success"><?php echo $success;?></div>   
        <?php
	}
	?>
    
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script>
	$('.carousel').carousel({
  interval: 5000
})
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>