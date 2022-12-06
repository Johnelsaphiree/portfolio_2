<?php
$page = 'ahome';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('up.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <title>User Home</title>
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
  <li class="active">USER AREA</li>
</ol>
  <?php
  $bal = new select();
$bal->pick('user', 'balance, reserved, username', 'id', "$auser", '', 'record', '', '', '=', '');
$bal_row = mysql_fetch_row($bal->query);
  ?>
  <table cellpadding="5">
  <tr>
  <td valign="bottom"><strong>User:</strong></td>
  <td><span class="text-success"><strong><?php echo $bal_row[2];?></strong></span></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <td valign="bottom"><strong>Balance:</strong></td>
  <td><span class="label label-default"><?php echo $bal_row[0];?></span></td>
  <td><small>Units</small></td>
  </tr>
  <tr>
  <td valign="bottom"><strong>Reserved:</strong></td>
  <td><span class="label label-default"><?php echo $bal_row[1];?></span></td>
  <td><small>Units</small></td>
  </tr>
  </table>
  
   <h2>Dashboard</h2>
  <table cellpadding="10" align="center">
  <tr valign="top" align="center">
    <td><a href="send_sms.php"><img src="../images/dashboard/sms.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Send SMS</a></td>
   <td><a href="target.php"><img src="../images/dashboard/targeted.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Targeted SMS</a></td>
   <td><a href="rem.php"><img src="../images/dashboard/appointment.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Appointment Reminder</a></td>
   <td><a href="address.php"><img src="../images/dashboard/address.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Address Book</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="card.php"><img src="../images/dashboard/business_card.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Business Card</a></td>
   <td><a href="profile.php"><img src="../images/dashboard/profile.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Profile</a></td>
   <td><a href="sent_msg.php"><img src="../images/dashboard/sent_msg.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Sent Messages</a></td>
   <td><a href="draft.php"><img src="../images/dashboard/draft.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Draft</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="rates.php"><img src="../images/dashboard/rate.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Rates</a></td>
   <td><a href="network.php"><img src="../images/dashboard/net_charge.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Network Charges</a></td>
   <td><a href="schedule.php"><img src="../images/dashboard/schedule.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Schedule Messages</a></td>
   <td><a href="treport.php"><img src="../images/dashboard/transaction.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Transaction Report</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="auto.php"><img src="../images/dashboard/auto.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Auto Responder</a></td>
   <td><a href="tnum_gen.php"><img src="../images/dashboard/gen.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Targeted No. Generator</a></td>
   <td><a href="sms_credit.php"><img src="../images/dashboard/buy.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Buy SMS Credit</a></td>
   <td><a href="refer.php"><img src="../images/dashboard/refer.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Refer and Earn</a></td>
    </tr>
    <tr valign="top" align="center">
    <td><a href="coupon.php"><img src="../images/dashboard/coupon.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Coupon</a></td>
   <td><a href="voucher.php"><img src="../images/dashboard/voucher.jpg" class="img-responsive img-thumbnail" width="100px" height="100px"><br />Load Voucher</a></td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
    </tr>
    </table>
  
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