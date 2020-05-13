<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
  exit;
}
$cart = WC()->cart;
do_action('woocommerce_before_checkout_form', $checkout);
?>






<? // If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
  echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
  return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

  <?php do_action('woocommerce_checkout_before_order_review'); ?>
  <?php do_action('woocommerce_checkout_after_order_review'); ?>

  <?php if ($checkout->get_checkout_fields()) : ?>

    <?php do_action('woocommerce_checkout_before_customer_details'); ?>


    <div class="col2-set" id="customer_details">

      <?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
        <div class="woocommerce-account-fields">
          <?php if ( ! $checkout->is_registration_required() ) : ?>

            <p class="form-row form-row-wide create-account">
              <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
              </label>
            </p>

          <?php endif; ?>

          <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

          <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

            <div class="create-account">
              <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
              <?php endforeach; ?>
              <div class="clear"></div>
            </div>

          <?php endif; ?>

          <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
        </div>
      <?php endif; ?>


      <div id="order_review" class="woocommerce-checkout-review-order">
        <?php do_action('woocommerce_checkout_order_review'); ?>
      </div>
      <?php do_action('woocommerce_checkout_billing'); ?>

      <?php //do_action( 'woocommerce_checkout_shipping' ); ?>




      <div class="woocommerce-order-details  checkout">
        <div class="order_table">
          <div class="table_body">
            <?
            $order_items = $cart->get_cart_contents();
            foreach ($order_items as $item_id => $item) {
              $product_id = $item['product_id'];

              $product_count = $item['quantity'];
              $product_title = $item['data'] -> name;

              $product_brand = 'Неизвестно';
              if(get_the_terms($product_id,'pa_brand')){
                $product_brand = get_the_terms($product_id,'pa_brand');
                $product_brand = $product_brand[0] -> name;
              }


              $product_regular_price_by_one = $item['data'] -> regular_price;
              $product_sale_price_by_one = $item['data'] -> sale_price;
              $sale_percent = '';
              $prod_sale_class = null;
              if($product_sale_price_by_one) {
                $prod_sale_class = 'is_sale';
                $sale_percent = round((($product_regular_price_by_one - $product_sale_price_by_one) / $product_regular_price_by_one) * 100);
              }

              $product_subtotal_wia_sale = $item['line_subtotal'];

              ?>

              <div class="table_row">
                <div class="cell prod_name">
                  <div class="title"><?= $product_title; ?></div>
                  <div class="brand">Брэнд: <?= $product_brand; ?></div>
                </div>
                <div class="cell prod_sale_percent"><?= $sale_percent?$sale_percent .'%':''; ?></div>
                <div class="cell prod_price <?= $prod_sale_class?$prod_sale_class:''; ?>">
                  <div class="sale"><?= $product_sale_price_by_one?$product_sale_price_by_one:''; ?> ₽</div>
                  <div class="price"><?= $product_regular_price_by_one; ?> ₽</div>
                </div>
                <div class="cell prod_quantity"><?= $product_count; ?> шт.</div>
                <div class="cell prod_price_total"><?= $product_subtotal_wia_sale; ?> ₽</div>
              </div>

            <? } ?>
          </div>
        </div>


      </div>

      <? $order_button_text = 'Оформить заказ';
      echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt btn_sbm" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' );
      ?>





    </div>

    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

  <?php endif; ?>

  <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>




</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>


