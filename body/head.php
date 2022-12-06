<script type="text/javascript">
function AlertIt() {
var answer = confirm ("Login to access functionality or sign up!")
if (answer)
window.location="index.php";
}
</script>
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php
		  if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $img_link = '../images/logo.jpg';
				  $home_link = '../index.php';
			  }
			  else
			  {
				  $img_link = 'images/logo.jpg';
				  $home_link = 'index.php';
			  }
		  ?>
          <a href="<?php echo $home_link;?>"><img src="<?php echo $img_link;?>" class="img-responsive img-thumbnail"></a>
         </div>
         
        <div class="collapse navbar-collapse pull-right">
          <ul class="nav navbar-nav">
          
          <?php
		if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $index_link = '../index.php';
			  }
			  else
			  {
				  $index_link = 'index.php';
			  }
		 if($page == 'home')
		  {
		  ?>
            <li class="active"><a href="<?php echo $index_link;?>"><span class="glyphicon glyphicon-registration-home"></span> Home</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $index_link;?>"><span class="glyphicon glyphicon-registration-home"></span> Home</a></li>
              <?php
		  }
          
		if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $reg_link = '../register.php';
			  }
			  else
			  {
				  $reg_link = 'register.php';
			  }
		 if($page == 'register')
		  {
		  ?>
            <li class="active"><a href="<?php echo $reg_link;?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $reg_link;?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
              <?php
		  }
		  
		   if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $rate_link = '../rate_price.php';
			  }
			  else
			  {
				  $rate_link = 'rate_price.php';
			  }
		   if($page == 'rate_price')
		  {
		  ?>
            <li class="active"><a href="<?php echo $rate_link;?>"><span class="glyphicon glyphicon-tasks"></span> Pricing</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $rate_link;?>"><span class="glyphicon glyphicon-tasks"></span> Pricing</a></li>
              <?php
		  }
		  
		   if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $reseller_link = '../reseller.php';
			  }
			  else
			  {
				  $reseller_link = 'reseller.php';
			  }
		   if($page == 'reseller')
		  {
		  ?>
            <li class="active"><a href="<?php echo $reseller_link;?>"><span class="glyphicon glyphicon-retweet"></span> Resellers</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $reseller_link;?>"><span class="glyphicon glyphicon-retweet"></span> Resellers</a></li>
              <?php
		  }
		  
		  if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $service_link = '../services.php';
			  }
			  else
			  {
				  $service_link = 'services.php';
			  }
		   if($page == 'services')
		  {
		  ?>
            <li class="active"><a href="<?php echo $service_link;?>"><span class="glyphicon glyphicon-cog"></span> Services</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $service_link;?>"><span class="glyphicon glyphicon-cog"></span> Services</a></li>
              <?php
		  }
		  
		  if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $api_link = '../api.php';
			  }
			  else
			  {
				  $api_link = 'api.php';
			  }
		   if($page == 'api')
		  {
		  ?>
            <li class="active"><a href="<?php echo $api_link;?>"><span class="glyphicon glyphicon-paperclip"></span> API</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $api_link;?>"><span class="glyphicon glyphicon-paperclip"></span> API</a></li>
              <?php
		  }
		  
  if($page == 'vreg' || $page == 'vlogin' || $page == 'avadmin')
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
  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-bullhorn"></span> Voice <b class="caret"></b></a>
  <ul class="dropdown-menu">
  <?php
   if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $vreg_link = '../vreg.php';
			  }
			  else
			  {
				  $vreg_link = 'vreg.php';
			  }
			  ?>
  <li><a href="<?php echo $vreg_link;?>"><span class="glyphicon glyphicon-registration-mark"></span> Register Now</a></li>
   <?php
   if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $vlogin_link = '../vlogin.php';
			  }
			  else
			  {
				  $vlogin_link = 'vlogin.php';
			  }
			  ?>
  <li><a href="<?php echo $vlogin_link;?>"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
  
  <?php
   if($admin != '')
			  {
				  if(@$xhome == '')
				  {
				  @$vadmin_link = 'vadmin.php';
				  }
				  else
				  {
					 @$vadmin_link = 'admin/vadmin.php'; 
				  }
			  ?>
  <li><a href="<?php echo $vadmin_link?>"><span class="glyphicon glyphicon-th"></span> Voice Admin</a></li>
         <?php 
			  }
			  ?>
              </ul>
  </li> 
  <?php
			  
		 if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $about_link = '../about.php';
			  }
			  else
			  {
				  $about_link = 'about.php';
			  }
		   if($page == 'about')
		  {
		  ?>
            <li class="active"><a href="<?php echo $about_link;?>"><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
             <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $about_link;?>"><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
              <?php
		  }
		  
		   if(($admin != '' || $auser != '') && @$xhome == '')
			  {
				  $contact_link = '../contact.php';
			  }
			  else
			  {
				  $contact_link = 'contact.php';
			  }
		   if($page == 'contact')
		  {
		  ?>
            <li class="active"><a href="<?php echo $contact_link;?>"><span class="glyphicon glyphicon-earphone"></span> Contact</a></li>
            <?php
		  }
		  else
		  {
			  ?>
              <li><a href="<?php echo $contact_link;?>"><span class="glyphicon glyphicon-earphone"></span> Contact</a></li>
              <?php
		  }
		
		  ?>
          </ul>
        </div><!--/.nav-collapse -->
        
    </div>
    </div>