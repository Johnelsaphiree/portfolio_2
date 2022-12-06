<?php
$page = 'setup';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/upload_download.php');

//check stat
$check = new select();
$check->pick('info', 'stat', 'id', "1", '', 'record', '', '', '=', '');
if($check->count > 0)
{
	$check_row = mysql_fetch_row($check->query);
}

$style = $_POST['style'];
$url = $_POST['url'];
$name = $_POST['name'];
$save = $_POST['save'];

$import = $_POST['import'];
$username = $_POST['username'];
$password = $_POST['password'];
$host = $_POST['host'];
$database = $_POST['database'];

if($import)
{
	$val = new validate();
	$val->valid("$database,$username,$host");
	if($val->error_code < 1)
	{
		// Name of the file
$filename = 'vsms.sql';
// MySQL host
$mysql_host = $host;
// MySQL username
$mysql_username = $username;
// MySQL password
$mysql_password = $password;
// Database name
$mysql_database = $database;

// Connect to MySQL server
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
// Select database
mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());

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
$iup = new update();
$iup->up('info', 'stat', 'id', "1", "'import'");
header("location: setup.php");
 //echo "Tables imported successfully";
	}
}

if($save)
{
	$aval = new validate();
	$aval->valid("$name,$url,$style");
	if($aval->error_code < 1)
	{
		$style = $style.'.css';
		$ain = new update();
		$ain->up('info', 'sname', 'id', "1", "'$name'");
		$ain->up('info', 'surl', 'id', "1", "'$url'");
		$ain->up('info', 'style', 'id', "1", "'$style'");
		$ain->up('info', 'stat', 'id', "1", "'active'");
		$ain->up('info', 'tell', 'id', "1", "080");
		$ain->up('info', 'sid', 'id', "1", "'MobicomCMS'");
		$ain->up('info', 'email', 'id', "1", "'info@mobicomcms.com'");
		$ain->up('info', 'description', 'id', "1", "'MobicomCMS'");
		$ain->up('info', 'num_gen', 'id', "1", "5");
		header("location: index.php");
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
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Setup</title>

    <!-- Bootstrap core CSS -->
     <link href="dist/css/bootstrap.css" rel="stylesheet">

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

    <div class="container">
<h2>SMS Portal CMS Setup</h2>
 <?php
 if($check_row[0] == 'import')
 {
	 echo "<h3>Step 2:</h3>";
 }
 elseif($check_row[0] == 'setup' || $check->count < 1)
 {
	  echo "<h3>Step 1:</h3>";
 }
 
 if($check_row[0] == 'import')
 {
   if($aval->error_code > 0 || $ain->error_code > 0 || $ain->success_code > 0)
   {
	   if($ain->success_code > 0)
	   {
	   ?>
       <div class="alert alert-success">
       <?php
	   }
	   else
	   {
		   ?>
       <div class="alert alert-danger">
	   <?php
	   }
       echo $aval->error_msg;
	   echo $ain->error_msg;
	   echo $ain->success_msg;
	   ?>
       </div>
       <?php
   }
	?>
    <form id="form1" name="form1" method="post" action="setup.php" role="form">
      
      <div class="form-group">
      <label for="name">Site name*:</label>
      <input name="name" type="text" class="form-control" id="name" value="<?php
     if($ain->success_code < 1)
	 {
		 echo stripslashes($name);
	 }
     ?>"/>
     </div>
     
     <div class="form-group">
     <label for="url">URL*:</label>
     <input name="url" type="text" class="form-control" id="url" value="<?php
     if($aval->error_code > 0)
	 {
		 echo stripslashes($url);
	 }
	 else
	 {
		 echo "http://www.";
	 }
     ?>"/>
     </div>
     
     <br />
     <div class="lead">Select Theme*:</div>
     <table cellpadding="5">
       <tr>
         <td><label>
           <input type="radio" name="style" value="bootstrap" id="style_0">
          Midnight Blue</label></td>
           <td><img src="images/theme/midnight_blue.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="1" id="style_1">
           Dark Slate Grey</label></td>
            <td><img src="images/theme/dark_slate_grey.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="2" id="style_2">
           Coffee</label></td>
            <td><img src="images/theme/coffee.jpg" class="img-thumbnail"></td>
       </tr>
       <tr>
         <td><label>
           <input type="radio" name="style" value="3" id="style_3">
           Plum Velvet</label></td>
            <td><img src="images/theme/plum_velvet.jpg" class="img-thumbnail"></td>
       </tr>
     </table><br />
<div class="form-group">
  <input type="submit" name="save" id="save" value="Save" class="btn btn-primary"/>
   </div>
     
    </form>
<?php
 }
 else
 {

     if($val->error_code > 0)
   {
		   ?>
       <div class="alert alert-danger">
	   <?php
       echo $val->error_msg;
	   ?>
       </div>
       <?php
   }
	?>
    <form id="form2" name="form2" method="post" action="setup.php" role="form">
      
      <div class="form-group">
      <label for="host">Host*:</label>
      <input name="host" type="text" class="form-control" id="host" value="<?php
     if($val->error_code > 0)
	 {
		 echo stripslashes($host);
	 }
     ?>"/>
     </div>
     
     <div class="form-group">
     <label for="username">Database username*:</label>
     <input name="username" type="text" class="form-control" id="username" value="<?php
     if($val->error_code > 0)
	 {
		 echo stripslashes($username);
	 }
     ?>"/>
     </div>
     
     <div class="form-group">
     <label for="password">Database password:</label>
     <input name="password" type="text" class="form-control" id="password" value="<?php
     if($val->error_code > 0)
	 {
		 echo stripslashes($password);
	 }
     ?>"/>
     </div>
     
     <div class="form-group">
     <label for="database">Database*:</label>
     <input name="database" type="text" class="form-control" id="database" value="<?php
     if($val->error_code > 0)
	 {
		 echo stripslashes($database);
	 }
     ?>"/>
     </div>
     
<div class="form-group">
  <input type="submit" name="import" id="import" value="Import tables" class="btn btn-primary"/>
   </div>
     
    </form>
     <?php
 }
 ?>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($connect)
?>