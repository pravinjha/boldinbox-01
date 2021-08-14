<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-ui-1.8.13.custom.min.js?v=6-20-13"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('locker');?>css/blitzer/jquery-ui-1.8.14.custom.css?v=6-20-13" />
<style>
  #ui-datepicker-div{display:none;}
</style>
<script type="text/javascript">

  <?php if(!$campaign_data['user_info']){ ?>
    var user_info=false;
  <?php }else{ ?>
    var user_info=true;
  <?php } ?>
  
  var send_now_email=true;
  jQuery('.send_now').live('click',function(){	
    send_now_email=true;
	$('#send_now').val('1');

    var c_sender_name = $('#email_from').val() ;
    var c_sender_email =  $('#email_id').val() ;
    $('.campaign_sender_name').html(c_sender_name);
    $('.campaign_sender_email').html(c_sender_email);
    var c_subject = $('#email_subject').val();
    $('.campaign_subject').html(c_subject);
    if($('#queued_campaign_count').val() > 4){
        displayAlertMessage('Alert!','<p>Already many campaigns are in queue. You can not send any more!</p>','0',true,300,150,false,'');					 
		exit;
    }
	if(c_subject ==''){
		displayAlertMessage('Alert!','<p>An "Email Subject" is required.</p>','0',true,350,150,false,'');
		exit;
	}else if(c_sender_name ==''){
		displayAlertMessage('Alert!','<p>A "From Name" is required.</p>','0',true,350,150,false,'');
		exit;
	}else if(c_sender_email ==''){
		displayAlertMessage('Alert!','<p>A "From Email" is required.</p>','0',true,350,150,false,'');
		exit;
	}else{
		var c_sender_email_domain = c_sender_email.split('@')[1].toLowerCase();
		if(c_sender_email_domain.substring(0,3) === 'aol' || c_sender_email_domain.substring(0,3) === 'gmx' || c_sender_email_domain.substring(0,5) === 'yahoo'){
			displayAlertMessage('Alert!','<p>Due to recent changes at  Yahoo, GMX and AOL, campaigns sent from a <b>yahoo</b> or <b>gmx</b> or <b>aol</b> email addresses will not be delivered. Please use a different "From Email" for your campaigns.</p>','0',true,550,150,false,'');
			exit();
		}
	}

    var selectedContactsCount =0;
    var block_data;
    block_data=$('#form_campaign_send').serialize();
    jQuery.ajax({
      url: "<?php echo base_url(); ?>promotions/selected_subscribers",
      type:"POST",
      data:block_data,
      success: function(data){
        $('.number_of_contact').html(data);
        $('.remaining_quota').html($('#quota_remaining').val());

        if($('#quota_remaining').val() < (1 * data)){
			displayAlertMessage('Email Sending Quota Exceeded','','0',true,500,180,false,'');
			$( "#message" ).html( $("#quota_exceeded_msg").html() ); 
            exit;
        }
        if(user_info){
			displayAlertMessage('Campaign Overview','','0',true,450,240,false,'');
			$( "#message" ).html( $("#send_now_msg").html() );
        }else{
			displayAlertMessage('Add / Update Your Physical Address.','','0',true,480,320,false,'');			
			$( "#message" ).load( base_url+"promotions/user_address" );
        }
      }
    });

  });
  $('#messageBox').find('.send_mail').live('click',function(){
    parent.submitFrm();
    $.modalBox.close();
  });

  $('#messageBox').find('.cancel_mail').live('click',function(){
    $.modalBox.close();
  });
  jQuery('.subscriptions_check').live('click',function(){

    var c_sender_name = $('#email_from').val() ;
    var c_sender_email =  $('#email_id').val() ;
    $('.campaign_sender_name').html(c_sender_name);
    $('.campaign_sender_email').html(c_sender_email);
    var c_subject = $('#email_subject').val();
    $('#campaign_subject').html(c_subject);

    var block_data;
    block_data=$('#form_campaign_send').serialize();
    jQuery.ajax({
      url: "<?php echo base_url(); ?>promotions/selected_subscribers",
      type:"POST",
      data:block_data,
      success: function(data){
        $('.number_of_contact').html(data);
      }
    });
  });
 
  function submitFrm(){
    $('save_email').val('1');
    $('#spin').show();
    document.form_campaign_send.submit();
  }
  function scheduleFrm(){
    $('save_email').val('1');
	$('#send_now').val('0');
	displayAlertMessage('Schedule Your Campaign To Send Later','','0',true,350,250,false,'');
	$( "#message" ).html( $(".schedule_delivery").html() );	
	$('#messageBox').find( "#scheduled_date" ).datepicker();
    //$('.schedule_delivery').toggle();
  }
  /*
    Function to send a test email
  */
  function send_test_email(){
	$('#spin').show();
	var is_ga = $('#is_ga_enabled').is(':checked');
	var is_ctrack = $('#is_clicktracking').is(':checked');
	is_ctrack = (is_ctrack)?'yes':'no';
	//is_ctrack = $('#is_clicktracking').length ? $('#is_clicktracking').is(':checked') : is_ctrack;
	var c_sender_name = $('#email_from').val() ;
    var c_subject = $('#email_subject').val();
	var c_sender_email =  $('#email_id').val() ;
	if(c_subject ==''){
		$('#spin').hide();
		displayAlertMessage('Alert!','<p>An "Email Subject" is required.</p>','0',true,350,150,false,'');		
		exit;
	}else if(c_sender_name ==''){
		$('#spin').hide();
		displayAlertMessage('Alert!','<p>A "From Name" is required.</p>','0',true,350,150,false,'');
		exit;
	}else if(c_sender_email ==''){
		$('#spin').hide();
		displayAlertMessage('Alert!','<p>A "From Email" is required.</p>','0',true,350,150,false,'');
		exit;
	}else{
		var c_sender_email_domain = c_sender_email.split('@')[1].toLowerCase();
		if(c_sender_email_domain.substring(0,3) === 'aol' || c_sender_email_domain.substring(0,3) === 'gmx' || c_sender_email_domain.substring(0,5) === 'yahoo'){
			$('#spin').hide();
			displayAlertMessage('Alert!','<p>Due to recent changes at  Yahoo, GMX and AOL, campaigns sent from a <b>yahoo</b> or <b>gmx</b> or <b>aol</b> email addresses will not be delivered. Please use a different "From Email" for your campaigns.</p>','0',true,450,250,false,'');
			exit();
		}
	}	
    var block_data="";
    
	block_data+="is_ga="+is_ga+"&is_ctrack="+is_ctrack+"&email_address="+escape($('#email_address').val())+"&email_subject="+encodeURIComponent($('#email_subject').val())+"&email_id="+escape($('#email_id').val())+"&email_from="+encodeURIComponent($('#email_from').val())+"&preheader="+encodeURIComponent($('#preheader').val());
	if($('#reply_to_email').val() != '')	{
		block_data +="&reply_to_email="+escape($('#reply_to_email').val());
	}
    jQuery.ajax({
      url: "<?php echo base_url() ?>campaign_email_test/index/<?php echo $campaign_data['campaign_id']; ?>",
      type:"POST",
      data:block_data,
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data) {
        $('#spin').hide();
		// $('#temp').html(data);return;
        var data_arr=data.split(":", 3);
        if(data_arr[0]=="error"){
          $('.email_msg').html('');
          $('.email_msg').html(data_arr[1]);
          $('.email_msg').addClass('info');
          $('.email_msg').fadeIn();
          if(data_arr[2]>25){
            if($('.test_email_count').length<=0){
              var html='<tr class="test_email_count" style="display:block;"><td colspan="2"><div class="email_msg info" style="margin-top:30px;">You have reached the maximum allowed tests for this campaign.</div></td></tr><tr class="test_email_count" style="display:block;"><td colspan="2" style="float:right;"><a style="margin:10px 10px 0 0px; padding-right:1px;" class="button-red fr subscr_list " title="Cancel" href="javascript:void(0); " onclick="javascript:$(\'.test_email_count\').hide();$(\'#link_send_test\').show();"><span>Cancel</span></a></td></tr>';
              $('#link_send_test').after(html);
              $('.email_address_tr').remove();
            }else{
              $('.email_address_tr').remove();
              $('.test_email_count').show();
              $('.email_msg').html('You have reached the maximum allowed tests for this campaign.');
              $('.email_msg').show();
            }
          }
        }else if(data_arr[0]=="Success"){
          $('#email_address').val('');
          $('.email_msg').html('A test email was sent');
          $('.email_msg').addClass('info');
          $('.email_msg').fadeIn();
          setTimeout( function(){$('.email_msg').fadeOut();} , 4000);
          setTimeout( function(){$('.email_address_tr').hide();} , 4000);
          setTimeout( function(){$('#link_send_test').show();} , 4000);
          if(data_arr[1]>25){
            $('.email_address_tr').remove();
          }
        }
      }
    });
  }
  jQuery("#save_exit").live('click',function(){
    $('#save_email').val('1');
    document.form_campaign_send.submit();
  });
  jQuery(".schedule_email").live('click',function(){
    send_now_email=false;
	
	$('#date_to_send').val($('#messageBox').find('#scheduled_date').val());
	$('#hour_to_send').val($('#messageBox').find('select[name=sch_hours]').val());
	$('#min_to_send').val($('#messageBox').find('select[name=sch_min]').val());
	$('#ampm_to_send').val($('#messageBox').find('select[name=sch_time]').val());
	  
	var c_sender_name = $('#email_from').val() ;
    var c_sender_email =  $('#email_id').val() ;
    $('.campaign_sender_name').html(c_sender_name);
    $('.campaign_sender_email').html(c_sender_email);

    var c_subject = $('#email_subject').val();
    $('.campaign_subject').html(c_subject);

	if($('#queued_campaign_count').val() > 4){
        displayAlertMessage('Alert!','<p>Already many campaigns are in queue. You can not send any more!</p>','0',true,300,150,false,'');					 
		exit;
    }
	if(c_subject ==''){
		displayAlertMessage('Alert!','<p>An "Email Subject" is required.</p>','0',true,300,150,false,'');
		exit;
	}else if(c_sender_name ==''){
		displayAlertMessage('Alert!','<p>A "From Name" is required.</p>','0',true,300,150,false,'');		
		exit;
	}else if(c_sender_email ==''){
		displayAlertMessage('Alert!','<p>A "From Email" is required.</p>','0',true,300,150,false,'');
		exit;
	}else{
		var c_sender_email_domain = c_sender_email.split('@')[1].toLowerCase();
		if(c_sender_email_domain.substring(0,3) === 'aol' || c_sender_email_domain.substring(0,3) === 'gmx' || c_sender_email_domain.substring(0,5) === 'yahoo'){
			displayAlertMessage('Alert!','<p>Due to recent changes at  Yahoo, GMX and AOL, campaigns sent from a <b>yahoo</b> or <b>gmx</b> or <b>aol</b> email addresses will not be delivered. Please use a different "From Email" for your campaigns.</p>','0',true,600,150,false,'');
			exit();
		}
	}

    var block_data;
    block_data=$('#form_campaign_send').serialize();
    jQuery.ajax({
      url: "<?php echo base_url(); ?>promotions/selected_subscribers",
      type:"POST",
      data:block_data,
      success: function(data){         		
        		$('.number_of_contact').html(data);
				$('.remaining_quota').html($('#quota_remaining').val());
				if($('#quota_remaining').val() < (1 * data)){
						displayAlertMessage('Alert!','','0',true,600,200,false,'');
						displayAlertMessage('Email Sending Quota Exceeded','','0',true,500,180,false,'');
						$( "#message" ).html( $("#quota_exceeded_msg").html() ); 				
						exit;
				}else{
					$('.number_of_contact').html(data);
					if(user_info){
						$('#save_email').val('0');
						displayAlertMessage('Campaign Overview','','0',true,400,220,false,'');
						$( "#message" ).html( $("#send_now_msg").html() ); 
					}else{
						$('#save_email').val('0');						
						displayAlertMessage('Add / Update Your Physical Address','','0',true,650,550,false,'');
						$( "#message" ).html( $("#user_account_option").html() );
					}
				}
			}
		});

	});

	

  /***********Function to add user info*******************/
function save_user_info(){
  var block_data;
  block_data='company='+encodeURIComponent($('#messageBox').find('#company_name').val())+'&address_line_1='+encodeURIComponent($('#messageBox').find('#address').val())+'&city='+encodeURIComponent($('#messageBox').find('#city').val())+'&state='+encodeURIComponent($('#messageBox').find('#state').val())+'&zipcode='+encodeURIComponent($('#messageBox').find('#zip').val())+'&country='+encodeURIComponent($('#messageBox').find('#country').val())+'&country_custom='+encodeURIComponent($('#messageBox').find('#country_custom').val());
  jQuery.ajax({
    url: "<?php echo base_url() ?>account/user_info",
    type:"POST",
    data:block_data,
    success: function(data) {
      var data_arr=data.split(':');
      if(data_arr[0]=="error"){
        $('#messageBox').find('.msg').html(data_arr[1]);
      }else{
        $('.company_name').html($('#messageBox').find("#company_name").val());
        $('.address').html($('#messageBox').find("#address").val());
        $('.city').html(" | "+$('#messageBox').find("#city").val());
        $('.state').html(", "+$('#messageBox').find("#state").val());
        $('.zip').html($('#messageBox').find("#zip").val());
        var country=$('#messageBox').find("#country :selected").text();
        if(country=="United States"){
          country="USA";
        }
        $('.country').html(" | "+country);
        user_info=true;        
		displayAlertMessage('Campaign Overview','','0',true,400,220,false,'');
		$( "#message" ).html( $("#send_now_msg").html() ); 
        
        
        /*
        *  Update company info on campaing footer
        */

        jQuery.ajax({
          url: "<?php echo base_url() ?>promotions/update_company_info_on_campaign/<?php echo $campaign_data['campaign_id']; ?>",
          type:"POST",
          data:block_data,
          success: function(data) {
			$.modalBox.close();
          }
        });
      }
    }
  });
}
$('#btn_add_other_eml').live('click', function(){
		displayAlertMessage('Add a New From Email','','0',true,500,150,false,'');
		$( "#message" ).html( $("#add_other_from_emails").html() ); 
	}
);
function save_another_eml(){
	var em = $('#messageBox').find('#another_emailid').val();
	var newEml = encodeURIComponent(em);
	$.modalBox.close();
	$('#spin').show();
	jQuery.ajax({
		url: "<?php echo base_url() ?>campaign_email_setting/add_another_emailid/",
		type:"POST",
		data:'newEml='+newEml,
		success: function(data) {
			if(data =='InvalidDomain'){
				displayAlertMessage('Alert!','','0',true,500,230,false,'');
				$( "#message" ).html( $("#InvalidDomain").html() ); 	 				
			}else if(data =='err'){				
				$('#verify_eml').html('<p>Invalid Email address.</p><div class = "message_button"><a href = "javascript:void(0);" class = "cancel_mail">OK. Close.</a></div>');
				displayAlertMessage('Alert!','','0',true,430,110,false,'');
				$( "#message" ).html( $("#verify_eml").html() ); 				
			}else if(data =='dup'){								
				$('#verify_eml').html('<p>You have already added this Email address.</p><div class = "message_button"><a href = "javascript:void(0);" class = "cancel_mail">OK. Close.</a></div>');
				displayAlertMessage('Alert!','','0',true,430,110,false,'');
				$( "#message" ).html( $("#verify_eml").html() ); 
				
			}else{
				$('#verify_eml').html('<p>A verification email was sent to '+em+' with a link to confirm the ownership of the email address.</p><div class = "message_button"><a href = "javascript:void(0);" class = "cancel_mail">OK. Thanks.</a></div>');
				displayAlertMessage('Confirm Your Email','','0',true,430,210,false,'');
				$( "#message" ).html( $("#verify_eml").html() ); 
			}
			$('#spin').hide();
		}
	});
	
}
function updateFromEmailDpd() {
$('#spin').show();
    jQuery.ajax({
		url: "<?php echo base_url() ?>campaign_email_setting/ajaxFromEmlArray/",
		type:"POST",
		success: function(data) {
			var arrData = data.split(',');
			$('#email_id option').remove();
			$.each(arrData, function (index, value) {
				$('#email_id').append($('<option>', { value: value, text : value }));
			});
		$('#spin').hide();
		}
	});

}

//setInterval(function(){ updateFromEmailDpd() }, 10000);

function openpinterest(){
	var s= encodeURIComponent($('#email_subject').val());
	var u = "http://pinterest.com/pin/create/button/?url=<?php echo urlencode(CAMPAIGN_DOMAIN.'c/'.$campaign_data['encrypted_cid']);?>&description="+s;

	var win=window.open(u, '_blank');
	win.focus();
}
function addToken(txtToAdd){
	var caretPos = document.getElementById("email_subject").selectionStart;    
	var textAreaTxt = (jQuery("#email_subject").val().toLowerCase()=="unnamed")? "" : jQuery("#email_subject").val();
	txtToAdd = (caretPos === 0)? txtToAdd + " " : " " + txtToAdd + " ";
    jQuery("#email_subject").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
}


</script>		
<?php
if($campaign_data['campaign_template_option'] == 3){
	$strEditorURL =  site_url('promotions/campaign_editor/'.$campaign_data['encrypted_cid']);	
}elseif($campaign_data['campaign_template_option'] == 5){
	$strEditorURL =  site_url('promotions/plain_text/'.$campaign_data['encrypted_cid']);
}elseif($campaign_data['campaign_template_option'] == 1){
	$strEditorURL =  site_url('promotions/url_import/'.$campaign_data['encrypted_cid']);
}elseif($campaign_data['campaign_template_option'] == 2){
	$strEditorURL =  site_url('promotions/zip_import/'.$campaign_data['encrypted_cid']);
}elseif($campaign_data['campaign_template_option'] == 4){
	$strEditorURL =  site_url('promotions/html_code/'.$campaign_data['encrypted_cid']);
}else{	
	$strEditorURL =  '';
}
?>	
			
			<!-- body - campaign DIY starts -->	
		
<div align = 'center' class = 'page_top_button_row'>		
	<input type = 'button' name = 'preview' value = '<< go back to preview' class = 'button blue large textCap' onclick="javascript:window.location.href='<?php echo site_url('preview/index/'.$campaign_data['encrypted_cid']);?>';" />
	<input type='button' name='edit' value='edit campaign' class='button blue large textCap' onclick="javascript:window.location.href='<?php echo $strEditorURL;?>'" />
	<input type = 'button' name = 'save_exit' id="save_exit" value = 'save campaign & exit >>' class = 'button blue large textCap' />
</div>
<div id="temp"></div>
<?php
echo form_open('campaign_email_setting/index/'.$campaign_data['encrypted_cid'], array('id' => 'form_campaign_send','name'=>'form_campaign_send','class'=>"form-website"));
?>
    <input type="hidden" value="0" name="send_now" id="send_now" />
    <input type="hidden" value="0" name="save_email" id="save_email" />
	<input type="hidden" value="0" name="date_to_send" id="date_to_send" />
    <input type="hidden" value="0" name="hour_to_send" id="hour_to_send" />
    <input type="hidden" value="0" name="min_to_send" id="min_to_send" />
    <input type="hidden" value="0" name="ampm_to_send" id="ampm_to_send" />
    <input type="hidden" value="<?php echo $quota_remaining;?>" name="quota_remaining" id="quota_remaining" />
    <input type="hidden" value="<?php echo $queued_campaign_count;?>" name="queued_campaign_count" id="queued_campaign_count" />    
			
			
			<fieldset class = 'DIY-campaign-options-testmail'>
				<legend>Email Preheader:</legend>
				<div>A preheader is the short summary text that follows the subject line when an email is viewed in the inbox. Many modern email clients helps the recepient get an idea of what the email contains.[Optional]</div>
				<div class = 'testmailarea' style="margin-bottom:10px;">
						<textarea name="preheader" id="preheader" placeholder = 'Example: Wishing you a safe and happy holiday season!'></textarea>
				</div>
				
			</fieldset>
			<fieldset class = 'DIY-campaign-options-sendmail'>
				<legend>Email Options:</legend>
				<div class = 'campaign_headers'>
					<!-- Personalization dropdown -->
					<div>
						Subject Personalization [Optional]
						<select name="personalization" id="personalization" onchange="javascript:addToken(this.value); this.value = '';">
						<option value="">Select Personalization</option>
							<option value="{name}">Name</option>
							<option value="{email}">Email</option>
							<option value="{first_name}">First name</option>
							<option value="{last_name}">Last name</option>
							<option value="{address}">Address</option>
							<option value="{city}">City</option>
							<option value="{state}">State</option>
							<option value="{zip}">Zip Code</option>
							<option value="{country}">Country</option>
							<option value="{company}">Company</option>
						</select>
					</div>
					
					Email Subject Line:<br />				
					<input type = 'text' name = 'email_subject' id = 'email_subject' placeholder = 'Example: Great Festive Offer Inside' value="<?php echo $campaign_data['camapign']['email_subject'];?>" maxlength=250 /><br />
					
					From Name:<br />
					<input type = 'text' name = 'email_from' id='email_from' placeholder = 'Example: My Online Shop' maxlength='250' value="<?php echo $campaign_data['camapign']['sender_name'] != "" ? $campaign_data['camapign']['sender_name'] : $campaign_data['email_from'] ; ?>" /><br />
					
					From Email:<br />
					<select name="email_id" id="email_id">
						<?php
							foreach($campaign_data['email_id'] as $fromEml){
								if($campaign_data['camapign']['sender_email'] == $fromEml)
								echo "<option value='$fromEml' selected>{$fromEml}</option>";
								elseif($campaign_data['last_campaign_from_email'] == $fromEml)
								echo "<option value='$fromEml' selected>{$fromEml}</option>";
								else
								echo "<option value='$fromEml'>{$fromEml}</option>";
							}
						?>
					</select><br />
					<?php if(591 != $this->session->userdata('member_id')){?>
					<div style="display:block;">
					<a href="javascript:void(0);" id="btn_add_other_eml" class="edit-interval" style="float:left;"><b>Add New From Email</b></a>
					<a href="javascript:void(0);" onclick="javascript:updateFromEmailDpd();"><img src="<?php echo $this->config->item('locker');?>images/boldinbox_sync.png" title='Refresh "From Email" list' alt="sync" style="float:right;" /></a>
					</div>
					<br /><br />
					<?php } ?>
					
					<?php if($campaign_data['reply_to_enabled']){ ?>
						Reply To Email:</strong>
						<select name="reply_to_email" id="reply_to_email">
						<?php
							foreach($campaign_data['email_id'] as $fromEml){
								if($campaign_data['camapign']['reply_to_email'] == $fromEml)
								echo "<option value='$fromEml' selected>{$fromEml}</option>";					
								else
								echo "<option value='$fromEml'>{$fromEml}</option>";
							}
						?>
					</select>	
					<?php }?>
					<?php if(!$campaign_data['is_ga_enabled']){?>
						<input type="checkbox" name="is_ga_enabled" id="is_ga_enabled" value="1" checked style="margin-right:12px;" />Track campaign using Google Analytics<br />
					<?php }?>
					<?php if(!$campaign_data['is_clicktracking']){?>
						<input type="checkbox" name="is_clicktracking" id="is_clicktracking" value="1" checked style="margin-right:12px" />Track clicks in the campaign<br />
					<?php }?>
				</div>
				<div class = 'campaign_contacts'  style = 'float:left;width:45%;border:solid 0px;margin-right:10px;font-size:13px;font-weight:700;'>
					Select Contact Lists:<br />
					<?php
					  $i=0;
					  echo '<div style="height:200px; padding:10px; border:1px solid #CCC;  font-weight:500;overflow:auto;"><table style="border:none">';
					  foreach($subscription_data['subscriptions'] as $subscription){
						if(isset($campaign_data['camapign']['subscription_list']) && in_array($subscription['subscription_id'],$campaign_data['camapign']['subscription_list']))
						  $checked=true;
						else
						  $checked=false;
						echo '<tr style="border:1px solid #000"><td style="padding:3px 3px;">';
						echo form_checkbox(array('name'=>'subscriptions[]','id'=>'subscriptions','class'=>'subscriptions_check','value'=>$subscription['subscription_id'],'checked'=>$checked ,'style'=>'display:inline;margin-right:15px;')).'</td><td >'.ucwords(substr($subscription['subscription_title'],0,25))." (".$subscription['number_of_contacts'].")";
						echo '</td></tr>';
						$i++;
					  }
					  if($i<=0) {
						echo "Please Create Subscriptions";
					  }
					  echo '</table></div>';
					?>
				</div>
				<div class = 'clear0'></div>
				<div class = 'advance_options'>
					<div class="email_msg info"></div>
					<div class = 'advance_options_1'>
						<b>Would you like to send a Test Email?</b>
						<?php if($campaign_data['camapign']['test_email_count']>=25){ ?>
						<div class="email_msg info">You have reached the maximum allowed tests for this campaign.</div>
						<?php }else{ ?>
						<div>
							<textarea name="email_address" id="email_address" placeholder = 'Enter email addresses separted by comma. Maximum 15 allowed in one day, 3 emails at a time.'></textarea>
						</div>
						<div>
							<input type = 'button' name = 'send_test_mail' value = 'SEND TEST MAIL' class = 'button grey2 small' onclick="send_test_email();" />
						</div>
						<?php } ?>
					</div>
					<div class = 'advance_options_2'>
						<b>Share on Social Media:</b>
						<div style = 'margin-top:5px;'>
							<a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign_data['encrypted_cid'];?>&t=<?php echo $subject?>"  title="Click to share this campaign on Facebook" target="_blank"><img src="<?php echo $this->config->item('locker');?>images/icons/share-on-facebook.png" alt="" width = '100'></a>
							<br /><a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$campaign_data['encrypted_cid'];?> via BoldInbox" title="Click to share this post on Twitter" target="_blank"><img src="<?php echo $this->config->item('locker');?>images/icons/share-on-twitter.png"  width = '100' alt=""></a>
						</div>
					</div>
				</div>
				
			</fieldset>

			<div align = 'center' class = 'page_top_button_row'>
				
				<div align = 'center'>					
					<input type = 'button' name = 'save_exit' value = 'send campaign now' class = 'button blue large textCap send_now' />
					<input type = 'button' name = 'save_exit' value = 'send campaign later' class = 'button blue large textCap' onclick="scheduleFrm();" />
					
				</div>
			</div>
			
<?php
echo form_hidden('subscription_ids_str',$campaign_data['subscription_ids_str']);
echo form_hidden('action','send_campaign');
echo form_close();
?>
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
          <?php echo '<input value="'.$campaign_data['camapign']['delivery_date'].'" id="scheduled_date" name="scheduled_date" type="text" size="40" style="width:160px; height:22px;"  readonly>'; ?>         
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
              echo form_button(array('name' => 'campaign_submit', 'id' => 'btnEdit','class'=>'inline-block schedule_email form_submit_btn_class','content' => 'Schedule Now'), 'Schedule');
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