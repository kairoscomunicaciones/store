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

namespace PrestaShop\Module\PsxDesign\MarketPlace;

use PrestaShop\PrestaShop\Adapter\Configuration;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class PsxDesignUpgradeCheckProcessor
{
    public const PSXDESIGN_MARKETPLACE_MODULE_VERSION = 'PSXDESIGN_MARKETPLACE_MODULE_VERSION';
    private const PSXDESIGN_MODULE_UPGRADE_NEEDED = 'PSXDESIGN_MODULE_UPGRADE_NEEDED';
    private const CACHE_EXPIRATION_TIME = 'PT1440M'; // One day in minutes

    /**
     * @var PsxDesignModuleDataProvider
     */
    private $marketplaceDataProvider;

    /**
     * @var PsxDesignModuleMarketPlaceRepository
     */
    private $marketPlaceRepository;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var string
     */
    private $moduleVersion;

    public function __construct(
        PsxDesignModuleDataProvider $marketplaceDataProvider,
        PsxDesignModuleMarketPlaceRepository $marketPlaceRepository,
        AdapterInterface $cache,
        Configuration $configuration,
        string $moduleName,
        string $moduleVersion
    ) {
        $this->marketplaceDataProvider = $marketplaceDataProvider;
        $this->marketPlaceRepository = $marketPlaceRepository;
        $this->cache = $cache;
        $this->configuration = $configuration;
        $this->moduleVersion = $moduleVersion;
        $this->moduleName = $moduleName;
    }

    /**
     * Processor which checks module information from marketplace if cache or configuration settings does not exist
     *
     * @return bool
     */
    public function isUpgradeAvailable(): bool
    {
        if ($this->cache->hasItem($this->buildCacheExistId()) && $this->cache->hasItem($this->buildCacheExistId()) !== null) {
            return (bool) $this->cache->getItem($this->buildUpgradeAvailableCacheId())->get();
        }

        if ($this->marketPlaceRepository->updatedInLessThanDay() && $this->configuration->get(self::PSXDESIGN_MARKETPLACE_MODULE_VERSION)) {
            return $this->configuration->getBoolean(self::PSXDESIGN_MODULE_UPGRADE_NEEDED);
        }

        $moduleMarketPlaceVersion = $this->marketplaceDataProvider->getModuleData();

        if (!$moduleMarketPlaceVersion) {
            return false;
        }

        $isUpgradeNeeded = (int) version_compare($moduleMarketPlaceVersion, $this->moduleVersion, '>');

        $this->configuration->set(self::PSXDESIGN_MARKETPLACE_MODULE_VERSION, $moduleMarketPlaceVersion);
        $this->configuration->set(self::PSXDESIGN_MODULE_UPGRADE_NEEDED, $isUpgradeNeeded);
        $this->setCache((bool) $isUpgradeNeeded);

        return (bool) $isUpgradeNeeded;
    }

    /**
     * @param bool $isUpgradeNeeded
     *
     * @return void
     */
    private function setCache(bool $isUpgradeNeeded): void
    {
        $cacheExistItem = $this->cache->getItem($this->buildCacheExistId());
        $cacheExistItem->set(true);
        $cacheExistItem->expiresAfter(new \DateInterval(self::CACHE_EXPIRATION_TIME));
        $this->cache->save($cacheExistItem);

        $cacheUpdateNeededItem = $this->cache->getItem($this->buildUpgradeAvailableCacheId());
        $cacheUpdateNeededItem->set($isUpgradeNeeded);
        $cacheUpdateNeededItem->expiresAfter(new \DateInterval(self::CACHE_EXPIRATION_TIME));
        $this->cache->save($cacheUpdateNeededItem);
    }

    /**
     * Builds unique cache id for checking existing cache
     *
     * @return string
     */
    private function buildCacheExistId(): string
    {
        return $this->moduleName . '-exist';
    }

    /**
     * Builds unique cache id for checking cache
     *
     * @return string
     */
    public function buildUpgradeAvailableCacheId(): string
    {
        return $this->moduleName . '-upgradeAvailable';
    }
}
