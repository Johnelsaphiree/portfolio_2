<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'anetwork';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
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

    <title>Network Charges</title>
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
  <li class="active">NETWORK CHARGES</li>
</ol>

  <span class="lead">Networks and Charges</span>
  <?php
  //get networks and charges
  $gn = new select();
  $gn->pick('network', 'id, ncode, ucost, name', '', '', '', 'record', 'name', '', '', '');
  if($gn->count > 0)
  {
  ?>
  <div class="table-responsive">
  <table class="table">
  <tr>
  <th>NETWORK NAME</th>
  <th>NETWORK CODE</th>
  <th>CHARGE PER SMS</th>
  </tr>
  <?php
  while($gn_row = mysql_fetch_row($gn->query))
  {
  ?>
  <tr>
  <td><?php echo $gn_row[3];?></td>
  <td><?php echo $gn_row[1];?></td>
  <td><?php echo $gn_row[2];?></td>
  </tr>
  <?php
  }
  ?>
  </table>
  </div><!--table responsive-->
  <?php
  }
  else
  {
	  echo $gn->error_msg;
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