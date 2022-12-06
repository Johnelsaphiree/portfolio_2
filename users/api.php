<?php
$page = 'aapi';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');

$url = $_POST['url'];
$set = $_POST['set'];
$rurl = $_POST['rurl'];
$rset = $_POST['rset'];

if($set)
{
	$val = new validate();
	$val->valid($url);
	
	if($val->error_code < 1)
	{
		//check existing link
		$check = new select();
		$check->pick('return_url', '*', 'user', "$auser", '', 'record', '', '', '=', '');
		if($check->count < 1)
		{
			$in = new insert();
			$in->input('return_url', 'id, user, url', "0, $auser, '$url'");
			if($in->error_code > 0)
			{
				$in->error_code = 26;
				$in->error_msg = error($in->error_code);
			}
		}
		else
		{
			$up = new update();
			$up->up('return_url', 'url', 'user', "$auser", "'$url'");
			if($up->error_code > 0)
			{
				$up->error_code = 26;
				$up->error_msg = error($up->error_msg);
			}
		}
	}
}


if($rset)
{
	$rval = new validate();
	$rval->valid($rurl);
	
	if($rval->error_code < 1)
	{
		//check existing link
		$rcheck = new select();
		$rcheck->pick('report_url', '*', 'user', "$auser", '', 'record', '', '', '=', '');
		if($rcheck->count < 1)
		{
			$rin = new insert();
			$rin->input('report_url', 'id, user, url', "0, $auser, '$rurl'");
			if($rin->error_code > 0)
			{
				$rin->error_code = 26;
				$rin->error_msg = error($rin->error_code);
			}
		}
		else
		{
			$rup = new update();
			$rup->up('report_url', 'url', 'user', "$auser", "'$rurl'");
			if($rup->error_code > 0)
			{
				$rup->error_code = 26;
				$rup->error_msg = error($rup->error_msg);
			}
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

    <title>API</title>
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.css" rel="stylesheet">

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
  <li class="active">API</li>
</ol>
  <h4>API</h4>
  
  <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SET REDIRECTION URL</h3>
  </div>
  <div class="panel-body">
  <?php
  if($val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$val->error_msg."</div>";
	}
	if($in->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$in->success_msg."</div>";
	}
	
	if($up->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$up->success_msg."</div>";
	}
	if($in->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$in->error_msg."</div>";
	}
	
	if($up->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$up->error_msg."</div>";
	}
	
	//get user existing url if available
	$ulink = new select();
	$ulink->pick('return_url', 'url', 'user', "$auser", '', 'record', '', '', '=', '');
	if($ulink->count > 0)
	{
		$urow = mysql_fetch_row($ulink->query);
	}
  ?>
  <form class="form-horizontal" role="form" name="form1" method="post" action="api.php">
  <div class="form-group">
  <label for="url" class="col-lg-2 control-label">Redirection URL*</label> 
    <div class="col-lg-10">
    <table width="100%">
    <tr>
    <td width="10%"><strong class="text-success">http://www.</strong></td>
      <td><input type="text" class="form-control" id="url" placeholder="Enter redirection URL" name="url" value="<?php
      if($ulink->count > 0)
	  {
		  echo $urow[0];
	  }
	  ?>">
      </td>
      </tr>
      </table>
      <p><em><strong>NOTE: </strong>Attach at least one GET variable to the link for this to work. Example: <?php echo $csite_url;?>/sms_api.php?validate=yes</em></p>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Set" name="set" id="set">
    </div>
  </div>
  </form>
  
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">SET REPORT URL</h3>
  </div>
  <div class="panel-body">
  <?php
  if($rval->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$rval->error_msg."</div>";
	}
	if($rin->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$rin->success_msg."</div>";
	}
	
	if($rup->success_code > 0)
	{
	echo "<div class='alert alert-success'>".$rup->success_msg."</div>";
	}
	if($rin->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$rin->error_msg."</div>";
	}
	
	if($rup->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".$rup->error_msg."</div>";
	}
	
	//get user existing url if available
	$urlink = new select();
	$urlink->pick('report_url', 'url', 'user', "$auser", '', 'record', '', '', '=', '');
	if($urlink->count > 0)
	{
		$urrow = mysql_fetch_row($urlink->query);
	}
  ?>
  <form class="form-horizontal" role="form" name="form2" method="post" action="api.php">
  <div class="form-group">
  <label for="rurl" class="col-lg-2 control-label">Report URL*</label> 
    <div class="col-lg-10">
    <table width="100%">
    <tr>
    <td width="10%"><strong class="text-success">http://www.</strong></td>
      <td><input type="text" class="form-control" id="rurl" placeholder="Enter report URL" name="rurl" value="<?php
      if($urlink->count > 0)
	  {
		  echo $urrow[0];
	  }
	  ?>">
      </td>
      </tr>
      </table>
      <p><em><strong>NOTE: </strong>Attach at least one GET variable to the link for this to work. Example: <?php echo $csite_url;?>/sms_api.php?report=yes</em></p>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Set" name="rset" id="rset">
    </div>
  </div>
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