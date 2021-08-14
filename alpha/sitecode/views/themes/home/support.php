
	<section class="campaign-search">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="inner-content">
				<div class="block-heading">
                  <h4>Search Campaign</h4>
                </div>
                <form  name="search_frm" id="search_frm"  method="post" action="<?php echo site_url('home/search_result'); ?>">
                  <div class="row">
                    
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <input name="search_text" type="text" class="form-control" id="search_text"  placeholder="Search Keyword" required="">
					  <button id="btnSearch" name="btnSearch" type="submit" class="primary-button"><i class="fa fa-search"></i></button>
                    </div>                   
                    
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <!-- Services Page -->
      <section class="support-page">
        <div class="container">
        Here are a few <b>Frequently Asked Questions</b> you can refer to get your questions and queries sorted out quickly. If you can't find your question or the answer to any of your questions here, please feel free to contact us here: <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a>
        	<!-- div class="row">
            	<div class="col-lg-12">
            		<form  name="search_frm"  method="post" action="<?php echo site_url('home/search_result'); ?>">
						<input name="search_text"  id="search_text" type="text"  placeholder = 'Search Here...' />
			
						<input type = 'image' src = "<?php echo base_url();?>locker/images/icons/search.jpg" style = 'vertical-align:bottom;margin-bottom:1px;;' />
					  </form>
					  
            	</div>
            </div -->	
          <div class="row">
            <div class="col-lg-12">
              <div id="tabs1">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="support-sidebar">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="support-list">
                            
                            <ul>
								<li><h4>Category</h4></li>
								<?php
			
								foreach($support_data as $category){
									$category_name =preg_replace(array("![^a-z0-9]+!i","/&/"), array("-","-"), trim($category['category']));
								?>
								<li>
								<?php
								if($category['id']==$selected_category_id){	
									$selected_category_name = $category_name;
								?>
									<a href="<?php echo base_url()."home/support/".$category_name."/".$category['id'];?>" id="categ_<?php echo $category['id']; ?>" class='active' name='<?php echo $category['id']; ?>'><?php echo '- '.$category['category']; ?></a>		
								<?php }else{?>	
									<a href="<?php echo base_url()."home/support/".$category_name."/".$category['id'].'#'. $category['id'];?>" id="categ_<?php echo $category['id']; ?>" ><?php echo '+ '.$category['category']; ?></a>
								<?php }?>	
								</li>
								<?php } ?>
						  	</ul>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="contact-us">
                            <h2>We Are Here!<br>Contact Us</h2>
                            <div class="main-white-button">
                              <a href="contact.html">Contact Us Now</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <section class='tabs-content'>
                      <?php
						//if($category['id']==$selected_category_id){	
						//	foreach($support_data as $category){
								
							$showHead = 0;
									foreach($product_data as $product){
										$product_name=preg_replace("![^a-z0-9]+!i", "-", trim($product['product']));
											
										if($showHead == 0){
											$showHead = 1;
											echo "<article id='tabs-{$selected_category_id}'>";
											echo "<div class='down-content'>";
											echo "<h4>".$selected_category_name."</h4>"; 
					
											echo "<div class='accordions'>";
											echo "<ul class='accordion1'>";  
										}	
										?>
										<li>
										<?php if($selected_product_id == $product['id']){
											echo "<a href='". base_url()."home/support/$product_name/".$category['id']."/". $product['id']."' name='".$product['id']."'>- ".substr($product['product'],0,255)."</a>"; 
										?>				
											
											<p>
												<?php
												  $description=str_replace("https", "http", $product['description']);
												  $description=str_replace("../http", "http", $description);
												  $description=str_replace("../../../../asset", base_url()."asset", $description);
												  $description=str_replace("../../../asset", base_url()."asset", $description);
												  $description=str_replace("../../asset", base_url()."asset", $description);
											
												   echo $description; 
												?>
											</p>
									
										</li>
									<?php
										}else{
										
											echo "<a href='". base_url()."home/support/$product_name/".$selected_category_id."/". $product['id'].'#'. $product['id']."'>+ ".substr($product['product'],0,255)."</a>"; 			
										
										}
									}
								?>
									</ul>
								</div>
							</div>
                      	</article>
						<?php //} // End of FOR -LOOP
						
						//}// End of IF for category comparision
						?>
                      
                     
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      

<script type="text/javascript">
$(document).ready(function(){
    $("#btnSearch").click(function(){        
        jQuery("#search_frm")[0].submit(); // Submit the form
    });
});

</script>
