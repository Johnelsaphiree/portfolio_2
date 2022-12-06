<?php
$page = 'ausers';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$sitem = $_POST['sitem'];
$search = $_POST['search'];

if($search)
{
	$sval = new validate();
	$sval->valid($sitem);
	if($sval->error_code < 1)
	{
		header("location: users.php?int=1&xsearch=$sitem");
	}
}

$view = $_GET['view'];
$xsearch = $_GET['xsearch'];
$delete = $_GET['delete'];
$success = $_GET['success'];

if($delete != '')
{
	//update admin balance
	$us = new select();
	$us->pick('user', 'balance', 'id', "$delete", '', 'record', '', '', '=', '');
	$usrow = mysql_fetch_row($us->query);
	
		$ua = new update();
		$ua->up('admin', 'balance', 'id', "1", "balance + $usrow[0]");
		
	$del = new delete();
	$del->gone('contact', 'user', "$delete");
	$del->gone('draft', 'user', "$delete");
	$del->gone('return_url', 'user', "$delete");
	$del->gone('schedule', 'user', "$delete");
	$del->gone('smslog', 'user', "$delete");
	$del->gone('transaction', 'user', "$delete");
	$del->gone('smsrequest', 'user', "$delete");
	$del->gone('user', 'id', "$delete");
	
	header("location: users.php?success=Successful&int=1");
}

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
$xsel = new select();

if($xsearch == '')
{
	$sel->pick('user', '*', '', '', '', 'record', '', '', '', '');

	$xsel->pick('user', 'id, username, phone, unix_timestamp(date_created), balance', '', '', "$start, $records", 'record', 'date_created desc', '', '', '');
}
else
{
	$sel->pick('user', '*', 'name,username', "'%$xsearch%','%$xsearch%'", '', 'record', '', '', 'like,like', 'or');

	$xsel->pick('user', 'id, username, phone, unix_timestamp(date_created), balance', 'name,username', "'%$xsearch%','%$xsearch%'", "$start, $records", 'record', 'date_created desc', '', 'like,like', 'or');
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
header("location: users.php?start=$start&int=$goto&xsearch=$xsearch");
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

    <title>Users</title>
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
  <?php
  if($view == '')
  {
  ?>
  <li class="active">USERS</li>
  <?php
  }
  else
  {
	  ?>
      <li><a href="users.php?int=1.php">USERS</a></li>
      <li class="active">VIEW USER</li>
      <?php
  }
  ?>
</ol>
  <?php
  if($view == '')
  {
	  ?>
       <h4>Users</h4>
       <?php
       if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."!</div>";
	}
    ?>
       <form class="form-inline" role="form" name="form1" method="post" action="users.php?int=1">
  <div class="form-group"> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="sitem" placeholder="Search" name="sitem" value="">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Search" name="search" id="search">
    </div>
  </div>
  </form><br />
       <?php
  if($xsel->error_code < 1)
  {
?>
  <h5><?php echo $a.'-'.$b.' of '.$total;?> RESULTS</h5>

<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>USERNAME</th>
<th>PHONE</th>
<th>DATE CREATED</th>
<th>BALANCE</th>
<th>VIEW</th>
</tr>
<?php
	if (($total > 0) && ($start < $total))
{
	while($xrow = mysql_fetch_row($xsel->query))
	{
?>
<tr>
<td><?php echo $xrow[1];?></td>
<td><?php echo $xrow[2];?></td>
<td><?php echo date('jS M Y', $xrow[3]);?></td>
<td><?php echo $xrow[4];?></td>
<td><a href="users.php?view=<?php echo $xrow[0];?>">View</a></td>
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
  <li><a href="users.php?start=<?php echo $start - $records;?>&int=<?php echo $int - 1;?>&xsearch=<?php echo $xsearch;?>">Previous</a></li>
  <?php
		}
		if (($start + $records) < $total)
		{
			?>
  <li><a href="users.php?start=<?php echo $start + $records;?>&int=<?php echo $int + 1;?>&xsearch=<?php echo $xsearch;?>">Next</a></li>
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
  <?php
  }
  else
  {
	  ?>
      <h4>View user</h4>
      <?php
	  $usel = new select();
$usel->pick('user', 'name, username, phone, email, balance, reserved, unix_timestamp(date_created), unix_timestamp(log_date)', 'id', "$view", '', 'record', '', '', '=', '');
$urow = mysql_fetch_row($usel->query);
	  ?>
      <div class="table-responsive">
  <table cellpadding="10">
  <tr>
  <td><span class="glyphicon glyphicon-fire text-primary"></span> <strong>USERNAME:</strong></td>
  <td><?php echo $urow[1];?></td>
  </tr>
  <tr>
  <td><span class="glyphicon glyphicon-user text-primary"></span> <strong>NAME:</strong></td>
  <td><?php echo $urow[0];?></td>
  </tr>
  <tr>
  <td><span class="glyphicon glyphicon-earphone text-primary"></span> <strong>PHONE:</strong></td>
  <td><?php echo $urow[2];?></td>
</tr>
  <tr>
  <td><span class="glyphicon glyphicon-envelope text-primary"></span> <strong>EMAIL:</strong></td>
  <td><?php echo $urow[3];?></td>
</tr>
<tr>
  <td><span class="glyphicon glyphicon-usd text-primary"></span> <strong>BALANCE:</strong></td>
  <td><?php echo $urow[4];?> Units</td>
</tr>
<tr>
  <td><span class="glyphicon glyphicon-pushpin text-primary"></span> <strong>RESERVED:</strong></td>
  <td><?php echo $urow[5];?> Units</td>
</tr>
<tr>
  <td><span class="glyphicon glyphicon-calendar text-primary"></span> <strong>DATE CREATED:</strong></td>
  <td><?php echo date('jS M Y', $urow[6]);?></td>
</tr>
<tr>
  <td><span class="glyphicon glyphicon-play text-primary"></span> <strong>LOG DATE:</strong></td>
  <td><?php echo date('jS M Y', $urow[7]);?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><a href="users.php?delete=<?php echo $view;?>" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>
  </tr>
  </table><br />
  <p><span class="glyphicon glyphicon-eye-open"></span> <a href="users.php?int=1"><strong>View users</strong></a></p>
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