<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'avtarget';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('../objects/upload_download.php');
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

    <title>Number Count</title>
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
  <li class="active">NUMBER COUNT</li>
</ol>
   <h4>Number Count</h4>
   <?php
   //get numbers
   $num = new select();
   $num->pick('target', 'state, count(*)', '', '', '', 'record', 'state', 'state', '', '');
   if($num->count > 0)
   {
	   ?>
       <div class="table-responsive">
       <table class="table table-striped">
       <tr>
       <th>STATE</th>
       <th>COUNT</th>
       </tr>
       <?php
	   while($num_row = mysql_fetch_row($num->query))
	   {
		   ?>
        <tr>
        <td><?php echo $num_row[0];?></td>
        <td><?php echo number_format($num_row[1]);?></td>
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
	   echo $num->error_msg;
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