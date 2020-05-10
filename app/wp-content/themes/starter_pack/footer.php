<footer class="footer" id="footer">
  <div class="main_wrapper">
    <div class="footer_column">
      <a href="/" class="footer_logo">
        <? if (get_field('main_logo_footer', 'option')['url'] != '') { ?>
          <img src="<?= get_field('main_logo_footer', 'option')['url']; ?>" alt="Логотип">
        <? } else {
          the_main_logo_icon();
        } ?>
      </a>
      <a class="politic" href="<?= get_field('politic', 'option')['url']; ?>">Политика безопасности</a>
    </div>
    <div class="footer_column double">
      <a href="/shop" class="title">Каталог</a>
      <? wp_nav_menu([
        'theme_location' => 'second_menu',
        'menu_class' => 'second_menu',
        'container' => 'false'
      ]); ?>
    </div>
    <div class="footer_column">
      <div class="title">Компания</div>
      <? wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class' => 'main_menu',
        'container' => 'false'
      ]); ?>
    </div>
    <div class="footer_column">
      <a href="tel:+<?= preg_replace('~\D+~', '', get_field('cf_phone', 'option')); ?>" class="phone_wrapper">
        <? the_field('cf_phone', 'option'); ?>
      </a>
      <span class="time">24/7</span>

      <span class="subscribe">Подпишитесь на наши новости и получиье скидку 10% на заказ</span>

      <?= do_shortcode('[contact-form-7 id="139" title="Подписаться"]'); ?>
    </div>
  </div>
</footer>
<div id="claim_popup" class="claim_popup popup d_none">
  <div id="close_popup">
    <? the_close_icon(); ?>
  </div>
  <div class="main_content">
    <div class="title">Связаться с нами</div>
    <?= do_shortcode('[contact-form-7 id="140" title="Попап форма"]'); ?>
  </div>
  <div class="alert_done d_none">
    <div class="title">Спасибо</div>
    <div class="desc">Мы свяжемся с Вами в ближайшее время</div>
  </div>
</div>
<div id="product_popup" class="product_popup d_none">
  <div id="close_product_popup">
    <? the_close_icon(); ?>
  </div>
  <div class="left_part" id="product_thumbnail" style>
    <? echo wp_get_attachment_image(56, 'full', "", array("class" => "popup_product_img")); ?>
  </div>
  <div class="right_part">
    <div id="product_title" class="product_title">Ремень женский 40мм 15</div>
    <div id="product_brand" class="product_brand">Ремень женский 40мм 15</div>
    <div id="product_price" class="product_price">
      <div class="sale_price">
        <span id="sale_price_count">810</span>р. / шт.
      </div>
      <div class="regular_price">
        <span id="regular_price_count">810</span>р. / шт.
      </div>
    </div>
    <div id="product_content" class="product_content">Ремень женский 40мм Строченный-2 1М – яркий представитель
      классических аксессуаров, для которых характерна повышенная прочность и долговечность. При создании изделия
      использовалась натуральная кожа красного цвета и металлическая фурнитура. Ремень от Rembini отлично дополнит как
      повседневный, так и праздничный образ. Станьте его обладателем прямо сейчас.
    </div>

    <div class="add_to_cart_wrapper">
      <div class="product_count_check_wrapper">
        <div id="minus_product" class="minus checkers">-</div>
        <input aria-label="counter" type="number" id="count_display" class="count_display" value="1">
        <div id="plus_product" class="plus checkers">+</div>
      </div>
      <a href="?add-to-cart=55" id="product_popup_add_to_cart" data-quantity="1"
         class="button add_to_cart_button ajax_add_to_cart"
         data-product_id="55" data-product_sku="" aria-label="Добавить в корзину" rel="nofollow noopener">В
        корзину</a>
    </div>
    <?= do_shortcode('[contact-form-7 id="141" title="Продукт попап"]'); ?>
  </div>
</div>
<div class="modal_search_wrapper">
  <div class="ms_wrapper">
    <?= do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
    <div class="close_search_popup"><? the_close_icon(); ?></div>
  </div>
</div>




<div id="overlay" class="d_none"></div>
<div id="search_overlay" class="d_none"></div>


<?php wp_footer(); ?>
</body>
</html>