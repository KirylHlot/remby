<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package starter_pack
 */
?>

<? get_header(); ?>


<section id="main" class="site-main main">
  <div class="top_part">
    <div class="main_wrapper">
      <div class="h1_wrapper">
        <h1 class="h1_title">
          Поиск
        </h1>
      </div>
      <? if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
      }; ?>
    </div>
  </div>





  <div class="bottom_part">
    <div class="main_wrapper">

      <div class="modal_search_wrapper p_search">
        <div class="ms_wrapper">
          <?= do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
          <div class="close_search_popup"><? the_close_icon(); ?></div>
        </div>
      </div>

      <?php if (have_posts()) {
        $counter = 0;
        ?>
        <div class="resault_list">
          <? while (have_posts()) {
            the_post();
            $counter = $counter + search_item_template(get_the_ID());
          }
          the_posts_navigation();
          ?>
        </div>
        <? if ($counter == 0) {
          echo '<span class="noresult">Извините, ваш запрос не дал результатов</span>';
        } ?>
      <? } else {
        echo '<span class="noresult">Извините, ваш запрос не дал результатов</span>';
      } ?>
    </div>
  </div>


</section><!-- #main -->


<? get_footer(); ?>
