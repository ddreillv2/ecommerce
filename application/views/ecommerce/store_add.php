<div id="put_script"></div>
<section class="section">
	<div class="section-header d-none">
		<h1><i class="fa fa-plus-circle"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item"><a href="<?php echo base_url('messenger_bot'); ?>"><?php echo $this->lang->line("Messenger Bot"); ?></a></div>
		  <div class="breadcrumb-item"><a href="<?php echo base_url('ecommerce'); ?>"><?php echo $this->lang->line("E-commerce"); ?></a></div>
		  <div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<div class="section-body">
		<form action="#" enctype="multipart/form-data" id="plugin_form">
			<div class="row">
				<div class="col-12">
					<div class="card main_card no_shadow">		
						<div class="card-body p-0">
							<div class="row">

							  <div class="form-group col-12 col-md-6">
							    <label>
							       <?php echo $this->lang->line("Select page"); ?> *
							       <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Select page") ?>" data-content='<?php echo $this->lang->line("Select your Facebook page for which you want to create the store.") ?>'><i class='fas fa-info-circle'></i> </a>
							    </label>
							    <?php $page_info['']= $this->lang->line("select page"); ?>
							    <?php echo form_dropdown('page', $page_info,'', 'class="form-control select2" id="page" style="width:100%;"' ); ?>                   
							  </div>

							   <div class="form-group col-12 col-md-6">
							    <label>
							      <?php echo $this->lang->line("Store name"); ?> *
							    </label>
							    <input type="text" name="store_name" id="store_name" class="form-control">                      
							  </div>

							  <div class="form-group col-6 col-md-6">
							    <label>
							      <?php echo $this->lang->line("Email"); ?> *
							    </label>
							    <input type="email" name="store_email" id="store_email" class="form-control">                      
							  </div>

							  <div class="form-group col-6 col-md-6">
							    <label>
							      <?php echo $this->lang->line("Mobile/phone"); ?>
							    </label>
							    <input type="text" name="store_phone" id="store_phone" class="form-control">                      
							  </div>

							  <div class="form-group col-12 col-md-4">
							    <label>
							      <?php echo $this->lang->line("Country"); ?> *
							    </label>
							    <?php 
							    $country_names[''] = $this->lang->line("Select");
							    echo form_dropdown('store_country', $country_names,'', 'class="form-control select2" id="store_country" style="width:100%;"' ); 
							    ?>
							  </div>

							  <div class="form-group col-6 col-md-4">
							    <label>
							      <?php echo $this->lang->line("State"); ?> *
							    </label>
							    <input type="text" name="store_state" id="store_state" class="form-control">                      
							  </div>

							  <div class="form-group col-6 col-md-4">
							    <label>
							      <?php echo $this->lang->line("City"); ?> *
							    </label>
							    <input type="text" name="store_city" id="store_city" class="form-control">                      
							  </div>

							  <div class="form-group col-12 col-md-8">
							    <label>
							      <?php echo $this->lang->line("Street address"); ?> *
							    </label>
							    <input type="text" name="store_address" id="store_address" class="form-control">                  
							  </div>

							  <div class="form-group col-6 col-md-2">
							    <label>
							      <?php echo $this->lang->line("Postal code"); ?> *
							    </label>
							    <input type="text" name="store_zip" id="store_zip" class="form-control">                      
							  </div>

							  <div class="form-group col-6 col-md-2">
							    <label>
							      <?php echo $this->lang->line("Locale"); ?> *
							    </label>
							   <?php echo form_dropdown('store_locale', $locale_list,$this->language, 'class="form-control select2" id="store_locale" style="width:100%;"' ); ?>                      
							  </div>

							  <div class="form-group col-6 col-md-6">
							    <label>
							      <?php echo $this->lang->line("Tax"); ?> % 
							    </label>
							    <div class="input-group mb-2">
		                            <input type="number" name="tax_percentage" id="tax_percentage" class="form-control" value="0" min="0" max="100">  
		                            <div class="input-group-append">
		                              <div class="input-group-text">%</div>
		                            </div>
		                        </div>                    
							  </div>

							  <div class="form-group col-6 col-md-6">
							    <label>
							      <?php echo $this->lang->line("Shipping fee"); ?>
							    </label>
							    <div class="input-group mb-2">
		                            <input type="number" name="shipping_charge" id="shipping_charge" class="form-control" value="0" min="0">  
		                            <div class="input-group-append">
		                              <div class="input-group-text">
		                              	<?php 
		                              		$currency = isset($get_ecommerce_config['currency']) ? $get_ecommerce_config['currency'] : "USD";
		                              		$currency_icon = isset($currency_icons[$currency]) ? $currency_icons[$currency] : "$";
		                              		echo $currency_icon; 
		                              	?>		                              		
		                              	</div>
		                            </div>
		                        </div>							                        
							  </div>							  

							  <div class="col-12 col-md-6">
							    <div class="form-group">
							      <label><?php echo $this->lang->line('Logo'); ?> 
							       <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Logo"); ?>" data-content="<?php echo $this->lang->line("Maximum: 1MB, Format: JPG/PNG, Recommended dimension : 200x50"); ?> / 120x120"><i class='fa fa-info-circle'></i> </a>
							      </label>
							      <div id="store-logo-dropzone" class="dropzone mb-1">
							        <div class="dz-default dz-message">
							          <input class="form-control" name="store_logo" id="store_logo" type="hidden">
							          <span style="font-size: 20px;"><i class="fas fa-cloud-upload-alt" title='<?php echo $this->lang->line("Upload"); ?>' data-toggle="tooltip" style="font-size: 35px;color: #6777ef;"></i> </span>
							        </div>
							      </div>
							      <span class="red"></span>
							    </div>
							  </div>

							  <div class="col-12 col-md-6">
							    <div class="form-group">
							      <label><?php echo $this->lang->line('Favicon'); ?> 
							       <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Favicon"); ?>" data-content="<?php echo $this->lang->line("Maximum: 1MB, Format: JPG/PNG, Recommended dimension : 100x100"); ?>"><i class='fa fa-info-circle'></i> </a>
							      </label>
							      <div id="store-favicon-dropzone" class="dropzone mb-1">
							        <div class="dz-default dz-message">
							          <input class="form-control" name="store_favicon" id="store_favicon" type="hidden">
							          <span style="font-size: 20px;"><i class="fas fa-cloud-upload-alt" title='<?php echo $this->lang->line("Upload"); ?>' data-toggle="tooltip" style="font-size: 35px;color: #6777ef;"></i> </span>
							        </div>
							      </div>
							      <span class="red"></span>
							    </div>
							  </div>

							  <div class="card col-12">
							  	<div class="card-header" style="border:1px solid #F7F9F9">
							  		<h4><?php echo $this->lang->line("Checkout Options"); ?></h4>
							  		<hr>
							  	</div>
							  	<div class="card-footer bg-whitesmoke row">

								  <div class="form-group col-12 col-md-6">
									<label>
										<?php echo $this->lang->line("PayPal checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="paypal_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="paypal_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-6">
									<label>
										<?php echo $this->lang->line("Stripe checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="stripe_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="stripe_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-4">
									<label>
										<?php echo $this->lang->line("Razorpay checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="razorpay_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="razorpay_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-4">
									<label>
										<?php echo $this->lang->line("Paystack checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="paystack_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="paystack_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-4">
									<label>
										<?php echo $this->lang->line("Mollie checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="mollie_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="mollie_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-6">
									<label>
										<?php echo $this->lang->line("Manual checkout"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="manual_enabled" value="1" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="manual_enabled" value="0" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>

								  <div class="form-group col-12 col-md-6">
									<label>
										<?php echo $this->lang->line("Cash on delivery"); ?> *
									</label>
									<div class="row">
										<div class="col-12">
											<div class="selectgroup w-100">
												<label class="selectgroup-item">
													<input type="radio" name="cod_enabled" value="1" class="selectgroup-input" checked>
													<span class="selectgroup-button"> <?php echo $this->lang->line("Yes") ?></span>
												</label>
												<label class="selectgroup-item">
													<input type="radio" name="cod_enabled" value="0" class="selectgroup-input">
													<span class="selectgroup-button"> <?php echo $this->lang->line("No") ?></span>
												</label>
											</div>
										</div>
									</div>
								  </div>
								</div>
							  </div>

							  <div class="form-group col-6 col-md-6 mt-2">
							    <label>
							      <?php echo $this->lang->line("Facebook Pixel ID"); ?>
							       <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Facebook Pixel ID"); ?>" data-content="<?php echo $this->lang->line("In Desktop Facebook Messenger, pixel tracking may not work properly as it loads in Facebook iframe."); ?>"><i class='fa fa-info-circle'></i> </a>
							    </label>
							    <input type="text" name="pixel_id" id="pixel_id" class="form-control" placeholder="<?php echo $this->lang->line('Example : '); ?> 1123241077781024">                      
							  </div>

							  <div class="form-group col-6 col-md-6 mt-2">
							    <label>
							      <?php echo $this->lang->line("Google Analytics ID"); ?>
							    </label>
							    <input type="text" name="google_id" id="google_id" class="form-control" placeholder="<?php echo $this->lang->line('Example : '); ?> UA-118292462-1">                      
							  </div>

							  <br><br>
							  <div class="form-group col-12 mt-2">
							    <label>
							      <?php echo $this->lang->line("Terms of service"); ?>
							    </label>
							    <textarea name="terms_use_link"  class="form-control visual_editor"></textarea>                    
							  </div>

							  <div class="form-group col-12">
							    <label>
							      <?php echo $this->lang->line("Refund policy"); ?>
							    </label>
							    <textarea name="refund_policy_link"  class="form-control visual_editor"></textarea>                     
							  </div>


				  			  <div class="form-group col-12 col-md-8 d-none">
				  			    <label>
				  			      <?php echo $this->lang->line("Select label"); ?>
				  			       <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Select label") ?>" data-content='<?php echo $this->lang->line("Will assign to this label after successful checkout.") ?> <?php echo $this->lang->line("You must select page to fill this list with data."); ?>'><i class='fa fa-info-circle'></i> </a>
				  			    </label>
				  			    <?php echo form_dropdown('label_ids[]',array(), '','style="height:45px;overflow:hidden;width:100%;" multiple="multiple" class="form-control select2" id="label_ids"'); ?>
				  			  </div>

			                  <div class="col-12 col-md-4">
				                  <div class="form-group">
				                    <label for="status" > <?php echo $this->lang->line('Status');?> *</label><br>
				                    <label class="custom-switch mt-2">
				                      <input type="checkbox" name="status" value="1" class="custom-switch-input" checked>
				                      <span class="custom-switch-indicator"></span>
				                      <span class="custom-switch-description"><?php echo $this->lang->line('Online');?></span>
				                      <span class="red"><?php echo form_error('status'); ?></span>
				                    </label>
				                  </div>
			                  </div>	

							</div>
						</div>
						
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="card no_shadow">
						<div class="card-footer p-0">  
							<button class="btn btn-lg btn-primary" id="get_button" name="get_button" type="button"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("Create Store");?></button>
							<button class="btn btn-lg btn-light float-right" onclick="ecommerceGoBack()" type="button"><i class="fas fa-times"></i> <?php echo $this->lang->line("Cancel");?></button>
					    </div>
					</div>
				</div>
			</div>

		</form>
	</div>
</section>




<script>
	var base_url="<?php echo site_url(); ?>";
	var action_url = base_url+"ecommerce/add_store_action";
	var success_title = '<?php echo $this->lang->line("Store Created"); ?>';
	$("document").ready(function()	{
		
		$(document).on('blur','#store_name',function(event){
			event.preventDefault();
			var ref=$(this).val();
			$("#email_subject").val(ref+" | <?php echo $this->lang->line('Cart Update'); ?>");

		});

		$(document).on('change','#page',function(event){
			event.preventDefault();

			var page_id=$(this).val();			 
			  $.ajax({
			  type:'POST' ,
			  url: base_url+"ecommerce/get_template_label_dropdown",
			  data: {page_id:page_id},
			  dataType : 'JSON',
			  success:function(response){
			    // $("#template_id").html(response.template_option);
			    $("#label_ids").html(response.label_option);
			    $("#put_script").html(response.script);
			  }

			});
		});
		
		$(document).on('click','#get_button',function(e){
			get_button();
		});


	});
</script>

<?php include(APPPATH.'views/ecommerce/store_style.php'); ?>
<?php include(APPPATH.'views/ecommerce/store_js.php'); ?>