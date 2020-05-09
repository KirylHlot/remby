<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', "remby" );


/** Имя пользователя MySQL */
define( 'DB_USER', "root" );


/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', "" );


/** Имя сервера MySQL */
define( 'DB_HOST', "localhost" );


/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );


/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '*F#wE&}pw~/PdazAs9-cCa,>2Da}W4%%*NtX@7uDOWnp(Ky_`HurYRGj,i2bz]3[' );

define( 'SECURE_AUTH_KEY',  'L:>U3ru]ZsWZ}8jmIaki1?Rsqk  c?#,8WuYmyK+7i!_,URX/yYk!(Pj:$j8kxEu' );

define( 'LOGGED_IN_KEY',    'pWueR7Ind;~ufj~uiX8GUA2uoYEkLw2BGt)5QQliUQC#qe (qE1y5t45_]IVo2`#' );

define( 'NONCE_KEY',        '@&_wqg,|C7mmW}26[S-~un:]#a>(}5Q+ZbPuP3`],sVX$X1~OcQ- l2`I!tmtv`P' );

define( 'AUTH_SALT',        '7?8j7Ji$Arc0kd!Mt%t&?c!1aIl8u6trMb {?NKzHZ9(X}Pw1-AX[t(qrd<gW}YG' );

define( 'SECURE_AUTH_SALT', '67@Gha/irdcp4Oa%U;4mMvy}SgS.SgZ|fVALvt~P2Zq8!h$*Eh|W2Gye-EUOV_?(' );

define( 'LOGGED_IN_SALT',   '74&wN6~>(E?2U9[ 3ZGz&k}#~po.vm{|j>-Xycr3N`FHZNWVJ?DWs&ji#YA8E$AB' );

define( 'NONCE_SALT',       'I?v8Md?O1>[t:(s3xdv``/tiIF3Yl[} oyhmXkZwWUu:#||@fb}z@Y&9sDdMH[#=' );


/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';


/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
