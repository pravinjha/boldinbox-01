
<script type="text/javascript">
  jQuery(".delete-list").live('click',function(event)
  {
    jQuery(this).fastConfirm({
      position: "top",
      questionText: "Are you sure you want to delete this Subscription Form?",
      onProceed: function(trigger) {
        var signup_form_id=jQuery(trigger).attr('name');
         jQuery.ajax({
          url: "<?php echo base_url(); ?>subscription/signup_delete/"+signup_form_id,
          type:"POST",
          success: function(data){
            jQuery(trigger).parents('#signup_form_'+signup_form_id).remove();
            if($('.editing-theme-box').length<=0){
              $('.create_signup_form').show();
              $('.copy_code').hide();
              $('.view_code_container').remove();
            }
          }
        });
      },
      onCancel: function(trigger) {
      }
    });

  });
  function get_share(signup_id, link){
    $("#copy_link").val(link);

    jQuery.ajax({
      url: "<?php echo base_url() ?>subscription/subscribe/"+signup_id+"/code",
      type:"POST",
      success: function(data) {
        $('.editing-theme-box').removeClass("active");
        $('#signup_form_'+signup_id).addClass("active");
        $('.copy_code').show();
        $('.view_code_container').hide();
        $('#copy_code').html(data);
      }
    });
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
		<table width="100%" cellpadding="4" cellspacing="3">
        <?php foreach($signup_froms['forms'] as $signup_from){ ?>
		<tr>
			<th onclick="view_form(<?php echo $signup_from['id']; ?>)"><?php echo $signup_from['form_name']; ?></th>
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
          <div class="view_code_bg">
            <div class="copy_code">
              <h2>Share</h2>
              <strong>Quick link to your Signup Form</strong>
              <span>(Copy &amp; paste in an email, on your website or blog, Facebook, Twitter or any other social network.)</span>
              <input id="copy_link" type="text" onclick="this.setSelectionRange(0, this.value.length)" class="clean" />
              <strong>Copy and Paste Code</strong>
              <textarea id="copy_code" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
            </div>
          </div>
          <div class="view_code_container">
            <h2>Overview</h2>
			<!-- Overview via AJAX -->
			<div class="view_overview"></div>		 
            <div class="splitter"></div>
            <h2>Sign Up Form Preview</h2>
			<!-- Signup form via AJAX -->
            <div class="view_code"></div>
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
