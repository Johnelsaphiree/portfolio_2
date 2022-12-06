<?php
$page = 'aschedule';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$delete = $_GET['delete'];
$success = $_GET['success'];
$view = $_GET['view'];

if($delete != '')
{
	$dsel = new select();
	$dsel->pick('schedule', 'credit', 'id', "$delete", '', 'record', '', '', '=', '');
	$dsel_row = mysql_fetch_row($dsel->query);
	$ups = new update();
	$ups->up('user', 'reserved', 'id', "$auser", "reserved - $dsel_row[0]");
	
	$del = new delete();
	$del->gone('schedule', 'id', "$delete");
	header("location: schedule.php?success=Successful");
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

    <title>Scheduled Messages</title>
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
  <li class="active">SCHEDULED MESSAGES</li>
</ol>
  <h4>Scheduled Messages</h4>
  <?php
   if($view == '')
  {
  if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."!</div>";
	}
  
  $sel = new select();
	$sel->pick('schedule', 'id, senderid, message, unix_timestamp(entrydate), unix_timestamp(senddate)', 'user', "$auser", '', 'record', 'id desc', '', '=', '');
	
	 if($sel->error_code < 1)
  {
?>
<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>SENDER ID</th>
<th>MESSAGE</th>
<th>ENTRY DATE</th>
<th>SEND DATE</th>
<th>VIEW</th>
<th>DELETE</th>
</tr>
<?php
	while($row = mysql_fetch_row($sel->query))
	{
?>
<tr>
<td><?php echo $row[1];?></td>
<td><?php echo substr($row[2], 0, 20);?>...</td>
<td><?php echo date('jS M Y', $row[3]);?></td>
<td><?php echo date('jS M Y', $row[4]);?></td>
<td><a href="schedule.php?view=<?php echo $row[0];?>">View</a></td>
<td><span class="glyphicon glyphicon-remove"></span> <a href="schedule.php?delete=<?php echo $row[0];?>">Cancel</a></td>
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
  <?php
  }//view
else
{
	$vsel = new select();
	$vsel->pick('schedule', 'senderid, destination, message, unix_timestamp(entrydate), unix_timestamp(senddate), credit, no_count', 'id', "$view", '', 'record', '', '', '=', '');
	$vrow = mysql_fetch_row($vsel->query);
	?>
<p><a href="schedule.php"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a></p>
    <strong>SENDER ID: </strong><?php echo $vrow[0];?><br />
     <strong>ENTRY DATE: </strong><?php echo date("jS M Y", $vrow[3]);?><br />
     <strong>SEND DATE: </strong><?php echo date("jS M Y", $vrow[4]);?><br />
     <strong>CREDIT: </strong><?php echo $vrow[5];?><br />
     <strong>NUMBER COUNT: </strong><?php echo $vrow[6];?><br /><br />
     <strong>RECIPIENTS:</strong><br />
    <textarea class="form-control" rows="5" id="message" name="message" disabled><?php echo $vrow[1];?></textarea><br />
    <strong>MESSAGE:</strong><br />
    <?php echo $vrow[6];?>
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

  </body>
</html>
<?php
mysql_close($connect);
?>