<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'register';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');
include('up.php');

@$sin = $_GET['sin'];

$name = $_POST['name'];
$tell = $_POST['tell'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$xpassword = md5($password);
$cpassword = $_POST['cpassword'];
$xcode = $_POST['xcode'];
$register = $_POST['register'];

$in = new insert();

if($register)
{
	$val = new validate();
	$val->captcha($xcode);
	$val->match($password, $cpassword);
	$val->email($email);
	$val->numeric($tell, 'Phone');

	$val->valid("$name,$tell,$email,$username,$password,$cpassword,$xcode");
	
	if($val->error_code < 1)
	{
		$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tell, 'single');
		$tell = $nval->vnumber;
	//check email
	$echeck = new select();
	$echeck->pick('user', '*', 'email', "'$email'", '', 'record', '', '', '=', '');
	if($echeck->count > 0)
	{
		$val->error_code = 29;
		$val->error_msg = error($val->error_code);
	}
	else
	{
		//bill Admin
		$abill = new process();
		$abill->pay(0, 1, 'transfer');
		
		//check and credit
			$credit = new select();
			$credit->pick('scredit', 'credit', 'id', "1", '', 'record', '', '', '=', '');
			if($credit->count > 0)
			{
				$credit_row = mysql_fetch_row($credit->query);
				if($credit_row[0] > 0)
				{
					$cbal = $credit_row[0];
				}
				else
				{
					$cbal = 0;
				}
			}
			else
			{
				$cbal = 0;
			}
		
		$in->input('user', 'id, name, username, password, phone, email, balance, reserved, date_created, log_date', "0, '$name', '$username', '$xpassword', '$tell', '$email', $cbal, 0, '$now', '$now'");
		
		//get welcome message
//get msg
  $gwmsg = new select();
  $gwmsg->pick('message', 'message', 'type', "'welcome'", '', 'record', '', '', '=', '');
  $gwmsg_row = mysql_fetch_row($gwmsg->query);
  
		$smsmsg = $gwmsg_row[0];
		$abill->sendsms($csenderid, $tell, $smsmsg);
	}
	
	}
	if($in->error_code > 0)
	{
		$in->error_code = 11;
		$in->error_msg = error($in->error_code);
	}
	else
	{
		if($in->success_code > 0)
		{
			$in->success_code = 2;
			$in->success_msg = success($in->success_code);
			
$to = $email;
$ato = $cemail;
//$subject = "Welcome to $csite_name";
//$asubject = 'User Registration';
$subject = $csite_name;
$asubject = $csite_name;

$headers = "From: " . $cemail . "\r\n";
$headers .= "Reply-To: ". $cemail . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="$logo_link" alt="Logo" />';
  
$message.= "<p>$gwmsg_row[0]</p>

<p><strong>LOGIN DETAILS:</strong><br />
USERNAME: $username<br />
PASSWORD: $password
</p>";

$message .= "</body></html>";

$amessage = '<html><body>';
$amessage .= '<img src="$logo_link" alt="Logo" />';
$amessage.= "<p>The following user just registered:</p>

<p>
<strong>Username: </strong>$username<br />
<strong>Name: </strong>$name<br />
<strong>Phone: </strong>$tell<br />
<strong>Email: </strong>$email<br />
</p>
";
$amessage .= "</body></html>";

mail($to, $subject, $message, $headers);
mail($ato, $asubject, $amessage, $headers);

header("Location: " . $_SERVER["REQUEST_URI"]."?sin=".$in->success_msg);
    exit;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $csite_name;?> registration page">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Registration</title>

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
    <h1>Registration</h1>
    <br />
    <?php
	if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($in->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$in->error_msg."</div>";
	}
	if($sin != '')
	{
	echo "<div class='alert alert-success'>".$sin.", <a href='index.php' class='alert-link'>Click HERE to Login</a></div>";
	}
	?>
    <p class="lead">PERSONAL INFO:</p>

  <form class="form-horizontal" role="form" name="form1" method="post" action="register.php">
  <div class="form-group">
    <label for="name" class="col-lg-2 control-label">Full Name*</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="name" placeholder="Full Name" name="name" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($name);
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="tell" class="col-lg-2 control-label">Phone*</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="tell" placeholder="Phone" name="tell" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($tell);
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-lg-2 control-label">Email*</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($email);
	  }
	  ?>">
    </div>
  </div>
  <br />
  <p class="lead">LOGIN INFO:</p>
  <div class="form-group">
    <label for="username" class="col-lg-2 control-label">Username*</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($username);
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-lg-2 control-label">Password*</label>
    <div class="col-lg-10">
      <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($password);
	  }
	  ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="cpassword" class="col-lg-2 control-label">Confirm Password*</label>
    <div class="col-lg-10">
      <input type="password" class="form-control" id="cpassword" placeholder=" Confirm Password" name="cpassword" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($cpassword);
	  }
	  ?>">
    </div>
  </div>
  
  <div class="form-group">
  <label for="xcode" class="col-lg-2 control-label">Captcha*</label>
  <p><img src="functions/captcha.php" align="absmiddle" class="img-responsive"/></p>
    <div class="col-lg-offset-2 col-lg-10">
      <input type="text" class="form-control" id="xcode" placeholder="Enter Code" name="xcode">
  </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Register" name="register" id="register">
    </div>
  </div>
</form>

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