<script>
  var base_url="<?php echo site_url(); ?>";
  var subscriber_id = "<?php echo $subscriber_id;?>";
 
  $("document").ready(function()  {
    $(".selecttwo").select2({minimumResultsForSearch: -1});
    $(document).on('click','.add_to_cart',function(e){
     e.preventDefault();
     var product_id = $(this).attr("data-product-id");
     var attribute_ids = $(this).attr("data-attributes");
     var action = $(this).attr("data-action");
     var buy_now = false;
     if($(this).hasClass('buy_now')) buy_now = true;

     var attribute_info = new Object();
     var exit = false;
     if(attribute_ids!='')
     {
        $(".options").each(function() {
            if($(this).val()=="") exit=true;            
            temp = $(this).attr('data-attr');
            attribute_info[temp] = $(this).val();
        });
     }
     if(exit)
     {
      swal("<?php echo $this->lang->line('Error'); ?>", "<?php echo $this->lang->line('Please choose the options.'); ?>", 'error');
      return false;
     }
     var item_count = $("#item_count").val();
     if (typeof(item_count)==='undefined') item_count = 0;
     else item_count = parseInt(item_count);

     if(item_count==0 && action=="remove")
     {
       swal("<?php echo $this->lang->line('Error'); ?>", '<?php echo $this->lang->line("Item can not be removed. It is not in cart anymore.");?>', 'error');
       return false;
     }
     var new_count = 0;
     var param = {'product_id':product_id,'action':action,'subscriber_id':subscriber_id,'attribute_info':attribute_info};
     var mydata = JSON.stringify(param);
     $(".add_to_cart").addClass("btn-progress");
     $.ajax({
       type: 'POST',
       dataType: 'JSON',
       data: {mydata:mydata},
       url: '<?php echo base_url('ecommerce/update_cart_item'); ?>',
       success: function(response) {

        $(".add_to_cart").removeClass("btn-progress");
        var cart_count = 0;
        if(response.status=='1')
        {
          cart_count = response.cart_data.cart_count;
          cart_count = parseInt(cart_count);
        }
        if(cart_count==0) 
        {
          $("#cart_count_display").hide();
          $("#single_visit_store").addClass('d-none');
          if(attribute_ids=='')$("#single_buy_now").removeClass('d-none');
        }
        else
        {
          $("#cart_count_display").html('<i class="fas fa-shopping-cart"></i> '+cart_count).show();
          $("#cart_count_display").attr('href',response.cart_data.cart_url);
          $("#cart_count_display").show();
          $("#single_visit_store").attr('href',response.cart_data.cart_url).removeClass('d-none');
          $("#single_buy_now").addClass('d-none');
        }
        
        if(response.status=='0')
        {
          swal("<?php echo $this->lang->line('Error'); ?>", response.message, 'error');
        }
        else
        {
          if(buy_now) window.location.replace(response.cart_url);
          else 
          {
            iziToast.success({title: "",message: response.message,position: 'bottomRight',timeout: 3000});
            if(attribute_ids=='')
            {
              if(action=="add") new_count = item_count+1;              
              else new_count = item_count-1;              
            }
            else new_count = response.this_cart_item.quantity;
            $("#item_count").val(new_count);
          }
        }
       }
     });

    });
  });
</script>
