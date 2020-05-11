<?php
function show_filtered_products(){
// sale
// new
// featured

  if(empty($_GET['filter_type'])){
    $filter_type = 'new';
  }else{
    $filter_type = esc_attr($_GET['filter_type']);
  }

  if ($filter_type === 'featured'){
    $args = array(
      'post_type'           => 'product',
      'posts_per_page'      => 10,
      'orderby'             => 'date',
      'post__in'            => wc_get_featured_product_ids(),
    );
    $query = new WP_Query($args);
  }else{

    $filter_type === 'sale'?$posts_per_page = -1:$posts_per_page = 10;

    $args = array(
      'post_type' => 'product',
      'posts_per_page' => $posts_per_page,
      'orderby' => 'date',
    );
    $query = new WP_Query($args);
  }

  $items_string = '';
  $counter = 0;

  while ($query->have_posts()) {
    $query->the_post();
    $product = wc_get_product(get_the_ID());
    if($filter_type === 'sale'){
      if ($product->is_on_sale() and $counter < 10 ) {
        $items_string = $items_string . product_carousel_item(get_the_ID());
        $counter++;
      }
    }else{
      $items_string = $items_string . product_carousel_item(get_the_ID());
    }
  };

  echo $items_string;
  wp_reset_postdata();
  wp_die();
}

//function get_cart_count(){
//  $cart_count = WC()->cart->get_cart_contents_count();
//  echo $cart_count;
//  wp_die();
//}