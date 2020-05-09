<?php namespace Premmerce\Premmerce\Api;

use Automatic_Upgrader_Skin;
use Plugin_Upgrader;
use Premmerce\Premmerce\Data\Plugins;
use Premmerce\SDK\V2\FileManager\FileManager;

class PluginApi{

	/**
	 * @var array
	 */
	private $plugins = [];

	/**
	 * @var FileManager
	 */
	private $fileManager;

	public function __construct(FileManager $fileManager){
		$this->fileManager = $fileManager;
		$this->init();
	}

	/**
	 * @param $slug
	 *
	 * @return array|object|\WP_Error
	 */
	public function getInfoPlugin($slug){

		if(!function_exists('plugins_api')){
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		return plugins_api('plugin_information', ['slug' => $slug]);
	}

	/**
	 * @return  array
	 */
	public function getExtra(){
		$plugins = Plugins::getAll();
		foreach($plugins as $slug => &$plugin){
			if(isset($plugin['link'])){
				$install = [
					"handler" => "premmerce.plugins",
					"action"  => "premmerce_actions",
					"value"   => $slug,
					"option"  => "install",
				];
			}else{
				$install = [
					"handler" => "wp.updates",
					'action'  => 'install-plugin',
					'slug'    => $slug,
				];
			}

			$plugin['install_action']  = $install;
			$plugin['activate_action'] = [
				"handler" => "premmerce.plugins",
				"action"  => "premmerce_actions",
				"value"   => $slug,
				"option"  => "activate",
			];
		};

		return $plugins;
	}


	/**
	 * @return array
	 */
	public function getInstalledPlugins(){
		return $this->plugins;
	}

	/**
	 * @param $slug
	 *
	 * @return mixed
	 */
	public function getInstalledPlugin($slug){
		return $this->plugins[ $slug ];
	}

	/**
	 * @param string $slug
	 *
	 * @return bool
	 */
	public function isInstalledPlugin($slug){
		return array_key_exists($slug, $this->plugins);
	}

	/**
	 * @param string $slug
	 *
	 * @return bool
	 */
	public function isActivePlugin($slug){
		if($this->isInstalledPlugin($slug)){

			$name = $this->plugins[ $slug ]['plugin'];

			return is_plugin_active($name);
		}
	}

	/**
	 * @param string $slug
	 *
	 * @return null|bool|\WP_Error
	 */
	public function activatePlugin($slug){
		if(!current_user_can('activate_plugins')){
			return new \WP_Error('permission_denied', __('Sorry, you are not allowed to activate this plugin.'));
		}
		if(!$this->isActivePlugin($slug)){
			$name   = $this->plugins[ $slug ]['plugin'];
			$result = activate_plugin($name);
			if(is_null($result)){
				return true;
			}else{
				return $result;
			}
		}
	}

	/**
	 * @param $slug
	 *
	 * @return \WP_Error|mixed
	 */
	public function deactivatePlugin($slug){
		if(!current_user_can('deactivate_plugins')){
			return new \WP_Error('permission_denied', __('Sorry, you are not allowed to deactivate plugins for this site.'));
		}
		if($this->isActivePlugin($slug)){
			$name = $this->plugins[ $slug ]['plugin'];

			return deactivate_plugins($name);
		}
	}

	/**
	 * Reload plugins
	 */
	public function reloadPlugins(){
		wp_clean_plugins_cache();
		$this->init();
	}


	/**
	 * Load plugins
	 */
	private function init(){

		if(!function_exists('get_plugins')){
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();

		foreach($plugins as $name => $plugin){
			$parts                            = explode('/', $name);
			$slug                             = array_shift($parts);
			$this->plugins[ $slug ]           = $plugin;
			$this->plugins[ $slug ]['plugin'] = $name;
		}


	}

	/**
	 * @param $slug
	 *
	 * @return bool
	 */
	public function activateTheme($slug){
		switch_theme($slug);

		return true;
	}


	/**
	 * @param $slug
	 *
	 * @return bool
	 */
	public function isActiveTheme($slug){
		return wp_get_theme()->get_template() == $slug;
	}


	/**
	 * @param $slug
	 *
	 * @return bool
	 */
	public function isInstalledTheme($slug){
		$themes = $this->getThemes();

		return array_key_exists($slug, $themes);
	}

	/**
	 * @return array
	 */
	public function getThemes(){
		return wp_get_themes();
	}


	/**
	 * @param string $slug
	 *
	 * @return bool|\WP_Error
	 */
	public function upgrade($slug){
		return $this->getPluginUpgrader()->upgrade($slug);
	}


	/**
	 * @param string $slug
	 *
	 * @return bool|\WP_Error
	 */
	public function installPlugin($slug){
		if(!current_user_can('install_plugins')){
			return new \WP_Error('permission_denied', __('Sorry, you are not allowed to install plugins on this site.'));
		}
		if(!$this->isInstalledPlugin($slug)){
			if($path = Plugins::getPath($slug)){
				$link = $this->fileManager->getPluginDirectory() . $path;
			}else{
				$result = $this->getInfoPlugin($slug);
				$link   = $result->download_link;
			}

			$result = $this->getPluginUpgrader()->install($link);

			$this->reloadPlugins();

			return $result;
		}
	}

	/**
	 * @return Plugin_Upgrader
	 */
	private function getPluginUpgrader(){
		$includes = ABSPATH . 'wp-admin/includes/';
		if(!class_exists('WP_Upgrader')){
			include_once $includes . 'class-wp-upgrader.php';
		}
		if(!class_exists('Plugin_Upgrader')){
			include_once $includes . 'class-plugin-upgrader.php';
		}

		return new Plugin_Upgrader(new Automatic_Upgrader_Skin());

	}

}