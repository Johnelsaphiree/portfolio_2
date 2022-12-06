<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'aimg_upload';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$img = isset($_FILES['img']['tmp_name']) ? $_FILES['img']['tmp_name'] : '';
$img_type = isset($_FILES['img']['type']) ? $_FILES['img']['type'] : '';

$upload = isset($_POST['upload']) ? $_POST['upload'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$delete = isset($_GET['delete']) ? $_GET['delete'] : '';
	
if($upload)
{
$val = new image_validate();
$up = new now_upload();
//echo $img.', '.$img_type;
	$val->valid('Image', $img_type);
if($val->error_code < 1)
	{
		$up->up($img, 'upload', '../images/uploads/', 'image', '', 'add', '');
		$success = success(21);
		
	header("location: img_upload.php?success=$success");
	exit;
	}

}

if($delete != '')
{
	$un = new wipe();
	$un->file_wipe($delete, '../images/uploads/', '.jpg');
	$del = new delete();
	$del->gone('upload', 'img_no', "$delete");
	$success = success(1);
	header("location: img_upload.php?success=$success");
	exit;
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

    <title>Image Upload</title>
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
  <li class="active">IMAGE UPLOAD</li>
</ol>
   <?php
  if(@$val->error_code > 0)
	{
	echo "<div class='alert alert-danger'>".@$val->error_msg."</div>";
	}
	
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	}
  ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">New Upload</h3>
  </div>
  <div class="panel-body">
  <form action="img_upload.php" method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" role="form">
  
  <table cellpadding="20">
  <tr>
  <td>
  <div class="form-group">
 <input name="img" type="file">
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Upload" name="upload" id="upload">
    </div>
  </div>
</td>
</tr>
</table>
  </form>
  
  </div>
</div>

<p class="lead">Images</p>
  <?php
 //query images
 $qi = new select();
 $qi->pick('upload', 'img_no', '', '', '', 'record', '', '', '', '');
 if($qi->count > 0)
 {
	 ?>
     <table class="table">
     <tr>
     <th>IMAGE</th>
     <th>SOURCE</th>
     <th>DELETE</th>
     </tr>
     <?php
	 while($qi_row = mysql_fetch_row($qi->query))
	 {
		 ?>
       <tr valign="top">
       <td><img src="../images/uploads/<?php echo $qi_row[0];?>.jpg" class="img-responsive img-thumbnail"></td>
       <td><strong class="text-info">../images/uploads/<?php echo $qi_row[0];?>.jpg</strong></td>
       <td><a href="img_upload.php?delete=<?php echo $qi_row[0];?>">Delete</a></td>
       </tr>
         <?php
	 }
	  ?>
    </table>
     <?php
 }
 else
 {
	 echo $qi->error_msg;
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

  </body>
</html>
<?php
mysql_close($connect);
?>