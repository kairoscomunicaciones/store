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

class PsxDesignMarketPlaceModuleDataQuery
{
    /**
     * @var int
     */
    private $idModule;

    /**
     * @var string
     */
    private $psVersion;

    /**
     * @var string
     */
    private $langCode;

    /**
     * @var string
     */
    private $countryCode;

    public function __construct(int $idModule, string $psVersion, string $langCode, string $countryCode)
    {
        $this->idModule = $idModule;
        $this->psVersion = $psVersion;
        $this->langCode = $langCode;
        $this->countryCode = $countryCode;
    }

    /**
     * @return int
     */
    public function getIdModule(): int
    {
        return $this->idModule;
    }

    /**
     * @return string
     */
    public function getPsVersion(): string
    {
        return $this->psVersion;
    }

    /**
     * @return string
     */
    public function getLangCode(): string
    {
        return $this->langCode;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}
