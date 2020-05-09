<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package starter_pack
 */

?>

<?
  $body_class_list = get_body_class();
  $is_home = in_array('home', $body_class_list);
?>


<!DOCTYPE HTML>
<html lang="ru">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
  <link rel="icon" href="<? echo get_template_directory_uri() ?>/assets/img/favicon/favicon_32.png">
  <link rel="apple-touch-icon" sizes="180x180"
        href="<? echo get_template_directory_uri() ?>/assets/img/favicon/apple-touch-icon-180.png">
  <meta name="theme-color" content="#333333">
	<?php wp_head(); ?>
</head>
<body id="body" <? body_class(); ?>>

<? top_menu($is_home); ?>
<? left_menu(); ?>