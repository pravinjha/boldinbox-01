<div class = 'body-public'>
	<div class = 'body-box-support'>		
		<div class = 'body-form-text2'>Email Marketing Features</div>
		<div class = 'body-form-desc'>See below the <b>Salient Features</b> of our Email Marketing software. If you can't find any feature you are looking for, please send us your feedback and suggestions at: <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a></div>
		
		
		<div class="support_category_block">
		  <div class = 'body-form-desc'>Select a category below to find your question:</div>
		  <ul>
			<?php
			
			foreach($support_data as $category){
			?>
			<li>
			<?php 
			$category_name =preg_replace(array("![^a-z0-9]+!i","/&/"), array("-","-"), trim($category['category']));;
			if($category['id']==$selected_category_id){		
			?>
			<a href="<?php echo base_url()."home/features/".$category_name."/".$category['id'];?>" id="categ_<?php echo $category['id']; ?>" class='active' name='<?php echo $category['id']; ?>'><?php echo '- '.$category['category']; ?></a>			
				<!-- QUESTIONS -->
				<ul class="support_topics">
				<?php
					foreach($product_data as $product){
					$product_name=preg_replace("![^a-z0-9]+!i", "-", trim($product['product']));
				?>
					<li style="list-style-type:none"> 					
						<div class="support_topic_desc">				
							<?php
							  $description=str_replace("https", "http", $product['description']);
							  $description=str_replace("../http", "http", $description);
							  $description=str_replace("../../../../asset", base_url()."asset", $description);
							  $description=str_replace("../../../asset", base_url()."asset", $description);
							  $description=str_replace("../../asset", base_url()."asset", $description);
							?>
							<?php  echo $description; ?>
						</div>						
					</li>
				<?php
					}
				?>
				</ul>
				<!-- QUESTIONS -->
			<?php }else{?>	
				<a href="<?php echo base_url()."home/features/".$category_name."/".$category['id'].'#'. $category['id'];?>" id="categ_<?php echo $category['id']; ?>" ><?php echo '+ '.$category['category']; ?></a>
			<?php }?>	
			</li>
			<?php } ?>
		  </ul>
		</div>
	</div>
</div>