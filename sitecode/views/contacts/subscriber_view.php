<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-ui-1.8.13.custom.min.js?v=6-20-13"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('locker');?>css/blitzer/jquery-ui-1.8.14.custom.css?v=6-20-13" />

<script type="text/javascript">
  
  function ucfirst(str) {
    var firstLetter = str.substr(0, 1);
    return firstLetter.toUpperCase() + str.substr(1);
  }

  function submit_frm(){
    var block_data="";
    block_data+=$('#contact_frm_submit').serialize();
    console.log(block_data);
    jQuery.ajax({
      url: "<?php echo base_url() ?>subscriber/edit/<?php echo $subscriptions[0]['subscriber_id']; ?>/<?php echo $contact_soft_bounce;?>/<?php echo $contact_bounce_status;?>",
      type:"POST",
      data:block_data,
      success: function(data) {alert(data);
        var data_arr=data.split(":", 2);
        if(data_arr[0]=="error"){
          $('.subscriber_msg').html('');
          $('.subscriber_msg').html(data_arr[1]);
          $('.subscriber_msg').addClass('info');
          $('.subscriber_msg').fadeIn();
        }else if(data_arr[0]=="success"){
          $('.subscriber_msg').html('Contact Updated Successfully');
          $('.subscriber_msg').addClass('info');
          $('.subscriber_msg').fadeIn();
          var elements=$('.custom_text');
          elements.each(function() {
            var val;
            val=$(this).val();
            val= val.replace(/^\s+|\s+$/g,"");
            if(val==""){
              var parent_ob=$(this).parent().parent();
              parent_ob.prev().remove();
              parent_ob.remove();
            }
          });
          /* parent.$('#subscriber_tr_<?php echo $subscriptions[0]['subscriber_id']; ?>').find('.subscriber_firstname').html($("input#first_name").val());
          parent.$('#subscriber_tr_<?php echo $subscriptions[0]['subscriber_id']; ?>').find('.subscriber_lastname').html($("input#last_name").val());
          parent.$('#subscriber_tr_<?php echo $subscriptions[0]['subscriber_id']; ?>').find('.subscriber_email').html($("input#email_address").val());
          setTimeout(function(){$('.subscriber_menus').fadeOut();} , 4000); */
        }
      }
    });
  }
  function moreHeight(){
    var space = document.createElement('div');
    space.setAttribute('id', 'dummy');
    space.style.height = "450px";
    space.style.clear = "both";
    document.getElementsByTagName("body")[0].appendChild(space);
    window.scrollBy(100,350);
    setTimeout('window.scrollBy(0,250)',50000);
  }
  function reduceHeight(){
    var rem = document.getElementById('dummy');
    document.getElementsByTagName("body")[0].removeChild(rem);
  }


function onScrollPaging(){
  var psize = $('#page_counter').val();
    jQuery.ajax({
      url: "<?php echo base_url() ?>subscriber/ajaxHistory/<?php echo $subscriptions[0]['subscriber_id'] ; ?>/<?php echo $subscriptions[0]['subscriber_id'] ; ?>/<?php echo $subscriptions[0]['subscriber_id'] ; ?>/"+psize+"/",
      type:"POST",

      success: function(data) {
        $(".history_contact_rec").append(data);
        $('div#last_msg_loader').html('');
        if(data.length == 0) {
          $(window).unbind("scroll");
          var $el = $("#last_msg_loader");
          if($el.children().length == 0) {
            $el.html("<h2>No Records Found</h2>");
          } else {
            $el.append("<h2>All Records have been loaded.</h2>");
          }
        }
      }
    });
  };
  $(document).ready(function(){
    $(window).scroll(function(){
      if($(window).scrollTop() == $(document).height() - $(window).height()){
        $('div#last_msg_loader').html('<img src="<?php echo $this->config->item('locker');?>images/loader.gif?v=6-20-13">');
        var psize = parseInt($('#page_counter').val()) + 1;
        $('#page_counter').val(psize)
        setTimeout("onScrollPaging()",3000);
      }
    });

  });
</script>
<!--[/main script] -->


<!--[body]-->
	<div class = 'ub-contact-top'>
		<div class = 'contacts_select_new' id = 'contacts_select' style = 'width:26%;'>					
			<div id = 'contacts_selected' class = 'contacts_selected_c'>Edit Info For</div>
			<div class = 'contacts_select_show' style = 'height:100px;'>				
				<div>
					<div class = 'contacts_select_show_head'>&raquo; Select Page</div>
					<ul>
						<li class = 'prl'><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);'>Edit Info For </a></div><div id = 'Edit Info For'  rel = 'cnt_edit_info' class = 'transparent_place_holder'></div></li>
						<li class = 'prl'>
							<div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);'>View Details For </a></div>
							<div rel = 'cnt_view_details' id = 'View Details For' class = 'transparent_place_holder'></div>
						</li>
						
						<!-- li class = 'prl'><div class = 'contacts_select_show_list_name'><a href = 'javascript:void(0);'>View History For </a></div><div  id = 'View History For' rel = 'cnt_view_history' class = 'transparent_place_holder'></div></li -->						
					</ul>
				</div>
			</div>
		</div>

	
								
		<div class = 'view_contact_select_label'><?php echo $subscriptions[0]['subscriber_email_address'];?></div>
	</div>
	<div class = 'contacts_import'>	
				<div id = 'cnt_view_details' class = 'cl_vw_cnt dn'>
					<div class = 'contacts_import_list_label'>Contact Status:</div>
					<div class = 'contacts_import_list'>
						<?php echo ($subscriptions[0]['subscriber_status']==1)? 'Active':'Inactive'; ?>
					</div>
					
					<div class = 'contacts_import_list_label'>Contact Present in Lists:</div>
					<div class = 'contacts_import_list'>
						 <?php 
							$i=1;
							foreach($subscription_title As $subscription){
								echo  $subscription;
								if($i!=count($subscription_title)) echo ", "; 
								$i++;
							} 
						?>
					</div>
					
					<div class = 'contacts_import_list_label'>Contact Added On:</div>
					<div class = 'contacts_import_list'>
						<?php              
						  echo $date = date('F j, Y \a\t g:i a', strtotime(getGMTToLocalTime($subscriptions[0]['subscriber_date_added'], $this->session->userdata('member_time_zone'))));
						?>
					</div>
					
					<div class = 'contacts_import_list_label'>Contact Added By:</div>
					<div class = 'contacts_import_list'>
						 <?php if($subscriptions[0]['is_signup']==1){ echo "Signup-form" ; } else { echo "Self: ".$this->session->userdata('member_username'); } ?>
					</div>
				</div>
				<div id = 'cnt_edit_info' class = 'cl_vw_cnt db'>
				<form  method="post" name="contact_frm_submit" id="contact_frm_submit" class="contact_frm_edit" onsubmit="submit_frm(); return false;" >
					<?php
					  if(($subscriptions[0]['subscriber_status']!=1)&&($subscriptions[0]['subscriber_status']!=3)&&($subscriptions[0]['subscriber_status']!=4)){
						$readonly="readonly";
					  }else{
						$readonly="";
					  }
					?>
					<div id = 'cnt_edit_info_fields'>
						<div class = 'contacts_import_list_label'>Email Address:</div>
						<div class = 'contacts_import_list'>
							<?php echo form_input(array('name'=>'subscriber_email_address','id'=>'email_address','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_email_address'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly ));
							?>													
						</div>
						<div class = 'clear10'></div>
						
						<div class = 'contacts_import_list_label'>First Name:</div>
						<div class = 'contacts_import_list'>
							<?php echo form_input(array('name'=>'subscriber_first_name','id'=>'first_name','maxlength'=>250,'size'=>40,'value'=>stripslashes($subscriptions[0]['subscriber_first_name']),'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly ));
							?>
						</div>
						<div class = 'clear10'></div>
						
						<div class = 'contacts_import_list_label'>Last Name:</div>
						<div class = 'contacts_import_list'>
							<?php echo form_input(array('name'=>'subscriber_last_name','id'=>'last_name','maxlength'=>250,'size'=>40,'value'=>stripslashes($subscriptions[0]['subscriber_last_name']),'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly));
							?>	
						</div>
						<div class = 'clear10'></div>
						<?php
						if(trim($subscriptions[0]['subscriber_address'])!=""){
							echo "<div class = 'contacts_import_list_label'>Address:</div>";
							echo "<div class = 'contacts_import_list'>".form_input(array('name'=>'address','id'=>'address','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_address'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' )).'';
							echo "</div>
								<div class = 'clear10'></div>";
						  }
						?>
						<?php
						if(trim($subscriptions[0]['subscriber_address'])!=""){
							echo "<div class = 'contacts_import_list_label'>Address:</div>";
							echo "<div class = 'contacts_import_list'>".form_input(array('name'=>'address','id'=>'address','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_address'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' )).'';
							echo "</div>
								<div class = 'clear10'></div>";
						  }
						?>
						<?php
						if(trim($subscriptions[0]['subscriber_address'])!=""){
							echo "<div class = 'contacts_import_list_label'>Address:</div>";
							echo "<div class = 'contacts_import_list'>".form_input(array('name'=>'address','id'=>'address','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_address'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' )).'';
							echo "</div>
								<div class = 'clear10'></div>";
						}
						
              
						if(trim($subscriptions[0]['subscriber_dob'])!=""){
							echo "<div class = 'contacts_import_list_label'>Birthday:</div>";
							echo "<div class = 'contacts_import_list'>". form_input(array('name'=>'birthday','id'=>'birthday','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_dob'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' )).'';
							
							  if($readonly !=''){
							?>
							  <script type="text/javascript">
								$(function() { $("#birthday").datepicker({changeMonth: true, changeYear: true, yearRange: '1950:2010' }); });
							  </script>
						<?php } 
							echo "</div>
								<div class = 'clear10'></div>";
						}
						
						
						if(trim($subscriptions[0]['subscriber_city'])!=""){
							echo "<div class = 'contacts_import_list_label'>City:</div><div class = 'contacts_import_list'>";
							echo form_input(array('name'=>'city','id'=>'city','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_city'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' ))."</div>
								<div class = 'clear10'></div>";
						}
						if(trim($subscriptions[0]['subscriber_company'])!=""){
							echo "<div class = 'contacts_import_list_label'>Company:</div><div class = 'contacts_import_list'>";
							echo form_input(array('name'=>'company','id'=>'company','maxlength'=>250,'size'=>40,'value'=>stripslashes($subscriptions[0]['subscriber_company']),'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text'))."</div>
								<div class = 'clear10'></div>";
						}
						if(trim($subscriptions[0]['subscriber_country'])!=""){
							echo "<div class = 'contacts_import_list_label'>Country:</div><div class = 'contacts_import_list'>";
							echo form_input(array('name'=>'country','id'=>'country','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_country'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text'))."</div>
								<div class = 'clear10'></div>";
						}
						if(trim($subscriptions[0]['subscriber_phone'])!=""){
							echo "<div class = 'contacts_import_list_label'>Phone:</div><div class = 'contacts_import_list'>";
							echo   form_input(array('name'=>'phone','id'=>'phone','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_phone'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' ))."</div>
								<div class = 'clear10'></div>";
						}
						if(trim($subscriptions[0]['subscriber_state'])!=""){
							echo "<div class = 'contacts_import_list_label'>State:</div><div class = 'contacts_import_list'>";
							echo  form_input(array('name'=>'state','id'=>'state','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_state'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly, 'class'=>'custom_text'))."</div>
								<div class = 'clear10'></div>";
						}
						if(trim($subscriptions[0]['subscriber_zip_code'])!=""){
							echo "<div class = 'contacts_import_list_label'>Zip Code:</div><div class = 'contacts_import_list'>";
							echo  form_input(array('name'=>'zip_code','id'=>'zip_code','maxlength'=>250,'size'=>40,'value'=>$subscriptions[0]['subscriber_zip_code'],'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' ))."</div>
								<div class = 'clear10'></div>";
						}
						
						if($subscriptions[0]['subscriber_extra_fields'] !=''){
							foreach(unserialize($subscriptions[0]['subscriber_extra_fields']) as $col=>$val){
							  if((strpos($col,'first') === false) and (strpos($col,'last') === false) and (strpos($col,'email') === false)){
								if((trim($col)!="")&&(trim($val) !="")){
									echo "<div class = 'contacts_import_list_label'>".ucwords(str_replace('_',' ',urldecode($col))).'<input type="hidden" name="custom_'.str_replace(" ","_",$col).'" value="'.$col.'" /></div>'."<div class = 'contacts_import_list'>";	
									echo form_input(array('name'=>str_replace(" ","_",$col),'id'=>str_replace(" ","_",$col),'maxlength'=>250,'size'=>40,'value'=>$val,'onclick'=>"javascript:$('.custom_list').hide();",$readonly=>$readonly,'class'=>'custom_text' )) ."</div>
									<div class = 'clear10'></div>";
								}
							  }
							}
						}
						
						?>
					</div>
					<div id = 'for_extra_fields'></div>
					
					<div class = 'clear10'></div>					
					<div class = 'clear10'></div>					
					<div class = 'clear10'></div>					
					<a href = 'javascript:void(0);' class = 'add_another_field'>Add Another Field</a>
					<?php if($readonly == ''){ ?>
					<div id = 'add_another_field' class = 'dn'>
						<div class = 'contacts_import_list_label'>Select The New Field To Add:</div>
						<div class = 'contacts_import_list'>
							<select name="select" id = 'add_another_field_choice'>						
								<option>Select</option>
								<option value = 'Address'>Address</option>
								<option value = 'Birthday'>Birthday</option>
								<option value = 'City'>City</option>														
								<option value = 'Company'>Company</option>														
								<option value = 'Country'>Country</option>														
								<option value = 'Phone'>Phone</option>														
								<option value = 'State'>State</option>														
								<option value = 'Zipcode'>Zipcode</option>														
								<option value = 'Custom'>Custom Field</option>														
							</select>						
						</div>
						<div class = 'clear2'></div>
						<a href = 'javascript:void(0);' class = 'cancel_add_another_field'>Cancel</a>
						<div class = 'clear10'></div>
						
						<div id = 'custom_field_creation' class = 'dn'>
							<div class = 'custom_field_creation_label'>
								What's your new custom field?<br />
								<a href = 'javascript:void(0);' id = 'save_cutom_field'>Save</a> | <a href = 'javascript:void(0);' id = 'cancel_cutom_field'>Cancel</a>
							</div>
							<div class = 'custom_field_creation_input'><input type = 'text' id = 'custom_field_value' /></div>
						</div>
						
					</div>
					<div class = 'clear0'></div>
					<div class = 'contacts_import_btn_import'>
					 <?php echo form_submit(array('name' => 'subscription_submit', 'id' => 'btnEdit','class'=>'button large3 blue textCsap','content' => 'Submit'), 'Submit & Save');?>
					</div>
					<input type="hidden" name="subscriber_id" id="subscriber_id" value="<?php echo $subscriptions[0]['subscriber_id'] ; ?>" />
					<input type="hidden" name="subscription_id" id="subscription_id" value="<?php echo $subscriptions[0]['subscription_id'] ; ?>" />
					<?php } ?>
				</form>	
				</div>
				<div id = 'cnt_view_history' class = 'cl_vw_cnt dn'>
					<?php if($contact_history !=''){?>
					<input type="hidden" name="page_counter" id="page_counter" value="0" />
					<input type="hidden" name="contact_soft_bounce" id="contact_soft_bounce" value="<?php echo $contact_soft_bounce;?>" />
					<input type="hidden" name="contact_bounce_status" id="contact_bounce_status" value="<?php echo $contact_bounce_status;?>" />
					<div class = 'history_header'>
						<div class = 'history_header_h1'>Campaign Title</div>
						<div class = 'history_header_h2'>Campaign Sent Date</div>
						<div class = 'history_header_h3'>Campaign Status</div>
					</div>
					<div class = 'history_list'>						
						 <?php echo $contact_history;?>
					</div>
					<?php } else { ?>
						<h2>No Records Found</h2>
					<?php } ?>
				</div>
			</div>

			<!-- body - contacts listing ends -->
