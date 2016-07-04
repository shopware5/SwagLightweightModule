<?php

require_once __DIR__ . '/Components/CSRFWhitelistAware.php';

/*
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Shopware_Plugins_Backend_SwagLightweightModule_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns an array with the capabilities of the plugin.
     *
     * @return array
     */
    public function getCapabilities()
    {
        return array(
            'install' => true,
            'enable' => true,
            'update' => true
        );
    }

    /**
     * Returns the current version of the plugin.
     *
     * @return string
     */
    public function getVersion()
    {
        return "1.0.0";
    }

    /**
     * After init event of the bootstrap class.
     *
     * The afterInit function registers the custom plugin models.
     */
    public function afterInit()
    {
        $this->registerCustomModels();
    }

    /**
     * Install function of the plugin bootstrap.
     *
     * Registers all necessary components and dependencies.
     *
     * @return bool
     */
    public function install()
    {
        try {
            $this->registerController();
            $this->createMenu();

            return array(
                'success' => true,
                'invalidateCache' => array('backend')
            );
        } catch (Exception $e) {
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Registers the plugin controller event for the backend controller SwagFavorites
     */
    public function registerController()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_ExampleModulePlainHtml',
            'onGetBackendController'
        );
    }

    /**
     * Creates the Favorites backend menu item.
     *
     * The Favorites menu item opens the listing for the SwagFavorites plugin.
     */
    public function createMenu()
    {
        $this->createMenuItem(
            array(
                'label' => 'Lightweight Backend Module',
                'onclick' => 'Shopware.ModuleManager.createSimplifiedModule("ExampleModulePlainHtml", { "title": "Lightweight Backend Module" })',
                'class' => 'sprite-application-icon-large',
                'active' => 1,
                'parent' => $this->Menu()->findOneBy(['label' => 'Einstellungen'])
            )
        );
    }

    /**
     * Uninstall function of the plugin.
     * Fired from the plugin manager.
     *
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * Returns the path to the controller.
     *
     * Event listener function of the Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagFavorites
     * event.
     * Fired if an request will be root to the own Favorites backend controller.
     *
     * @return string
     */
    public function onGetBackendController()
    {
        $this->get('template')->addTemplateDir($this->Path() . 'Views/');

        return $this->Path() . 'Controllers/ExampleModulePlainHtml.php';
    }
}
