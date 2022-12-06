<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

/*$fp = fopen("lock/clock.txt", "w+");

if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
    // do the work*/
	
include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

//SCHEDULE
$schedule = new select();
$schedule->pick('schedule', 'id, senderid, destination, message, user, credit, no_count, status', 'unix_timestamp(senddate)', "unix_timestamp('$now')", '', 'record', '', '', '<', '');

if($schedule->count > 0)
{
$xsend = new process();
	while($schedule_row = mysql_fetch_row($schedule->query))
	{
$nval = new number_val();
$nval->length($schedule_row[2], 'group');

$xsend->pay($schedule_row[4], $schedule_row[5], 'sendsms');
$xsend->sendsms($schedule_row[1], $schedule_row[2], $schedule_row[3], $nval->xsession);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$schedule_row[1]', '$schedule_row[2]', '$schedule_row[3]', $schedule_row[5], $schedule_row[4], '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $schedule_row[5], $schedule_row[4], $schedule_row[6], '$now'");
					
					//get info
						$guser = new select();
						$guser->pick('user', 'phone, email', 'id', "$schedule_row[4]", '', 'record', '', '', '=', '');
						$guser_row = mysql_fetch_row($guser->query);
						
					if($schedule_row[7] == 'report')
					{
						$rsender = $csenderid;
						$rmsg = "Your message has been sent successfully to $schedule_row[6] recipients.";
						$rtell = $guser_row[0];
						$xsend->sendsms($rsender, $rtell, $rmsg);
					}
					
		$count = strlen($schedule_row[3]);
		$xcount = ceil($count/160);
		
					//Send Email
					$to = $guser_row[1];
//$subject = 'SMS Report';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your message was sent successfully to $schedule_row[6] recipients</p>

<p><strong>Message Count:</strong> $xcount<br />
<strong>Cost:</strong> $schedule_row[5]</p>
<p>$csite_name</p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);


//updates
$up = new update();
if($schedule_row[4] == 0)
{
	$up->up('admin', 'reserved', 'id', "1", "reserved - $schedule_row[5]");
}
else
{
$up->up('user', 'reserved', 'id', "$schedule_row[4]", "reserved - $schedule_row[5]");
}

$del = new delete();
$del->gone('schedule', 'id', "$schedule_row[0]");
				}
	}
}

//TOKEN
$tok = mysql_query("delete from token where (unix_timestamp(date) + 86400) < unix_timestamp('$now')");

//COUPON
$coup = new select();
$coup->pick('coupon', 'id', 'unix_timestamp(exp_date)', "unix_timestamp(now())", '', 'record', '', '', '<', '');
if($coup->count > 0)
{
	while($coup_row = mysql_fetch_row($coup->query))
	{
		$cd = new delete();
		$cd->gone('coupon_usage', 'coupon_id', "$coup_row[0]");
		$cd->gone('coupon', 'id', "$coup_row[0]");
	}
}

mysql_close($connect);

/* flock($fp, LOCK_UN); // release the lock
} else {
    echo "Couldn't get the lock!";
}

fclose($fp);*/
?>