<?php
print_r($data);
?>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<SCRIPT type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-ui-1.8.13.custom.min.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('locker');?>css/blitzer/jquery-ui-1.8.14.custom.css" />
<script type="text/javascript">
function setprice(){
	//var p = $("#packageId option:selected").text();
	var p = $("#packageId").find('option:selected').text();
	var price = p.split("[");
	
$('#package_price').val(price[0].substr(1));
}
$( document ).ready( setprice );
</script>
<center>
<div id="messages">
<?php
/// display all messages

if (is_array($messages)):
    foreach ($messages as $type => $msgs):
        foreach ($msgs as $message):
            echo ('<span class="' .  $type .'">' . $message . '</span>');
        endforeach;
    endforeach;
endif;
?>
</div>  
<div class="tblheading">Create Invoice</div>
<?php 

echo '<div style="color:#FF0000;">'.validation_errors().'</div>'; 
echo form_open('bandook/invoice/create/'.$member_id, array('id' => 'frmInvoices','name' => 'frmInvoices'));
echo '<table><tr><td>';
?>
<table id="table1" class="tbl_listing" width="100%">
<tr><th>User</th><td><?php echo $data['member_username'];?></td></tr>
<tr><th>Invoice Date</th><td><?php echo form_input(array('name'=>'invoice_date','id'=>'invoice_date','maxlength'=>50,'size'=>40 ,'value'=>date('Y-m-d'))) ; ?>
			<SCRIPT type="text/javascript">
				$(function(){$("#invoice_date").datepicker({ dateFormat: 'yy-mm-dd' });});
			</SCRIPT></td></tr>
<tr><th>Invoice Counter</th><td><?php echo form_input(array('name'=>'invoice_counter','id'=>'invoice_counter','maxlength'=>5,'size'=>40 ,'value'=>$data['invoice_counter'])) ; ?></td></tr>

 
<tr><th>Package</th><td>
<select name="packageId" id="packageId" onchange="javascript:setprice();">
<?php 
//Fetch packages from packages array 
foreach($packages as $package){
	if($package['package_id'] == $user_packages['package_id'])
	echo "<option value='".$package['package_id']."' selected>$".$package['package_price']. '['.$package['package_min_contacts'].'-'.$package['package_max_contacts']."]</option>";
	else
	echo "<option value='".$package['package_id']."'>$".$package['package_price']. '['.$package['package_min_contacts'].'-'.$package['package_max_contacts']."]</option>";
}
?>
</select>
</td>
</tr>
<tr><th>Amount</th><td><input type="text" name="package_price"  id="package_price" /> </td></tr>
<tr><td colspan="3" align="center">
<?php
echo form_submit(array('name' => 'btnSubmit', 'id' => 'btnSubmit' ,'class'=>'inputbuttons','content' => 'Submit'), 'Submit');
echo '&nbsp;&nbsp;';
echo form_button(array('name'=>'btnCancel','class'=>'inputbuttons', 'value'=>'Cancel','content'=>'Cancel','onclick'=>"window.location.href='".base_url().'bandook/users_manage/users_list'."'"));
?>
</td></tr>
</table>


<?php
echo form_hidden('action','save');

echo form_hidden('member_id',$member_id);
echo form_close();
?>
</center>