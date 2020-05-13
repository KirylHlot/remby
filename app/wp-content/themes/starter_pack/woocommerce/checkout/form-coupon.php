<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>


<form class="cupon_wrapper checkout_coupon woocommerce-form-coupon" method="post">
  <div class="title">У вас есть купон?</div>

      <input aria-label="coupon" type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />

      <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>

      <div class="used_coupons">
        <? foreach ( WC()->cart->get_coupons() as $code => $coupon ){ ?>
        <div class="coupon_item">
          <div class="coupon_title"><? wc_cart_totals_coupon_label( $coupon ); ?> </div>
          <div class="coupon_amount"><? wc_cart_totals_coupon_html( $coupon ); ?></div>
        </div>
        <? }; ?>
      </div>




</form>
