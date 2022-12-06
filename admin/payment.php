<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'apayment';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$online = isset($_POST['online']) ? $_POST['online'] : '';
$bank = isset($_POST['bank']) ? $_POST['bank'] : '';
$rbank = isset($_POST['rbank']) ? $_POST['rbank'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

//get info
	$gnet = new select();
	$gnet->pick('payment', 'online, bank, rbank', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);
	
if($save)
{
$val = new validate();
if($val->error_code < 1)
{
	if($gnet->count > 0)
	{
		$up = new update();
		$up->up('payment', 'online', 'id', "1", "'$online'");
		$up->up('payment', 'bank', 'id', "1", "'$bank'");
		$up->up('payment', 'rbank', 'id', "1", "'$rbank'");
		
	}
	else
	{
		$up = new insert();
		$up->input('payment', 'id, online, bank, rbank', "0, '$online', '$bank', '$rbank'");
	}
	$success = success(1);
	header("location: payment.php?success=$success");
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

    <title>Payments</title>
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
<script type="text/javascript">
tinymce.init({
        selector: "textarea",
        plugins: [
                
                "emoticons textcolor paste"
        ],

        toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor | charmap emoticons",

        menubar: false,
        toolbar_items_size: 'small',

        style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],

        templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
        ]
});</script>
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
  <li class="active">PAYMENTS</li>
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
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">PAYMENTS</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="payment.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <table cellpadding="20" class="table-bordered">
  <tr>
  <td><strong>ONLINE PAYMENT:</strong></td>
  <td>
  <p><img src="../images/voguepay.png" class="img-responsive"></p>
  <div class="form-group">
  <label for="online" class="control-label">Merchant ID*:</label> 
      <input type="text" class="form-control" id="online" placeholder="Merchant ID*" name="online" value="<?php
	  if($gnet->count > 0)
	  {
		  echo $gnet_row[0];
	  }
	  ?>">
  </div></td>
  </tr>
  <tr>
  <td><strong>BANK PAYMENT:</strong></td>
  <td><div class="form-group">
    <label for="bank" class="control-label">Enter/Paste Instructions*</label> 
     <textarea class="form-control" rows="10" id="bank" name="bank"><?php
	 echo $gnet_row[1];
	 ?></textarea>
    </div></td>
  </tr>
  
  <tr>
  <td><strong>RESELLER BANK PAYMENT:</strong></td>
  <td><div class="form-group">
    <label for="rbank" class="control-label">Enter/Paste Instructions*</label> 
     <textarea class="form-control" rows="10" id="rbank" name="rbank"><?php
	 echo $gnet_row[2];
	 ?></textarea>
    </div></td>
  </tr>
  </table>
  <br />
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