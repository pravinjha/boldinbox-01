<?php
 load_class('JSMin','libraries', '');

$arrJSFiles = array(
			'js/jquery-1.5.1.min.js',
			'js/jquery-ui-1.8.13.custom.min.js',			
			'js/nicEdit.js',			
			'js/jquery.upload-1.0.2.js',
			'js/colorpicker.js',			
			'js/jquery.masonry.min.js',
			'jquery/jquery.modalBox.js',
			'js/generic.js',
			'js/site.js'
			);
			
$modified_time = 0;
foreach($arrJSFiles as $jsfile){
	$js_output .= (file_get_contents(FCFOLDER . '/locker/'.$jsfile))."\n\n\n";
	$modified_time = max(filemtime(FCFOLDER . '/locker/'.$jsfile), $modified_time);
}
// $js_output = JSMin::minify($js_output);

Header("content-type: application/x-javascript");
echo ($js_output);
?>
/* 
window.onerror = function(msg, url, linenumber) {
    alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
    return true;
} 
*/

function changeLayout(){
	bibConfirm("You will loose your campaign changes. Do you still want to change layout?","gotoSelectLayout()");
}
function gotoSelectLayout(){
	window.location.href=base_url+'promotions/layouts';
}

var pagechange=false;		//set Page content change
var border_style=0;			//Check border style none or not none
var body_blocks=new Array('body_main');	// Define blocks
 
 
var img_size_2 =275; //265
var img_size_3 =181; //168
var img_size_4 =135; //120
var img_gap = 5; //25
 
var handler='<div class="handler_ul_div"><ul style="list-style: none outside none;display:none;" class="handler"><li class="alignright"><a class="close-link" ><img src="<?php echo base_url() ?>locker/images/cross-script.png?v=6-20-13" title="Delete" /></a></li><li class="drag-center"><a class="drag_handler"><img src="<?php echo base_url() ?>locker/images/drag.png?v=6-20-13" title="Drag"/></a></li><li class="li_bgcolor" ><a class="a_bgclr"><img src="<?php echo base_url() ;?>locker/images/ico_famimart.png" width="14" height="14" /></a></li></ul></div>';		//Blocks handler

var handler_banner='<div class="handler_ul_div"><ul style="list-style: none outside none;display:none;" class="handler"><li class="alignright"><a class="close-link" ><img src="<?php echo base_url() ?>locker/images/cross-script.png?v=6-20-13" title="Delete" /></a></li><li class="drag-center"><a class="drag_handler"><img src="<?php echo base_url() ?>locker/images/drag.png?v=6-20-13" title="Drag"/></a></li></ul></div>';		//Blocks handler 

var handler_image1="<div class='handler_img'><ul><li><a  class='img_styl'>css</a></li><li><a  class='option_image-link'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' title='Add Link'/></a></li><li ><a  class='option_image-caption' ><img src='<?php echo base_url() ?>locker/images/icons/caption.png' title='Add Caption'/></a></li><li><a  class='clone_image' ><img src='<?php echo base_url() ?>locker/images/icons/add.png' title='Add'/></a></li></ul></div><div class='handler_img_width_height'></div>";		//1 image  handler

var handler_image2="<div class='handler_img'><ul><li><a  class='img_styl'>css</a></li><li><a  class='option_image-link'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' title='Add Link'/></a></li><li><a  class='option_image-caption' ><img src='<?php echo base_url() ?>locker/images/icons/caption.png' title='Add Caption'/></a></li><li><a  style='margin:0px' class='close-clone-link'><img src='<?php echo base_url() ?>locker/images/icons/delete.png' title='Delete'/></a></li><li><a  class='clone_image' ><img src='<?php echo base_url() ?>locker/images/icons/add.png' title='Add'/></a></li></ul></div><div class='handler_img_width_height'></div>";		//2 images handler

var handler_image3="<div class='handler_img'><ul><li><a  class='img_styl'>css</a></li><li><a  class='option_image-link'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' title='Add Link'/></a></li><li><a  class='option_image-caption' ><img src='<?php echo base_url() ?>locker/images/icons/caption.png' title='Add Caption'/></a></li><li><a  style='margin:0px' class='close-clone-link'><img src='<?php echo base_url() ?>locker/images/icons/delete.png' title='Delete'/></a></li><li><a  class='clone_image' ><img src='<?php echo base_url() ?>locker/images/icons/add.png' title='Add'/></a></li></ul></div><div class='handler_img_width_height'></div>";		//3 images  handler

var handler_image4="<div class='handler_img'><ul><li><a  class='img_styl'>css</a></li><li><a  class='option_image-link'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' title='Add Link'/></a></li><li><a  class='option_image-caption' ><img src='<?php echo base_url() ?>locker/images/icons/caption.png' title='Add Caption'/></a></li><li><a  style='margin:0px' class='close-clone-link'><img src='<?php echo base_url() ?>locker/images/icons/delete.png' title='Delete'/></a></li></ul></div><div class='handler_img_width_height'></div>";	//4 images  handler

var handler_image_text="<div class='handler_img'><ul><li><a  class='img_styl'>css</a></li><li><a  class='option_image-link'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' title='link'/></a></li><li><a  class='option_image-caption' style='float:left;'><img src='<?php echo base_url() ?>locker/images/icons/caption.png' title='caption'/></a></li><li><a  class=\"change-pos\" style=\"float:left;\"><img src='<?php echo base_url() ?>locker/images/icons/align_right.png?v=6-20-13' title='Right align'/></a></li></ul></div><div class='handler_img_width_height'></div><div class='handler_img_width_height'></div>";	//image with text block  handler

var handler_youtube="<div class='handler_img '><ul><li><a  class=\"edit_youtube-link\" style=\"float:left; display: block; width: 22px; height: 25px;\"><img src=\"<?php echo base_url() ?>locker/images/icons/edit.png\" title=\"edit\"/></a></li></ul></div><div class='handler_img_width_height'></div>";	//Youtube block handler

var handler_header='<div class="menu iconWrap" ><ul><li class="drag-center"><a class="drag_handler" style="width:32px;height:32px;"><img src="<?php echo base_url() ?>locker/images/drag.png?v=6-20-13" title="Drag"/></a></li><li><a  class="img_styl" >CSS</a></li><li><a  class="header_link" ><img title="Add Link"  src="<?php echo base_url();?>locker/images/icons/add_link.png?v=6-20-13"/></a></li><li><a  class="select_theme" ><img  title="Add / Change Header Image" src = "<?php echo base_url() ?>locker/images/icons/change-header.png" /></a></li><li><a   class="add_logo" ><img title="Add Logo"  src="<?php echo base_url();?>locker/images/icons/add_logo.png?v=6-20-13" /></a></li><li title="Delete Header"><a   class="close-link header_unlink"><img src = "<?php echo base_url() ?>locker/images/icons/delete.png" /></a></li></li></ul></div>';	// toolbar opions for header

var logo_header='<div id="logo" class="banner_logo" style="left:0px;right:0px"><div class=\'handler_img_width_height\'></div><span class="logo_img"><img id="logo_img_id" src="[logo_src]" title="click to change logo" alt="click to change logo" style="width:100px;"/></span><div  class="logo-resize-div"><div class="handler_logo" ><a class="close-link logo_class" href="javascript:void(0);"><img title="Delete" src="<?php echo base_url();?>locker/images/icons/delete.png?v=6-20-13" border="0"/></a></div><div class="div_border"></div></div></div>';		// Logo html

var handler_footer='<div class="footer_menu iconWrap"><ul><li><a class="edit_footer"><img  title="Edit Footer Address" src = "<?php echo base_url() ?>locker/images/icons/edit.png" /></a></li></ul></div>';
// Footer handler

var header_border='<div class="resize_header_div" style="position: absolute; top: 0px; left: 0px;display:block;"><div style="border: 1px solid rgb(128, 128, 128);" class="div_border"><span style="display:block;" class="drop_div_border"></span></div></div>';		//Header Image Border




/**
	Function drag_drop to drag blocks: Text, Image, Text Image, Divider and  drop  them into email template
**/
function drag_drop(){
	jQuery("#body_main").addClass('drop'); // Add Class drop in body container
	jQuery( ".drop" ).sortable({
		containment: '.main-container',
		revert: true,
		helper:	'clone',
		placeholder:'ui-state-highlight',
		start: function(event, ui) {
			ui.placeholder.height('50px');
			//ui.placeholder.height(ui.item.height());
		},
		over: function(event, ui) {
			$('.diy_demo_video').remove();
			$('.empty_block').removeClass('empty_block');
		},
		out: function(event, ui) {
			//if($('.container-div').length <= 1){
			if($('#body_main').children('.container-div').length < 1){
				$('#body_main').addClass('empty_block');
			}
		},
		update: function(e, ui){
			if (ui.item.hasClass('block-banner'))			fnBannerBlock(ui.item);
			if (ui.item.hasClass('block-title'))			fnTitleBlock(ui.item);
			if (ui.item.hasClass('block-text'))				fnTextBlock(ui.item);
			if (ui.item.hasClass('block-offer'))			fnOfferBlock(ui.item);
			if (ui.item.hasClass('block-image'))			fnImageGroupBlock(ui.item);
			if (ui.item.hasClass('block-image-text'))		fnImageTextBlock(ui.item);
			if (ui.item.hasClass('block-button'))			fnButtonBlock(ui.item);
			if (ui.item.hasClass('block-table'))			fnTableBlock(ui.item);			
			if (ui.item.hasClass('block-divider-rule'))		fnDividerRule(ui.item);
			if (ui.item.hasClass('block-partition'))		fnPartitionBlock(ui.item);			
			if (ui.item.hasClass('block-social-media'))		fnSocailMedia(ui.item);
			if (ui.item.hasClass('block-youtube'))			fnYoutubeBlock(ui.item);
			jQuery('.save_campaign').removeClass('disable-link');	// enable save link
			pagechange=true;	//Page content change
		}
	});
	jQuery( ".block,.block-banner,.block-image,.block-text,.block-offer,.block-image-text,.block-table,.block-button, .block-title, .block-image-group,.block-social-media,.block-youtube,.block-divider-rule,.block-partition" ).draggable({ connectToSortable: ".drop",helper: function() {
	var $this= $(this);
	var clone= $this.clone().css("position","relative");
	var cloneWidth= $this.width();
	var helper= $("<div>")
	.append(clone)
	.width(cloneWidth*2)
	.mousemove(function() {
	var lb= 0;
	var q=  cloneWidth;
	var p= 1-Math.max(0,Math.min(1,clone.offset().left / q));
	var lb= 0;
	clone.css("left",(p*50) + "%");
	});
	return helper;
	}, containment: '.main-container', appendTo: ".drop",snap:'true',"distance":0,'revert': false
	});
}
// Image style: Starts
$('.img_styl').live('click',function(){
	var blkid = $(this).parents('.container-div').attr('id');
	 
	var thisBrdrclr = hexc($('#'+blkid).find('.image-container').css('border-color'));
	var thisBrdrWidth = parseInt($('#'+blkid).find('.image-container').css('border-width'), 10); 
	alert(thisBrdrWidth); 
	
	displayAlertMessage('Change image style','','0',true,400,280,false,'');
	$( "#message" ).html( $(".img_style").html() ); 	
	$('#messageBox').find('#txt_blkBrdrColor').ColorPicker({
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {$(el).val('#'+hex).css('color','#'+hex).css('background-color','#'+hex); $(el).ColorPickerHide();}
	});
	$('#messageBox').find("#txt_blkBrdrColor").val(thisBrdrclr).css({'color':thisBrdrclr,'background-color':thisBrdrclr});
$('#messageBox').find("#sel_brdrWidth option[value="+thisBrdrWidth+"]").attr('selected','selected');	
	
	$("#current_container_id").val(blkid);		// set block id
	pagechange=true;	//Page content change
});


function updateImgStyle(){
	var bid = $("#current_container_id").val();
	$("#current_container_id").val('');
	 
	 
	var thisBrdrclr =  $('#messageBox').find("#txt_blkBrdrColor").val();
	var thisBrdrWidth =  $('#messageBox').find("#sel_brdrWidth").val();
	 
	$('#'+bid).find('.image-container').css({'border-width':thisBrdrWidth+'px','border-collapse': 'separate','border-style': 'solid', 'border-color':thisBrdrclr});
	//$('#'+bid).css({'background-color':thisBGclr,'border-width':thisBrdrWidth+'px','border-collapse': 'separate','border-style': 'solid', 'padding':'5px', 'border-color':thisBrdrclr});
	
	//$('#'+bid).css({'background-color':thisBGclr, 'padding':'5px'});
	
	$.modalBox.close();
} 
// Image style: Ends

$('.a_bgclr').live('click',function(){
	var blkid = $(this).parents('.container-div').attr('id');	 
	var blkbgcolor = hexc($('#'+blkid).css('backgroundColor'));	 
	
	displayAlertMessage('Change style of this block','','0',true,400,280,false,'');
	$( "#message" ).html( $(".blk_style").html() ); 	
	$('#messageBox').find('#txt_blkBGColor').ColorPicker({
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {$(el).val('#'+hex).css('color','#'+hex).css('background-color','#'+hex); $(el).ColorPickerHide();}
	});
	$('#messageBox').find("#txt_blkBGColor").val(blkbgcolor).css({'color':blkbgcolor,'background-color':blkbgcolor});
	
	$("#current_container_id").val(blkid);		// set block id
	pagechange=true;	//Page content change
});


function updateBlockStyle(){
	var bid = $("#current_container_id").val();
	$("#current_container_id").val('');
	 
	var thisBGclr =  $('#messageBox').find("#txt_blkBGColor").val();
	var thisBrdrclr =  $('#messageBox').find("#txt_blkBrdrColor").val();
	 
	
	$('#'+bid).css({'background-color':thisBGclr, 'padding':'5px'});
	
	$.modalBox.close();
} 
// Banner Block
function fnBannerBlock(thisBlock){	
	var banner_id='block_'+new Date().getTime();		
	$('.empty_block').removeClass('empty_block');
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='header_banner container-div' id='"+banner_id+"' style='border-width:0px !important'><tr><td  class='handler_div' align='center'><div class='header_div'><span class='empty_header'>Put banner here</span></div></td></tr></table>");
	loadHeaderEffect(banner_id);
	 
	thisBlock.remove();
	//var handler_block=handler_banner.replace('[colspan]','1');
	$('#'+banner_id).find('.handler_div').prepend(handler_banner);
	pagechange=true;	//Page content change
}
/**
	Function fnTextBlock to drop text block
**/
function fnTextBlock(thisBlock){
	var text_block_id='block_'+new Date().getTime();
	$('.empty_block').removeClass('empty_block');	
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+text_block_id+"'><tr><td align='center' class='handler_div' ><div  class='text-paragraph-container' style='padding:0 15px;text-align:left;line-height:1.7;' ><div class='empty_text'>Click here to add text.</div></div></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+text_block_id).find('.handler_div').prepend(handler_block);
	pagechange=true;	//Page content change
}
function fnTitleBlock(thisBlock){
	var text_block_id='block_'+new Date().getTime();
	$('.empty_block').removeClass('empty_block');	
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+text_block_id+"'><tr><td align='center' class='handler_div' ><div  class='text-paragraph-container' style='padding:0 15px;text-align:left;line-height:1.7; text-align: center; font-size: 18px; font-weight: bold;' ><div class='empty_title'>Click here to add Title.</div></div></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+text_block_id).find('.handler_div').prepend(handler_block);
	pagechange=true;	//Page content change
}

/**
	Function fnImageTextBlock to drop image-text block
**/
function fnImageTextBlock(thisBlock){
	var imtxt_id ='block_'+new Date().getTime();	
	$('.empty_block').removeClass('empty_block');
	
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div'  id='"+imtxt_id+"' align='center'><tr><td class='handler_div' align='center'><div class='text_img_outer_div' style='padding:0 15px;text-align:left;line-height:1.7;'><table  cellspacing='0' cellpadding='0' class='resize_table' style='width:225px;margin-top:7px;' align='left'><tr><td id='"+imtxt_id+"_1' class='img_content text_img_content' align='left'><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image text_image ' alt='Campaign-Image' border='0' name='1'/><div class='resize_div_text' style='width:200px;height: 200px; '><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png'  class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td></tr></table><div class='text-paragraph-container' align='left' style='width:100%;min-height:207px;line-height:1.7;'><div class='empty_text'>Click here to add text.</div></div></div></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+imtxt_id).find('.handler_div').prepend(handler_block);
	$('#'+imtxt_id).find('.resize_div_text').prepend(handler_image_text);
	imageResize(imtxt_id);
	loadImageSize(imtxt_id);
	pagechange=true;	//Page content change
	dragDropImageBank();
}
function removeGarbage(){
	$('.diy_demo_video').remove();
	if($("#current_container_id").val()!=""){
		var top_div=$("#current_container_id").val();		
		jQuery("#"+top_div).remove();
	}
	$('#active_block').attr('id','');
	$('#active_header').attr('id','');
	$('#active_logo').attr('id','');
	$('#active_image_bank').attr('id','');
	$('#active_theme_color').attr('id','');
}
function fnButtonBlock(thisBlock){
	var btnid='block_'+new Date().getTime();
	thisBlock.attr('id',btnid);
	displayAlertMessage('Add a Button to Your Campaign','','0',true,400,280,false,'fn:removeGarbage');
	$( "#message" ).html( $("#btn_dialog").html() ); 	
	$('#messageBox').find('#btnBGColor,#btnFontColor').ColorPicker({
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {$(el).val('#'+hex).css('color','#'+hex).css('background-color','#'+hex); $(el).ColorPickerHide();}
	});
	
	$("#current_container_id").val(btnid);		// set block id
	pagechange=true;	//Page content change
}

function addButton(){
	var btn_block_id='block_'+new Date().getTime();	
	var btntxt = $('#messageBox').find("#btnText").val();
	var btnurl = $('#messageBox').find("#btnURL").val();
	var btnbg = $('#messageBox').find("#btnBGColor").val();
	var btncolor = $('#messageBox').find("#btnFontColor").val();
	var btnalign = $('#messageBox').find("#btn_alignment").val();	
	if(btnurl == 'http://' || btnurl ==''){ $('#messageBox').find("#btnmsg").html('URL Is Required');return false; }
	var top_div = "";
	if($("#current_container_id").val()!=""){
		top_div=$("#current_container_id").val();
		$("#current_container_id").val("");
	}	
	jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+btn_block_id+"' ><tr><td  class='handler_div' align='"+btnalign+"'><div style='padding:10px 0px;' ><a href='#' title='"+btnurl+"' class='button_block' style='margin:30px 15px; font-weight:700;padding:6px 10px;text-decoration:none; box-shadow:2px 3px 5px #888888; background:"+btnbg+"; color:"+btncolor+"'>"+btntxt+"</a></div></td></tr></table>");
	jQuery("#"+top_div).remove();
	$.modalBox.close();
	
	$('#'+btn_block_id).find('.handler_div').prepend('<div class="handler_ul_div"><ul style="list-style: none outside none;display:none;" class="handler"><li class="alignright"><a class="close-link" ><img src="<?php echo base_url() ?>locker/images/cross-script.png?v=6-20-13" title="Delete" /></a></li><li><a title="Edit"  class="edit_btn">edit</a></li><li class="drag-center" ><a class="drag_handler"><img src="<?php echo base_url() ?>locker/images/drag.png?v=6-20-13" title="Drag"/></a></li><li class="drag-center" ><a class="a_bgclr"><img src="<?php echo base_url() ;?>locker/images/ico_famimart.png" width="14" height="14" /></a></li></ul></div>');
	$('.empty_block').removeClass('empty_block');
	pagechange=true;	//Page content change
}
$('.edit_btn').live('click',function(){
	var btnid = $(this).parents('.container-div').attr('id');
	var btn_align = $('#'+btnid).find('.handler_div').attr('align');
	var btn_href = $('#'+btnid).find('.button_block').attr('title');
	var btn_text = $('#'+btnid).find('.button_block').text();
	var btn_bg = hexc($('#'+btnid).find('.button_block').css('backgroundColor'));
	var btn_color = hexc($('#'+btnid).find('.button_block').css('color'));
	
	displayAlertMessage('Edit this button','','0',true,400,280,false,'');
	$( "#message" ).html( $("#btn_dialog").html() );
	// Open Colorpicker for button-block settings . #	
	$('#messageBox').find('#btnBGColor,#btnFontColor').ColorPicker({
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {$(el).val('#'+hex).css({'color':'#'+hex, 'background-color': '#'+hex}); $(el).ColorPickerHide();}
	});
	
	$("#current_container_id").val(btnid);	
	$('#messageBox').find("#btnText").val(btn_text);
	$('#messageBox').find("#btnURL").val(btn_href);
	$('#messageBox').find("#btnBGColor").val(btn_bg).css({'color':btn_bg,'background-color':btn_bg});
	$('#messageBox').find("#btnFontColor").val(btn_color).css({'color':btn_color,'background-color':btn_color});
	$('#messageBox').find("#btn_alignment").val(btn_align);
});
function fnTableBlock(thisBlock){
	var tbl_id='block_'+new Date().getTime();
	thisBlock.attr('id',tbl_id);
	displayAlertMessage('Insert a Table','','0',true,350,160,false,'fn:removeGarbage');
	$( "#message" ).html( $("#tbl_dialog").html() ); 	
	$("#current_container_id").val(tbl_id);		// set block id
	pagechange=true;	//Page content change
}
function addTable(){
	var table_block_id='block_'+new Date().getTime();
	
	var tblRows = $('#messageBox').find("#tbl_rows").val();
	var tblCols = $('#messageBox').find("#tbl_cols").val();
	
	var top_div = "";
	if($("#current_container_id").val()!=""){
		top_div=$("#current_container_id").val();
		$("#current_container_id").val("");
	}
	var tabStr = "<table width='100%' cellspacing='0' cellpadding='0' class='container-div table_block' id='"+table_block_id+"' ><tr><td  class='handler_div' align='center'>";	
	tabStr += "<table width='100%' cellspacing='0' border='1' cellpadding='2' style='border-collapse:collapse;border:1px solid #c1c1c1;'>";
	for(var i=0;i < tblRows; i++){
		tabStr += "<tr>";
		for(var j=0;j < tblCols; j++)tabStr += "<td><div class='text-paragraph-container' id='"+i+"-"+j+"' align='left' style='padding:0 15px;text-align:left;line-height:1.7;'><div class='empty_text'>Click here to add text.</div></div></td>";
		tabStr += "</tr>";
	}
	tabStr += "</table></td></tr></table>";
	
	jQuery("#"+top_div).after(tabStr);
	jQuery("#"+top_div).remove();
	$.modalBox.close();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+table_block_id).find('.handler_div').prepend(handler_block);
	$('.empty_block').removeClass('empty_block');
	pagechange=true;	//Page content change
}
/**
	Function fnImageGroupBlock to drop image group block
**/
function fnImageGroupBlock(thisBlock){
	var img_id='block_'+new Date().getTime();
	thisBlock.attr('id',img_id);
	displayAlertMessage('Add Image(s) to Your Campaign','','0',true,450,290,false,'fn:removeGarbage');
	$( "#message" ).html( $("#image_group_dialog").html() ); 
	$("#current_container_id").val(img_id);		// set block id
	pagechange=true;	//Page content change
}
/**
	Function fnDividerRule to drop divider  block
**/
function fnDividerRule(thisBlock){
	var bid='block_'+new Date().getTime();
	$('.empty_block').removeClass('empty_block');
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div divider_block' id='"+bid+"' ><tr><td  class='handler_div' align='center'><hr style='width:547px;margin:5px 0;padding:0;' /></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+bid).find('.handler_div').prepend(handler_block);
	pagechange=true;	//Page content change
}
function fnPartitionBlock(thisBlock){
	var bid='block_'+new Date().getTime();
	$('.empty_block').removeClass('empty_block');
	thisBlock.after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div divider_block' id='"+bid+"' ><tr><td  class='handler_div' align='center'><div style='height:40px;'>&nbsp;</div></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+bid).find('.handler_div').prepend(handler_block);
	pagechange=true;	//Page content change
}

/**
	Function fnOfferBlock to drop offer  block
**/
function fnOfferBlock(thisBlock){
	var obid ='block_'+new Date().getTime();
	$('.empty_block').removeClass('empty_block');
	thisBlock.after("<table width='100%' cellspacing='5' style='padding:0px !important;' cellpadding='0' class='container-div offer_block' align='center' id='"+obid+"'><tr><td align='center'  class='handler_div'><table width='100%' cellspacing='0' cellpadding='0' class='offer' style='padding-top:10px;padding-bottom:10px;' ><tr><td align='center'><div class='edit_offer_div'  style='border-width:6px;border-style: dashed;border-color:#333333 !important;'><div class='edit_offer' style='padding:0px;line-height: 1.7;text-align: center;'><br><font size='6'><b>FREE!</b></font><br /><br />Write offer detail here <br><br><font size='2'>Expires:month/day/year</font><br><br></div></div></td></tr></table></td></tr></table>");
	thisBlock.remove();
	var handler_block=handler.replace('[colspan]','1');
	$('#'+obid).find('.handler_div').prepend(handler_block);
	$('#'+obid).find('.edit_offer_div').prepend("<div class='handler_img_width_height'></div>");
	pagechange=true;	//Page content change
	loadOfferEffects(obid);
}
/**
	Function fnSocailMedia to drop social media block
**/
function fnSocailMedia(thisBlock){
	var img_id='block_'+new Date().getTime();
	thisBlock.attr('id',img_id);
	displayAlertMessage('Add Social Media to Your Campaign',"<div style='text-align:center'><img src='"+base_url+"locker/images/icons/ajax-loader.gif' /></div>",'0',true,500,355,true,'fn:removeGarbage');
	$.ajax({type: "POST", cache: false, url: base_url+'promotions/get_socialmedia_ajax/', success: function(data) { $( "#message" ).html( data ); } });
	$("#current_container_id").val(img_id);		// set block id
	pagechange=true;	//Page content change
}
/**
	Function fnYoutubeBlock to drop youtube block or vimeo video
**/
function fnYoutubeBlock(thisBlock){
	var img_id= new Date().getTime();
	thisBlock.attr('id',img_id);
	displayAlertMessage('Add a Video to Your Campaign','','0',true,480,220,false,'fn:removeGarbage');
	$( "#message" ).html( $("#youtube_edit_dialog").html() ); 	
	$("#current_container_id").val(img_id);		// set block id
	pagechange=true;	//Page content change
}

/**
	function saveImageGroupOption to insert  images in image group block
**/
function saveImageGroupOption(img_count){	
	var top_div;
	if($("#current_container_id").val()!=""){
		top_div=$("#current_container_id").val();
	}
	if(img_count==1){
		var im='block_'+new Date().getTime();		
		$('.empty_block').removeClass('empty_block');
		jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+im+"'><tr><td  class='handler_div' align='center'><table  align='center' class='resize_table' cellpadding='0' cellspacing='0'><tr><td align='left' class='img_content' id='"+im+"_1' valign='top'><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' width='"+img_size_2+"' height='"+img_size_2+"'  name='1' border='0'/><div class='resize_div ' style='width: "+img_size_2+"px; height: "+img_size_2+"px;'><div  class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' width = '16' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td></tr></table></td></tr></table>");
		jQuery("#"+top_div).remove();
		var handler_block=handler.replace('[colspan]','1');
		$('#'+im).find('.handler_div').prepend(handler_block);
		$('#'+im).find('.resize_div').prepend(handler_image1);
		loadImageSize(im);
		imageResize(im);
		pagechange=true;	//Page content change
	}else if(img_count==2){
		var im='block_'+new Date().getTime();		
		$('.empty_block').removeClass('empty_block');
		jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+im+"'><tr><td  class='handler_div'><table  align='center' class='resize_table' cellspacing='0' cellpadding='0'><tr><td align='left' class='img_content' id='"+im+"_1' valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' width='"+img_size_2+"' height='"+img_size_2+"' name='1' border='0' /><div class='resize_div ' style='width: "+img_size_2+"px; height: "+img_size_2+"px; '><div  class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' class='img_content' id='"+im+"_2' valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0'  width='"+img_size_2+"' height='"+img_size_2+"'  name='1' /><div class='resize_div ' style='width: "+img_size_2+"px; height: "+img_size_2+"px;'><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td></tr></table></td></tr></table>");
		jQuery("#"+top_div).remove();
		var handler_block=handler.replace('[colspan]','2');
		$('#'+im).find('.handler_div').prepend(handler_block);
		$('#'+im+'_1').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_2').find('.resize_div').prepend(handler_image2);
		loadImageSize(im);
		imageResize(im);
		pagechange=true;	//Page content change
	}else if(img_count==3){
		var im='block_'+new Date().getTime();		
		$('.empty_block').removeClass('empty_block');
		jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+im+"'><tr><td  class='handler_div'><table  align='center' class='resize_table' cellspacing='0' cellpadding='0'><tr><td align='left' class='img_content' id='"+im+"_1'  valign='top'><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' width='"+img_size_3+"' height='"+img_size_3+"' border='0' name='1' /><div class='resize_div ' style='width: "+img_size_3+"px; height: "+img_size_3+"px; '><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' class='img_content' id='"+im+"_2'  valign='top'><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0' width='"+img_size_3+"' height='"+img_size_3+"' name='1' /><div class='resize_div ' style=' width: "+img_size_3+"px; height: "+img_size_3+"px;'><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' id='"+im+"_3' class='img_content'   valign='top'><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0' width='"+img_size_3+"' height='"+img_size_3+"' name='1' /><div class='resize_div ' style='width: "+img_size_3+"px; height: "+img_size_3+"px;'><div  class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td></tr></table></td></tr></table>");
		jQuery("#"+top_div).remove();
		var handler_block=handler.replace('[colspan]','3');
		$('#'+im).find('.handler_div').prepend(handler_block);
		$('#'+im+'_1').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_2').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_3').find('.resize_div').prepend(handler_image2);
		loadImageSize(im);
		imageResize(im);
		pagechange=true;	//Page content change
	}else if(img_count==4){
		var im='block_'+new Date().getTime();
		$('.empty_block').removeClass('empty_block');		
		jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div' id='"+im+"'><tr><td  class='handler_div'><table  align='center' class='resize_table' cellspacing='0' cellpadding='0'><tr><td align='left' id='"+im+"_1' class='img_content' valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' width='"+img_size_4+"' height='"+img_size_4+"' class='image-container drop-image' alt='Campaign-Image' border='0'  name='1'/><div class='resize_div ' style='width: "+img_size_4+"px; height: "+img_size_4+"px;'><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' id='"+im+"_2' class='img_content'  valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0' width='"+img_size_4+"' height='"+img_size_4+"'  name='1'/><div class='resize_div ' style='width: "+img_size_4+"px; height: "+img_size_4+"px; '><div  class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' id='"+im+"_3' class='img_content'  valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0' width='"+img_size_4+"' height='"+img_size_4+"' name='1'/><div class='resize_div ' style=' width: "+img_size_4+"px; height: "+img_size_4+"px; left: 0px; top: 0px;'><div  class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td><td align='left' id='"+im+"_4' class='img_content'  valign='top' ><div class='position_div'><img src='<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0' class='image-container drop-image' alt='Campaign-Image' border='0' width='"+img_size_4+"' height='"+img_size_4+"' name='1'/><div class='resize_div ' style='width: "+img_size_4+"px; height: "+img_size_4+"px;'><div class='div_border'><span style='display:none;' class='drop_div_border'></span></div><div  class='highlight_on_image_hover'><span style='display:none;' class='drop_div_border'></span></div></div></div><div class=\"active_image_option\"><span class='img_link_span'><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/></span></div><div><span class='image_caption'></span></div></td></tr></table></td></tr></table>");
		jQuery("#"+top_div).remove();
		var handler_block=handler.replace('[colspan]','4');
		$('#'+im).find('.handler_div').prepend(handler_block);
		$('#'+im+'_1').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_2').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_3').find('.resize_div').prepend(handler_image4);
		$('#'+im+'_4').find('.resize_div').prepend(handler_image4);
		loadImageSize(im);
		imageResize(im);
		pagechange=true;	//Page content change
	}
	$('.empty_block').removeClass('empty_block');
	$.modalBox.close();
	setTimeout('dragDropImageBank()', 1000);
}
/**
	Function CloseImageGroupBlock to close image group popup
**/
function CloseImageGroupBlock(){
	$('.diy_demo_video').remove();
	if($("#current_container_id").val()!=""){
		var top_div=$("#current_container_id").val();
		jQuery("#"+top_div).remove();
	}
}
/**
	Function socialMediaUrlSubmit to save social media url
**/
function addSM(){
	var social_media_link="";
	var top_div="";
	var colspan=0;
	if($("#current_container_id").val()!=""){
		top_div=$("#current_container_id").val();
		$("#current_container_id").val("");
	}
	var sm = ['facebook', 'twitter', 'linkedin', 'rss', 'youtube', 'google_plus', 'tumblr', 'flickr', 'pinterest', 'instagram', 'mailto', 'skype', 'website']; 
	for(var i = 0; i < sm.length; i++){
		var smv = sm[i]; 
		var smid = i + 1;	
		if(($('#messageBox').find('#'+smv+'_link:checked').val())&&($('#messageBox').find('#'+smv+'_url').val())){
			var sm_url = $('#messageBox').find("#"+smv+"_url").val();			 	
			sm_url = ( -1 == sm_url.indexOf('http'))? 'http://'+sm_url : sm_url;
			colspan++;
			
			social_media_link+="<td class='social_li' valign='top'><a target='_blank' class='social_media_link "+smv+"_url_link' name='"+sm_url+"'><img src='<?php echo $this->config->item('locker');?>images/icons/"+smv+"-share.png' alt='"+smv+"'  title='"+sm_url+"' border='0'/></a> </td>";
			jQuery.ajax({url: "<?php echo base_url() ?>promotions/add_member_sm/"+smid+"/"+sm_url, type:"POST", data: { "smid": smid, "smurl": sm_url },	success: function(data) {} });
		}
	}
	if(social_media_link!=""){
		if(top_div!=""){
			var social_block_id='block_'+new Date().getTime();
			
			$('#'+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div ' align='center' valign='middle' id='"+social_block_id+"'><tr><td align='center'  class='handler_div'><table  cellspacing='4' cellpadding='0' valign='top'><tr>"+social_media_link+"</tr></table></td></tr></table>");
			$('#'+top_div).remove();
			var handler_block=handler.replace('[colspan]','1');
			$('#'+social_block_id).find('.handler_div').prepend(handler_block);
			$('#'+social_block_id).find('.handler').addClass('handler_social_media');
			$('#'+social_block_id).find('.handler').find('.drag-center').addClass('drag_social_li');
			$('#'+social_block_id).find('.handler').find('.close-link').parent().after('<li><a title="Edit"  class="edit_sm">edit</a></li>');
		}
		$('.empty_block').removeClass('empty_block');		//remove empty block from conatiner
	}
	$.modalBox.close();		// close popup
	pagechange=true;	//Page content change
}
 
/**
	Function checkYoutubevideoOrVimeovideo to check enter url is for youtube or for vimeo video
**/
function checkYoutubevideoOrVimeovideo(){
	var url="";
	url=$('#messageBox').find("#youtube_url").val();
	var matches = url.match(/^(https?:\/\/)?([^\/]*\.)?youtube\.com\/watch\?([^]*&)?v=\w+(&[^]*)?/i);
	if(matches){
		youtubeUrlUpdate()
	}else{
		vimeoVideoUrlUpdate();
	}
}
/**
	function youtubeUrlUupdate to save youtube url
**/
function youtubeUrlUpdate(){
$('#messageBox').find('.youtube_msg').show();
$('#messageBox').find('.youtube_msg').html("Please wait...");
	var url="";
	url=$('#messageBox').find("#youtube_url").val();
	var matches = url.match(/^(https?:\/\/)?([^\/]*\.)?youtube\.com\/watch\?([^]*&)?v=\w+(&[^]*)?/i);
	if(matches){
		var top_div_arr=$("#current_container_id").val().split('_');
		var top_div=top_div_arr[0];
		if(top_div_arr[1]=="edit"){
			$('#'+top_div).html('');
		}
		if(url!=""){
			var img_id='block_'+new Date().getTime();
			$("#"+top_div).attr('name',url);
			var video_id =url.split('v=')[1];
			var ampersandPosition = video_id.indexOf('&');
			if(ampersandPosition != -1) {
				video_id = video_id.substring(0, ampersandPosition);
			}
			// http://salman-w.blogspot.in/2010/01/retrieve-youtube-video-title.html
			var ytApiKey = "AIzaSyAlDZx2H4e1r035CjWftUHNJQr_iu8P0BM";			 
			$.getJSON("https://www.googleapis.com/youtube/v3/videos", {
					key: ytApiKey,
					part: "snippet,statistics",
					id: video_id
				}, function(data) {
					if (data.items.length === 0) {
					}else{
						youtubeFeedCallback(data);
					}
				});	
		}else if($('#'+top_div).find('.youtube_link').length<=0){
			$("#"+top_div).remove();
			$.modalBox.close();
		}
	}else{
		$('#messageBox').find('.youtube_msg').show();
		$('#messageBox').find('.youtube_msg').html("Please Enter Valid url");
		setTimeout( function(){$('#messageBox').find('.youtube_msg').fadeOut();} , 4000);
	}
	pagechange=true;	//Page content change
}
/**
	Function to display youtube image and title
**/
function youtubeFeedCallback(json){
	if(typeof json["error"] !== 'undefined'){
		close_youtube();
		displayAlertMessage('','<div style="display:block;margin:20px;">Right now, we are unable to get video details for this URL.</div>','0',true,480,250,false,'fn:removeGarbage');
	}else{
	$('.diy_demo_video').remove();
	$('#body_main').removeClass('empty_block');
	var top_div_arr=$("#current_container_id").val().split('_');
	var top_div=top_div_arr[0];
	var ybid=new Date().getTime();	
	var img_src=json.items[0].snippet.thumbnails.medium.url; 
	img_src = img_src.replace('http://','https://');	
	var youtube_link=$("#"+top_div).attr('name');
	
	$("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div youtube_container' id='"+ybid+"' name='"+youtube_link+"'><tr><td  class='handler_div'  align='center'><table style='width:550px;border-spacing:0px;border:none;' cellspacing='0' cellpadding='0' border='0' align='center' class='resize_table'><tr><td align='center' class='img_content' id='"+ybid+"_1' valign='top'><div class='position_div' style=\"position:relative;text-align:center;display:inline-block;\"><img src='"+img_src+"' class='image-container drop-image' alt='Campaign-video' width='300'  height='176' border='0' /><div class='video_play' style=\"position: absolute;top: 80px;left: 103px;width: 58px;height: 41px;background-image: url('<?php echo $this->config->item('locker');?>images/play.png?v=6-20-13')\"></div><div class='resize_div ' style='position: absolute; text-align: center; width: 300px; height: 0px; left: 0px; top: 0px; z-index: 10; border-color: transparent;'><div style='display: block; position: absolute; top: -1px; bottom: -1px; right: -1px; left: -1px; border: 1px solid rgb(240, 240, 240);' class='div_border'><span style='display:none;' class='drop_div_border'></span></div></div></div><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/><span class='youtube_image_caption'></span></td></tr></table></td></tr></table>");
	var handler_block=handler.replace('[colspan]','1');
	$('#'+ybid).find('.handler_div').prepend(handler_block);
	$('#'+ybid).find('.resize_div').prepend(handler_youtube);
	//$("#"+ybid).find('.youtube_image_caption').html(json["data"]["title"]);
	$("#"+ybid).find('.youtube_image_caption').html(json.items[0].snippet.title);
	$("#"+ybid).find('.image_link').attr('title',youtube_link);
	var delay2 = function() { youtubeImageAspectRatio(ybid+'_1'); };
	setTimeout(delay2, 1000);
	$.modalBox.close();
	$('#'+top_div).remove();
	pagechange=true;	//Page content change
	}
}
/**
	function youtubeImageAspectRatio to calculate youtube image aspect ratio
**/
function youtubeImageAspectRatio(youtube_img_id){
	var width=$('#'+youtube_img_id).find('.image-container').width();
	var height=$('#'+youtube_img_id).find('.image-container').height();

	if((width>0)&&(height>0)){
		var aspect_ratio=width/height;
		$('#'+youtube_img_id).find('.image-container').attr('name',aspect_ratio);
		var delay1 = function() { loadImageSize(youtube_img_id); };
		setTimeout(delay1, 2000);
		var delay = function() { imageResize(youtube_img_id); };
		setTimeout(delay, 2000);
		$('#'+youtube_img_id).find('.video_play').css({'top':(height-41)/2+'px','left':(width-58)/2+'px'});
	}else{
		youtubeImageAspectRatio(youtube_img_id);
	}
}
 
/**
	Function vimeoVideoUrlUpdate to add vimeo video on campaign
**/
function vimeoVideoUrlUpdate(){
	var url="";
	url=$('#messageBox').find("#youtube_url").val();
	var vimeo_url="http://vimeo.com/";
	var video_id=url.substring(vimeo_url.length);
	jQuery.ajax({
		url: "<?php echo base_url() ?>ajax/get_vimeo_video_image/"+video_id,
		type:"POST",
		success: function(data) {
			var data_obj=jQuery.parseJSON(data);
			if(data_obj.error){
				jQuery('#messageBox').find('.youtube_msg').show();
				jQuery('#messageBox').find('.youtube_msg').html(data_obj.error);
				setTimeout( function(){$('#messageBox').find('.youtube_msg').fadeOut();} , 4000);
			}else{
				var vimeo_video_url=data_obj.image_path;
				jQuery('#body_main').removeClass('empty_block');
				var top_div_arr=$("#current_container_id").val().split('_');
				var top_div=top_div_arr[0];
				var vmid=new Date().getTime();				
				jQuery("#"+top_div).after("<table width='100%' cellspacing='0' cellpadding='0' class='container-div youtube_container' id='"+vmid+"' name='"+url+"'><tr><td  class='handler_div'><table style='width:550px;' border='0' align='center' class='resize_table'><tr><td align='center' class='img_content' id='"+vmid+"_1' valign='top'><div class='position_div' style=\"position:relative;text-align:center;display:inline-block;\"><img src='"+vimeo_video_url+"' class='image-container drop-image' alt='Campaign-video' width='300' height='176' border='0' /><div class='video_play' style=\"position: absolute;top: 80px;left: 103px;width: 58px;height: 41px;background-image: url('<?php echo $this->config->item('locker');?>images/play.png?v=6-20-13')\"></div><div class='resize_div ' style='position: absolute; text-align: center; width: 300px; height: 0px; left: 0px; top: 0px; z-index: 10; border-color: transparent;'><div style='display: block; position: absolute; top: -1px; bottom: -1px; right: -1px; left: -1px; border: 1px solid rgb(240, 240, 240);' class='div_border'><span style='display:none;' class='drop_div_border'></span></div></div></div><img src='<?php echo base_url() ?>locker/images/icons/add_link.png' class='image_link' style='display:none;'/><span class='youtube_image_caption'></span></td></tr></table></td></tr></table>");
				var handler_block=handler.replace('[colspan]','1');
				jQuery('#'+vmid).find('.handler_div').prepend(handler_block);
				jQuery('#'+vmid).find('.resize_div').prepend(handler_youtube);
				jQuery("#"+vmid).find('.youtube_image_caption').html(data_obj.title);
				jQuery("#"+vmid).find('.image_link').attr('title',url);
				var delay2 = function() { youtubeImageAspectRatio(vmid+'_1'); };
				setTimeout(delay2, 1000);
				$.modalBox.close();
				$('.diy_demo_video').remove();
				$('#'+top_div).remove();
				pagechange=true; 	//Page content change
			}
		}
	});
}
/**
	Function saveImageCaption to add caption on image
**/
function saveImageCaption(){
	var image_caption=$('#messageBox').find("#image_link_caption").val();
	var img_conatiner_id=$('#active_block').parents('.img_content').attr('id');
	$('#'+img_conatiner_id).find('.image_caption').html(image_caption);
	$('#active_block').attr("id","");
	image_block_id=$('#'+img_conatiner_id).parents('.container-div').attr('id');
	// ----------------------
	var container_min_height =$('#'+image_block_id).innerHeight();
	$('#'+image_block_id).find('.text-paragraph-container').css('min-height',container_min_height+'px');
	// ----------------------

	$.modalBox.close();
	pagechange=true;	//Page content change
}
/**
	Function to add link on image
**/
function saveImageLink(){
	var image_link="";
	if( -1 == $('#messageBox').find("#image_link").val().indexOf('http') ){
		image_link ='http://'+$('#messageBox').find("#image_link").val();
	}else{
		image_link =$('#messageBox').find("#image_link").val();
	}
	if($('#messageBox').find("#image_link").val()){
		var img_conatiner_id=$('#active_block').parents('.img_content').attr('id');
		$('#'+img_conatiner_id).find('.image_link').attr("title",image_link);
		$('#'+img_conatiner_id).find('.image_link').attr("name",image_link);
		$('#'+img_conatiner_id).find('.image_link').show();
	}else{
		var img_conatiner_id=$('#active_block').parents('.img_content').attr('id');
		$('#'+img_conatiner_id).find('.image_link').hide();
		$('#'+img_conatiner_id).find('.image_link').attr("title","");
		$('#'+img_conatiner_id).find('.image_link').attr("name","");
	}
	$('#active_block').attr("id","");
	$.modalBox.close();
	pagechange=true;	//Page content change
}

/**
	Function loadHeaderEffect to load header effects
**/
function loadHeaderEffect(thisBID = 'header'){
	$('#'+thisBID).find('.header_div').find('.menu').remove();
	$('#'+thisBID).find('.header_div').find('.resize_header_div').remove();
	$('#'+thisBID).find('.header_div').find('.resize_div').remove();
	$('#'+thisBID).find('.header_div').prepend(handler_header);
	 
	 
	
	$('#'+thisBID).find('.header_link').attr('lang',thisBID);
	$('#'+thisBID).find('.select_theme').attr('lang',thisBID);
	$('#'+thisBID).find('.add_logo').attr('lang',thisBID);
	$('#'+thisBID).find('.header_unlink').attr('lang',thisBID);
	
	
	$('#'+thisBID).find('.header_div').append(header_border);
	// If header is empty then load drop-header image
	if($('.empty_header').length>0){
		if(jQuery("#current_template_id").val()!=-1){
			var header_img_path='<div class="banner_div ui_droppable"><img src="<?php echo $this->config->item('locker');?>header-images/header-'+jQuery("#current_template_id").val()+'.jpg" border="0" class="image-container header_img" alt="campaign header"  /></div>';
			alert(header_img_path);
			jQuery('#'+thisBID).find('.header_div').append(header_img_path);
			jQuery('.empty_header').remove();
		}else{
			$('#'+thisBID).find('.empty_header').html('<img src="<?php echo $this->config->item('locker');?>images/contentBuilderHeader.jpg?v=1.0" />');
			$('#'+thisBID).find('.header_link').css('display','none');
			$('#'+thisBID).find('.add_logo').css('display','none');
			$('#'+thisBID).find('#logo').remove();
			$('#'+thisBID).find('#header_link').remove();
			$('#'+thisBID).find('.header_link').parent().remove();
			$('#'+thisBID).find('.add_logo').parent().remove();
 
			$('#'+thisBID).find('.header_unlink').remove();
			$('#'+thisBID).find('.header_unlink').parent().remove();
		}
	}
	var delay = function() { setHeaderHeight(); };
	setTimeout(delay, 1000);
}
/**
	Function setHeaderHeight to set header height
**/
function setHeaderHeight(){
	var img_height=0;
	if($('.empty_header').length>0){
		img_height=$('.empty_header').find('img').height();
	}else{
		img_height=$('.header_img').height();
	}
	$('#header').find('.resize_header_div').width('595');
	$('#header').find('.resize_header_div').height(img_height);
	$('.header_img').attr('height',img_height);
	$('.header_img').attr('width','595');
}
/**
	Function dragDropImageBank to drag image from image bank and drop them to Image block or to Header of email conatainer
**/
function dragDropImageBank(){
	jQuery( ".draggable1").draggable({
		helper:'clone',
		containment: '#main-table',
		appendTo: "#body_main",
		snap:true,
		zIndex:15,
		revert: false,
		cursor:'move',
		drag: function(event, ui) {
			jQuery('.handler_img').hide();
			//jQuery('.handler').hide();
			jQuery('.container-div').removeClass('highlighted');
		}
	});
	jQuery( ".position_div,#header" ).droppable({
		'tolerance':'touch',
		accept:".draggable1",
		over: function(event, ui) {
			jQuery(this).find('.resize_div').find('.highlight_on_image_hover').css("border","5px solid #808080");
			jQuery(this).find('.resize_div').show();
			jQuery(this).find('.resize_header_div').find('.div_border').css("border","5px solid #808080");
			jQuery(this).find('.resize_header_div').show();
			jQuery(this).find('.resize_div_text').find('.highlight_on_image_hover').css("border","5px solid #808080");
			jQuery(this).find('.resize_div_text').show();
		},
		out: function(event, ui) {
			jQuery(this).find('.resize_div').find('.highlight_on_image_hover').css("border","none");
			jQuery(this).find('.resize_div').hide();
			jQuery(this).find('.resize_div_text').find('.highlight_on_image_hover').css("border","none");
			jQuery(this).find('.resize_header_div').find('.div_border').css("border","none");
			jQuery(this).find('.resize_header_div').hide();
			jQuery(this).find('.resize_div_text').hide();
			jQuery('#header').css("border","none");
		},
		drop: function( event, ui ) {
			jQuery(this).find('.resize_div').find('.div_border').css("border","1px solid #808080");
			jQuery(this).find('.resize_div_text').find('.div_border').css("border","1px solid #808080");
			jQuery(this).find('.resize_header_div').find('.div_border').css("border","1px solid #808080");
			jQuery(this).find('.resize_div').find('.highlight_on_image_hover').css("border","none");
			jQuery(this).find('.resize_div_text').find('.highlight_on_image_hover').css("border","none");
			jQuery('#header').css("border","none");
			jQuery(this).find('.resize_div').hide();
			jQuery(this).find('.resize_div_text').hide();
			jQuery(this).find('.resize_header_div').hide();
			var image_block=ui.draggable.clone();
			var img_src=jQuery(image_block).attr('src');
			if(jQuery(this).find('.header_div').length>0){
				var header_img='<div class="banner_div ui_droppable"><img border="0" class="image-container  header_img" src="'+img_src+'" width="595" height="auto" alt="campaign header" /></div>';
				jQuery(this).find('.header_img').remove();
				jQuery('#header').append(header_img);
				jQuery(this).find('.empty_header').remove();
				jQuery(this).find('.header_div').find('.menu').remove();
				jQuery(this).find('.header_div').prepend(handler_header);	
				var delay = function() { setHeaderHeight(); };
				setTimeout(delay, 1000);
			}else{
				jQuery(this).find('.image-container').attr('src',img_src);
				var img_info=jQuery(image_block).attr('name');
				var img_array=img_info.split(",",3);
				var aspect_ratio=parseFloat(img_array[1]/img_array[2]);
				jQuery(this).find('.image-container').attr('name',aspect_ratio);
				var img_filename = img_src.substring(img_src.lastIndexOf("-")+1,img_src.lastIndexOf("."));
				jQuery(this).find('.image-container').attr('alt',img_filename);
				var element_id=jQuery(this).parents('.container-div').attr('id');
				loadImageSize(element_id);
			}
			pagechange=true;	//Page content change
		}
	});
}
function organizeImageBank() {
	$('.img-bank').masonry({
    itemSelector : '.li_draggable',
    columnWidth : 112
  });
	$('.img-bank').find("img").each(function() {
		$(this).load(function() {
			$('.img-bank').masonry().masonry('reload');
		});
	});
}
/**
	Function loadImageEffect to calculate width and height of drop image according to aspect ratio
**/
function loadImageEffect(img_obj){
	var width=img_obj.width();
	var height=img_obj.height();
	var aspect_ratio=img_obj.attr('name');
	var new_height=width/aspect_ratio;
	img_obj.height(new_height);
}
/**
	Function resizeCloneImages to calculate width and height of clone images according to aspect ratio
**/
function resizeCloneImages(div_id,clone_length){
	var element_obj=$('#'+div_id).find('.img_content');
	if(clone_length==1){//image-container drop-image
		element_obj.each(function() {
			var width= img_size_2;
			var aspect_ratio=$(this).find('.image-container').attr('name');
			var new_height=width/aspect_ratio;
			$(this).find('.image-container').height(new_height);
			$(this).find('.image-container').width(width);
		});
	}else if(clone_length==2){
		element_obj.each(function() {
			var width= img_size_2;
			var aspect_ratio=$(this).find('.image-container').attr('name');
			var new_height=width/aspect_ratio;
			$(this).find('.image-container').height(new_height);
			$(this).find('.image-container').width(width);
		});
	}else if(clone_length==3){
		element_obj.each(function() {
			var width= img_size_3;
			var aspect_ratio=$(this).find('.image-container').attr('name');
			var new_height=width/aspect_ratio;
			$(this).find('.image-container').height(new_height);
			$(this).find('.image-container').width(width);
		});
	}else if(clone_length==4){
		element_obj.each(function() {
			var width= img_size_4;
			var aspect_ratio=$(this).find('.image-container').attr('name');
			var new_height=width/aspect_ratio;
			$(this).find('.image-container').height(new_height);
			$(this).find('.image-container').width(width);
		});
	}
}



/**
	Function saveHeader to change Header Image
**/
saveHeader=function(template_id,thisBid){
	jQuery('#'+thisBid).find('.header_img').remove();
	var header_img_path='<div class="banner_div ui_droppable"><img src="<?php echo $this->config->item('locker');?>images/template-headers/'+template_id+'.jpg" border="0" class="image-container header_img" alt="campaign header" width="595" height="auto" /></div>';
	jQuery('#'+thisBid).find('.header_div').append(header_img_path);
	jQuery('#'+thisBid).find('.empty_header').remove();
	jQuery('#'+thisBid).find('.header_div').find('.menu').remove();
	jQuery('#'+thisBid).find('.header_div').prepend(handler_header);
	
	jQuery('#'+thisBid).find('.header_link').attr('lang',thisBid);
	jQuery('#'+thisBid).find('.select_theme').attr('lang',thisBid);
	jQuery('#'+thisBid).find('.add_logo').attr('lang',thisBid);
	jQuery('#'+thisBid).find('.header_unlink').attr('lang',thisBid);
	
	jQuery.modalBox.close();
	pagechange=true;	//Page content change
	var delay = function() { setHeaderHeight(); };
	setTimeout(delay, 1000);
}

// Function to add link on header
function addHeaderLink(){
	var bid = jQuery('#active_header').attr('lang');
	jQuery('#active_header').attr('id','');
	
	if($('#messageBox').find("#header_link_text").val()){
		setTimeout(jQuery('#'+bid).find('.header_div').find('#header_link').empty().remove(),1000);
		var link_url = $('#messageBox').find("#header_link_text").val();
		link_url =( -1 == link_url.indexOf('http') )? 'http://'+ link_url : link_url;	
		jQuery('#'+bid).find('.header_div').prepend('<div id="header_link"><img class="header_link_show" name="'+link_url+'"  title="'+link_url+'" src="<?php echo base_url();?>locker/images/icons/add_link.png"/></div>');
	}else{
		jQuery('#'+bid).find('#header_link').remove();
	}
	jQuery.modalBox.close();
	pagechange=true;	//Page content change
}

/**
*	Function unlinkImageBank to remove image from image bank
*	param (int) (img_id)  image id
*/
function unlinkImageBank(img_id){
	jQuery.ajax({
		url: "<?php echo base_url() ?>ajax/unlink_image_bank/"+img_id,
		type:"POST",
		success: function(data) {
			jQuery("#"+img_id).parents('.li_draggable').remove();
			if($('.image_bank_div').length<=0){
				var no_img='<li class="load_images"><b>Click "Upload Images" button to upload your images</b></li>';
				$('.img-bank').html(no_img);
			}
		}
	});
}

/**
	Function loadFooterEffect to load header effects
**/
function loadFooterEffect(){
	if(jQuery('#footer').find('.footer_menu').length>0){
		jQuery('#footer').find('.footer_menu').remove();
	}
	$('#footer').prepend(handler_footer);
	var address=$('.address').html().replace(/&nbsp;/g, "");
	$('.address').html(address);
	$('.address').parent().css("margin-left","0px");
	$('.copyright').html('&copy; ');
}

function close_block(){
	$('#active_block').attr('id','');
	$('#active_header').attr('id','');
	$('#active_logo').attr('id','');
	$('#active_image_bank').attr('id','');
	$('#active_theme_color').attr('id','');
}
/**
*	Function toolboxDisplay to display toolbox blocks
*
**/
function toolboxDisplay(){
	$('.toolbox').addClass('selected');
	$('.imagebank').removeClass('selected');
	$('.color').removeClass('selected');
	$('#one').show();
	$('#two').hide();
	$('#three').hide();
}
/**
	Function imageBankDisplay to display image bank images for login user
**/
function imageBankDisplay(){
	$('.toolbox').removeClass('selected');		//Unhighlight toolbox tab
	$('.color').removeClass('selected');		//Unhighlight colorbox tab
	$('.imagebank').addClass('selected');		//Hightlight imagebank tab
	$('#one').hide();
	$('#two').show();
	$('#three').hide();		
	jQuery.ajax({
			url: "<?php echo base_url() ?>promotions/get_image_bank_for_ajax",
			type:"POST",
			success: function(data) {
				$('.img-bank').html(data);
				organizeImageBank();
				dragDropImageBank();
			}
	});
	var delay = function() { setHeaderHeight(); };
	setTimeout(delay, 1000);
}

/**
	Function colorboxDisplay to display colors options
**/
function colorboxDisplay(){
	$('#custom-theme').css('display','none');
	$('.colorstab li').removeClass('active');
	$($('.colorstab li').get(0)).addClass('active');
	$('.toolbox').removeClass('selected');
	$('.imagebank').removeClass('selected');
	$('.color').addClass('selected');
	$('#one').hide();
	$('#two').hide();
	$('#three').show();	
		jQuery.ajax({
			url: "<?php echo base_url() ?>promotions/get_theme_colors",
			type:"POST",
			success: function(data) {
				$('#default_theme_color').html(data);
			}
	});
	

	$('#custom-theme').slideUp();
	$('#default-theme').slideDown();
}

/**
	Function to save imagebank's images url
**/
function save_image_bank_url(){
	var image_url=$('#messageBox').find('#image_bank_url').attr('value');
	var image_data="image_url="+image_url;
	jQuery.ajax({
		url: "<?php echo base_url() ?>ajax/copy_image_bank_image_url",
		type:"POST",
		data:image_data,
		success: function(data) {
			var image_obj=jQuery.parseJSON(data);
			error=image_obj.error;
			if(error=="true"){
				$('#messageBox').find('.img_upload_msg').show();
				$('#messageBox').find('.img_upload_msg').html(image_obj.error_msg);
				$('#messageBox').find('.img_upload_msg').addClass('error');
			}else{
				$('#messageBox').find('.img_upload_msg').hide();
				var img_path=image_obj.file_path;
				var img_height=image_obj.height;
				var img_width=image_obj.width;
				var img_id=image_obj.img_id;
				var thumb_img_path=image_obj.thumb_image_path;
				var li_class_name=$('#messageBox').find(".img-bank  li:last-child").attr("class");
				var li_name=img_path+','+img_width+','+img_height;
				li_class_name='class="li_draggable" ';
				var img_remove='<div  class="del_image_link"><a href="javascript:void(0);"  class="remove-img-link image_bank_unlink" id="'+img_id+'" title="Delete"><i class="icon-remove"></i></a></div>';
				var img_html='<div  class="image_div"><img class="image_bank_div draggable1" src="'+img_path+'?a='+'block_'+new Date().getTime()+'" name="'+li_name+'"   /></div>';
				var thumb_img_html='<img  src="'+thumb_img_path+'"  />';
				parent.$('.load_images').remove();
				parent.$('.img-bank').append('<li '+li_class_name+'  title="Click & Drag"><div  class="img_slide" >'+img_remove+img_html+'</div></li>');
				$.modalBox.close();
			}
			dragDropImageBank();
		}
	});
}

/**
	Function to remove  color theme from colorbox
**/
function unlinkThemeColor(theme_id){
	jQuery.ajax({
		url: "<?php echo base_url() ?>ajax/unlink_theme_color/"+theme_id,
		type:"POST",
		success: function(data) {
			jQuery("#"+theme_id).remove();
		}
	});
}
/**
	Function changeStyle to change color or font style of email tempalte
**/
function changeFooterAlignment(){
	var al = $('#footer_alignment').val();
	$('.company_name').parent().css({'float':'none','text-align':al});
}
function changeStyle(element_id,color,element){		
	$style="";
	if(element=="font_color"){
		var cl = $('#messageBox').find("#"+color).val();
		$("."+element_id+"_font_style").remove();				
		$('#template_container').prepend("<style class='footer_font_style custome_style'>#"+element_id+"{color:"+cl+" !important}</style>");
		
	}else if(element=="font_size"){
		$("#"+element_id).css('font-size',$('#footer_font_size').val());
		$(".selected_font").css('font-size',$('#footer_font_size').val());
	}else if(element=="border_style"){
		if(jQuery("#body-options-border").val()=="thin"){
			$("#"+element_id).css('border-width',$('#body-options-border').val());
			$("#"+element_id).css('border-style','solid');
			$("#template_container").width('597');
			$("#email_template_table").attr('width','597');
		}
		else if(jQuery("#body-options-border").val()=="thick"){
			$("#"+element_id).css('border-width',$('#body-options-border').val());
			$("#"+element_id).css('border-style','solid');
			$("#template_container").width('605');
			$("#email_template_table").attr('width','605');
		}else if(jQuery("#body-options-border").val()=="solid"){
			$("#"+element_id).css('border-width','2px');
			$("#"+element_id).css('border-style',$('#body-options-border').val());
			$("#template_container").width('599');
			$("#email_template_table").attr('width','599');
		}else if(jQuery("#body-options-border").val()=="none"){
			$("#"+element_id).css('border-style',$('#body-options-border').val());
			$("#template_container").width('595');
			$("#email_template_table").attr('width','595');
		}else{
			if($("#"+element_id).attr('width')=='595'){
				$("#"+element_id).css('border-style','solid');
				$("#"+element_id).css('border-width','2px');
				$("#template_container").width('599');
				$("#email_template_table").attr('width','599');
			}
			$("#"+element_id).css('border-style',$('#body-options-border').val());
		}
	}else if(element=="border"){
		$(".border_style").remove();
		$style="<style class='border_style custome_style'>#"+element_id+"{border-color:#"+color+" !important}.body_border{background-color:#"+color+" !important}</style>";
		$('#template_container').prepend($style);
	}else{
		$("."+element_id+"_style").remove();
		if(element_id=="outer_bg"){			
			$style="<style class='"+element_id+"_style custome_style'>.outer_bg{background-color:#"+color+" !important}.diy-editor{background-color:#"+color+" !important;}#template_container{background-color:#"+color+" !important;}</style>";
		}else if(element_id=="body_main"){
			$style="<style class='"+element_id+"_style custome_style'>#"+element_id+"{background-color:#"+color+" !important}.body_bg_color{background-color:#"+color+" !important}</style>";
		}else if(element_id=="footer"){
			$style="<style class='"+element_id+"_style custome_style'>#"+element_id+"{background-color:#"+color+" !important}.footer_txt_color{background-color:#"+color+" !important}</style>";
		}else{
			$style="<style class='"+element_id+"_style custome_style'>#"+element_id+"{background-color:#"+color+" !important}</style>";
		}
		$('#template_container').prepend($style);
	}
	pagechange=true;	//Page content change
}

/**
	Function to add theme color in databse
**/
function saveThemeColor(){
	var block_data="";
	block_data='color_theme_name='+$("#color_theme_name").val();
	block_data+='&theme_body_color='+hexc($("#background_color_txt").css('background-color')).replace("#","");
	block_data+='&theme_outer_bg_color='+hexc($("#background_outer_txt").css('background-color')).replace("#","");	
	block_data+='&theme_border_color='+hexc($("#border_txt").css('background-color')).replace("#","");
	block_data+='&theme_footer_color='+hexc($("#footer_txt").css('background-color')).replace("#","");
	 
	jQuery.ajax({
		url: "<?php echo base_url() ?>ajax/add_color_theme",
		type:"POST",
		data:block_data,
		success: function(data) {
			var data_arr=data.split(":");
			if(data_arr[0]=="error"){
				$('.theme_color_info').show();
				$('.theme_color_info').html(data_arr[1]);
				$('.theme_color_info').addClass('info');
			}else{
				$("#color_theme_name").val('');
				colorboxDisplay();
			}
		}
	});
}
/**
	Function hexc to conver color from rgb() to # format
**/
function hexc(colorval) {
  var rgb = colorval;
  if (!rgb) return '#FFFFFF'; //default color
  var hex_rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  function hex(x) {
  	return ("0" + parseInt(x).toString(16)).slice(-2);
  }
  if (hex_rgb) return "#" + hex(hex_rgb[1]) + hex(hex_rgb[2]) + hex(hex_rgb[3]);
  else return rgb; //ie8 returns background-color in hex format
}
/**
	Function resetDefault to reset the colors of email container
**/
function resetDefault(){
	page_id=jQuery("#current_tab_page").val();
	jQuery.ajax({
		url: "<?php echo base_url() ?>page/resetColor/"+page_id+"/"+'block_'+new Date().getTime(),
		type:"POST",
		success: function(data) {

		}
	});
}



/**
	Function to save the email content
**/
function publishPageContent(page_id,email_campaign_id,preview){
	$('#body_main').find('.ui-sortable-helper').remove();
	//$('#body_main').find('.ui-draggable').remove();
	$('#body_main').find('.ui-state-highlight').remove();
	$('.diy_demo_video').remove();
	$('.gr-textarea-btn').remove();
	if(pagechange){
		var campaign_title=$('#campaign_title').val();
		if(campaign_title){
		// If campaign title is not empty then proceed to save the campaign			
			var campaign_content=encodeURIComponent($('#template_container').html());
			 
			
			var block_data="campaign_content="+campaign_content+"&campaign_title="+encodeURIComponent($('#campaign_title').val())+"&campaign_outer_bg="+encodeURIComponent($('.outer_bg').css('background-color'));

			var url="";
			if(campaign_text=="auotresponder"){
				url="<?php echo base_url() ?>promotions/save_content/"+page_id+"/"+email_campaign_id+"/1/"+'block_'+new Date().getTime();
			}else{
				url="<?php echo base_url() ?>page/publish_content/"+page_id+"/"+email_campaign_id+"/0/"+'block_'+new Date().getTime();
			}

			jQuery.ajax({
				url: url,
				type:"POST",
				data:block_data,				
				contentType: "application/x-www-form-urlencoded;charset=utf-8",
				success: function(data) {//alert(data);
					pagechange=false;
					//$('#temp').text(data);
					//alert('cys');
				//exit;
					if(preview==1){
						window.open($('#preview_alert').attr('href'),'_blank');
					}else if(preview==2){
						window.location=$('#next_step').attr('href');
					}
				}
			});			
		}else{	// If campaign title is empty then display error message
			alert("Please Enter Campaign Name");
		}
	}
}

/**
	Save Color theme for email campaign
**/
saveColorTheme=function(template_id,obj){
	header_image_change=1;
	campaign_color_theme_id=template_id;
	var url="";
	<?php if($is_auotresponder){ ?>
		url="<?php echo base_url() ?>userboard/autoresponder/change_theme/"+campaign_color_theme_id+"/"+email_campaign_id;
	<?php }else{ ?>
		url="<?php echo base_url() ?>promotions/change_theme/"+campaign_color_theme_id+"/"+email_campaign_id;
	<?php } ?>
	jQuery.ajax({
		url: url,
		success: function(data) {
			var url="";
			<?php if($is_auotresponder){ ?>
				url="<?php echo base_url() ?>userboard/autoresponder/get_theme_css/"+template_id+"/ajax";
			<?php }else{ ?>
				url="<?php echo base_url() ?>promotions/get_theme_css/"+template_id+"/ajax";
			<?php } ?>
			jQuery.ajax({
				url: url,
				success: function(data) {
					$('.custome_style').remove();
					$('#template_container').prepend(data);
					resetDefault();
					pagechange=true;	//Page content change
				}
			});
		}
	});
	jQuery('.save_campaign').removeClass('disable-link');	// enable save link
}

function loadImageSize(element_id){
	var elements=$('#'+element_id).find('.image-container');
	var element_length=$('#'+element_id).find('.image-container').length;
	var element_image_text=$('#'+element_id).find('.text_image');
	var height=0;
	if(element_image_text.length==1){
		height=200;
	}else if(element_length==1){
		height= img_size_2;
	}else if(element_length==2){
		height= img_size_2;
	}else if(element_length==3){
		height= img_size_3;
	}else if(element_length==4){
		height= img_size_4;
	}
	var calc_width=0;
	var min_height=0;

	if(element_image_text.length==1){
		elements.each(function() {
			// Calcultate Minmum height
			calc_width+=$(this).attr('name')*height+25;
		});
		if(calc_width>225){
			var set_width=false;
			while(!set_width){
				height--;
				calc_width=0;
				elements.each(function() {
					// Calcultate Minmum height
					calc_width+=$(this).attr('name')*height+25;
				});
				if(calc_width<=225){
					set_width=true;
				}
			}
			min_height=height;
		}else{
			min_height=height;
		}
	}else{
		elements.each(function() {
			// Calcultate Minmum height
			calc_width+=$(this).attr('name')*height;
		});
		var comapre_width=element_length*height;
		if(calc_width>comapre_width){
			var set_width=false;
			while(!set_width){
				height--;
				calc_width=0;
				elements.each(function() {
					// Calcultate Minmum height
					calc_width+=$(this).attr('name')*height;
				});
				if(calc_width<=comapre_width){
					set_width=true;
				}
			}
			min_height=height;
		}else{
			min_height=height;
		}
	}
	var i=1;
	elements.each(function() {
		//set Width and height to each image
		$(this).attr('width',min_height*$(this).attr('name'));
		$(this).attr('height',min_height);
		$(this).parent().find('.resize_div').width(min_height*$(this).attr('name'));
		//$(this).parent().parent().find('.active_image_option').width(min_height*$(this).attr('name'));
		$(this).parent().find('.resize_div').height(min_height);
		$(this).parent().find('.resize_div_text').width(min_height*$(this).attr('name'));
		$(this).parent().find('.resize_div_text').height(min_height);
		$(this).parents('.container-div').find('.resize_table').width(calc_width);

		if(i<element_length){
			$(this).parent().css('padding-right', img_gap+'px');
		}else{
			$(this).parent().css('padding-right','0px');
		}



		i++;
	});
	// ----------------------
	//alert($('#'+element_id).children('div.text-paragraph-container').css('min-height'));
	//$('#'+element_id).next('#div_'+element_id).css('min-height',(min_height+7)+'px');
	$('#'+element_id).find('div.text-paragraph-container').css('min-height',(min_height+7)+'px');
	// ----------------------

}
/**
	Function imageResize to resize images
**/
function imageResize(element_id){
	// Delete Resize classes
	if(element_id){
		$('#'+element_id).find('.resize_div').removeClass('ui-resizable');
		$('#'+element_id).find('.ui-resizable-handle').remove();
	}else{
		$('.resize_div').removeClass('ui-resizable');
		$('.resize_div').find('.ui-resizable-handle').remove();
		$('.resize_div_text').removeClass('ui-resizable');
		$('.resize_div_text').find('.ui-resizable-handle').remove();
	}
	/**
		Resize Image Block
	**/
	$('.resize_div').resizable({
		handles:'se',
		helper:'ui-state-helper',
		minWidth:70,
		minHeight:70,
		aspectRatio:true,
		resize:function(event,ui)
		{
			jQuery('.handler_img').hide();
			//jQuery('.handler').hide();
			jQuery('.container-div').removeClass('highlighted');
			jQuery('.div_border').hide();
			jQuery('.ui-resizable-handle').hide();
			/**
				calculate width and height of image block during resize event
			**/
			var parent_obj=jQuery(this).parents('.container-div');
			var width=jQuery(ui.helper).width();
			var height=jQuery(ui.helper).height();
			var elements=parent_obj.find('.image-container');
			var element_length=parent_obj.find('.image-container').length;
			var calc_height=0;
			if(element_length==1){
				calc_height=555;
			}else if(element_length==2){
				calc_height= img_size_2;
			}else if(element_length==3){
				calc_height= img_size_3;
			}else if(element_length==4){
				calc_height= img_size_4;
			}
			var calc_width=0;
			var min_height=0;
			elements.each(function() {
				// Calcultate Minmum height
				calc_width+=$(this).attr('name')*height;
			});
			var comapre_width=element_length*calc_height;
			if(calc_width>comapre_width){
				var set_width=false;
				while(!set_width){
					height--;
					calc_width=0;
					elements.each(function() {
						// Calcultate Minmum height
						calc_width+=$(this).attr('name')*height;
					});
					if(calc_width<=comapre_width){
						set_width=true;
					}
				}
				min_height=height;
			}else{
				min_height=height;
			}
			/**
				calculate gap between images
			**/
			var gap=0;
			if(element_length>1){
				gap=1*(element_length-1);
			} 
			var parent_obj_id=parent_obj.attr('id');

			jQuery('#'+parent_obj_id+"_1").find('.handler_img_width_height').html(parseInt(calc_width+gap)+"X"+min_height);
			jQuery('#'+parent_obj_id+"_1").find('.handler_img_width_height').show();

			$('#'+parent_obj_id).find('.video_play').css({'top':(min_height-41)/2+'px','left':(calc_width-58)/2+'px'});
		},
		stop:function(event,ui)
		{
			var parent_obj=jQuery(this).parents('.container-div');
			var width=jQuery(ui.helper).width();
			var height=jQuery(ui.helper).height();
			var elements=parent_obj.find('.image-container');
			var element_length=parent_obj.find('.image-container').length;
			var calc_height=0;
			if(element_length==1){
				calc_height=555;
			}else if(element_length==2){
				calc_height= img_size_2;
			}else if(element_length==3){
				calc_height= img_size_3;
			}else if(element_length==4){
				calc_height= img_size_4;
			}
			var calc_width=0;
			var min_height=0;
			elements.each(function() {
				// Calcultate Minimum height
				calc_width+=$(this).attr('name')*height;
			});
			var comapre_width=element_length*calc_height;
			if(calc_width>comapre_width){
				var set_width=false;
				while(!set_width){
					height--;
					calc_width=0;
					elements.each(function() {
						// Calcultate Minmum height
						calc_width+=$(this).attr('name')*height;
					});
					if(calc_width<=comapre_width){
						set_width=true;
					}
				}
				min_height=height;
			}else{
				min_height=height;
			}
			var i=1;
			elements.each(function() {
				//set Width and height to each image
				$(this).attr('width',min_height*$(this).attr('name'));
				$(this).attr('height',min_height);
				$(this).parent().find('.resize_div').width(min_height*$(this).attr('name'));
				$(this).parents('.container-div').find('.resize_table').width(calc_width);
				//$(this).parent().parent().find('.active_image_option').width(min_height*$(this).attr('name'));
				$(this).parent().find('.resize_div').height(min_height);
				if(i<element_length){
					$(this).parent().css('padding-right',img_gap+'px');
				}else{
					$(this).parent().css('padding-right','0px');
				}
				i++;
			});
			jQuery('.div_border').show();
			var parent_obj_id=parent_obj.attr('id');
			jQuery('#'+parent_obj_id+"_1").find('.handler_img_width_height').hide();
			pagechange=true;	//Page content change
		}
	});

	/**
		Resize Image Text Block
	**/
	$('.resize_div_text').resizable({
		handles:'se',
		helper:'ui-state-helper',
		minWidth:77,
		minHeight:77,
		aspectRatio:true,
		resize:function(event,ui)
		{
			jQuery('.handler_img').hide();
			//jQuery('.handler').hide();
			jQuery('.container-div').removeClass('highlighted');
			jQuery('.div_border').hide();
			jQuery('.ui-resizable-handle').hide();
			jQuery(this).find('.handler_img_width_height').show();
			var parent_obj=jQuery(this).parents('.container-div');
			var width=jQuery(ui.helper).width();
			var height=jQuery(ui.helper).height();
			var elements=parent_obj.find('.image-container');
			var calc_width=0;
			var min_height=0;
			// Calcultate Minmum height
			elements.each(function() {
				calc_width+=$(this).attr('name')*height+25;
			});
			if(calc_width>375){
				var set_width=false;
				while(!set_width){
					height--;
					calc_width=0;
					elements.each(function() {
						// Calcultate Minmum height
						calc_width+=$(this).attr('name')*height+25;
					});
					if(calc_width<=375){
						set_width=true;
					}
				}
				min_height=height;
			}else{
				min_height=height;
			}
			jQuery(this).find('.handler_img_width_height').html(parseInt(calc_width-25)+"X"+min_height);
		},
		stop:function(event,ui)
		{
			var parent_obj=jQuery(this).parents('.container-div');
			var width=jQuery(ui.helper).width();
			var height=jQuery(ui.helper).height();
			var elements=parent_obj.find('.image-container');
			var calc_width=0;
			var min_height=0;
			// Calcultate Minmum height
			elements.each(function() {
				calc_width+=$(this).attr('name')*height+25;
			});
			if(calc_width>375){
				var set_width=false;
				while(!set_width){
					height--;
					calc_width=0;
					elements.each(function() {
						// Calcultate Minmum height
						calc_width+=$(this).attr('name')*height+25;
					});
					if(calc_width<=375){
						set_width=true;
					}
				}
				min_height=height;
			}else{
				min_height=height;
			}
			//set Width and height to each image
			elements.each(function() {
				$(this).attr('width',min_height*$(this).attr('name'));
				$(this).attr('height',min_height);
				$(this).parent().find('.resize_div_text').width(min_height*$(this).attr('name'));
				//$(this).parent().parent().find('.active_image_option').width(min_height*$(this).attr('name'));
				$(this).parent().find('.resize_div_text').height(min_height);
			});
			// ----------------------
			var container_min_height =(parent_obj.find('.text_img_content').height());
			parent_obj.find('.text-paragraph-container').css('min-height',container_min_height+'px');
			// ----------------------
			parent_obj.find('.resize_table').width(calc_width);
			jQuery('.div_border').show();
			jQuery(this).find('.handler_img_width_height').hide();
			pagechange=true;	//Page content change
		}
	});
}
function loadOfferEffects(element_id){
	if(!element_id){
		$('.edit_offer_div').removeClass('ui-resizable');
		$('.edit_offer_div').find('.ui-resizable-handle').remove();
	}
	var elements=jQuery('.edit_offer_div');
	elements.each(function() {
		jQuery(this).resizable({
			aspectRatio:false,
			minWidth:150,
			minHeight:150,
			maxWidth:543,
			handles: 'e',
			resize:function(event,ui)
			{
				var width=jQuery(ui.helper).width()+12;
				var height=jQuery(ui.helper).height();
				jQuery(this).find('.handler_img_width_height').html(parseInt(width)+"X"+height);
				jQuery(this).find('.handler_img_width_height').show();
			},
			stop:function(event,ui)
			{
				jQuery(ui.helper).css('height','auto');
				jQuery(ui.helper).css('min-height','150px');
				jQuery('.save_campaign').removeClass('disable-link');	// enable save link
				jQuery(this).find('.handler_img_width_height').hide();
			}
		});
	});
}
/**
	Function loadImageEffects to load image effects like  add highlight_on_image_hover border
**/
function loadImageEffects(){
	$('.highlight_on_image_hover').remove();
	$('.ui-resizable-handle').hide();
	$('.div_border').after("<div  class=\'highlight_on_image_hover\'><span style='display:none;' class='drop_div_border'></span></div>");
	$('.div_border').css('border','none');
}
/**
	Function loadLogoEffect to load logo effects:draggable,resize logo
**/
function loadLogoEffect(logo_block='header'){
	jQuery('#'+logo_block).find('.logo-resize-div').removeClass('ui-resizable');
	jQuery('#'+logo_block).find('#logo').removeClass('ui-draggable');
	jQuery('#'+logo_block).find('.logo-resize-div').find('.ui-resizable-handle').remove();
	jQuery('#'+logo_block).find('#logo').draggable({
		zIndex: 	1000,
		ghosting:	false,
		opacity: 	0.7,
		containment : '#'+logo_block,
		stop: function(event, ui) {
			pagechange=true;	//Page content change
		}
	});
	jQuery( '#'+logo_block ).droppable({
	'tolerance':'touch',
	accept:"#logo",
		drop: function( event, ui ) {
			pagechange=true;	//Page content change
		}
	});
	jQuery('#'+logo_block).find('#logo').find('.logo-resize-div').resizable({
		aspectRatio:true,
		minWidth:50,
		minHeight:50,
		containment: '#'+logo_block,
		handles:'se',
		resize: function(event,ui){
			var height=jQuery(ui.helper).width();
			var width=jQuery(ui.helper).width();
			jQuery(this).parent().find('.handler_img_width_height').html(parseInt(width)+"X"+height);
			jQuery(this).parent().find('.handler_img_width_height').show();
		},
		stop:function(event,ui){
			var aspect_ratio=jQuery('#'+logo_block).find('.logo_img').find('img').width()/jQuery('.logo_img').find('img').height();
			var height=jQuery(ui.helper).width()/aspect_ratio;
			jQuery('#'+logo_block).find('.logo_img').find('img').width(jQuery(ui.helper).width());
			jQuery('#'+logo_block).find('.logo_img').find('img').height(height);
			jQuery('#'+logo_block).find('#logo').width(jQuery(ui.helper).width());
			jQuery(ui.helper).height(height);
			jQuery(this).parent().find('.handler_img_width_height').hide();
			pagechange=true;	//Page content change
		}
	});
	dragDropImageBank();
}

function webCompatibleString(){
	var campaign_content=escape($('#template_container').html());
	campaign_content=campaign_content.replace(/%u201C/g, "%22");
	campaign_content=campaign_content.replace(/%u201D/g, "%22");
	campaign_content=campaign_content.replace(/%u2018/g, "%27");
	campaign_content=campaign_content.replace(/%u2019/g, "%27");
	campaign_content=campaign_content.replace(/%u2026/g, "...");
	campaign_content=campaign_content.replace(/%u2014/g, "-");
	campaign_content=campaign_content.replace(/%u2013/g, "-");
	campaign_content=campaign_content.replace(/%u2022/g, ".");
	return campaign_content;
}
function customColors(){ 
	//var bgct = hexc($('.body_bg_color').css('background-color'));
	var bgct = hexc($('#body_main').css('background-color'));
	$('#background_color_txt').val(bgct);
	$('#background_color_txt').css({"color":bgct,"background-color":bgct});
	$('#background_outer_txt').val(hexc($('.outer_bg').css('background-color')));
	$('#background_outer_txt').css("color",hexc($('.outer_bg').css('background-color')));
	$('#footer_txt').val(hexc($('.footer_txt_color').css('background-color')));
	$('#footer_txt').css("color",hexc($('.footer_txt_color').css('background-color')));
	$('#border_txt').val(hexc($('.body_border').css('background-color')));
	$('#border_txt').css("color",hexc($('.body_border').css('background-color')));
	/* $('#footer_color_txt').val(hexc($('.footer_color_txt').css('background-color')));
	$('#footer_color_txt').css("color",hexc($('.footer_color_txt').css('background-color'))); */
	$('#custom-theme').slideDown();
	$('#default-theme').slideUp();
}

/**
	show border on mouse over of block
**/
jQuery(".container-div")
	.live('mouseover',function(){
		jQuery(this).addClass('highlighted');
		jQuery(this).find('.handler').show();
		jQuery(this).find('.resize_div').show();
		jQuery(this).find('.resize_div_text').show();
	})
	.live('mouseout',function(){
		jQuery(this).find('.handler').hide();
		jQuery(this).removeClass('highlighted');
});


jQuery(".offer")
	.live('mouseover',function(){
		jQuery(this).find('.ui-resizable-handle').show();	//Show offer block resize icon
})
	.live('mouseout',function(){
		jQuery(this).find('.ui-resizable-handle').hide();		//Hide offer block resize icon
});
jQuery(".img_content")
	.live('mouseover',function(){
		jQuery(this).find('.ui-resizable-handle').show();	//Show offer block resize icon
})
	.live('mouseout',function(){
		jQuery(this).find('.ui-resizable-handle').hide();		//Hide offer block resize icon
		jQuery(this).find('.div_border').css('border',"none");
});
jQuery(".position_div")
	.live('mouseover',function(){
		jQuery(this).find('.handler_img').show();
})
	.live('mouseout',function(){
		jQuery(this).find('.handler_img').hide();
});
/**
	show toolbar on mouse over of header
**/
jQuery("#header")
	.live('mouseover',function(){
		jQuery(this).find('.menu').show();
})
	.live('mouseout',function(){
		jQuery(this).find('.menu').hide();
});
/**
*	Social media url-textbox to show/hide on click of chckbox
**/
$("#twitter_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#twitter_url').show() : 	$('#messageBox').find('#twitter_url').hide();	});
$("#facebook_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#facebook_url').show() : 	$('#messageBox').find('#facebook_url').hide();	});
$("#linkedin_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#linkedin_url').show() : 	$('#messageBox').find('#linkedin_url').hide();	});
$("#rss_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#rss_url').show() : 	$('#messageBox').find('#rss_url').hide();	});
$("#youtube_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#youtube_url').show() : 	$('#messageBox').find('#youtube_url').hide();	});
$("#google_plus_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#google_plus_url').show() : 	$('#messageBox').find('#google_plus_url').hide();	});
$("#tumblr_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#tumblr_url').show() : 	$('#messageBox').find('#tumblr_url').hide();	});
$("#flickr_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#flickr_url').show() : 	$('#messageBox').find('#flickr_url').hide();	});
$("#skype_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#skype_url').show() : 	$('#messageBox').find('#skype_url').hide();	});
$("#pinterest_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#pinterest_url').show() : 	$('#messageBox').find('#pinterest_url').hide();	});
$("#instagram_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#instagram_url').show() : 	$('#messageBox').find('#instagram_url').hide();	});
$("#mailto_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#mailto_url').show() : 	$('#messageBox').find('#mailto_url').hide();	});
$("#website_link").live('change',function(){ ($(this).is(':checked'))?	$('#messageBox').find('#website_url').show() : 	$('#messageBox').find('#website_url').hide();	});

/**
	Remove block from email container
**/
jQuery(".close-link")
	.live('click',function(event){
		var confirm_msg=$("#confirm_msg").html();
		if(jQuery(this).hasClass('logo_class')){
			$(this).parents('#logo').find('.logo_img').attr('id','active_logo');
			confirm_msg=confirm_msg.replace('block','logo');
		}else if(jQuery(this).hasClass('theme_color_delete')){			
			$(this).parent().parent('div').addClass('active_theme_color');		
			confirm_msg=confirm_msg.replace('block','theme');
		}else if(jQuery(this).hasClass('image_bank_unlink')){
			$(this).parent('div').attr('id','active_image_bank');
		}else if(jQuery(this).hasClass('header_unlink')){
			var bid = jQuery(this).attr('lang');
			//$(this).parents('#'+bid).find('.header_img').attr('id','active_header');
			$('#'+bid).find('.header_img').attr('id','active_header');
			$('#'+bid).find('.header_img').attr('lang',bid);
			confirm_msg=confirm_msg.replace('block','banner'); 			
		}else{
			jQuery(this).parents('.container-div').attr('id','active_block');
		}
		
		displayAlertMessage('Please Confirm',confirm_msg,'0',true,450,150,false,'fn:removeGarbage');
	});
jQuery(".remove-img-link").live('click',function(event){
		var confirm_msg=$("#confirm_msg_img_remove").html();
		 if(jQuery(this).hasClass('image_bank_unlink')){
			$(this).parent('div').attr('id','active_image_bank');
		}		
		displayAlertMessage('Please Confirm',confirm_msg,'0',true,350,150,false,'fn:removeGarbage');
	});
$('#messageBox').find(".delete-block").live('click',function(event){
		if($('#active_logo').length>0){
			$('#logo').remove();
		}else if($('#active_image_bank').length>0){
			var image_id=$('#active_image_bank').find('.remove-img-link').attr('id');
			unlinkImageBank(image_id);
		}else if($('.active_theme_color').length>0){
			var theme_id=$('.active_theme_color').attr('id');			
			unlinkThemeColor(theme_id);
		}else if($('#active_header').length > 0){
			jQuery(this).find('.header_div').append('<span class="empty_header"><img src="<?php echo $this->config->item('locker');?>images/contentBuilderHeader.jpg?v=1.0" width="595"/></span>');
			var delay = function() { setHeaderHeight(); };
			setTimeout(delay, 1000);
			var thisBlock = jQuery('#active_header').attr('lang');
			
			jQuery('#'+thisBlock).find('.header_unlink').remove();
			jQuery('#'+thisBlock).find('.header_unlink').parent().remove();
			jQuery('#'+thisBlock).find('.header_link').css('display','none');
			jQuery('#'+thisBlock).find('.header_link').parent().remove();
			jQuery('#'+thisBlock).find('.add_logo').parent().remove();
			jQuery('#'+thisBlock).find('.add_logo').css('display','none');
			jQuery('#'+thisBlock).find('#logo').remove();
			jQuery('#'+thisBlock).find('#header_link').remove();	
			jQuery('#'+thisBlock).remove();
		}else{
			$('#active_block').remove();
			if($('#body_main').find('table').length==0){
				$('#body_main').addClass('empty_block');
			}
		}
		$.modalBox.close();
		pagechange=true;	//Page content change
		setTimeout(function() {			
			imageBankDisplay();
		},100);
	});
$('#messageBox').find(".cancel_delete-link")
	.live('click',function(event){
	$('#active_block').attr('id','');
	$('#active_header').attr('id','');
	$('#active_logo').attr('id','');
	$('#active_image_bank').attr('id','');
	$('#active_theme_color').attr('id','');
	$.modalBox.close();
});

/**
	crate clone of image
**/
$('.clone_image').live('click',function(event){
	var parent_obj_id=$(this).parents('.container-div').attr('id');
	var img_length=$('#'+parent_obj_id).find('.img_content').length;
	var img_id=$(this).parents('.img_content').attr('id');
	var img_id_arr=img_id.split('_');
	var clone_img_id=img_id_arr[1]+1;
	$(this).parents('.img_content').parent().append($(this).parents('.img_content').clone());
	var img_container=$(this).parents('.img_content').parent().find('.img_content');
	var img_container_length=$(this).parents('.img_content').parent().find('.img_content').length;
	$('#'+parent_obj_id).find('.handler').parent().attr('colspan',img_container_length);
	var i=1;
	img_container.each(function() {
		$(this).attr('id',img_id_arr[0]+"_"+img_id_arr[1]+"_"+i);
		if(i==4){
			$(this).find('.image-container').attr('src','<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0');
			$(this).find('.image-container').attr('name','1');
			$(this).find('.image_caption').html('');
			$(this).find('.image_link').attr('title','');
			$(this).find('.image_link').hide();
			$(this).find('.handler_img').remove();
			$(this).find('.handler_img_width_height').remove();
			$(this).find('.resize_div').prepend(handler_image4);
			imageResize(img_id_arr[0]+"_"+img_id_arr[1]+"_4");
		}else if(img_container_length==i){
			$(this).find('.image-container').attr('src','<?php echo base_url() ?>locker/images/contentBuilderEmailContentDrop.jpg?v=1.0');
			$(this).find('.image-container').attr('name','1');
			$(this).find('.image_caption').html('');
			$(this).find('.image_link').attr('title','');
			$(this).find('.image_link').hide();
			$(this).find('.handler_img').remove();
			$(this).find('.handler_img_width_height').remove();
			$(this).find('.resize_div').prepend(handler_image2);
			imageResize(img_id_arr[0]+"_"+img_id_arr[1]+"_"+i);
		}else{
			$(this).find('.handler_img').remove();
			$(this).find('.handler_img_width_height').remove();
			$(this).find('.resize_div').prepend(handler_image4);
		}
		i++;
	});
	loadImageSize(parent_obj_id);
	dragDropImageBank();
	pagechange=true;	//Page content change
});
/**
	Delete clone of image
**/
$('.close-clone-link').live('click',function(event){
	var parent_obj_id=$(this).parents('.container-div').attr('id');
	$(this).parents('.img_content').remove();
	var img_container=$('#'+parent_obj_id).find('.img_content');
	var img_container_length=$('#'+parent_obj_id).find('.img_content').length;
	$('#'+parent_obj_id).find('.handler').parent().attr('colspan',img_container_length);
	var i=1;
	img_container.each(function() {
		if(img_container_length>=i){
			$(this).attr('id',parent_obj_id+"_"+i);
			if(img_container_length==1){
				$(this).find('.handler_img').remove();
				$(this).find('.handler_img_width_height').remove();
				$(this).find('.resize_div').prepend(handler_image1);
			}else if(i==4){
				$(this).find('.handler_img').remove();
				$(this).find('.handler_img_width_height').remove();
				$(this).find('.resize_div').prepend(handler_image4);
			}else if(img_container_length==i){
				$(this).find('.handler_img').remove();
				$(this).find('.handler_img_width_height').remove();
				$(this).find('.resize_div').prepend(handler_image2);
			}else{
				$(this).find('.handler_img').remove();
				$(this).find('.handler_img_width_height').remove();
				$(this).find('.resize_div').prepend(handler_image4);
			}
		}
		i++;
	});
	loadImageSize(parent_obj_id);
	pagechange=true;	//Page content change
});
/**
	open popup for image caption
**/
jQuery(".option_image-caption").live('click',function(){
	jQuery(this).attr('id','active_block');
	var image_caption=jQuery(this).parents('.img_content').find('.image_caption').html();	
	displayAlertMessage('Add a Caption to Your Image','','0',true,350,250,false,'');
	$( "#message" ).html( $("#image_caption").html() ); 
	$('#messageBox').find("textarea#image_link_caption").val(image_caption);	//set caption in textarea
});

/**
	Open dialog box for  image options
**/
jQuery(".option_image-link").live('click',function(){
	jQuery(this).attr('id','active_block');	
	displayAlertMessage('Add a Link to Your Image','','0',true,300,150,false,'');
	$( "#message" ).html( $("#image_option").html() ); 
	var u = (jQuery(this).parents('.img_content').find('.image_link').attr('name') != '')?jQuery(this).parents('.img_content').find('.image_link').attr('name') : 'http://';
	$('#messageBox').find("#image_link").val(u);
});

/**
	Display Popup For Selct Theme
**/

function changeHeader(param,bid){
	var cat = ($('#messageBox').find("#category_list").val() > 0 && $('#messageBox').find("#category_list").val() != undefined)?$('#messageBox').find("#category_list").val() : 0;	
	if(param == 'initiate'){
		displayAlertMessage('Change Header Image', "<div style='text-align:center'><img src='"+base_url+"locker/images/icons/ajax-loader.gif' /></div>", '0', true, 700, 450, false, '');}			
	$.ajax({
        type        : "POST",
        cache       : false,
        url         : base_url+'promotions/get_theme_data_for_ajax/'+cat+'/'+bid,        
        success: function(data) {			
			$( "#message" ).html( data ); 			
        }
    });
    return false;
}
jQuery(".select_theme").live('click',function(e){ 
	var thisBid = $(this).attr('lang');
	changeHeader('initiate',thisBid);	
});

/**
*	Open popup  for  to add Banner-UR/link
*/
jQuery(".header_link").live('click',function(){	
	var bid = jQuery(this).attr('lang');alert(bid);
	jQuery('#'+bid).find('.header_img').attr('id','active_header');
	jQuery('#'+bid).find('.header_img').attr('lang',bid);
	
	displayAlertMessage('Add a Link to Banner','','0',true,290,120,false,'');
	jQuery( "#message" ).html( jQuery("#header_link_option").html() ); 
	var header_lnk_url = (undefined == jQuery('#'+bid).find('#header_link').find('img').attr('name')) ? '' : 	jQuery('#'+bid).find('#header_link').find('img').attr('name');
	header_lnk_url = ( -1 == header_lnk_url.indexOf('http'))? 'http://'+header_lnk_url : header_lnk_url;
	jQuery('#messageBox').find("#header_link_text").val(header_lnk_url);
});
/**
*	Open popup for Banner logo
*/
jQuery(".add_logo").live('click',function(){	
	var bid = jQuery(this).attr('lang');
	jQuery('#'+bid).find('.header_img').attr('id','active_header');
	jQuery('#'+bid).find('.header_img').attr('lang',bid);
	
	
	displayAlertMessage('Add a Logo to Header','','0',true,280,120,false,'');
	jQuery( "#message" ).html( jQuery("#logo_dialog").html() );
	jQuery("#logo_file").attr("value",'');
	jQuery("#logo_dialog").parent().focus();
});

/**
*	Upload Banner Logo
*/
jQuery('#logo_file').live('change',function(){
	var bid = jQuery('#active_header').attr('lang');
	jQuery('#active_header').attr('id','');
	
	jQuery('#'+bid).find('#logo').remove();
	if(campaign_text == "auotresponder") var ctyp = 'a'; else var ctyp = 'c';
    jQuery(this).upload(base_url+'ajax/upload_diy_logo/'+ctyp+'/'+email_campaign_id+'/'+bid, function(res) {
		var image_obj=res;
		var img_path=image_obj.file_path; 
		var logo=logo_header.replace('[logo_src]',img_path+'?a='+'block_'+new Date().getTime());
		logo=logo.replace('logo_img_id','logo_'+bid);
		jQuery('#'+bid).find('.header_div').prepend(logo);
		jQuery.modalBox.close();
		loadLogoEffect(bid);
    }, 'json');	
});

/**
*	Open dialog box to upload image in  imagebank
*/
jQuery(".upload_image_bank").live('click',function(){
	jQuery.ajax({
        type        : "POST",
        cache       : false,
        url         : base_url+'promotions/getImageBankSize'  ,
        success: function(data) {
			if(data == 'ok'){
				if(jQuery(this).find('img').length==0){
					displayAlertMessage('Browse & Upload Your Image','','0',true,450,150,false,'');
					jQuery( "#message" ).html( jQuery("#upload_image_bank_dialog").html() ); 					
					jQuery('.uplaod_image_url').show();
				}
			}else{
				displayAlertMessage('','','0',true,350,175,false,'');
				jQuery( "#message" ).html( jQuery("#block_upload_image_bank_dialog").html() );				
			}
        }
    });

});

/**
*	upload image on change event of image file
*/
jQuery('#messageBox').find('#image_bank_file').live('change',function(){
jQuery('#messageBox').find('.img_upload_msg').show();
jQuery('#messageBox').find('.img_upload_msg').html("Please wait...");
jQuery('#messageBox').find('.img_upload_msg').addClass('error');
	jQuery('.uplaod_image_url').hide();
    jQuery(this).upload(base_url+'ajax/upload_image_bank_image', function(res) {

		var image_obj=jQuery.parseJSON(res);
		error=image_obj.error;
		if(error=="true"){
			jQuery('#messageBox').find('.img_upload_msg').show();
			jQuery('#messageBox').find('.img_upload_msg').html(image_obj.error_msg);
			jQuery('#messageBox').find('.img_upload_msg').addClass('error');
		}else{
			jQuery('#messageBox').find('.img_upload_msg').hide();
			imageBankDisplay();
			jQuery.modalBox.close();
		}
    }, 'text');
});
/**
*	Display toolbar on mouseover of imagebank images
*/
jQuery(".li_draggable").live('mouseover',function(){
  jQuery(this).find('.image_bank_div').addClass('thin-border-radius');
  jQuery(".del_image_link",this).show();
}).live('mouseout',function(){
	jQuery(this).find('.image_bank_div').removeClass('thin-border-radius');
    jQuery(".del_image_link",this).hide();
});

/**
*	Display toolbar on mouseover of colorbox
*/
jQuery(".color_theme_link").live('mouseover',function(){
	jQuery(this).find('.theme_color_delete').show();
}).live('mouseout',function(){
	jQuery(this).find('.theme_color_delete').hide();
});

/**
*	Display Toolbar for footer on mouseover of footer content
*/
jQuery("#footer").live('mouseover',function(){
	jQuery(this).find('.footer_menu').show();
}).live('mouseout',function(){
	jQuery(this).find('.footer_menu').hide();
});

/**
	Edit Footer Content
**/

jQuery(".edit_footer").live('click',function(){
	displayAlertMessage('Add / Edit Footer Address','','0',true,540,500,false,'');
	$( "#message" ).html( $("#footer_link_option").html() ); 
	$('#messageBox').find('#footer_color_txt').ColorPicker({
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {$(el).val('#'+hex).css('color','#'+hex).css('background-color','#'+hex); $(el).ColorPickerHide();}
	});
	
	if('245' != $('#messageBox').find('#country_name_footer').val())
		$('#messageBox').find('span#country_custom_div').hide();
	else
		$('#messageBox').find('span#country_custom_div').show();			
	var fsz = hexc($('#footer').css('font-size'));	
	var cl = hexc($('#footer').css('color'));	
	$('#messageBox').find('#footer_font_size').val(fsz);	
	$('#messageBox').find('.selected_font').css({'color': cl, 'font-size': fsz});
	$('#messageBox').find('#footer_color_txt').val(cl).css({"background-color": cl, "color": cl});
});
// Function to update footer
function updateFooter(){
	var c = $('#messageBox').find('#company_name_footer').val();
	var a = $('#messageBox').find('#address_footer').val();
	var ct = $('#messageBox').find('#city_footer').val();
	var st = $('#messageBox').find('#state_footer').val();
	var z = $('#messageBox').find('#zip_footer').val();
	var cntry = $('#messageBox').find('#country_name_footer').val();
	var cntry_2 = $('#messageBox').find('#country_custom_name_footer').val();
	var cntry_txt =$('#messageBox').find("#country_name_footer :selected").text();
	jQuery.ajax({
		url: "<?php echo base_url() ?>account/user_info",
		type:"POST",
		data:'company='+encodeURIComponent(c)+'&address_line_1='+encodeURIComponent(a)+'&city='+encodeURIComponent(ct)+'&state='+encodeURIComponent(st)+'&zipcode='+encodeURIComponent(z)+'&country='+encodeURIComponent(cntry)+'&country_custom='+encodeURIComponent(cntry_2),
		success: function(data){ 
			var data_arr=data.split(':');
			if(data_arr[0]=="error"){
				$('.msg').html(data_arr[1]);
			}else{
				$('.company_name').html('<b><span class="copyright">&copy; </span>'+c+'</b>');
				$('.address').html(a);
				$('.city').html(" | "+ct);
				$('.state').html(", "+st);
				$('.zip').html(z);
				
				if(cntry_txt=="United States"){					
					$('.country').html('');
				}else if(cntry_txt=="Custom"){
					$('.country').html(" | "+cntry_2);
				}else{
					$('.country').html(" | "+cntry_txt);
				}	
				changeStyle('footer','footer_color_txt','font_color');
				jQuery('.save_campaign').removeClass('disable-link');	// enable save link
			}
			pagechange=true;	//Page content change
		}
	});
	$.modalBox.close();
}
function showCustom(dpdCountry){
	if('245' == dpdCountry.value){
	$('span#country_custom_div').show();
	}else{
	$('span#country_custom_div').hide();
	}
}
/**
	Save email campaign on click of save link
**/
jQuery(".save_campaign_changes").live('click',function(){
	pagechange = true;
	if($(this).attr('id')=="next_step"){ 
		//publishPageContent(jQuery("#current_tab_page").attr('value'),email_campaign_id,2);
		publishPageContent(1,email_campaign_id,2);
	}else{
		//publishPageContent(jQuery("#current_tab_page").attr('value'),email_campaign_id);
		publishPageContent(1,email_campaign_id);
	}
});
/**
	Onclick of enter button
**/
jQuery('#campaign_title').live("keydown", function(e) {
	var code = e.keyCode || e.which;
	if(code == 13) {
		if(jQuery(this).val()==""){
			jQuery(this).val("Unnamed");
		}
		jQuery("a.save_campaign_changes").click();
	}
});
/**
	Display Popup on click of privew link
**/
jQuery('#preview_alert').live('click',function(){
	if(pagechange){
		displayAlertMessage('','','0',true,450,200,false,'');
		$( "#message" ).html( $("#preview_msg").html() ); 		
		return false;
	}else{
		window.open($('#preview_alert').attr('href'),'_blank');
		return false;
	}
});
/**
	Save campaign changes
**/
$('#messageBox').find(".save_campaign_changes").live('click',function(event){
	//set Page content change
	var current_tab_page=jQuery("#current_tab_page").attr('value');
	publishPageContent(current_tab_page,email_campaign_id,1);
	$.modalBox.close();
});
/**
	Discard campaign changes
**/
$('#messageBox').find(".discard_campaign_changes").live('click',function(event){
	$.modalBox.close();
	window.open($('#preview_alert').attr('href'),'_blank');
});
/**
	cancel campaign preview
**/
$('#messageBox').find(".cancel_campaign_changes").live('click',function(event){
	$.modalBox.close();
});
/**
	Change alignment of image in image with  text block
**/
jQuery('.change-pos').live('click',function(){
	var image_align=jQuery(this).parents('.resize_table').attr('align');
	if(image_align=="left"){
		jQuery(this).find('img').attr('src','<?php echo base_url() ?>locker/images/icons/align_left.png?v=6-20-13');
		jQuery(this).find('img').attr('title','Left Align');
		jQuery(this).parents('.resize_table').attr('align','right');
		jQuery(this).parents('.resize_table td').attr('align','right');
		jQuery(this).parents('.resize_table').find('.active_image_option').css('padding-left','25px');
		jQuery(this).parents('.resize_table').css({'margin-left':'0px','margin-right':'0px'});
	}else{
		jQuery(this).find('img').attr('src','<?php echo base_url() ?>locker/images/icons/align_right.png?v=6-20-13');
		jQuery(this).find('img').attr('title','Right Align');
		jQuery(this).parents('.resize_table').attr('align','left');
		jQuery(this).parents('.resize_table td').attr('align','left');
		jQuery(this).parents('.resize_table').find('.active_image_option').css('padding-left','0px');
		jQuery(this).parents('.resize_table').css({'margin-left':'0px','margin-right':'0px'});
	}
	pagechange=true;		//set Page content change
});
/**
	Highlight border and display handler on mousover of logo
**/
jQuery("#logo").live('mouseover',function(){
	jQuery(this).find('.handler').show();
	jQuery(this).find('.logo-resize-div').show();
	jQuery(this).css('border-color','red');
}).live('mouseout',function(){
	//jQuery(this).find('.handler').hide();
	jQuery(this).find('.logo-resize-div').hide();
	jQuery(this).css('border-color','transparent');
});
/**
	Open popup to edit Social media links
**/
$('.edit_sm').live('click',function(){
	var el_id = $(this).parents('.container-div').attr('id');
	
	displayAlertMessage('Add/update Social Media to Your Campaign',"<div style='text-align:center'><img src='"+base_url+"locker/images/icons/ajax-loader.gif' /></div>",'0',true,500,355,true,'');
	$.ajax({type: "POST", cache: false, url: base_url+'promotions/get_socialmedia_ajax/', 	
	success: function(data) { $( "#message" ).html( data ); 
	
		var sm = ['facebook', 'twitter', 'linkedin', 'rss', 'youtube', 'google_plus', 'tumblr', 'flickr', 'pinterest', 'instagram', 'mailto', 'skype', 'website']; 
		for(var i = 0; i < sm.length; i++){
			var smv = sm[i];		
			if($('#'+el_id).find('.'+smv+'_url_link').length > 0){
				$('#messageBox').find('#'+smv+'_url').val($('#'+el_id).find('.'+smv+'_url_link').attr('name')).show();
				$('input[name='+smv+'_link]').attr('checked', true);
			}
		}
	} });
	
	
	$("#current_container_id").val(el_id);
	pagechange=true;	//Page content change
});

/**
	Open popup to edit youtube media links
**/
$('.edit_youtube-link').live('click',function(){
	displayAlertMessage('','','0',true,480,220,false,'fn:removeGarbage');
	$( "#message" ).html( $("#youtube_edit_dialog").html() );	
	$('#messageBox').find("#youtube_url").val($(this).parents('.img_content').find('.image_link').attr('title'));
	$("#current_container_id").val($(this).parents('.container-div').attr('id')+"_edit");
});
/**
	Display blank on click of campaign_title input box
*/
jQuery("#campaign_title").live('click',function(){
	if(jQuery(this).val().toLowerCase()=="unnamed"){
		$("#current_container_id").val(jQuery(this).val());
		jQuery(this).val("");
	}
}).live('blur',function(){
	if(jQuery(this).val()==""){
		jQuery(this).val("Unnamed");
	}
	$("#current_container_id").val("");
});
/**
	Display Popup For feedback
**/
jQuery(".feedback_popup").live('click',function(){
	$.ajax({ type : "POST", cache: false, url: base_url+'feedback/create', data: $(this).serializeArray(),
        success: function(data) { if(data){	displayAlertMessage('',data,'0',true,450,520,false,'');}  }
    });
    return false;
});
$(document).ready(function(){
	// Open Colorpicker for background color of email container
	$('#background_color_txt,#background_outer_txt,#footer_txt,#border_txt, #footer_color_txt,.a_bgclr').ColorPicker({		
		onBeforeShow: function () {	$(this).ColorPickerSetColor(this.value);},
		onShow: function (colpkr) {	$(colpkr).fadeIn(500);	return false;},
		onHide: function (colpkr) {	$(colpkr).fadeOut(500);	return false;},
		onSubmit: function (hsb, hex, rgb,el) {			
			$(el).val('#'+hex).css({'color':'#'+hex, 'background-color': '#'+hex}); 
			switch(el.id){
				case 'background_color_txt':changeStyle('body_main',hex);changeStyle('header',hex); break;
				case 'background_outer_txt':changeStyle('outer_bg',hex); break;
				case 'footer_txt':changeStyle('footer',hex); break;
				case 'border_txt':changeStyle('email_template_table',hex,'border'); break;
				case 'footer_color_txt':changeStyle('footer',hex,'font_color'); break;
			}					
			$(el).ColorPickerHide();
		}	
	});	
	
	
	
	/**
		Display nice editor on click of text block
	**/
	var myNicEditor ;
	var isEditable = false;
	var container_id = 0;
	var div_id = 0;
	jQuery(".text-paragraph-container,.entry,.edit_offer").live('mousedown',function(){
		if(isEditable == false){

			isEditable = true;
			container_id=	'div_'+jQuery(this).parents('.container-div').attr('id');
			jQuery(this).find('.empty_text').remove();
			jQuery(this).find('.empty_title').remove();
			div_id = container_id + '_'+jQuery(this).attr('id');
			jQuery(this).attr('id',div_id);
			// alert(jQuery(this).attr("class").substring(0,14));			
			myNicEditor = new nicEditor({buttonList : ['undo','redo','bold','italic','underline','strikethrough','fontSize','subscript','superscript','left','center','right','justify','fontFamily','ol','ul','link','unlink','forecolor','xhtml','insertHTML']}).setPanel('myNicPanel').addInstance(div_id);
			
			jQuery(this).addClass('text-highlighted');
			jQuery('#'+div_id).focus();
			jQuery(this).attr("tabindex","-1");
			setTimeout('jQuery("#'+div_id+'").focus();',300);
			pagechange=true;	//Page content change

			// Stick Top Header
			var testTopScroll = function() {
				var scroll = $(window).scrollTop();
				if(scroll < 40) {
					$("#myNicPanel .nicEdit-panelContain").css("marginTop",40 - scroll);
				} else {
					$("#myNicPanel .nicEdit-panelContain").css("marginTop",0);
				}
			};
			testTopScroll();
			$(window).scroll(testTopScroll);
		}
	});
	/**
		Hide nice editor on click of html body
	**/
	$("html").click(function (evt) {
		var target = evt.target;
		if (!($(target).closest('#'+div_id).length)  && !($(target).closest('.nicEdit-pane').length) && !($(target).closest('.nicEdit-panel').length)  &&  (isEditable)){
			isEditable = false;
			$('#myNicPanel').html('');
			myNicEditor.removeInstance(div_id);
			myNicEditor = null;
			if(jQuery('#'+div_id).hasClass('header-text')){
				if((jQuery('#'+div_id).html()=="")||(jQuery('#'+div_id).html()=="<br>")||(jQuery('#'+div_id).html()=="<BR>")){
					jQuery('#'+div_id).html("<div class='empty_title'>Add a title.</div>");
				}
			}else if(jQuery('#'+div_id).hasClass('text-paragraph-container')){
				if((jQuery('#'+div_id).html()=="")||(jQuery('#'+div_id).html()=="<BR>")||(jQuery('#'+div_id).html()=="<br>")){
					jQuery('#'+div_id).html("<div class='empty_text'>Click here to add text.</div>");
				}
			}
			jQuery('.resize-div').css('z-index','10');
			jQuery('#'+div_id).removeClass('text-highlighted');
			jQuery('#'+div_id).removeAttr('contenteditable');
		}
	});

	/**
		Block Sorting Function
	**/
	for(var i=0;i<body_blocks.length;i++){
		jQuery("#"+body_blocks[i]).sortable({items:'.container-div,.move_img_container',
		handle:'.drag_handler,.handler_move',
		cursor:'move',
		connectWith:'#body_main',
		receive:function(event,ui){
			if(jQuery(this).find('.empty-text').length>0){
				jQuery(this).find('.empty-text').remove();
				jQuery(this).css("background-image","none");
				jQuery(this).css("min-height","0px");
			}
		},
		remove:function(event,ui){
			if(jQuery(this).find('.container-div').length<1)
			{
				jQuery(this).empty().append('<p class="empty-text"></p>');
			}
		}
		});
	}
	/**
		Add tool tip
	**/
	drag_drop();	// Load drag_drop on load of page
	loadHeaderEffect();	// Load header effects on load of page
	loadFooterEffect();	//Load footer effects on load of page
	imageResize();		// Load image resizer on load of page
	loadLogoEffect('header');		// Load logo effects  on load of page
	loadOfferEffects();		// Load Offer block effects on load of page
	loadImageEffects();		// Load image effects on load of page
	customColors();			// Load custom colors effects on load of page
});

/**
	Confirmation message before leaving page
**/
window.onbeforeunload = function(){
    if (pagechange){
        return  "Your Campaign has unsaved changes. Any unsaved changes will be lost!\n" +
           "Would you still like to exit without saving??";
    }
}

$(document).ready(function(){
	/* Append video link in default/blank template */
	//if($('.container-div').length <=1){
	if($('#body_main').children('.container-div').length < 1){
		pagechange=true;
		$('.diy_demo_video').remove();
	}

  var followScroll = function() {
	//alert($(window).height());
	//alert($("#campaign_editor_left_menu").height());
    if($(window).height() > $("#campaign_editor_left_menu").height()) {
		
      var $leftMenu = $("#campaign_editor_left_menu"),
          top = $leftMenu.offset().top - parseFloat($leftMenu.css('marginTop').replace(/auto/,0));
	
      $(window).scroll(function() {
        var y = $(this).scrollTop();

        if (y >= top) {
			//alert('t1');
          $leftMenu.addClass('fixed');
        } else {
          $leftMenu.removeClass('fixed');
        }
      });
    } else {
      $(window).unbind("scroll");
      $("#campaign_editor_left_menu").removeClass("fixed").removeAttr("style");
    }
  };

   followScroll();

  $(window).resize(function() {
    followScroll();
  });
});

