<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$xsend = new process();

$check = new select();
$check->pick('xjob', 'id, start, message, type, body, credit', '', '', "0, 1", 'record', 'id', '', '', '');
if($check->count > 0)
{
	//echo "yes <br />";
	$check_row = mysql_fetch_row($check->query);
		//echo "hour <br />";;
	$sel = new select();
	switch($check_row[3])
	{
		case 'all users':
		$sel->pick('user', 'phone', '', "", "$check_row[1], 2", 'record', '', '', '', '');
		$jsend = $csenderid;
		//echo 1;
		break;
	}
		if($sel->count > 0)
		{
			$xarr = array();
	$arr['network'] = array();
	$arr['code'] = array();
	$arr['tcost'] = array();
	$arr['count'] = array();
	$tcost = 0;
	$tcount = 0;
			
			while($row = mysql_fetch_row($sel->query))
			{
				if(empty($tell))
				{
					$tell = $row[0];
				}
				else
				{
					$tell = $tell.','.$row[0];
				}
				//echo "$email <br />";
			}
			
			$xsend->billing($check_row[2], $tell, 0, 'sendnow', $tcost, $arr, $tcount);
			$arr = $xsend->net_arr;
				$mcount = $xsend->msg_count;
				
			$cost = $xsend->cost;
			$tcount = $xsend->net_tcount;
			
			$xsend->pay(0, $cost, 'sendsms');

//echo $tell;
//send sms
$smsg = "$check_row[2]";
$xsend->sendsms($jsend, $tell, $smsg);

//update job
$up = new update();
$ustart = $check_row[1] + 2;
$ucost = $check_row[5] + $cost;
$up->up('xjob', 'start', 'id', "$check_row[0]", "$ustart");
$up->up('xjob', 'credit', 'id', "$check_row[0]", "$ucost");

//echo $cost."<br />".$tell."<br />".$jsend;
		}
		else
		{
			//Send Email
					$to = $cemail;
//$subject = 'SMS Report';
$subject = 'Msg All Users Job Completed';

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your message was sent successfully to all users on your portal</p>

<p><strong>Cost:</strong> $check_row[5]<br /></p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);


			//get total users
			$tu = new select();
			$tu->pick('user', 'count(*)', '', '', '', 'record', '', '', '', '');
			$tu_row = mysql_fetch_row($tu->query);
			//log
			$log = new insert();
			//$log->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$jsend', '$tu_row[0]', '$check_row[2]', $check_row[5], 0, '$now'");
			$log->input('transaction', 'id, type, credit, user, tuser, date', "0, 'MSG ALL USERS', $check_row[5], 0, '$tu_row[0]', '$now'");
			//delete row
			$del = new delete();
			$del->gone('xjob', 'id', "$check_row[0]");
		}
}
else
{
	//truncate table
	$trunc = new truncate();
	$trunc->clear('xjob');
}

mysql_close($connect);
?>