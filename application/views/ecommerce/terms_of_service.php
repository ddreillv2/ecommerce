<section class="section">
  <div class="section-header">

    <?php $store_unique_id =  isset($store_data['store_unique_id']) ? $store_data['store_unique_id'] : "";?>
    <h1 class="">
      <?php
      $store_name_logo =  ($store_data['store_logo']!='') ? '<img alt="'.$store_data['store_name'].'" class="img-fluid" src="'.base_url("upload/ecommerce/".$store_data['store_logo']).'">' : $store_data['store_name'];      
      ?>
      <a href="<?php echo base_url('ecommerce/store/'.$store_unique_id."?subscriber_id=".$subscriber_id); ?>"><?php echo $store_name_logo; ?></a>   
    </h1>
    
    <div class="section-header-breadcrumb">
      <h1><?php echo $this->lang->line("Terms of service");?></h1>      
    </div>
  </div>


  <div class="section-body">
    <div class="card">
      <div class="card-body mt-3 mb-3">
        <?php echo $store_data['terms_use_link'];?>
      </div>
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
<?php include(APPPATH."views/ecommerce/common_style.php"); ?>