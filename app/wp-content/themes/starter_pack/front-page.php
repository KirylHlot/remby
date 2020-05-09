<!-- 11 -->
<? $page_id = get_the_ID(); ?>
<? get_header(); ?>
  <header id="header" class="header">
    <div class="header_carousel_block">
      <div class="content_part">
        <h1 class="h1_title"><? the_field('main_h1', $page_id) ?></h1>
        <div class="content"><? the_field('mp_h1_desc', $page_id) ?></div>
        <a href="/shop" class="button">Каталог</a>
      </div>
      <div class="header_carousel_navs">
        <div class="header_carousel_left_nav navs">
          <span>Вперед</span>
          <? the_long_arrow_left_icon(); ?>
        </div>
        <div class="header_carousel_right_nav navs">
          <span>Назад</span>
          <? the_long_arrow_right_icon(); ?>
        </div>
      </div>
      <? if (have_rows('header_carousel', $page_id)): ?>
        <div id="header_carousel" class="header_carousel owl-carousel"><?
          while (have_rows('header_carousel', $page_id)) : the_row(); ?>
            <div class="carousel_image"
                 style="background-image: linear-gradient(0deg, rgba(23, 31, 33, 0.24), rgba(23, 31, 33, 0.24)), url(<?= get_sub_field('carousel_image')['url']; ?>)"></div>
          <? endwhile; ?>
        </div>
      <? endif; ?>
    </div>
    <ul class="header_socline">
      <li>
        <a href="<? the_field('fb_url', 'option'); ?>">
          <? the_fb_icon(); ?>
        </a>
      </li>
      <li>
        <a href="<? the_field('vk_url', 'option'); ?>">
          <? the_vk_icon(); ?>
        </a>
      </li>
      <li>
        <a href="<? the_field('yout_url', 'option'); ?>">
          <? the_youtube_icon(); ?>
        </a>
      </li>
      <li>
        <a href="<? the_field('insta_url', 'option'); ?>">
          <? the_instagram_icon(); ?>
        </a>
      </li>
      <li>
        <? the_socseti_text_icon(); ?>
      </li>
    </ul>
  </header>
  <div id="advantage" class="section advantage">
    <div class="main_wrapper">
      <? the_advantage($page_id); ?>
    </div>
  </div>
  <?
  $query = new WP_Query(array(
    'post_type' => 'product',  // указываем, что выводить нужно именно товары
    'posts_per_page' => 10, // количество товаров для отображения
    'orderby' => 'date', // тип сортировки (в данном случае по дате)
  //  'product_cat' => 'vstraivaemaya-texnika', // указываем слаг нужной категории
  ));
  $product_count = wp_count_posts('product');
  if ($product_count > 0) { ?>
    <div id="mp_prduct_list" class="section mp_prduct_list">
      <div class="main_wrapper">

        <div class="filter_line">
          <div id="sale" class="filter_item">Раcпродажа</div>
          <div id="new" class="filter_item currient">Новинка</div>
          <div id="featured" class="filter_item">Хит продаж</div>
          <div id="filter_overlay" class="filter_overlay d_none"></div>
        </div>

        <div id="product_carousel_wrapper" class="product_carousel_wrapper">

          <div id="product_carousel" class="product_carousel owl-carousel">
            <?
            while ($query->have_posts()) {
              $query->the_post();
              echo product_carousel_item(get_the_ID());
            };
            wp_reset_postdata();
            ?>
          </div>

          <div class="product_carousel_navs">
            <div class="product_carousel_left_nav navs">
              <? the_arrow_icon(); ?>
            </div>
            <div class="product_carousel_right_nav navs">
              <? the_arrow_icon(); ?>
            </div>
          </div>
          <div id="carousel_overlay" class="carousel_overlay d_none">
            <div class="preloader"></div>
          </div>
        </div>
      </div>
    </div>
  <? } ?>
  <section id="section_about" class="section_about"
           style="background-image: url(<?= get_field('mp_ab_bg', $page_id)['url']; ?>);">
    <div class="content_wrapper">
      <h2 class="h2_title"><? the_field('mp_ab_title', $page_id); ?></h2>
      <div class="desc"><? the_field('mp_ab_desc', $page_id); ?></div>
      <a href="/about" class="button">Читать далее</a>
    </div>

    <div class="about_socline_wrapper">
      <span>Соцсети</span>
      <ul class="about_socline">
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

  </section>
  <section id="mp_partnership" class="mp_partnership">
    <div class="main_wrapper">
      <div class="left_part">
        <h2 class="h2_title"><? the_field('mp_pr_title', $page_id); ?></h2>
        <div class="desc"><? the_field('mp_pr_desc', $page_id); ?></div>
        <?= do_shortcode('[contact-form-7 id="59" title="Сотрудничество"]'); ?>
      </div>
      <div class="right_part">
        <div class="main_image" style="background-image: url(<?= get_field('mp_pr_bottom_img', $page_id)['url']; ?>)">
          <? if (get_field('mp_pr_button_url', $page_id) !== '') { ?>
            <a href="<? the_field('mp_pr_button_url', $page_id); ?>"
               class="link"><? the_field('mp_pr_button_title', $page_id); ?></a>
          <? } else { ?>
            <div class="link"><? the_field('mp_pr_button_title', $page_id); ?></div>
          <? } ?>
        </div>
        <div class="second_image"
             style="background-image: url(<?= get_field('mp_pr_top_img', $page_id)['url']; ?>)"></div>
      </div>
    </div>
  </section>
  <section id="mp_contacts" class="mp_contacts">
      <div class="left_part">
        <h2 class="h2_title"><? the_field('ct_mp_title', $page_id); ?></h2>


        <div class="block phone_block">
          <? if (have_rows('phone_list', 35)): ?>
            <div class="title">T:</div>
            <div class="content_list">
              <? while (have_rows('phone_list', 35)) : the_row(); ?>
                <div class="content_item"><?= get_sub_field('phone'); ?></div>
              <? endwhile; ?>
            </div>
          <? endif; ?>
          <div class="messengers_list">
            <a href="https://wa.me/<? the_field('whatsapp', 35); ?>" target="_blank"><? the_whatsapp_icon(); ?></a>
            <a id="viber_desctop" href="viber://add?number=<? the_field('viber', 35); ?>"
               target="_blank"><? the_viber_icon(); ?></a>
            <a id="viber_mobi" class="d_none"
               href="viber://chat?number=+<? the_field('viber', 35); ?>"><? the_viber_icon(); ?></a>
            <a href="https://telegram.im/<? the_field('telegram', 35); ?>" target="_blank"><? the_telegram_icon(); ?></a>
          </div>
        </div>

        <? if (have_rows('address_list', 35)): ?>
          <div class="block">
            <div class="title">А:</div>
            <div class="content_list">
              <? while (have_rows('address_list', 35)) : the_row(); ?>
                <div class="content_item"><?= get_sub_field('address'); ?></div>
              <? endwhile; ?>
            </div>
          </div>
        <? endif; ?>

        <? if (have_rows('mails_list', 35)): ?>
          <div class="block">
            <div class="title">Е:</div>
            <div class="content_list">
              <? while (have_rows('mails_list', 35)) : the_row(); ?>
                <div class="content_item"><?= get_sub_field('mail'); ?></div>
              <? endwhile; ?>
            </div>
          </div>
        <? endif; ?>


        <div class="button transparent_btn after claim">Написать</div>

        <ul class="contacts_socline">
          <li>
            <a href="<? the_field('fb_url', 'option'); ?>">
              <? the_fb_icon(); ?>
            </a>
          </li>
          <li>
            <a href="<? the_field('vk_url', 'option'); ?>">
              <? the_vk_icon(); ?>
            </a>
          </li>
          <li>
            <a href="<? the_field('yout_url', 'option'); ?>">
              <? the_youtube_icon(); ?>
            </a>
          </li>
          <li>
            <a href="<? the_field('insta_url', 'option'); ?>">
              <? the_instagram_icon(); ?>
            </a>
          </li>
        </ul>

      </div>
      <div class="map"><? the_field('map', 35); ?></div>
    </section>
<? get_footer(); ?>