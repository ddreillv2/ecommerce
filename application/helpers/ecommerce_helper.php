<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('mec_display_price'))
{
  function mec_display_price($original_price=0, $sell_price=0, $currency_icon = '$',$retun_type='1',$currency_position='left',$decimal_point=0,$thousand_comma='0') //$retun_type=1 means price overthrough, $retun_type=2 means purchase price, $retun_type=3 means disount, $retun_type=4 menas discount formatted
  {
    $ci = &get_instance();

    $currency_left = $currency_right = "";
    if($currency_position=='left') $currency_left = $currency_icon;
    if($currency_position=='right') $currency_right = $currency_icon;

    if($retun_type=='1')
    {
      if($sell_price>0 && ($sell_price<$original_price)) 
      {
        $return = "<span class='text-light' style='text-decoration:line-through'>".$currency_left.mec_number_format($original_price,$decimal_point,$thousand_comma).$currency_right."</span> <span class='text-dark'>".$currency_left.mec_number_format($sell_price,$decimal_point,$thousand_comma).$currency_right."</span>";
      }
      else $return = $currency_left.mec_number_format($original_price,$decimal_point,$thousand_comma).$currency_right;
    }
    else if($retun_type=='2')
    {
      if($sell_price>0 && ($sell_price<$original_price)) 
      {
        $return = mec_number_format($sell_price,$decimal_point,$thousand_comma);
      }
      else $return = mec_number_format($original_price,$decimal_point,$thousand_comma);
    }
    else
    {
      $disocunt = 0;
      if($sell_price>0 && ($sell_price<$original_price)) 
      {
        $disocunt = round((($original_price-$sell_price)/$original_price)*100);
        
        if($retun_type==4) $return = '<div class="yith-wcbsl-badge-wrapper yith-wcbsl-mini-badge"> <div class="yith-wcbsl-badge-content">-'.$disocunt.'%</div></div>';
        else $return = $disocunt;
      }
      else
      {
        if($retun_type==4) $return = '';
        else $return = 0;
      }

    }

    return $return;
  }
}

if ( ! function_exists('mec_attribute_map'))
{
  function mec_attribute_map($attribute_array=array(),$attribute_str='',$retun_type='string') // makes comma seperated attributes as name string (1,2 = Color,Size)
  {
    $explode = explode(',', $attribute_str);

    $output = array();
    foreach ($explode as $value) 
    {
      if(isset($attribute_array[$value])) $output[] = $attribute_array[$value];
    }
    if($retun_type=='string') return ucfirst(strtolower(implode(' , ', $output)));
    else return $output;
  }
}


if ( ! function_exists('mec_number_format'))
{
  function mec_number_format($number,$decimal_point=0,$thousand_comma='0')
  {
      $decimal_point_count = strlen(substr(strrchr($number, "."), 1));
      if($decimal_point_count>0 && $decimal_point==0) $decimal_point = $decimal_point_count; // if setup no deciaml place but the number is naturally float, we can not just skip it

      if($decimal_point>2) $decimal_point=2;

      $number = (float)$number;
      $comma = $thousand_comma=='1' ? ',' : '';
      return number_format($number, $decimal_point,'.',$comma);
  }
}






