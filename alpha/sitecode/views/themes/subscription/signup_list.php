<script type="text/javascript">

$(document).on('click','.delete-list',function(){
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
	displayAlertMessageAdvance('Share / Embed signup-form URL & Code','','0','');
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
        $('.view_overview').html(obj.view_overview);
        $('.view_code').html(obj.view_code);        
      }
    });
	displayAlertMessageAdvance('Overview','','0','');
	$( "#message" ).html( $("#overview").html() ); 
	$( ".view_overview" ).show();
	$( ".view_code" ).show();
}
</script>
<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner su_form">					
					<div class="row">							
						<?php if(count($signup_froms['forms'])) { ?>
							<div class="col-lg-12 signupformlist">
							<div class="campaign-post">
								<div class = 'row'>
									<div class="col-lg-12 contacts-grid">
										<div class="row row-head">
											<div class="col-1 contacts-cols">#</div>
											<div class="col-7 contacts-cols">Form Title / Name</div>
											<div class="col-4 contacts-cols">Manage</div>
										</div>
										<?php
											 $i=1;
											foreach($signup_froms['forms'] as $signup_from){ 
										?>										
										<div class="row row-striped">
											<div class="col-1 contacts-cols"><?PHP echo $i; ?></div>
											<div class="col-7 contacts-cols"><a title="Click to Preview" href="javascript:void(0);" onclick="view_form(<?php echo $signup_from['id']; ?>)"><?php echo $signup_from['form_name']; ?></a></div>
											<div class="col-4 contacts-cols">
												<ul class="contacts-ops-links">
													<li><a title="Click to Preview" href="javascript:void(0);" onclick="view_form(<?php echo $signup_from['id']; ?>)" class="pink round round32"><i class="fa fa-eye"></i></a></li>
													<li><a title="Click to Edit" href="<?php echo base_url(); ?>subscription/edit/<?php echo $signup_from['id']; ?>" class="pink round round32"><i class="fa fa-edit"></i></a></li>
													<li><a title="Click to Share" href="javascript:void(0);" onclick="get_share(<?php echo $signup_from['id']; ?>,'<?php echo CAMPAIGN_DOMAIN.'s/'.$signup_from['id'] ?>')" class="pink round round32"><i class="fa fa-share-alt"></i></a></li>
													<li><a title="Click to Delete" href="javascript:void(0);" class="delete-list pink round round32" name="<?php echo $signup_from['id']; ?>"><i class="fa fa-trash"></i></a></li>
												</ul>
											</div>
											
										</div>
										
										 <?php
										  $i++;
										  } ?>
										<div class="row row-footer">						
											<div class="col-12 contacts-cols">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>
						</div>	
						 <?php }else{ ?>
							<div class="col-lg-12">
								<div class = 'alert alert-warning'>You have not created any sign-up forms yet.  <a class = 'font4 font400' href = '<?php echo  site_url('subscription/create') ;?>'><b>Create Sign-up form</b></a></div>
							</div>
						 <?php } ?>	
					</div>						
				</div>
			</div>
		</div>
	</div>
</section>

<div id = 'overview'>
<div class = 'overlay-page'>
	<div class="form-group">
		<label class="col-form-label">Sign-Up Form Stats</label>
	</div>
	<div class="view_overview"></div>
	<div class = 'clear10'></div>
	<div class="form-group">
		<label class="col-form-label">Sign-Up Form Preview</label>
	</div>
	<div class="view_code"></div>
	<div class = 'clear10'></div>
</div>
</div>
<div id="copy-code">
	<div class = 'overlay-page'>		
		<div class="form-group">
		<label class="col-form-label">(Copy &amp; paste in an email, on your website or blog, Facebook, Twitter or any other social network.)</label>			
			 <input id="copy_link" value="<?php echo CAMPAIGN_DOMAIN.'s/'.$this->is_authorized->encryptor('encrypt',$signup_from['id']); ?>" type="text" onclick="this.setSelectionRange(0, this.value.length)" style="width:550px;" class="clean form-control" />
		</div>
		<div class="form-group">
			<label class="col-form-label">HTML Embed Code</label>		
			<textarea style = 'min-height:250px;' class = 'form-control' onclick="this.setSelectionRange(0, this.value.length)" id="showSignupCode"></textarea>
		</div>
	</div>

</div> 