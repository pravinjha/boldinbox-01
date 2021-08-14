


<div class = 'body-public'>
	<div class = 'body-box-support' style = 'padding-bottom:40px;'>		
		<div class = 'body-form-text2'>Help & Support - Search Results</div>
		 <div class="body-box-support-search">			  
		  <form  name="search_frm"  method="post" action="<?php echo site_url('home/search_result'); ?>">
			<input name="search_text" class = 'body-box-support-searchbox' id="search_text" type="text" value = '<?php echo $search_text; ?>' placeholder = 'Search Here...' />
			<?php
            // display all messages
            if (is_array($messages)):
              echo '<div id="messages" style="color:#ff0000;">';
              foreach ($messages as $type => $msgs):
                  foreach ($msgs as $message):
                    echo ('<span class="' .  $type .' error">' . $message . '</span>');
                  endforeach;
              endforeach;
              echo '</div><br/><br/>';
            endif;
        ?>
			<input type = 'image' src = "<?php echo base_url();?>locker/images/icons/search.jpg" style = 'vertical-align:bottom;margin-bottom:1px;;' />
		  </form>
		</div>
		<div class = 'body-form-desc'>If you can't find your question or the answer to any of your questions here, please feel free to contact us here: <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a></div>
		

		<div class = 'support_search_results'>
			<ul>
				<?php if(count($product_data)>0){ ?>
					<?php
						foreach($product_data as $product){
						$product_name=preg_replace("![^a-z0-9]+!i", "-", trim($product['product']));
						
					?>
						<li>
							<a href="<?php echo base_url()."home/support/".$product_name.'/'.$product['category_id']."/". $product['id']."#". $product['id']; ?>" target = "_blank"><?php echo $product['product']; ?></a><br/>
						   <?php echo substr(strip_tags($product['description']),0,135)."..."; ?>
						</li>
					<?php } ?>
				<?php }else{ ?>
						<li>No Records Found. Feel free to write to us at <a href = 'mailto:support@boldinbox.com'>support@boldinbox.com</a></li>
				<?php } ?>
			</ul>
		</div>

		<!-- For Pagination -->
		<form name="hidden_frm" method="post" id="hidden_frm">
			<input type="hidden" name="search_text" value="<?php echo $search_text; ?>"/>
		</form>
		<?php
		//Display paging links
		echo '<div class = "pagination_div" style = "padding-left:10px;">'.$paging_links.'</div>';
		?>
		<!-- For Pagination -->
		
		 
          
	
</div>
</div>
<script type="text/javascript">
	$(".pagination a").live('click',function(event){
		$("#hidden_frm").attr("action",$(this).attr('href'));
		$("#hidden_frm").submit();
		return false;
	});
</script>


















