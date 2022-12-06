<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'akeyword';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

//get info
	$gnet = new select();
	$gnet->pick('keyword', 'keyword', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);

if($save)
{
	$val = new validate();
	$val->valid($keyword);
	if($val->error_code < 1)
	{
		$keyword = str_replace(' ', '', $keyword);
		if($gnet->count > 0)
		{
			$up = new update();
			$up->up('keyword', 'keyword', 'id', "1", "'$keyword'");
		}
		else
		{
			$up = new insert();
			$up->input('keyword', 'id, keyword', "0, '$keyword'");
		}
		$success = success(1);
		header("location: keyword.php?success=$success");
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

    <title>Banned Keywords</title>
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
  <li class="active">BANNED KEYWORDS</li>
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

  <form class="form-horizontal" role="form" name="form1" method="post" action="keyword.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  
  <div class="form-group">
    <label for="keyword" class="control-label">Keyword*</label> 
     <textarea class="form-control" rows="3" id="keyword" name="keyword"><?php
	 echo $gnet_row[0];
	 ?></textarea>
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