<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
$cart = WC()->cart;
?>




<div class="shop_table woocommerce-checkout-review-order-table">
<div class="shop_table_top_wrapper">

    <div class="ch_column shipping_column">
      <div class="title_wrapper">
        <? the_deliver_small_icon(); ?>
        <h2 class="h2_title">Доставка</h2>
      </div>
      <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

        <?php wc_cart_totals_shipping_html(); ?>

        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

      <?php endif; ?>
      <a class="dostavka" href="/dostavka-i-oplata" target="_blank">Подробней о доставке</a>

    </div>

    <div class="ch_column pay_column">
      <div class="title_wrapper">
        <? the_wallet_small_icon(); ?>
        <h2 class="h2_title">Оплата</h2>
      </div>
      <? do_action('woocommerce_checkout_payment');?>
    </div>

    <div id="your_order" class="ch_column your_order">
      <h2 class="h2_title">
        Ваш заказ
      </h2>

      <div class="order_list_info">

        <div class="list_item">
          <div class="title">Товары:</div>
          <?
            $cart_total = intval($cart->get_cart_contents_total());
            $total_sale = intval(get_total_sale_price());
            $full_price_html = $cart_total+$total_sale;
            $full_price_html = $full_price_html . '<span class="woocommerce-Price-currencySymbol">₽</span>';
          ?>
            <div class="desc <?= $total_sale > 0?'is_sale':''; ?>">
            <div id="itog_price" class="itog_price"><?php wc_cart_totals_subtotal_html(); ?></div>
            <div class="price_without_sale">
              <?= $full_price_html; ?>
            </div>
          </div>
        </div>

        <div class="list_item">
          <div class="title">Доставка:</div>
          <div id="ship_price" class="desc"></div>
        </div>

        <div class="list_item sale">
          <div class="title">Скидка:</div>
          <div class="desc sale">
            <?= get_total_sale_price();?>
            <?= get_total_sale_price() > 0?'<span class="woocommerce-Price-currencySymbol">₽</span>':''; ?>

          </div>
        </div>

      </div>

      <div class="total">
        <div class="title">Всего:</div>
        <div id="total_amount" class="total_amount">
          <?php
          do_action( 'woocommerce_review_order_before_order_total' );
          wc_cart_totals_order_total_html();
          do_action( 'woocommerce_review_order_after_order_total' );
          ?>
        </div>
      </div>

      <? $order_button_text = 'Оформить заказ';
        echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' );
      ?>

    </div>

  </div>
<? do_action('woocommerce_checkout_coupon_form'); ?>


  <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
      <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
        <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
          <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <th><?php echo esc_html( $tax->label ); ?></th>
            <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr class="tax-total">
          <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
          <td><?php wc_cart_totals_taxes_total_html(); ?></td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>
  <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
  <tr class="fee">
    <th><?php echo esc_html( $fee->name ); ?></th>
    <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
  </tr>
<?php endforeach; ?>

</div>
