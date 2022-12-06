<?php
if($page == 'aadmission' || $page == 'anew_student' || $page == 'aoutline' || $page == 'agraduate' || $page == 'anysc' || $page == 'aresults' || $page == 'aupayment' || $page == 'aattend' || $page == 'autransport' || $page == 'acattend' || $page == 'amaterial')
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
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-upload"></span> Uploads <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <?php
			  if($tuser == '')
			  {
				  ?>
               <li><a href="outline.php"><?php
                if($type == 'school')
				{
					echo 'Subject';
				}
				elseif($type == 'university')
				{
					echo 'Course';
				}
				?> outline</a></li>
               <li><a href="new_student.php">New students</a></li>
              
               <?php
			  }
			  else
			  {
				   ?>
               <li><a href="material.php">Material</a></li>
               <?php
			  }
			   if($admin != '')
			   {
				   ?>
                <li><a href="admission.php">Admission list</a></li>
                <?php
				if($type == 'university')
				{
				?>
                <li><a href="nysc.php">NYSC list</a></li>
                <?php
				}
				?>
                <li><a href="graduate.php">Graduate list</a></li>
                <?php
			   }
			   if($tuser == '')
			   {
			   ?>
               <li><a href="upayment.php">Student's payment</a></li>
               <?php
			   if($admin == '' || $type == 'school')
			   {
				   ?>
               <li><a href="results.php">Results</a></li>
               <?php
			   }
			   }//tuser
			   ?>
               <?php
			   if($type == 'school' && $tuser == '')
			   {
				   ?>
               <li><a href="attend.php">Daily attendance</a></li>
               <li><a href="utransport.php">Transport log</a></li>
               <?php
			   }
			   if(($type == 'school' || $duser != '') && $tuser == '')
			   {
				   ?>
               <li><a href="cattend.php">Class attendance</a></li>    
                   <?php
			   }
			   ?>
              </ul>
            </li>

?>
