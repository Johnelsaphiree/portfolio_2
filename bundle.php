<?php
$page = 'bundle';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/upload_download.php');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$le = isset($_GET['le']) ? $_GET['le'] : '';
$lkey = isset($_POST['lkey']) ? $_POST['lkey'] : '';
$enter = isset($_POST['enter']) ? $_POST['enter'] : '';

if($enter)
{
	$val = new validate();
	$val->valid($lkey);
	if($val->error_code < 1)
	{
				//check exist
				$cu = new select();
				$cu->pick('ulic', '*', 'id', "1", '', 'record', '', '', '=', '');
				if($cu->count < 1)
				{
					$in = new insert();
					$in->input('ulic', 'id, lkey', "0, '$lkey'");
					header("location: index.php?bud=yes");
				}
				else
				{
					$up = new update();
					$up->up('ulic', 'lkey', 'id', "1", "'$lkey'");
					header("location: index.php?bud=yes");
				}
	
	}//val
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>License</title>

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

    <div class="container">
<h2>Licence</h2>
 <?php
   if($val->error_code > 0 || $success != '' || $le != '')
   {
	   if($success != '')
	   {
	   ?>
       <div class="alert alert-success"><strong>
       <?php
	   }
	   else
	   {
		   ?>
       <div class="alert alert-danger"><strong>
	   <?php
	   }
       echo $val->error_msg;
	   echo $success;
	   echo $le;
	   ?>
       </div></strong>
       <?php
   }
   
   if($success == '')
   {
	?>
    <form id="form1" name="form1" method="post" action="bundle.php" role="form">
      
       <div class="form-group">
      <label for="lkey">License Key:</label>
      <textarea name="lkey" id="lkey" rows="5" class="form-control"></textarea>
     </div>
      
<div class="form-group">
  <input type="submit" name="enter" id="enter" value="Enter" class="btn btn-primary"/>
   </div>
     
    </form>
    <?php
   }
   else
   {
	   ?>
   <p><a href="index.php" class="btn btn-success">Continue</a></p>
       <?php
   }
   ?>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect)
?>