<?php
$page = 'amessage';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$message = $_POST['message'];
$rmessage = $_POST['rmessage'];
$save = $_POST['save'];

if($save)
{
		$up = new update();
		$up->up('message', 'message', 'type', "'welcome'", "'$message'");
		$up->up('message', 'message', 'type', "'reseller'", "'$rmessage'");
		$success = success(1);
		header("location: wmsg.php?success=$success");
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

    <title>Messages</title>
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
  <li class="active">MESSAGES</li>
</ol>
 
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}

	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
	?>
    <form action="wmsg.php" method="post" enctype="multipart/form-data" name="wmsg" class="form-horizontal" role="form">
    <div class="panel panel-default">
  <div class="panel-heading">Welcome Message</div>
  <div class="panel-body">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <?php
  //get msg
  $gwmsg = new select();
  $gwmsg->pick('message', 'message', 'type', "'welcome'", '', 'record', '', '', '=', '');
  $gwmsg_row = mysql_fetch_row($gwmsg->query);
  ?>
  <div class="form-group">
    <label for="message" class="control-label">Enter/Paste Message*</label> 
     <textarea class="form-control" rows="5" id="message" name="message"><?php
	 echo $gwmsg_row[0];
	 ?></textarea>
     <br />
    <span id="remaining">160 characters remaining</span>
    <span id="messages">1 message(s)</span>
    </div>
    </td>
    </tr>
    </table>
    
  </div><!--panel-->
  </div><!--panel-->
  
  <div class="panel panel-default">
  <div class="panel-heading">Reseller Welcome Message</div>
  <div class="panel-body">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <?php
  //get msg
  $grmsg = new select();
  $grmsg->pick('message', 'message', 'type', "'reseller'", '', 'record', '', '', '=', '');
  $grmsg_row = mysql_fetch_row($grmsg->query);
  ?>
  <div class="form-group">
    <label for="rmessage" class="control-label">Enter/Paste Message*</label> 
     <textarea class="form-control" rows="5" id="rmessage" name="rmessage"><?php
	 echo $grmsg_row[0];
	 ?></textarea>
     <br />
    <span id="rremaining">160 characters remaining</span>
    <span id="rmessages">1 message(s)</span>
    </div>
    </td>
    </tr>
    </table>
    
  </div><!--panel-->
  </div><!--panel-->
  
   <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="save" class="btn btn-success" value="Save">
    </div>
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

<script>
$(document).ready(function(){
    var $remaining = $('#rremaining'),
        $messages = $remaining.next();

    $('#rmessage').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

  </body>
</html>
<?php
mysql_close($connect);
?>