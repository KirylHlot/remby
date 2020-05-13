<?
$page_id = get_the_ID();
$post_slug = $post->post_name;
//var_dump($post_slug);
?>


<? get_header(); ?>
<section id="post-<?php the_ID(); ?>" <?php post_class('main'); ?>>
  <div class="top_part">
    <div class="main_wrapper">

      <div class="h1_wrapper">
        <h1 class="h1_title">
          <? the_title(); ?>
        </h1>
      </div>

      <? if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
      }; ?>

    </div>
  </div>

  <div class="bottom_part">
    <div class="main_wrapper filter_page">
      <? the_category_products_filter_html($post_slug); ?>

      <div class="the_filter_content"></div>
    </div>
  </div>
</section>
<? get_footer(); ?>
