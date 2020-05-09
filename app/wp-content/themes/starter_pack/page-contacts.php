<? $page_id = get_the_ID(); ?>
<? get_header(); ?>
<section id="main" class="main">
  <div class="top_part">
    <div class="main_wrapper">

      <div class="h1_wrapper">
        <h1 class="h1_title">
          Контакты
        </h1>
      </div>

      <? if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
      }; ?>

    </div>
  </div>

  <div class="bottom_part pb_0">
    <div class="main_wrapper">
      <div class="page_contacts_list">
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
            <a href="https://telegram.im/<? the_field('telegram', 35); ?>"
               target="_blank"><? the_telegram_icon(); ?></a>
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

        <div class="block">
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
      </div>
    </div>
    <div class="p_contacts_map">
      <? the_field('map', 35); ?>
    </div>
  </div>
</section>
<? get_footer(); ?>
