<?php
$page = 'update';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$update = isset($_POST['update']) ? $_POST['update'] : '';

if($update)
{
		// Name of the file
$filename = 'update.sql';

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
if (substr($line, 0, 2) == '--' || $line == '')
    continue;

// Add this line to the current segment
$templine .= $line;
// If it has a semicolon at the end, it's the end of the query
if (substr(trim($line), -1, 1) == ';')
{
    // Perform the query
    mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
    // Reset temp variable to empty
    $templine = '';
}
}

$up = mysql_query("alter table cpages add elink varchar(200) not null default ''");
$up = mysql_query("alter table cpages change message message longtext not null default ''");

 $success = "Software updated successfully";
 header("location: update.php?success=$success");
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
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Update</title>
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
 
  
  <!--avHtFe9bYjI_xfz1HftxNEGKH8w-->
</head>

  <body>
  
    <div class="container">
    
    <div class="row">
  
    <div class="col-md-9">
    <p><img src="images/logo.jpg" class="img-responsive"></p>
      <h1>Update App</h1>
       <?php
	if($success != '')
	{
	echo "<div class='alert alert-success'>".$success."</div>";
	?>
   <a href="index.php" class="btn btn-info">Continue</a>
    <?php
	}
  ?>
  <form class="form-horizontal" role="form" name="form1" method="post" action="update.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <input type="submit" class="btn btn-primary" value="Update" name="update" id="update">
    </div>
  </div>
</td>
</tr>
</table>
  </form>
  
  </div>
</div>

</div><!--col-->
</div><!--row-->
    </div><!-- /.container -->
   

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>