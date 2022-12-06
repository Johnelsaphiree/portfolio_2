<?php
class process{
var $error_code;
var $error_msg;
var $success_code;
var $success_msg;
var $cost;
var $msg_count;
var $xsession;
var $test;
var $net_arr;
var $net_tcount;


function sendsms($senderid, $num, $msg, $psession = array())
{	
//get api link
$aplink = mysql_query("select slink from api where xdefault = '1'");
if(mysql_num_rows($aplink) > 0)
{
$aplink_row = mysql_fetch_row($aplink);

	if(stristr($num, ','))
		{
			if(sizeof($psession) > 20)
			{
				$sno = 0;
		foreach($psession as $gitem)
		{
			$gitem = str_replace(' ', '', $gitem);
			$gitem = implode(',',$gitem);
			switch($sno)
			{
				case 0;
				$xjob = 'job';
				break;
				case 1;
				$xjob = 'job1';
				break;
				case 2;
				$xjob = 'job2';
				break;
				case 3;
				$xjob = 'job3';
				break;
				case 4;
				$xjob = 'job4';
				break;
			}
			//$this->error_msg = $this->error_msg.$gitem."<br /><br /><br />";
$job = mysql_query("insert into $xjob (id, senderid, destination, message) values (0, '$senderid', '$gitem', '$msg')");

$sno = $sno + 1;
if($sno > 4)
{
	$sno = 0;
}

		}
			}
			else
			{
				$msg = stripslashes($msg);
$senderid = stripslashes($senderid);
	$senderid = urlencode($senderid);
	$msg = urlencode($msg);	
		foreach($psession as $gitem)
		{
			$gitem = str_replace(' ', '', $gitem);
			if(@stristr($gitem, ','))
			{
				//do nothing
			}
			else
			{
			$gitem = implode(',',$gitem);
			}
			//$this->error_msg = $this->error_msg.$gitem."<br /><br /><br />";
	$gitem = urlencode($gitem);
	
$live_url= str_replace('@@sender@@', $senderid, $aplink_row[0]);
$live_url= str_replace('@@recipient@@', $gitem, $live_url);
$live_url= str_replace('@@msg@@', $msg, $live_url);

/*// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $live_url);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);*/

$parse_url=file($live_url);
//echo $parse_url[0];
//echo $live_url;
		}//foreach
		
		}//grp4
		}
		else
		{
			$num = str_replace(' ', '', $num);
			$msg = stripslashes($msg);
$senderid = stripslashes($senderid);
	$senderid = urlencode($senderid);
	$msg = urlencode($msg);
	$num = urlencode($num);
$live_url= str_replace('@@sender@@', $senderid, $aplink_row[0]);
$live_url= str_replace('@@recipient@@', $num, $live_url);
$live_url= str_replace('@@msg@@', $msg, $live_url);
/*// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $live_url);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);*/

$parse_url=file($live_url);

//echo $parse_url[0];
//echo $live_url;
		}
	$this->success_code = 6;
	$this->success_msg = success($this->success_code);
}//api availability
$this->error_code = 54;
$this->error_msg = error($this->error_code);

}

function billing($message, $num, $user, $type, $tcost, $arr, $tcount)
{
	$num = ','.$num;
	$message = stripslashes($message);
	
		$count = strlen($message);
		$xcount = ceil($count/160);
		$this->msg_count = $xcount;
		//get cost
		$net = mysql_query("select ncode, ucost, name from network");
		if(mysql_num_rows($net) > 0)
		{
			while($net_row = mysql_fetch_row($net))
			{
				$cal_cost = 0;
				if(substr_count($num, "$net_row[0]") > 0)
				{
					$occur = substr_count($num, "$net_row[0]");
					$cal_cost = $occur * $net_row[1];
					if(array_search("$net_row[2]", $arr['network']) > -1)
					{
						$key = array_search("$net_row[2]", $arr['network']);
						
						$arr['tcost'][$key] = $arr['tcost'][$key] + $cal_cost;
						$arr['count'][$key] = $arr['count'][$key] + $occur;
						$tcost = $tcost + $cal_cost;
						$tcount = $tcount + $occur;
					}
					else
					{
						$arr['network'][] = $net_row[2];
						$arr['code'][] = $net_row[0];
						$arr['tcost'][] = $cal_cost;
						$arr['count'][] = $occur;
						$tcost = $tcost + $cal_cost;
						$tcount = $tcount + $occur;
					}
					//echo $arr['network'][0].', '.$arr['code'][0]."<br />";
				}
			}
		}
		
		
		$this->cost = $xcount * $tcost;
		$this->net_tcount = $tcount;
		$this->net_arr = $arr;
		
		if($user == 0)
		{
		$sql = mysql_query("select balance, reserved from admin where id = 1");
		$row = mysql_fetch_row($sql);
		}
		else
		{
			//user
		$sql = mysql_query("select balance, reserved from user where id = $user");
		$row = mysql_fetch_row($sql);
		}
		
		if($row[0] < $this->cost)
		{
			$this->error_code = 16;
			$this->error_msg = error($this->error_code).' Transaction Cost: '.$this->cost.' Units';
		}
		else
		{
			if($type == 'schedule')
			{
				$this->success_code = 1;
				$this->success_msg = success($this->success_code);
			}
			elseif($type == 'sendnow')
			{
				$calc = $row[0] - $this->cost;
				if($calc < $row[1])
				{
					$this->error_code = 16;
					$this->error_msg = error($this->error_code).' Transaction Cost: '.$this->cost." Units. You can't use credit reserved for Scheduled SMS";
				}
				else
				{
				$this->success_code = 1;
				$this->success_msg = success($this->success_code);
				}
			}
		}
}

function pay($user, $amount, $type)
{
	if($user == 0)
	{
	$sql = mysql_query("update admin set balance = balance - $amount where id = 1");
	}
	else
	{
		//users
	$sql = mysql_query("update user set balance = balance - $amount where id = $user");
	}
}

}

class number_val extends process{
	var $vnumber;
	var $cnumber;
	function length($no, $type){
		//$this->xsession = array();
		
		switch($type)
		{
			case 'single':
		if(strlen($no) == 11)
		{
			$no = '234'.substr($no, 1);
		}
		$this->vnumber = $no;
		break;
		case 'group':
		if(substr($no, 0, 1) == '0')
{
	$no = '234'.substr($no, 1);
}
		$no = str_replace(',0', ',234', $no);
		$no = str_replace("\r\n0", ',234', $no);
		$no = str_replace("\r\n2", ',2', $no);
		$no = str_replace(', 0', ',234', $no);
		
		if(stristr($no, ','))
		{
			$no = str_replace("\r\n", '', $no);
			$exp = explode(',', $no);
		}
		else
		{
			$exp = explode("\r\n", $no);
		}
		$exp = array_unique($exp);
		$this->cnumber = sizeof($exp);
		$this->vnumber = implode(',',$exp);
		$this->xsession = array_chunk($exp, 50);
		
		break;
		case 'array':
			foreach($no as $item)
			{
				if(stristr($item, ','))
				{
					$exp = explode(',', $item);
					//session
					$grp = 1;
			foreach($exp as $sitem)
			{
	
			if($this->xsession[$grp] == '')
			{
				$gcount = 1;
	
				$this->xsession[$grp] = $sitem;
			}
			else
			{
				$gcount = $gcount + 1;
				if($gcount == 1)
				{
			
					$this->xsession[$grp] = $sitem;
				}
				else
				{
	
				$this->xsession[$grp] .= ','.$sitem;
				}
				
				if($gcount == 50)
				{
					$grp = $grp + 1;
					$gcount = 0;
				}
			}
		}
		//session end
			if($this->vnumber == '')
			{
				$this->vnumber = $item;
				$this->cnumber = sizeof($exp);
			}
			else
			{
				$this->vnumber .= ','.$item;
				$this->cnumber = $this->cnumber + sizeof($exp);
			}
				}
				else
				{
			if($this->vnumber == '')
			{
				$this->vnumber = $item;
				$this->cnumber = 1;
				
				$gcount = 1;
		
				$this->xsession[$grp] = $item;
			}
			else
			{
				$this->vnumber .= ','.$item;
				$this->cnumber = $this->cnumber + 1;
				
				$gcount = $gcount + 1;
				if($gcount == 1)
				{
		
					$this->xsession[$grp] = $item;
				}
				else
				{
		
				$this->xsession[$grp] .= ','.$item;
				}
				
				if($gcount == 50)
				{
					$grp = $grp + 1;
					$gcount = 0;
				}
			}
				}
			
			}
		break;
	}
	
	}
	
	function number_count($num)
	{
		$exp = explode(',', $num);
		$this->cnumber = sizeof($exp);
	}
}

class number_clean extends process{
	var $rnumber;
	var $arr_count;
	var $num_diff;
	var $rarr_count;
	
	function fresh($no, $type){
		$this->rnumber = $no;
	}
	
	function duplicate($no)
	{

		if(stristr($no, ','))
		{
			$exp = explode(',', $no);
		}
		else
		{
			$exp = explode("\r\n", $no);
		}
$this->arr_count = sizeof($exp);
/*if($this->arr_count > 100000)
{
	$this->error_code = 31;
	$this->error_msg = error($this->error_code).$this->arr_count;
}
else
{*/
	$exp = array_unique($exp);
	$this->rarr_count = sizeof($exp);
	$this->num_diff = $this->arr_count - $this->rarr_count;
	$this->rnumber = implode(',',$exp);
	//}
	}//greater than 100,000
}

class check extends process{
	var $uemail;
	var $uid;
	var $ubalance;
	var $uphone;
	
	function vcredit($user, $amount)
	{
		if($user == 0)
		{
			$query = mysql_query("select balance, reserved from admin where id = 1");
			$row = mysql_fetch_row($query);
			
			if($row[0] < $amount)
			{
				$this->error_code = 16;
				$this->error_msg = error($this->error_code);
			}
			else
			{
				$xmath = $row[0] - $amount;
				if($xmath < $row[1])
				{
					$this->error_code = 16;
					$this->error_msg = error($this->error_code)." You can't use credit reserved for Scheduled SMS";
				}
			}
		}
		else
		{
			//users
			$query = mysql_query("select balance, reserved from user where id = $user");
			$row = mysql_fetch_row($query);
			
			if($row[0] < $amount)
			{
				$this->error_code = 16;
				$this->error_msg = error($this->error_code);
			}
			else
			{
				$xmath = $row[0] - $amount;
				if($xmath < $row[1])
				{
					$this->error_code = 16;
					$this->error_msg = error($this->error_code)." You can't use credit reserved for Scheduled SMS";
				}
			}
		}
	}
	
	function ckuser($user)
	{
		$query = mysql_query("select email, id, balance, phone from user where username = '$user'");
		if(mysql_num_rows($query) < 1)
		{
			$this->error_code = 14;
			$this->error_msg = error($this->error_code);
		}
		else
		{
			$row = mysql_fetch_row($query);
			$this->uemail = $row[0];
			$this->uid = $row[1];
			$this->ubalance = $row[2];
			$this->uphone = $row[3];
		}
	}
}
?>