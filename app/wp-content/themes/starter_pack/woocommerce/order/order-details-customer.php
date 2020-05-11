<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">

  <?php if ($show_shipping) : ?>

  <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
    <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

      <? endif; ?>
      <div class="address_wrapper">

        <div class="title_line">
          <h3 class="h3_title">
            <?php esc_html_e('Billing address', 'woocommerce'); ?>
          </h3>
        </div>
        <div class="address">
          <?
          $address = $order->get_formatted_billing_address(esc_html__('N/A', 'woocommerce'));
          $address_array = explode('<br/>', $address);
          if ($address_array) {
            foreach ($address_array as $adr) {
              ?>
              <div><?= $adr; ?></div>
            <?
            }
          } ?>

          <?php if ($order->get_billing_phone()) : ?>
            <div class="get_billing_phone"><?php echo esc_html($order->get_billing_phone()); ?></div>
          <?php endif; ?>

          <?php if ($order->get_billing_email()) : ?>
            <div class="get_billing_email"><?php echo esc_html($order->get_billing_email()); ?></div>
          <?php endif; ?>

        </div>

      </div>

      <?php if ($show_shipping) : ?>

    </div><!-- /.col-1 -->

    <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
      <h2 class="woocommerce-column__title"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h2>
      <address>
        <?php echo wp_kses_post($order->get_formatted_shipping_address(esc_html__('N/A', 'woocommerce'))); ?>
      </address>
    </div><!-- /.col-2 -->

  </section><!-- /.col2-set -->

<?php endif; ?>

  <?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>
