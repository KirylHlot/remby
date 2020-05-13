<?php

function the_advantage($page_id)
{
  if (have_rows('the_advantage', $page_id)):?>
    <div class="the_advantage">
      <? while (have_rows('the_advantage', $page_id)) : the_row();
        $icon_url = get_sub_field('icon')['url'];
        $title = get_sub_field('title');
        $desc = get_sub_field('desc');
        ?>
        <div class="list_item">
          <div class="icon"><img src="<?= $icon_url; ?>" alt="<?= $title; ?>"></div>
          <div class="title"><?= $title; ?></div>
          <div class="desc"><?= $desc; ?></div>
        </div>
      <? endwhile;
      ?>
    </div>
  <?
  endif;
}

function add_to_cart_button($page_id)
{
  $title = get_the_title($page_id);
  return '
   <a href="?add-to-cart=' . $page_id . '" data-quantity="1" class="button add_to_cart_button ajax_add_to_cart" data-product_id="' . $page_id . '" data-product_sku="" aria-label="Добавить &quot;' . $title . '&quot; в корзину" rel="nofollow noopener">В корзину</a>
  ';
}

function search_item_template($product_id)
{
  $product = wc_get_product($product_id);

//  var_dump($product);

  if (!$product) {
    return 0;
  }

  $sale_class = '';
  $sale_price = '';

  if ($product->is_on_sale()) {
    $sale_price = $product->get_sale_price();
    $sale_class = 'product_on_sale';
  }

  $is_in_stock = true;
  if (!$product->is_in_stock()) {
    $is_in_stock = false;
  }


  $price = $product->get_regular_price();

  $pagetitle = get_the_title($product_id);

  $brand = $product->get_attribute('pa_brand');
  $brand === '' ? $brand = 'Неизвестно' : false;

  ?>
  <div class="search_product_item">
    <div class="content_wrapper">
      <div class="title"><?= $pagetitle; ?></div>
      <div class="brand">Бренд: <?= $brand; ?></div>
    </div>

    <div class="product_price <?= $sale_class; ?>">
      <div class="sale_price">
        <span id="sale_price_count"><?= $sale_price; ?></span>р. / шт.
      </div>
      <div class="regular_price">
        <span id="regular_price_count"><?= $price; ?></span>р. / шт.
      </div>
    </div>

    <div class="part_wrapper">
      <div class="product_count_check_wrapper <?= $is_in_stock ? '' : 'disabled'; ?>">
        <div class="minus checkers">-</div>
        <input aria-label="counter" type="number" class="count_display" value="1">
        <div class="plus checkers">+</div>
        <div class="checkers_overlay"></div>
      </div>

      <?
      if ($is_in_stock) {
        echo add_to_cart_button($product_id);
      } else {
        echo '<div class="button disabled">В корзину</div>';
      } ?>
    </div>


  </div>
  <?
  return 1;
}

//процент скидки
function woocommerce_custom_sales_price($product)
{
  if ($product->sale_price) {
    $percentage = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
    return $percentage;
  }
  return '';
}

// Сумма скидки на странице оформления заказа
function get_total_sale_price()
{
  global $woocommerce;
  $discount_total = 0;

  foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
    $_product = $values['data'];
    if ($_product->is_on_sale()) {
      $regular_price = $_product->get_regular_price();
      $sale_price = $_product->get_sale_price();
      $discount = ($regular_price - $sale_price) * $values['quantity'];
      $discount_total += $discount;
    }
  }

  return $discount_total;
}

//фильтр
function the_category_products_filter_html($category_slug)
{
  ?>
  <div class="the_filter">
    <div class="title">Фильтр</div>
    <ul class="filter_menu">
      <? $terms = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'pad_counts' => true,
        'orderby' => 'name',
        'parent' => 0
      )); ?>
      <? if ($terms) { ?>
        <? foreach ($terms as $term) {
          $link_slug = $term->slug; ?>

          <? if ($link_slug === $category_slug) { ?>
            <li class="menu-item currient-menu-item">
              <span><?= $term->name; ?></span>
            </li>
            <li class="filter_wrap">
              <div class="filter_type">- Цена</div>
              <div class="text_input_wrap">
                <input aria-label="input" placeholder="100" type="text" class="val_input min_val">
                <input aria-label="input" placeholder="1215" type="text" class="val_input max_val">
              </div>
              <input aria-label="input" type="range" min="1" max="100" value="50" class="slider" id="myRange">
              <div class="visual_range_wrapper">
                <div class="visual visual_min">100</div>
                <div class="visual visual_max">1215</div>
              </div>
            </li>
          <? } else { ?>
            <li class="menu-item">
              <a href="<?= get_term_link($term->term_id); ?>"><?= $term->name; ?></a>
            </li>

          <?
          }
        };
      } ?>
    </ul>
  </div>
  <?
}