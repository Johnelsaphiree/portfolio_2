<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$username = $_GET['username'];
$password = $_GET['password'];
$xpassword = md5($password);
$sender = $_GET['sender'];
$dest = $_GET['dest'];
$msg = $_GET['msg'];
$meg_report = $_GET['meg_report'];

$xarr = array();
	$arr['network'] = array();
	$arr['code'] = array();
	$arr['tcost'] = array();
	$arr['count'] = array();
	$tcost = 0;
	$tcount = 0;
	//check network charge
//check username and password
$user = new select();
$user->pick('user', 'id, email, balance', 'username,password', "'$username','$xpassword'", '', 'log', '', '', '=,=', 'and');
if($user->count < 1)
{
	echo error(13);
}
else
{
	$urow = mysql_fetch_row($user->query);
	if($meg_report == '')
	{
	if($sender == '')
	{
		echo 'Null Sender';
	}
	elseif($dest == '')
	{
		echo 'Null Destination';
	}
	elseif($msg == '')
	{
		echo 'Null Message';
	}
	else
	{

		$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($dest, 'group');
		$otell = $nval->vnumber;
		$ncount = $nval->cnumber;
		
		if($ncount > 0)
	{
		//get return url
		/*$url = new select();
		$url->pick('return_url', 'url', 'user', "$urow[0]", '', 'record', '', '', '=', '');
		if($url->count > 0)
		{
			$url_row = mysql_fetch_row($url->query);
		}*/
		
		$xsend = new process();
			$xsend->billing($msg, $otell, $urow[0], 'sendnow', $tcost, $arr, $tcount);
			$arr = $xsend->net_arr;
				$mcount = $xsend->msg_count;
				
			$cost = $xsend->cost;
			$tcount = $xsend->net_tcount;
			$cdiff = $ncount - $tcount;
			
			if($xsend->success_code > 0)
			{
				//Pay
				$xsend->pay($urow[0], $cost, 'sendsms');
				$xsend->sendsms($sender, $otell, $msg, $nval->xsession);
				
				if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$sender', '$otell', '$msg', $cost, $urow[0], '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $cost, $urow[0], '$ncount', '$now'");
					
//redirect
//header("location: http://www.$url_row[0]&status=146");
echo 146;
				}
			}//billing
			else
			{
				//header("location: http://www.$url_row[0]&status=141");
				echo 141;
			}
	}//ncount
	else
	{
				echo error(15);
	}
	
	}
	
	}//meg_report
	elseif($meg_report == 'balance')
	{
		//get return url
		$url = new select();
		$url->pick('report_url', 'url', 'user', "$urow[0]", '', 'record', '', '', '=', '');
		if($url->count > 0)
		{
			$url_row = mysql_fetch_row($url->query);
		}
		
		//header("location: http://www.$url_row[0]&balance=$urow[2]");
		echo $urow[2];
	}
}
?>