<div class="col-md-3">
<?php
		  if(@$admin == '' && $page != 'login' && @$auser == '')
		  {
			  ?>
    <span class="lead text-primary"><strong><span class="glyphicon glyphicon-log-in"></span> Login</strong></span>
    <?php
  if(@$error != '')
	{
	echo "<div class='alert alert-danger'>".$error."</div>";
	}
  ?>
    <form name="form1" method="post" action="" class="form-horizontal" role="form">
    <div class="form-group">
    <label for="username" class="col-lg-4 control-label">Username</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="Username">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-lg-4 control-label">Password</label>
    <div class="col-lg-10">
      <input type="password" class="form-control" id="password" placeholder="Password" name="password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-12">
      <input type="submit" class="btn btn-primary" name="login" value="login" id="login">
    </div>
  </div>
    </form>
    <p>
    <a href="forgot.php">Forgot password</a><br />
    <a href="register.php">Register</a>
    </p>
    <?php
		  }
	?>  <br />
    <div class="panel panel-primary">
    <div class="panel-heading"><span><strong><span class="glyphicon glyphicon-list-alt"></span> SMS Portal Menu</strong></span></div>
    <div class="panel-body">
    
<div class="sidebar-nav">
<div class="navbar navbar-inverse" role="navigation">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="visible-xs navbar-brand">SMS Portal Menu</span>
         </div>
         
        <div class="collapse navbar-collapse sidebar-navbar-collapse">
          <ul class="nav navbar-nav">
          <?php
		  if(@$admin == '' && $page != 'login' && @$auser == '')
		  {
			  ?>
			  <li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-send"></span> Send SMS</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-screenshot"></span> Targeted SMS</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-random"></span> Auto Responder</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-credit-card"></span> Mobile Business Card</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-briefcase"></span> Appointment Reminder</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-export"></span>  Refer and Earn</a></li>
<li><a href="javascript:AlertIt();"><span class="glyphicon glyphicon-gift"></span>  Mobile Coupon</a></li>
<?php
//custom pages
		  $scustom = mysql_query("select title, id, elink from cpages order by sort");
		  if(mysql_num_rows($scustom) > 0)
		  {
			  while($scustom_row = mysql_fetch_row($scustom))
			  {
				 if($scustom_row[2] == '')
				 { 
			   if($page == $scustom_row[0])
		  {
		  ?>
            <li class="active"><a href="page.php?pid=<?php echo $scustom_row[1];?>"><span class="glyphicon glyphicon-file"></span> <?php echo $scustom_row[0];?></a></li>
            <?php
		  }
		  else
		  {
			  ?>
             <li><a href="page.php?pid=<?php echo $scustom_row[1];?>"><span class="glyphicon glyphicon-file"></span> <?php echo $scustom_row[0];?></a></li>
              <?php
		  }
				 }//elink
				 else
				 {
					 ?>
			<li><a href="<?php echo $scustom_row[2];?>" target="_new"><span class="glyphicon glyphicon-file"></span> <?php echo $scustom_row[0];?></a></li>	
            <?php	 
				 }
			  }
		  }
			 /* 
		  if($page == 'home')
		  {
		  ?>
            <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
              <?php
		  }
		  
		   if($page == 'services')
		  {
		  ?>
            <li class="active"><a href="services.php"><span class="glyphicon glyphicon-cog"></span> Services</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="services.php"><span class="glyphicon glyphicon-cog"></span> Services</a></li>
              <?php
		  }
		  
		   if($page == 'rate_price')
		  {
		  ?>
            <li class="active"><a href="rate_price.php"><span class="glyphicon glyphicon-tasks"></span> Rates/Pricing</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="rate_price.php"><span class="glyphicon glyphicon-tasks"></span> Rates/Pricing</a></li>
              <?php
		  }
		  
		   if($page == 'api')
		  {
		  ?>
            <li class="active"><a href="api.php"><span class="glyphicon glyphicon-paperclip"></span> API</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="api.php"><span class="glyphicon glyphicon-paperclip"></span> API</a></li>
              <?php
		  }
		  
		   if($page == 'reseller')
		  {
		  ?>
            <li class="active"><a href="reseller.php"><span class="glyphicon glyphicon-retweet"></span> Resellers</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="reseller.php"><span class="glyphicon glyphicon-retweet"></span> Resellers</a></li>
              <?php
		  }
		  
		   if($page == 'register')
		  {
		  ?>
            <li class="active"><a href="register.php"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="register.php"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
              <?php
		  }
		  
		   if($page == 'about')
		  {
		  ?>
            <li class="active"><a href="about.php"><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="about.php"><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
              <?php
		  }
		  
		   if($page == 'contact')
		  {
		  ?>
            <li class="active"><a href="contact.php"><span class="glyphicon glyphicon-earphone"></span> Contact us</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="contact.php"><span class="glyphicon glyphicon-earphone"></span> Contact us</a></li>
              <?php
		  }
		  //custom pages
		  $scustom = mysql_query("select title, id from cpages order by sort");
		  if(mysql_num_rows($scustom) > 0)
		  {
			  while($scustom_row = mysql_fetch_row($scustom))
			  {
			   if($page == $scustom_row[0])
		  {
		  ?>
            <li class="active"><a href="page.php?pid=<?php echo $scustom_row[1];?>"><span class="glyphicon glyphicon-file"></span> <?php echo $scustom_row[0];?></a></li>
            <?php
		  }
		  else
		  {
			  ?>
             <li><a href="page.php?pid=<?php echo $scustom_row[1];?>"><span class="glyphicon glyphicon-file"></span> <?php echo $scustom_row[0];?></a></li>
              <?php
		  }
			  }
		  
		  }
		  */
		  }
		  else
		  {
			  if($page != 'login' && $page != 'achar')
			  {
if(($admin != '' || $auser != '') && @$xhome != '')
{
	if($admin != '')
	{
		@$push_link = 'admin/';
	}
	else
	{
	@$push_link = 'users/';
	}
}
  if($page == 'ahome')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>index.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>index.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>    
      <?php
  }
  
  if($auser != '')
  {
  if($page == 'aprofile')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
  <?php
  }
  
  if($page == 'aauto')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>auto.php"><span class="glyphicon glyphicon-random"></span> Auto Responder</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>auto.php"><span class="glyphicon glyphicon-random"></span> Auto Responder</a></li>
  <?php
  }
  
  }
  
  if($page == 'asend_sms')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>send_sms.php"><span class="glyphicon glyphicon-send"></span> Send SMS</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>send_sms.php"><span class="glyphicon glyphicon-send"></span> Send SMS</a></li>
  <?php
  }
  
  if(@$admin != '')
  {
   if($page == 'amsgau')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>msgau.php"><span class="glyphicon glyphicon-bullhorn"></span> Msg All Users</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>msgau.php"><span class="glyphicon glyphicon-bullhorn"></span> Msg All Users</a></li>
  <?php
  }
  }
  
  if($page == 'atarget')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>target.php"><span class="glyphicon glyphicon-screenshot"></span> Targeted SMS</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>target.php"><span class="glyphicon glyphicon-screenshot"></span> Targeted SMS</a></li>
  <?php
  }
  
  if($page == 'arem')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>rem.php"><span class="glyphicon glyphicon-briefcase"></span> Appointment Reminder</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>rem.php"><span class="glyphicon glyphicon-briefcase"></span> Appointment Reminder</a></li>
  <?php
  }
  
  if($page == 'aaddress')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>address.php"><span class="glyphicon glyphicon-book"></span> Address</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>address.php"><span class="glyphicon glyphicon-book"></span> Address book</a></li>
  <?php
  }
  
   if($page == 'acard')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>card.php"><span class="glyphicon glyphicon-credit-card"></span> Business Card</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>card.php"><span class="glyphicon glyphicon-credit-card"></span> Business Card</a></li>
  <?php
  }
  
  if($page == 'aduplicate')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>duplicate.php"><span class="glyphicon glyphicon-tags"></span> Duplicates remover</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>duplicate.php"><span class="glyphicon glyphicon-tags"></span> Duplicates remover</a></li>
  <?php
  }
  
  if($page == 'atransfer')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>transfer.php"><span class="glyphicon glyphicon-transfer"></span> Transfer</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>transfer.php"><span class="glyphicon glyphicon-transfer"></span> Transfer</a></li>
  <?php
  }
  
  if($page == 'asent_msg')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>sent_msg.php?int=1"><span class="glyphicon glyphicon-new-window"></span> Sent Messages</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>sent_msg.php?int=1"><span class="glyphicon glyphicon-new-window"></span> Sent Messages</a></li>
  <?php
  }
  
  if($page == 'adraft')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>draft.php"><span class="glyphicon glyphicon-floppy-saved"></span> Draft</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>draft.php"><span class="glyphicon glyphicon-floppy-saved"></span> Draft</a></li>
  <?php
  }
  if(@$ure != 'Y')
  {
  if($page == 'arates')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>rates.php"><span class="glyphicon glyphicon-stats"></span> Rates</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>rates.php"><span class="glyphicon glyphicon-stats"></span> Rates</a></li>
  <?php
  }
  }
  
  if($page == 'anetwork')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>network.php"><span class="glyphicon glyphicon-compressed"></span> Network Charges</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>network.php"><span class="glyphicon glyphicon-compressed"></span> Network Charges</a></li>
  <?php
  }

  
  if($page == 'aschedule')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>schedule.php"><span class="glyphicon glyphicon-tasks"></span> Scheduled MSGS</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>schedule.php"><span class="glyphicon glyphicon-tasks"></span> Scheduled MSGS</a></li>
  <?php
  }
  
  if($page == 'atreport')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>treport.php?int=1"><span class="glyphicon glyphicon-random"></span> Transaction report</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>treport.php?int=1"><span class="glyphicon glyphicon-random"></span> Transaction report</a></li>
  <?php
  }
  
  if($auser == '')
  {
  /*if($page == 'aprocess')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>process.php"><span class="glyphicon glyphicon-forward"></span> Process payments</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>process.php"><span class="glyphicon glyphicon-forward"></span> Process payments</a></li>
  <?php
  }*/
  
  if($page == 'ausers')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>users.php?int=1"><span class="glyphicon glyphicon-user"></span> Users</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>users.php?int=1"><span class="glyphicon glyphicon-user"></span> Users</a></li>
  <?php
  }
  
  if($page == 'anewsletter')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>newsletter.php"><span class="glyphicon glyphicon-envelope"></span> Newsletter</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>newsletter.php"><span class="glyphicon glyphicon-envelope"></span> Newsletter</a></li>
  <?php
  }
  
  }
  else
  {
	  if($page == 'atnum_gen')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>tnum_gen.php"><span class="glyphicon glyphicon-th"></span> Targeted No. Generator</a></li>
  <?php
  }
  else
  {
	  ?>
  <li><a href="<?php echo @$push_link;?>tnum_gen.php"><span class="glyphicon glyphicon-th"></span> Targeted No. Generator</a></li>
  <?php
  }
	  
	  if($page == 'asms_credit')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>sms_credit.php"><span class="glyphicon glyphicon-shopping-cart"></span> Buy SMS credit</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>sms_credit.php"><span class="glyphicon glyphicon-shopping-cart"></span> Buy SMS credit</a></li>
  <?php
  }
  
   if($page == 'arefer')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>refer.php"><span class="glyphicon glyphicon-export"></span> Refer and Earn</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>refer.php"><span class="glyphicon glyphicon-export"></span>  Refer and Earn</a></li>
  <?php
  }
  
  if($page == 'acoupon')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>coupon.php"><span class="glyphicon glyphicon-gift"></span> Coupon</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>coupon.php"><span class="glyphicon glyphicon-gift"></span>  Coupon</a></li>
  <?php
  }
  
  if($page == 'avoucher')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>voucher.php"><span class="glyphicon glyphicon-credit-card"></span> Load Voucher</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>voucher.php"><span class="glyphicon glyphicon-credit-card"></span>  Load Voucher</a></li>
  <?php
  }
  
  }
  
  if($admin != '')
  {
	  if($page == 'aapi')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>api.php"><span class="glyphicon glyphicon-link"></span> API</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>api.php"><span class="glyphicon glyphicon-link"></span> API</a></li>
 <?php
  }
  
   if($page == 'akeyword')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>keyword.php"><span class="glyphicon glyphicon-ban-circle"></span> Banned Keywords</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>keyword.php"><span class="glyphicon glyphicon-ban-circle"></span> Banned Keywords</a></li>
  <?php
  }
  
  
   if($page == 'aacoupon' || $page == 'avcoupon')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-gift"></span> Coupon <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>acoupon.php"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
  <li><a href="<?php echo @$push_link;?>vcoupon.php"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
  </ul>
  </li>
  
  <?php
  if($page == 'aavoucher' || $page == 'avvoucher')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-credit-card"></span> Voucher <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>avoucher.php"><span class="glyphicon glyphicon-plus"></span> Generate</a></li>
  </ul>
  </li>
  
  <?php
  if($page == 'aimg_upload')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>img_upload.php"><span class="glyphicon glyphicon-upload"></span> Image Upload</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>img_upload.php"><span class="glyphicon glyphicon-upload"></span>  Image Upload</a></li>
  <?php
  }
  
  if($page == 'asetup' || $page == 'amessage' || $page == 'asocial' || $page == 'alfimg' || $page == 'aslider' || $page == 'abank' || $page == 'apayment' || $page == 'areferral' || $page == 'atheme' || $page == 'arunit' || $page == 'avoice')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Configuration <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>setup.php"><span class="glyphicon glyphicon-wrench"></span> Setup</a></li>
  <li><a href="<?php echo @$push_link;?>theme.php"><span class="glyphicon glyphicon-tree-deciduous"></span> Theme</a></li>
  <li><a href="<?php echo @$push_link;?>wmsg.php"><span class="glyphicon glyphicon-pencil"></span> Messages</a></li>
  <li><a href="<?php echo @$push_link;?>voice.php"><span class="glyphicon glyphicon-bullhorn"></span> Voice</a></li>
  <li><a href="<?php echo @$push_link;?>runit.php"><span class="glyphicon glyphicon-registration-mark"></span> Signup Credit</a></li>
  <li><a href="<?php echo @$push_link;?>social.php"><span class="glyphicon glyphicon-hand-up"></span> Socials</a></li>
  <li><a href="<?php echo @$push_link;?>lfimg.php"><span class="glyphicon glyphicon-picture"></span> Logo/Favicon</a></li>
  <li><a href="<?php echo @$push_link;?>slider.php"><span class="glyphicon glyphicon-forward"></span> Slider</a></li>
  <li><a href="<?php echo @$push_link;?>bank.php"><span class="glyphicon glyphicon-tower"></span> Bank</a></li>
  <li><a href="<?php echo @$push_link;?>payment.php"><span class="glyphicon glyphicon-play"></span> Payments</a></li>
  <li><a href="<?php echo @$push_link;?>referral.php"><span class="glyphicon glyphicon-export"></span> Referral</a></li>
  </ul>
  </li>
  
  <?php
  if($page == 'aitarget' || $page == 'avtarget' || $page == 'anum_gen')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-screenshot"></span> Targeted <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>itarget.php"><span class="glyphicon glyphicon-import"></span> Import Numbers</a></li>
  <li><a href="<?php echo @$push_link;?>vtarget.php"><span class="glyphicon glyphicon-eye-open"></span> View Number Count</a></li>
  <li><a href="<?php echo @$push_link;?>num_gen.php"><span class="glyphicon glyphicon-th"></span> Number Generator</a></li>
  </ul>
  </li>
  
  <?php
  if($page == 'arreseller' || $page == 'avreseller')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-retweet"></span> Reseller <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>rreseller.php"><span class="glyphicon glyphicon-registration-mark"></span> Register Reseller</a></li>
  <li><a href="<?php echo @$push_link;?>vreseller.php"><span class="glyphicon glyphicon-eye-open"></span> View Reseller</a></li>
  </ul>
  </li>
  
  <?php
  if($page == 'athome' || $page == 'atservices' || $page == 'atabout' || $page == 'atterms' || $page == 'atprivacy' || $page == 'atcontact' || $page == 'apricing')
  {
  ?>
  <li class="dropdown active">
  <?php
  }
  else
  {
  ?>
  <li class="dropdown">
  <?php
  }
  ?>
  <a href="<?php echo @$push_link;?>#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list-alt"></span> Pages <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <li><a href="<?php echo @$push_link;?>thome.php"><span class="glyphicon glyphicon-file"></span> Home Page</a></li>
  <li><a href="<?php echo @$push_link;?>tservices.php"><span class="glyphicon glyphicon-briefcase"></span> Sevices Page</a></li>
  <li><a href="<?php echo @$push_link;?>tabout.php"><span class="glyphicon glyphicon-info-sign"></span> About Page</a></li>
  <li><a href="<?php echo @$push_link;?>tcontact.php"><span class="glyphicon glyphicon-phone-alt"></span> Contact Page</a></li>
  <li><a href="<?php echo @$push_link;?>tterms.php"><span class="glyphicon glyphicon-flag"></span> Terms of Use Page</a></li>
  <li><a href="<?php echo @$push_link;?>tprivacy.php"><span class="glyphicon glyphicon-lock"></span> Privacy Policy Page</a></li>
  <li><a href="<?php echo @$push_link;?>treseller.php"><span class="glyphicon glyphicon-retweet"></span> Reseller Page</a></li>
  <li><a href="<?php echo @$push_link;?>tpricing.php"><span class="glyphicon glyphicon-tasks"></span> Pricing Page</a></li>
  <li><a href="<?php echo @$push_link;?>page.php"><span class="glyphicon glyphicon-file"></span> Custom Pages</a></li>
  </ul>
  </li>
  
  <?php
  }//admin
  
  if($page == 'apassword')
  {
  ?>
  <li class="active"><a href="<?php echo @$push_link;?>password.php"><span class="glyphicon glyphicon-asterisk"></span> Change password</a></li>
  <?php
  }
  else
  {
  ?>
  <li><a href="<?php echo @$push_link;?>password.php"><span class="glyphicon glyphicon-asterisk"></span> Change password</a></li>
  <?php
  }

				  if($admin != '')
				  {
			  ?>
              <li><a href="<?php echo @$push_link;?>../index.php?out=admin&ouser=<?php echo $admin;?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              <?php
				  }
				  elseif($auser != '')
				  {
					  ?>
             <li><a href="<?php echo @$push_link;?>../index.php?out=auser&ouser=<?php echo $auser;?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                      <?php
				  }
			  }
		  }
		  ?>
          </ul>
        </div><!--/.nav-collapse -->
        
        </div>
        </div>
        
    </div>
    </div>
    
    </div><!--col-->