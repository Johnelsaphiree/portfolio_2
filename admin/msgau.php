<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'amsgau';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];

$message = $_POST['message'];
$send = $_POST['send'];
$val = new validate();
$in = new insert();

if($send)
{
	$val->valid($message);
	if($val->error_code < 1)
	{
		$in->input('xjob', 'id, start, message, type, body, credit', "0, 0, '$message', 'all users', 0, 0");
		$success = success(19);
		header("location: msgau.php?success=$success");
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

    <title>Msg All Users</title>
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
  <li class="active">Msg All Users</li>
</ol>
 <h4>Msg All Users</h4>
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
if($error != '')
	{
		echo "<div class='alert alert-danger'>".$error."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	
	if($success == '')
	{
	?>
 <form action="msgau.php" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SMS Message<span class="text-primary">*</span></h3>
  </div>
  
  <div class="panel-body">
  <div class="form-group">
    <label for="message" class="col-lg-2 control-label">Enter/Paste Message</label> 
    <div class="col-lg-10">
     <textarea class="form-control" rows="5" id="message" name="message"><?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($message);
	  }
	  ?></textarea>
     <br />
    <span id="remaining">160 characters remaining</span>
    <span id="messages">1 message(s)</span>
    </div>
  </div>
  
   </div>
  </div>
  <table cellpadding="5">
  <tr>
  <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="send" class="btn btn-success" value="Send">
    </div>
  </div>
  </td>
  </tr>
  </table>
    </form>
  <?php
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

<script>
$(document).ready(function(){
    var $remaining = $('#remaining'),
        $messages = $remaining.next();

    $('#message').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>