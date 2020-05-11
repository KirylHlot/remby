<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

  <form class="products_list woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>
    <?php do_action('woocommerce_before_cart_contents'); ?>

    <!-- //Лист с продуктами-->
    <? foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
      $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
      if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        $thumbnail_url = get_the_post_thumbnail_url($product_id);
        $product_title = get_the_title($product_id);
        $product_brand = $_product->get_attribute('pa_brand');
        $product_price = $_product->get_regular_price();
        $product_url = get_the_permalink($product_id);
        $sale_price = '';
        $sale_class = '';
        if ($_product->is_on_sale()) {
          $sale_price = $_product->get_sale_price();
          $sale_class = 'is_sale';
        }
        ?>

        <div class="product_item">
          <!--  //remove_product button-->
          <?= apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'woocommerce_cart_item_remove_link',
            sprintf(
              '<a href="%s" class="remove_product" aria-label="%s" data-product_id="%s" data-product_sku="%s"> <svg xmlns="http://www.w3.org/2000/svg"
       viewBox="0 0 80090 80092"
       class="the_close_icon">
        <path class="stripe1" d="M72884 78855l-32839 -32839 -32837 32839c-1647,1647 -4321,1647 -5970,-3l0 3c-1648,-1651 -1648,-4324 0,-5974l0 3 32838 -32837 -32838 -32838c-1650,-1648 -1650,-4322 -1,-5971l1 0c1647,-1648 4321,-1648 5970,0l32839 32838 32837 -32838c1647,-1650 4321,-1650 5970,-1l1 1c1647,1647 1647,4321 -1,5970l-32838 32837 32839 32839c1647,1648 1647,4323 -1,5971l1 0c-1651,1648 -4324,1648 -5972,0l1 0z"></path></svg>
  </a>',
              esc_url(wc_get_cart_remove_url($cart_item_key)),
              esc_html__('Remove this item', 'woocommerce'),
              esc_attr($product_id),
              esc_attr($_product->get_sku())
            ),
            $cart_item_key
          ); ?>

          <? if ($thumbnail_url) { ?>
            <div class="thumb_wrapper">
              <img src="<?= $thumbnail_url; ?>" alt="Изображение продукта">
            </div>
          <? } ?>

          <div class="content_wrapper">
            <a href="<?= $product_url; ?>" class="title"><?= $product_title; ?></a>
            <div class="brand">Бренд: <?= $product_brand ? $product_brand : 'Неизвестно'; ?></div>
            <div class="prices <?= $sale_class ? $sale_class : ''; ?>">
              <div class="sale"><?= $sale_price; ?>р. / шт.</div>
              <div class="price"><?= $product_price; ?>р. / шт.</div>
            </div>
            <div class="bottom_content">
              <div class="product_count_check_wrapper">
                <div class="minus checkers">-</div>
                <? if ($_product->is_sold_individually()) {
                  $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                } else {
                  $product_quantity = woocommerce_quantity_input(
                    array(
                      'input_name' => "cart[{$cart_item_key}][qty]",
                      'input_value' => $cart_item['quantity'],
                      'max_value' => $_product->get_max_purchase_quantity(),
                      'min_value' => '0',
                      'classes' => 'count_display cart_product_count',
                      'product_name' => $_product->get_name(),
                      'placeholder' => ''
                    ),
                    $_product, true);
                }
                //              echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                ?>
                <div class="plus checkers">+</div>
              </div>
              <button type="submit" class="button" name="update_cart"
                      value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                Обновить корзину
              </button>
            </div>
          </div>

        </div>
        <?}
        }; ?>

    <?php do_action('woocommerce_cart_contents'); ?>

    <!--    //Купон отключен-->
    <?php if (false) { ?>
    <?php //if (wc_coupons_enabled()) { ?>
      <div class="coupon">
        <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input type="text"
                                                                                               name="coupon_code"
                                                                                               class="input-text"
                                                                                               id="coupon_code" value=""
                                                                                               placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
        <button type="submit" class="button" name="apply_coupon"
                value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
        <?php do_action('woocommerce_cart_coupon'); ?>
      </div>
    <?php } ?>

    <div class="process_part">
      <div class="subtotal">
        <span>Всего:</span> <strong><?= WC()->cart->get_subtotal();?>р.</strong>
      </div>

      <?php
//     Добавляет кнопку оформиьт заказ
      do_action( 'woocommerce_proceed_to_checkout' ); ?>

      <?php do_action( 'woocommerce_after_cart_totals' ); ?>

      <?php
      /**
       * Cart collaterals hook.
       *
       * @hooked woocommerce_cross_sell_display
       * @hooked woocommerce_cart_totals - 10
       */
//      do_action('woocommerce_cart_collaterals');

//      Выводит опции доставки и данные
//      wc_cart_totals_shipping_html();
      ?>

    </div>



    <?php do_action('woocommerce_cart_actions'); ?>
    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    <?php do_action('woocommerce_after_cart_contents'); ?>
    <?php do_action('woocommerce_before_cart_collaterals'); ?>
    <?php do_action('woocommerce_after_cart'); ?>
    <?php do_action('woocommerce_after_cart_table'); ?>
  </form>
