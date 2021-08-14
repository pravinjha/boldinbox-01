<section class="section-new">
	<div class="container">
		<div class="row">            
			<div class="col-lg-12">		
				<div class="section-inner">		
				<form  method="post" name="form1" id="message" onsubmit="submit_frm(); return false;">
					<div class="row">
						
						<!-- THIS IS MESSAGE BAR -->							
							<?php  if('1'==$extra['contact_import_progress']){ ?>
							 <div class="col-lg-12">
							<div class="alert alert-warning" role="alert">
							  Your contacts import is under progress. As soon as it completed, we will notify you. Meanwhile, you can create your email campaign. <a class = 'au' href = '<?php echo site_url("promotions");?>'><b>Create Campaign Now</b></a>.
							</div>
							</div>
							<?php  } ?>						 
							<!-- THIS IS MESSAGE BAR -->
						
						<div class="col-lg-12">
							<div class="campaign-post">							
								<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-4">
										<!-- List of contacts- Starts -->
										<div class = 'dropDownCustom' id = 'contacts_select'>					
											<div id = 'contacts_selected' class = ' dropDownCustomSelected contacts_selected_c'>All My Contacts (<?php echo $total["'".(0-$extra['member_id'])."'"];?>)</div>
											<div class = 'contacts_select_show dropDownCustomOpened' style = 'overflow: hidden;'>
												<!-- lists -->
												<div>
													<div class = 'contacts_select_show_head dropDownCustomHeading'>
														<div class='heading_section'>Active Subscribers</div>
														<div class = 'link_section'><a onclick="javascript: createList();" href = "javascript: void(0);">+ New List</a></div>
													</div>
														<ul class = 'contacts_active'>
															<?php 
																if(count($subscriptions)) {
																	foreach($subscriptions as $listname){
																		if($listname['subscription_id'] != ($extra['member_id']*-1)){
																			echo '<li>
																				<div  id = "'.$listname['subscription_id'].'" class = "contacts_select_show_list_name contacts_select_show_list_name_a"><a href = "javascript:void(0);">'.$listname['subscription_title'].' ('.$total["'".$listname['subscription_id']."'"].')</a></div><div class = "contacts_select_show_list_action"><a href = "#" class = "list_edit">Edit</a> &nbsp; <a href = "javascript:void(0);" id="list_'.$listname['subscription_id'].'" class="listdelete">Delete</a></div>
																			</li>';
																		}else{
																			echo '<li>
																				<div id = "'.$listname['subscription_id'].'" class = "contacts_select_show_list_name contacts_select_show_list_name_a"><a href = "javascript:void(0);">'.$listname['subscription_title'].' ('.$total["'".$listname['subscription_id']."'"].')</a></div>
																			</li>';
																		}
																		
																	}
																}
															?>
														</ul>
													</div>
													<div class = 'contacts_dnm'>
														<div class = 'contacts_select_show_head dropDownCustomHeading'>Do Not Mail List</div>
														<ul>
															<li><div  id="bounce_count" class = 'contacts_select_show_list_name contacts_select_show_list_name_a'><a href = 'javascript:void(0);'>Bounces (<?php echo $bounce_count;?>)</a></div></li>
															<li><div  id="unsubscribe_count" class = 'contacts_select_show_list_name contacts_select_show_list_name_a'><a href = 'javascript:void(0);'>Unsubscribes (<?php echo $unsubscriber_count;?>)</a></div></li>
															<li><div id="complaint_count" class = 'contacts_select_show_list_name contacts_select_show_list_name_a'><a href = 'javascript:void(0);'>Complaints (<?php echo $complaint_count;?>)</a></div></li>
															<li><div id="removed_count" class = 'contacts_select_show_list_name contacts_select_show_list_name_a'><a href = 'javascript:void(0);'>Removed (<?php echo $removed_count;?>)</a></div></li>
														</ul>
													</div>
												<!-- lists -->
											</div>
										</div>
										<!-- List of contacts- Ends -->                 
									</div>
									<div class="col-lg-8 col-md-6 col-sm-8">
										<div class = 'contacts_search'>
											<input type = 'text' name = 'email_search' id = 'email_search' placeholder = 'email@domain.com'  class="form-control" />
											<button type="submit" class="primary-button pink round round40" name="btnSearch" id="btnSearch"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-12 contacts">
							<div class="campaign-post">							
								<div class="row">
									<div class="col-lg-12 contacts-grid">
										<div class="row row-head">
											<div class="col-1 contacts-cols"><input type="checkbox" title="Select All Contacts" onclick="updateChecked('page',true);" id="select_list"></div>
											<div class="col-6 contacts-cols" onclick='order_by("subscriber_email_address")'>Email Address &nbsp; <i class="fa fa-sort"></i></div>
											<div class="col-2 contacts-cols col-name"  onclick='order_by("subscriber_first_name")'>Name &nbsp; <i class="fa fa-sort"></i></div>
											<div class="col-3 contacts-cols move-copy-icons">
												<ul class="contacts-ops-links">
													<li class="link-move-copy"><a href="#"><i class="fa fa-arrows"></i></a></li>
													<li class="link-move-copy"><a href="#"><i class="fa fa-files-o"></i></a></li>
												</ul>
											</div>
											<div class="col-3 contacts-cols move-copy">
												<button  onclick="slideMenu('move_list')" class = 'buttonSm'><i class="fa fa-arrows"></i> Move To</button>
												<div  class="move_list drop-down">
												<ul>
													<?php
													echo "
													<li onclick='unsubscribe_list(".(0-$extra['member_id']).",\"unsubscribe\")' name='".(0-$extra['member_id'])."' class='do-not-mail-options' >
													<a href='javascript:void(0);'>Do Not Mail List</a>
													</li>
													";
													?>
												</ul>
												</div>											
												<button onclick="slideMenu('copy_list')" class = 'buttonSm'><i class="fa fa-files-o"></i> Copy To</button>
												<div  class="copy_list drop-down">
												<ul>
													<?php
													foreach($select_subscriptions as $subscription){
													if($subscription['subscription_id'] > 0)
													echo "
													<li onclick='submit_frm(".$subscription['subscription_id'].",\"copy\")' name='".$subscription['subscription_id']."' class='copy_".$subscription['subscription_id']." list' >
													<a href='javascript:void(0);'>
													".ucfirst(substr($subscription['subscription_title'],0,25))."
													</a>
													</li>
													";
													}
													?>
												</ul>
												</div>
											</div>
										</div>
										<div  class = 'contacts_list tbl-contacts'><!-- Rows of Contacts will be shown here --></div>
										<div class="row row-footer">						
											<div class="col-12 contacts-cols">
											<?php //if($extra['manage_contacts'] > 0){ ?>					
											<button href="<?php  echo base_url(); ?>subscriber/subscriber_delete/<?php  echo $subscription_first_id; ?>" class="delete_subscriber buttonSm" id="delete_subscriber">Delete</button> 
											<?php // }?>
											<?php if($extra['manage_contacts'] >= 0){ echo '<button class="export_csv buttonSm">Export To CSV</button>';}?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="col-lg-12">
							<div class="campaign-post">							
								<div class="row">
									<div class="col-lg-12">
										<div class="campaign-pagination pagination_div"></div>
								    </div>	
								</div>
							</div>
						</div>
						
						
					</div>
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
				</form>
				</div>
			</div>
		</div>
	</div>
</section>

	
	<script type="text/javascript">
$(document).ready(function(){	
  <?php  if('1'==$extra['contact_import_progress']){ ?>
		setInterval('checkImportStatus()',10000);
    <?php  }else { ?>
		$('.subscriber_msg').hide();
    <?php } ?> 
  
		display_contacts(0);
		
	$('#delete_subscriber').on('click',function(event){  
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
		
		
window.onclick = function(event) {			
	  if (!event.target.matches('.dropDownCustomSelected') && !event.target.matches('.dropDownCustomHeading') && !event.target.matches('.heading_section') && !event.target.matches('.link_section')) {
			$('.dropDownCustomOpened').slideUp();
	  }
	}
});
</script>

	
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