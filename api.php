<?php
$page = 'api';

include('functions/connection.php');
include('functions/error_success.php');
include('objects/query.php');
include('objects/sms.php');
include('up.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $csite_name;?> gateway">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>API: Gateway connect</title>
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
  </head>

  <body>
  <?php
 include('body/head.php');
 ?>
    <div class="container">
     <div class="row">
    <?php
	include("body/sidex.php");
	?>
    <div class="col-md-9">
    <h1>API(HTTP)</h1><br />
    <p>To use our API you must be a registered user on <?php echo $csite_name;?>.</p><br />
    <p><span class="lead text-primary">Parameters:</span><br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>username:</strong> Your username on <?php echo $csite_name;?>.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>password:</strong> Your password on <?php echo $csite_name;?>.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>sender:</strong> This is the sender id that will appear on the recipient's phone. Alphanumeric = 11 Characters, Numeric = 18 characters.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>dest:</strong> The destination numbers where the message will be sent to. Separated with a comma(,) for multiple recipients.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>msg:</strong> The message to be sent. 160 characters per message.
    </p><br />
    <p><span class="lead text-primary">Examples:</span><br />
    <span class="glyphicon glyphicon-user"></span> <strong>Single recipient:</strong><br />
    <?php echo $csite_url;?>/sms_api.php?username=megzy&password=major&sender=OneCrib&dest=2349093302368&msg=Testing the API
    <br /><br />
    <span class="glyphicon glyphicon-user"></span><span class="glyphicon glyphicon-user"></span> <strong>Multiple recipients:</strong><br />
    <?php echo $csite_url;?>/sms_api.php?username=megzy&password=major&sender=OneCrib&dest=2349093302368,08098987676,2348098789876&msg=Testing the API
    </p>
    <span class="glyphicon glyphicon-asterisk text-info"></span> <strong class="text-info"><em>NOTE: </em></strong><small>The SENDER, DEST and MSG must be URL ENCODED for the API to work properly. It is advisable to pass 25 destinations at a time for multiple rceipients.</small>
    <br />
    <br />
    <p><span class="lead text-primary">Return values:</span><br />
    
    <span class="glyphicon glyphicon-chevron-right"></span> 146 = Successful<br />
    <span class="glyphicon glyphicon-chevron-right"></span> 140 = Usuccessful<br />
    <span class="glyphicon glyphicon-chevron-right"></span> 141 = Insufficient credit<br />
    </p>
 <br />
    
    <p><span class="lead text-primary">Credit balance report:</span><br />
    
    <p><span class="lead text-primary">Parameters:</span><br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>meg_report:</strong> The value should be set to "balance" to specify that you are quering your account balance.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>username:</strong> Your username on <?php echo $csite_name;?>.<br />
    <span class="glyphicon glyphicon-chevron-right"></span> <strong>password:</strong> Your password on <?php echo $csite_name;?>.<br />
    <p><span class="lead text-primary">Examples:</span><br />
    <?php echo $csite_url;?>/sms_api.php?meg_report=balance&username=megzy&password=major
    </p>
     <br />
    
    </div>
    </div>
    
    </div><!-- /.container -->
    <?php
	include('body/foot.php');
	?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>