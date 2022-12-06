<?php
$hour = time();
$hour = (int)$hour;

$page = 'anewsletter';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$send = $_POST['send'];
$message = $_POST['message'];
$topic = $_POST['topic'];

$sent = $_GET['sent'];

if($send)
{
	$val = new validate();
	$val->valid("$message,$topic");
	if($val->error_code < 1)
	{
		$xhour = $hour - 7200;
$in = new insert();
$in->input('newsletter_job', 'id, topic, start, hour, message', "0, '$topic', 0, $xhour, '$message'");

header("Location: " . $_SERVER["REQUEST_URI"]."?sent=Successful!");
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

    <title>Newsletter</title>
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
  <li class="active">NEWSLETTER</li>
</ol>
 <h4>Newsletter</h4>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SEND NEWSLETTER</h3>
  </div>
  <div class="panel-body">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($sent != '')
	{
	echo "<div class='alert alert-success'>".$sent."!</div>";
	}
  ?>
  <form class="form-horizontal" role="form" name="form1" method="post" action="newsletter.php">
  <div class="form-group">
  <label for="topic" class="col-lg-2 control-label">Subject*</label> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="topic" placeholder="Subject" name="topic" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($topic);
	  }
	  ?>">
    </div>
  </div>
  
  <div class="form-group">
  <label for="message" class="col-lg-2 control-label">Message*</label> 
    <div class="col-lg-10">
      <textarea class="form-control" rows="3" id="message" placeholder="Message" name="message"><?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($message);
	  }
	  ?></textarea>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Send" name="send" id="send">
    </div>
  </div>
  </form>
  
  </div>
</div>
    
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