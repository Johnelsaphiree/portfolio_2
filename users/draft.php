<?php
$page = 'adraft';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$delete = $_GET['delete'];
$success = $_GET['success'];

if($delete != '')
{
	$del = new delete();
	$del->gone('draft', 'id', "$delete");
	header("location: draft.php?success=Successful");
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

    <title>Draft</title>
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
  <li class="active">DRAFT</li>
</ol>
  <h4>Draft</h4>
  <?php
  if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."!</div>";
	}
  
  $sel = new select();
	$sel->pick('draft', 'id, senderid, message', 'user', "$auser", '', 'record', 'id desc', '', '=', '');
	
	 if($sel->error_code < 1)
  {
?>
<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>SENDER ID</th>
<!--<th>MESSAGE</th>-->
<th>SEND/DELETE</th>
</tr>
<?php
	while($row = mysql_fetch_row($sel->query))
	{
?>
<tr>
<td><?php echo $row[1];?></td>
<!--<td><?php //echo substr($row[2], 0, 30);?>...</td>-->
<td><span class="glyphicon glyphicon-send"></span> <a href="send_sms.php?dmsg=<?php echo $row[0];?>">Send</a> | <span class="glyphicon glyphicon-trash"></span> <a href="draft.php?delete=<?php echo $row[0];?>">Delete</a></td>
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