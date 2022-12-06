<?php
$page = 'aduplicate';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$tnum = $_POST['tnum'];
$submit = $_POST['submit'];

if($submit)
{
	$val = new validate();
	$val->valid($tnum);
	if($val->error_code < 1)
	{
		$dval = new number_clean();
		$dval->duplicate($tnum);
		if($dval->error_code > 0)
		{
			$val->error_code = $dval->error_code;
			$val->error_msg = $dval->error_msg;
		}
		else
		{
			$success = success(1)." Duplicates removed: $dval->num_diff | Numbers remaining: $dval->rarr_count";
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

    <title>Duplicates Remover</title>
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
  <li class="active">DUPLICATES REMOVER</li>
</ol>
 <h4>Duplicates Remover</h4>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">REMOVE DUPLICATES</h3>
  </div>
  <div class="panel-body">
  <!--<em class="text-warning"><strong>Note:</strong> You can work on 100,000 numbers at a time</em>-->
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
  <form class="form-horizontal" role="form" name="form1" method="post" action="duplicate.php">
   <table cellpadding="10" width="100%" align="center">
  <tr>
  <td>
 <div class="form-group">
    <label for="tnum" class="control-label"><p align="left"><small>Enter/Paste numbers separated by comma(,) OR Enter/Paste numbers line by line <a href="../images/number_format.png">View Example</a></small></p></label>
     <textarea class="form-control input-sm" rows="5" id="tnum" name="tnum"><?php
      if($success == '')
	  {
		  echo stripslashes($tnum);
	  }
	  else
	  {
		  echo $dval->rnumber;
	  }
	  ?></textarea>
  </div>
  
  <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Submit" name="submit" id="submit">
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

  </body>
</html>
<?php
mysql_close($connect);
?>