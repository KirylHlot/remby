<?php
if(!defined('WPINC')){
	die;
}
?>
<form action="options.php" method="post">
	<?php
	// Поля для безпеки для зареєстрованих налаштувань "premmerce"
	settings_fields('premmerce_settings');

	// Вивід секцій налаштувань та їх полів
	do_settings_sections('premmerce');

	// Кнопка збереження
	submit_button();
	?>
</form>
