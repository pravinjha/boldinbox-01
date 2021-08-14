<!-- Contact Info -->
      <section class="contact-info">
        <div class="container">
          <div class="row">
            <div class="col-lg-4">
              <div class="info-item">
                <div class="icon">
                  <i class="fa fa-envelope"></i>
                </div>
                <h4>Email Address</h4>
                <p><a href = 'mailo:support@boldinbox.com'><b>support@boldinbox.com</b></a><br><a href = 'mailo:sumit@boldinbox.com'><b>sumit@boldinbox.com</b></a></p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="info-item">
                <div class="icon">
                  <i class="fa fa-phone"></i>
                </div>
                <h4>Phone Number</h4>
                <p><a href="tel:+918130972229">+91 8130 972 229</a> <br><a href="skype:sumitthakkar?chat"><img src="https://secure.skypeassets.com/i/scom/images/skype-buttons/chatbutton_32px.png"  alt="Skype chat, instant message" role="Button" style="border:0;height:32px;width:86px;"></a></p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="info-item">
                <div class="icon">
                  <i class="fa fa-map-marker"></i>
                </div>
                <h4>Street Address</h4>
                <p><a href="#">Lyle Dr, San Jose<br>CA 95129</a></p>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Contact Us -->
      <section class="contact-us">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="inner-content">
                <div class="block-heading">
                  <h4>Say Hello To Us!</h4>
                </div>
                <?php echo form_open(base_url().'home/contact/', array('id' => 'signup', 'class' => 'body-form contact-form'));?>
				<?php echo validation_errors('<span class="error">', '</span>'); ?>
				<?php if(isset($msg1)){ ?>
				<?php echo $msg1; ?>
				<?php } ?>
                  <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                      <input name="name" type="text" class="form-control" id="name" placeholder="Full Name" required=""  value="<?php echo $name;?>" >
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                      <input name="email" type="text" class="form-control" id="email" pattern="[^ @]*@[^ @]*" placeholder="E-Mail Address" required=""  value="<?php echo $email;?>" >
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                      <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone" value="<?php echo $phone;?>" >
                    </div>
                    <div class="col-lg-12">
                      <textarea name="desc" rows="6" class="form-control" id="desc" placeholder="Your Message" required=""><?php echo $desc;?></textarea>
                    </div>
                    <div class="col-lg-12">
                    <input type="hidden" name="word" value="<?php echo $word;?>" />			
			<div><div class="g-recaptcha" data-sitekey="6LcN4wYUAAAAAKf4cDNbc_VT01tFh2IPZ_4S5s3W"></div></div>
                      <button type="submit" id="form-submit" class="filled-button">Send Message Now</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>


<script src='https://www.google.com/recaptcha/api.js'></script>