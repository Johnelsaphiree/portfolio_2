 <div class="col-md-3">
  <div class="list-group">
  <?php
  if($page == 'ahome')
  {
  ?>
  <a href="index.php" class="list-group-item active">Home</a>
  <?php
  }
  else
  {
	   ?>
  <a href="index.php" class="list-group-item">Home</a>
  <?php
  }
  
  if($page == 'agen')
  {
  ?>
  <a href="gen.php" class="list-group-item active">Generate License</a>
  <?php
  }
  else
  {
 ?>
  <a href="gen.php" class="list-group-item">Generate License</a>
  <?php	  
  }
  
  if($page == 'apassword')
  {
  ?>
  <a href="password.php" class="list-group-item active">Password</a>
  <?php
  }
  else
  {
 ?>
  <a href="password.php" class="list-group-item">Password</a>
  <?php	  
  }
  ?>
  <a href="../index.php?out=controller&ouser=controller" class="list-group-item">Logout</a>
</div>
   </div><!--col-->