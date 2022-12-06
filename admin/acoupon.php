<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aacoupon';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];

$code = isset($_POST['code']) ? $_POST['code'] : '';
$date_time = isset($_POST['date_time']) ? $_POST['date_time'] : '';
$unit = isset($_POST['unit']) ? $_POST['unit'] : '';
$create = isset($_POST['create']) ? $_POST['create'] : '';

if($create)
{
	$val = new validate();
	$val->numeric($unit, 'Unit');
	$val->valid("$code,$date_time,$unit");
	if($val->error_code < 1)
	{
				$xdate_time = $date_time.' 00:00:00';
		$in = new insert();
		$in->input('coupon', 'id, code, unit, exp_date', "0, '$code', $unit, '$xdate_time'");
		//echo mysql_error();
		if($in->success_code > 0)
		{
		$success = success(1);
		header("location: acoupon.php?success=$success");
		exit;
		}
		else
		{
			$val->error_code = 47;
			$val->error_msg = error($val->error_code);
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

    <title>Add Coupon</title>
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
  <li class="active">ADD COUPON</li>
</ol>
<h4>Add Coupon</h4>
<table width="70%">
<tr>
<td>
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
	?>
 <form action="acoupon.php" method="post" enctype="multipart/form-data" name="sms_form" class="form-horizontal" role="form">
 
  <div class="form-group">
    <label for="code" class="control-label">Coupon Code*:</label>
      <input name="code" type="text" class="form-control input-sm" id="code" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($code);
	  }
	  ?>" placeholder="Coupon Code">
  </div>
  
  <div class="form-group">
    <label for="unit" class="control-label">Unit Value*:</label>
      <input name="unit" type="text" class="form-control input-sm" id="unit" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($unit);
	  }
	  ?>" placeholder="Unit Value">
  </div>
 
  
            <?php
			$dtp_date = date('Y-m-d', time()).'T'.date('H:i:s', time()).'Z';
			?>
            <div class="form-group">
              <label for="dtp_input1" class="control-label">Expiry Date*:</label>
                <div class="input-group date form_datetime" data-date="<?php echo $dtp_date;?>" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1">
                    <input class="form-control" type="text" value="<?php
      if($val->error_code > 0)
	  {
		  echo stripslashes($date_time);
	  }
	  ?>" readonly name="date_time" id="date_time">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" />
            </div>
           
  <div class="form-group">
      <input type="submit" name="create" class="btn btn-success" value="Create">
  </div>
    </form>
    </td>
    </tr>
    </table>

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
		startView: 3,
		forceParse: 0,
        showMeridian: 1
    });
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>