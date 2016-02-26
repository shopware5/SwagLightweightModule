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
        return [
            'install' => true,
            'enable' => true,
            'update' => true
        ];
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
            $this->registerController('Backend', 'ExampleModulePlainHtml');
            $this->createMenu();

            return [
                'success' => true,
                'invalidateCache' => ['backend']
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Creates the Favorites backend menu item.
     *
     * The Favorites menu item opens the listing for the SwagFavorites plugin.
     */
    public function createMenu()
    {
        $this->createMenuItem(
            [
                'label' => 'Lightweight Backend Module',
                'onclick' => 'Shopware.ModuleManager.createSimplifiedModule("ExampleModulePlainHtml", { "title": "Lightweight Backend Module" })',
                'class' => 'sprite-application-icon-large',
                'active' => 1,
                'parent' => $this->Menu()->findOneBy(['label' => 'Einstellungen'])
            ]
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
}
