<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
  <a href="/my-account" class="title"><? the_title(); ?></a>
	<ul>
		<?php
    $counter = 0;

    foreach ( wc_get_account_menu_items() as $endpoint => $label ){
      if($counter == 0 or $counter == 2 ){
        $counter++;
        continue;
      } else if($counter == 5){?>
        <li class="external">
          <a href="/cart">Корзина</a>
        </li>
        <li class="external">
          <a href="/contacts">Контакты</a>
        </li>
      <?}
      ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?
      $counter++;
    }?>


	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
