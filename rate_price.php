<?php
$page = 'rate_price';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('up.php');

$rsel = new select();
$rsel->pick('rate', 'lower, upper, cost, id', '', '', '', 'record', 'cost desc', '', '', '');

$gm = new select();
$gm->pick('pages', 'message', 'type', "'pricing'", '', 'record', '', '', '=', '');
$gm_row = @mysql_fetch_row($gm->query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $csite_name;?> pricing">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Rates/Pricing</title>
    <!-- Bootstrap core CSS -->
    <link href="dist/css/<?php echo $cstyle;?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php
 include('body/head.php');
 ?>
    <div class="container">
     <div class="row">
    <?php
	include("body/sidex.php");
	?>
    <div class="col-md-9">
    <h1>Rates/Pricing</h1>
    <?php
	if($rsel->count > 0)
	{
	?>
  <div class="table-responsive">
<table class="table table-striped table-condensed">
<tr>
<th>CREDIT RANGE</th>
<th>SALES RATE PER UNIT</th>
</tr>
<?php
	while($rrow = mysql_fetch_row($rsel->query))
	{
		if($xl == '')
		{
		$xl = $rrow[0];
		}
?>
<tr>
<td><?php echo $rrow[0];?> Units - <?php echo $rrow[1];?> Units</td>
<td>₦<?php echo $rrow[2];?></td>
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
	echo "<div class='alert alert-danger'>".error(4)."</div>";
}
?>
      
    <span class="lead text-primary">BUYING SMS CREDIT</span><br />
     <?php echo str_replace('../', '', stripslashes($gm_row[0]));?>
    </p>
    
    </div>
    </div>
    
    </div><!-- /.container -->
    <?php
	include('body/foot.php');
	?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect);
?>