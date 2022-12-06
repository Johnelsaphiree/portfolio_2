<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

/*$fp = fopen("lock/flock.txt", "w+");

if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
    // do the work*/
	
include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$form = new select();
$form->pick('form_job', 'id, senderid, destination, message, user, credit', 'unix_timestamp(senddate)', "unix_timestamp('$now')", '', 'record', '', '', '<', '');

if($form->count > 0)
{
$xsend = new process();
	while($form_row = mysql_fetch_row($form->query))
	{
		$ccheck = new check();
			$ccheck->vcredit($form_row[4], $form_row[5]);
			if($ccheck->error_code < 1)
		{	
$xsend->pay($form_row[4], $form_row[5], 'sendsms');
$xsend->sendsms($form_row[1], $form_row[2], $form_row[3]);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$form_row[1]', '$form_row[2]', '$form_row[3]', $form_row[5], $form_row[4], '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $form_row[5], $form_row[4], 1, '$now'");

$del = new delete();
$del->gone('form_job', 'id', "$form_row[0]");
				}
		}//check cost
	}
}

mysql_close($connect);
/*
 flock($fp, LOCK_UN); // release the lock
} else {
    echo "Couldn't get the lock!";
}

fclose($fp);*/
?>