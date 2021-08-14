<div class="main-container">
			

			<section class="page-top">
				<div class="container">
					<div class="col-md-4 col-sm-4">
						<h1>Packages & Pricing</h1>
					</div>
					<div class="col-md-8 col-sm-8">
						<ul class="pull-right breadcrumb">
							<li>
								<a href="<?php echo base_url();?>">
									Home
								</a>
							</li>
							<li class="active">
									Pricing						
							</li>
						</ul>
					</div>
				</div>
			</section>
			<section class="wrapper">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<p>
								Start sending your <b>Email Campaign For Free</b>. If you can't find a package suitable for you, please feel free to write to us at: <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a>
							</p>
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th colspan="10">+ Low Volume Packages</th>											
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Contacts</th>
											<?php
												for($i=0;$i<9;$i++){
													echo '<th>Up to  <br />'. number_format($packages[$i]['package_max_contacts']).'</th>';
												}
											?>
										</tr>
										<tr>
											<th>Monthly Price</th>
											<?php
												for($i=0;$i<9;$i++){
													if($packages[$i]['package_price']==0.00)
														echo '<td>$0</td>';
													else
														echo '<td>$'.number_format($packages[$i]['package_price'],0).'</td>';
												}
											?>
										</tr>										
									</tbody>
								 
									<thead>
										<tr>
											<th colspan="10">+ High Volume Packages</th>											
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Contacts</th>
											<?php
												for($i=9;$i<18;$i++){
													echo '<th>Up to  <br />'. number_format($packages[$i]['package_max_contacts']).'</th>';
												}
											?>
										</tr>
										<tr>
											<th>Monthly Price</th>
											<?php
												for($i=9;$i<18;$i++){
													if($packages[$i]['package_price']==0.00)
														echo '<td>$0</td>';
													else
														echo '<td>$'.number_format($packages[$i]['package_price'],0).'</td>';
												}
											?>
										</tr>										
									</tbody>
								</table>
							</div>
							
							<p> <a href="<?php echo  base_url()."home/contact";?>"><b>Contact us</b></a> for custom plans or annual discount or for any other pricing related queries you may have. <br /><br />All our plans are based on the total number of subscribers in your account and are defined to send unlimited emails per billing period, however, individual sending limits may apply on case to case basis.</p>
						</div>
					</div>
					 
					    
					 
				</div>
			</section>
		</div>     