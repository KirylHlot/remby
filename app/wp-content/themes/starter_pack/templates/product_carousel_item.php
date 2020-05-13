<?
function product_carousel_item($product_id)
{
  $product = wc_get_product($product_id);

  if ( !$product->is_in_stock() ) {
    return '';
  }

//  var_dump(get_post_meta($product_id));
  $remark = '';
  $cross = '';
  $sale = '';

  if ($product->is_on_sale()) {
    $sale = '<div class="sale">' . $product->get_sale_price() . ' р.</div>';
    $remark = 'sale';
    $cross = 'cross';
  }

  $product_url = get_the_permalink($product_id);

  $featured_array = wc_get_featured_product_ids();
  if (in_array($product_id, $featured_array)) {
    $remark = 'Хит';
  }

  $galary = '';
  $galary_images = $product->get_gallery_image_ids();
  if (count($galary_images) > 0) {
    $galary = $galary . '<div class="inner_galary owl-carousel">';

    foreach ($galary_images as $image_id) {
      $galary = $galary . '<div class="inner_galary_item"
        style="background-image: url(' . wp_get_attachment_image_url($image_id, 'full', false) . ')"></div>';
    }

    $galary = $galary . '</div>';

  } else {
    $galary = '<div class="inner_galary_item">
    ' . get_the_post_thumbnail_url($product_id, 'full') . '
    </div>';
  }

  $pagetitle = get_the_title($product_id);

  $price = $product->get_regular_price();

  $cart_button = add_to_cart_button($product_id);

  return '
    <div class="item_wrapper">
    ' . $galary . '
    <div class="remark">' . $remark . '</div>
      <div class="content">
        <a href="' . $product_url . '" class="title">' . $pagetitle . '</a>
        <div class="price_wrapper ' . $cross . '">
          ' . $sale . '
          <div class="price">' . $price . ' р.</div>
        </div>
      </div>
      ' . $cart_button . '
    </div>
  ';
};