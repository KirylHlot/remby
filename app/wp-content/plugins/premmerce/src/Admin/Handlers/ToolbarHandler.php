<?php

namespace Premmerce\Premmerce\Admin\Handlers;

use  Premmerce\Premmerce\Api\PluginApi ;
use  Premmerce\Premmerce\Api\WizardApi ;
class ToolbarHandler
{
    const  META_KEY_USE_MENU = 'premmerce_use_menu' ;
    const  AME_PLUGIN = 'admin-menu-editor' ;
    /**
     * @var WizardApi
     */
    private  $wizard ;
    /**
     * @var PluginApi
     */
    private  $api ;
    /**
     * ToolbarHandler constructor.
     *
     * @param PluginApi $api
     * @param WizardApi $wizard
     */
    public function __construct( PluginApi $api, WizardApi $wizard )
    {
        $this->api = $api;
        $this->wizard = $wizard;
    }
    
    /**
     * Add premmerce to top admin bar
     */
    public function renderAdminBarItem()
    {
        /** @var \WP_Admin_Bar $wp_admin_bar */
        global  $wp_admin_bar ;
        $uncompleted = (int) $this->wizard->getUncompletedTipsCount();
        $uncompleted = "<div class='wp-core-ui wp-ui-notification premmerce-wizard-counter count-{$uncompleted}' ><span class='plugin-count' data-premmerce-count>{$uncompleted}<span></div>";
        $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
        $title = '<div class="premmerce-toolbar-icon ab-icon">' . $svg . '</div>' . $uncompleted;
        $wp_admin_bar->add_menu( [
            'id'    => 'premmerce_toolbar',
            'title' => $title,
            'href'  => menu_page_url( 'premmerce', false ),
            'meta'  => [
            'class' => 'premmerce_admin_bar_wizard',
        ],
        ] );
        $wp_admin_bar->add_menu( [
            'parent' => 'premmerce_toolbar',
            'id'     => 'premmerce_toolbar_wizard',
            'title'  => __( 'Incomplete', 'premmerce' ) . $uncompleted,
            'href'   => menu_page_url( 'premmerce', false ),
        ] );
        $wp_admin_bar->add_menu( [
            'parent' => 'premmerce_toolbar',
            'id'     => 'premmerce_toolbar_plugins',
            'title'  => __( 'Plugins', 'premmerce' ),
            'href'   => menu_page_url( 'premmerce', false ) . '&tab=plugins',
        ] );
        $wp_admin_bar->add_menu( [
            'parent' => 'premmerce_toolbar',
            'id'     => 'premmerce_toolbar_settings',
            'title'  => __( 'Settings', 'premmerce' ),
            'href'   => menu_page_url( 'premmerce', false ) . '&tab=settings',
        ] );
        $wp_admin_bar->add_menu( [
            'parent' => 'premmerce_toolbar',
            'id'     => 'premmerce_toolbar_addons',
            'title'  => __( 'Addons', 'premmerce' ),
            'href'   => menu_page_url( 'premmerce', false ) . '&tab=addons',
        ] );
    }

}