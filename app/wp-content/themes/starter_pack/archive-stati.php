<? $page_id = 33; ?>
<? get_header(); ?>
  <section id="main" class="main">
    <div class="top_part">
      <div class="main_wrapper">

        <div class="h1_wrapper">
          <h1 class="h1_title">
            Статьи
          </h1>
        </div>

        <? if (function_exists('yoast_breadcrumb')) {
          yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
        }; ?>

      </div>
    </div>

    <div class="bottom_part">
      <div class="main_wrapper">
        <div class="stati_list">
          <?
          $query = new WP_Query(array(
            'posts_per_page' => '6',
            'post_type' => array('stati'),
            'post_status' => 'publish',
          ));
          while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="list_item">
              <div class="date"><?= get_the_date('d/m/Y'); ?></div>
              <div class="inner_wrapper">
                <h3 class="h3_title"><? echo mb_substr(  strip_tags(get_the_title()), 0, 70, 'UTF-8') . '...';  ?></h3>
                <a href="<? the_permalink(); ?>">Далее</a>
              </div>
            </div>

          <? };
          wp_reset_postdata();
          ?>
        </div>
        <? echo do_shortcode( '[ajax_load_more container_type="div" scroll="false" post_type="stati" offset="6" posts_per_page="6" pause="true" button_label="Загрузить еще"]' ); ?>
      </div>
    </div>
  </section>
<? get_footer(); ?>