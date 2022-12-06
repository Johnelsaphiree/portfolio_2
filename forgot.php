<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('up.php');

$email = $_POST['email'];
$ok = $_POST['ok'];

$xcode = $_GET['xcode'];
$id = $_GET['id'];

$spin = $_GET['spin'];

$val = new validate();

if($ok)
{
	$val->email($email);
	$val->valid($email);
	
	if($val->error_code < 1)
	{
		//check email
		$check = new select();
		$check->pick('user', 'id, username', 'email', "'$email'", '', 'record', '', '', '=', '');
		if($check->error_code > 0)
		{
			$val->error_code = 23;
			$val->error_msg = error($val->error_code);
		}
		else
		{
			$crow = mysql_fetch_row($check->query);
			//generate random code and input code into database
		$code_box = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9);
	   $code_shuff = shuffle($code_box);
	   $code = $code_box[0].$code_box[5].$code_box[11].$code_box[16].$code_box[22].$code_box[29].$code_box[37].$code_box[43].$code_box[51].$code_box[53].$code_box[55].$code_box[57].$code_box[59];
	   $pin = new insert();
	   $pin->input('token', 'id, code, date', "0, '$code', '$now'");
	   
	   if($pin->error_code > 0)
	   {
		   while($pin->error_code > 0)
		   {
			   $pin->error_code = 0;
			   
			   $code_shuff = shuffle($code_box);
	    $code = $code_box[0].$code_box[5].$code_box[11].$code_box[17].$code_box[23].$code_box[30].$code_box[38].$code_box[45].$code_box[53].$code_box[55].$code_box[57].$code_box[59].$code_box[61];
	   $pin = new insert();
	   $pin->input('token', 'id, code, date', "0, '$code', '$now'");
		   }
	   }
	   $pin->success_code = 10;
	   $pin->success_msg = success($pin->success_code);
			//send email
			$to = $email;
//$subject = "$csite_name password reset";
$subject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
$message.= "<p>Hello $crow[1]</p>

<p>To change your password on $csite_name kindly follow the link below:</p>

<p>$csite_url/forgot.php?id=$crow[0]&xcode=$code</p>

<p>This link will be available for 24hr, if the above link is not clicked withing this time, you will have to start the process from the begining. If you did not send a request for a reset of password, kindly ignore this mail</p>";

$message .= "</body></html>";

mail($to, $subject, $message, $headers);	

header("Location: " . $_SERVER["REQUEST_URI"]."?spin=".$pin->success_msg);
    exit;
		}
	}
}

if($id != '' && $xcode != '')
{
	//validate code and user
	$xcheck = new select();
	$ucheck = new select();
	$xcheck->pick('token', '*', 'code', "'$xcode'", '', 'record', '', '', '=', '');
	$ucheck->pick('user', 'username', 'id', "$id", '', 'record', '', '', '=', '');
	if($xcheck->count < 1)
	{
		$val->error_code = 24;
		$val->error_msg = error($val->error_code);
	}
	elseif($ucheck->count < 1)
	{
		$val->error_code = 25;
		$val->error_msg = error($val->error_code);
	}
	
	if($val->error_code < 1)
	{
		$ucrow = mysql_fetch_row($ucheck->query);
		//encrypt code
		$ecode = md5($xcode);
		//update password
		$up = new update();
		$up->up('user', 'password', 'id', "'$id'", "'$ecode'");
		
		if($up->success_code > 0)
		{
			$up->success_code = 5;
			$up->success_msg = success($up->success_code)."<p>Login Details:<br /><br /><strong>USERNAME:</strong> $ucrow[0]<br /><strong>PASSWORD:</strong> $xcode<br /><br />Kindly login to change your password.</p>";
			//delete code from db
			$del = new delete();
			$del->gone('token', 'code', "'$xcode'");
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reset password">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Forgot Password: Password reset</title>

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
<?php
 include('body/head.php');
 ?>
    <div class="container">
    <div class="row">
    <?php
	include("body/sidex.php");
	?>
    <div class="col-md-9">
    <h1>Forgot Password</h1>
    <br />
    <?php
	if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($spin != '')
	{
	echo "<div class='alert alert-success'>".$spin."</div>";
	}
	if($up->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$up->success_msg."</div>";
	}
	
	if($spin == '' && $id == '' && $xcode == '')
	{
	?>
  <form class="form-inline" role="form" name="form1" method="post" action="forgot.php">
  <div class="form-group">
    <label for="name" class="col-lg-4 control-label">Email*</label>
    <div class="col-md-10">
      <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="">
    </div>
  </div>
  <br />
  <br />
  <div class="form-group">
    <div class="col-lg-12">
      <input type="submit" class="btn btn-primary" value="OK" name="ok" id="ok">
    </div>
  </div>
</form>
<p><small><em><strong>NOTE:</strong> Enter your email address on <?php echo $csite_name;?>.</em></small></p>
<?php
	}
	?>
    </div>
    </div>
    
    </div><!-- /.container -->
    <?php
	include('body/foot.php');
	?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect);
?>