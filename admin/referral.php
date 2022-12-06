<?php
date_default_timezone_set('Africa/Lagos');

$now = date('Y-m-d H:i:s', time());

$page = 'areferral';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/sms.php');
include('up.php');

$credit = isset($_POST['credit']) ? $_POST['credit'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$sender = isset($_POST['sender']) ? $_POST['sender'] : '';
$text = isset($_POST['text']) ? $_POST['text'] : '';
$save = isset($_POST['save']) ? $_POST['save'] : '';

$success = isset($_GET['success']) ? $_GET['success'] : '';

//get info
	$gnet = new select();
	$gnet->pick('ref_setup', 'credit, message, sender, text', 'id', "1", '', 'record', '', '', '=', '');
	$gnet_row = @mysql_fetch_row($gnet->query);

if($save)
{
	$val = new validate();
	$val->numeric($credit, 'Credit');
	$val->valid("$credit,$message,$sender,$text");
	if($val->error_code < 1)
	{
		if($gnet->count > 0)
		{
			$up = new update();
			$up->up('ref_setup', 'credit', 'id', "1", "$credit");
			$up->up('ref_setup', 'message', 'id', "1", "'$message'");
			$up->up('ref_setup', 'sender', 'id', "1", "'$sender'");
			$up->up('ref_setup', 'text', 'id', "1", "'$text'");
		}
		else
		{
			$up = new insert();
			$up->input('ref_setup', 'id, credit, message, sender, text', "0, $credit, '$message', '$sender', '$text'");
		}
		$success = success(1);
		header("location: referral.php?success=$success");
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

    <title>Referral</title>
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
        selector: "textarea#text",
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
  <li class="active">REFERRAL</li>
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
    <h3 class="panel-title">REFERRAL SETUP</h3>
  </div>
  <div class="panel-body">
  <form class="form-horizontal" role="form" name="form1" method="post" action="referral.php">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="sender" class="control-label">Sender ID*:</label> 
      <input type="text" class="form-control" id="sender" placeholder="Sender ID*" name="sender" value="<?php
		  echo $gnet_row[2];
	  ?>" maxlength="11">
  </div>
  
  <div class="form-group">
    <label for="message" class="control-label">Message*</label> 
     <textarea class="form-control" rows="3" id="message" name="message"><?php
	 echo $gnet_row[1];
	 ?></textarea>
     <br />
    <!--<span id="remaining">160 characters remaining</span>
    <span id="messages">1 message(s)</span>-->
    </div>
  
  <div class="form-group">
  <label for="credit" class="control-label">Referral Credit*:</label> 
      <input type="text" class="form-control" id="credit" placeholder="Referral Credit*" name="credit" value="<?php
		  echo $gnet_row[0];
	  ?>">
  </div>
  
   <div class="form-group">
    <label for="text" class="control-label">Help Text*</label> 
     <textarea class="form-control" rows="5" id="text" name="text"><?php
	 echo $gnet_row[3];
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
    <script>
$(document).ready(function(){
    var $remaining = $('#remaining'),
        $messages = $remaining.next();

    $('#message').keyup(function(){
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
});
</script>

  </body>
</html>
<?php
mysql_close($connect);
?>