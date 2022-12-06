<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'avcoupon';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$delete = $_GET['delete'];

if($delete)
{
	$del = new delete();
	$del->gone('coupon_usage', 'coupon_id', "$delete");
	$del->gone('coupon', 'id', "$delete");
	$success = success(1);
	header("location: vcoupon.php?success=$success");
	exit;
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

    <title>View Coupon</title>
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
  <li class="active">VIEW COUPON</li>
</ol>
<h4>View Coupon</h4>
  <?php
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	
	//get coupons
	$gc = new select();
	$gc->pick('coupon', 'id, code, unit, unix_timestamp(exp_date)', '', '', '', 'record', 'unit', '', '', '');
	if($gc->count > 0)
	{
		?>
     <div class="table-responsive">
     <table class="table table-bordered">
     <tr>
     <th>COUPON CODE</th>
     <th>UNIT VALUE</th>
     <th>EXPIRY DATE</th>
     <th>DELETE</th>
     </tr>
     <?php
	 while($gc_row = mysql_fetch_row($gc->query))
	 {
		 ?>
       <tr>
       <td><?php echo $gc_row[1];?></td>
       <td><?php echo $gc_row[2];?></td>
       <td><?php echo date('jS M Y', $gc_row[3]);?></td>
       <td><a href="vcoupon.php?delete=<?php echo $gc_row[0];?>">Delete</a></td>
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
		echo $gc->error_msg;
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