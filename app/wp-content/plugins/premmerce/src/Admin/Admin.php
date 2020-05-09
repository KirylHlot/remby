<?php

namespace Premmerce\Premmerce\Admin;

use  Premmerce\Premmerce\Container ;
use  Premmerce\Premmerce\PremmercePlugin ;
/**
 * Class Admin
 *
 * @package Premmerce\Premium\Admin
 */
class Admin
{
    /**
     * @var Container
     */
    private  $container ;
    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param Container $container
     */
    public function __construct( Container $container )
    {
        $this->container = $container;
        $this->addActions();
    }
    
    /**
     * Add plugin actions
     */
    public function addActions()
    {
        add_action( 'admin_menu', [ $this, 'addMenuPage' ], 1 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
        add_action( 'admin_post_premmerce_actions', function () {
            call_user_func( [ $this->container->getPluginsHandler(), 'runAction' ] );
        } );
        add_action( 'wp_ajax_premmerce_actions', function () {
            call_user_func( [ $this->container->getPluginsHandler(), 'runAction' ] );
        } );
        add_action( 'wp_ajax_premmerce_wizard_actions', function () {
            call_user_func( [ $this->container->getWizardHandler(), 'runAction' ] );
        } );
        add_action( 'wp_before_admin_bar_render', function () {
            call_user_func( [ $this->container->getToolbarHandler(), 'renderAdminBarItem' ] );
        } );
    }
    
    /**
     * Add premmerce to menu
     */
    public function addMenuPage()
    {
        $tips = $this->container->getWizardApi()->getUncompletedTipsCount();
        add_menu_page(
            __( 'Premmerce', 'premmerce' ),
            __( 'Premmerce', 'premmerce' ) . " <span class='update-plugins  count-{$tips} premmerce-wizard-counter' ><span class='plugin-count' data-premmerce-count>{$tips}</span></span>",
            'manage_options',
            'premmerce',
            [ $this, 'optionsPage' ],
            $this->getIcon()
        );
        //Remove notifications from hook
        global  $admin_page_hooks ;
        $admin_page_hooks['premmerce'] = 'premmerce';
    }
    
    /**
     * Manage plugins page
     */
    public function optionsPage()
    {
        $current = $this->getCurrentTab();
        $data = [
            'current' => $current,
            'tabs'    => $this->getTabs(),
            'url'     => menu_page_url( PremmercePlugin::SLUG, false ),
        ];
        switch ( $current ) {
            case 'wizard':
                $data['wizard'] = $this->container->getWizardApi();
                break;
            case 'plugins':
                $data['dep'] = $this->container->getPluginsApi();
                break;
            case 'addons':
                $data['addons'] = $this->container->getAddonsManager();
                break;
        }
        $this->container->getFileManager()->includeTemplate( 'admin/main.php', $data );
    }
    
    /**
     * @param string $hook
     */
    public function enqueueScripts( $hook )
    {
        $fm = $this->container->getFileManager();
        wp_enqueue_style( 'premmerce-wizard-bar', $fm->locateAsset( 'admin/css/wizard-admin-bar.css' ) );
        
        if ( $hook == 'toplevel_page_premmerce' ) {
            wp_enqueue_style( 'premmerce-tabs', $fm->locateAsset( 'admin/css/premmerce-tabs.css' ) );
            wp_register_script( 'premmerce_actions', $fm->locateAsset( 'admin/js/premmerce_actions.js' ), [ 'updates' ] );
            wp_register_style( 'premmerce-spinner', $fm->locateAsset( 'admin/css/spinner.css' ) );
            switch ( $this->getCurrentTab() ) {
                case 'wizard':
                    wp_enqueue_script( 'premmerce-wizard', $fm->locateAsset( 'admin/js/wizard.js' ), [ 'premmerce_actions', 'jquery-ui-sortable' ] );
                    wp_enqueue_style( 'premmerce-wizard', $fm->locateAsset( 'admin/css/wizard.css' ), [ 'premmerce-spinner' ] );
                    break;
                case 'plugins':
                    wp_enqueue_script( 'premmerce_plugins', $fm->locateAsset( 'admin/js/plugins.js' ), [ 'premmerce_actions' ] );
                    wp_enqueue_style( 'premmerce-plugins', $fm->locateAsset( 'admin/css/plugins.css' ), [ 'premmerce-spinner' ] );
                    break;
                case 'settings':
            }
        }
    
    }
    
    /**
     *
     * @return array
     */
    private function getTabs()
    {
        $tabs['wizard'] = __( 'Wizard', 'premmerce' );
        $tabs['plugins'] = __( 'Plugins', 'premmerce' );
        $tabs['settings'] = __( 'Settings', 'premmerce' );
        $tabs['addons'] = __( 'Addons', 'premmerce' );
        
        if ( function_exists( 'premmerce_pwk_fs' ) ) {
            if ( premmerce_pwk_fs()->is_registered() ) {
                $tabs['account'] = __( 'Account', 'premmerce' );
            }
            $tabs['contact'] = __( 'Contact Us', 'premmerce' );
            //			$tabs['pricing'] = __('Pricing', 'premmerce');
        }
        
        return $tabs;
    }
    
    /**
     * Tab from get parameter of default
     * @return string
     */
    private function getCurrentTab()
    {
        return ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'wizard' );
    }
    
    /**
     * Inline svg icon
     *
     * @return string
     */
    private function getIcon()
    {
        $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
        $svg = 'data:image/svg+xml;base64,' . base64_encode( $svg );
        return $svg;
    }

}