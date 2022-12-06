<?php
$page = 'arates';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

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
  <li><a href="index.php">USER AREA</a></li>
  <li class="active">RATES</li>
</ol>
 <h4>Rates</h4>
  <?php
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
</tr>
<?php
	while($rrow = mysql_fetch_row($rsel->query))
	{
?>
<tr>
<td><?php echo $rrow[0];?> Units - <?php echo $rrow[1];?> Units</td>
<td>₦<?php echo $rrow[2];?></td>
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