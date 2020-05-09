<? $page_id = get_the_ID(); ?>
<? get_header(); ?>
<section id="main" class="main">
  <div class="top_part">
    <div class="main_wrapper">

      <div class="h1_wrapper">
        <h1 class="h1_title">
          Условия сотрудничества
        </h1>
      </div>

      <? if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
      }; ?>

    </div>
  </div>

  <div class="bottom_part">
    <div class="main_wrapper">

      <? if (get_the_content() !== '') { ?>
        <div class="the_content mb_40">
          <? the_content(); ?>
        </div>
      <? } ?>

    </div>
  </div>
</section>
<? get_footer(); ?>
