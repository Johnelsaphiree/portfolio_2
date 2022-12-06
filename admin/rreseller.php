<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'arreseller';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$sin = $_GET['sin'];

$name = $_POST['name'];
$tell = $_POST['tell'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$xpassword = md5($password);
$cpassword = $_POST['cpassword'];
$rate = $_POST['rate'];
$register = $_POST['register'];

if($register)
{
	$val = new validate();
	$val->match($password, $cpassword);
	$val->email($email);
	$val->numeric($tell, 'Phone');
	$val->numeric($rate, 'Rate');

	$val->valid("$name,$tell,$email,$username,$password,$cpassword,$rate");
	
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
		
		$in = new insert();
		$in->input('user', 'id, name, username, password, phone, email, balance, reserved, date_created, log_date, reseller, rate', "0, '$name', '$username', '$xpassword', '$tell', '$email', 0, 0, '$now', '$now', 'Y', $rate");
		
		//get welcome message
//get msg
  $gwmsg = new select();
  $gwmsg->pick('message', 'message', 'type', "'reseller'", '', 'record', '', '', '=', '');
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
			$in->success_code = 12;
			$in->success_msg = success($in->success_code);
			
$to = $email;
//$subject = "Welcome to $csite_name";
$subject = $csite_name;

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

mail($to, $subject, $message, $headers);

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
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Reseller Registration</title>

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
	include("../body/sidex.php");
	?>
    <div class="col-md-9">
     <ol class="breadcrumb">
  <li><a href="index.php">DASHBOARD</a></li>
  <li class="active">RESELLER REGISTRATION</li>
</ol>
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
	echo "<div class='alert alert-success'>".$sin."</div>";
	}
	?>
    <p class="lead">PERSONAL INFO:</p>

  <form class="form-horizontal" role="form" name="form1" method="post" action="rreseller.php">
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
  
   <div class="form-group">
  <label for="rate" class="col-lg-2 control-label">Reseller Rate*</label>
    <div class="col-lg-offset-2 col-lg-10">
      <input type="text" class="form-control" id="rate" placeholder="Reseller Rate" name="rate" value="<?php
      if($in->success_code < 1)
	  {
		  echo stripslashes($rate);
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
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Register" name="register" id="register">
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
	$('.carousel').carousel({
  interval: 5000
})
	</script>
  </body>
</html>
<?php
mysql_close($connect);
?>