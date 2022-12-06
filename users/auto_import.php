<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'aauto';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$view = $_GET['view'];

$path = $_FILES['ufile']['tmp_name'];
$path_type = $_FILES['ufile']['type'];
$path_size = $_FILES['ufile']['size'];
$max = 2000000;
$upload = $_POST['upload'];

$sval = new validate();
$in = new insert();

$check = new select();
$check->pick('form', '*', 'id,user', "$view,$auser", '', 'record', '', '', '=,=', 'and');
if($check->count < 1)
{
	header("location: auto.php");
}

if($upload)
{
	$sval = new validate();
		$sval->valid($path);
			//val file
			$fval = new file_validate();
			$fval->valid($path_type, $path_size, $max, 'text');
			if($fval->error_code > 0)
			{
				$sval->error_code = $fval->error_code;
				$sval->error_msg = error($sval->error_code);
			}
			
	if($sval->error_code < 1)
	{		
	$sql = new insert();
	$error = '';
$file = file_get_contents($path);
$exp = explode("\r\n", $file);

$sn = 0;
///////////////////////////////////////////////
foreach($exp as $item)
{
	if($sn > 0)
	{
	$val = explode("\t", $item);
if($val[1] != '')
{
$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($val[0], 'single');
		$val[0] = $nval->vnumber;
//st
//get form info

	$form = new select();
	$form->pick('form', 'name, mno, sid, user', 'id', "$view", '', 'record', '', '', '=', '');
	$form_row = mysql_fetch_row($form->query);
	$fitem = new select();
	$fitem->pick('form_item', 'id, fmessage, fwhen, ftime', 'form', "$view", '', 'record', '', '', '=', '');
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
				if(substr_count($val[0], "$net_row[0]") > 0)
				{
					$cal_cost = $net_row[1];
					$ccheck = new check();
			$ccheck->vcredit($form_row[3], $cal_cost);
			if($ccheck->error_code < 1)
		{	
			$xsend = new process();
			$xsend->pay($form_row[3], $cal_cost, 'sendsms');
$xsend->sendsms($form_row[2], $val[0], $fitem_row[1]);
if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$form_row[2]', '$val[0]', '$fitem_row[1]', $cal_cost, $form_row[3], '$now'");
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
				if(substr_count($val[0], "$net_row[0]") > 0)
				{
					$cal_cost = $net_row[1];
					//insert
					$in = new insert();
					$in->input('form_job', 'id, senderid, destination, message, entrydate, senddate, user, credit', "0, '$form_row[2]', '$val[0]', '$fitem_row[1]', '$now', '$xtime', $form_row[3], $cal_cost");
					break;
				}
			}
		}
		}//if not instant
	
	}
	//echo 'yes';
		//enter recipient
		$ent = new insert();
		$ent->input('form_recipient', 'id, name, tell, form', "0, '$val[1]', '$val[0]', $view");

//st_end
}//val space
	}
	$sn = $sn + 1;
}

$success = success(14);
header("location: auto.php?success=$success&view=$view");
exit;
	}//val
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Import List</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  
    <div class="container">
    <?php
	include('../body/head.php');
	?>
    <div class="row">
  <?php
  include('../body/sidex.php');
  ?>
  <div class="col-md-9">
  <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li><a href="auto.php">AUTO RESPONDER</a></li>
  <li class="active">IMPORT LIST</li>
</ol>
<h4>Import List</h4>
  <?php
  if($sval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sval->error_msg."</div>";
	}
	if($sql->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sql->error_msg."</div>";
	}
	if($error != '')
	{
	echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	?>
    <p><a href="../doc/auto.xlsx">Download Template</a></p>
    <p><strong>INSTRUCTION: </strong>Download the template using the link above, then open the file and make your arrangements, then save the file as "text(tab dilimited). Now browse for the text file and upload then click the import button.</p>
    <form action="auto_import.php?view=<?php echo $view;?>" method="post" enctype="multipart/form-data" name="bulk_form" class="form-horizontal" role="form">
   <table cellpadding="10" align="center" width="100%">
  <tr>
  <td>
  <div class="form-group">      
    <label for="ufile" class="control-label">Upload file*</label>  
    <input type="file" name="ufile" id="ufile">
  </div>
  
  <div class="form-group">
      <input type="submit" name="upload" class="btn btn-primary" value="Import">
  </div>
  </td>
  </tr>
  </table>
    </form>
  </div><!--panel-body-->
</div><!--main panel-->

  </div>
  </div>
    </div><!-- /.container -->
    <?php
	include('../body/foot.php');
	?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect);
?>