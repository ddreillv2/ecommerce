<section class="section">
  <div class="section-header">
    <h1>
      <?php 
      $subscriber_id = isset($_GET['subscriber_id']) ? $_GET['subscriber_id'] : "";
      $store_link = base_url("ecommerce/store/".$product_data['store_unique_id']);
      if($subscriber_id!='') $store_link.='?subscriber_id='.$subscriber_id;
      $store_name_logo = ($product_data['store_logo']!='') ? '<img alt="'.$product_data['store_name'].'" class="img-fluid" src="'.base_url("upload/ecommerce/".$product_data['store_logo']).'">' : $product_data['store_name'];
      echo $store_name_logo = "<a href='".$store_link."'>".$store_name_logo."</a>";
      $currency = isset($ecommerce_config['currency']) ? $ecommerce_config['currency'] : "USD";
      $currency_icon = isset($currency_icons[$currency]) ? $currency_icons[$currency] : "$";
      $currency_position = isset($ecommerce_config['currency_position']) ? $ecommerce_config['currency_position'] : "left";
      $decimal_point = isset($ecommerce_config['decimal_point']) ? $ecommerce_config['decimal_point'] : 0;
      $thousand_comma = isset($ecommerce_config['thousand_comma']) ? $ecommerce_config['thousand_comma'] : '0';

      // $attribute_map="";
      // if($product_data['attribute_ids']!='') $attribute_map = mec_attribute_map($attribute_list,$product_data['attribute_ids']);

      $product_link = base_url("ecommerce/product/".$product_data['id']); 
      if($subscriber_id!="") $product_link .= "?subscriber_id=".$subscriber_id;

      $current_cart_id = isset($current_cart['cart_id']) ? $current_cart['cart_id'] : 0;
      $cart_count = isset($current_cart['cart_count']) ? $current_cart['cart_count'] : 0;
      $current_cart_url = base_url("ecommerce/cart/".$current_cart_id); 
      if($subscriber_id!="") $current_cart_url .= "?subscriber_id=".$subscriber_id;

      $current_cart_data = (isset($current_cart["cart_data"]) && is_array($current_cart["cart_data"])) ? $current_cart["cart_data"] : array();

      $have_attributes = false;
      $product_attributes = array_filter(explode(',', $product_data['attribute_ids']));
      if(is_array($product_attributes) && !empty($product_attributes)) $have_attributes = true;

      $quantity_in_cart = 0;
      if(!$have_attributes) $quantity_in_cart = isset($current_cart_data[$product_data['id']]["quantity"]) ? $current_cart_data[$product_data['id']]["quantity"] : 0;
      else if(isset($_GET['quantity']))  $quantity_in_cart = $_GET['quantity'];
      ?>        
    </h1>
    <div class="section-header-breadcrumb">
        <!-- <div class="breadcrumb-item"><a href="<?php echo $store_link; ?>"><?php echo $product_data['store_name'];?></a></div> -->
        <div class="breadcrumb-item"><?php echo $product_data['product_name'];?></div>
        <a class="badge badge-danger text-white" id="cart_count_display" href="<?php echo $current_cart_url;?>" style="margin-left: 10px;<?php if($subscriber_id=="" || $current_cart_id==0) echo 'display:none;';?>"><i class="fas fa-shopping-cart"></i> <?php echo $cart_count; ?>
        </a>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <article class="article article-style-c" style="min-height: 497px">            
          <img style="height: 345px;width: 100%" src="<?php echo ($product_data['thumbnail']!='') ? base_url('upload/ecommerce/'.$product_data['thumbnail']) : base_url('assets/img/products/product-1.jpg'); ?>"/>
          <?php echo mec_display_price($product_data['original_price'],$product_data['sell_price'],$currency_icon,'4',$currency_position,$decimal_point,$thousand_comma); ?>           
          <div class="article-details">
            
            <div class="form-group">
              <div class="input-group mb-3">
                <div class="input-group-append">
                  <button class="btn btn-outline-primary add_to_cart" data-product-id="<?php echo $product_data['id'];?>" data-attributes="<?php echo $product_data['attribute_ids'];?>" data-action="remove" type="button" style="border-radius: 4px 0 0 4px;min-width: 120px;" data-toggle="tooltip" title="<?php echo $this->lang->line('Remove 1 from Cart'); ?>"><i class="fas fa-minus-circle"></i> <?php echo $this->lang->line('Remove'); ?> <?php echo $this->lang->line('(-1)');?></button>
                </div>
                <input type="text" class="form-control text-center" data-toggle="tooltip" title="<?php echo $this->lang->line('Currently added to cart');?>" id="item_count" readonly value="<?php echo $quantity_in_cart;?>">
                <div class="input-group-append">
                  <button style="min-width: 120px;" class="btn btn-outline-primary add_to_cart" data-product-id="<?php echo $product_data['id'];?>"  data-attributes="<?php echo $product_data['attribute_ids'];?>" data-action="add" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('Add 1 to Cart');?>"><i class="fas fa-cart-plus"></i> <?php echo $this->lang->line('Add');?> <?php echo $this->lang->line('(+1)');?></button>
                </div>
              </div>
            </div>

            <!-- <a href="" class="btn btn-primary btn-lg btn-icon icon-left add_to_cart" data-product-id="<?php echo $product_data['id'];?>" <?php echo $have_attributes ? 'data-have-attribute="1"' :  'data-have-attribute="0"';?> data-action='add'><i class="fas fa-cart-plus"></i> <?php echo $this->lang->line("Add to Cart"); ?></a> -->

            <div class="text-center">
              
              <a href="" id="single_buy_now" class="btn btn-primary add_to_cart buy_now btn-lg <?php echo ($cart_count==0 && $product_data['attribute_ids']=='')?'':'d-none'; ?>" data-attributes="<?php echo $product_data['attribute_ids'];?>" data-product-id="<?php echo $product_data['id'];?>" data-action='add'><i class="fas fa-credit-card"></i> <?php echo $this->lang->line("Buy Now"); ?></a>                    
              
              <a href="<?php echo $current_cart_url;?>" id="single_visit_store" class="btn btn-outline-dark btn-lg <?php if($cart_count==0) echo 'd-none';?>"><i class="fas fa-shopping-basket"></i> <?php echo $this->lang->line("Visit Cart"); ?></a>
              
            </div>
             
          </div>
        </article>
      </div>     
      <div class="col-12 col-sm-12 col-md-6 col-lg-8">
        <div class="card" style="margin-bottom: 0;border-radius: 3px 3px 0 0;">
          <div class="card-header">
             <h4 style="font-size: 20px;" class="full_width">
              <?php echo $product_data['product_name'];?>
              <span class="float-right" id="calculated_price_basedon_attribute"><?php echo mec_display_price($product_data['original_price'],$product_data['sell_price'],$currency_icon,'1',$currency_position,$decimal_point,$thousand_comma);?></span>          
            </h4>
          </div>
        </div>

        <div class="hero bg-white" style="padding:25px;border-radius: 0 0 3px 3px;">
          <div class="hero-inner">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link <?php echo $have_attributes ? 'active show' : '';?>" id="details-tab2" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false"><?php echo $this->lang->line("Options"); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo !$have_attributes ? 'active show' : '';?>" id="description-tab2" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="false"><?php echo $this->lang->line("Details"); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link"  id="purchase_note-tab2" data-toggle="tab" href="#purchase_note" role="tab" aria-controls="purchase_note" aria-selected="false"><?php echo $this->lang->line("Note"); ?></a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content" style="min-height: 333px">
              <div class="tab-pane fade <?php echo $have_attributes ? 'active show' : '';?>" id="details" role="tabpanel" aria-labelledby="details-tab2">
                <div class="row">
                 <?php if($have_attributes) 
                 { ?>
                  <div class="col-12 col-lg-6">
                    <ul class="list-group">
                      <?php                      
                      $attr_count = 0;
                      foreach ($attribute_list as $key => $value) 
                      {
                        if(in_array($value["id"], $product_attributes))
                        {
                          $attr_count++;
                          $name = "attribute_".$attr_count;
                          $options_array = json_decode($value["attribute_values"],true);
                          $options = array(''=>$this->lang->line("Choose"));
                          foreach ($options_array as $key2 => $value2)
                          {
                            $options[$value2] = $value2;
                          }
                          $url_option = "option".$value["id"];
                          $selected = isset($_GET[$url_option]) ? $_GET[$url_option] : "";
                          $properties = 'class="selecttwo form-control options" data-attr="'.$value["id"].'" style="width:150px;"';

                          echo 
                          '<li class="list-group-item d-flex justify-content-between align-items-center" style="padding-top:20px;">
                              '.$value["attribute_name"].'
                              <div class="form-group" style="margin-bottom: 10px;">';
                               echo form_dropdown($name, $options,$selected,$properties); 
                               echo '   
                              </div>
                            </li>
                          ';
                        }
                      }
                      ?>
                    </ul>
                  </div>
                 <?php 
                 } ?>
                 <div class="<?php echo $have_attributes ? 'col-12 col-lg-6' : 'col-12';?>">
                    <div class="hero bg-light text-drk text-center" style="padding: 30px 20px;height: 100%;">
                      <div class="hero-inner">                        
                        <p class="lead">                          
                          <?php echo isset($category_list[$product_data['category_id']]) ? $category_list[$product_data['category_id']] : $this->lang->line("Uncategorised");?>
                        </p>
                        <h6>                          
                          <?php echo $this->lang->line("Sales"); ?> : <?php echo $product_data['sales_count']." ".$this->lang->line("Units");?>  <br>
                        </h6>
                        <h6>
                          <?php if($product_data['stock_display']=='1') echo$this->lang->line("Stock")." : ".$product_data['stock_item']." ".$this->lang->line("Units");?>
                        </h6>
                        <div class="mt-3">
                          <a href="<?php echo $store_link; ?>" class="btn btn-outline-primary btn-icon icon-left"><i class="fas fa-store-alt"></i> <?php echo $this->lang->line("Visit")." ".$product_data['store_name']; ?></a>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div class="tab-pane fade <?php echo !$have_attributes ? 'active show' : '';?>" id="description" role="tabpanel" aria-labelledby="description-tab2">
                <?php echo $product_data['product_description']; ?>
              </div>
              <div class="tab-pane fade" id="purchase_note" role="tabpanel" aria-labelledby="purchase_note-tab2">
                <?php echo $product_data['purchase_note']; ?>
              </div>
            </div>
            
          </div>
        </div>
                
      </div>
    </div>

      <?php 
      if($product_data['featured_images']!="")
      {
        $featured_images_array = explode(',', $product_data['featured_images']); ?>     
        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4><?php echo $this->lang->line("Featured Images"); ?></h4>
              </div>
              <div class="card-body">
                  <div class="row">                 
                      <?php 
                      $slide=0;
                      foreach ($featured_images_array as $key => $value)
                      {
                        $slide++;
                          echo ' <div class="col-12 col-sm-6 col-md-6 col-lg-4" style="min-height:350px;"><div class="gallery gallery-md"><div class="gallery-item img-thumbnail" data-image="'.base_url('upload/ecommerce/'.$value).'"></div></div></div>';
                      } ?>
                    </div>
              </div>
            </div>
          </div>
        </div>
      <?php 
      } ?>

  </div>
</section>

<?php
$store_mapping = base_url("ecommerce/store/".$product_data['store_unique_id']);
if($subscriber_id!="") $store_mapping .= "?subscriber_id=".$subscriber_id;
$footer_copyright = "<a href='".$store_mapping."'>".$product_data['store_name']."</a>";
$footer_terms_use_link = $product_data['terms_use_link'];
$footer_refund_link = $product_data['refund_policy_link'];
?>
<div class="mt-3 mb-3 text-center">
  <?php echo "&copy".date("Y")." ".$footer_copyright;?><br>
    <?php if(isset($footer_terms_use_link) && !empty($footer_terms_use_link))echo "<a href='".base_url("ecommerce/terms_of_service/".$product_data['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Terms of service')."</a>"; ?>
    <?php if(isset($footer_refund_link) && !empty($footer_refund_link)) echo "&nbsp;&nbsp;<a href='".base_url("ecommerce/refund_policy/".$product_data['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Refund policy')."</a>"; ?>
</div>

<?php include(APPPATH."views/ecommerce/cart_js.php"); ?>
<?php include(APPPATH."views/ecommerce/cart_style.php"); ?>
<?php include(APPPATH."views/ecommerce/common_style.php"); ?>

<script>
  $(document).ready(function() {

    var current_product_id = "<?php echo $current_product_id; ?>";
    var current_store_id = "<?php echo $current_store_id; ?>";
    var currency_icon = "<?php echo $currency_icon; ?>";
    var currency_position = "<?php echo $currency_position; ?>";
    var decimal_point = "<?php echo $decimal_point; ?>";
    var thousand_comma = "<?php echo $thousand_comma; ?>";

    var attribute_info = new Object();
    $(".options").each(function() {
        if($(this).val()=="") exit=true;            
        temp = $(this).attr('data-attr');
        attribute_info[temp] = $(this).val();
    });
    $.ajax({
      context: this,
      type:'POST',
      dataType:'JSON',
      url:"<?php echo site_url();?>ecommerce/get_price_basedon_attribues",
      data:{product_id:current_product_id,current_store_id:current_store_id,attribute_info:attribute_info,currency_icon:currency_icon,currency_position:currency_position,decimal_point:decimal_point,thousand_comma:thousand_comma},
      success:function(response){
        $("#calculated_price_basedon_attribute").html(response.price_html);
      }
    });

    
    $(document).on('change','.options',function(e){
      var attribute_info = new Object();
      $(".options").each(function() {
          if($(this).val()=="") exit=true;            
          temp = $(this).attr('data-attr');
          attribute_info[temp] = $(this).val();
      });
      $.ajax({
        context: this,
        type:'POST',
        dataType:'JSON',
        url:"<?php echo site_url();?>ecommerce/get_price_basedon_attribues",
        data:{product_id:current_product_id,current_store_id:current_store_id,attribute_info:attribute_info,currency_icon:currency_icon,currency_position:currency_position,decimal_point:decimal_point,thousand_comma:thousand_comma},
        success:function(response){
          $("#calculated_price_basedon_attribute").html(response.price_html);
        }
      });
    });

  });
</script>


