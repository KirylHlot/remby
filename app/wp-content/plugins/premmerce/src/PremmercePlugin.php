<?php

namespace Premmerce\Premmerce;

use  Premmerce\Premmerce\Admin\Settings ;
use  Premmerce\Premmerce\Admin\Admin ;
use  Premmerce\Premmerce\Api\WizardApi ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\SDK\V2\Plugin\PluginInterface ;
/**
 * Class PremiumPlugin
 *
 * @package Premmerce\Premium
 */
class PremmercePlugin implements  PluginInterface 
{
    const  SLUG = 'premmerce' ;
    const  VERSION_OPTION = 'premmerce_version' ;
    const  IS_PREMIUM = true ;
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var string
     */
    private  $version = '1.3.14' ;
    /**
     * PluginManager constructor.
     *
     * @param $mainFile
     *
     */
    public function __construct( $mainFile )
    {
        $this->fileManager = new FileManager( $mainFile, 'premmerce' );
        $this->registerThemeDirectory();
        add_action( 'init', [ $this, 'loadTextDomain' ] );
    }
    
    /**
     * Run plugin part
     */
    public function run()
    {
        $container = new Container( $this->fileManager );
        if ( is_admin() ) {
            new Admin( $container );
        }
        ( new Settings() )->addActions();
        $container->getAddonsManager()->init();
    }
    
    public function registerThemeDirectory()
    {
        $theme = wp_get_theme( 'saleszone-premium' );
        if ( $theme->exists() ) {
            register_theme_directory( $theme->get_template_directory() . '/child-themes' );
        }
    }
    
    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain( 'premmerce', false, $name . '/languages/' );
    }
    
    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        update_option( self::VERSION_OPTION, $this->version, true );
        update_option( Settings::OPTIONS_KEY, [
            'transliterate_slugs' => 'on',
        ], true );
    }
    
    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
    }
    
    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        delete_option( self::VERSION_OPTION );
        delete_option( WizardApi::KEY_POSITIONS );
        delete_option( WizardApi::KEY_STATES );
        delete_option( WizardApi::KEY_SWITCHER_STATE );
    }

}