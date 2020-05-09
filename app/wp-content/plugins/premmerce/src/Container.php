<?php

namespace Premmerce\Premmerce;

use  Premmerce\Premmerce\Addons\AddonsManager ;
use  Premmerce\Premmerce\Admin\Handlers\PluginsHandler ;
use  Premmerce\Premmerce\Admin\Handlers\PremiumHandler ;
use  Premmerce\Premmerce\Admin\Handlers\ToolbarHandler ;
use  Premmerce\Premmerce\Admin\Handlers\WizardHandler ;
use  Premmerce\Premmerce\Api\PluginApi ;
use  Premmerce\Premmerce\Api\WizardApi ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\SDK\V2\Notifications\AdminNotifier ;
/**
 * Class Container
 *
 * This class responsive for instantiation on plugin classes
 *
 * @package Premmerce\Premmerce
 */
class Container
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var PluginApi
     */
    private  $pluginsApi ;
    /**
     * @var WizardApi
     */
    private  $wizardApi ;
    /**
     * @var PluginsHandler
     */
    private  $pluginsHandler ;
    /**
     * @var ToolbarHandler
     */
    private  $toolbarHandler ;
    /**
     * @var WizardHandler
     */
    private  $wizardHandler ;
    /**
     * @var PremiumHandler
     */
    private  $premiumHandler ;
    /**
     * @var AdminNotifier
     */
    private  $notifier ;
    private  $addonsManager ;
    /**
     * Container constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct( FileManager $fileManager )
    {
        $this->fileManager = $fileManager;
    }
    
    public function getAddonsManager()
    {
        if ( is_null( $this->addonsManager ) ) {
            $this->addonsManager = new AddonsManager( $this->fileManager );
        }
        return $this->addonsManager;
    }
    
    /**
     * @return PluginApi
     */
    public function getPluginsApi()
    {
        if ( is_null( $this->pluginsApi ) ) {
            $this->pluginsApi = new PluginApi( $this->getFileManager() );
        }
        return $this->pluginsApi;
    }
    
    /**
     * @return WizardApi
     */
    public function getWizardApi()
    {
        if ( is_null( $this->wizardApi ) ) {
            $this->wizardApi = new WizardApi( $this->getPluginsApi() );
        }
        return $this->wizardApi;
    }
    
    /**
     * @return PluginsHandler
     */
    public function getPluginsHandler()
    {
        if ( is_null( $this->pluginsHandler ) ) {
            $this->pluginsHandler = new PluginsHandler( $this->getPluginsApi(), $this->getFileManager() );
        }
        return $this->pluginsHandler;
    }
    
    /**
     * @return WizardHandler
     */
    public function getWizardHandler()
    {
        if ( is_null( $this->wizardHandler ) ) {
            $this->wizardHandler = new WizardHandler( $this->getWizardApi(), $this->getFileManager() );
        }
        return $this->wizardHandler;
    }
    
    /**
     * @return ToolbarHandler
     */
    public function getToolbarHandler()
    {
        if ( is_null( $this->toolbarHandler ) ) {
            $this->toolbarHandler = new ToolbarHandler( $this->getPluginsApi(), $this->getWizardApi() );
        }
        return $this->toolbarHandler;
    }
    
    /**
     * @return AdminNotifier
     */
    public function getNotifier()
    {
        if ( is_null( $this->notifier ) ) {
            $this->notifier = new AdminNotifier();
        }
        return $this->notifier;
    }
    
    /**
     * @return FileManager
     */
    public function getFileManager()
    {
        return $this->fileManager;
    }

}