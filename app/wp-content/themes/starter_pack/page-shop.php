<!--
woocommerce/myaccount/form-login.php
-->

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
    <div class="main_wrapper">
      <div id="category_list" class="category_list">
        <?php $terms = get_terms(array(
          'taxonomy' => 'product_cat',
          'hide_empty' => true,
          'pad_counts' => true,
          'orderby' => 'id',
          'parent' => 0
        )); ?>
        <?php if ($terms) : ?>
          <?php foreach ($terms as $term) : ?>
            <a href="<?= get_term_link($term->term_id); ?>" class="item_category">
              <div class="thumbnail_wrapper">
                <?php woocommerce_subcategory_thumbnail($term); ?>
              </div>
              <h2 class="title"><?= $term->name; ?></h2>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <? if (get_the_content() !== '') { ?>
        <div class="the_content mb_40">
          <? the_content(); ?>
        </div>
      <? } ?>

      <div class="before_footer_content">
        <? the_field('before_footer_content', 'option') ?>
      </div>

    </div>


  </div>
</section>


<? get_footer(); ?>
