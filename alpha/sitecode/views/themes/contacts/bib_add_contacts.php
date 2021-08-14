<script type="text/javascript" src="<?php echo $this->config->item('locker'); ?>js/bib.import.js?v=122"></script>
<!-- script src="<?php echo $this->config->item('locker'); ?>js/jquery.multiselect.js" type="text/javascript"></script -->
<link href="<?php echo $this->config->item('locker'); ?>css/blitzer/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css" />
<!-- link href="<?php echo $this->config->item('locker'); ?>css/jquery.multiselect.css" rel="stylesheet" type="text/css" / -->

<script language="javascript" type="text/javascript">
    $(document).ready(function(){    
    <?php if('1'==$extra['contact_import_progress']){?>
      $('.subscriber_msg').show();
      setInterval('checkImportStatus()',10000);  
    <?php }?>
	
		// $("#subscription_contact_one").multiselect(	);
		
		
		$('.dropDownCustomSelected').click(function(){			
			$('.dropDownCustomOpened').slideToggle("show");
		});
		$('.dropDownCustomOpened a').click(function(){			
			$('.dropDownCustomSelected').html($(this).html());
		});		
		window.onclick = function(event) {			
		  if (!event.target.matches('.dropDownCustomSelected') && !event.target.matches('.dropDownCustomHeading')) {
				$('.dropDownCustomOpened').slideUp();
		  }
		}
		
		
    });
	
	
	
	function charLimit(limitField, limitNum) {
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		}
	}
</script>
<style>
#progress-bar {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
.btnSubmit{background-color:#09f;border:0;padding:10px 40px;color:#FFF;border:#F0F0F0 1px solid; border-radius:4px;}
#progress-div {border:#0FA015 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
</style>
<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner">					
					<div class="row">

						<!-- THIS IS MESSAGE BAR -->							
						<?php  if('1'==$extra['contact_import_progress']){ ?>
							<div class="col-lg-12">
								<div class="alert alert-warning" role="alert">
								Your list import is under process. Larger lists will take longer. However, navigating away from this page will not interrupt the upload. After completion of process, you will be informed by email.
								</div>
							</div>
						<?php  } ?>						 
						<!-- THIS IS MESSAGE BAR -->
					
						
						<form   method="post" accept-charset="utf-8" id="uploadForm" name="uploadForm" enctype="multipart/form-data">
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class="row">
									<div class="col-lg-6">										
										<div class = 'formLabel'>Select Contact Import Method:</div>
										<div class = 'dropDownCustom'>					
											<div class = 'dropDownCustomSelected'>Upload Excel/CSV File </div>											
											<div class = 'dropDownCustomOpened'>
												<div class = 'dropDownCustomList'>	
												 <a href = '#'>Upload Excel/CSV File </a>							
												 <a href = '#'>Copy / Paste Emails </a>	
												 <a href = '#'>Type Emails & Names One By One </a>
												</div>
											</div>
											</div>
											
											<!-- Method 1 - Upload CSV  -->
											<div id = 'import_file' class = 'cl_imp_cnt'>
												<div class = 'formLabel'>													
													<div class = 'row'>
														<div class="col-lg-8 col-md-8 col-sm-6">Upload file with extension CSV or XLS:</div>
														<div class="col-lg-4 col-md-4 col-sm-6 cl_imp_cnt_sample">
															<a href = 'javascript:void(0);' id = 'show_sample_file' class = 'font6 font600 floatRight'>Show Sample File</a>
														</div> 
													</div>
												</div>
												<div class = 'cl_imp_cnt_file'><?php echo form_upload(array('id'=>'subscriber_csv_file','name'=>'subscriber_csv_file','value'=>set_value('subscriber_csv_file'), 'class'=>'form-control')); ?></div>												
											</div>
											
											<!-- Method 2 - Copy Paste  -->
											<div id = 'copy_pase_contacts' class = 'cl_imp_cnt dn'>
												<div class = 'formLabel'>Copy Paste Contacts (Not more than 100):</div>
												<div class = 'cl_imp_cnt_copy_paste_area'><textarea class = 'form-control' name="copy_csv" id="copy_csv" maxlength="5000" onKeyDown="charLimit(this,5000);"></textarea></div>						
											</div>
											
											<!-- Method 3 - One by One  -->
											<div id = 'ony_by_one' class = 'cl_imp_cnt dn'>
											<div class = 'formLabel'>Enter Email Addresses/Names:</div>												
											<div class = 'row'>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d1'><input placeholder = 'Email Id' class = 'form-control' name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div></div>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d2'><input placeholder = 'First Name' class = 'form-control' name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div></div>
												<!-- div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div -->
											</div>
											<div class = 'row'>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d1'><input placeholder = 'Email Id' class = 'form-control' name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div></div>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d2'><input placeholder = 'First Name' class = 'form-control' name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div></div>
												<!-- div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div -->
											</div>
											<div class = 'row'>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d1'><input placeholder = 'Email Id' class = 'form-control' name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div></div>
												<div class = 'col-sm-6'><div class = 'cl_imp_cnt_ono_d2'><input placeholder = 'First Name' class = 'form-control' name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div></div>
												<!-- div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div -->
											</div>
										</div>
											
											
											
									</div>
									<div class="col-lg-6 tipBox">
										<div class = 'formLabelTips'>How would you like to import your contacts?</div>
										<div class = 'formTips'>A preheader is the short summary text that follows the subject line when an email is viewed in the inbox. Many modern email clients helps the recepient get an idea of what the email contains.</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class="row">
									<div class="col-lg-6">
										<div class = 'formLabel'>
											<div class = 'row'>
												<div class="col-lg-8 col-md-8 col-sm-6">Select Contacts List:</div>
												<div class="col-lg-4 col-md-4 col-sm-6 cl_imp_cnt_sample">
													<a href = 'javascript:void(0);' class = 'font6 font600 floatRight'>+ Add New List</a>
												</div> 
											</div>
										</div>
										<div class = 'contacts_import_list'>
											<?php
												// print_r($select_subscriptions);
												  $i=0;
												  echo '<div class="contacts-list">';
												  foreach($select_subscriptions as $subscription){													  
													if($subscription_first_id == $subscription['subscription_id']){
													  $checked=true;
													}else{
													  if($subscription['subscription_id'] == '-1')	$checked=true; else $checked=false;
													}
													echo '<div>';
													echo form_checkbox(array('name'=>'subscription_contact_one[]','id'=>'subscription_contact_one','class'=>'form-control','value'=>$subscription['subscription_id'],'checked'=>$checked )).' '.ucwords(substr($subscription['subscription_title'],0,25))." (".$subscription['number_of_contacts'].")";
													echo '</div>';
													$i++;
												  }
												  if($i<=0) {
													echo "Please Create Subscriptions";
												  }
												  echo '</div>';
											?>
											</select>						
										</div>
									</div>
									<div class="col-lg-6 tipBox">
										<div class = 'formLabelTips'>Select the list you would like to upload your contacts to.</div>
										<div class = 'formTips'>A preheader is the short summary text that follows the subject line when an email is viewed in the inbox. Many modern email clients helps the recepient get an idea of what the email contains.</div>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class="row">
									<div class="col-lg-6">
										<div class = 'formLabel'>Please Confirm</div>
										<div class="contacts-list" style = 'height:auto;'>											
											<div><input type="checkbox" name="terms" class = 'form-control' id="terms" value="1" />
												I agree to not rent/purchase and use any third party mailing list.<br /> I also agree to comply with all BoldInbox <a class = 'font400' target="_blank" href = '<?php echo  base_url().'home/terms';?>'><b>Terms & Policies</b></a>
											</div>											
										</div>
									</div>
									<div class="col-lg-6 tipBox">
										<div class = 'formLabelTips'>Select the list you would like to upload your contacts to.</div>
										<div class = 'formTips'>A preheader is the short summary text that follows the subject line when an email is viewed in the inbox. Many modern email clients helps the recepient get an idea of what the email contains.</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class="row">
									<div class="col-lg-6">
										<div class = 'formLabel'>That's it! Just click the button & relax.</div>
										<button type = 'sumit' name = 'save' value = 'Import Now' class = 'blue rectangle send_now'>Import Now</button>
										<!-- Show progress  -->
											<div id="progress-bar"></div>
									</div>
									<div class="col-lg-6 tipBox">
										<div class = 'formLabelTips'>How the contact import process works?</div>
										<div class = 'formTips'>Please follow our Support section to learn more about it. <a href = '#'>Click Here</a></div>
									</div>
								</div>
							</div>
						</div>
						
						
						</form>
						
						
					</div>					
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Popup for confirmation before sending campaign -->
<div style="display:none;" id="send_now_msg">
  <p>
	<strong>Total Contacts:</strong> <span class="number_of_contact"></span><br />
	<strong>Subject:</strong> <span class="campaign_subject"></span><br />
	<strong>From Name:</strong> <span class="campaign_sender_name"></span><br />
	<strong>From Email:</strong> <span class="campaign_sender_email"></span><br />
  </p>
  <div class="message_button">
	<a class="fast_confirm_proceed send_mail btn confirm">Yes, Send Now.</a>
	<a class="fast_confirm_cancel cancel_mail btn cancel">No, I Need to Change</a>
  </div>
</div>
	
	<!-- Show settings to Schedule for latter -->
					
        <div class="schedule_delivery" style="display:none;">
          
		  <div>Delivery Date:<br />
          <?php  echo '<input value="'.$campaign_data['camapign']['delivery_date'].'" id="scheduled_date" name="scheduled_date" type="text" size="40" style="width:160px; height:22px;"  readonly>'; ?>         
		  
		  </div>
		

		 
		 
		 
		 
		 
		  <br />
         <div> Start Sending at:<br />
          <select class="select" style="margin:3px 5px 0 0;border:solid 1px #CCC;width:70px;" name="sch_hours">
          <?php
            for($i=1;$i<=12;$i++){
              if($campaign_data['camapign']['send_time'][0]==$i){
                echo "<option value='$i' selected='selected'>".$i."</option>";
              }else{
                echo "<option value='$i'>".$i."</option>";
              }
            }
          ?>
          </select>
          <select  class="select" style="margin:3px 5px 0 0;border:solid 1px #CCC;width:70px;" name="sch_min">
            <?php
            for($i=0;$i<=59;$i++){
              if(strlen($i)==1){
                if($campaign_data['camapign']['send_time'][1]=="0".$i){
                  echo "<option value='$i' selected='selected'>0".$i."</option>";
                }else{
                  echo "<option value='$i'>0".$i."</option>";
                }
              }else{
                if($campaign_data['camapign']['send_time'][1]==$i){
                  echo "<option value='$i' selected='selected'>".$i."</option>";
                }else{
                  echo "<option value='$i'>".$i."</option>";
                }
              }
            }
            ?>
          </select>
          <select  class="select" style="margin:3px 0px 0 0;border:solid 1px #CCC;width:70px;" name="sch_time">
            <?php if($campaign_data['camapign']['send_time'][2]=="am"){ ?>
              <option value="am" selected="selected">AM</option>
            <?php }else{ ?>
              <option value="am">AM</option>
            <?php } ?>
            <?php if($campaign_data['camapign']['send_time'][2]=="pm"){ ?>
              <option value="pm" selected="selected">PM</option>
            <?php }else{ ?>
              <option value="pm">PM</option>
            <?php } ?>
          </select>
		  </div>
		  <div>
		  <?php $member_time_zone = array_search($this->session->userdata('member_time_zone'),getTimezones() );?>
          <p style="margin: 5px 0"><small><b><?php echo $member_time_zone;// US Pacific Time (Los Angeles)?>. </b> To change your timezone, go to <a href='<?php echo site_url("account/index");?>' style="text-decoration:underline;">Settings</a></small><p>
		  </div>
         <div class="btn-group message_button" style = ''>
            <?php
              echo form_button(array('name' => 'campaign_submit', 'id' => 'btnEdit','class'=>'inline-block schedule_email form_submit_btn_class','content' => 'Schedule Now' ), 'Schedule');
              echo form_button(array('name'=>'campaign_cancel','class'=>'btn cancel inline-block form_submit_btn_class', 'value'=>'Cancel','content'=>'Cancel, Not Now','onclick'=>"$.modalBox.close();"));
            ?>
          </div>

        </div>
					<!-- Show settings to Schedule for latter -->

<div style="display:none;" id="quota_exceeded_msg"> 
	<p align="left" style="font-size:14px;">
	Oops! You have almost reached your sending limit. You can only send to <span class="remaining_quota" style="font-size:15px;font-weight:bold;">250</span> contacts at the moment. Please contact us at <a href = 'mailto:support@boldinbox.com'>support@boldinbox.com</a> for any possible solution.

  </p>
    <div class="message_button">
    
	<a href = 'javascript:void(0);' class = 'cancel_mail'>OK, Thanks.</a>
  </div>

</div>
<!-- Add Other From Emails -->
<div style="display:none;" id="add_other_from_emails">
	<div id="add_other_from_emails_form">
       
        <p>
          Please enter the email address you would like to use to send your emails:<br/>
          <input type="text" name="another_emailid" id="another_emailid" size="40" style="width:325px; margin:10px 0px;" /><span id='errInvalid' style="font-weight:bold; color:#ff0000 !important;padding-left:15px"></span>
        </p>
		<div class="message_button">
			<a href="javascript:void(0);"  onclick="save_another_eml();" class="btn add">Submit</a>
		</div>
	</div>
</div>
<div style="display:none;" id="verify_eml">
        <h5>Verify your email</h5>
        <p>A verification email was sent , please click on the verification link and select the appropriate reason for changing your email address.</p>
</div>
<div style="display:none; " id="InvalidDomain">
	<div>
			<p><b>Important Notice:</b> To prevent the risk of delivery issues, use a FROM EMAIL address at your own custom domain. We are requesting all our valuable users to not use domains such as Yahoo, GMAIL, AOL, Hotmail, etc as sending (FROM EMAIL) domains.</p>

			<p>If you need any help to get a new domain for your business, you can email us at <a href = 'mailto:support@boldinbox.com'>support@boldinbox.com</a> with your domain choice.</p>
	</div>
	<div class="message_button">
			<a href="javascript:void(0);"  class = 'cancel_mail'>OK. Thanks.</a>
		</div>
</div>
<!-- Add Other From Emails -->
<?php

function getTimezones(){
return
array (
  '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
  '(GMT-11:00) Midway Islands Time' => 'Pacific/Apia',
  '(GMT-10:00) Hawaii Standard Time' => 'Pacific/Honolulu',
  '(GMT-09:00) Alaska Standard Time' => 'America/Anchorage',
  '(GMT-08:00) Pacific Standard Time' => 'America/Los_Angeles',
  '(GMT-07:00) Mountain/Phoenix Standard Time' => 'America/Phoenix',
  '(GMT-06:00) Central Standard Time' => 'America/Chicago',
  '(GMT-05:00) Eastern Standard Time' => 'America/New_York',
  '(GMT-05:00) Indiana Eastern Standard Time' => 'America/Indiana/Indianapolis',
  '(GMT-04:00) Puerto Rico and US Virgin Islands Time' => 'America/Halifax',
  '(GMT-03:30) Canada Newfoundland Time' => 'America/St_Johns',
  '(GMT-03:00) Brazil-Eastern/Argentina Standard Time' => 'America/Sao_Paulo',
  '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
  '(GMT-01:00) Central African Time' => 'Atlantic/Azores',
  '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
  '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
  '(GMT+01:00) European Central Time' => 'Europe/Berlin',
  '(GMT+02:00) Eastern European Time' => 'Europe/Istanbul',
  '(GMT+02:00) (Arabic) Egypt Standard Time' => 'Asia/Jerusalem',
  '(GMT+03:00) Eastern African Time' => 'Africa/Nairobi',
  '(GMT+03:30) Middle East Time' => 'Asia/Tehran',
  '(GMT+04:00) Near East Time' => 'Asia/Muscat',
  '(GMT+04:30) Kabul' => 'Asia/Kabul',
  '(GMT+05:00) Pakistan Lahore Time' => 'Asia/Karachi',
  '(GMT+05:30) India Standard Time' => 'Asia/Calcutta',
  '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
  '(GMT+06:00) Bangladesh Standard Time' => 'Asia/Dhaka',
  '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
  '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
  '(GMT+07:00) Vietnam Standard Time' => 'Asia/Bangkok',
  '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
  '(GMT+08:00) China Taiwan Time' => 'Asia/Hong_Kong',
  '(GMT+09:00) Japan Standard Time' => 'Asia/Tokyo',
  '(GMT+09:30) Australia Central Time' => 'Australia/Adelaide',
  '(GMT+10:00) Australia Eastern Time' => 'Australia/Sydney',
  '(GMT+11:00) Solomon Standard Time' => 'Asia/Magadan',
  '(GMT+12:00) New Zealand Standard Time' => 'Pacific/Auckland',
  '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
);
}
?>