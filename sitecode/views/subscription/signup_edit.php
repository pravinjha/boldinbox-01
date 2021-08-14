<link href="<?php echo $this->config->item('locker');?>css/colorpicker.css?v=6-20-13" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/colorpicker.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-ui-1.8.13.custom.min.js?v=6-20-13"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('locker');?>css/blitzer/jquery-ui-1.8.14.custom.css?v=6-20-13" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('locker');?>css/jquery.multiselect.css?v=6-20-13" />
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery.multiselect.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery.upload-1.0.2.js?v=6-20-13"></script>
<?php echo link_tag('locker/css/signup_form.css?v=6-20-13'); ?>
<style type="text/css">
.ui-multiselect{margin-top:5px;border-radius:0;height:32px;}
.ui-multiselect,.ui-multiselect-menu{width:350px !important;}

.save_get_code {
background: #EFEFEF;
color: #0000FF;
text-decoration: none;

font-size: 16px;
text-align: center;
border: solid 0px;
padding: 4px 8px;
box-shadow: 2px 2px 1px #ccc;
display: inline-block;
width: 140px;
margin-left:0px;
margin-top:5px;
margin-bottom:5px;
font-weight:bold;}
.signup-tbl-field-list{width:700px !important;}
.add-sign-up-field1{padding: 7px 4px 7px 11px;
background: #fcfcfc;
margin-bottom: 1px;
position: relative;
font-size: 14px;
color: #333;}
</style>
<script type="text/javascript">
var base_url="<?php echo base_url();?>";
var copy_link="<?php echo CAMPAIGN_DOMAIN.'s/'; ?>";
var sid = <?php echo ($signup_from['form'][0]['id'] > 0)?$signup_from['form'][0]['id'] : 0; ?>;

</script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/signup.js?2323ss"></script>
<?php echo form_open('subscription/signup_edit/'.$signup_from['form'][0][id].'#showCodePopup', array('id' => 'frmListing','name'=>'frmListing', 'class'=>"form-website")); ?>
<?php if(validation_errors()){
        echo '<div class="info">'.validation_errors().'</div>';
      } ?>

<table width="100%" cellspacing="0" cellpadding="4" class="user_account" border="0">
<tr><td  valign="top">
<table>
<tr><td  valign="top">
<b>Form Name: </b> <br/>
	<?php echo form_input(array('name'=>'form_name','id'=>'form_name','maxlength'=>40,'size'=>42,'style'=>'width:316px;','value'=>html_entity_decode($signup_from['form'][0]['form_name']))) ; ?>
	</td>
	<td style="width:10px;">&nbsp;</td>
	<td><b>Select a List:</b> <br/>
	<input type="hidden" name="selectedSubscriptionValues" id="selectedSubscriptionValues" value="<?php echo $signup_from['form'][0]['subscription_id']; ?>"/>
              <select name="subscription_id" multiple>
              <?php foreach($signup_from['subscriptions'] as $subscription){ ?>
              <?php
                  if(in_array($subscription['subscription_id'],$signup_from['subscription_id_arr'])){
                    $select="selected";
                  }else{
                    $select="";
                  }
                ?>
              <option value="<?php echo $subscription['subscription_id'];?>" <?php echo $select; ?>><?php echo substr($subscription['subscription_title'],0,25); ?></option>
              <?php } ?>
              </select>
              <div class = 'clear5'></div>
				<a href="javascript:void(0);" onclick="javascript:$('.add_subscription').toggle();" class="btn cancel inline-block" style="padding: 3px 2px 4px;position: relative;bottom: 1px;font-size: 14px;font-weight:bold;">
                Create New List
              </a>
		<div class="add_subscription" style="display:none;">
                <?php echo form_input(array('name'=>'subscription_list_title','id'=>'subscription_list_title','maxlength'=>45,'value'=>'','style'=>'width:220px;margin-right:5px','placeholder'=>'List name...')) ; ?>
                <a href="javascript:void(0);"  class="btn add inline-block" id="add_subscription_list" style="padding: 3px 8px 4px;position: relative;bottom: 3px;font-size: 14px; font-weight: 700;">
                  + Add List 
                </a>
        </div>	  
	</td>
</tr>
</table>
</td>
</tr>


<tr><td valign="top">Select Fields:</br>
<table class="signup-tbl-field-list">


      <?php
        $arrSignupFormFieldLabels = array('email'=>'Email', 'name'=>'Name', 'first_name'=>'First Name', 'last_name'=>'Last Name', 'company'=>'Company', 'address'=>'Address', 'city'=>'City', 'state'=>'State', 'zip_code'=>'Zip Code', 'country'=>'Country');
        $arrFldName   = array();
        $arrFldType   = array();
        $arrFldRequired = array();
        $arrFldOptions  = array();

        if (!is_null($signup_from['form'][0]['fld_sequence'])) {
          $arrSignupFormFields = unserialize($signup_from['form'][0]['fld_sequence']);
      		if($arrSignupFormFields !== false){
			  $arrFldName = $arrSignupFormFields['fld_name'];
			  $arrFldType = $arrSignupFormFields['fld_type'];
			  $arrFldRequired = $arrSignupFormFields['fld_required'];
			  $arrFldOptions = $arrSignupFormFields['fld_options'];
      		}
        }
        $thisFldRecord = "";
        $headerHead = "<td>Fields</td>";
        $requiredHead = "<td>Required</td>";
        $showHead = "<td>Show</td>";
        
        foreach($arrSignupFormFieldLabels as $fldKey => $fldValue){
        	// Headers
        	if($fldKey != 'email'){
            $headerHead .= "<td class='add-sign-up-field1' style='width:10%;'>"   ;
            $headerHead .= "<span id='{$fldKey}'>$fldValue</span></td>";  
            }
            // Required checkboxes
            if($fldKey != 'email'){
				$thisKey = array_search ($fldKey,$arrFldName);
				$thisRequired = ($thisKey !== FALSE)?$arrFldRequired[$thisKey] : 'N';
				if($thisRequired == 'Y') {
				  $requiredHead .= "<td class='add-sign-up-field1' align='center' style='width:10%;'><input type='checkbox' class='field_required_toggle' name='fields[field_{$fldKey}_required]' checked value='1' id='field_{$fldKey}_required' data-field='{$fldKey}' data-language='".$this->lang->line($fldKey)."' /></td>";
				} else {
				  $isDisabled = ($thisKey !== FALSE)? '' : 'disabled' ;
				  $requiredHead .= "<td align='center' class='add-sign-up-field1' style='width:10%;'><input type='checkbox' class='field_required_toggle $isDisabled' name='fields[field_{$fldKey}_required]' value='0' id='field_{$fldKey}_required'  data-field='{$fldKey}' data-language='".$this->lang->line($fldKey)."'  /></td>";              
				}
            }
            // Show checkboxes
            if($fldKey != 'email'){
				if($thisKey !== FALSE){
						$isChecked ='checked'; $val=1;
					}else{
						$isChecked =''; $val=0;
					}           
            	$showHead .= "<td align='center' class='add-sign-up-field1' style='width:10%;'><input type='checkbox' value='$val' class='field_add_toggle' name='fields[is_{$fldKey}]' id='field_{$fldKey}'  data-field='{$fldKey}' data-language='".$this->lang->line($fldKey)."' $isChecked  /></td>";
            }
        }
         $thisFldRecord = "<tr>".$headerHead."</tr><tr>".$requiredHead."</tr><tr>".$showHead."</tr>";
         
         echo $thisFldRecord;
         
      
      
      /*
      // CUSTOME FIELDS PRE-POPULATED FROM DB

                $i = 1;
echo "<tr><td> Custom Field:</td>";
                foreach ($arrFldName as $k =>$v) {
         if(!array_key_exists($v,$arrSignupFormFieldLabels)){
        ?>
          <td id="custom<?php echo $i; ?>" class="add-sign-up-field custom active" name="field_custom<?php echo $i; ?>">
			<input type="hidden" name="custom_field[]" value="0" id="field_custom<?php echo $i; ?>" />
			<input type="hidden" name="custom_field_required[]" value="0" id="field_custom<?php echo $i; ?>_required" />
			<input type="hidden" name="custom_field_type[]" id="field_custom<?php echo $i; ?>_type" value="<?php echo $arrFldType[$k];?>" />
			<input type="hidden"  value="0" id="add_custom<?php echo $i; ?>" />
			<strong class="add-sign-up-field-custom-title"><?php if($arrFldType[$k] == 'date_dropdown')echo 'Date';elseif($arrFldType[$k] == 'textarea')echo 'Paragraph';else echo ucfirst($arrFldType[$k]); ?> Title:</strong>
			</td>
			<td>
            <input class="sign-up-field-custom custom_text" placeholder="Enter custom field" type="text" name="custom_field_name[]" id="custom<?php echo $i; ?>_field_name" onkeyup="display_form_field('custom<?php echo $i; ?>','Custom',<?php echo $i; ?>,'<?php echo $arrFldType[$k]; ?>')" value="<?php echo str_replace('_',' ',$v);?>"  />

      <?php if ($arrFldType[$k] != "text" && $arrFldType[$k] != "date_dropdown" && $arrFldType[$k] !== "textarea") {?>
          <strong class="add-sign-up-field-custom-title options">Options: <span class="add-sign-up-field-custom-subtitle">(comma separated)</span></strong>
          <input class="sign-up-field-custom custom_text" type="text" name="custom_field_options[]" placeholder="Enter options ex. Basketball, Baseball, Soccer, Tennis" id="<?php echo 'custom'.$i.'_field_name_options';?>" value="<?php echo $arrFldOptions[$k];?>" onkeyup="display_form_field('<?php echo 'custom'.$i;?>','<?php echo 'Custom';?>',<?php echo $i;?>,'<?php echo $arrFldType[$k];?>');"/>
      <?php } ?>
			</td><td>
                    <i class="icon icon-trash delete_custom"></i>
                    <input type="checkbox" class="field_required_toggle required custom" <?php if ($arrFldRequired[$k] == "Y")echo "checked='checked'";?> />
                  </td>
                <?php
        $i++;
        }
        }
        echo "</tr>";
        */
      ?>
      <!--
		<tr><td colspan="3"><select id="add_custom_fld" name="add_custom_fld">
                <option value="0">Select Input Type</option>
                <option value="text">Text</option>
                <option value="checkbox">Checkbox</option>
                <option value="dropdown">Drop-down</option>
                <option value="radio">Radio</option>
                <option value="date_dropdown">Date</option>
                <option value="textarea">Paragraph</option>
              </select></td></tr>
	 -->
	 
	 
	 
       </table>
	   

 </td>
 </tr>

 <tr><td height = '5'></td></tr>
 <tr>
<td>
 
 <table>
		<tr>
			<td>
				Signup Form Preview:<br />
				
				<div class="signupform_code_td expanded" style="background-color:<?php echo $signup_from['form'][0]['form_background_color'];?>; border: 1px solid #ddd;min-height: 496px;background-image:url(<?php echo $signup_from['form'][0]['background_background_image']; ?>);background-repeat:<?php echo ($signup_from['form'][0]['background_background_tile_image'])?'repeat':'no-repeat';?>">
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
			<td valign = 'top' width = '10'>
				
			</td>
			<td valign = 'top' style="padding-top:0px;">
				Custom Styling Options:<br />
 
 <div style='background:#F0F1F3;width:250px;padding:10px;top:10px;border-radius:0px; border:1px solid #ddd;'>
				<div class="signup-tbl-inline form-info content-random" style = 'background:#FFFFFF;width:240px;border-radius:0px;padding:5px;'>
 

            <label class="label">Form Title</label>
            <?php echo form_input(array('name'=>'form_title','id'=>'form_title','maxlength'=>50,'size'=>40 ,'style'=>'width:200px;','value'=> html_entity_decode($signup_from['form'][0]['form_title']),'onkeyup'=>"changeTitle('form_title')")) ; ?>

            
            <div class="clear10"></div>

            <label class="label">Button Text</label>
            <?php echo form_input(array('name'=>'form_button_text','id'=>'form_button_text','maxlength'=>25,'size'=>40 ,'style'=>'width:200px;','value'=> html_entity_decode($signup_from['form'][0]['form_button_text']),'onkeyup'=>"changeButtonText('form_button_text')"))?>

            <div class="clear10"></div>
            
            <label class="label">Header Text Color</label>
            <?php echo form_input(array('name'=>'header_text_color','id'=>'header_text_color','size'=>5 ,'value'=>$signup_from['form'][0]['header_text_color'],'style'=>'color:'.$signup_from['form'][0]['header_text_color'].';background-color:'.$signup_from['form'][0]['header_text_color'].';width:200px;','size'=>'16')); ?>

			 <div class="clear10"></div>

            <label class="label">Header Background</label>
            <div class="signup-tbl-background-image">
              <?php echo form_input(array('name'=>'header_background_color','id'=>'header_background_color', 'class'=>'background-color','size'=>5 ,'value'=>$signup_from['form'][0]['header_background_color'],'style'=>'color:'.$signup_from['form'][0]['header_background_color'].';background-color:'.$signup_from['form'][0]['header_background_color'].';','size'=>'16')); ?>
              <?php echo form_input(array('name'=>'hbg_file','id'=>'hbg_file','type'=>'file','class'=>'background-image','value'=>'Upload Image')) ; ?>
              <a class="btn cancel" href="javascript:void(0);">Select Image</a>
              <i class="icon-trash btn add remove-header-background-image"></i>
              <div class="clear-both"></div>
              <?php echo form_input(array('name'=>'add_hbg','id'=>'add_hbg', 'type'=>'button', 'value'=>'Upload Now', 'class'=>'btn confirm upload-button loading')) ; ?>
              <?php echo form_input(array('name'=>'header_background_image','id'=>'header_background_image', 'type'=>'hidden', 'value'=>$signup_from['form'][0]['header_background_image'])) ; ?>
            </div>
            
            <div class="clear10"></div>

            <label class="label">Background</label>
            <div class="signup-tbl-background-image">
              <?php echo form_input(array('name'=>'form_background_color','id'=>'form_background_color', 'class'=>'background-color','size'=>5 ,'value'=>$signup_from['form'][0]['form_background_color'],'style'=>'color:'.$signup_from['form'][0]['form_background_color'].';background-color:'.$signup_from['form'][0]['form_background_color'].';','size'=>'16')); ?>
              <?php echo form_input(array('name'=>'bbg_file','id'=>'bbg_file','type'=>'file','class'=>'background-image','value'=>'Upload Image')) ; ?>
              <a class="btn cancel" href="javascript:void(0);">Select Image</a>
              <i class="icon-trash btn add remove-background-image"></i>
              <div class="clear-both"></div>
              <?php echo form_input(array('name'=>'add_bbg','id'=>'add_bbg', 'type'=>'button', 'value'=>'Upload Now', 'class'=>'btn confirm upload-button loading')) ; ?>
              <?php echo form_input(array('name'=>'background_background_image','id'=>'background_background_image', 'type'=>'hidden', 'value'=>$signup_from['form'][0]['background_background_image'])) ; ?>
            </div>
            <div class="clear"></div>
            

          </div>

 </div>

 <!-- Advanced Button:Starts -->
  <br/>
    
<a href="javascript:void(0);" onclick="javascript:showAdvancedPopup(true);" class="btn cancel inline-block" style="padding: 3px 2px 4px; font-size: 14px;font-weight:bold;"> Advance Settings</a>

        <?php
          echo form_hidden('action','save');
          echo "<input type='hidden' name='signup_form_id' id='signup_form_id' value=''>";
        ?>

        <div class="clear"></div>

        <script type="text/html" id="advanced-signup-tbl" style="display:none;"></script>

      	 
 
 
  <!-- Advanced Button:Ends -->
 <br/>
 <?php echo form_button(array('name' => 'listing_submit', 'id' => 'btnSubmit','class'=>'btn add save_get_code','content' => '<i class="icon-save" style="position: relative; bottom: 2px"></i>Save & Share','onclick'=>'submit_form(true)'), 'Save and Get Code'); ?>
 			</td>	
		</tr>
</table>
 
 
 

 </td></tr>


</table>
</form>


  <!--[body]-->
  

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
