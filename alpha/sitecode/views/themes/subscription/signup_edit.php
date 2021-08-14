<script type="text/javascript">
var base_url="<?php echo base_url();?>";
var copy_link="<?php echo CAMPAIGN_DOMAIN.'s/'; ?>";
var sid = <?php echo ($signup_from['form'][0]['id'] > 0)?$signup_from['form'][0]['id'] : 0; ?>;
</script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/signup.js?2323ss"></script>
<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner">
					<?php echo form_open('subscription/signup_edit/'.$signup_from['form'][0][id].'#showCodePopup', array('id' => 'frmListing','name'=>'frmListing', 'class'=>"form-website")); ?>
					<div class="row">
						<!-- REPEATD -->
						<?php  if(validation_errors()){ ?>
							<div class="col-lg-12">
							  <div class = 'alert alert-danger'><?PHP echo validation_errors(); ?></div>	
							</div>							  
						<?php } ?>	
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class = 'row'>
									<div class="col-lg-6">
										<div class = 'formLabel'>Form Name / Title:</div>
										<?php echo form_input(array('name'=>'form_name','id'=>'form_name','maxlength'=>25,'class'=>'form-control','required'=>'required','value'=>html_entity_decode($signup_from['form'][0]['form_name']))) ; ?>
										
										<div class = 'formLabel'>
											<div class = 'row'>
												<div class="col-lg-8 col-md-8 col-sm-6">Select Contacts List:</div>
												<div class="col-lg-4 col-md-4 col-sm-6 cl_imp_cnt_sample">
													<a href="javascript:void(0);" class = 'font6 font600 floatRight'>+ Add New List</a>
												</div> 
											</div>
										</div>
										<div class = 'contacts_import_list'>
											<input type="hidden" name="selectedSubscriptionValues" id="selectedSubscriptionValues" value="<?php echo $signup_from['form'][0]['subscription_id']; ?>"/>
											<?php
												
												  $i=0;
												  echo '<div class="contacts-list">';												  
											      foreach($signup_from['subscriptions'] as $subscription){
												  if(in_array($subscription['subscription_id'],$signup_from['subscription_id_arr'])){
													$checked=true;
												  }else{
													$checked=false;
												  }
													  
													echo '<div>';
													echo form_checkbox(array('name'=>'subscription_id[]','id'=>'subscription_id','class'=>'form-control','value'=>$subscription['subscription_id'],'checked'=>$checked )).' '.ucwords(substr($subscription['subscription_title'],0,25))." (".$subscription['number_of_contacts'].")";
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
										
										<div class = 'formLabel'>
											<div class = 'row'>
												<div class="col-lg-8 col-md-8 col-sm-6">Select Form Fields To Be Added:</div>
												<div class="col-lg-4 col-md-4 col-sm-6 cl_imp_cnt_sample">
													<a href="javascript:void(0);" class = 'font6 font600 floatRight'>Custom Styling</a>
												</div> 
											</div>
										</div>
										
										<div class = 'field-list-grid'>
											<div class = 'row-head'>
												<div class = 'row'>
													<div class = 'col-6'>Field Label</div>
													<div class = 'col-3'>Show</div>
													<div class = 'col-3'>Required</div>
												</div>
											</div>
											<div class = 'field-list-grids'>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Name</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>First Name</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Last Name</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Company</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Address</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>City</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>State</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Zip Code</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
												<div class = 'row row-striped'>
													<div class = 'col-6 contacts-cols'>Country</div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
													<div class = 'col-3 contacts-cols'><input type = 'checkbox' class = 'form-control' /></div>
												</div>
											</div>
											<div class = 'row-footer'>	
												<div class="row">						
													<div class="col-12">&nbsp;</div>
												</div>
											</div>
										</div>
										
									</div>
								
									<div class="col-lg-6" align = 'center'>
										<div class = 'formLabel'>
											<div class = 'row'>
												<div class="col-lg-8 col-md-8 col-sm-6" align = 'left'>Sign-Up Form Preview:</div>
												<div class="col-lg-4 col-md-4 col-sm-6 cl_imp_cnt_sample">
													<a href="javascript:void(0);" class = 'font6 font600 floatRight'>Advance Setting</a>
												</div> 
											</div>
										</div>
										<div>
											<table>
		<tr>
			<td>
				
				<div class="signupform_code_td expanded" style="background-color:<?php echo $signup_from['form'][0]['form_background_color'];?>; border: 1px solid #ddd;min-height: 440px;background-image:url(<?php echo $signup_from['form'][0]['background_background_image']; ?>);background-repeat:<?php echo ($signup_from['form'][0]['background_background_tile_image'])?'repeat':'no-repeat';?>">
              <!-- Form preview Starts -->
              <div class="signupform">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form_preview formTable" >
				<tbody  id="form_preview">
                  <tr>
          <td class="form_title tdheader"  style="font-weight:bold;font-size:27px;text-align:center;;"><div class="header-txt" style="padding:20px 0 15px;background-color:<?php echo $signup_from['form'][0]['header_background_color'];?>;color:<?php echo $signup_from['form'][0]['header_text_color'];?>"><?php echo html_entity_decode($signup_from['form'][0]['form_title']); ?></div>
          <?php
          if($signup_from['form'][0]['header_background_image'] !=''){ ?>
                    <img src="<?php echo $signup_from['form'][0]['header_background_image']; ?>" id="header-img" style="width:100%; height:auto;margin-top:-71px;padding-bottom:15px;" />
          <?php }?>

          </td>
          <!--h2 style="background-color:<?php echo $signup_from['form'][0]['header_background_color'];?>;color:<?php echo $signup_from['form'][0]['header_text_color'];?>;background-image:url(<?php echo $signup_from['form'][0]['header_background_image']; ?>);background-repeat:<?php echo ($signup_from['form'][0]['header_background_tile_image'])?'repeat':'no-repeat';?>"></h2-->
                  </tr>
                  <?php
$elementCounter = 0;
          // $frmCode= "<tr id='EL-email'><td><label><span class='form-label update-language-email'>".$this->lang->line('email')." </span><span>*</span></label><br/><input type='text' id='signup_email' name='signup[email]'   maxlength='50' size='40'>$validation_error</td></tr>\n";

          $arrSignupFormFieldLabels = array('email'=>'Email', 'name'=>'Name', 'first_name'=>'First Name', 'last_name'=>'Last Name', 'company'=>'Company', 'address'=>'Address', 'city'=>'City', 'state'=>'State', 'zip_code'=>'Zip Code', 'country'=>'Country');



        if (!is_null($signup_from['form'][0]['fld_sequence']) && trim($signup_from['form'][0]['fld_sequence']) != '' && $signup_from['form'][0]['fld_sequence'] != 'b:0;') {
            $arrSignupFormFields = unserialize($signup_from['form'][0]['fld_sequence']);

      if(count($arrSignupFormFields)  > 0){
            $arrFldName = $arrSignupFormFields['fld_name'];
            $arrFldType = $arrSignupFormFields['fld_type'];
            $arrFldRequired = $arrSignupFormFields['fld_required'];
            $arrFldOptions = $arrSignupFormFields['fld_options'];


            $i = 1;

                foreach($arrFldName as $fld => $fldVal){
$elementCounter++;
                        if(array_key_exists($fldVal,$arrSignupFormFieldLabels)){
                          $frmCode.= "<tr class='field_{$fldVal}' id='EL-{$fldVal}'><td><label class='form-title-label'><span class='form-label update-language-{$fldVal}'>".$this->lang->line($fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash field_add_toggle' id='$fldVal'></i>";
              $frmCode.= "</label><br/><input type='text' id='signup_{$fldVal}' name='signup[$fldVal]'   maxlength='50' size='40' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_name' name='fld_sequence[fld_name][]' value='$fldVal' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_type' name='fld_sequence[fld_type][]' value='".$arrFldType[$fld]."' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_required' name='fld_sequence[fld_required][]' value='".$arrFldRequired[$fld]."' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_options' name='fld_sequence[fld_options][]' value='".$arrFldOptions[$fld]."' />";
              $frmCode.= "</td></tr>\n";
                        }else{
              if($arrFldType[$fld] =="text"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/><input type='text' id='signup_{$fldVal}' name='signup[$i]'   maxlength='50' size='40' />";
              }elseif($arrFldType[$fld] =="textarea"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/><textarea id='signup_{$fldVal}' name='signup[$i]'></textarea>";
              }elseif($arrFldType[$fld] =="dropdown"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/>";
              $frmCode.= "<div class='input-option-container'>";
              $frmCode.= "<select id='signup_{$fldVal}' name='signup[$i]'><option value=''>--</option>";
              if(trim($arrFldOptions[$fld]) != '')$arrThisFldOptions = array_filter(explode(',',$arrFldOptions[$fld]));
              if(is_array($arrThisFldOptions) && count($arrThisFldOptions) > 0){
                foreach($arrThisFldOptions as $thisOpt)
                $frmCode.= "<option value='$thisOpt'>$thisOpt</option>";
              }
              $frmCode.= "</select>";
              $frmCode.= "</div>";
              }elseif($arrFldType[$fld] =="checkbox"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/>";
              $frmCode.= "<div class='input-option-container'>";
              if(trim($arrFldOptions[$fld]) != '')$arrThisFldOptions = array_filter(explode(',',$arrFldOptions[$fld]));
              for($j=0;$j < count($arrThisFldOptions);$j++){
                $frmCode.= "<div class='input-option-fields'>";
                $frmCode.= "<input type='checkbox' name='signup[$i]' id='signup_{$fldVal}{$i}' value='".$arrThisFldOptions[$j]."' /> ";
                $frmCode.= "<label for='signup_{$fldVal}{$i}'>".$arrThisFldOptions[$j]."</label> ";
                $frmCode.= "</div>";
              }
              $frmCode.= "</div>";
              }elseif($arrFldType[$fld] =="radio"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/>";
              $frmCode.= "<div class='input-option-container'>";
              if(trim($arrFldOptions[$fld]) != '')$arrThisFldOptions = array_filter(explode(',',$arrFldOptions[$fld]));
              for($k=0;$k < count($arrThisFldOptions);$k++){
                $frmCode.= "<div class='input-option-fields'>";
                $frmCode.= "<input type='radio' name='signup[$i]' id='signup_{$fldVal}{$i}' value='".$arrThisFldOptions[$k]."' /> ";
                $frmCode.= "<label for='signup_{$fldVal}{$i}'>".$arrThisFldOptions[$k]."</label> ";
                $frmCode.= "</div>";
              }
              $frmCode.= "</div>";
              }elseif($arrFldType[$fld] =="date_dropdown"){
              $frmCode.= "<tr class='field_{$fldVal} custom{$i}_fld'  name='custom{$i}'><td><label class='form-title-label'><span class='form-label'>".str_replace('_',' ',$fldVal)."</span>";
              $frmCode.= ($arrFldRequired[$fld] =="Y")?"<span>*</span>":'';
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash delete_custom'></i>";
              $frmCode.= "</label><br/>";
              $frmCode.= "<div class='input-option-container'>";
              $frmCode.= "<div class='input-option-field'>";
                $frmCode.= "<select class='input-option-date'><option default>Month</option>";
                for($i=1;$i < 13;$i++){
                $frmCode.= "<option value='$i'>$i</option>";
                }
                $frmCode.= "</select>";

                $frmCode.= "<select class='input-option-date'><option default>Day</option>";
                for($i=1;$i < 32;$i++){
                $frmCode.= "<option value='$i'>$i</option>";
                }
                $frmCode.= "</select>";

                $frmCode.= "<select class='input-option-date'><option default>Year</option>";
                for($i=1900;$i < date('Y')+20;$i++){
                $frmCode.= "<option value='$i'>$i</option>";
                }
                $frmCode.= "</select>";

              $frmCode.= "</div>";
              $frmCode.= "</div>";
              }
              $frmCode.= "<input type='hidden' class='fld_sequence_name' name='fld_sequence[fld_name][]' value='$fldVal' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_type' name='fld_sequence[fld_type][]' value='".$arrFldType[$fld]."' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_required' name='fld_sequence[fld_required][]' value='".$arrFldRequired[$fld]."' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_options' name='fld_sequence[fld_options][]' value='".$arrFldOptions[$fld]."' />";
              $frmCode.= "</td></tr>\n";

              $i++;
                    }

                }
      }// Check array count
        }else{
			 $frmCode = "<tr class='field_email' id='EL-email'><td><label class='form-title-label'><span class='form-label update-language-email'>".$this->lang->line('email')."</span>";
              $frmCode.= "<span>*</span>";
			  $frmCode.="<i class='icon-move'></i><i class='icon-trash'></i>";
              $frmCode.= "</label><br/><input type='text' id='signup_email' name='signup[email]'   maxlength='50' size='40' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_name' name='fld_sequence[fld_name][]' value='email' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_type' name='fld_sequence[fld_type][]' value='text' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_required' name='fld_sequence[fld_required][]' value='Y' />";
              $frmCode.= "<input type='hidden' class='fld_sequence_options' name='fld_sequence[fld_options][]' value='' />";
              $frmCode.= "</td></tr>\n";

		}
                    echo $frmCode;
                  ?>
                  <tr class="subscribe_to_list">
                    <td><?php echo form_submit(array('name' => 'listing_submit', 'id' => 'btnSubmitForm','content' => 'Submit','onclick'=>'return false;','class'=>'submit_button'), html_entity_decode($signup_from['form'][0]['form_button_text'])); ?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
				
				
				
				
			</td>
			
		</tr>
</table>
										</div>
										
										<div class = 'clear10'></div>
										<div class = 'clear10'></div>
										<div class = 'clear10'></div>
										<button name = 'listing_submit' id = 'btnSubmit' onclick = "javascript:submit_form(true);" class = 'blue rectangle'>Save & Share</button>
										
										
									</div>								
								</div>
							</div>	
						</div>			
						<!-- REPEATD -->
						
						
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>


  <div id="copy-code" style="display:none">
    <div style="width: 780px; margin: 0; height: 520px;">     
      <span class="subtitle">(Copy &amp; paste in an email, on your website or blog, Facebook, Twitter or any other social network.)</span>
      <input id="copy_link" value="<?php echo CAMPAIGN_DOMAIN.'s/'.$this->is_authorized->encryptor('encrypt',$signup_from['form'][0]['id']); ?>" type="text" onclick="this.setSelectionRange(0, this.value.length)" class="clean" />
      <h6 style="margin-top: 5px">HTML Code</h6>
      <textarea style="height: 392px; width: 550px;margin: 4px 13px 13px; resize: none;" onclick="this.setSelectionRange(0, this.value.length)" id="showSignupCode"></textarea>
    </div>
  </div>   
<!-- START: Add Other From Emails -->
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
        <p>A verification email was sent. Check your email and verify to be able to see it as an option.</p>

</div>
<!-- Header BG  popup box -->
<div id="hbg_dialog" style="height:200px; width:550px;display:none;">
  <div class="hbg_dialog">
    <form action="#" method="post">
    <h5>Upload Header Background</h5>
    <input name="hbg_file1" id="hbg_file1" type="file">
    </form>
  </div>
</div>
<div id="bbg_dialog" style="height:200px; width:550px;display:none;">
  <div class="bbg_dialog">
    <form action="#" method="post">
    <h5>Upload Background Image</h5>
    <input name="bbg_file1" id="bbg_file1" type="file">
    </form>
  </div>
</div>
<!-- END: Add Other From Emails -->