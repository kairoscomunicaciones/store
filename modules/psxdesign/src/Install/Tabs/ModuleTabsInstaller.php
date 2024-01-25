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

namespace PrestaShop\Module\PsxDesign\Install\Tabs;

use Language;
use PrestaShopBundle\Entity\Repository\TabRepository;
use PsxDesign;
use Tab;

class ModuleTabsInstaller
{
    /* @var TabRepository */
    private $tabRepository;

    /* @var PsxDesign */
    private $module;

    public function __construct(TabRepository $tabRepository, PsxDesign $module)
    {
        $this->tabRepository = $tabRepository;
        $this->module = $module;
    }

    /**
     * Get module tabs information for installation
     *
     * @return array<int, array<string, mixed>>
     */
    private function getTabs(): array
    {
        return [
            [
                'class_name' => 'AdminPsxDesignLogos',
                'visible' => true,
                'name' => 'Logos',
                'route_name' => 'admin_logos_index',
                'parent_class_name' => 'AdminThemesParent',
                'wording' => 'Logos',
                'wording_domain' => 'Modules.Theme.Admin',
            ],
        ];
    }

    public function installTabs(): bool
    {
        $tabs = $this->getTabs();
        $translator = $this->module->getTranslator();

        foreach ($tabs as $tab) {
            $tabId = $this->tabRepository->findOneIdByClassName($tab['class_name']);

            if (!$tabId) {
                $tabId = null;
            }

            $newTab = new Tab($tabId);
            $newTab->active = $tab['visible'];
            $newTab->class_name = $tab['class_name'];

            $newTab->route_name = $tab['route_name'];
            $newTab->name = [];
            $newTab->id_parent = $this->tabRepository->findOneIdByClassName($tab['parent_class_name']);

            foreach (Language::getLanguages() as $lang) {
                $newTab->name[$lang['id_lang']] = $translator->trans($tab['name'], [], 'Modules.Theme.Admin', $lang['locale']);
            }

            $newTab->module = $this->module->name;

            if (!$newTab->save()) {
                return false;
            }
        }

        return true;
    }

    public function uninstallTabs(): bool
    {
        $tabs = $this->getTabs();

        foreach ($tabs as $tab) {
            $tabId = (int) $this->tabRepository->findOneIdByClassName($tab['class_name']);

            if (!$tabId) {
                return true;
            }

            $tab = new Tab($tabId);
            $tab->delete();
        }

        return true;
    }
}
