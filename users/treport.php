<?php
$page = 'atreport';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

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
$sel->pick('transaction', '*', 'user', "$auser", '', 'record', '', '', '=', '');

$xsel = new select();
	$xsel->pick('transaction', 'id, type, credit, tuser, unix_timestamp(date)', 'user', "$auser", "$start, $records", 'record', 'id desc', '', '=', '');
	
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
header("location: treport.php?start=$start&int=$goto");
}

//incase you delete the last item in a page, to initialise to the previous...
if($total > 0 && $result < 1)
	{
		$start = $start - $records;
		$int = $int - 1;
		$xsel = new select();

$xsel->pick('transaction', 'id, type, credit, tuser, unix_timestamp(date)', 'user', "$auser", "$start, $records", 'record', 'id desc', '', '=', '');
	}

$a = $start + 1;
$b = $start + $xsel->count;
$c = ceil($total / $records);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Transactions</title>
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
  <li class="active">TRANSACTIONS</li>
</ol>
       <h4>Transactions</h4>
       <?php
  if($xsel->error_code < 1)
  {
?>
  <h5><?php echo $a.'-'.$b.' of '.$total;?> RESULTS</h5>

<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>DATE</th>
<th>TYPE</th>
<th>T-USER</th>
<th>CREDIT</th>
</tr>
<?php
	if (($total > 0) && ($start < $total))
{
	while($xrow = mysql_fetch_row($xsel->query))
	{
		if($xrow[1] == 'sendsms')
		{
			$tuser = $xrow[3];
		}
		elseif($xrow[1] == 'transfer' || $xrow[1] == 'receive' || $xrow[1] == 'transfer(coupon)' || $xrow[1] == 'receive(coupon)' || stristr($xrow[1], 'voucher_transfer') || stristr($xrow[1], 'voucher_receive'))
		{
			if($xrow[3] == 0)
			{
				$tuser = 'Admin';
			}
			else
			{
			//get user
			$guser = new select();
			$guser->pick('user', 'username', 'id', "'$xrow[3]'", '', 'record', '', '', '=', '');
			$grow = mysql_fetch_row($guser->query);
			
			$tuser = $grow[0];
			}
		}
?>
<tr>
<td><?php echo date('jS M Y', $xrow[4]);?></td>
<td><?php echo strtoupper($xrow[1]);?></td>
<td><?php echo $tuser;?></td>
<td><?php echo $xrow[2];?></td>
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
  <li><a href="treport.php?start=<?php echo $start - $records;?>&int=<?php echo $int - 1;?>">Previous</a></li>
  <?php
		}
		if (($start + $records) < $total)
		{
			?>
  <li><a href="treport.php?start=<?php echo $start + $records;?>&int=<?php echo $int + 1;?>">Next</a></li>
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
    <div class="col-lg-10">
    Page:
    </div>
    </div>
    
<div class="form-group">
    <div class="col-lg-10">
      <input type="text" class="form-control" id="goto" name="goto" value="<?php echo $int;?>"> 
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-10">
    / <?php echo $c;?>
    </div>
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