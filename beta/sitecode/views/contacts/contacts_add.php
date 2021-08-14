<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery.blockUI.js?v=6-20-13"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $(".fancybox").fancybox({'autoDimensions':false,'centerOnScroll':true,'scrolling':true,'width':'400','height':'auto'});

    <?php if('1'==$extra['contact_import_progress']){?>
      $('.subscriber_msg').show();
      setInterval('checkImportStatus()',10000);  
    <?php }?>
    });
</script>
<script type="text/javascript" src="<?php echo $this->config->item('locker'); ?>js/ajaxfileupload.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker'); ?>js/contacts_add.js?v=6-20-13"></script>
<!--[body]-->
	 <div class="subscriber_msg info"><?php if('1'==$extra['contact_import_progress']){?>Your list import is under process. Larger lists will take longer. However, navigating away from this page will not interrupt the upload. After completion of process, you will be informed by email.<?php }?></div>
	 
	
	<div class = 'ub-contact-top'>
		<div class = 'contacts_select_new' id = 'contacts_select' style = 'width:26%;'>					
			<div id = 'contacts_selected' class = 'contacts_selected_c'>Upload Excel/CSV File</div>
			<div class = 'contacts_select_show' style = 'height:130px;'>				
				<div>
					<div class = 'contacts_select_show_head'>&raquo; Select Method</div>
					<ul>
						<li class = 'prl'><div class = 'contacts_select_show_list_name'><a href = '#'>Upload Excel/CSV File </a></div><div id = 'Upload Excel/CSV File'  rel = 'import_file' class = 'transparent_place_holder'></div></li>
						<li class = 'prl'>
							<div class = 'contacts_select_show_list_name'><a href = '#'>Copy / Paste Emails </a></div>
							<div rel = 'copy_pase_contacts' id = 'Copy / Paste Emails' class = 'transparent_place_holder'></div>
						</li>
						
						<li class = 'prl'><div class = 'contacts_select_show_list_name' style = 'width:80%;'><a href = '#'>Type Emails & Names One By One </a></div><div  id = 'Type Emails & Names' rel = 'ony_by_one' class = 'transparent_place_holder'></div></li>						
					</ul>
				</div>
			</div>
		</div>								
		<div class = 'view_contact_select_label'>To Import Your Contacts in Your Account</div>
	</div>
		<form onsubmit="importContact(); return(false);" method="post" accept-charset="utf-8" id="contact_import_form" name="contact_import_form" enctype="multipart/form-data">
	<div class = 'contacts_import'>			
					<div class = 'contacts_import_list_label'>Select the list you would like to import your contacts to:</div>
					<div class = 'contacts_import_list'>
						<select name="subscription_contact_one" id="subscription_contact_one">
							<?php
							foreach($select_subscriptions as $subscription){
							  if($subscription_first_id == $subscription['subscription_id'])
								echo "<option value='".$subscription['subscription_id']."' selected>".ucfirst($subscription['subscription_title'])."</option>";
							  else
								echo "<option value='".$subscription['subscription_id']."'>".ucfirst($subscription['subscription_title'])."</option>";
							}
							?>
						</select>						
					</div>
					
					<div id = 'import_file' class = 'cl_imp_cnt'>
						<div class = 'cl_imp_cnt_label'>Upload file with extension CSV or XLS:</div>
						<div class = 'cl_imp_cnt_file'><?php echo form_upload(array('id'=>'subscriber_csv_file','name'=>'subscriber_csv_file','value'=>set_value('subscriber_csv_file') )); ?></div>
						<div class = 'cl_imp_cnt_sample'><a href = '#' id = 'show_sample_file'>Show me a sample</a></div>
					</div>
					
					<div id = 'copy_pase_contacts' class = 'cl_imp_cnt dn'>
						<div class = 'cl_imp_cnt_copy_paste_area'><textarea name="copy_csv" id="copy_csv"></textarea></div>						
					</div>
					
					<div id = 'ony_by_one' class = 'cl_imp_cnt dn'>						
						<div class = 'cl_imp_cnt_ono_h1'>Email Address</div>
						<div class = 'cl_imp_cnt_ono_h2'>First Name</div>
						<div class = 'cl_imp_cnt_ono_h3'>Last Name</div>
					
						<div class = 'cl_imp_cnt_ono_d1'><input name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d2'><input name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div>
						
						<div class = 'cl_imp_cnt_ono_d1'><input name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d2'><input name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div>
						
						<div class = 'cl_imp_cnt_ono_d1'><input name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d2'><input name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div>
						
						<div class = 'cl_imp_cnt_ono_d1'><input name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d2'><input name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div>
						
						<div class = 'cl_imp_cnt_ono_d1'><input name="subscriber_email_address[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_email_address');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d2'><input name="subscriber_first_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_first_name');?>" /></div>
						<div class = 'cl_imp_cnt_ono_d3'><input name="subscriber_last_name[]" maxlength="100" type = 'text' value="<?php echo set_value('subscriber_last_name');?>" /></div>



						
					</div>
					

					<div class = 'contacts_import_agree_terms'>
						<div class = 'contacts_import_agree_terms_chk'>
					<input type="checkbox" name="terms" id="terms" value="1" /></div> 
						<div class = 'contacts_import_agree_terms_terms'>I agree to not rent/purchase and use any third party mailing list.<br /> I also agree to comply with all BoldInbox <a target="_blank" href = '<?php echo  base_url().'terms';?>'>Terms</a>.</div>
					</div>
					<div class = 'contacts_import_btn_import'>
						<input type = 'submit' name = 'save' id='save' value = 'Import Now' class = 'button large3 blue textCsap' />
					</div>		
				
	</div>
	</form>
