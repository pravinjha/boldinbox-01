<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner">
					<form action="<?php echo site_url('change_plan/process'); ?>" class="form-website" method="post" name="billingForm">
						<div class="row">							
							<div class="col-lg-12">
								<div class = 'alert alert-info'>For any queries / issues regarding payment, please contact us at +91-8130972229 or write to us at <a href = 'mailto:support@boldinbox.com'>support@boldinbox.com</a></div>	
							</div>
							<?php  if(count($messages) > 0) { ?>
								<div class="col-lg-12">
								  <div class = 'alert alert-danger'>All fields are mandatory.</div>	
								</div>							  
							<?php } ?>	
							<div class="col-lg-12">
								<div class="campaign-post">
									<div class = 'row'>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>First Name:</div>											
											<input type="text" value="<?php if(!empty($posted['firstname']))echo $posted['firstname']; else echo $arrUserProfile[0]['first_name']; ?>" id="firstname" name="firstname" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Last Name:</div>											
											<input type="text" name="lastname" id="lastname" value="<?php if(!empty($posted['lastname']))echo $posted['lastname']; else echo $arrUserProfile[0]['last_name']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Email Address:</div>											
											<input type="text" name="email" id="email" value="<?php if(!empty($posted['email']))echo $posted['email']; else echo $arrUserProfile[0]['email_address']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Phone:</div>											
											<input type="text" name="phone" id="phone" value="<?php if(!empty($posted['phone']))echo $posted['phone']; else echo $arrUserProfile[0]['phone_number']; ?>" class="form-control" required="required" />
										</div>										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Address:</div>											
											<input type="text" name="address" id="address" value="<?php  if(!empty($posted['address']))echo $posted['address']; else echo $arrUserProfile[0]['address']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>City:</div>											
											<input type="text" name="city" id="city" value="<?php  if(!empty($posted['city']))echo $posted['city']; else echo $arrUserProfile[0]['city']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>State:</div>											
											<input type="text" name="state" id="state" value="<?php  if(!empty($posted['state']))echo $posted['state']; else echo $arrUserProfile[0]['state']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Zip Code:</div>											
											<input type="text" name="zipcode" id="zipcode" value="<?php  if(!empty($posted['zipcode']))echo $posted['zipcode']; else echo $arrUserProfile[0]['zip']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Country:</div>											
											<select name="country" id="country" onchange="javascript: showCustom(this);" class="form-control" required="required" >           
												<?php
												foreach($country_info as $c){
													if($c['country_name'] == $posted['country'])
														echo "<option value='".$c['country_name']."' selected>".$c['country_name']."</option>";
													elseif($c['country_name'] == $arrUserProfile[0]['country'])
														echo "<option value='".$c['country_name']."' selected>".$c['country_name']."</option>";
													else	
														echo "<option value='".$c['country_name']."'>".$c['country_name']."</option>";
												
												}
												?>	
											</select>
											<div id="country_custom_div">
											<input type="text" maxlength="50" name="country_custom" placeholder = 'Country Name' id="country_custom" value="<?php echo $user_info['country_custom'];?>" class="form-control" required="required" />
											</div>
										</div>
										<div class="col-sm-12">&nbsp;</div>
										<div class="col-lg-6 col-md-6 col-sm-12">					
											<button type = 'submit'  name="submit" value="Next >>" class = 'blue rectangle'>Next &raquo;</button>
										</div>
									</div>
								</div>
							</div>	
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>