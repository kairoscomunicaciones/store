<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

use PrestaShop\Module\PsxDesign\Install\Tabs\ModuleTabsInstaller;
use PrestaShop\Module\PsxDesign\Install\Tabs\TabsModificator;
use PrestaShop\Module\PsxDesign\MarketPlace\PsxDesignUpgradeCheckProcessor;
use PrestaShop\PrestaShop\Adapter\Cache\Clearer\SymfonyCacheClearer;
use PrestaShop\PrestaShop\Adapter\Module\ModuleDataProvider;
use PrestaShopBundle\Entity\Repository\TabRepository;
use Symfony\Component\Templating\EngineInterface;

if (!defined('_PS_VERSION_')) {
    exit();
}

class PsxDesign extends Module
{
    /**
     * Module ID created when registering product on marketplace and required to get information from marketplace.
     */
    public const ADDONS_MODULE_ID = 89361;
    private const PSXDESIGN_MARKETPLACE_MODULE_VERSION = 'PSXDESIGN_MARKETPLACE_MODULE_VERSION';
    private const PSXDESIGN_MODULE_UPGRADE_NEEDED = 'PSXDESIGN_MODULE_UPGRADE_NEEDED';

    public function __construct()
    {
        $this->name = 'psxdesign';
        $this->tab = 'others';
        $this->version = '0.1.1';
        $this->author = 'PrestaShop';
        $this->need_instance = 1;
        $this->module_key = '82148d7b60bbd40f98c65ac7ae3e431a';

        parent::__construct();

        $this->autoload();

        $this->displayName = $this->getTranslator()->trans(
            'PrestaShop Design',
            [],
            'Modules.Theme.Admin'
        );

        $this->description =
            $this->getTranslator()->trans(
                'PrestaShop Design allows you to be more autonomous in the complete and advanced customization of your PrestaShop store.',
                [],
                'Modules.Theme.Admin'
            );

        $this->ps_versions_compliancy = [
            'min' => '8',
            'max' => _PS_VERSION_,
        ];
    }

    /**
     * This function is required in order to make module compatible with new translation system.
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function install(): bool
    {
        /** @var PrestaShop\PrestaShop\Adapter\Configuration $configuration */
        $configuration = $this->get('prestashop.adapter.legacy.configuration');

        $configuration->set(self::PSXDESIGN_MARKETPLACE_MODULE_VERSION, '0');
        $configuration->set(self::PSXDESIGN_MODULE_UPGRADE_NEEDED, '0');

        /** @var TabRepository $tabRepository */
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');

        return parent::install() &&
//            TODO: uncomment when we want to show our pages
//            (new ModuleTabsInstaller($tabRepository, $this))->installTabs() &&
//            (new TabsModificator($tabRepository, $this))->modifyExistingTabsSettings() &&
            $this->registerHook('actionAdminControllerSetMedia');
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function uninstall(): bool
    {
        /** @var PrestaShop\PrestaShop\Adapter\Configuration $configuration */
        $configuration = $this->get('prestashop.adapter.legacy.configuration');

        $configuration->remove(self::PSXDESIGN_MARKETPLACE_MODULE_VERSION);
        $configuration->remove(self::PSXDESIGN_MODULE_UPGRADE_NEEDED);

        /** @var TabRepository $tabRepository */
        $tabRepository = $this->get('prestashop.core.admin.tab.repository');

        return parent::uninstall() && (new ModuleTabsInstaller($tabRepository, $this))->uninstallTabs();
    }

    /**
     * @return void
     */
    private function autoload(): void
    {
        include_once "{$this->getLocalPath()}vendor/autoload.php";
    }

    public function hookActionAdminControllerSetMedia(): void
    {
        /** @var PsxDesignUpgradeCheckProcessor $upgradeCheckProcessor */
        $upgradeCheckProcessor = $this->get('prestashop.module.psxdesign.marketplace.upgrade.check.processor');

        if ($upgradeCheckProcessor->isUpgradeAvailable() && $this->isNotificationNeeded()) {
            $this->context->controller->addCSS($this->getPathUri() . 'views/css/upgrade-notification/dashboard-notification.css');
            $this->context->controller->addJS($this->getPathUri() . 'views/js/upgrade-notification.js');

            /** @var EngineInterface $twig */
            $twig = $this->get('twig');

            Media::addJsDef([
                'psxDesignUpdateNotification' => $twig->render('@Modules/psxdesign/views/templates/upgrade-notification/dashboard-notification.html.twig'),
            ]);
        }

        if ($this->context->controller->controller_name === 'AdminThemes') {
            $this->context->controller->addJS($this->getPathUri() . 'views/js/theme-and-logo.js');
        }
    }

    /**
     * @param bool $force_all
     *
     * @return bool
     */
    public function disable($force_all = false): bool
    {
        /** @var SymfonyCacheClearer $cache */
        $cache = $this->get('prestashop.adapter.cache.clearer.symfony_cache_clearer');
        $cache->clear();

        return parent::disable($force_all);
    }

    public function isNotificationNeeded(): bool
    {
        /** @var ModuleDataProvider $moduleDataProvider */
        $moduleDataProvider = $this->get('prestashop.adapter.data_provider.module');
        $module = $moduleDataProvider->findByName($this->name);

        if ($this->context->isMobile() && !$module['active_on_mobile']) {
            return false;
        }

        /** @var PrestaShop\PrestaShop\Adapter\Configuration $configuration */
        $configuration = $this->get('prestashop.adapter.legacy.configuration');

        if ($this->version === $configuration->get(self::PSXDESIGN_MARKETPLACE_MODULE_VERSION)) {
            return false;
        }

        return true;
    }
}
