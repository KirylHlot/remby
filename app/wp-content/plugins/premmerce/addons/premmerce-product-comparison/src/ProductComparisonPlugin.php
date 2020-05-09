<?php namespace Premmerce\ProductComparison;

use Premmerce\Premmerce\Addons\AddonInterface;
use Premmerce\ProductComparison\Frontend\Frontend;
use Premmerce\ProductComparison\Models\ProductComparisonModel;
use Premmerce\ProductComparison\Widget\ComparisonWidget;

use Premmerce\ProductComparison\RestApi\ComparisonRestApi;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;

/**
 * Class ProductComparisonPlugin
 * @package Premmerce\ProductComparison
 */
class ProductComparisonPlugin implements AddonInterface
{

    const DOMAIN = 'premmerce-product-comparison';
    const OPTION_PAGE = 'premmerce-product-comparison-page-id';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * @var ProductComparisonModel
     */
    private $model;

    /**
     * @var ProductComparisonStorage
     */
    private $storage;

    /**
     * PluginManager constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->fileManager = new FileManager($file);
        $this->notifier    = new AdminNotifier();
        $this->model       = new ProductComparisonModel();
        $this->storage     = new ProductComparisonStorage($this->model);

        add_action('widgets_init', [$this, 'registerWidgets']);

        add_action('init', [$this, 'loadTextDomain']);
        add_action('admin_init', [$this, 'checkRequirePlugins']);

        $this->loadFunctions();
    }

    private function loadFunctions()
    {
        $file = $this->fileManager->getPluginDirectory() . 'functions.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }

    /**
     * Register comparison widget
     */
    public function registerWidgets()
    {
        $widget = new ComparisonWidget($this->fileManager, $this->model, $this->storage);

        register_widget($widget);
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        if ($valid) {
            new ComparisonRestApi($this->fileManager, $this->model, $this->storage);
            $GLOBALS['premmerce_comparison_frontend'] = new Frontend($this->fileManager, $this->model, $this->storage);

        }
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();

        load_plugin_textdomain(self::DOMAIN, false, $name . '/languages/');
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        $this->model->createTables();

        $new = false;
        if ($pageId = get_option(self::OPTION_PAGE)) {
            if ( ! get_post($pageId)) {
                $new = true;
            }
        } else {
            $new = true;
        }

        if ($new) {
            $name = get_page_by_title('Comparisons') ? 'Premmerce Comparison' : 'Comparison';

            $post_data = [
                'post_title'   => $name,
                'post_content' => '[comparisons_page]',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'page',
            ];

            $newId = wp_insert_post($post_data);
            update_option(self::OPTION_PAGE, $newId);
        }
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
        if ($pageId = get_option(self::OPTION_PAGE)) {
            wp_delete_post($pageId, true);
            delete_option($pageId);
        }
    }

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        $model = new ProductComparisonModel();
        $model->deleteTables();

        if ($pageId = get_option(self::OPTION_PAGE)) {
            wp_delete_post($pageId, true);
            delete_option($pageId);
        }
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', self::DOMAIN);

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'Premmerce WooCommerce Product Comparison', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = [];

        /**
         * Check if WooCommerce is active
         **/
        if ( ! in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }
}
