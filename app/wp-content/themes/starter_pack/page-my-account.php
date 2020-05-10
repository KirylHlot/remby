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

  <div class="bottom_part bottom_part_my_accaunt">

    <div class="main_wrapper <? if ( is_user_logged_in() ) {
      echo 'my_account_wrapper';
    } ?>">
      <? the_content(); ?>
    </div>
  </div>
</section>
<? get_footer(); ?>
