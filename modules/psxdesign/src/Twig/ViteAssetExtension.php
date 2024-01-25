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

namespace PrestaShop\Module\PsxDesign\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private $isDev;

    public function __construct(
        $isDev
    ) {
        $this->isDev = $isDev;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']]),
        ];
    }

    public function asset(array $entries): string
    {
        if ($this->isDev) {
            return $this->assetDev($entries);
        }

        return $this->assetProd($entries);
    }

    public function assetDev(array $entries): string
    {
        $html = "<script type='module' src='//localhost:5173/_dev/@vite/client'></script>" . PHP_EOL;
        foreach ($entries as $entry) {
            $html .= "<script type='module' src='//localhost:5173/_dev/src/components/$entry/$entry.ts' defer></script>" . PHP_EOL;
        }

        return $html;
    }

    public function assetProd(array $entries): string
    {
        $html = ' ';
        foreach ($entries as $entry) {
            $html .= "<script type='module' src='/modules/psxdesign/views/js/$entry.js' defer></script>" . PHP_EOL;
        }

        $html = ' ';
        foreach ($entries as $entry) {
            $html .= "<script type='module' src='/modules/psxdesign/views/js/$entry.js' defer></script>" . PHP_EOL;
        }

        return $html;
    }
}
