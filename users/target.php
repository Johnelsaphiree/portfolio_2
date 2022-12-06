<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'atarget';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$step = $_GET['step'];
$view = $_GET['view'];

$count = $_POST['count'];
$state = $_POST['state'];
$continue = $_POST['continue'];

$sid = $_POST['sid'];
$message = $_POST['message'];
$send = $_POST['send'];

if($send)
{
	$val = new validate();
	$val->valid("$sid,$message");
	if($val->error_code < 1)
	{
		$mcount = strlen($message);
		$xmcount = ceil($mcount/160);
		$xmcount = (int)$xmcount;
		$scount = $_SESSION['xcount'];
		$sstate = $_SESSION['xstate'];
		
		$in = new insert();
		$in->input('target_job', 'id, sid, message, count, state, start, user, mcount', "0, '$sid', '$message', $scount, '$sstate', 0, $auser, $xmcount");
		//echo mysql_error();
		$success = success(19);
		header("location: target.php?success=$success");
		exit;
	}
}

if($continue)
{
	$val = new validate();
	$val->numeric($count, 'Number Count');
	$val->valid($count);
	if($val->error_code < 1)
	{
		//check state count
		$check = new select();
		$check->pick('target', 'count(*)', 'state', "'$state'", '', 'record', '', '', '=', '');
		$check_row = mysql_fetch_row($check->query);
		if($check_row[0] < $count)
		{
			$val->error_code = 46;
			$val->error_msg = error($val->error_code)."Total amount of available numbers for $state is: ".number_format($check_row[0]);
		}
		else
		{
		$_SESSION['xstate'] = $state;
		$_SESSION['xcount'] = $count;
		header("location: target.php?step=2");
		}
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

    <title>Targeted SMS</title>
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
  <li class="active">TARGETED SMS</li>
</ol>
 <h4>Targeted SMS</h4>
 <?php
 if($view == '')
 {
	 ?>
 <p><a href="target.php?view=yes"><span class="glyphicon glyphicon-eye-open"></span> View Database</a></p>
  <?php
 }
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
	if($view == '')
	{
		?>
        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php
    if($step == '')
	{
		echo 'Step 1';
	}
	elseif($step == 2)
	{
		echo 'Step 2';
	}
	?></h3>
  </div>
  <div class="panel-body">
  <form action="target.php?step=<?php echo $step;?>" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <?php
  if($step == '')
	{
		?>
  <div class="form-group">
  <label for="state" class="control-label">Select State*</label>
      <select class="form-control" name="state" id="state">
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
    <label for="count" class="control-label">Number Count*:</label>
      <input name="count" type="text" class="form-control input-sm" id="count" value="" placeholder="Number Count">
    </div>
  
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Continue" name="continue" id="continue">
  </div>
    <?php
	}
	elseif($step == 2)
	{
		?>
        <div class="form-group">
    <label for="sid" class="control-label">Sender ID</label>
      <input name="sid" type="text" class="form-control input-sm" id="sid" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($sid);
	  }
	  ?>" maxlength="11" placeholder="Sender ID(11 alpha numeric Characters)">
    </div>
    
     <div class="form-group">
    <label for="message" class="control-label">Enter/Paste Message</label> 
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
    
    <div class="form-group">
      <input type="submit" name="send" class="btn btn-success" value="Send">
    </div>
        <?php
	}
	?>
</td>
</tr>
</table>
  </form>
  
  </div>
</div>
<?php
	}//view
	else
	{
		
   //get numbers
   $num = new select();
   $num->pick('target', 'state, count(*)', '', '', '', 'record', 'state', 'state', '', '');
   if($num->count > 0)
   {
	   ?>
       <div class="table-responsive">
       <table class="table table-striped">
       <tr>
       <th>STATE</th>
       <th>COUNT</th>
       </tr>
       <?php
	   while($num_row = mysql_fetch_row($num->query))
	   {
		   ?>
        <tr>
        <td><?php echo $num_row[0];?></td>
        <td><?php echo number_format($num_row[1]);?></td>
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