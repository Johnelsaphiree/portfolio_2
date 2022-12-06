<?php
$page = 'arates';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$sin = $_GET['sin'];
$xedit = $_GET['xedit'];

$upper = $_POST['upper'];
$lower = $_POST['lower'];
$cost = $_POST['cost'];
$set = $_POST['set'];

if($set)
{
	$val = new validate();
	$val->numeric($lower, 'Lower Range');
	$val->numeric($upper, 'Upper Range');
	$val->numeric($cost, 'Cost');
	$val->valid("$lower,$upper,$cost");
	if($val->error_code < 1)
	{
		if($xedit == '')
		{
		$in = new insert();
		$in->input('rate', 'id, lower, upper, cost', "0, '$lower', '$upper', '$cost'");
		}
		else
		{
			$in = new update();
			$in->up('rate', 'lower', 'id', "$xedit", "'$lower'");
			$in->up('rate', 'upper', 'id', "$xedit", "'$upper'");
			$in->up('rate', 'cost', 'id', "$xedit", "'$cost'");
		}
		
		header("Location: rates.php?sin=".$in->success_msg);
    exit;
	}
}

$del = $_GET['del'];
if($del != '')
{
	$xdel = new delete();
	$xdel->gone('rate', 'id', "'$del'");
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

    <title>Rates</title>
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
  <li><a href="index.php">DASHBOARD</a></li>
  <li class="active">RATES</li>
</ol>
 <h4>Rates</h4>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php
    if($xedit == '')
	{
		echo 'SET';
	}
	else
	{
		echo 'EDIT';
	}
	?> RATE</h3>
  </div>
  <div class="panel-body">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($sin != '')
	{
	echo "<div class='alert alert-success'>".$sin."</div>";
	}
	
	if($in->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$in->error_msg."</div>";
	}
	if($xedit != '')
	{
		$esel = new select();
		$esel->pick('rate', 'lower, upper, cost', 'id', "$xedit", '', 'record', '', '', '=', '');
		$esel_row = mysql_fetch_row($esel->query);
	}
  ?>
  <form class="form-inline" role="form" name="form1" method="post" action="rates.php?xedit=<?php echo $xedit;?>">
  <div class="form-group">
  <label for="lower" class="col-lg-4 control-label">Lower Range*</label> 
    <div class="col-lg-14">
      <input type="text" class="form-control" id="lower" placeholder="Lower Range" name="lower" value="<?php
	  if($xedit != '')
	  {
		  echo $esel_row[0];
	  }
      elseif($in->success_code < 1)
	  {
		  echo $lower;
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
  <label for="upper" class="col-lg-4 control-label">Upper Range*</label> 
    <div class="col-lg-14">
      <input type="text" class="form-control" id="upper" placeholder="Upper Range" name="upper" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[1];
	  }
      elseif($in->success_code < 1)
	  {
		  echo $upper;
	  }
	  ?>">
    </div>
    </div>
    
    <div class="form-group">
  <label for="cost" class="col-lg-4 control-label">Cost(₦)*</label> 
    <div class="col-lg-14">
      <input type="text" class="form-control" id="cost" placeholder="Cost" name="cost" value="<?php
      if($xedit != '')
	  {
		  echo $esel_row[2];
	  }
      elseif($in->success_code < 1)
	  {
		  echo $cost;
	  }
	  ?>">
    </div>
  </div>
<br />
<br />
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Set" name="set" id="set">
    </div>
  </div>
  </form>
  
  </div>
</div>
  <br />
  <?php
  if($xedit == '')
  {
	  ?>
  <h5>View Rates</h5>
  <?php
  if($xdel->success_code > 0)
  {
	echo "<div class='alert alert-success'>".$xdel->success_msg."</div>";
  }
	
	$rsel = new select();
	$rsel->pick('rate', 'lower, upper, cost, id', '', '', '', 'record', 'cost desc', '', '', '');
	
	if($rsel->count > 0)
	{
	?>
  <div class="table-responsive">
<table class="table table-striped">
<tr>
<th>CREDIT RANGE</th>
<th>SALES RATE PER UNIT</th>
<th>ACTION</th>
</tr>
<?php
	while($rrow = mysql_fetch_row($rsel->query))
	{
?>
<tr>
<td><?php echo $rrow[0];?> Units - <?php echo $rrow[1];?> Units</td>
<td>₦<?php echo $rrow[2];?></td>
<td><span class="glyphicon glyphicon-edit"></span> <a href="rates.php?xedit=<?php echo $rrow[3];?>">Edit</a> | <span class="glyphicon glyphicon-trash"></span> <a href="rates.php?del=<?php echo $rrow[3];?>">Delete</a></td>
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
  }//xedit
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