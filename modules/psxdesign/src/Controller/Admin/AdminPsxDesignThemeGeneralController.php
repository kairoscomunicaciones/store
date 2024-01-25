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

namespace PrestaShop\Module\PsxDesign\Controller\Admin;

use PrestaShopBundle\Controller\Admin\Improve\Design\ThemeController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPsxDesignThemeGeneralController extends ThemeController
{
    private const PSXDESIGN_MODULE_UPGRADE_NEEDED = 'PSXDESIGN_MODULE_UPGRADE_NEEDED';
    private const PSXDESIGN_MARKETPLACE_MODULE_VERSION = 'PSXDESIGN_MARKETPLACE_MODULE_VERSION';

    /**
     * Show main themes page.
     *
     * @AdminSecurity(
     *     "is_granted('read', request.get('_legacy_controller'))",
     *     message="You do not have permission to edit this."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        /** @var AdapterInterface $cache */
        $cache = $this->get('cache.app');

        $upgradeProcessor = $this->get('prestashop.module.psxdesign.marketplace.upgrade.check.processor');
        $upgradeCacheItem = $cache->getItem($upgradeProcessor->buildUpgradeAvailableCacheId());
        $configuration = $this->get('prestashop.adapter.legacy.configuration');
        $notificationNeeded = $this->get('psxdesign.module')->isNotificationNeeded();

        if ($notificationNeeded && ($upgradeCacheItem->get() || $configuration->getBoolean(self::PSXDESIGN_MODULE_UPGRADE_NEEDED))) {
            $template = $this->render(
                '@Modules/psxdesign/views/templates/upgrade-notification/alert-notification.html.twig',
                [
                    'psxdesignUpgradeUrl' => $this->generateUrl(
                        'admin_psxdesign_upgrade_action'
                    ),
                ]
            );

            $this->addFlash('psxdesign-info', $template->getContent());
        }

        return parent::indexAction($request);
//        TODO: uncomment when we want to show our page
//        return $this->render('@Modules/psxdesign/views/templates/themes/index.html.twig');
    }
}
