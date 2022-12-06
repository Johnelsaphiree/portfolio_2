<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$check = new select();
$check->pick('target_job', 'id, sid, message, count, state, start, user, mcount, cost', '', '', "0, 1", 'record', 'id', '', '', '');

//echo mysql_error();
if($check->count > 0)
{
	//echo "yes <br />";
	$check_row = mysql_fetch_row($check->query);
	
	if($check_row[3] > 50000)
{
	$limit = 50000;
	//echo 'a';
}
else
{
	$limit = $check_row[3];
	//echo 'b';
}

if($check_row[3] > 0)
{
	$sel = new select();
		$sel->pick('target', 'num', 'state', "'$check_row[4]'", "$check_row[5], $limit", 'record', '', '', '=', '');
		if($sel->count > 0)
		{
			$row = mysql_fetch_row($sel->query);
			while($row = mysql_fetch_row($sel->query))
			{
				if($tell == '')
				{
					$tell = $row[0];
				}
				else
				{
					$tell = $tell.','.$row[0];
				}
				//echo "$email <br />";
			}
			//get cost
		$net = mysql_query("select ncode, ucost, name from network");
		if(mysql_num_rows($net) > 0)
		{
							$cal_cost = 0;
			while($net_row = mysql_fetch_row($net))
			{
				if(substr_count($tell, "$net_row[0]") > 0)
				{
					$occur = substr_count($tell, "$net_row[0]");
					//echo $occur.',';
					$ocost = $occur * $net_row[1];
					$cal_cost = $cal_cost + $ocost;
				}
			}
			$xcost = $cal_cost * $check_row[7];
			if($check_row[6] == 0)
		{
		$xsql = mysql_query("select balance, reserved from admin where id = 1");
		$xrow = mysql_fetch_row($xsql);
		}
		else
		{
			//user
		$xsql = mysql_query("select balance, reserved from user where id = $check_row[6]");
		$xrow = mysql_fetch_row($xsql);
		}
		
		if($xrow[0] > $xcost)
		{
				$calc = $xrow[0] - $xcost;
				if($calc > $xrow[1])
				{
					$exp = explode(',', $tell);
					$xsession = array_chunk($exp, 50);
				//send
				$xsend = new process();
				$xsend->pay($check_row[6], $xcost, 'sendsms');
				//print_r($xsession);
$xsend->sendsms($check_row[1], $tell, $check_row[2], $xsession);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$check_row[1]', '$tell', '$check_row[2]', $xcost, $check_row[6], '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $xcost, $check_row[6], $limit, '$now'");

//update count and start
$up = new update();
$ustart = $check_row[5] + $limit;
$ucount = $check_row[3] - $limit;
$ucost = $check_row[8] + $xcost;
$up->up('target_job', 'start', 'id', "$check_row[0]", "$ustart");
$up->up('target_job', 'count', 'id', "$check_row[0]", "$ucount");
$up->up('target_job', 'cost', 'id', "$check_row[0]", "$ucost");
				}
				
				}
		}
		
		}
		
		}
		else
		{
			//send email
			if($check_row[6] == 0)
			{
				$xe = $cemail;
			}
			else
			{
			$ge = new select();
			$ge->pick('user', 'email', 'id', "$check_row[6]", '', 'record', '', '', '=', '');
			$ge_row = mysql_fetch_row($ge->query);
			$xe = $ge_row[0];
			}
			
			$to = $xe;
//$subject = 'SMS Job';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$mcost = 'N'.number_format($check_row[8]);
$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your SMS job has been completed</p>
<p><strong>DETAILS:</strong><br />
SENDER: $check_row[1]<br />
COST: $mcost<br />
MESSAGE:<br />
$check_row[2]
</p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);

			//delete row
			$del = new delete();
			$del->gone('target_job', 'id', "$check_row[0]");
		}
}//check count
else
{
	//del
	$del = new delete();
	$del->gone('target_job', 'id', "$check_row[0]");
}
}
else
{
	//truncate table
	$trunc = new truncate();
	$trunc->clear('target_job');
}

mysql_close($connect);
?>