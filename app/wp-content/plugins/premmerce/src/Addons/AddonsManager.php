<?php namespace Premmerce\Premmerce\Addons;

use Premmerce\ProductComparison\ProductComparisonPlugin;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Toolkit\PremmerceToolkitPlugin;

class AddonsManager{

	/**
	 * @var string
	 */
	private $addonsBasePath;
	private $addonsRelativePath;
	private $addonsDirname = 'addons';

	const OPTION = 'premmerce_premium_plugins';
	const OPTION_ACTIVATION = 'premmerce_premium_plugins_activate';

	/**
	 * @var array
	 */
	private $addons = [
		ProductComparisonPlugin::class => 'premmerce-product-comparison/premmerce-product-comparison.php',
		PremmerceToolkitPlugin::class  => 'premmerce-woocommerce-toolkit/premmerce-toolkit.php',
	];


	private $textDomains = [
		ProductComparisonPlugin::class => 'premmerce-product-comparison',
		PremmerceToolkitPlugin::class  => 'premmerce-toolkit',
	];

	/**
	 * @var array
	 */
	private $instances = [];

	/**
	 * @var array
	 */
	private $addonsStatuses = [];


	/**
	 * Container constructor.
	 *
	 * @param FileManager $fileManager
	 */
	public function __construct(FileManager $fileManager){
		$this->addonsBasePath     = $fileManager->getPluginDirectory() . $this->addonsDirname;
		$this->addonsRelativePath = $fileManager->getPluginName() . '/' . $this->addonsDirname;

	}

	public function init(){

		$this->addonsStatuses = $this->getOptions();

		add_action('plugins_loaded', [$this, 'runActive']);

		add_filter('plugin_action_links', function($actions, $plugin, $data){
			$plugins = array_flip($this->addons);
			if(in_array($plugin, $this->addons) && $this->isActive($plugins[ $plugin ])){
				unset($actions['activate']);
				$actions[] = '<p>' . sprintf(__('Please deactivate %s addon before activating this plugin', 'premmerce'), $data['Title']) . '</p>';
			}

			return $actions;
		}, 10, 3);

		add_action('admin_post_premmerce_addon_action', function(){
			$class  = urldecode($_POST['slug']);
			$action = urldecode($_POST['addon_action']);

			if($this->exists($class)){
				switch($action){
					case 'activate':
						$this->activate([$class]);
						break;
					case 'deactivate':
						$this->deactivate([$class]);
						break;
				}
			}

			wp_redirect($_SERVER['HTTP_REFERER']);
			die;
		});
	}

	/**
	 * @return array
	 */
	public function getAddons(){
		return $this->addons;
	}

	/**
	 * @param string $class
	 *
	 * @return mixed
	 */
	public function get($class){
		if($this->exists($class)){
			return $this->instantiate($class);
		}
	}

	public function exists($class){
		return array_key_exists($class, $this->addons);
	}

	/**
	 * @param string $plugin
	 *
	 * @return string
	 */
	public function getPluginFullPath($plugin){
		return $this->addonsBasePath . '/' . $plugin;
	}

	/**
	 * Run active plugins
	 */
	public function runActive(){

		foreach($this->getActivePlugins() as $class){
			$plugin = $this->addons[ $class ];
			if(!is_plugin_active($plugin)){
				$this->callAddonMethod($class, 'run');
			}else{
				//deactivate addon if same plugin is active
				$this->setStatus([$class], false);
			}
		}

	}

	/**
	 * Activate plugins
	 *
	 * @param array $addons
	 */
	public function activate(array $addons){

		foreach($addons as $addon){
			if(!$this->isActive($addon)){
				$this->callAddonMethod($addon, 'activate');
			}
		}

		$this->setStatus($addons, true);
	}


	/**
	 * Deactivate plugins
	 *
	 * @param array $plugins
	 */
	public function deactivate(array $plugins){
		foreach($plugins as $plugin){
			if($this->isActive($plugin)){
				$this->callAddonMethod($plugin, 'deactivate');
			}
		}
		$this->setStatus($plugins, false);
	}

	/**
	 * @param string $plugin
	 * @param string $method
	 *
	 * @return mixed
	 */
	private function callAddonMethod($plugin, $method){
		if($plugin = $this->get($plugin)){
			if(method_exists($plugin, $method)){
				call_user_func([$plugin, $method]);
			}
		};

		return $plugin;
	}

	/**
	 * Set status to array of plugins
	 *
	 * @param array $plugins
	 * @param bool $status
	 */
	private function setStatus(array $plugins, $status){
		$options = $this->addonsStatuses;

		foreach($plugins as $plugin){
			$options[ $plugin ] = $status;
		}

		update_option(self::OPTION, $options);
	}

	/**
	 * Check that plugin is active
	 *
	 * @param $plugin
	 *
	 * @return mixed
	 */
	public function isActive($plugin){
		return $this->addonsStatuses[ $plugin ];
	}


	/**
	 * Get data from plugin annotations
	 *
	 * @param string $plugin
	 *
	 * @return array
	 */
	public function getPluginData($plugin){
		$pluginFullPath = $this->getPluginFullPath($plugin);

		$default = [
			'Name'        => 'Plugin Name',
			'PluginURI'   => 'Plugin URI',
			'Version'     => 'Version',
			'Description' => 'Description',
			'Author'      => 'Author',
			'AuthorURI'   => 'Author URI',
			'TextDomain'  => 'Text Domain',
			'DomainPath'  => 'Domain Path',
		];
		$data    = get_file_data($pluginFullPath, $default, 'plugin');


		return $data;
	}

	/**
	 * Array of active plugins
	 *
	 * @return array
	 */
	private function getActivePlugins(){
		$options = $this->getOptions();

		$options = array_filter($options);

		return array_keys($options);
	}

	/**
	 * Cleaned plugins options merged with defaults
	 *
	 * @return array
	 */
	private function getOptions(){
		$all = $this->getDefaults();

		$options = get_option(self::OPTION, []);

		foreach($options as $key => $status){
			if(!array_key_exists($key, $all)){
				unset($options[ $key ]);
			}
		}

		$options = array_merge($all, $options);

		return array_merge($all, $options);
	}

	/**
	 * Default option for handled plugins
	 * @return array
	 */
	private function getDefaults(){
		$all = array_keys($this->getAddons());

		$keys = array_fill(0, count($all), false);

		$all = array_combine($all, $keys);

		return $all;
	}


	/**
	 * @param $class
	 *
	 * @return mixed
	 */
	private function instantiate($class){
		$path = $this->addons[ $class ];

		if(!isset($this->instances[ $path ])){
			$fullPath = $this->getPluginFullPath($path);

			$this->loadAddonTextDomain($class);

			$autoloadPath = dirname($fullPath) . '/vendor/autoload.php';
			if(file_exists($autoloadPath)){
				require_once $autoloadPath;
			}

			$plugin                   = new $class($fullPath);
			$this->instances[ $path ] = $plugin;
		}

		return $this->instances[ $path ];
	}

	private function loadAddonTextDomain($class){

		if(isset($this->textDomains[ $class ])){

			$domain = $this->textDomains[ $class ];

			$path = $this->addons[ $class ];

			$lang = $this->addonsRelativePath . '/' . dirname($path) . '/languages';

			load_plugin_textdomain($domain, false, $lang);
		}
	}


}