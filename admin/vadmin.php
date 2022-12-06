<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'avadmin';

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

    <title>Voice Admin</title>
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

   //get link
	$gl = new select();
	$gl->pick('voice', 'vadmin', 'id', "1", '', 'record', '', '', '=', '');
	$gl_row = @mysql_fetch_row($gl->query);
  ?>
  <ol class="breadcrumb">
  <li><a href="index.php">DASHBOARD</a></li>
  <li class="active">VOICE ADMIN</li>
</ol>

   <object data=<?php echo $gl_row[0];?> width="100%" height="1000"> <embed src=<?php echo $gl_row[0];?> width="100%" height="100%"> </embed> Error: Embedded data could not be displayed. </object>
  

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