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

namespace PrestaShop\Module\PsxDesign\Handler;

use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class PsxDesignModuleUpgradeCacheHandler
{
    private const CACHE_EXPIRATION_TIME = 'PT1440M';
    private const PSXDESIGN_MODULE_UPGRADE_NEEDED = 'PSXDESIGN_MODULE_UPGRADE_NEEDED';

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $upgradeCacheId;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(
        AdapterInterface $cache,
        string $upgradeCacheId,
        ConfigurationInterface $configuration
    ) {
        $this->cache = $cache;
        $this->upgradeCacheId = $upgradeCacheId;
        $this->configuration = $configuration;
    }

    public function updateModuleNotificationStatuses(): void
    {
        $this->configuration->set(self::PSXDESIGN_MODULE_UPGRADE_NEEDED, 0);
        $upgradeNeededCacheItem = $this->cache->getItem($this->upgradeCacheId);
        $upgradeNeededCacheItem->set(false);
        $upgradeNeededCacheItem->expiresAfter(new \DateInterval(self::CACHE_EXPIRATION_TIME));
        $this->cache->save($upgradeNeededCacheItem);
    }
}
