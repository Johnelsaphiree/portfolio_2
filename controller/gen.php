<?php
date_default_timezone_set('Africa/Lagos');
$now = date('Y-m-d H:i:s', time());

$page = 'agen';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('up.php');

$lic = isset($_GET['lic']) ? $_GET['lic'] : '';

$duration = isset($_POST['duration']) ? $_POST['duration'] : '';
$generate = isset($_POST['generate']) ? $_POST['generate'] : '';

if($generate)
{
	switch($duration)
	{
		case '1 Year':
		$exp = time() + 29030400;
		break;
		case '2 Years':
		$exp = time() + 58060800;
		break;
		case '3 Years':
		$exp = time() + 87091200;
		break;
		case '4 Years':
		$exp = time() + 116121600;
		break;
		case '1 Month':
		$exp = time() + 2419200;
		break;
	}
	$dom = $_SERVER['HTTP_HOST'];
	$dom = str_replace('www.', '', $dom);
	$code_box = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 2, 3, 4, 5, 6, 7, 8, 9);
	   $code_shuff = shuffle($code_box);
	   $code = $code_box[0].$code_box[5].$code_box[11].$code_box[16].$code_box[22].$code_box[29].$code_box[37].$code_box[43].$code_box[51].$code_box[53].$code_box[55].$code_box[57].$code_box[59].$code_box[8].$code_box[14].$code_box[19].$code_box[25].$code_box[32].$code_box[40].$code_box[46].$code_box[54].$code_box[56].$code_box[58];
	   //check exist
	   $check = new select();
	   $check->pick('glic', '*', 'id', "1", '', 'record', '', '', '=', '');
	   if($check->count < 1)
	   {
	$in = new insert();
	$in->input('glic', 'id, lkey, url, exp, yr', "0, '$code', '$dom', $exp, '$duration'");
	   }
	   else
	   {
		   $up = new update();
		   $up->up('glic', 'lkey', 'id', "1", "'$code'");
		   $up->up('glic', 'url', 'id', "1", "'$dom'");
		   $up->up('glic', 'exp', 'id', "1", "$exp");
		   $up->up('glic', 'yr', 'id', "1", "'$duration'");
	   }
	
	header("location: gen.php?lic=yes");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../images/favicon.jpg">

    <title>Generate License</title>

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
   <div class="row">
   <?php
   include('side.php')
   ?>
   <div class="col-md-9">
   <p><strong class="lead">Generate License</strong></p>
   <?php
   if($lic == '')
   {
	   ?>
   <form action="gen.php" method="post" enctype="multipart/form-data" name="form1" id="form1" role="form">
         <div class="form-group" id="sport">
    <label for="duration">Duration</label>
     <select name="duration" class="form-control">
     <option value="1 Month">1 Month</option>
     <option value="1 Year">1 Year</option>
     <option value="2 Years">2 Years</option>
     <option value="3 Years">3 Years</option>
     <option value="4 Years">4 Years</option>
</select>
</div>
   
   <div class="form-group">
      <input name="generate" type="submit" id="generate" value="Generate" class="btn btn-primary"> 
      </div>
      </form>
      <?php
   }//lic
   else
   {
	   //get lic
	   $gl = new select();
	   $gl->pick('glic', 'lkey', 'id', "1", '', 'record', '', '', '=', '');
	   $gl_row = mysql_fetch_row($gl->query);
	 ?>
     <div class="panel panel-primary">
     <div class="panel-heading">Key</div>
     <div class="panel-body">
     <?php
	 echo $gl_row[0];
	 ?>
     </div><!--panel-->
     </div><!--panel-->
     <?php  
   }
   ?>
   
   </div><!--col-->
   
   </div><!--row-->
      
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect)
?>