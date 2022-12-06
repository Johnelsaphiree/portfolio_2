<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'home';

$bud = isset($_GET['bud']) ? $_GET['bud'] : '';
include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('up.php');

$out = isset($_GET['out']) ? $_GET['out'] : '';
$ouser = isset($_GET['ouser']) ? $_GET['ouser'] : '';

if($out != '')
{
	setcookie($out, $ouser, time()-96400, '/');
	$admin = '';
	$auser = '';
	header('location: index.php');
}


$gm = new select();
$gm->pick('pages', 'message', 'type', "'home'", '', 'record', '', '', '=', '');
$gm_row = @mysql_fetch_row($gm->query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $cdescription;?>">
    <meta name="author" content="">
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Home</title>
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
    <div id="carousel-example-generic" class="carousel slide">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="images/s1.jpg" alt="..." class="img-responsive">
      <div class="carousel-caption">
        ...
      </div>
    </div>
   <div class="item">
      <img src="images/s2.jpg" alt="..." class="img-responsive">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="images/s3.jpg" alt="..." class="img-responsive">
      <div class="carousel-caption">
        ...
      </div>
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="icon-next"></span>
  </a>
</div><br />
    <div class="row">
    <?php
	include("body/sidex.php");
	?>
    <div class="col-md-6">
  <p><?php echo str_replace('../', '', stripslashes($gm_row[0]));?></p>

</div><!--col6-->

<div class="col-md-3">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Targeted No. Generator</h3>
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

<div class="panel panel-primary">
<div class="panel-heading"><span class="lead"><strong><span class="glyphicon glyphicon-tower"></span> Bank Details</strong></span></div>
<div class="panel-body">
  <?php
  //bank info
  $gb = new select();
  $gb->pick('bank', 'id, acc_name, acc_no, logo, name', '', '', '', 'record', '', '', '', '');
  if($gb->count > 0)
  {
	  ?>
  <table width="100%" cellpadding="5">
   <?php
  while($gb_row = mysql_fetch_row($gb->query))
  {
	  ?>
  <tr valign="top">
  <td width="20%"><img src="images/bank/<?php echo $gb_row[3];?>.jpg"class="img-responsive img-thumbnail"></td>
  <td><small><strong>Bank name:</strong> <?php echo $gb_row[4];?><br /><strong>Acc name:</strong> <?php echo $gb_row[1];?><br /><strong>Acc no:</strong> <?php echo $gb_row[2];?></small></td>
  </tr>
  <?php
  }
  ?>
  </table>
  <?php
  }
  else
  {
	  echo $gb->error_msg;
  }
  ?>
  </div><!--panel-->
  </div><!--panel-->

</div><!--col3-->

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
    <script>
	$('.carousel').carousel({
  interval: 5000
})
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>