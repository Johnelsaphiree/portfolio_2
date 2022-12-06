<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'arunit';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$credit = isset($_POST['credit']) ? $_POST['credit'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

//get info
	$gnet = new select();
	$gnet->pick('scredit', 'credit', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);

if($save)
{
	$val = new validate();
	$val->numeric($credit, 'Credit');
	$val->valid($credit);
	if($val->error_code < 1)
	{
		if($gnet->count > 0)
		{
			$up = new update();
			$up->up('scredit', 'credit', 'id', "1", "$credit");
		}
		else
		{
			$up = new insert();
			$up->input('scredit', 'id, credit', "0, $credit");
			
		}
		$success = success(1);
		header("location: runit.php?success=$success");
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

    <title>Signup Credit</title>
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
  <li class="active">SIGNUP CREDIT</li>
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
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SIGNUP CREDIT</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="runit.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="credit" class="control-label">Credit*:</label> 
      <input type="text" class="form-control" id="credit" placeholder="Credit*" name="credit" value="<?php
		  echo $gnet_row[0];
	  ?>">
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Save" name="save" id="save">
    </div>
  </div>
</td>
</tr>
</table>
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

  </body>
</html>
<?php
mysql_close($connect);
?>