<script type="text/javascript">
$(document).ready(function(){	
  <?php  if('1'==$extra['contact_import_progress']){ ?>
		setInterval('checkImportStatus()',10000);
    <?php  }else { ?>
		$('.subscriber_msg').hide();
    <?php } ?> 
  
		display_contacts(0);
		
	$('#delete_subscriber').live('click',function(event){  
    if($(this).hasClass('disabled_select')){
      return false;
    }
    <?php  if($package_max_contacts==100){ ?>
        var check_subscriber_id=0;
      $('.check-boxalign').each(function () {
        if (this.checked) {
          check_subscriber_id++;
        }
      }
                               );
      if((check_subscriber_id>1)||($('#action').val()=='page')){        
		displayAlertMessage('Oops! Sorry.','You are not allowed to delete more than 1 contact at a time in <b>FREE account</b>. Please upgrade your account to delete more than 1 contact at a time.','<a href = "'+site_url+'change_package/index">I want to upgrade now.</a>',true,350,150,false,'');
      }
      else{
		$this = $(this);		
		displayAlertMessage('Please Confirm!','','0',true,450,150,false,'');
		$( "#message" ).load( $this.attr('href') ); return false; 
      }
      <?php  }else {?>
		$this = $(this);		
		displayAlertMessage('Please Confirm!','','0',true,450,150,false,'');
		$( "#message" ).load( $this.attr('href') ); return false;    
      <?php  } ?>
      return false;
  });	
		
  });
</script>

    <?php  if('1'==$extra['contact_import_progress']){ ?>
    <div class="contacts_message">
      Your contacts import is under progress. As soon as it completed, we will notify you. Meanwhile, you can create your email campaign. <a class = 'au' href = '<?php echo site_url("promotions");?>'><b>Create Campaign Now</b></a>.
    </div>
    <?php  } ?>
<!-- body - contacts listing starts -->	
			 <form  method="post" name="form1" id="form1" onsubmit="submit_frm(); return false;">
			  <input type="hidden" name="contact_list_action" id="contact_list_action" />
			  <input type="hidden" name="select_action" id="select_action" />
			  <input type="hidden" name="action" id="action" value="" />
			  <input type="hidden" name="uncheck_list" id="uncheck_list" value="" />
			  <input type="hidden" name="action_notmail" id="action_notmail" value="" />
			  <input type="hidden" name="checked" id="checked" value="" />
			  <input type="hidden" name="search_key" id="search_key" value="" />
			  <input type="hidden" name="order_by" id="order_by" value="" />
			  <input type="hidden" name="order_by_column" id="order_by_column" value="" />
			  <input type="hidden" name="order_by_paging" id="order_by_paging" value="" />
			  <input type="hidden" name="visible_contacts_count" id="visible_contacts_count" value="" />
			  <input type="hidden" name="subscription_selected_id" id="subscription_selected_id" value="<?php  echo $subscription_first_id; ?>" />
			  
			<div class = 'ub-contact-top'>
				<div class = 'contacts_select_new' id = 'contacts_select'>					
					<div id = 'contacts_selected' class = 'contacts_selected_c'>All My Contacts (<?php echo $total["'".(0-$extra['member_id'])."'"];?>)</div>
					<div class = 'contacts_select_show' style = 'overflow:hidden;'>
					<!-- lists -->
					<div class = 'contacts_active' style = 'overflow:auto;overflow-x:hidden;height:198px;'>
							<div class = 'contacts_select_show_head'>&raquo; Active Subscribers</div>
							<ul>
								<?php 
									if(count($subscriptions)) {
										foreach($subscriptions as $listname){
											if($listname['subscription_id'] != ($extra['member_id']*-1)){
												echo '<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id = "'.$listname['subscription_id'].'" class = "contacts_select_show_list_name_a"><span>'.$listname['subscription_title'].'</span> ('.$total["'".$listname['subscription_id']."'"].')</a></div><div class = "contacts_select_show_list_action"><a href = "#" class = "list_edit">Edit</a> | <a href = "javascript:void(0);" id="list_'.$listname['subscription_id'].'" class="listdelete">Delete</a></div></li>';
											}else{
												echo '<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id = "'.$listname['subscription_id'].'" class = "contacts_select_show_list_name_a">'.$listname['subscription_title'].' ('.$total["'".$listname['subscription_id']."'"].')</a></div></li>';
											}
											
										}
									}
								?>
							</ul>
						</div>
						<div class = 'contacts_dnm'>
							<div class = 'contacts_select_show_head'>&raquo; Do Not Mail List</div>
							<ul>
								<li><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);' id="bounce_count" class="contacts_select_show_list_name_a">Bounces (<?php echo $bounce_count;?>)</a></div></li>
								<li><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);' id="unsubscribe_count" class="contacts_select_show_list_name_a">Unsubscribes (<?php echo $unsubscriber_count;?>)</a></div></li>
								<li><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);' id="complaint_count" class="contacts_select_show_list_name_a">Complaints (<?php echo $complaint_count;?>)</a></div></li>
								<li><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);' id="removed_count" class="contacts_select_show_list_name_a">Removed (<?php echo $removed_count;?>)</a></div></li>
							</ul>
						</div>
					<!-- lists -->
					</div>
				</div>
				
				<div class = 'contacts_search'>
					<input type = 'text' name = 'email_search' id = 'email_search' placeholder = 'email@domain.com' />
					<input type = 'image' src = '<?php  echo $this->config->item('locker');?>images/icons/search.png' width = '' name="btnSearch" id="btnSearch" />
				</div>
				<div class = 'clear5'></div>
				<div class="contacts_message" id="msg"></div>	
			</div>
			
			
			<div class = 'contacts_section'>				
				<div class = 'contacts_tools'>
					<a href="javascript:void(0);" onclick="updateChecked('page',true);" id="select_list">Select All</a>
					<div style = 'display:inline;position:relative;'>
						<a href="javascript:void(0);" onclick="slideMenu('move_list')" >Move To</a>
						<ul  class="move_list drop-down" >
						  <?php //print_r($select_subscriptions);
							/*foreach($select_subscriptions as $subscription){
							if($subscription['subscription_id'] > 0){
							  echo "
							  <li  onclick='submit_frm(".$subscription['subscription_id'].",\"move\")' name='".$subscription['subscription_id']."' class='move_".$subscription['subscription_id']." list' >
							  <a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;'>".ucfirst(substr($subscription['subscription_title'],0,25))."</a>
							  </li>
							  ";
							}
							}
							*/
								echo "
								<li onclick='unsubscribe_list(".(0-$extra['member_id']).",\"unsubscribe\")' name='".(0-$extra['member_id'])."' class='do-not-mail-option' >
								<a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;color:#CC0000;'>
								Do Not Mail List
								</a>
								</li>
								";
							
						  ?>
						</ul>
					</div>
					<div style = 'display:inline;position:relative;'>	
					<a href="javascript:void(0);" onclick="slideMenu('copy_list')" >Copy To</a>
						<ul  class="copy_list drop-down">
						  <?php
							foreach($select_subscriptions as $subscription){
								if($subscription['subscription_id'] > 0)
							  echo "
							  <li onclick='submit_frm(".$subscription['subscription_id'].",\"copy\")' name='".$subscription['subscription_id']."' class='copy_".$subscription['subscription_id']." list' >
							  <a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;'>
							  ".ucfirst(substr($subscription['subscription_title'],0,25))."
							  </a>
							  </li>
							  ";
							}
						  ?>
						  </ul>
					</div>
					<?php if($extra['manage_contacts'] == 1){ ?>					
						<a href="<?php  echo base_url(); ?>subscriber/subscriber_delete/<?php  echo $subscription_first_id; ?>" class="btn add delete_subscriber red " id="delete_subscriber">Delete</a> 
					<?php }elseif($extra['manage_contacts'] == 2){ ?>
						<a href="javascript:void(0);" class="export_csv btn cancel">Export To CSV</a>
					<?php }else{?>
						<a href="<?php  echo base_url(); ?>subscriber/subscriber_delete/<?php  echo $subscription_first_id; ?>" class="btn add delete_subscriber red " id="delete_subscriber">Delete</a> 
						<a href="javascript:void(0);" class="export_csv btn cancel">Export To CSV</a>					
					<?php }?>
				</div>
				
				
				<div  class = 'contacts_heads'>
					<div class = 'ch_1'></div>
					<div class = 'ch_2'><a href='javascript:void(0);' onclick='order_by("subscriber_email_address")'>Email Address</a> <span>&#8693;</span></div>
					<div class = 'ch_3'><a href='javascript:void(0);' onclick='order_by("subscriber_first_name")'>Name</a> <span>&#8693;</span></div>
					<div class = 'ch_4'></div>
				</div>
				
				<div  class = 'contacts_list tbl-contacts'><!-- here all contacts get loaded --> 
				<div style='display:block; 400px; min-height:550px;'>&nbsp;</div></div>
				<div class = 'contacts_tools_bottom'>
					<div class = 'show_records_per_page'><?php if($extra['manage_contacts'] >= 2){ ?>
					<a href="javascript:void(0);" class="export_csv button white">Export To CSV</a>
					<?php }?></div>
					<div class = 'pagination_div'></div>
					
				</div>
			</div>
		</form>	
			
			<!-- body - contacts listing ends -->	
	


	
    <!--Define hidden pop-up boxes -->
    <div class="unsubscriber_box" style="display:none">
      <div class="fancybox-page registration-page_contact_delete" >
        <div style="width:600px; margin:15px auto;">
          <div class="fancybox-form contact_frm" style="height:120px;width:500px;margin:5px 25px;" >
            <input type="hidden" name="unsubscriber_subscription_id" id="unsubscriber_subscription_id" value="<?php  echo $subscription_id; ?>" />
            <table  width="100%" border="0" cellspacing="0" cellpadding="0"  class="contact_tbl">
              <tr>
                <td class="popup_large">
                  Do you want to in-activate <b id='total_unsubscribe_count' class="error"></b> contact(s).
                </td>
              </tr>
              
              <tr>
                <td colspan="2">
				<div class="message_button">
					<?php echo form_submit(array('name' => 'subscription_submit', 'id' => 'btnEdit','class'=>'btn danger add_more form_submit_btn_class','content' => 'Submit','style' => 'margin-left:5px; width: 232px; padding: 4px 0','onclick'=>'submit_unsubscribe_form()'), 'Add to Do Not Mail List'); ?>
					<?php echo form_button(array('name' => 'subscription_cancel', 'id' => 'subscription_cancel','class'=>'btn cancel form_submit_btn_class','content' => 'Cancel','style' => 'margin-left:5px;','onclick'=>'javascript:$.modalBox.close();'), 'Cancel'); ?>
				</div>
                </td>
              </tr>
            </table>
          </div>
        </div>        
        <!--[/body]-->
      </div>
    </div>
<!-- Hidden popup to add list -->	
    <div id="subscription_edit" style="display:none;" >
      <div id="edit-list">
        <form onsubmit="saveSubscriptionTitle(this); return(false);" method="post" class="form-website" id="subscriptionfrmToEdit"  name="subscriptionfrmToEdit">
          <div class="subscription_msg contacts_message" style = 'height:15px;width:67%;margin-bottom:5px;'>List created successfully.</div>
		  Enter List Name:         
          <div>
            <?php echo form_input(array('name'=>'subscription_title','id'=>'subscription_title','maxlength'=>250,'size'=>40,'value'=>set_value('subscription_title'),'class'=>'subscription_title')); ?>
          </div>
          <div class="btn-group message_button">
			<input type="hidden" name="hidListId" id="hidListId" />
            <?php
              echo form_submit(array('name' =>'subscription_submit', 'id' =>'btnEdit','content' =>'Submit', 'class' => "btn confirm form_submit_btn_class"), 'Update List');
              echo form_button(array('name'=>'campaign_cancel', 'value'=>'Cancel','content'=>'Cancel','onclick'=>"$.modalBox.close();", 'class' => "btn cancel form_submit_btn_class"));
            ?>
          </div>
        </form>
      </div>
    </div>
    
