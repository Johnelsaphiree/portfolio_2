<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aauto';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

require '../Classes/PHPExcel.php';
require_once '../Classes/PHPExcel/IOFactory.php';

$new = $_GET['new'];
$step = $_GET['step'];
$code = $_GET['code'];
$success = $_GET['success'];
$view = $_GET['view'];
$delete = $_GET['delete'];
$xsid = $_GET['xsid'];
$xdelete = $_GET['xdelete'];
$xedit = $_GET['xedit'];
$sdel = $_GET['sdel'];

$name = isset($_POST['name']) ? $_POST['name'] : '';
$mno = isset($_POST['mno']) ? $_POST['mno'] : '';
$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
$continue = isset($_POST['continue']) ? $_POST['continue'] : '';
$edit = isset($_POST['edit']) ? $_POST['edit'] : '';
$emessage = isset($_POST['emessage']) ? $_POST['emessage'] : '';
$ewhen = isset($_POST['ewhen']) ? $_POST['ewhen'] : '';
$etime = isset($_POST['etime']) ? $_POST['etime'] : '';
$esid = isset($_POST['esid']) ? $_POST['esid'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

if($step == 2)
{
	$xmno = $_SESSION['amno'];
	$xname = $_SESSION['aname'];
	$xsid = $_SESSION['asid'];
	
	for($x = 0; $x < $xmno; $x++)
	{
		$fmessage = "message$x";
		$fwhen = "when$x";
		$ftime = "time$x";
		$fmessage = $_POST["$fmessage"];
		$fwhen = $_POST["$fwhen"];
		$ftime = $_POST["$ftime"];
	}
$gen = isset($_POST['gen']) ? $_POST['gen'] : '';
}

if($continue)
{
	$val = new validate();
	$val->valid("$name,$sid");
	if($val->error_code < 1)
	{
		$_SESSION['aname'] = $name;
		$_SESSION['asid'] = $sid;
		$_SESSION['amno'] = $mno;
		header("location: auto.php?new=yes&step=2");
	}
}

if($gen)
{
	$xmno = $_SESSION['amno'];
	for($x = 0; $x < $xmno; $x++)
	{
		$fmessage = "message$x";
		$fwhen = "when$x";
		$ftime = "time$x";
		$fmessage = $_POST["$fmessage"];
		$fwhen = $_POST["$fwhen"];
		$ftime = $_POST["$ftime"];

		if(trim($fmessage) == '')
		{
			$error = error(1);
		}
		elseif(trim($fwhen) == '')
		{
			$error = error(1);
		}
		elseif(trim($ftime) == '')
		{
			$error = error(1);
		}
	}//for loop
	if($error == '')
	{
		$in = new insert();
		$in->input('form', 'id, name, mno, sid, user', "0, '$xname', $xmno, '$xsid', $auser");
		//get max id
		$max = new select();
		$max->pick('form', 'max(id)', 'user', "$auser", '', 'record', '', '', '=', '');
		$max_row = mysql_fetch_row($max->query);
		
		for($x = 0; $x < $xmno; $x++)
	{
		$fmessage = "message$x";
		$fwhen = "when$x";
		$ftime = "time$x";
		$fmessage = $_POST["$fmessage"];
		$fwhen = $_POST["$fwhen"];
		$ftime = $_POST["$ftime"];
		$imessage = $fmessage;
		$iwhen = $fwhen;
		$itime = $ftime;
		
		$in->input('form_item', 'id, fmessage, fwhen, ftime, form', "0, '$imessage', '$iwhen', '$itime', $max_row[0]");
		$success = success(13);
		header("location: auto.php?code=$max_row[0]&success=$success");
	}//for loop
	}
}

if($edit)
{
	$val = new validate();
	$val->valid($emessage);
	if($val->error_code < 1)
	{
		$up = new update();
		$up->up('form_item', 'fmessage', 'id', "$xedit", "'$emessage'");
		$up->up('form_item', 'fwhen', 'id', "$xedit", "'$ewhen'");
		$up->up('form_item', 'ftime', 'id', "$xedit", "'$etime'");
		
		$success = success(15);
		header("location: auto.php?view=$view&success=$success");
		exit;
	}
}

if($xdelete != '')
{
	$del = new delete();
	$del->gone('form_item', 'id', "$xdelete");
		
		$success = success(16);
		header("location: auto.php?view=$view&success=$success");
		exit;
}

if($save)
{
	$val = new validate();
	$val->valid($esid);
	if($val->error_code < 1)
	{
		$up = new update();
		$up->up('form', 'sid', 'id', "$xsid", "'$esid'");
		$success = success(15);
		header("location: auto.php?view=$view&success=$success");
		exit;
	}
}

if($delete != '')
{
	$del = new delete();
	$del->gone('form', 'id', "$delete");
	$del->gone('form_item', 'form', "$delete");
	$del->gone('form_recipient', 'form', "$delete");
	$sdel = success(17);
	header("location: auto.php?sdel=$sdel");
}

if($view != '' && $xedit == '' && $xsid == '')
{
	$records = 20;
if (!$_GET['start'])
{
$start = 0;
}
else
{
$start = $_GET['start'];
}

		  //get recipients
		  $gr = new select();
		  $gr->pick('form_recipient', 'name, tell', 'form', "$view", '', 'record', '', '', '=', '');
		  $xsel = new select();
	$xsel->pick('form_recipient', 'name, tell', 'form', "$view", "$start, $records", 'record', '', '', '=', '');
	$int = $_GET['int'];
	
	$total = $gr->count;
	$result = $xsel->count;
	
	$a = $start + 1;
$b = $start + $xsel->count;
$c = ceil($total / $records);
}

$export = $_POST['export'];
if($export)
{		
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Megzy")
                ->setLastModifiedBy("Megzy")
                ->setTitle("Data Export")
                ->setSubject("Data Export")
                ->setDescription("Data Export")
                ->setKeywords("Data Export")
                ->setCategory("Data Export");
				
$objPHPExcel->getActiveSheet()->setTitle("Data Export");
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NAME')
            ->setCellValue('B1', 'PHONE');
			
				//poplulate records
				if($gr->count > 0)
				{
					$z = 2;
					while($gr_row = mysql_fetch_row($gr->query))
					{
			
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$z, "$gr_row[0]")
			->setCellValue('B'.$z, "$gr_row[1]");
			
			$z = $z + 1;
					}
				}
		
// If you want to output e.g. a PDF file, simply do:
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
@unlink('../export/elist.xls');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
$objWriter->save('../export/elist.xls');

header('location: ../export/elist.xls');
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

    <title>Auto Responder</title>
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
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
    </script>
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
  <li class="active">AUTO RESPONDER</li>
</ol>
<?php
if($new == '' && $code == '')
{
	?>
<p><a href="auto.php?new=yes" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> New Campaign</a></p><br />
<?php
if($sdel != '')
{
	?>
 <div class="alert alert-success"><?php echo $sdel;?></div>   
    <?php
}
//get forms
$gform = new select();
$gform->pick('form', 'id, name', 'user', "$auser", '', 'record', 'name', '', '=', '');
 if($gform->count > 0)
  {
?>
<form name="form" id="form" role="form">
<label for="formjump" class="control-label">Select Form:</label>
  <select name="formjump" id="formjump" onChange="MM_jumpMenu('parent',this,0)" class="form-control">
  <?php
  if($view != '')
  {
	  //get name
	  $vname = new select();
	  $vname->pick('form', 'name', 'id', "$view", '', 'record', '', '', '=', '');
	  $vname_row = mysql_fetch_row($vname->query);
	  ?>
     <option value="auto.php?view=<?php echo $view;?>"><?php echo $vname_row[0];?></option>  
      <?php
  }
  else
  {
	  ?>
     <option value="auto.php">Select form</option>  
      <?php 
  }
  while($gform_row = mysql_fetch_row($gform->query))
  {
	  ?>
    <option value="auto.php?view=<?php echo $gform_row[0];?>"><?php echo $gform_row[1];?></option>
    <?php
  }
  ?>
  </select>
</form>
<?php
  }
  else
  {
	 echo "<em>No forms</em>"; 
  }
  
  if($view != '')
  {
	  //get form name
	  $fn = new select();
	  $fn->pick('form', 'name', 'id', "$view", '', 'record', '', '', '=', '');
	  $fn_row = mysql_fetch_row($fn->query);
	  ?>
      <h4><?php echo $fn_row[0];?></h4>
      <?php
	  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
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
      <p><a href="auto.php?delete=<?php echo $view;?>" class="btn btn-danger">Delete form</a> <a href="auto.php?code=<?php echo $view;?>"  class="btn btn-info">Get Code</a> <?php
      if($xsid == '')
	  {
	  ?><a href="auto.php?xsid=<?php echo $view;?>&view=<?php echo $view;?>" class="btn btn-primary">Change SenderID</a><?php
	  }
	  ?> <a href="auto_import.php?view=<?php echo $view;?>" class="btn btn-warning">Import List</a></p>
      <?php
	  if($xedit == '' && $xsid == '')
	  {
	  $fitem = new select();
	  $fitem->pick('form_item', 'id, fmessage, fwhen, ftime', 'form', "$view", '', 'record', '', '', '=', '');
	  if($fitem->count < 1)
	  {
		  echo 'No messages';
	  }
	  else
	  {
		  $int = 1;
		  ?>
         <div class="table-responsive">
         <table class="table table-striped">
         <tr>
         <th>MESSAGE</th>
         <th>SCHEDULE</th>
         <th>ACTION</th>
         </tr>
         <?php
		 while($fitem_row = mysql_fetch_row($fitem->query))
		 {
			 ?>
             <tr>
             <td><?php echo substr($fitem_row[1], 0, 15);?>...</td>
             <td><?php echo $fitem_row[2].' @ '.$fitem_row[3];?></td>
             <td><a href="auto.php?xedit=<?php echo $fitem_row[0];?>&view=<?php echo $view;?>">Edit</a> | <a href="auto.php?xdelete=<?php echo $fitem_row[0];?>&view=<?php echo $view;?>">Delete</a></td>
             </tr>
             <?php
		 }
		 ?>
         </table>
         </div><!--table responsive--> 
  <?php
	  }
	  ?>
	  <h4>Recipients</h4>
          <?php
	
	  if($xsel->count < 1)
	  {
		  echo 'No recipients';
	  }
	  else
	  {
		  ?>
          <h5><?php echo $a.'-'.$b.' of '.$total;?> RECIPIENTS</h5>
           <form class="form-inline" role="form" name="export_form" method="post" action="">
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="export" class="btn btn-success" value="Export List">
    </div>
  </div>
        </form><br />
         <div class="table-responsive">
         <table class="table table-striped">
         <tr>
         <th>NAME</th>
         <th>PHONE</th>
         </tr>
         <?php
	while($xrow = mysql_fetch_row($xsel->query))
	{
			 ?>
             <tr>
             <td><?php echo $xrow[0];?>...</td>
             <td><?php echo $xrow[1];?></td>
             </tr>
             <?php
	}
		 ?>
        
         </table>
         </div><!--table responsive--> 
          <ul class="pager">
<?php
if ($start >= $records && $start > 0)
		{
			?>
  <li><a href="auto.php?start=<?php echo $start - $records;?>&int=<?php echo $int - 1;?>&view=<?php echo $view;?>">Previous</a></li>
  <?php
		}
		if (($start + $records) < $total)
		{
			?>
  <li><a href="auto.php?start=<?php echo $start + $records;?>&int=<?php echo $int + 1;?>&view=<?php echo $view;?>">Next</a></li>
  <?php
		}

        ?>
        </ul>
         
         <?php
	  }
	  
	  }//xedit
	  elseif($xedit != '')
	  {
	
	//get info
	$einfo = new select();
	$einfo->pick('form_item', 'fmessage, fwhen, ftime', 'id', "$xedit", '', 'record', '', '', '=', '');
	$einfo_row = mysql_fetch_row($einfo->query);
	?>
 <form action="auto.php?xedit=<?php echo $xedit;?>&view=<?php echo $view;?>" method="post" enctype="multipart/form-data" name="campaign_form" class="form-horizontal" role="form">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Edit<span class="text-primary"></span></h3>
  </div>
  <table cellpadding="20" width="100%">
  <tr>
  <td>
      <div class="form-group">
    <label for="emessage" class="control-label">Enter/Paste Message*:</label> 
     <textarea class="form-control" rows="5" id="emessage" name="emessage"><?php
     echo $einfo_row[0];
	 ?></textarea>
     <br />
    <span id="remaining">160 characters remaining</span>
    <span id="messages">1 message(s)</span>
    </div>
   
  
  <div class="form-group">
    <label for="when" class="control-label" >When*:</label>
  <select class="form-control" name="ewhen" id="ewhen">
       <option value="<?php echo $einfo_row[1];?>"><?php echo $einfo_row[1];?></option>  
  <option value="Instantly">Instantly</option>
  <option value="1 day later">1 day later</option>
  <option value="2 days later">2 days later</option>
  <option value="3 days later">3 days later</option>
  <option value="4 days later">4 days later</option>
  <option value="5 days later">5 days later</option>
  <option value="6 days later">6 days later</option>
  <option value="7 days later">7 days later</option>
  <option value="8 days later">8 days later</option>
  <option value="9 days later">9 days later</option>
  <option value="10 days later">10 days later</option>
  <option value="11 days later">11 days later</option>
  <option value="12 days later">12 days later</option>
  <option value="13 days later">13 days later</option>
  <option value="14 days later">14 days later</option>
  <option value="15 days later">15 days later</option>
  <option value="16 days later">16 days later</option>
  <option value="17 days later">17 days later</option>
  <option value="18 days later">18 days later</option>
  <option value="19 days later">19 days later</option>
  <option value="20 days later">20 days later</option>
</select>
  </div>
  
   <div class="form-group">
    <label for="time" class="control-label" >Time*:</label>
  <select class="form-control" name="etime" id="etime">
       <option value="<?php echo $einfo_row[2];?>"><?php echo $einfo_row[2];?></option>  
       
  <option value="1am">1am</option>
  <option value="2am">2am</option>
  <option value="3am">3am</option>
  <option value="4am">4am</option>
  <option value="5am">5am</option>
  <option value="6am">6am</option>
  <option value="7am">7am</option>
  <option value="8am">8am</option>
  <option value="9am">9am</option>
  <option value="10am">10am</option>
  <option value="11am">11am</option>
  <option value="1pm">1pm</option>
  <option value="2pm">2pm</option>
  <option value="3pm">3pm</option>
  <option value="4pm">4pm</option>
  <option value="5pm">5pm</option>
  <option value="6pm">6pm</option>
  <option value="7pm">7pm</option>
  <option value="8pm">8pm</option>
  <option value="9pm">9pm</option>
  <option value="10pm">10pm</option>
  <option value="11pm">11pm</option>
</select>
  </div>
  
  <div class="form-group">
      <input type="submit" name="edit" class="btn btn-primary" value="Edit">
  </div> 
  </td>
  </tr>
  </table>
  </div>
  </div>
    </form> 
          <?php
	  }
	  elseif($xsid != '')
	  {
		  //get sender
		  $gs = new select();
		  $gs->pick('form', 'sid', 'id', "$xsid", '', 'record', '', '', '=', '');
		  $gs_row = mysql_fetch_row($gs->query);
		  ?>
      <form action="auto.php?view=<?php echo $view;?>&xsid=<?php echo $xsid;?>" method="post" enctype="multipart/form-data" name="campaign_form" class="form-horizontal" role="form">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Change Sender ID<span class="text-primary"></span></h3>
  </div>
  <table cellpadding="20" width="100%">
  <tr>
  <td>
  <div class="form-group">
    <label for="sid" class="control-label">Sender ID*:</label>
      <input name="esid" type="text" class="form-control input-sm" id="esid" value="<?php echo $gs_row[0];?>" placeholder="Sender ID" maxlength="11">
  </div>
  
  <div class="form-group">
      <input type="submit" name="save" class="btn btn-warning" value="Save">
  </div>
  </td>
  </tr>
  </table>
  </div>
    </form>    
          <?php
	  }
  }

}
elseif($code == '')
{
	?>
    <h4>New Campaign</h4>
    <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
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
 <form action="auto.php?new=<?php echo $new;?>&step=<?php echo $step;?>" method="post" enctype="multipart/form-data" name="campaign_form" class="form-horizontal" role="form">
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php
    if($step == 2)
	{
		echo 'Step 2';
	}
	else
	{
		echo 'Step 1';
	}
	?><span class="text-primary"></span></h3>
  </div>
  <table cellpadding="20" width="100%">
  <tr>
  <td>
  <?php
  if($step == '')
  {
	  ?>
  <div class="form-group">
    <label for="name" class="control-label">Name*:</label>
      <input name="name" type="text" class="form-control input-sm" id="name" value="" placeholder="Name">
  </div>
  
  <div class="form-group">
    <label for="mno" class="control-label" >Number of message*:</label>
  <select class="form-control" name="mno" id="mno">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10</option>
</select>
  </div>
  
  <div class="form-group">
    <label for="sid" class="control-label">Sender ID*:</label>
      <input name="sid" type="text" class="form-control input-sm" id="sid" value="" placeholder="Sender ID" maxlength="11">
  </div>
  
  <div class="form-group">
      <input type="submit" name="continue" class="btn btn-warning" value="Continue">
  </div>
  <?php
  }
  else
  {
	  for($x = 0; $x < $xmno; $x++)
	  {
	  ?>
      <div class="well">
      <div class="form-group">
    <label for="message" class="control-label">Enter/Paste Message*:</label> 
     <textarea class="form-control" rows="5" id="message<?php echo $x;?>" name="message<?php echo $x;?>"><?php
     if($error != '')
	 {
		 $fm = 'message'.$x;
		 echo $$fm;
	 }
	 ?></textarea>
      <br />
    <span id="remaining<?php echo $x;?>">160 characters remaining</span>
    <span id="messages<?php echo $x;?>">1 message(s)</span>
    </div>
  
  <div class="form-group">
    <label for="when" class="control-label" >When*:</label>
  <select class="form-control" name="when<?php echo $x;?>" id="when<?php echo $x;?>">
  <?php
     if($error != '')
	 {
		 $fw = 'when'.$x;
		 ?>
       <option value="<?php echo $$fw;?>"><?php echo $$fw;?></option>  
         <?php
	 }
	 ?>
  <option value="Instantly">Instantly</option>
  <option value="1 day later">1 day later</option>
  <option value="2 days later">2 days later</option>
  <option value="3 days later">3 days later</option>
  <option value="4 days later">4 days later</option>
  <option value="5 days later">5 days later</option>
  <option value="6 days later">6 days later</option>
  <option value="7 days later">7 days later</option>
  <option value="8 days later">8 days later</option>
  <option value="9 days later">9 days later</option>
  <option value="10 days later">10 days later</option>
  <option value="11 days later">11 days later</option>
  <option value="12 days later">12 days later</option>
  <option value="13 days later">13 days later</option>
  <option value="14 days later">14 days later</option>
  <option value="15 days later">15 days later</option>
  <option value="16 days later">16 days later</option>
  <option value="17 days later">17 days later</option>
  <option value="18 days later">18 days later</option>
  <option value="19 days later">19 days later</option>
  <option value="20 days later">20 days later</option>
</select>
  </div>
  
   <div class="form-group">
    <label for="time" class="control-label" >Time*:</label>
  <select class="form-control" name="time<?php echo $x;?>" id="time<?php echo $x;?>">
   <?php
     if($error != '')
	 {
		 $ft = 'time'.$x;
		 ?>
       <option value="<?php echo $$ft;?>"><?php echo $$ft;?></option>  
         <?php
	 }
	 ?>
  <option value="1am">1am</option>
  <option value="2am">2am</option>
  <option value="3am">3am</option>
  <option value="4am">4am</option>
  <option value="5am">5am</option>
  <option value="6am">6am</option>
  <option value="7am">7am</option>
  <option value="8am">8am</option>
  <option value="9am">9am</option>
  <option value="10am">10am</option>
  <option value="11am">11am</option>
  <option value="1pm">1pm</option>
  <option value="2pm">2pm</option>
  <option value="3pm">3pm</option>
  <option value="4pm">4pm</option>
  <option value="5pm">5pm</option>
  <option value="6pm">6pm</option>
  <option value="7pm">7pm</option>
  <option value="8pm">8pm</option>
  <option value="9pm">9pm</option>
  <option value="10pm">10pm</option>
  <option value="11pm">11pm</option>
</select>
  </div>
  </div><!--well-->
  <?php
	  }
	  ?>
  <div class="form-group">
      <input type="submit" name="gen" class="btn btn-primary" value="Generate">
  </div> 
      <?php
  }
  ?>
  </td>
  </tr>
  </table>
  </div>
  </div>
    </form>
    <?php
}
elseif($code != '')
{
	?>
    <h4>Get Code</h4>
    <?php
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	//get code
	
	?>
	 <div class="form-group">
    <label for="fcode" class="control-label">Copy and paste code in your website:</label> 
     <textarea class="form-control" rows="10" id="fcode" name="fcode">
	<script type="text/javascript">

	function validateForm() {
		var name = document.forms["myForm"]["rec_name"].value;
		var phone = document.forms["myForm"]["msg_rec"].value;
		if(name != '' && phone != '')
		{
			return true;
		}
		else
		{	
			alert( "Please enter your name and Phone Number" );
			return false;
		}
	}
</script>

<form method='post' action="<?php echo $csite_url.'/form.php';?>" class='user_form' enctype='multipart/form-data' name="myForm" onsubmit="return validateForm()"> 

<input type='hidden' name='fid' value="<?php echo $code;?>" class='ppdia_textBox' />
		<table align='center' class='ppdia_table' >
			
			<tr  >
				<td  ><label for='rec_name' class='ppdia_label'>Name</label></td><td  ><input type='text' name='rec_name'class='ppdia_textBox' /></td>
			</tr>
			<tr  >
				<td  ><label for='rec_name' class='ppdia_label'>Phone Number</label></td><td  ><input type='text' name='msg_rec'class='ppdia_textBox' /><br />
           </td>
			</tr>
			<tr  >
				<td colspan='2' ><button type='submit' id='validate'   >Send</button>
</td>
			</tr>
		</table></form></div>
        
        </textarea>
    </div>
  
  <a href="auto.php" class="btn btn-info">Finish</a>
      <?php
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

    $('#emessage').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining0'),
        $messages = $remaining.next();

    $('#message0').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining1'),
        $messages = $remaining.next();

    $('#message1').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining2'),
        $messages = $remaining.next();

    $('#message2').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining3'),
        $messages = $remaining.next();

    $('#message3').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining4'),
        $messages = $remaining.next();

    $('#message4').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining5'),
        $messages = $remaining.next();

    $('#message5').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining6'),
        $messages = $remaining.next();

    $('#message6').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining7'),
        $messages = $remaining.next();

    $('#message7').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining8'),
        $messages = $remaining.next();

    $('#message8').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script>
$(document).ready(function(){
    var $remaining = $('#remaining9'),
        $messages = $remaining.next();

    $('#message9').keyup(function(){
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