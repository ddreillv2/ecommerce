<style>
  /* Manual payment style */
  #manual-payment-modal #additional-info {
    height: 160px !important;
  }
</style>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php
$currency_position = isset($ecommerce_config['currency_position']) ? $ecommerce_config['currency_position'] : "left";
$decimal_point = isset($ecommerce_config['decimal_point']) ? $ecommerce_config['decimal_point'] : 0;
$thousand_comma = isset($ecommerce_config['thousand_comma']) ? $ecommerce_config['thousand_comma'] : '0';


$order_title = $this->lang->line("Checkout");
$order_date = date("jS M,Y",strtotime($webhook_data_final['updated_at']));      
$wc_first_name = $webhook_data_final['first_name'];
$wc_last_name = $webhook_data_final['last_name'];
// $confirmation_response = json_decode($webhook_data_final['confirmation_response'],true);
$wc_buyer_location = json_decode($webhook_data_final['user_location'],true);
if(!is_array($wc_buyer_location)) $wc_buyer_location = array(); 
$currency = $webhook_data_final['currency'];
$currency_icon = isset($currency_icons[$currency])?$currency_icons[$currency]:'$';
$wc_email_bill = $webhook_data_final['email'];
$wc_phone_bill = $webhook_data_final['phone_number'];
$shipping_cost = $webhook_data_final["shipping"];
$total_tax = $webhook_data_final["tax"];     
$checkout_amount  = $webhook_data_final['payment_amount'];
$coupon_code = $webhook_data_final['coupon_code'];
$coupon_type = $webhook_data_final['coupon_type'];
$coupon_amount =  $webhook_data_final['discount'];
$subtotal =  $webhook_data_final['subtotal'];
$currency_left = $currency_right = "";
if($currency_position=='left') $currency_left = $currency_icon;
if($currency_position=='right') $currency_right = $currency_icon;

$payment_method =  $webhook_data_final['payment_method'];
if($payment_method=='') $payment_method =  '<span class="badge badge-danger">'.$this->lang->line("Incomplete").'</span>';      
else $payment_method =  $payment_method." ".$webhook_data_final['card_ending'];

$order_no =  $webhook_data_final['id'];
$order_url =  base_url("ecommerce/order/".$order_no);

$buyer_country = isset($country_names[$webhook_data_final["buyer_country"]]) ? ucwords(strtolower($country_names[$webhook_data_final["buyer_country"]])) : $webhook_data_final["buyer_country"];
$store_country = $webhook_data_final["store_country"];
$store_country_formatted = isset($country_names[$webhook_data_final["store_country"]]) ? ucwords(strtolower($country_names[$webhook_data_final["store_country"]])) : $webhook_data_final["store_country"];
// $buyer_address = $webhook_data_final["buyer_address"]."<br>".$webhook_data_final["buyer_state"]." ".$webhook_data_final["buyer_zip"]."<br>".$buyer_country;
$store_name = $webhook_data_final['store_name'];
$store_address = $webhook_data_final["store_address"]."<br>".$webhook_data_final["store_state"].", ".$store_country_formatted." ".$webhook_data_final["store_zip"];
$store_phone = $webhook_data_final["store_phone"];
$store_email = $webhook_data_final["store_email"];
$subscriber_id_database = $webhook_data_final["subscriber_id"];
$store_unique_id = $webhook_data_final["store_unique_id"];

$table_bordered = 'table-bordered';
$table_data ='
<div class="table-responsive">
<table class="table table-striped table-hover table-md '.$table_bordered.'">
  <tbody>
  <tr>
    <th data-width="40">#</th>
    <th class="text-center">'.$this->lang->line("Thumbnail").'</th>
    <th>'.$this->lang->line("Item").'</th>
    <th class="text-center">'.$this->lang->line("Unit Price").'</th>
    <th class="text-center">'.$this->lang->line("Quantity").'</th>
    <th class="text-right">'.$this->lang->line("Price").'</th>
  </tr>';
$i=0;
// $subtotal_count = 0;
foreach ($product_list as $key => $value) 
{        
  $title = isset($value['product_name']) ? $value['product_name'] : "";
  $quantity = isset($value['quantity']) ? $value['quantity'] : 1;
  $price = isset($value['unit_price']) ? $value['unit_price'] : 0;
  $item_total = $price*$quantity;
  // $subtotal_count+=$item_total;
  $item_total = mec_number_format($item_total,$decimal_point,$thousand_comma);
  $price = mec_number_format($price,$decimal_point,$thousand_comma);
  $image_url = (isset($value['thumbnail']) && !empty($value['thumbnail'])) ? base_url('upload/ecommerce/'.$value['thumbnail']) : base_url('assets/img/example-image.jpg');        
  $permalink = base_url("ecommerce/product/".$value['product_id']);
  $attribute_info = (is_array(json_decode($value["attribute_info"],true))) ? json_decode($value["attribute_info"],true) : array();

  $attribute_query_string_array = array();
  $attribute_query_string = "";
  foreach ($attribute_info as $key2 => $value2) 
  {
    $attribute_query_string_array[]="option".$key2."=".urlencode($value2);
  }
  $attribute_query_string = implode("&", $attribute_query_string_array);
  if(!empty($attribute_query_string_array)) $attribute_query_string = "&quantity=".$quantity."&".$attribute_query_string;

  $attribute_print = "";
  if(!empty($attribute_info))$attribute_print = "<br><small>".implode(', ', array_values($attribute_info)). "</small>";
  if($subscriber_id!='') $permalink.="?subscriber_id=".$subscriber_id.$attribute_query_string;

  $i++;
  $off = $value["coupon_info"];
  if($off!="") $off.=" ".$this->lang->line("OFF");
  $table_data .='
  <tr>
    <td data-width="40">'.$i.'</td>
    <td class="text-center" width="140px;"><a href="'.$permalink.'"><img src="'.$image_url.'" style="width:120px; height:120px;" class="rounded"></a></td>
    <td><a href="'.$permalink.'">'.$title.'</a> <span class="text-warning"> '.$off."</span>".$attribute_print.'<br><br><a class="pointer text-danger delete_item" href="#" data-id="'.$value['id'].'">'.$this->lang->line("Remove").'</a></td>
    <td class="text-center">'.$currency_left.$price.$currency_right.'</td>
    <td class="text-center">'.$quantity.'</td>
    <td class="text-right">'.$currency_left.$item_total.$currency_right.'</td>
  </tr>';
}
$table_data .= '</tbody></table></div>';        

$coupon_info2 = "";
if($coupon_code!='' && $coupon_type=="fixed cart")
$coupon_info2 = 
'<div class="invoice-detail-item">
  <div class="invoice-detail-name">'.$this->lang->line("Discount").'</div>
  <div class="invoice-detail-value">-'.$currency_left.mec_number_format($coupon_amount,$decimal_point,$thousand_comma).$currency_right.'</div>
</div>';

$tax_info = "";
if($total_tax>0)
$tax_info = 
'<div class="invoice-detail-item">
    <div class="invoice-detail-name">'.$this->lang->line("Tax").'</div>
    <div class="invoice-detail-value">'.$currency_left.mec_number_format($total_tax,$decimal_point,$thousand_comma).$currency_right.'</div>
</div>';

$shipping_info = "";
if($shipping_cost>0)
$shipping_info = 
'<div class="invoice-detail-item">
    <div class="invoice-detail-name">'.$this->lang->line("Shipping").'</div>
    <div class="invoice-detail-value">'.$currency_left.mec_number_format($shipping_cost,$decimal_point,$thousand_comma).$currency_right.'</div>
</div>';

// $coupon_code." (".$currency_icon.$coupon_amount.")";      

//if($webhook_data_final['action_type']!='checkout') $subtotal = $subtotal_count;
$subtotal = mec_number_format($subtotal,$decimal_point,$thousand_comma);
$checkout_amount = mec_number_format($checkout_amount,$decimal_point,$thousand_comma);
$coupon_amount = mec_number_format($coupon_amount,$decimal_point,$thousand_comma);

$store_name_formatted = '<a href="'.base_url('ecommerce/store/'.$store_unique_id."?subscriber_id=".$subscriber_id).'">'.$store_name.'</a>';
 $store_image = ($webhook_data_final['store_logo']!='') ? '<div class="col-lg-12 text-center"><a href="'.base_url('ecommerce/store/'.$store_unique_id."?subscriber_id=".$subscriber_id).'"><img src="'.base_url("upload/ecommerce/".$webhook_data_final['store_logo']).'"></a><hr></div>':'';

$output = "";
$after_checkout_details = 
$coupon_info2.$shipping_info.$tax_info.'  
<div class="invoice-detail-item">
  <div class="invoice-detail-name">'.$this->lang->line("Total").'</div>
  <div class="invoice-detail-value invoice-detail-value-lg">'.$currency_left.$checkout_amount.$currency_right.'</div>
</div>';

$apply_coupon = '<div class="section-title">'.$this->lang->line("Apply Coupon").'</div>
<p class="section-lead mt-2">
  <div class="input-group">
    <input type="text" class="form-control" id="coupon_code" name="coupon_code" style="height:50px;" placeholder="'.$this->lang->line("Code").'" value="'.$coupon_code.'">
    <div class="input-group-append">
      <button class="btn btn-primary"  style="height:50px;" type="button" id="apply_coupon"><i class="fas fa-check-circle"></i> '.$this->lang->line("Apply").'</button>
    </div>
  </div>
</p>';

$seller_info = 
'<div class="section-title">'.$this->lang->line("Seller").'</div>
<p class="section-lead ml-0">
'.$store_address.'<br>
'.$store_email.'<br>'.$store_phone.'
</p>
';


$coupon_details =
'<div class="col-12 col-md-6">
  '.$apply_coupon.'                     
</div>
<div class="col-12 col-md-6"></div>';

$seller_details =
'<div class="col-8 col-md-5">
  '.$seller_info.'                     
</div>';


?>

<section class="section">
  <div class="section-body">
    
    <div class="invoice" style="border:1px solid #dee2e6;padding:40px 25px">
      <?php echo $store_image;?>
      <?php if($i>0) : ?>
      <div class="invoice-print"> 
        <div class="row">
          <div class="col-md-12">
            <div class="section-title mt-0"><?php echo $this->lang->line("Order Summary"); ?><span class="float-right"><?php echo date("jS M,Y"); ?></span></div>
            <?php echo $table_data;?>
            <div class="row">
              <?php echo $coupon_details; ?>
              <?php echo $seller_details;?>
              <div class="col-4 col-md-7 text-right">
                <div class="invoice-detail-item" style="margin-top: 20px;">
                  <div class="invoice-detail-name"><?php echo $this->lang->line("Subtotal");?></div>
                  <div class="invoice-detail-value"><?php echo $currency_left.$subtotal.$currency_right;?></div>
                </div>
                <?php echo $after_checkout_details;?>
              </div>
            </div>
          </div>
        </div>
        <hr class="mb-3">
        <div class="row" id="address_row">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-12 col-md-6">
                  <div class="section-title"><?php echo $this->lang->line("Billing Address"); ?></div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Name");?></span>
                      </div>
                      <input type="text" class="form-control"  class="form-control-plaintext" readonly value="<?php echo $wc_first_name; ?>">
                      <input type="text" class="form-control"  class="form-control-plaintext" readonly value="<?php echo $wc_last_name; ?>">
                    </div>
                  </div>                 
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Country");?></span>
                      </div>
                      <?php 
                      $default_country = isset($wc_buyer_location['country']) ? $wc_buyer_location['country'] : $store_country;
                      $country_names[''] =  $this->lang->line("Select Country");
                      //echo form_dropdown('country', $country_names,$default_country,"id='country' class='form-control select2' style='width:65%'"); 
                      ?>
                      <select id='country' name='country' class='form-control select2' style='width:65%'> 
                        <?php foreach ($country_names as $key => $value) {
                          $selected_country = ($key==$default_country) ? 'selected' : '';
                          $phonecode_attr = isset($phonecodes[$key]) ? $phonecodes[$key] : '';
                          echo '<option phonecode="'.$phonecode_attr.'" value="'.$key.'" '.$selected_country.'>'.$value.'</option>';
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("State & Zip");?></span>
                      </div>
                      <?php $wc_buyer_state =  isset($wc_buyer_location['state']) ? $wc_buyer_location['state'] : ''; ?>
                      <?php $wc_buyer_zip =  isset($wc_buyer_location['zip']) ? $wc_buyer_location['zip'] : ''; ?>
                      <input type="text" class="form-control"  name="state" value="<?php echo $wc_buyer_state;?>" placeholder="<?php echo $this->lang->line('State'); ?>">
                      <input type="text" class="form-control"  name="zip" value="<?php echo $wc_buyer_zip;?>" placeholder="<?php echo $this->lang->line('Zip'); ?>">
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Street & City");?></span>
                      </div>
                      <?php $wc_buyer_street =  isset($wc_buyer_location['street']) ? $wc_buyer_location['street'] : ''; ?>
                      <?php $wc_buyer_city =  isset($wc_buyer_location['city']) ? $wc_buyer_location['city'] : ''; ?>
                      <input type="text" class="form-control"  name="street" value="<?php echo $wc_buyer_street;?>" placeholder="<?php echo $this->lang->line('Street'); ?>">
                       <input type="text" class="form-control"  name="city" value="<?php echo $wc_buyer_city;?>" placeholder="<?php echo $this->lang->line('City'); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Email");?></span>
                      </div>
                      <input type="text" class="form-control" name="email" value="<?php echo $wc_email_bill;?>" placeholder="<?php echo $this->lang->line("Email"); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Phone");?></span>
                      </div>
                      <input type="text" class="form-control" name="mobile" value="<?php echo $wc_phone_bill?>" placeholder="<?php echo $this->lang->line("Phone Number"); ?>">
                    </div>
                  </div>             
              </div>
              <div class="col-12 col-md-6">

                  <div class="section-title">
                    <?php echo $this->lang->line("Delivery Address"); ?>
                    <small class="float-right">
                       <div class="custom-control custom-checkbox d-inline">
                        <input type="checkbox" class="custom-control-input" id="copy_address">
                        <label class="custom-control-label" for="copy_address"><small><?php echo $this->lang->line("Copy Billing Address"); ?></small></label>
                      </div>
                    </small>

                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Name");?></span>
                      </div>
                      <input type="text" class="form-control" name="buyer_first_name" placeholder="<?php echo $this->lang->line('First Name');?>" class="form-control" value="<?php echo !empty($webhook_data_final['buyer_first_name']) ? $webhook_data_final['buyer_first_name'] : $wc_first_name; ?>">
                      <input type="text" class="form-control" name="buyer_last_name" placeholder="<?php echo $this->lang->line('Last Name');?>" class="form-control" value="<?php echo !empty($webhook_data_final['buyer_last_name']) ? $webhook_data_final['buyer_last_name'] : $wc_last_name; ?>">
                    </div>
                  </div>                  
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Country");?></span>
                      </div>
                      <?php 
                      $default_country = isset($wc_buyer_location['country']) ? $wc_buyer_location['country'] : $store_country;
                      $country_names[''] =  $this->lang->line("Select Country");
                      // echo form_dropdown('buyer_country', $country_names,$default_country,"id='buyer_country' class='form-control select2' style='width:65%'");
                      ?>
                      <select id='buyer_country' name='buyer_country' class='form-control select2' style='width:65%'> 
                        <?php foreach ($country_names as $key => $value) {
                          $selected_country = ($key==$default_country) ? 'selected' : '';
                          $phonecode_attr = isset($phonecodes[$key]) ? $phonecodes[$key] : '';
                          echo '<option phonecode="'.$phonecode_attr.'" value="'.$key.'" '.$selected_country.'>'.$value.'</option>';
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("State & Zip");?></span>
                      </div>
                      <input type="text" class="form-control"  name="buyer_state" value="<?php echo !empty($webhook_data_final['buyer_state']) ? $webhook_data_final['buyer_state'] : $wc_buyer_state;?>" placeholder="<?php echo $this->lang->line('State'); ?>">
                      <input type="text" class="form-control"  name="buyer_zip" value="<?php echo !empty($webhook_data_final['buyer_zip']) ? $webhook_data_final['buyer_zip'] : $wc_buyer_zip;?>" placeholder="<?php echo $this->lang->line('Zip'); ?>">
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Street & City");?></span>
                      </div>
                      <input type="text" class="form-control"  name="buyer_address" value="<?php echo !empty($webhook_data_final['buyer_address']) ? $webhook_data_final['buyer_address'] : $wc_buyer_street;?>" placeholder="<?php echo $this->lang->line('Street'); ?>">
                      <input type="text" class="form-control"  name="buyer_city" value="<?php echo !empty($webhook_data_final['buyer_city']) ? $webhook_data_final['buyer_city'] : $wc_buyer_city;?>" placeholder="<?php echo $this->lang->line('City'); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Email");?></span>
                      </div>
                      <input type="text" class="form-control" name="buyer_email" value="<?php echo !empty($webhook_data_final['buyer_email']) ? $webhook_data_final['buyer_email'] : $wc_email_bill; ?>" placeholder="<?php echo $this->lang->line("Email"); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id=""><?php echo $this->lang->line("Phone");?></span>
                      </div>
                      <input type="text" class="form-control" name="buyer_mobile" value="<?php echo !empty($webhook_data_final['buyer_mobile']) ? $webhook_data_final['buyer_mobile'] : $wc_phone_bill; ?>" placeholder="<?php echo $this->lang->line("Phone Number"); ?>">
                    </div>
                  </div>                                    
              </div>
              <div class="col-12 col-md-4 offset-md-4 mt-4">
                <a href="#" id="proceed_checkout" class="btn btn-lg btn-outline-primary"><i class="fas fa-credit-card"></i> <?php echo $this->lang->line("Save & Proceed Checkout"); ?></a> 
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-12">
            <div id="payment_options"></div>          
          </div>
        </div>

      </div>
      <?php endif; ?>
      <?php if($i==0) :?>
          <div class="empty-state">
            <img class="img-fluid" style="height: 300px" src="<?php echo base_url('assets/img/drawkit/drawkit-full-stack-man-colour.svg'); ?>" alt="image">
             <h2 class="mt-0"><?php echo $this->lang->line("Cart is empty");?></h2>
             <p class="lead"><?php echo $this->lang->line("There is no product added to cart. Please browse our store and add them to cart to continue."); ?></p>
             <a href="<?php echo base_url('ecommerce/store/'.$store_unique_id."?subscriber_id=".$subscriber_id);?>" class="btn btn-outline-primary mt-4"><i class="fas fa-store-alt"></i> <?php echo $this->lang->line("Browse Store");?></a>
          </div>
      <?php endif; ?>
  </div>
</section>


<?php
$store_mapping = base_url("ecommerce/store/".$webhook_data_final['store_unique_id']);
if($subscriber_id!="") $store_mapping .= "?subscriber_id=".$subscriber_id;
$footer_copyright = "<a href='".$store_mapping."'>".$webhook_data_final['store_name']."</a>";
$footer_terms_use_link = $webhook_data_final['terms_use_link'];
$footer_refund_link = $webhook_data_final['refund_policy_link'];
$buyerphone =  !empty($webhook_data_final['buyer_mobile']) ? $webhook_data_final['buyer_mobile'] : $wc_phone_bill; 
?>
<div class="mt-3 mb-3 text-center">
  <?php echo "&copy".date("Y")." ".$footer_copyright;?><br>
    <?php if(isset($footer_terms_use_link) && !empty($footer_terms_use_link))echo "<a href='".base_url("ecommerce/terms_of_service/".$webhook_data_final['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Terms of service')."</a>"; ?>
    <?php if(isset($footer_refund_link) && !empty($footer_refund_link)) echo "&nbsp;&nbsp;<a href='".base_url("ecommerce/refund_policy/".$webhook_data_final['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Refund policy')."</a>"; ?>
</div>


<script>
  var base_url="<?php echo site_url(); ?>";
 
  $("document").ready(function()  {
    $(document).on('click','#apply_coupon',function(e){
     e.preventDefault();
     var coupon_code = $("#coupon_code").val();

     var cart_id = '<?php echo $order_no;?>';
     var subscriber_id = '<?php echo $subscriber_id;?>';
     $("#apply_coupon").addClass("btn-progress");
     $.ajax({
       type: 'POST',
       dataType: 'JSON',
       data: {coupon_code,cart_id,subscriber_id},
       url: '<?php echo base_url('ecommerce/apply_coupon'); ?>',
       success: function(response) {
        $("#apply_coupon").removeClass("btn-progress");
        if(response.status=='0') swal("<?php echo $this->lang->line('Error'); ?>", response.message, 'error');        
        else 
        {
          swal("<?php echo $this->lang->line('Success'); ?>", response.message, 'success');  
          location.reload();
        }  
       }
     });

    });

    $(document).on('click','#proceed_checkout',function(e){
    e.preventDefault();
    var input_name;
    var address_data = new Object();
    var input_error = false;
    $("#address_row :input").each(function(e)
    {  
      input_name = $(this).attr('name');
      input_value = $(this).val();
      if(typeof(input_name)!=='undefined')
      {
        if(input_value=="")
        {
          input_error = true;
          $("[name="+input_name+"]").addClass("is-invalid");
        }
        else 
        {
          address_data[input_name] = input_value;
          $("[name="+input_name+"]").removeClass("is-invalid");
        }
      }
    });

    if(input_error)
    {
      
      swal("<?php echo $this->lang->line('Error'); ?>", "<?php echo $this->lang->line('Please fill the required address fields to continue.');?>", 'error');
      return false;      
    }

    var cart_id = '<?php echo $order_no;?>';
    var subscriber_id = '<?php echo $subscriber_id;?>';

    var param = {'cart_id':cart_id,'subscriber_id':subscriber_id,'address_data':address_data};
    var mydata = JSON.stringify(param);
    $("#proceed_checkout").addClass("btn-progress");
    $.ajax({
      type: 'POST',
      dataType: 'JSON',
      data: {mydata:mydata},
      url: '<?php echo base_url('ecommerce/proceed_checkout'); ?>',
      success: function(response) {
       $("#proceed_checkout").removeClass("btn-progress");
       if(response.status=='0') swal("<?php echo $this->lang->line('Error'); ?>", response.message, 'error');        
       else 
       {
         $("#payment_options").html(response.html);
         $("#manual-payment-ins-modal .modal-body").html(response.manual_payment_instruction);
         // $("#proceed_checkout").parent().hide();
       }  
      }
    });

    });

    $('.modal').on("hidden.bs.modal", function (e) { 
      if ($('.modal:visible').length) { 
        $('body').addClass('modal-open');
      }
    });

  });
</script>


<script>
  $(document).ready(function() {

    $(document).on('click', '#manual-payment-button', function() {
      $('#payment_modal').modal('toggle');
      $('#manual-payment-modal').modal();
    });

    setTimeout(function(){ 
      $("#country").trigger('change');
      $("#buyer_country").trigger('change'); 
    }, 500);

    $(document).on('click', '#copy_address', function(e) {
      $("input[name='buyer_email']").val($("input[name='email']").val());
      $("input[name='buyer_mobile']").val($("input[name='mobile']").val());
      $("input[name='buyer_address']").val($("input[name='street']").val());
      $("input[name='buyer_city']").val($("input[name='city']").val());
      $("input[name='buyer_state']").val($("input[name='state']").val());
      $("input[name='buyer_zip']").val($("input[name='zip']").val());
      $("#buyer_country").val($("#country").val()).trigger('change');
      $(this).parent().parent().hide();
    });

    $(document).on('change', '#country', function(e) {
      var xphone = '<?php echo $wc_phone_bill;?>';
      var setval = $('option:selected', this).attr('phonecode');
      if(xphone=='') $("input[name='mobile']").val(setval);
    });

    $(document).on('change', '#buyer_country', function(e) {
      var xphone = '<?php echo $buyerphone;?>';
      var setval = $('option:selected', this).attr('phonecode');
      if(xphone=='') $("input[name='buyer_mobile']").val(setval);
    });


    $(document).on('click', '#mollie-payment-button', function(e) {
      e.preventDefault();
      var redirect_url=$(this).attr('href');
      window.top.location.href=redirect_url;
    });

    $(document).on('click', '#cod-payment-button', function(e) {
      e.preventDefault();
      var cart_id = '<?php echo $order_no;?>';
      var subscriber_id = '<?php echo $subscriber_id;?>';
      $("#cod-payment-button").addClass("btn-progress");
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: {cart_id,subscriber_id},
        url: '<?php echo base_url('ecommerce/cod_payment'); ?>',
        success: function(response) {
         $("#cod-payment-button").removeClass("btn-progress");
         if (response.error)  swal("<?php echo $this->lang->line('Error'); ?>", response.error, 'error');        
         else window.location.href = response.redirect;
         } 
        
      });
    });


    // Handles form submit
    $(document).on('click', '#manual-payment-submit', function() {
      
      // Reference to the current el
      var that = this;
      
      // Shows spinner
      $(that).addClass('btn-progress');
      var formData = new FormData($("#manaul_payment_data")[0]);

      $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        dataType: 'JSON',
        url: '<?php echo base_url('ecommerce/manual_payment'); ?>',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.success) {

            $(that).removeClass('btn-progress');
            empty_form_values();
            $('#manual-payment-modal').modal('hide');
            window.location.href = response.redirect;
          }

          if (response.error) {

            $(that).removeClass('btn-progress');

            var span = document.createElement("span");
            span.innerHTML = response.error;

            swal({
              icon: 'error',
              title: '<?php echo $this->lang->line('Error'); ?>',
              content:span,
            });
          }
        },
        error: function(xhr, status, error) {
          $(that).removeClass('btn-progress');
        },
      });
    });

    $('#manual-payment-modal').on('hidden.bs.modal', function (e) {
        $('#manaul_payment_data').trigger("reset");  
    });

    // Empties form values
    function empty_form_values() {
      $('#paid-amount').val('');
      $('#additional-info').val('');
      $('#paid-currency').prop("selectedIndex", 0);
      $("#manual-payment-file").val('');
      // Clears added file
    }


    $(document).on('click','.delete_item',function(e){
       e.preventDefault();
       var id = $(this).attr("data-id");
       var subscriber_id = '<?php echo $subscriber_id;?>';
       var cart_id = '<?php echo $order_no;?>';
       $.ajax({
         type: 'POST',
         dataType: 'JSON',
         data: {id,cart_id,subscriber_id},
         url: '<?php echo base_url('ecommerce/delete_cart_item'); ?>',
         success: function(response)
         {
            if(response.status=='0') 
            swal("<?php echo $this->lang->line('Error'); ?>", response.message, 'error');          
            else location.reload();
         }
       });

    });

  });
</script>


<div class="modal fade" role="dialog" id="manual-payment-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="min-width:50%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> <?php echo $this->lang->line("Manual payment");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container p-0">

          <form action="#" method="POST" id="manaul_payment_data" enctype="multipart/form-data">
            <?php if (isset($manual_payment_instruction) && ! empty($manual_payment_instruction)): ?>
            <div class="row">
              <div class="col-lg-12 mb-2">
                <!-- Manual payment instruction -->
                <h6  class="display-6"><i class="far fa-lightbulb"></i> <?php echo $this->lang->line('Manual payment instructions'); ?></h6>
                    <?php echo $manual_payment_instruction; ?>
              </div>
            </div>
            <?php endif; ?>

            <input type="hidden" name="cart_id" id="cart_id" value="<?php echo $order_no;?>">
            <input type="hidden" name="subscriber_id" id="subscriber_id" value="<?php echo $subscriber_id;?>">

            <!-- Paid amount and currency -->
            <div class="row">
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label for="paid-amount"><?php echo $this->lang->line('Paid Amount'); ?></label>
                  <input type="number" name="paid-amount" id="paid-amount" class="form-control" min="1">
                  <input type="hidden" id="selected-package-id">
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label for="paid-currency"><?php echo $this->lang->line('Currency'); ?></label>              
                  <?php echo form_dropdown('paid-currency', $currency_list, $currency, ['id' => 'paid-currency', 'class' => 'form-control select2','style'=>'width:100%']); ?>
                </div>
              </div>
            </div>          
            
            <div class="row">
              <!-- Additional Info -->
              <div class="col-12 mb-2">
                <div class="form-group">
                  <label for="paid-amount"><?php echo $this->lang->line('Additional Info'); ?></label>
                  <textarea name="additional-info" id="additional-info" class="form-control"></textarea>
                </div>
              </div>
              <!-- Image upload - Dropzone -->
              <div class="col-12">
                <div class="form-group">
                  <label style="width:100%;">
                    <?php echo $this->lang->line('Attachment'); ?> <?php echo $this->lang->line('(Max 5MB)');?> 
                    <span class="red float-right"><?php echo $this->lang->line("Allowed types");?> : pdf, doc, txt, png, jpg & zip</span>
                  </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="manual-payment-file" name="manual-payment-file">
                    <label class="custom-file-label" for="manual-payment-file">Choose file</label>
                  </div>
                  
                </div>
              </div>
            </div>
          </form>

        </div><!-- ends container -->
      </div><!-- ends modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" id="manual-payment-submit" class="btn btn-primary btn-lg"><i class="fas fa-check-circle"></i> <?php echo $this->lang->line('Submit'); ?></button>      
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="manual-payment-ins-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-info-circle"></i> <?php echo $this->lang->line("Manual Payment Instructions");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>

      <div class="modal-footer bg-whitesmoke br">             
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>

<script>

$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>


<style type="text/css">
  .form-group{margin-bottom: 10px;}
  .section .section-title{margin:20px 0 20px 0;}
  .input-group-prepend{width:35%;}
  .input-group-text{width:100%;}
  ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: #ccc !important;
    opacity: 1  !important;; /* Firefox */
  }
  :-ms-input-placeholder { /* Internet Explorer 10-11 */
    color: #ccc !important;
  }
  ::-ms-input-placeholder { /* Microsoft Edge */
    color: #ccc !important;
  }
  #proceed_checkout{font-weight: bold;font-size: 14px;height: 55px;line-height: 55px;padding-top: 0;padding-bottom: 0;width: 100%;}
  .stripe-button-el,.stripe-button-el span{
    -moz-box-shadow: none;
    -ms-box-shadow: none;
    -o-box-shadow: none;
    box-shadow: none;
    width:100%
  }
  .stripe-button-el span{height: 50px;line-height: 50px;}
  #payment_options button:not(.stripe-button-el),#mollie-payment-button{
    font-size:14px;
    font-weight:bold;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    background: #1275ff;
    background-image: -webkit-linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4);
    background-image: -moz-linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4);
    background-image: -ms-linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4);
    background-image: -o-linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4);
    text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
    -webkit-border-radius: 4px;
    -moz-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    border-radius: 6px;
    /*margin-top:-2px;*/
    width: 100%;
    /*line-height: 50px;*/
    height: 52px;
    border-bottom-color:#015e94;
    color:#fff;
    border:none;
    cursor: pointer;
    display: inline-block;
  }
  #mollie-payment-button{line-height: 52px;text-align: center;}
  #mollie-payment-button:hover{text-decoration: none;}

  #payment_options button:hover:not(.stripe-button-el){ border-bottom-color:#015e94 !important};

 }
</style>