
<script type="text/javascript">
  jQuery(".delete-list").live('click',function(event){
   		 
  		bibConfirm("Are you sure you want to delete this Subscription Form?",'delSignUpFrm("'+$(this).attr('name')+'")');
  
  });
  
  function delSignUpFrm(sid){
  	    
	jQuery.ajax({
        url: "<?php echo base_url(); ?>subscription/signup_delete/"+sid,
        type:"POST",
        success: function(data){ window.location.reload();}
  	});
  }
function get_share(sid, link){  
 
	    jQuery.ajax({
          url: base_url+"subscription/subscribe/"+sid+"/code",
          type:"POST",
          success: function(data) {
            $('textarea#showSignupCode').html(data).text();
           // $('input#copy_link').val(copy_link+sid);
          }
        });
		 
        
		displayAlertMessage('BIB Share signup-form URL & code','','0',true,600,550,false,'');
		$( "#message" ).html( $("#copy-code").html() );   
}     
 
  function goto_page(signup_id){
      window.location.href="<?php echo base_url(); ?>subscription/signup_edit/"+signup_id;
  }

  function view_form(signup_id){
    jQuery.ajax({
      url: "<?php echo base_url() ?>subscription/subscribe/"+signup_id+"/view_code",
      type:"POST",
      success: function(data) {
        var obj=jQuery.parseJSON(data);
        $('.editing-theme-box').removeClass("active");
        $('#signup_form_'+signup_id).addClass("active");
        $('.copy_code').hide();
        $('.view_code_container').show();
        $('.view_overview').html(obj.view_overview);
        $('.view_code').html(obj.view_code);
        $('.view_code').css("background-color",obj.background_color);
        $('.view_code').css("background-image",obj.background_image);
        $('.view_code').css("background-repeat",obj.background_repeat);
      }
    });
  }
  function enable_stats(signup_id){
    jQuery.ajax({
      url: "<?php echo base_url() ?>subscription/enable_stats/"+signup_id,
      type:"POST",
      success: function(data) {
        view_form(signup_id);
      }
    });
  }
</script>


  <!--[body]-->

      
        <?php
          //Fetch signup_froms from signup_froms array
          $i=1;
        ?>
		<table width="97%" cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;" align="center">
		<tr bgcolor="#ccc"><td><b>Form Title</b></td><td colspan="4"><b>Actions</b></td></tr>
        <?php foreach($signup_froms['forms'] as $signup_from){ ?>
		<tr>
			<td onclick="view_form(<?php echo $signup_from['id']; ?>)" bgcolor="#fcfcfc"><b><?php echo $signup_from['form_name']; ?></b></td>
			<td><a href="javascript:void(0);" onclick="view_form(<?php echo $signup_from['id']; ?>)" class="btn cancel">View</a></td>
			<td><a href="<?php echo base_url(); ?>subscription/edit/<?php echo $signup_from['id']; ?>"  class="btn cancel">Edit</a></td>
			<td><a href="javascript:void(0);" class="link btn cancel" onclick="get_share(<?php echo $signup_from['id']; ?>,'<?php echo CAMPAIGN_DOMAIN.'s/'.$signup_from['id'] ?>')">Share</a></td>
			<td><a href="javascript:void(0);" class="delete-list btn cancel" name="<?php echo $signup_from['id']; ?>">Delete</a></td>
		</tr>		
          
         <?php
          $i++;
          } ?>
 </table>
      <div class="right-menu contacts forms">
        <?php if(count($signup_froms['forms'])) { ?>
         
          <div class="view_code_container">
            <h2  style="margin-left:10px;">Overview</h2>
			<!-- Overview via AJAX -->
			<div class="view_overview"></div>		 
            <div class="splitter"></div>
            <h2  style="margin-left:10px;">Sign Up Form Preview</h2>
			<!-- Signup form via AJAX -->
            <div class="view_code" style="margin-left:10px;width:350px;"></div>
          </div>
        <?php } else { ?>
			<div class = 'contacts_message'>You do not have any signup-form created yet.<br/>
                <a class = 'au' href = '<?php echo  site_url('subscription/create') ;?>'><b>Create Subscription-form</b></a>
				
			</div>
          
        <?php  } ?>
      </div>
  
      <!--[/navigation]-->

  <!--[/body]-->
<?php if(count($signup_froms['forms'])>0) { ?>
<script type="text/javascript">
view_form(<?php echo $signup_froms['forms'][0]['id']; ?>)
</script>
<?php } ?>





<div id="copy-code" style="display:none">
    <div style="width: 780px; margin: 0; height: 520px;">     
      <span class="subtitle">(Copy &amp; paste in an email, on your website or blog, Facebook, Twitter or any other social network.)</span>
      <input id="copy_link" value="<?php echo CAMPAIGN_DOMAIN.'s/'.$this->is_authorized->encryptor('encrypt',$signup_from['id']); ?>" type="text" onclick="this.setSelectionRange(0, this.value.length)" style="width:550px;" class="clean" />
      <h6 style="margin-top: 5px">HTML Code</h6>
      <textarea style="height: 392px; width: 550px;margin: 4px 13px 13px; resize: none;" onclick="this.setSelectionRange(0, this.value.length)" id="showSignupCode"></textarea>
    </div>
  </div>   
