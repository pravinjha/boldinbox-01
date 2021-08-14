
<div class = 'body-public'>




	<div class = 'body-box-support'>		
		<div class = 'body-form-text2'>Packages & Pricing</div>
		<div class = 'body-form-desc'>Start sending your <b>Email Campaign For Free</b>. If you can't find a package suitable for you, please feel free to write to us at: <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a></div>
		

		<div class="support_category_block">
		 
	<div>
      <table class = 'pricing_table' cellspacing = '0'>
		<tr>
			<th class = 'row_head' colspan = '10' align = 'left'>
				<ul><li><a class = 'active'>+ Low Volume Packages</a></li></ul>
			</th>
		</tr>
        <tr>
          <th>
            <strong>Contacts</strong>
          </th>
        <?php
			for($i=0;$i<9;$i++){
				echo '<th>Up to  <br />'. number_format($packages[$i]['package_max_contacts']).'</th>';
			}
		?>
        </tr>
        <tr>
          <td>
            <strong>Monthly Price</strong>
          </td>
        <?php
			for($i=0;$i<9;$i++){
				if($packages[$i]['package_price']==0.00){
					//echo '<td>$0</td>';
					echo '<td>&#x20B9; 0</td>';
				}else{
					//echo '<td>$'.number_format($packages[$i]['package_price'],0).'</td>';
					echo '<td>&#x20B9;'.number_format($packages[$i]['package_price_inr'],0).'</td>';
				}	
			}
        ?>
        </tr>		
       <tr>
			<td height = '10' class = 'row_spacer'></td>
		<tr>
      </table>
    </div>
	<div class = 'clear10'></div><div class = 'clear10'></div>
    <div>
      <table class = 'pricing_table' cellspacing = '0'>
		<tr>
			<th class = 'row_head' colspan = '10' align = 'left'><ul><li><a class = 'active'>+ High Volume Packages</a></li></ul></th>
		</tr>
        <tr>
          <th>
            <strong>Contacts</strong>
          </th>
        <?php
			for($i=9;$i<18;$i++){
				echo '<th>Up to  <br />'. number_format($packages[$i]['package_max_contacts']).'</th>';
			}
		?>
        </tr>
        <tr>
          <td>
            <strong>Monthly Price</strong>
          </td>
        <?php
			for($i=9;$i<18;$i++){
				if($packages[$i]['package_price']==0.00){
					//echo '<td>$0</td>';
					echo '<td>$0</td>';
				}else{
					//echo '<td>$'.number_format($packages[$i]['package_price'],0).'</td>';
					echo '<td>&#x20B9;'.number_format($packages[$i]['package_price_inr'],0).'</td>';
				}	
			}
        ?>
        </tr>
		
        <tr>
			<td height = '10' class = 'row_spacer'></td>
		<tr>
      </table>
    </div>

    <div class = 'clear10'></div><div class = 'clear10'></div>
    <div>
      <div class = 'body-form-desc'><a href="<?php echo  base_url()."home/contact";?>"><b>Contact us</b></a> for custom plans or annual discount or for any other pricing related queries you may have. <br /><br />All our plans are based on the total number of subscribers in your account and are defined to send unlimited emails per billing period, however, individual sending limits may apply on case to case basis.</div>
    </div>
    
    
    <div style="width:100%;margin:3px auto;">
        <h3 style="font-size: 1.6em;letter-spacing: 0px;color: #003399;">* We provide FREE HTML Emailer design with all the paid plans. The emailer will be highly compatible with all the global email clients.</h3>
    </div>
    
    
    
		</div>
</div>
</div>