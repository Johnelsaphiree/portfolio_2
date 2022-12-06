<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'atheme';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$style = $_POST['style'];
$save = $_POST['save'];

if($save)
{
	$aval = new validate();
	$aval->valid($style);
	if($aval->error_code < 1)
	{
		$style = $style.'.css';
		$ain = new update();
		$ain->up('info', 'style', 'id', "1", "'$style'");
		$success = success(1).' Kinldy refresh page if change has not reflected yet';
		header("location: theme.php?success=$success");
		exit;
	}
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

    <title>Admin Home</title>
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
  <li class="active">ADMIN AREA</li>
  <li class="active">THEME</li>
</ol>
  <?php
  if($aval->error_code > 0 || $success != '')
   {
	   if($success != '')
	   {
	   ?>
       <div class="alert alert-success">
       <?php
	   }
	   else
	   {
		   ?>
       <div class="alert alert-danger">
	   <?php
	   }
       echo $aval->error_msg;
	   ?>
       </div>
       <?php
   }
	?>
    <form id="form1" name="form1" method="post" action="theme.php" role="form">
     <div class="lead">Select Theme*:</div>
     <table cellpadding="5">
       <tr>
         <td><label>
           <input type="radio" name="style" value="bootstrap" id="style_0">
          Midnight Blue</label></td>
           <td><img src="../images/theme/midnight_blue.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="1" id="style_1">
           Dark Slate Grey</label></td>
            <td><img src="../images/theme/dark_slate_grey.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="2" id="style_2">
           Coffee</label></td>
            <td><img src="../images/theme/coffee.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="3" id="style_3">
           Plum Velvet</label></td>
            <td><img src="../images/theme/plum_velvet.jpg" class="img-thumbnail"></td>
       </tr>
     </table><br />
<div class="form-group">
  <input type="submit" name="save" id="save" value="Save" class="btn btn-primary"/>
   </div>
     
    </form>
  
  
  
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