<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package starter_pack
 */

get_header();
?>

  <style>
    .p_404 {
      width: 100%;
      height: 70vh;
    }

    .main_wrapper {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      padding: 0 2rem;
      text-align: center;
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 2rem;
    }

    .desc {
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    .button {

    }

    @media(max-width: 560px){
      h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
      }

      .desc {
        font-size: 1.5rem;
        margin-bottom: 1rem;
      }

    }


  </style>

  <div class="p_404">
    <div class="main_wrapper">
      <h1>К сожалению, такая страница не найдена.</h1>
      <div class="desc">(Ошибка 404)</div>
      <a href="/" class="button">На главную</a>
    </div>
  </div>

<?php
get_footer();
