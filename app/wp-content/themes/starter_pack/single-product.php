<?
$page_id = get_the_ID();
$product = wc_get_product($page_id);
$thumbnail_url = get_the_post_thumbnail_url();
$galary_images = $product->get_gallery_image_ids();
$galary_images_url_array = array();
$is_galary = false;
if ($galary_images and count($galary_images) > 0) {
  $counter = 0;
  foreach ($galary_images as $image_id) {
    if ($counter < 3) {
      array_push($galary_images_url_array, wp_get_attachment_image_url($image_id, 'full'));
      $counter++;
    }
  }
  $is_galary = true;
} else {
  array_push($galary_images_url_array, $thumbnail_url);
}

?>
<? get_header(); ?>
<section <?php post_class('main'); ?>>
  <div class="top_part">
    <div class="main_wrapper">

      <div class="h1_wrapper">
        <h1 class="h1_title">
          <? the_title(); ?>
        </h1>
      </div>

      <? if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
      }; ?>

    </div>
  </div>

  <div class="bottom_part">
    <div class="main_wrapper">

      <div class="product_info">
        <? if ($galary_images_url_array and count($galary_images_url_array) > 0) { ?>
          <div class="page_product_carousel_wrapper">
            <div class="page_product_carousel <?= $is_galary ? 'owl-carousel' : ''; ?>">
              <? foreach ($galary_images_url_array as $image_url) { ?>
                <a href="<?= $image_url; ?>" data-fancybox="gallery">
                  <img src="<?= $image_url; ?>" alt="<? the_title(); ?>">
                </a>
              <? } ?>
            </div>
            <div id="product_carousel_dots_list" class="product_carousel_dots_wrapper">
              <?
              $counter = 0;
              foreach ($galary_images_url_array as $image_url) { ?>
                <div class="product_carousel_dot <?= $counter < 1 ? 'active' : ''; ?>"
                     style="background-image: url(<?= $image_url; ?>)">
                  <div class="car_overlay"></div>
                </div>
                <?
                $counter++;
              } ?>
            </div>
          </div>
        <? } ?>
        <div class="product_info_wrapper">
          <?
          $brand = $product->get_attribute('pa_brand');
          $regular_price = $product->regular_price;
          if ($product->is_on_sale()) {
            $sale_price = $product->get_sale_price();
          }
          $content = get_the_content();
          $sku = $product->get_sku();
          $attributes = $product->get_attributes();
          $attribute_array = array();

          foreach ($attributes as $attribute) {
            if ($attribute['name'] !== 'pa_brand') {
              array_push($attribute_array, $attribute['name']);
            }
          }
          ?>

          <div class="character_wrapper">
            <h2 class="h2_title">
              <? the_title(); ?>
            </h2>
            <? if ($brand and $brand !== '') { ?>
              <div class="brand">Бренд: <?= $brand ? $brand : 'Неизвестно' ?></div>
            <? } ?>
            <div class="price_line">
              <div class="price_wrapper <?= $sale_price ? 'is_sale' : ''; ?>">
                <span class="sale_price"><?= $sale_price; ?>р./шт.</span>
                <span class="regular_price"><?= $regular_price; ?>р./шт.</span>
              </div>

              <span class="remark">(Розничная цена)</span>
              <span class="want_opt claim">Хотите оптом?</span>
            </div>

            <? if ($content and $content !== '') { ?>
              <div class="content"><?= $content; ?></div>
            <? } ?>

            <? if (count($attribute_array) > 0) { ?>
              <div class="attribute_list">

                <? if ($sku and $sku !== '') { ?>
                  <div class="attribute_item">
                    <div class="title">+ Код товара</div>
                    <div class="value"><?= $sku; ?></div>
                  </div>
                <? } ?>

                <? foreach ($attribute_array as $atr) { ?>
                  <div class="attribute_item">
                    <div class="title">+ <?= wc_attribute_label($atr) ?></div>
                    <div class="value"><?= $product->get_attribute($atr); ?></div>
                  </div>
                <? } ?>
              </div>
            <? } ?>
          </div>
          <div class="add_to_cart_wrapper">

            <div id="checkers_product_wrapper" class="product_count_check_wrapper">
              <div class="minus checkers_product">-</div>
              <input aria-label="counter" type="number" id="count_display" class="count_display" value="1">
              <div class="plus checkers_product">+</div>
            </div>
            <div class="add_to_cart_button_wrapper">
              <a href="?add-to-cart=<?= $page_id; ?>" id="product_popup_add_to_cart" data-quantity="1"
                 class="button add_to_cart_button ajax_add_to_cart"
                 data-product_id="<?= $page_id; ?>" data-product_sku="<?= $sku ? $sku : ''; ?>"
                 aria-label="Добавить в корзину" rel="nofollow noopener">В
                корзину</a>
            </div>


          </div>
          <?= do_shortcode('[contact-form-7 id="141" title="Продукт попап"]'); ?>

        </div>

      </div>

    </div>


    <?
    $query = new WP_Query(array(
      'post_type'           => 'product',
      'posts_per_page'      => -1,
      'orderby'             => 'ASC',
      'order'               => $order == 'asc' ? 'asc' : 'desc',
      'post__in'            => wc_get_featured_product_ids(),
    ));
    $product_count = wp_count_posts('product');
    if ($product_count > 0) { ?>
      <div id="mp_prduct_list" class="section mp_prduct_list">
        <div class="main_wrapper featured_products">

          <div class="title_line">
            <h2 class="h2_title">Вам может понравиться</h2>
          </div>

          <div id="product_carousel_wrapper" class="product_carousel_wrapper">

            <div id="product_carousel" class="product_carousel owl-carousel">
              <?
              while ($query->have_posts()) {
                $query->the_post();
                echo product_carousel_item(get_the_ID());
              };
              wp_reset_postdata();
              ?>
            </div>

            <div class="product_carousel_navs">
              <div class="product_carousel_left_nav navs">
                <? the_arrow_icon(); ?>
              </div>
              <div class="product_carousel_right_nav navs">
                <? the_arrow_icon(); ?>
              </div>
            </div>
            <div id="carousel_overlay" class="carousel_overlay d_none">
              <div class="preloader"></div>
            </div>
          </div>

          <div class="before_footer_content">
            <? the_field('before_footer_content', 'option') ?>
          </div>

        </div>
      </div>
    <? } ?>

  </div>
</section>


<? get_footer(); ?>
