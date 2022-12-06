<?php
$page = 'atpages';

include('../functions/connection.php');
include('../functions/error_success.php');
include('../objects/query.php');
include('../objects/upload_download.php');
include('../objects/sms.php');
include('up.php');

$xedit = $_GET['xedit'];
$create = $_GET['create'];
$delete = $_GET['delete'];
$success = $_GET['success'];
$message = $_POST['message'];
$sort = $_POST['sort'];
$title = $_POST['title'];
$elink = $_POST['elink'];
$save = $_POST['save'];

if($xedit != '')
{
//get msg
$gm = new select();
$gm->pick('cpages', 'message, title, sort, elink', 'id', "$xedit", '', 'record', '', '', '=', '');
$gm_row = @mysql_fetch_row($gm->query);
}

if($save)
{
	$val = new validate();
	$val->numeric($sort, 'Sort Order');
	$val->valid("$title,$sort");
	if($val->error_code < 1)
	{
		$message = mysql_real_escape_string($message);
		if(stristr($message, '<img'))
		{
			$message = str_replace('<img', '<img class='.'img-responsive', $message);
		}
		if($xedit != '')
		{
			$up = new update();
			$up->up('cpages', 'message', 'id', "$xedit", "'$message'");
			$up->up('cpages', 'title', 'id', "$xedit", "'$title'");
			$up->up('cpages', 'sort', 'id', "$xedit", "'$sort'");
			$up->up('cpages', 'elink', 'id', "$xedit", "'$elink'");
		}
		else
		{
			$up = new insert();
			$up->input('cpages', 'id, message, title, sort, elink', "0, '$message', '$title', $sort, '$elink'");
		}
		$success = success(1);
		header("location: page.php?success=$success");
		exit;
	}
}

if($delete != '')
{
	$del = new delete();
	$del->gone('cpages', 'id', "$delete");
	
	$success = success(1);
		header("location: page.php?success=$success");
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

    <title>Custom Pages</title>
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
  <li class="active">CUSTOM PAGES</li>
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
	
	if($create != '' || $xedit != '')
	{
	?>
    <form action="page.php?create=<?php echo $create;?>&xedit=<?php echo $xedit;?>" method="post" enctype="multipart/form-data" name="wmsg" class="form-horizontal" role="form">
    <div class="panel panel-default">
  <div class="panel-heading"><?php
  if($xedit != '')
  {
	  echo 'Edit Page';
  }
  elseif($create != '')
  {
	  echo 'Create Page';
  }
  ?></div>
  <div class="panel-body">
  <table width="100%" cellpadding="10" align="center">
  <tr>
  <td>
  <div class="form-group">
  <label for="title" class="control-label">Title*:</label> 
      <input type="text" class="form-control" id="title" placeholder="Title*" name="title" value="<?php
		 if($xedit != '')
	 {
	 echo $gm_row[1];
	 }
	 elseif($create != '')
	 {
		if($success == '')
		{
			echo stripslashes($title);
		}
	 }
	  ?>">
  </div>
  
  <div class="form-group">
    <label for="message" class="control-label">Enter/Paste Content(<small>Dont enter content if this is an external link</small>)</label> 
     <textarea class="form-control" rows="50" id="message" name="message"><?php
	 if($xedit != '')
	 {
	 echo stripslashes($gm_row[0]);
	 }
	 elseif($create != '')
	 {
		if($success == '')
		{
			echo stripslashes($message);
		}
	 }
	 ?></textarea>
    </div>
    
    <div class="form-group">
  <label for="elink" class="control-label">External link(Optional):</label> 
      <input type="text" class="form-control" id="elink" placeholder="External link(Optional)*" name="elink" value="<?php
		 if($xedit != '')
	 {
		 if($gm_row[3] != '')
		 {
	 echo $gm_row[3];
		 }
		 else
		 {
			 echo 'http://';
		 }
	 }
	 elseif($create != '')
	 {
		if($val->error_code > 0)
		{
			echo stripslashes($elink);
		}
		else
		{
			echo 'http://';
		}
	 }
	  ?>">
  </div>
    
    <div class="form-group">
  <label for="sort" class="control-label">Sort Order*:</label> 
      <input type="text" class="form-control" id="sort" placeholder="Sort*" name="sort" value="<?php
		 if($xedit != '')
	 {
	 echo $gm_row[2];
	 }
	 elseif($create != '')
	 {
		if($val->error_code > 0)
		{
			echo $sort;
		}
		else
		{
			echo 0;
		}
	 }
	  ?>">
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
<?php
	}//create and edit
	else
	{
		?>
    <p><a href="page.php?create=yes" class="btn btn-danger"><span class="glyphicon glyphicon-plus-sign"></span> Create page</a></p>
    <p class="lead">Pages</p>
        <?php
		//page query
		$qpage = new select();
		$qpage->pick('cpages', 'id, title, sort', '', '', '', 'record', 'sort', '', '', '');
		if($qpage->count > 0)
		{
			?>
            <div class="table-responsive">
            <table class="table table-striped">
            <tr>
            <th>TITLE</th>
            <th>SORT ORDER</th>
            <th>ACTION</th>
            </tr>
            <?php
			while($qpage_row = mysql_fetch_row($qpage->query))
			{
				?>
               <tr>
              <td><?php echo $qpage_row[1];?></td> 
              <td><?php echo $qpage_row[2];?></td>
              <td><a href="page.php?xedit=<?php echo $qpage_row[0];?>">Edit</a> | <a href="page.php?delete=<?php echo $qpage_row[0];?>">Delete</a></td> 
               </tr> 
                <?php
			}
			?>
            </table>
            </div>
            <?php
		}
		else
		{
			echo $qpage->error_msg;
		}
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