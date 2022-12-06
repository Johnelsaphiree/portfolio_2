 <br />
<br />
<div id="footer">
<?php
if(@$admin == '' && @$auser == '')
{
?>
<div class="container">
<div class="row">

        <div class="col-md-6">
        <p>
        <a href="terms.php">Terms of use</a> | <a href="privacy.php">Privacy policy</a></p>
        <p><small>&copy;<?php echo $csite_name;?> <?php echo date('Y', time());?>. All rights reserved.</small></p>
        </div>
        
        <div class="col-md-6">
        <?php
		//get social
		$fsocial = mysql_query("select facebook, twitter from social where id = 1");
		$fsocial_row = @mysql_fetch_row($fsocial);
		?>
        <table align="right" cellpadding="5">
        <tr>
        <td><a href="<?php echo $fsocial_row[0];?>"><img src="images/facebook.png" alt="facebook" class="img-responsive"></a></td>
        <td><a href="<?php echo $fsocial_row[1];?>"><img src="images/twitter.png" alt="facebook" class="img-responsive"></a></td>
         <td><img src="images/payments.png" alt="payments logo" class="img-responsive"></td>
        </tr>
        </table>
      </div>
      
      </div>
      </div>
      </div>
      <?php
}
?>