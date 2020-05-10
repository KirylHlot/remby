<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders);

$counter = 0;

if ($has_orders) {
  do_action('woocommerce_before_account_orders_pagination'); ?>


  <div class="order_items_list">

    <? foreach ($customer_orders->orders as $customer_order) {
      $order = wc_get_order($customer_order);
      $order_product_count = $order->get_item_count() - $order->get_item_count_refunded();
      $order_status = esc_html(wc_get_order_status_name($order->get_status()));
      $order_number = esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number());
      $order_url = esc_url($order->get_view_order_url());
      $order_date = esc_attr($order->get_date_created()->date('d.m.Y'));
      $order_date2 = esc_html(wc_format_datetime($order->get_date_created()));
      $order_total_cash = $order->get_formatted_order_total();

      $actions = wc_get_account_orders_actions($order);
      $order_action_url = $actions['cancelled']['url'];
      $order_action_action = $actions['cancelled']['action'];

      $order_end_string = '';
      if($order_product_count > 1 and $order_product_count < 5){
        $order_end_string = 'a';
      }else if($order_product_count > 5){
        $order_end_string = 'ов';
      }
      ?>



      <div class="order_item">
        <div class="count cell">
          <?= $counter + 1 . '.'; ?>
        </div>
        <div class="order_info cell">
          <div class="info_wrapper">
            <span class="color_accent">Заказ <?= $order_number; ?></span> от <?= $order_date; ?>г., <?= $order_product_count ?> товар<?= $order_end_string; ?> на сумму <?= $order_total_cash; ?>
          </div>
          <a href="<?= $order_url; ?>" class="show_detail">Смотреть детали</a>
        </div>

        <div class="order_navigate cell">
          <div class="status"><?= $order_status; ?> </div>
          <a href="<?= $order_action_url; ?>" class="woocommerce-button cancelled">Отменить</a>
        </div>

      </div>


    <?
      $counter++;
    } ?>
  </div>


<? } else { ?>
  <h2 class="empty_alert">
    <?php esc_html_e('No order has been made yet.', 'woocommerce'); ?>
  </h2>
<? } ?>



<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
