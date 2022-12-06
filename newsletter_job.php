<?php
$hour = time();
$hour = (int)$hour;

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');

$check = new select();
$check->pick('newsletter_job', 'id, topic, start, hour, message', '', '', "0, 1", 'record', 'id', '', '', '');
if($check->count > 0)
{
	//echo "yes <br />";
	$check_row = mysql_fetch_row($check->query);
	if($hour >= ($check_row[3] + 7200))
	{
		//echo "hour <br />";;
	$sel = new select();
		$sel->pick('user', 'email', '', '', "$check_row[2], 500", 'record', '', '', '', '');
		if($sel->count > 0)
		{
			while($row = mysql_fetch_row($sel->query))
			{
				if($email == '')
				{
					$email = $row[0];
				}
				else
				{
					$email = $email.', '.$row[0];
				}
				//echo "$email <br />";
			}
			//send mail
			$to = $email;
//$subject = $check_row[1];
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$xmessage = "<html><body>";
$xmessage .= '<p><img src="$logo_link" alt="Logo" /></p>';
$xmessage .= "<p>$check_row[4]</p>
<p>$cemail<br />
$csite_url
$mynumber
</p>";

$xmessage .= "</body></html>";
mail($to, $subject, $xmessage, $headers);
//update newsletter_job
$up = new update();
$ustart = $check_row[2] + 500;
$up->up('newsletter_job', 'start', 'id', "$check_row[0]", "$ustart");
$up->up('newsletter_job', 'hour', 'id', "$check_row[0]", "$hour");
		}
		else
		{
			//delete row
			$del = new delete();
			$del->gone('newsletter_job', 'id', "$check_row[0]");
			
			//send mail
			$to = $cemail;
//$subject = 'Newsletter job';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$xmessage = '<html><body>';
$xmessage .= '<p>Your Newsletter job has been completed!</p>';

$xmessage .= "</body></html>";
mail($to, $subject, $xmessage, $headers);
		}
	}//hour
}
else
{
	//truncate table
	$trunc = new truncate();
	$trunc->clear('newsletter_job');
}

mysql_close($connect);
?>