<?php
$page = 'aaddress';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$option = $_GET['option'];
$cadd = $_GET['cadd'];
$cedit = $_GET['cedit'];
$cdelete = $_GET['cdelete'];
$success = $_GET['success'];
$ssin = $_GET['ssin'];
$ssql = $_GET['ssql'];

$name = $_POST['name'];
$tell = $_POST['tell'];
$add = $_POST['add'];
$path = $_FILES['ufile']['tmp_name'];
$path_size = $_FILES['ufile']['size'];
$path_type = $_FILES['ufile']['type'];
$max = 2000000;
$upload = $_POST['upload'];
$edit = $_POST['edit'];
$gname = $_POST['gname'];

$sval = new validate();
$sin = new insert();
$sup = new update();

$ncount = '';

if($add)
{
	$snval = new number_val();
	if($option == 'scontact')
	{
	$snval->length($tell, 'single');
	}
	elseif($option == 'gcontact')
	{
		//numbers are cleaned up also
		$snval->length($tell, 'group');
	}
	if($snval->error_code > 0)
	{
		$sval->error_code = $snval->error_code;
		$sval->error_msg = error($sval->error_code);
	}

//max group count
$ncount = $snval->cnumber;
/*if($ncount > 100000)
{
	$sval->error_code = 19;
	$sval->error_msg = error($sval->error_code);
}*/

	if($option == 'scontact')
	{
	$sval->numeric($tell, 'Phone');
	$sval->valid("$name,$tell");
	}
	elseif($option == 'gcontact')
	{
		$sval->valid("$name,$tell");
	}
	
	if($sval->error_code < 1 && $snval->error_code < 1)
	{
		if($option == 'scontact')
		{
		$snclean = new number_clean();
		$snclean->fresh($tell, 'single');
		$otell = $snclean->rnumber;
		}
		elseif($option == 'gcontact')
		{
			$otell = $snval->vnumber;
		}
		
		if($otell == '')
		{
			$sval->error_code = 15;
			$sval->error_msg = error($sval->error_code);
		}
		else
		{
		if($option == 'scontact')
		{
		$sin->input('contact', 'id, name, phone, type, user', "0, '$name', '$otell', 'single', $auser");
		}
		elseif($option == 'gcontact')
		{
		$sin->input('contact', 'id, name, phone, type, user', "0, '$name', '$otell', 'group', $auser");
		}
		$ssin = $sin->success_msg.' '.$ncount.' numbers uploaded';
		header("Location: " . $_SERVER["REQUEST_URI"]."&ssin=$ssin");
    exit;
		}
	}
}

if($edit)
{
	$snval = new number_val();
	if($option == 'scontact')
	{
	$snval->length($tell, 'single');
	}
	elseif($option == 'gcontact')
	{
		$snval->length($tell, 'group');
	}
	
	if($snval->error_code > 0)
	{
		$sval->error_code = $snval->error_code;
		$sval->error_msg = error($sval->error_code);
	}
	//max group count
$ncount = $snval->cnumber;
/*if($ncount > 100000)
{
	$sval->error_code = 19;
	$sval->error_msg = error($sval->error_code);
}*/

	if($option == 'scontact')
	{
	$sval->numeric($tell, 'Phone');
	}
	
	$sval->valid("$name,$tell");
	
	//check whether user has right
	$eu = new select();
	$eu->pick('contact', '*', 'id,user', "'$cedit','$auser'", '', 'record', '', '', '=,=', 'and');
	if($eu->count > 0)
	{
	if($sval->error_code < 1 && $snval->error_code < 1)
	{
		if($option == 'scontact')
		{
		$snclean = new number_clean();
		$snclean->fresh($tell, 'single');
		$otell = $snclean->rnumber;
		}
		elseif($option == 'gcontact')
		{
			$otell = $snval->vnumber;
		}
		if($otell == '')
		{
			$sval->error_code = 15;
			$sval->error_msg = error($sval->error_code);
		}
		else
		{
		$sup->up('contact', 'name', 'id', "'$cedit'", "'$name'");
		$sup->up('contact', 'phone', 'id', "'$cedit'", "'$otell'");
		}
	}
	}
	else
	{
		$sval->error_code = 21;
		$sval->error_msg = error($sval->error_code);
	}
}

if($upload)
{
	$bval = new validate();
		$bval->valid("$path,$gname");
			
	if($bval->error_code < 1)
	{		
	$sql = new insert();
	$error = '';
	$gnum = '';
	
	$fval = new file_validate();
	$fval->valid($path_type, $path_size, $max, 'text');
	if($fval->error_code < 1)
	{
		$filex = file_get_contents($path);
$bnval = new number_val();
$bnval->length($filex, 'group');
$gnum = $bnval->vnumber;
$ncount = $bnval->cnumber;
	}
	else
	{
		$error = $fval->error_msg;
	}
if($error == '')
{
/*if($ncount > 100000)
{
	$bval->error_code = 19;
	$bval->error_msg = error($bval->error_code);
}
else
{*/
//add group
	if($gnum == '')
		{
			$bval->error_code = 15;
			$bval->error_msg = error($bval->error_code);
		}
		else
		{
	$sql->input('contact', 'id, name, phone, type, user', "0, '$gname', '$gnum', 'group', $auser");
		}

if($sql->success_code < 1)
{
	$bval->error_code = $bnval->error_code;
	$bval->error_msg = error($bval->error_code);
}
else
{
	$ssql = $sql->success_msg.' '.$ncount.' numbers uploaded';
	header("Location: " . $_SERVER["REQUEST_URI"]."&ssql=$ssql");
    exit;
}

//}//ncount
}//error
	}//bval
}


if($cdelete != '')
{
	$sdel = new delete();
	$sdel->gone('contact', 'id', "'$cdelete'");
	if($start == 0)
	{
		$int = 1;
	}
	header("location: address.php?option=$option&start=$start&int=$int&success=Successful");
}

if($cadd == '' && $option != '')
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

$sel = new select();
if($option == 'scontact')
{
$sel->pick('contact', '*', 'type,user', "'single',$auser", '', 'record', '', '', '=,=', 'and');
$xsel = new select();
	$xsel->pick('contact', 'id, name, phone', 'type,user', "'single',$auser", "$start, $records", 'record', 'name', '', '=,=', 'and');
}
elseif($option == 'gcontact')
{
	$sel->pick('contact', '*', 'type,user', "'group',$auser", '', 'record', '', '', '=,=', 'and');
$xsel = new select();
	$xsel->pick('contact', 'id, name, phone', 'type,user', "'group',$auser", "$start, $records", 'record', 'name', '', '=,=', 'and');
}
	
	$total = $sel->count;
	$result = $xsel->count;
	
$int = $_GET['int'];
$goto = $_POST['goto'];
$go = $_POST['go'];
if($go)
{
$start = ($goto * $records) - $records;
if($goto < 1)
{
	$start = 0;
	$goto = 1;
}
header("location: address.php?start=$start&int=$goto&option=$option");
}

//incase you delete the last item in a page, to initialise to the previous...
if($total > 0 && $result < 1)
	{
		$start = $start - $records;
		$int = $int - 1;
		$xsel = new select();
		if($option == 'scontact')
		{
$xsel->pick('contact', 'id, name, phone', 'type,user', "'single',$auser", "$start, $records", 'record', 'name', '', '=,=', 'and');
		}
		elseif($option == 'gcontact')
		{
$xsel->pick('contact', 'id, name, phone', 'type,user', "'group',$auser", "$start, $records", 'record', 'name', '', '=,=', 'and');
		}
	}

$a = $start + 1;
$b = $start + $xsel->count;
$c = ceil($total / $records);
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

    <title>Address book</title>
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
  <?php
  if($cadd == '' && $cedit == '' && $option == '')
  {
	  ?>
  <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li class="active">ADDRESS BOOK</li>
</ol>
<?php
  }
  
if($option == '')
{
?>
 <!--option-->
 <h4>Select Option:</h4>
<div class="list-group text-center">
  <a href="address.php?option=scontact&int=1" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="glyphicon glyphicon-user text-primary"></span> CONTACTS</h5>
  </a>
  <a href="address.php?option=gcontact&int=1" class="list-group-item">
    <h5 class="list-group-item-heading"><span class="glyphicon glyphicon-user text-primary"></span><span class="glyphicon glyphicon-user text-primary"></span> GROUP CONTACTS</h5>
  </a>
</div>
 <!--option end-->
  <?php
}
else
{
	if($cedit == '' && $cadd == '')
	{
	?>
    <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li><a href="address.php">ADDRESS BOOK</a></li>
  <?php
  if($option == 'scontact')
{
	?>
  <li class="active">CONTACTS</li>
  <?php
}
elseif($option == 'gcontact')
{
	?>
	<li class="active">GROUP CONTACTS</li>
    <?php
}
?>
</ol>
<?php
if($option == 'scontact')
{
	?>
<h4>Contacts</h4>
<?php
}
elseif($option == 'gcontact')
{
	?>
  <h4>Group Contacts</h4>  
    <?php
}
	}
	if($cadd == '' && $cedit == '')
	{
		if($option == 'scontact')
		{
	?>
    <p><a class="btn btn-success" href="address.php?cadd=yes&option=<?php echo $option;?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Contact</a></p><br />
    <?php
		}
		elseif($option == 'gcontact')
		{
			?>
    <p><a class="btn btn-success" href="address.php?cadd=yes&option=<?php echo $option;?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Group Contact</a></p><br />        
            <?php
		}
		
   if($xsel->error_code < 1)
  {
	  if($success != '')
	  {
		  echo "<div class='alert alert-success'>".$success."!</div>";
	  }
?>
  <h5><?php echo $a.'-'.$b.' of '.$total;?> RESULTS</h5>

<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>NAME</th>
<th>PHONE NUMBER(s)</th>
<th>NUMBER COUNT</th>
<th>ACTION</th>
</tr>
<?php
	if (($total > 0) && ($start < $total))
{
	while($xrow = mysql_fetch_row($xsel->query))
	{
?>
<tr>
<td><?php echo $xrow[1];?></td>
<?php
if($option == 'scontact')
{
?>
<td><?php echo $xrow[2];?></td>
<?php
}
elseif($option == 'gcontact')
{
?>
<td><?php echo substr($xrow[2], 0, 30);?>...</td>
<?php	
}
//count num
$count = new number_val();
$count->number_count($xrow[2]);
?>
<td><?php echo $count->cnumber;?></td>
<td><span class="glyphicon glyphicon-edit"></span> <a href="address.php?option=<?php echo $option;?>&cedit=<?php echo $xrow[0];?>">Edit</a> | <span class="glyphicon glyphicon-trash"></span> <a href="address.php?option=<?php echo $option;?>&cdelete=<?php echo $xrow[0];?>&start=<?php echo $start;?>&int=<?php echo $int;?>">Delete</a></td>
</tr>
<?php
	}
}
else
{
	echo "<div class='alert alert-danger'>".error(4)."</div>";
}
?>
</table>
</div><!--table-responsive-->
<ul class="pager">
<?php
if ($start >= $records && $start > 0)
		{
			?>
  <li><a href="address.php?start=<?php echo $start - $records;?>&int=<?php echo $int - 1;?>&option=<?php echo $option;?>">Previous</a></li>
  <?php
		}
		if (($start + $records) < $total)
		{
			?>
  <li><a href="address.php?start=<?php echo $start + $records;?>&int=<?php echo $int + 1;?>&option=<?php echo $option;?>">Next</a></li>
  <?php
		}
}
  else
  {
	  echo "<div class='alert alert-danger'>".error(4)."</div>";
  }
        if($total > $records)
		{
		?>
        <br />
        <br />
        <form class="form-inline" role="form" name="goto_form" method="post" action="">
        <div class="form-group">
    Page:
    </div>
    
<div class="form-group">
      <input type="text" class="form-control" id="goto" name="goto" value="<?php echo $int;?>"> 
  </div>
  
  <div class="form-group">
    / <?php echo $c;?>
    </div>
    
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="go" class="btn btn-primary" value="Go">
    </div>
  </div>
        </form>
        <?php
		}
		?>
</ul>
<?php
	}
	else
	{
		if($cedit == '')
		{
		?>
        <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li><a href="address.php">ADDRESS BOOK</a></li>
  <?php
  if($option == 'scontact')
  {
	  ?>
  <li><a href="address.php?option=<?php echo $option;?>&int=1">CONTACTS</a></li>
  <li class="active">ADD CONTACT</li>
  <?php
  }
  elseif($option == 'gcontact')
  {
	  ?>
	  <li><a href="address.php?option=<?php echo $option;?>&int=1">GROUP CONTACTS</a></li>
  <li class="active">ADD GROUP CONTACT</li>
  <?php
  }
  ?>
</ol>
<?php
if($option == 'scontact')
{
	?>
<h4>Contacts</h4>
<?php
}
elseif($option == 'gcontact')
{
	?>
 <h4>Group Contacts</h4>   
    <?php
}
  /*if($option == 'gcontact')
  {
	  ?>
      <em class="text-warning"><strong>Note:</strong> 100,000 contacts per group</em>
      <?php
  }*/
  ?>
        <div class="row">
  <div class="col-md-5">
  <?php
		}
		elseif($cadd == '')
		{
			?>
        <ol class="breadcrumb">
  <li><a href="index.php">USER AREA</a></li>
  <li><a href="address.php">ADDRESS BOOK</a></li>
  <?php
	if($option == 'scontact')
	{
		?>
  <li><a href="address.php?option=<?php echo $option;?>&int=1">CONTACTS</a></li>
  <li class="active">EDIT CONTACT</li>
  <?php
	}
	elseif($option == 'gcontact')
	{
		?>
         <li><a href="address.php?option=<?php echo $option;?>&int=1">GROUP CONTACTS</a></li>
  <li class="active">EDIT GROUP CONTACT</li>
        <?php
	}
	?>
</ol>
<?php
if($option == 'scontact')
{
	?> 
<h4>Contacts</h4>
            <?php
}
elseif($option == 'gcontact')
{
	?>
  <h4>Group Contacts</h4>  
  <?php
 /* if($option == 'gcontact')
  {
	  ?>
      <em class="text-warning"><strong>Note:</strong> 100,000 contacts per group</em>
      <?php
  }*/
}
		}
		?>
  <div class="panel panel-default">
  <div class="panel-heading">
  <?php
  if($cadd != '')
  {
  ?>
    <h3 class="panel-title">Manual Entry</h3>
    <?php
  }
  elseif($cedit != '')
  {
	  if($option == 'scontact')
	  {
	  ?>
     <h3 class="panel-title">Edit Contact</h3> 
      <?php
	  }
	  else
	  {
		  ?>
          <h3 class="panel-title">Edit Group Contact</h3>
          <?php
	  }
  }
  ?>
  </div>
  <div class="panel-body">
  <?php
  if($sval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sval->error_msg."</div>";
	}
	if($sin->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sin->error_msg."</div>";
	}
	if($ssin != '')
	{
	echo "<div class='alert alert-success'>".$ssin."</div>";
	}
	if($sup->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sup->error_msg."</div>";
	}
	if($sup->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$sup->success_msg." Number count: $ncount</div>";
	}
	
	if($cadd != '')
	{
  ?>
  <form class="form-horizontal" role="form" name="contact_form" method="post" action="address.php?cadd=yes&option=<?php echo $option;?>">
  <?php
	}
	elseif($cedit != '')
	{
		$equery = new select();
		$equery->pick('contact', 'name, phone', 'id', "'$cedit'", '', 'record', '', '', '=', '');
		$equery_row = mysql_fetch_row($equery->query);
		?>
   <form class="form-horizontal" role="form" name="contact_form" method="post" action="address.php?cedit=<?php echo $cedit;?>&option=<?php echo $option;?>">
   <?php
	}
	?>
    <table cellpadding="10" align="center" width="100%">
    <tr>
    <td>
  <div class="form-group">
    <label for="name" class="control-label">Name*</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php
	  if($cedit == '')
	  {
      if($sin->success_code < 1)
	  {
		echo stripslashes($name);  
	  }
	  }
	  else
	  {
		  echo $equery_row[0];
	  }
	  ?>">
  </div>
  
  <div class="form-group">
  <?php
	if($option == 'scontact')
	{
	?>
    <label for="tell" class="control-label">Phone number*</label>
    <?php
	}
	elseif($option == 'gcontact')
	{
		?>
   <label for="tell" class="control-label">Phone numbers*</label><label><small class="text-success">Enter/Paste numbers separated by comma(,) OR Enter/Paste numbers line by line.<br /><a href="../images/number_format.png">View Example</a></small></label>
        <?php
	}
	if($option == 'scontact')
	{
	?>
      <input type="text" class="form-control" id="tell" name="tell" placeholder="Phone number" value="<?php
	  if($cedit == '')
	  {
      if($sin->success_code < 1)
	  {
		echo stripslashes($tell);  
	  }
	   }
	  else
	  {
		  echo $equery_row[1];
	  }
	  ?>">
      <?php
	}
	elseif($option == 'gcontact')
	{
		?>
        <textarea class="form-control" rows="5" id="tell" name="tell"><?php
	  if($cedit == '')
	  {
      if($sin->success_code < 1)
	  {
		echo stripslashes($tell);  
	  }
	   }
	  else
	  {
		  echo $equery_row[1];
	  }
	  ?></textarea>
        
        <?php
	}
	?>
  </div>
  
  <div class="form-group">
    <?php
	if($cadd != '')
	{
		?>
      <input type="submit" name="add" class="btn btn-primary" value="Add">
      <?php
	}
	elseif($cedit != '')
	{
		?>
        <input type="submit" name="edit" class="btn btn-primary" value="Edit">
        <?php
	}
	?>
  </div>
   </td>
  </tr>
  </table>
 
  </form>
  </div><!--panel-body-->
</div><!--main panel-->
</div><!--col-md-5-->
<?php
if($cedit == '')
{
	?>
  
  
  <div class="col-md-2">
  <?php
  if($option == 'gcontact')
  {
	  ?>
  <h2 align="center">OR</h2>
  <?php
  }
  ?>
  </div><!--col-md-2-->
  <div class="col-md-5">
   <?php
  if($option == 'gcontact')
  {
	  ?>
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Bulk Entry</h3>
  </div>
  <div class="panel-body">
  <?php
  if($bval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$bval->error_msg."</div>";
	}
	if($sql->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$sql->error_msg."</div>";
	}
	if($error != '')
	{
	echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($ssql != '')
	{
	echo "<div class='alert alert-success'>".$ssql."</div>";
	}
	?>
    <form action="address.php?cadd=yes&option=<?php echo $option;?>" method="post" enctype="multipart/form-data" name="bulk_form" class="form-horizontal" role="form">
   <table cellpadding="10" align="center" width="100%">
  <tr>
  <td>
  <div class="form-group">      
    <label for="ufile" class="control-label">Upload numbers from Text file*</label>
    <p align="left"><small>Numbers should be separated by comma(,) OR Numbers should be arrainged line by line <a href="../images/number_format.png">View Example</a></small></p>  
    <input type="file" name="ufile" id="ufile">
    <p><em class="help-block"><strong>NOTE: </strong>Max upload size is 2MB</em></p>
  </div>
  
  <div class="form-group">
    <label for="gname" class="control-label">Name*</label>
      <input type="text" class="form-control" id="gname" name="gname" placeholder="Name" value="<?php
      if($sql->success_code < 1)
	  {
		echo stripslashes($gname);  
	  }
	  ?>">
  </div>
  
  <div class="form-group">
      <input type="submit" name="upload" class="btn btn-primary" value="Upload">
  </div>
  </td>
  </tr>
  </table>
    </form>
  </div><!--panel-body-->
</div><!--main panel-->
   <?php
  }
	  ?>
  </div><!--col-md-5-->
  </div><!--row-->
  <?php
}
?>
<p><span class="glyphicon glyphicon-eye-open"></span> <a href="address.php?option=<?php echo $option;?>&int=1"><strong>View contacts</strong></a></p>

  
        <?php
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
  </body>
</html>
<?php
mysql_close($connect);
?>