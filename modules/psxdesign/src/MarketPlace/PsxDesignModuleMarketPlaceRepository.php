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

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ForwardCompatibility\Result;

class PsxDesignModuleMarketPlaceRepository
{
    private const DAY = 1;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * @var string
     */
    private $configurationKey;

    public function __construct(Connection $connection, string $dbPrefix, string $configurationKey)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
        $this->configurationKey = $configurationKey;
    }

    /**
     * Checks if request was done in more than 1 day.
     *
     * @return bool
     */
    public function updatedInLessThanDay(): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('date_upd')
            ->from($this->dbPrefix . 'configuration')
            ->where('name = :configurationKey')
            ->setParameter('configurationKey', $this->configurationKey);

        /** @var Result $execute */
        $execute = $qb->execute();

        $lastUpdate = $execute->fetchOne();

        if (!$lastUpdate) {
            return false;
        }

        $lastUpdate = new DateTime($lastUpdate);
        $diff = $lastUpdate->diff(new DateTime())->days;

        return (int) $diff < self::DAY;
    }
}
