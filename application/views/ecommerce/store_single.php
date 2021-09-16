<section class="section">
  <div class="section-header">
    <h1>
      <?php 
      // echo ($store_data['store_logo']!='') ? '<img style="height:50px;" alt="'.$store_data['store_name'].'" class="img-fluid" src="'.base_url("upload/ecommerce/".$store_data['store_logo']).'">' : $store_data['store_name'];
      $currency = isset($ecommerce_config['currency']) ? $ecommerce_config['currency'] : "USD";
      $currency_icon = isset($currency_icons[$currency]) ? $currency_icons[$currency] : "$";
      $currency_position = isset($ecommerce_config['currency_position']) ? $ecommerce_config['currency_position'] : "left";
      $decimal_point = isset($ecommerce_config['decimal_point']) ? $ecommerce_config['decimal_point'] : 0;
      $thousand_comma = isset($ecommerce_config['thousand_comma']) ? $ecommerce_config['thousand_comma'] : '0';
      $currency_left = $currency_right = "";
      if($currency_position=='left') $currency_left = $currency_icon;
      if($currency_position=='right') $currency_right = $currency_icon;

      $form_action = base_url('ecommerce/store/'.$store_data['store_unique_id']);
      $subscriber_id = isset($_GET['subscriber_id']) ? $_GET['subscriber_id'] : "";
      if($subscriber_id!="") $form_action .= "?subscriber_id=".$subscriber_id;

      $current_cart_id = isset($current_cart['cart_id']) ? $current_cart['cart_id'] : 0;
      $cart_count = isset($current_cart['cart_count']) ? $current_cart['cart_count'] : 0;
      $current_cart_url = base_url("ecommerce/cart/".$current_cart_id); 
      if($subscriber_id!="") $current_cart_url .= "?subscriber_id=".$subscriber_id;

      $store_link = base_url("ecommerce/store/".$store_data['store_unique_id']);
      if($subscriber_id!='') $store_link.='?subscriber_id='.$subscriber_id;
      $store_name_logo = ($store_data['store_logo']!='') ? '<img alt="'.$store_data['store_name'].'" class="img-fluid" src="'.base_url("upload/ecommerce/".$store_data['store_logo']).'">' : $store_data['store_name'];
      echo $store_name_logo = "<a href='".$store_link."'>".$store_name_logo."</a>";

      ?>        
    </h1>
    <div class="section-header-breadcrumb">
      <form method="POST" action="<?php echo $form_action; ?>">
        <div class="form-row">
          <div class="col-4">
            <input type="text" class="form-control" name="search" id="search" value= "<?php echo $this->session->userdata('search_search');?>" placeholder="<?php echo $this->lang->line("Search"); ?>">
          </div>
          <div class="col-4">
            <?php
            $url_cat =  isset($_GET["category"]) ? $_GET["category"] : "";
            $default_cat = ($url_cat!="") ? $url_cat : $this->session->userdata('search_category_id');
            $category_list[''] = $this->lang->line("Category");
            echo form_dropdown('category_id', $category_list,$default_cat,'class="form-control selecttwo" id="category_id" style="width:100%"');
            ?>
          </div>
          <div class="col-4">
            <?php 
            echo form_dropdown('sort_by', $sort_dropdown,$this->session->userdata('search_sort_by'),'class="form-control selecttwo" id="sort_by" style="width:100%"');
            ?>
          </div>
          <input type="submit" id="submit" value="" class="d-none">
        </div>
      </form>
      <a class="badge badge-danger text-white" id="cart_count_display" href="<?php echo $current_cart_url;?>" style="margin-left: 10px;<?php if($subscriber_id=="" || $current_cart_id==0) echo 'display:none;';?>"><i class="fas fa-shopping-cart"></i> <?php echo $cart_count; ?>
      </a>
    </div>
  </div>

  <div class="section-body">
    <?php
    if(empty($product_list))
    { ?>
      <div class="card" id="nodata">
        <div class="card-body">
          <div class="empty-state">
            <img class="img-fluid" style="height: 300px" src="<?php echo base_url('assets/img/drawkit/drawkit-full-stack-man-colour.svg'); ?>" alt="image">
             <h2 class="mt-0"><?php echo $this->lang->line("We could not find any product.");?></h2>
             <?php if($_POST) { ?>
             <a href="<?php echo $_SERVER['QUERY_STRING'] ? current_url().'?'.$_SERVER['QUERY_STRING'] : current_url(); ?>" class="btn btn-outline-primary mt-4"><i class="fas fa-arrow-circle-right"></i> <?php echo $this->lang->line("Search Again");?></a>
             <?php } ?>
          </div>
        </div>
      </div>
    <?php
    }?>
    <div class="row">
      <?php
      foreach ($product_list as $key => $value) 
      {  
        $product_link = base_url("ecommerce/product/".$value['id']); 
        if($subscriber_id!="") $product_link .= "?subscriber_id=".$subscriber_id;
        // $attribute_map="";
        // if($value['attribute_ids']!='') $attribute_map = mec_attribute_map($attribute_list,$value['attribute_ids']);
        ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
          <article class="article article-style-c">            
            <a href="<?php echo $product_link;?>"><img style="height: 345px;width: 100%" src="<?php echo ($value['thumbnail']!='') ? base_url('upload/ecommerce/'.$value['thumbnail']) : base_url('assets/img/products/product-1.jpg'); ?>"/></a>
            <?php echo mec_display_price($value['original_price'],$value['sell_price'],$currency_icon,'4',$currency_position,$decimal_point,$thousand_comma); ?>           
            <div class="article-details">
              <div class="article-title">                
                <h2>
                  <a href="<?php echo $product_link;?>"><?php echo $value['product_name'];?></a> 
                  <?php //if($attribute_map!='') echo '<i class="fas fa-info-circle text-primary pointer" data-toggle="tooltip" title="'.$attribute_map." : ".$this->lang->line("can be selected on checkout.").'"></i>';?>
                  <a class="float-right" data-toggle="tooltip" title="<?php echo $this->lang->line('Sales Count'); ?>"><i class="fas fa-shopping-bag"></i> <?php echo $value['sales_count'];?></a>
                </h2>                
              </div>
              <div class="article-category">
                <a class="float-right"><?php echo isset($category_list[$value['category_id']]) ? $category_list[$value['category_id']] : $this->lang->line("Uncategorised");?></a>
                <h6 class='text-center d-inline' style="font-size: 15px">
                  <?php 
                  echo mec_display_price($value['original_price'],$value['sell_price'],$currency_icon,'1',$currency_position,$decimal_point,$thousand_comma);
                  ?>                    
                </h6>
              </div>
              <!-- <p>Description</p> -->
              <br>

              <?php if($value['attribute_ids']=='') 
              {?>
                <a href="" class="btn btn-primary add_to_cart" data-attributes="<?php echo $value['attribute_ids'];?>" data-product-id="<?php echo $value['id'];?>" data-action='add'><i class="fas fa-cart-plus"></i> <?php echo $this->lang->line("Add to Cart"); ?></a>
              <?php 
              } 
              else 
              { ?>
                <a href="<?php echo $product_link;?>" class="btn btn-primary"><i class="fas fa-palette"></i> <?php echo $this->lang->line("Choose Options"); ?></a>
              <?php 
              } ?>

              

              <?php 
              if($cart_count==0 && $value['attribute_ids']=='') 
              {?>
                <a href="" class="btn btn-outline-primary add_to_cart buy_now float-right" data-attributes="<?php echo $value['attribute_ids'];?>" data-product-id="<?php echo $value['id'];?>" data-action='add'><i class="fas fa-credit-card"></i> <?php echo $this->lang->line("Buy Now"); ?></a>
              <?php 
              } 
              if($cart_count>0)  
              { ?>
                <a href="<?php echo $current_cart_url;?>" class="btn btn-outline-dark float-right"><i class="fas fa-shopping-basket"></i> <?php echo $this->lang->line("Visit Cart"); ?></a>
              <?php 
              } ?>
            </div>
          </article>
        </div>
      <?php
      } ?>      
    </div>
  </div>

</section>

<?php
$store_mapping = base_url("ecommerce/store/".$store_data['store_unique_id']);
if($subscriber_id!="") $store_mapping .= "?subscriber_id=".$subscriber_id;
$footer_copyright = "<a href='".$store_mapping."'>".$store_data['store_name']."</a>";
$footer_terms_use_link = $store_data['terms_use_link'];
$footer_refund_link = $store_data['refund_policy_link'];
?>
<div class="mt-3 mb-3 text-center">
  <?php echo "&copy".date("Y")." ".$footer_copyright;?><br>
    <?php if(isset($footer_terms_use_link) && !empty($footer_terms_use_link))echo "<a href='".base_url("ecommerce/terms_of_service/".$store_data['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Terms of service')."</a>"; ?>
    <?php if(isset($footer_refund_link) && !empty($footer_refund_link)) echo "&nbsp;&nbsp;<a href='".base_url("ecommerce/refund_policy/".$store_data['store_unique_id']."/".$subscriber_id)."'>".$this->lang->line('Refund policy')."</a>"; ?>
</div>


<script> 
  var url_cat =  '<?php echo $url_cat;?>';
  $("document").ready(function()  {
    if(url_cat!="" )$("#submit").click();
    $(document).on('keyup','#search',function(e){
      // var keycode = (e.keyCode ? e.keyCode : e.which);
      // if(keycode == '13'){
      //   $("#submit").click();          
      // }
      var len = $("#search").val().length;
      if(len>=3 || len==0) $("#submit").click();
    });
    // $(document).on('blur','#search',function(e){
    //   $("#submit").click();
    // });
    $(document).on('change','#category_id',function(e){
      $("#submit").click();
    });
    $(document).on('change','#sort_by',function(e){
      $("#submit").click();
    });
  });
</script>


<?php include(APPPATH."views/ecommerce/cart_js.php"); ?>
<?php include(APPPATH."views/ecommerce/cart_style.php"); ?>
<?php include(APPPATH."views/ecommerce/common_style.php"); ?>