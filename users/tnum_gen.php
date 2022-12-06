<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'atnum_gen';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$view = $_GET['view'];

$state = $_POST['state'];
$gen = $_POST['gen'];

if($gen)
{
	header("location: tnum_gen.php?view=$state");
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

    <title>Targeted No. Generator</title>
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
  <li class="active">TARGETED NO. GENERATOR</li>
</ol>
 <h4>Targeted No. Generator</h4>
 <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
?>
        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Generate Numbers</h3>
  </div>
  <div class="panel-body">
  <form action="tnum_gen.php" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="state" class="control-label">Select State*</label>
      <select class="form-control" name="state" id="state">
      <?php
	  if($view != '')
	  {
		  ?>
          <option value="<?php echo $view;?>"><?php echo $view;?></option> 
          <?php
	  }
	  ?>
            <option value="Abia">Abia</option>
            <option value="Abuja">Abuja</option>
            <option value="Adamawa">Adamawa</option>
            <option value="Akwa Ibom">Akwa Ibom</option>
            <option value="Anambra">Anambra</option>
            <option value="Bauchi">Bauchi</option>
            <option value="Bayelsa">Bayelsa</option>
            <option value="Benue">Benue</option>
            <option value="Borno">Borno</option>
            <option value="Cross River">Cross River</option>
            <option value="Delta">Delta</option>
            <option value="Ebonyi">Ebonyi</option>
            <option value="Edo">Edo</option>
            <option value="Ekiti">Ekiti</option>
            <option value="Enugu">Enugu</option>
            <option value="Gombe">Gombe</option>
            <option value="Imo">Imo</option>
            <option value="Jigawa">Jigawa</option>
            <option value="Kaduna">Kaduna</option>
            <option value="Kano">Kano</option>
            <option value="Kastina">Kastina</option>
            <option value="Kebbi">Kebbi</option>
            <option value="Kogi">Kogi</option>
            <option value="Kwara">Kwara</option>
            <option value="Lagos">Lagos</option>
            <option value="Nassarawa">Nassarawa</option>
            <option value="Niger">Niger</option>
            <option value="Ogun">Ogun</option>
            <option value="Ondo">Ondo</option>
            <option value="Osun">Osun</option>
            <option value="Oyo">Oyo</option>
            <option value="Plateau">Plateau</option>
            <option value="Rivers">Rivers</option>
            <option value="Sokoto">Sokoto</option>
            <option value="Taraba">Taraba</option>
            <option value="Yobe">Yobe</option>
            <option value="Zamfara">Zamfara</option>
</select>
  </div>
  
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Generate" name="gen" id="gen">
  </div>
  
</td>
</tr>
</table>
  </form>
  
  </div>
</div>
<?php	
if($view != '')
{
	//get limit
	$limit = new select();
	$limit->pick('info', 'num_gen', 'id', "1", '', 'record', '', '', '=', '');
	$limit_row = mysql_fetch_row($limit->query);
   //get numbers
   $num = new select();
   $num->pick('target', 'num', 'state', "'$view'", "0, $limit_row[0]", 'record', '', '', '=', '');
   echo mysql_error();
   if($num->count > 0)
   {
	   ?>
       <div class="table-responsive">
       <table class="table table-striped" align="center">
       <tr>
       <th>NUMBERS</th>
       </tr>
       <?php
	   while($num_row = mysql_fetch_row($num->query))
	   {
		   ?>
        <tr>
        <td><?php echo $num_row[0];?></td>
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
}//view
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