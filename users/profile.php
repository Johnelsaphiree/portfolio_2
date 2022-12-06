<?php
$page = 'aprofile';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$edit = $_GET['edit'];
$success = $_GET['success'];

$name = $_POST['name'];
$tell = $_POST['tell'];
$email = $_POST['email'];
$save = $_POST['save'];

if($save)
{
	$val = new validate();
	$val->email($email);
	$val->numeric($tell, 'Phone');

	$val->valid("$name,$tell,$email");
	
	if($val->error_code < 1)
	{
		$nval = new number_val();
				//numbers are cleaned up also
		$nval->length($tell, 'single');
		$tell = $nval->vnumber;
		$up = new update();
		$up->up('user', 'name', 'id', "$auser", "'$name'");
		$up->up('user', 'phone', 'id', "$auser", "'$tell'");
		$up->up('user', 'email', 'id', "$auser", "'$email'");
		
		header("location: profile.php?success=yes");
	}
}

$sel = new select();
$sel->pick('user', 'name, username, phone, email, reseller, rate', 'id', "$auser", '', 'record', '', '', '=', '');
$row = mysql_fetch_row($sel->query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>Profile</title>
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
  <li><a href="index.php">USER AREA</a></li>
  <?php
  if($edit == '')
  {
  ?>
  <li class="active">PROFILE</li>
  <?php
  }
  else
  {
  ?>
  <li><a href="profile.php">PROFILE</a></li>
  <li class="active">EDIT PROFILE</li>
  <?php
  }
  ?>
</ol>
  <h4>Profile</h4>
  <form class="form-horizontal" role="form" name="form1" method="post" action="profile.php?edit=yes">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($up->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$up->error_msg."</div>";
	}
	if($success != '')
	{
	echo "<div class='alert alert-success'>Successful!</div>";
	}
  ?>
  <div class="table-responsive">
  <table cellpadding="10">
  <tr>
  <td><span class="glyphicon glyphicon-leaf text-primary"></span> <strong>USERNAME:</strong></td>
  <td><?php echo $row[1];?></td>
  </tr>
  <tr>
  <td><span class="glyphicon glyphicon-user text-primary"></span> <strong>NAME:</strong><?php
  if($edit != '')
  {
	  echo '*';
  }
  ?></td>
  <td><?php
  if($edit == '')
  {
  echo $row[0];
  }
  else
  {
	  ?>
      <div class="form-group"> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?php
      echo $row[0];
	  ?>">
    </div>
  </div>
      <?php
  }
  ?>
  </td>
  </tr>
  <tr>
  <td><span class="glyphicon glyphicon-earphone text-primary"></span> <strong>PHONE:</strong><?php
  if($edit != '')
  {
	  echo '*';
  }
  ?></td>
  <td><?php
  if($edit == '')
  {
  echo $row[2];
  }
  else
  {
	  ?>
      <div class="form-group"> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="tell" placeholder="Phone" name="tell" value="<?php
     echo $row[2];
	  ?>">
    </div>
  </div>
      <?php
  }
  ?></td>
  </tr>
  <tr>
  <td><span class="glyphicon glyphicon-envelope text-primary"></span> <strong>EMAIL:</strong><?php
  if($edit != '')
  {
	  echo '*';
  }
  ?></td>
  <td><?php
  if($edit == '')
  {
  echo $row[3];
  }
  else
  {
	  ?>
      <div class="form-group"> 
    <div class="col-lg-10">
      <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="<?php
      echo $row[3];
	  ?>">
    </div>
  </div>
      <?php
  }
  ?></td>
  </tr>
  
  <?php
  if($row[4] == 'Y')
  {
	  ?>
  <tr>
  <td><span class="glyphicon glyphicon-retweet text-primary"></span> <strong>RESELLER RATE:</strong></td>
  <td><?php echo $row[5];?></td>
  </tr>
  <?php
  }//reseller
  ?>
  
  <tr>
  <td>&nbsp;</td>
  <td><div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <?php
	if($edit == '')
	{
		?>
     <a href="profile.php?edit=yes" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>
     <?php
	}
	else
	{
		?>
  <input type="submit" class="btn btn-success" value="Save" name="save" id="save">
         <?php
	}
	?>
    </div>
  </div></td>
  </tr>
  </table>  
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

  </body>
</html>
<?php
mysql_close($connect);
?>