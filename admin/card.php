<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'acard';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$sncount = $_GET['sncount'];
$smcount = $_GET['smcount'];
$scost = $_GET['scost'];
$xht = $_SESSION['sht'];
$cid = $_GET['cid'];
$new = $_GET['new'];
$xedit = $_GET['xedit'];
$view = $_GET['view'];
$delete = $_GET['delete'];
$xsuccess = $_GET['xsuccess'];

$sid = $_POST['sid'];
$tnum = $_POST['tnum'];
$message = $_POST['message'];
$send = $_POST['send'];

$save = $_POST['save'];
$title = $_POST['title'];
$name = $_POST['name'];
$tell = $_POST['tell'];
$url = $_POST['url'];
$company = $_POST['company'];
$description = $_POST['description'];

$din = new insert();
$dup = new update();
$xsend = new process();
$ncount = '';

if($send)
{
	//get card info
	$info = new select();
	$info->pick('card', 'title, name, tell, url, company, description', 'id', "$cid", '', 'record', '', '', '=', '');
	$info_row = mysql_fetch_row($info->query);
$message = "NAME: $info_row[1], PHONE: $info_row[2], WEBSITE: $info_row[3], COMPANY: $info_row[4], DESCRIPTION: $info_row[5]";
	$xarr = array();
	$arr['network'] = array();
	$arr['code'] = array();
	$arr['tcost'] = array();
	$arr['count'] = array();
	$tcost = 0;
	$tcount = 0;
	//check network charge
	$sval = new validate();
	$sval->valid($tnum);
	if($sval->error_code < 1)
	{
				$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tnum, 'group');
		$otell = $nval->vnumber;
		$ncount = $nval->cnumber;

			//calculate billing
			if($error == '')
			{
	if($ncount > 0)
	{
		/*if($ncount <= 100000)//xxx
		{*/
			$xsend->billing($message, $otell, 0, 'sendnow', $tcost, $arr, $tcount);
			$arr = $xsend->net_arr;
				$mcount = $xsend->msg_count;
				
			$cost = $xsend->cost;
			$tcount = $xsend->net_tcount;
			$cdiff = $ncount - $tcount;
			
			$ht = "<table class='table table-bordered'>
					<tr>
					<th>NETWORK</th>
					<th>COUNT</th>
					<th>COST</th>
					</tr>";
					if(sizeof($arr['network'] > 0))
					{
						foreach($arr['network'] as $k => $kval)
						{
							$ht .= "<tr>
							<td>$kval</td>
							<td>".$arr['count'][$k]."</td>
							<td>".$arr['tcost'][$k]."</td>
							</tr>";
						}
					}
					$ht .= "<tr>
					<td>OTHERS</td>
							<td>$cdiff</td>
							<td>Not sent</td>
					</tr></table>";
			//echo $cost;
			//print_r($arr);
			$_SESSION['sht'] = $ht;
			
			if($xsend->success_code > 0)
			{
				//If not scheduled
				//Pay
				$xsend->pay(0, $cost, 'sendsms');
				$xsend->sendsms($info_row[0], $otell, $message, $nval->xsession);
				//echo $xsend->error_msg;
				if($xsend->success_code > 0)
				{
					//log
					$rin = new insert();
					$rin->input('smslog', 'id, senderid, destination, message, credit, user, date', "0, '$info_row[0]', '$otell', '$message', $ncount, 0, '$now'");
					$rin->input('transaction', 'id, type, credit, user, tuser, date', "0, 'sendsms', $cost, 0, '$ncount', '$now'");
					
					//Send Email
					$to = $cemail;
//$subject = 'SMS Report';
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Your card was sent successfully to $ncount recipients</p>

<p><strong>Message Count:</strong> $mcount<br /></p>
<p><strong>Cost:</strong> $cost<br /></p>";

$message .= "</body></html>";
mail($to, $subject, $message, $headers);

					//echo $nval->error_msg;
					header("Location: card.php?xsuccess=".$xsend->success_msg."&sncount=$ncount&smcount=$mcount&scost=$cost");
    exit;
				}
			
			}//xsend success
		/*}//max recipients
		else
		{
			$sval->error_code = 17;
				$sval->error_msg = error($sval->error_code);
		}*/
			}//ncount
			else
			{
				$sval->error_code = 15;
				$sval->error_msg = error($sval->error_code);
			}
			}//error condition

	}
}

if($save)
{
	if($xedit != '')
	{
		$check = new select();
		$check->pick('card', '*', 'id,user', "$xedit,0", '', 'record', '', '', '=,=', 'and');
		if($check->count < 1)
		{
			header("location: card.php");
			exit;
		}
	}
	$sval = new validate();
	$sval->no_numeric($title, "Title");
	$sval->valid("$title,$name,$tell,$url,$company,$description");
	if($sval->error_code < 1)
	{
		if($xedit == '')
		{
		$in = new insert();
		$in->input('card', 'id, title, name, tell, url, company, description, user', "0, '$title', '$name', '$tell', '$url', '$company', '$description', 0");
		}
		else
		{
			$in = new update();
			$in->up('card', 'title', 'id', "$xedit", "'$title'");
			$in->up('card', 'name', 'id', "$xedit", "'$name'");
			$in->up('card', 'tell', 'id', "$xedit", "'$tell'");
			$in->up('card', 'url', 'id', "$xedit", "'$url'");
			$in->up('card', 'company', 'id', "$xedit", "'$company'");
			$in->up('card', 'description', 'id', "$xedit", "'$description'");
		}
		//echo mysql_error();
		$success = success(1);
		header("location: card.php?success=$success");
		exit;
		
	}
}

if($delete)
{
		$check = new select();
		$check->pick('card', '*', 'id,user', "$delete,0", '', 'record', '', '', '=,=', 'and');
		if($check->count < 1)
		{
			header("location: card.php");
			exit;
		}
		else
		{
			$del = new delete();
			$del->gone('card', 'id', "$delete");
			$success = success(1);
			header("location: card.php?success=$success");
		}
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

    <title>Business Card</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
  <li><a href="index.php">DASHBOARD</a></li>
  <li class="active">BUSINESS CARD</li>
</ol>
 <h4>Business Card</h4>
 <?php
 if($new == '' && $cid == '' && $xedit == '' && $view == '')
 {
	 ?>
 <p><a href="card.php?new=yes" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> New Card</a></p>
  <?php
 }
  if($sval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sval->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($xsend->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$xsend->error_msg."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	if($xsuccess != '')
	{
	echo "<div class='alert alert-success'><p>Card sent to $sncount recipients</p>
	<p><strong>Message Count:</strong> $smcount</p><p>
	<strong>Cost:</strong> $scost Units
	</p>
	<p>
	$xht
	</p>
	</div>";
	}
	if($new != '' || $cid != '' || $xedit != '')
	{
	if($success == '')
	{
	?>
 <form action="card.php?cid=<?php echo $cid;?>&new=<?php echo $new;?>&xedit=<?php echo $xedit;?>" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
 <?php
 if($cid != '')
 {
	 ?>
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Recipients<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  
  <div class="panel panel-default">
  <div class="panel-body">
  <div class="form-group">
    <label for="tnum" class="col-lg-10 control-label"><p align="left">Enter/Paste numbers separated by comma(,) OR Enter/Paste numbers line by line <a href="../images/number_format.png">View Example</a></small></p></label>
    <div class="col-lg-10">
     <textarea class="form-control input-sm" rows="5" id="tnum" name="tnum"><?php
      if($success == '')
	  {
		  echo stripslashes($tnum);
	  }
	  ?></textarea>
    </div>
  </div>
  </div><!--panel1 body-->
  </div><!--panel1-->
 
  </div>
  </div>
            
  <table cellpadding="5">
  <tr>
  <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="send" class="btn btn-success" value="Send">
    </div>
  </div>
  </td>
  </tr>
  </table>
  <?php
 }
 elseif($new != '' || $xedit != '')
 {
	 ?>
     <table width="70%">
     <tr>
     <td>
     <?php
	 if($xedit != '')
	 {
		 //get info
		 $esel = new select();
		 $esel->pick('card', 'title, name, tell, url, company, description', 'id', "$xedit", '', 'record', '', '', '=', '');
		 $esel_row = mysql_fetch_row($esel->query);
	 }
	 ?>
      <div class="form-group">
    <label for="title" class="control-label">Title*:</label>
      <input name="title" type="text" class="form-control input-sm" id="title" value="<?php
	  if($xedit != '')
	  {
		  echo $esel_row[0];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($title);
	  }
	  ?>" maxlength="11" placeholder="Title(11 alpha numeric Characters)">
  </div>
  
  <div class="form-group">
    <label for="name" class="control-label">Name*:</label>
      <input name="name" type="text" class="form-control input-sm" id="name" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[1];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($name);
	  }
	  ?>" placeholder="Name">
  </div>
  
   <div class="form-group">
    <label for="tell" class="control-label">Phone*:</label>
      <input name="tell" type="text" class="form-control input-sm" id="tell" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[2];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($tell);
	  }
	  ?>" placeholder="Phone">
  </div>
  
   <div class="form-group">
    <label for="url" class="control-label">Website*:</label>
      <input name="url" type="text" class="form-control input-sm" id="url" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[3];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($url);
	  }
	  ?>" placeholder="URL">
  </div>
  
   <div class="form-group">
    <label for="company" class="control-label">Company*:</label>
      <input name="company" type="text" class="form-control input-sm" id="company" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[4];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($company);
	  }
	  ?>" placeholder="Company">
  </div>
  
  <div class="form-group">
    <label for="description" class="control-label">Enter Description*:</label>
     <textarea class="form-control input-sm" rows="5" id="description" name="description"><?php
      if($xedit != '')
	  {
		  echo $esel_row[5];
	  }
      elseif($success == '')
	  {
		  echo stripslashes($description);
	  }
	  ?></textarea>
    </div>
    
    <div class="form-group">
      <input type="submit" name="save" class="btn btn-success" value="Save">
  </div>
  </td>
  </tr>
  </table>
     <?php
 }
 ?>
    </form>
  <?php
	}
	}//new|cid
	else
	{
		if($view == '')
		{
		//get cards
		$gc = new select();
		$gc->pick('card', 'id, title, name, tell, url, company, description', 'user', "0", '', 'record', 'title', '', '=', '');
		if($gc->count > 0)
		{
			?>
            <div class="table-responsive">
            <table class="table">
            <tr>
            <th>TITLE</th>
            <th>SMS PAGE COUNT</th>
             <th>SEND</th>
              <th>EDIT</th>
               <th>DELETE</th>
            </tr>
            <?php
			while($gc_row = mysql_fetch_row($gc->query))
			{
				//get page count
				$count = strlen($gc_row[1]) + strlen($gc_row[2]) + strlen($gc_row[3]) + strlen($gc_row[4]) + strlen($gc_row[5]) + strlen($gc_row[6]);
		$xcount = ceil($count/160);
				?>
             <tr>
             <td><a href="card.php?view=<?php echo $gc_row[0];?>"><?php echo $gc_row[1];?></a></td>
             <td><?php echo $xcount;?></td>
             <td><a href="card.php?cid=<?php echo $gc_row[0];?>">Send</a></td>
             <td><a href="card.php?xedit=<?php echo $gc_row[0];?>">Edit</a></td>
             <td><a href="card.php?delete=<?php echo $gc_row[0];?>">Delete</a></td>
             </tr>   
                <?php
			}
			?>
            </table>
            </div>
            <?php
		
		}
		else
		{
			echo 'No Cards';
		}
		}//view
		else
		{
			?>
       <p><a href="card.php"><span class="glyphicon glyphicon-backward"></span> Back</a> </p>    
            <?php
			//get info
			$cinfo = new select();
			$cinfo->pick('card', 'title, name, tell, url, company, description', 'id', "$view", '', 'record', '', '', '=', '');
			$cinfo_row = mysql_fetch_row($cinfo->query);
			echo "<p><strong>TITLE: </strong>$cinfo_row[0]<br />
			<strong>NAME: </strong>$cinfo_row[1]<br />
			<strong>PHONE: </strong>$cinfo_row[2]<br />
			<strong>WEBSITE: </strong>$cinfo_row[3]<br />
			<strong>COMPANY: </strong>$cinfo_row[4]<br />
			<strong>DESCRIPTION: </strong><br />$cinfo_row[5]<br />
			</p>";
		}
	}
	
	?>

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

<script>
$(document).ready(function(){
    var $remaining = $('#remaining'),
        $messages = $remaining.next();

    $('#message').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>