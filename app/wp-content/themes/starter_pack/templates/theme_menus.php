<?php
function top_menu($is_home)
{

  ?>
  <nav id="top_menu" class="top_menu <?= $is_home ? 'transparent_menu' : ''; ?>">

    <a href="/" class="main_logo_wrapper">
      <?
      if (get_field('main_logo', 'option')) {
        $main_logo = get_field('main_logo', 'option');
        ?>
        <img src="<?= $main_logo['url']; ?>" alt="<?= $main_logo['alt']; ?>" class="the_text_logo_icon">
        <?
      } else {
        the_text_logo_icon();
      }
      ?>
    </a>

    <div id="show_catalog" class="show_catalog">
      <? the_burger_icon(); ?>
      <span>Каталог</span>
    </div>

    <? wp_nav_menu([
      'theme_location' => 'main_menu',
      'menu_class' => 'top_menu_wrapper',
      'container' => 'false'
    ]); ?>

    <ul class="top_menu_icons_list">
      <li id="show_search_popup">
        <? the_search_icon(); ?>
      </li>
      <li>
        <a href="/my-account"><? the_user_icon(); ?></a>
      </li>
      <li>
        <a href="/cart">
          <? the_basket_icon(); ?>
        </a>
        <? $cart_count = WC()->cart->get_cart_contents_count(); ?>
        <div class="cart_count">
          <?= $cart_count; ?>
        </div>


      </li>
    </ul>

  </nav>
  <?
}

function left_menu()
{
  ?>
  <nav id="left_menu" class="left_menu">

    <div class="head">
      <div id="close_menu" class="close_menu">
        <? the_close_icon(); ?>
      </div>
      <a href="/shop" class="title">Каталог</a>
      <a href="/" class="main_logo_wrapper">
        <?
        if (get_field('main_logo', 'option')) {
          $main_logo = get_field('main_logo', 'option');
          ?>
          <img src="<?= $main_logo['url']; ?>" alt="<?= $main_logo['alt']; ?>" class="the_text_logo_icon">
          <?
        } else {
          the_text_logo_icon();
        }
        ?>
      </a>
    </div>

    <div id="left_menu_content" class="menu_content">
        <ul class="second_menu">
          <? $terms = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'pad_counts' => true,
            'orderby' => 'name',
            'parent' => 0
          )); ?>
          <? if ($terms) { ?>
            <? foreach ($terms as $term) { ?>
              <li class="menu-item">
                <a href="<?= get_term_link($term->term_id); ?>"><?= $term->name; ?></a>
              </li>
            <? }; ?>
          <? } ?>
        </ul>


      <a href="tel:+<?= preg_replace('~\D+~', '', get_field('cf_phone', 'option')); ?>" class="phone_wrapper">
        <? the_field('cf_phone', 'option'); ?>
      </a>

      <? wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class' => 'main_menu',
        'container' => 'false'
      ]); ?>

      <ul id="left_menu_socline" class="socline">
        <li>
          <a href="<? the_field('insta_url', 'option'); ?>">
            <? the_instagram_icon(); ?>
          </a>
        </li>
        <li>
          <a href="<? the_field('yout_url', 'option'); ?>">
            <? the_youtube_icon(); ?>
          </a>
        </li>
        <li>
          <a href="<? the_field('vk_url', 'option'); ?>">
            <? the_vk_icon(); ?>
          </a>
        </li>
        <li>
          <a href="<? the_field('fb_url', 'option'); ?>">
            <? the_fb_icon(); ?>
          </a>
        </li>
      </ul>
    </div>


  </nav>
<? }