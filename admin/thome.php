<?php
$page = 'athome';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$success = $_GET['success'];
$message = $_POST['message'];
$save = $_POST['save'];

//get msg
$gm = new select();
$gm->pick('pages', 'message', 'type', "'home'", '', 'record', '', '', '=', '');
$gm_row = @mysql_fetch_row($gm->query);

if($save)
{
	$val = new validate();
	$val->valid($message);
	if($val->error_code < 1)
	{
		$message = mysql_real_escape_string($message);
		if(stristr($message, '<img'))
		{
			$message = str_replace('<img', '<img class='.'img-responsive', $message);
		}
		if($gm->count > 0)
		{
			$up = new update();
			$up->up('pages', 'message', 'type', "'home'", "'$message'");
		}
		else
		{
			$up = new insert();
			$up->input('pages', 'id, message, type', "0, '$message', 'home'");
		}
		//echo mysql_error();
		$success = success(1);
		header("location: thome.php?success=$success");
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

    <title>Home Page</title>
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
    <script type="text/javascript" src="../tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: "textarea",
    theme: "modern",

    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],
   content_css: "css/content.css",
   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
</script>
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
  <li class="active">HOME PAGE</li>
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
    <form action="thome.php" method="post" enctype="multipart/form-data" name="wmsg" class="form-horizontal" role="form">
    <div class="panel panel-default">
  <div class="panel-heading">Home</div>
  <div class="panel-body">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
    <label for="message" class="control-label">Enter/Paste Content*</label> 
     <textarea class="form-control" rows="50" id="message" name="message"><?php
	 echo stripslashes($gm_row[0]);
	 ?></textarea>
    </div>
    </td>
    </tr>
    </table>
    
  </div><!--panel-->
  </div><!--panel-->
  
   <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" name="save" class="btn btn-success" value="Save">
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

  </body>
</html>
<?php
mysql_close($connect);
?>