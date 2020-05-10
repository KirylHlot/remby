<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
} ?>


<h2 class="lf_title">Регистрация</h2>
<div class="lf_content">Зарегистрируйтесь, чтобы использовать все функции своей личной учетной записи: отслеживание
  заказа, настройка подписки, общение с социальными сетями и другие. Мы ни при каких обстоятельствах не разглашаем
  личные данные клиентов. Контактная информация будет использоваться только для размещения заказов и более удобной
  работы с сайтом.
</div>

<? do_action('woocommerce_before_customer_login_form'); ?>

<div class="forms_wrapper">
  <div class="register_wrapper">
    <div class="title">Регистрация</div>
    <form method="post"
          class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >

      <?php do_action('woocommerce_register_form_start'); ?>

      <? if ('no' === get_option('woocommerce_registration_generate_username')) { ?>
        <div class="field_wrapper woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_username">Имя<span class="required">*</span></label>
          <input type="text" name="username"
                 id="reg_username" autocomplete="username"
                 class="woocommerce-Input woocommerce-Input--text input-text"
                 required
                 value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/>
        </div>
      <? } ?>

      <div class="field_wrapper woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_email">E-mail&nbsp;<span class="required">*</span></label>
        <input type="email" name="email" id="reg_email"
               autocomplete="email"
               class="woocommerce-Input woocommerce-Input--text input-text"
               required
               value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"/>
      </div>
      <div class="notice">Мы никогда не передадим вашу электронную почту кому-либо еще</div>

      <? if ('no' === get_option('woocommerce_registration_generate_password')) { ?>
        <div class="field_wrapper woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_password">Пароль&nbsp;<span class="required">*</span></label>
          <input type="password" name="password" id="reg_password"
                 class="woocommerce-Input woocommerce-Input--text input-text"
                 required
                 autocomplete="new-password"/>
        </div>

      <? } ?>

      <div id="aproof" class="aproof">
        <div class="done"></div>
        Я согласен на обработку <span class="color_accent">персональных данных</span>
      </div>


      <?php do_action('woocommerce_register_form'); ?>

      <div class="button_wrapper woocommerce-form-row form-row">
        <div id="button_overlay" class="button_overlay"></div>
        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
        <button type="submit"
                class="button transparent_btn after woocommerce-Button woocommerce-button button woocommerce-form-register__submit"
                name="register"
                value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
      </div>

      <!--Пустой хук-->
      <?php do_action('woocommerce_register_form_end'); ?>
    </form>
    <!--Пустой хук-->
    <?php do_action('woocommerce_after_customer_login_form'); ?>
  </div>
  <div class="autorize_wrapper">
    <div class="title">Вход</div>

    <? do_action('woocommerce_before_customer_login_form'); ?>

    <form class="woocommerce-form woocommerce-form-login login" method="post">

      <?php do_action('woocommerce_login_form_start'); ?>

      <div class="field_wrapper woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="username">Логин или e-mail</label>
        <input aria-label="Логин или e-mail" type="text" class="woocommerce-Input woocommerce-Input--text input-text"
               name="username" id="username"
               autocomplete="username"
               required
               value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"/>
      </div>


      <div class="field_wrapper woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <div class="title_wrapper">
          <label for="password">Пароль</label>
          <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">Забыли пароль</a>
        </div>
        <input aria-label="Пароль" class="woocommerce-Input woocommerce-Input--text input-text" type="password"
               required
               name="password" id="password" autocomplete="current-password"/>
      </div>

      <?php do_action('woocommerce_login_form'); ?>

      <div class="checkbox_wrapper">
        <input aria-label="Запомнить меня" class="woocommerce-form__input woocommerce-form__input-checkbox"
               name="rememberme" type="checkbox"
               id="rememberme" value="forever"/>
        <span>Запомнить меня</span>
      </div>

      <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
      <button type="submit" class="woocommerce-button button transparent_btn after woocommerce-form-login__submit"
              name="login"
              value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">Вход
      </button>

      <?php do_action('woocommerce_login_form_end'); ?>

    </form>

  </div>
</div>
</div>


