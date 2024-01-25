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

class TabsModificator
{
    private const LOGOS_CONTROLLER = 'AdminPsxDesignLogos';
    private const LOGO_TAB_POSITION = 2;

    /** @var TabRepository */
    private $tabRepository;

    /** @var PsxDesign */
    private $module;

    public function __construct(TabRepository $tabRepository, PsxDesign $module)
    {
        $this->tabRepository = $tabRepository;
        $this->module = $module;
    }

    public function modifyExistingTabsSettings(): bool
    {
        return $this->renameTabs() && $this->changeTabPosition();
    }

    /**
     * Renaming existing tabs on installation to provide more understandable names
     *
     * @return bool
     */
    private function renameTabs(): bool
    {
        $translator = $this->module->getTranslator();
        $tabs = $this->getTabsToRename();

        foreach ($tabs as $tab) {
            $tabId = $this->tabRepository->findOneIdByClassName($tab['className']);
            $psTab = new Tab($tabId);

            if (!$psTab->id) {
                return false;
            }

            foreach (Language::getLanguages() as $lang) {
                $psTab->name[$lang['id_lang']] = $translator->trans($tab['name'], [], 'Modules.Theme.Admin', $lang['locale']);
            }

            $psTab->update();
        }

        return true;
    }

    /**
     * Changing tabs position to provide easier accessibility and visibility to the configurations page
     *
     * @return bool
     */
    private function changeTabPosition(): bool
    {
        $tabId = $this->tabRepository->findOneIdByClassName(self::LOGOS_CONTROLLER);
        $tab = new Tab($tabId);

        if (!$tab->id) {
            return false;
        }

        $tab->updatePosition(false, self::LOGO_TAB_POSITION);

        return true;
    }

    /**
     * List of tabs that has to be renamed
     *
     * @return array<string, array{name: string, className: string}>
     */
    private function getTabsToRename(): array
    {
        return [
            'sidebarMainTab' => [
                'name' => 'Theme Manager',
                'className' => 'AdminThemesParent',
            ],
            'generalTab' => [
                'name' => 'Themes',
                'className' => 'AdminThemes',
            ],
        ];
    }
}
