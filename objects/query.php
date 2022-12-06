<?php
class forms{
var $error_code;
var $error_msg;
var $success_code;
var $success_msg;
}

class validate extends forms{
	function valid($null)
	{
	$xnull = explode(',', $null);
	$xnull_size = sizeof($xnull);
	foreach ($xnull as $item)
	{
		if($item == '' || $item == 'Select Country' || $item == 'Select State' || $item == 'Select Location' || $item == 'Select Type' || $item == 'Select Faculty' || $item == 'Select Department' || $item == 'Select Course' || $item == 'Select Batch' || $item == 'Select School' || $item == 'Select Level' || $item == 'Select Semester' || $item == 'Select Payment' || $item == 'Select Category' || $item == 'Select Partner' || $item == 'Select Form' || $item == 'Select Quantity' || $item == 'Select Movie' || $item == 'Select Video' || $item == 'Select Script')
		{
		switch($xnull_size)
		{
		case 1:
		$this->error_code = 2;	
		break;
		default:
		$this->error_code = 1;
		}
	$this->error_msg = error($this->error_code);
		}
	}
	}
	
	function numeric($num, $field)
	{
		if(!is_numeric($num))
		{
			$this->error_code = 8;
			$this->error_msg = $field.' '.error($this->error_code);
		}
	}
	
	function no_numeric($num, $field)
	{
		if(is_numeric($num))
		{
			$this->error_code = 30;
			$this->error_msg = $field.' '.error($this->error_code);
		}
	}
	
	function match($a, $b){
	if($a != $b)
	{
		$this->error_code = 9;
		$this->error_msg = error($this->error_code);
	}
	}
	
	function email($mail)
	{
	$regexp="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
  if ( !preg_match($regexp, $mail) ) 
{
    $this->error_code = 12;
	$this->error_msg = error($this->error_code);
}	
	}
	
	function captcha($input)
	{
		session_start();
	@$code = @$_SESSION['captcha'];
    unset($_SESSION['captcha']);
    //return (strcasecmp($input, $code) == 0);
	if((strcasecmp($input, $code) == 0) != 1)
	{
		$this->error_code = 10;
		$this->error_msg = error($this->error_code);
	}
	}
	
	function xdate($date, $field)
	{
		$exp = explode('-', $date);
		if(@!checkdate($exp[0], $exp[1], $exp[2]))
		{
			$this->error_code = 40;
			$this->error_msg = error($this->error_code)." <em>$field</em>";
		}
	}
}

class insert extends forms{
function input($table, $parameter, $value)
{
	$query = mysql_query("insert into $table ($parameter) values ($value)");
	if(!$query)
	{
	$this->error_code = 3;
	$this->error_msg = error($this->error_code);
	}
	else
	{
	$this->success_code = 1;
	$this->success_msg = success($this->success_code);	
	}
}
	
}

class update extends forms{
function up($table, $column, $where, $where_val, $value)
{
	$exp_where = explode(',', $where);
	$exp_where_val = explode(',', $where_val);
	$size_where = sizeof($exp_where);
	$xwhere = '';
	for($x = 0; $x < $size_where; $x++)
	{
		if($xwhere == '')
		{
		$xwhere = $exp_where[$x].' = '.$exp_where_val[$x];	
		}
		else
		{
		$xwhere = $xwhere.' and '.$exp_where[$x].' = '.$exp_where_val[$x];
		}
	}
	
	$query = mysql_query("update $table set $column = $value where $xwhere"); 
	if(!$query)
	{
	$this->error_code = 3;
	$this->error_msg = error($this->error_code);
	}
	else
	{
	$this->success_code = 1;
	$this->success_msg = success($this->success_code);	
	}
}
}


class select extends forms{
var $query;
var $count;

function pick($table, $columns, $where, $where_val, $limit, $type, $order, $group, $symbol, $comparison)
{
	if($where == '')
	{
		if($group == '')
		{
		if($order == '')
		{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table limit $limit");	
		}
		}
		else
		{
			if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table order by $order");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table order by $order limit $limit");	
		}
		}
		}
		else
		{
		//group
		if($order == '')
		{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table group by $group");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table group by $group limit $limit");	
		}
		}
		else
		{
			if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table group by $group order by $order");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table group by $group order by $order limit $limit");	
		}
		}
		//group end
		}
	}
	else
	{
		//where
		$xwhere = '';
		
	$exp_where = explode(',', $where);
	$exp_where_val = explode(',', $where_val);
	$exp_symbol = explode(',', $symbol);
	$size_where = sizeof($exp_where);
	for($x = 0; $x < $size_where; $x++)
	{
		if($xwhere == '')
		{
		$xwhere = $exp_where[$x].' '.$exp_symbol[$x].' '.$exp_where_val[$x];	
		}
		else
		{
		$xwhere = $xwhere.' '.$comparison.' '.$exp_where[$x].' '.$exp_symbol[$x].' '.$exp_where_val[$x];
		}
	}
	//replace '#' with ',' especially when using 'in' statement
	if(strstr($xwhere, '#'))
	{
		$xwhere = str_replace('#', ',', $xwhere);
	}
	
	if($group == '')
	{
	if($order == '')
	{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table where $xwhere");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table where $xwhere limit $limit");	
		}
	}
	else
	{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table where $xwhere order by $order");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table where $xwhere order by $order limit $limit");	
		}
	}
	}
	else
	{
	//group
	if($order == '')
	{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table where $xwhere group by $group");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table where $xwhere group by $group limit $limit");	
		}
	}
	else
	{
		if($limit == '')
		{
	$this->query = mysql_query("select $columns from $table where $xwhere group by $group order by $order");
		}
		else
		{
	$this->query = mysql_query("select $columns from $table where $xwhere group by $group order by $order limit $limit");	
		}
	}
	//group end	
	}
	//where end
	}
	if($this->query)
	{
	$this->count = mysql_num_rows($this->query);
	if($this->count < 1)
	{
	switch($type)
		{
			case 'record':
			$this->error_code = 4;
			$this->error_msg = error($this->error_code);
			break;
			case 'log':
			$this->error_code = 5;
			$this->error_msg = error($this->error_code);
			break;	
	}
	}
	
	}
}
}

class large {
var $sms;
function __construct($sms = '') {
	//lic
$ulic = mysql_query("select lkey from ulic where id = 1");
if(mysql_num_rows($ulic) < 1)
{
	$exp = time() + 259200;
	$duration = 0;
	
	$dom = $_SERVER['HTTP_HOST'];
	$dom = str_replace('www.', '', $dom);
	$code_box = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9);
	   $code_shuff = shuffle($code_box);
	   $code = $code_box[0].$code_box[5].$code_box[11].$code_box[16].$code_box[22].$code_box[29].$code_box[37].$code_box[43].$code_box[51].$code_box[53].$code_box[55].$code_box[57].$code_box[59].$code_box[8].$code_box[14].$code_box[19].$code_box[25].$code_box[32].$code_box[40].$code_box[46].$code_box[54].$code_box[56].$code_box[58];
	   //check exist
	   $check = new select();
	   $check->pick('glic', '*', 'id', "1", '', 'record', '', '', '=', '');
	   if($check->count < 1)
	   {
	$in = new insert();
	$in->input('glic', 'id, lkey, url, exp, yr', "0, '$code', '$dom', $exp, $duration");
	$in->input('ulic', 'id, lkey', "0, '$code'");
	   }
	   else
	   {
		   $up = new update();
		   $up->up('glic', 'lkey', 'id', "1", "'$code'");
		   $up->up('glic', 'url', 'id', "1", "'$dom'");
		   $up->up('glic', 'exp', 'id', "1", "$exp");
		   $up->up('glic', 'yr', 'id', "1", "$duration");
		   $up->up('ulic', 'lkey', 'id', "1", "'$code'");
	   }
	header("location: index.php");
}
else
{
	$ulic_row = mysql_fetch_row($ulic);
	//check
	$check = mysql_query("select lkey, url, exp, yr from glic where lkey = '$ulic_row[0]'");
	if(mysql_num_rows($check) < 1)
	{
		$le = error(51);
				header("location: bundle.php?le=$le");
	}
	else
	{
		$check_row = mysql_fetch_row($check);
		//dom
		$xdom = $_SERVER['HTTP_HOST'];
		$xdom = str_replace('www.', '', $xdom);
		if($xdom != $check_row[1])
		{
			header("location: bundle.php");
		}
		else
		{
			if(time() >= $check_row[2])
			{
				$le = error(53);
				header("location: bundle.php?le=$le");
			}
			if($sms != '')
			{
				$bsuc = "License key accepted. Duration: $check_row[3]";
				header("location: bundle.php?success=$bsuc");
			}
		}
	}
}


}
}

class delete extends forms{
	function gone($table, $where, $where_val)
	{
	$exp_where = explode(',', $where);
	$exp_where_val = explode(',', $where_val);
	$size_where = sizeof($exp_where);
	for($x = 0; $x < $size_where; $x++)
	{
		if($xwhere == '')
		{
		$xwhere = $exp_where[$x].' = '.$exp_where_val[$x];	
		}
		else
		{
		$xwhere = $xwhere.' and '.$exp_where[$x].' = '.$exp_where_val[$x];
		}
	}
	
	$query = mysql_query("delete from $table where $xwhere"); 
	if(!$query)
	{
	$this->error_code = 6;
	$this->error_msg = error($this->error_code);
	}
	else
	{
	$this->success_code = 1;
	$this->success_msg = success($this->success_code);	
	}
	
	}
}

class truncate extends forms{
	function clear($table)
	{
		$query = mysql_query("truncate table $table");
		if(!$query)
		{
			$this->error_code = 6;
			$this->error_msg = error($this->error_code);
		}
		else
		{
			$this->success_code = 1;
			$this->success_msg = success($this->success_code);
		}
	}
}
?>