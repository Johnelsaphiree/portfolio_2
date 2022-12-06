<?php
/*$fp = fopen("lock/jlock2.txt", "w+");

if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock*/
    // do the work

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$sel = new select();
$sel->pick('job2', 'id, senderid, destination, message', '', '', "0, 40", 'record', '', '', '', '');

if($sel->count > 0)
{
	while($row = mysql_fetch_row($sel->query))
	{
		$xsession = array();
		$xsession[] = $row[2];
		
		$xsend = new process();
		$xsend->sendsms($row[1], $row[2], $row[3], $xsession);
		
		//delete from db
		$del = mysql_query("delete from job2 where id = $row[0]");
	}
}
else
{
	$query = mysql_query("truncate table job2");
}

mysql_close($connect);

 /*flock($fp, LOCK_UN); // release the lock
} else {
    echo "Couldn't get the lock!";
}

fclose($fp);*/
?>