<?php namespace Premmerce\Toolkit\Admin;


class SettingsPage{


	public function initSettings(){

		register_setting('premmerce_settings', 'premmerce_toolkit_product_video');
		register_setting('premmerce_settings', 'premmerce_toolkit_shipping_description');
		register_setting('premmerce_settings', 'premmerce_toolkit_catalog_mode');
		register_setting('premmerce_settings', 'premmerce_toolkit_head_scripts');
		register_setting('premmerce_settings', 'premmerce_toolkit_footer_scripts');
		add_settings_section(
			'woocommerce',
			__('WooCommerce settings', 'premmerce-toolkit'),
			'',
			'premmerce'
		);
		add_settings_section(
			'user_scripts',
			__('User scripts', 'premmerce-toolkit'),
			'',
			'premmerce'
		);

		add_settings_field(
			'premmerce_toolkit_product_video',
			'',
			[$this, 'checkboxCallback'],
			'premmerce',
			'woocommerce',
			[
				'label_for'   => 'premmerce_toolkit_product_video',
				'description' => __('Activate the ability to add videos to the product.', 'premmerce-toolkit'),
				'label'       => __('Product video', 'premmerce-toolkit'),

			]
		);

		add_settings_field(
			'premmerce_toolkit_shipping_description',
			'',
			[$this, 'checkboxCallback'],
			'premmerce',
			'woocommerce',
			[
				'label_for'   => 'premmerce_toolkit_shipping_description',
				'description' => __('Enable the option to add description to shipping methods.', 'premmerce-toolkit'),
				'label'       => __('Shipping description', 'premmerce-toolkit'),

			]
		);


		add_settings_field(
			'premmerce_toolkit_shipping_description',
			'',
			[$this, 'checkboxCallback'],
			'premmerce',
			'woocommerce',
			[
				'label_for'   => 'premmerce_toolkit_shipping_description',
				'description' => __('Enable the option to add description to shipping methods.', 'premmerce-toolkit'),
				'label'       => __('Shipping description', 'premmerce-toolkit'),

			]
		);


		add_settings_field(
			'premmerce_toolkit_head_scripts',
			'',
			[$this, 'textAreaCallback'],
			'premmerce',
			'user_scripts',
			[
				'label_for' => 'premmerce_toolkit_head_scripts',
				'tag'       => '</head>',

			]
		);
		add_settings_field(
			'premmerce_toolkit_footer_scripts',
			'',
			[$this, 'textAreaCallback'],
			'premmerce',
			'user_scripts',
			[
				'label_for' => 'premmerce_toolkit_footer_scripts',
				'tag'       => '</body>',

			]
		);


	}

	public function checkBoxCallback($args){
		$checkbox = '<label><input type="checkbox" name="%s" %s >%s</label><p class="description">%s</p>';

		$checked = get_option($args['label_for']);
		printf($checkbox, esc_attr($args['label_for']), checked('on', $checked, false), $args['label'], $args['description']);
	}

	public function textAreaCallback($args){
		$textArea = '<textarea rows="10" cols="90" name="%s">%s</textarea><p class="description">%s</p>';
		printf($textArea, $args['label_for'], get_option($args['label_for']), htmlspecialchars($args['tag']));
	}

}