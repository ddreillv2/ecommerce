<section class="section">
  <div class="section-header">

    <?php $store_unique_id =  isset($store_data['store_unique_id']) ? $store_data['store_unique_id'] : "";?>
    <h1 class="">
      <?php
      $store_name_logo =  ($store_data['store_logo']!='') ? '<img alt="'.$store_data['store_name'].'" class="img-fluid" src="'.base_url("upload/ecommerce/".$store_data['store_logo']).'">' : $store_data['store_name'];
      $currency = isset($ecommerce_config['currency']) ? $ecommerce_config['currency'] : "USD";
      $currency_icon = isset($currency_icons[$currency]) ? $currency_icons[$currency] : "$";

      $form_action = base_url('ecommerce/store/'.$store_data['store_unique_id']);
      $subscriber_id = isset($_GET['subscriber_id']) ? $_GET['subscriber_id'] : "";
      if($subscriber_id!="") $form_action .= "?subscriber_id=".$subscriber_id;

      $current_cart_id = isset($current_cart['cart_id']) ? $current_cart['cart_id'] : 0;
      $cart_count = isset($current_cart['cart_count']) ? $current_cart['cart_count'] : 0;
      $current_cart_url = base_url("ecommerce/cart/".$current_cart_id); 
      if($subscriber_id!="") $current_cart_url .= "?subscriber_id=".$subscriber_id;
      ?>
      <a href="<?php echo base_url('ecommerce/store/'.$store_unique_id."?subscriber_id=".$subscriber_id); ?>"><?php echo $store_name_logo; ?></a>   
    </h1>
    
    <div class="section-header-breadcrumb">
      <h1><i class="fas fa-shopping-cart"></i> <?php echo $page_title;?></h1>      
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <style type="text/css">
    @media (max-width: 575.98px) {
      #search_store_id{width: 75px;}
      #search_status{width: 80px;}
      #select2-search_store_id-container,#select2-search_status-container,#search_value{padding-left: 8px;padding-right: 5px;}
    }
  </style>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">
            <div class="row">
              <div class="col-6 col-md-4">
                <?php
                $status_list[''] = $this->lang->line("Status");                
                echo 
                '<div class="input-group mb-3" id="searchbox">
                  <div class="input-group-prepend d-none">
                    <input type="text" value="'.$store_id.'" name="search_store_id" id="search_store_id">
                    <input type="text" value="'.$subscriber_id.'" name="search_subscriber_id" id="search_subscriber_id">
                  </div>
                  <div class="input-group-prepend d-none">
                    '.form_dropdown('search_status',$status_list,'','class="form-control select2" id="search_status"').'
                  </div>
                  <input type="text" class="form-control" id="search_value" autofocus name="search_value" placeholder="'.$this->lang->line("Search...").'">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="search_action"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">'.$this->lang->line("Search").'</span></button>
                  </div>
                </div>'; ?>                                          
              </div>

              <div class="col-6 col-md-8">

            	<?php
			          echo $drop_menu ='<a href="javascript:;" id="search_date_range" class="btn btn-primary btn-lg float-right icon-left btn-icon"><i class="fas fa-calendar"></i> '.$this->lang->line("Choose Date").'</a><input type="hidden" id="search_date_range_val">';
			        ?>

                                         
              </div>
            </div>

            <div class="table-responsive2">
                <input type="hidden" id="put_page_id">
                <table class="table table-bordered" id="mytable">
                  <thead>
                    <tr>
                      <th>#</th>      
                      <th style="vertical-align:middle;width:20px">
                          <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                      </th>
                      <th><?php echo $this->lang->line("Subscriber ID")?></th>              
                      <th><?php echo $this->lang->line("Store")?></th>              
                      <th style="max-width: 130px"><?php echo $this->lang->line("Status")?></th>              
                      <th><?php echo $this->lang->line("Coupon")?></th>                   
                      <th><?php echo $this->lang->line("Amount")?></th>                   
                      <th><?php echo $this->lang->line("Currency")?></th>                   
                      <th><?php echo $this->lang->line("Method")?></th>                   
                      <th><?php echo $this->lang->line("Transaction ID")?></th>                   
                      <th><?php echo $this->lang->line("Invoice")?></th>                              
                      <th><?php echo $this->lang->line("Docs")?></th>                              
                      <th><?php echo $this->lang->line("Ordered at")?></th>                   
                      <th><?php echo $this->lang->line("Paid at")?></th>                  
                  	</tr>
                  </thead>
                </table>
            </div>
          </div>
        </div>
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


<script>

	var base_url="<?php echo site_url(); ?>";
	
	$('#search_date_range').daterangepicker({
	  ranges: {
	    '<?php echo $this->lang->line("Last 30 Days");?>': [moment().subtract(29, 'days'), moment()],
	    '<?php echo $this->lang->line("This Month");?>'  : [moment().startOf('month'), moment().endOf('month')],
	    '<?php echo $this->lang->line("Last Month");?>'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	  },
	  startDate: moment().subtract(29, 'days'),
	  endDate  : moment()
	}, function (start, end) {
	  $('#search_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
	});

	var perscroll;
	var table1 = '';
	table1 = $("#mytable").DataTable({
	  serverSide: true,
	  processing:true,
	  bFilter: false,
	  order: [[ 12, "desc" ]],
	  pageLength: 10,
	  ajax: {
	      url: base_url+'ecommerce/my_orders_data',
	      type: 'POST',
	      data: function ( d )
	      {
	          d.search_store_id = $('#search_store_id').val();
            d.search_subscriber_id = $('#search_subscriber_id').val();
            d.search_status = $('#search_status').val();
	          d.search_value = $('#search_value').val();
	          d.search_date_range = $('#search_date_range_val').val();
	      }
	  },
	  language: 
	  {
	    url: "<?php echo base_url('assets/modules/datatables/language/'.$this->language.'.json'); ?>"
	  },
	  dom: '<"top"f>rt<"bottom"lip><"clear">',
	  columnDefs: [
	    {
	        targets: [1,2,3,5,8,9,13],
	        visible: false
	    },
	    {
	        targets: [2,7,9,10,11,12,13],
	        className: 'text-center'
	    },
      {
          targets: [5,6],
          className: 'text-right'
      },
	    {
	        targets: [4,10,11],
	        sortable: false
	    }
	  ],
	  fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
	         if(areWeUsingScroll)
	         {
	           if (perscroll) perscroll.destroy();
	           perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	         }
	     },
	     scrollX: 'auto',
	     fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
	         if(areWeUsingScroll)
	         { 
	           if (perscroll) perscroll.destroy();
	           perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	         }
	     }
	});


	$("document").ready(function(){

      $(document).on('change', '#search_status', function(e) {
          table1.draw();
      });

	    $(document).on('change', '#search_date_range_val', function(e) {
        	e.preventDefault();
        	table1.draw();
      });

    	$(document).on('keypress', '#search_value', function(e) {
      	if(e.which == 13) $("#search_action").click();
    	});

    	$(document).on('click', '#search_action', function(event) {
      	event.preventDefault(); 
      	table1.draw();
    	});

      $(document).on('click', '#mp-download-file', function(e) {
        e.preventDefault();

        // Makes reference 
        var that = this;

        // Starts spinner
        $(that).removeClass('btn-outline-info');
        $(that).addClass('btn-info disabled btn-progress');

        // Grabs ID
        var file = $(this).data('id');

        // Requests for file
        $.ajax({
          type: 'POST',
          data: { file },
          dataType: 'JSON',
          url: '<?php echo base_url('ecommerce/manual_payment_download_file') ?>',
          success: function(res) {
            // Stops spinner
            $(that).removeClass('btn-info disabled btn-progress');
            $(that).addClass('btn-outline-info');

            // Shows error if something goes wrong
            if (res.error) {
              swal({
                icon: 'error',
                text: res.error,
                title: '<?php echo $this->lang->line('Error!'); ?>',
              });
              return;
            }

            // If everything goes well, requests for downloading the file
            if (res.status && 'ok' === res.status) {
              window.location = '<?php echo base_url('ecommerce/manual_payment_download_file'); ?>';
            }
          },
          error: function(xhr, status, error) {
            // Stops spinner
            $(that).removeClass('btn-info disabled btn-progress');
            $(that).addClass('btn-outline-info');

            // Shows internal errors
            swal({
              icon: 'error',
              text: error,
              title: '<?php echo $this->lang->line('Error!'); ?>',
            });
          }
        });
      });

      $(document).on('click', '.additional_info', function() { 
        $(this).addClass('btn-progress');       
        var cart_id = $(this).attr('data-id');
        $.ajax({
            context: this,
            type:'POST' ,
            url:"<?php echo base_url('ecommerce/addtional_info_modal_content')?>",
            data:{cart_id:cart_id},
            success:function(response)
            { 
              $('.additional_info').removeClass('btn-progress'); 
              $('#manual-payment-modal .modal-body').html(response);
              $('#manual-payment-modal').modal();
            }
        });
      });


	});

</script>




<div class="modal fade" tabindex="-1" role="dialog" id="manual-payment-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> <?php echo $this->lang->line("Manual Payment Information");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer bg-whitesmoke br"> 
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>
<?php include(APPPATH."views/ecommerce/common_style.php"); ?>