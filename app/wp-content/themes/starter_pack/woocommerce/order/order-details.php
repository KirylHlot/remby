<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

$order = wc_get_order($order_id); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited

if (!$order) {
  return;
}

$order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

if ($show_downloads) {
  wc_get_template(
    'order/order-downloads.php',
    array(
      'downloads' => $downloads,
      'show_title' => true,
    )
  );
}
?>
<section class="woocommerce-order-details">
  <?php do_action('woocommerce_order_details_before_order_table', $order); ?>
  <div class="order_table">

    <div class="table_head">
      <div class="cell prod_name">Название:</div>
      <div class="cell prod_sale_percent">Скидка:</div>
      <div class="cell prod_price">Цена:</div>
      <div class="cell prod_quantity">Количество:</div>
      <div class="cell prod_price_total">Всего:</div>
    </div>

    <div class="table_body">

      <?
      do_action('woocommerce_order_details_before_order_table_items', $order);
      $prod_sub_total = 0;
      foreach ($order_items as $item_id => $item) {
        $product = $item->get_product();
        //var_dump($product);
        $title = $product->get_title();
        $brand = $product->get_attribute('pa_brand');

        if($product->is_on_sale()){
          $sale_price = $product -> get_sale_price();
          $prod_sale_percent = woocommerce_custom_sales_price($product);
          $prod_sale_class = 'is_sale';
        }

        apply_filters( 'woocommerce_order_item_visible', true, $item );
        $prod_qty = $item->get_quantity();
        $price = $product->get_regular_price();

        $prod_price_total = $order->get_formatted_line_subtotal( $item );
        ?>

        <div class="table_row">
          <div class="cell prod_name">
            <div class="title"><?= $title; ?></div>
            <div class="brand">Брэнд: <?= $brand?$brand:'Неизвестно'; ?></div>
          </div>
          <div class="cell prod_sale_percent"><?= $prod_sale_percent?$prod_sale_percent .'%':''; ?></div>
          <div class="cell prod_price <?= $prod_sale_class?$prod_sale_class:''; ?>">
            <div class="sale"><?= $sale_price?$sale_price:''; ?> р.</div>
            <div class="price"><?= $price; ?> р.</div>
          </div>
          <div class="cell prod_quantity"><?= $prod_qty; ?> шт.</div>
          <div class="cell prod_price_total"><?= $prod_price_total; ?></div>
        </div>

      <? } ?>


    </div>
    <div class="total">
      <span>Всего</span>
      <?$prod_sub_total = $order->get_formatted_order_total()?>
      <strong><?= $prod_sub_total; ?></strong>
    </div>
  </div>


  <?php do_action('woocommerce_order_details_after_order_table', $order); ?>

</section>


<?
//добавляет платежный адрес
if ($show_customer_details) {
  wc_get_template('order/order-details-customer.php', array('order' => $order));
}
?>
