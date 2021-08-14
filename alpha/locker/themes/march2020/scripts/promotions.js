// ajax request for campaign-listing pg.

$(document).on('click',".pagination a", function(event){
	if(!$(this).hasClass('selected')){
		var block_data="";
		
		block_data+="&srch_email="+$('#email_search').val();
		
		
		$.ajax({
		   type: "POST",
		   data: block_data,
		   url: $(this).attr('href'),
		   success: function(data){ 
				// for campaign pages
				$('.campaigns_container').html(data);				
		   }
		});
	}
	//$(".pagination").find('.selected').removeClass('selected');
	//$(this).addClass('selected');
	//reinit();
    return false; // don't let the link reload the page
});


$(document).on('click', "#btnSearchCampaign", function(){        
        jQuery("#campaignSearchFrm")[0].submit(); // Submit the form
    });




/*
		ajax call to delete contact
*/

function delCampaign(enc_cid){
	bibConfirm("Are you sure to delete this campaign. All the stats will also be deleted associated to this campaign.",'deleteCampaign("'+enc_cid+'")');
}


function deleteCampaign(campaign_id){
  jQuery.ajax({
  url: "<?php echo base_url() ?>promotions/update_campaign/"+campaign_id+"/delete",
  type:"POST",
  success: function(data){
    jQuery("#campaign_"+campaign_id).fadeOut('slow');
  }});
}



// Removes leading whitespaces
function LTrim( value ) {
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}
// Removes ending whitespaces
function RTrim( value ) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}