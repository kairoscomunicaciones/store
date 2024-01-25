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

use Exception;
use GuzzleHttp\Client;
use PrestaShop\Module\PsxDesign\Exception\MarketPlaceException;

class PsxDesignModuleDataProvider
{
    private const BASE_URL = 'https://api.addons.prestashop.com/?';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var PsxDesignMarketPlaceModuleDataQuery
     */
    private $moduleDataQuery;

    public function __construct(
        Client $httpClient,
        PsxDesignMarketPlaceModuleDataQuery $moduleDataQuery
    ) {
        $this->httpClient = $httpClient;
        $this->moduleDataQuery = $moduleDataQuery;
    }

    /**
     * Get module data from PrestaShop marketplace.
     *
     * @return string
     */
    public function getModuleData(): ?string
    {
        $response = $this->httpClient->get(
            self::BASE_URL .
            'version=' . $this->moduleDataQuery->getPsVersion() .
            '&iso_lang=' . $this->moduleDataQuery->getLangCode() .
            '&iso_code=' . $this->moduleDataQuery->getCountryCode() .
            '&method=version' .
            '&id_module=' . $this->moduleDataQuery->getIdModule()
        );

        $data = $response->getBody()->getContents();

        try {
            $moduleVersion = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        } catch (Exception $exception) {
            throw new MarketPlaceException($exception);
        }

        return $moduleVersion ? $moduleVersion->number->__toString() : null;
    }
}
