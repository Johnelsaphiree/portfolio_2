<?php
include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');
include('up.php');

$pid = $_GET['pid'];
//page info
$gm = new select();
$gm->pick('cpages', 'message, title', 'id', "$pid", '', 'record', '', '', '=', '');
$gm_row = @mysql_fetch_row($gm->query);

$page = $gm_row[1];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title><?php echo $gm_row[1];?></title>
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
 
  
  <!--avHtFe9bYjI_xfz1HftxNEGKH8w-->
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
      <h1><?php echo $gm_row[1];?></h1>
   <p><?php echo str_replace('../', '', stripslashes($gm_row[0]));?></p>

</div><!--col-->
</div><!--row-->
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