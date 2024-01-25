<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Mr. APPs
 * @copyright Mr. APPs 2023
 * @license Mr. APPs
 */

namespace MrAPPs\MrShopApi\Handler;

use Manufacturer;
use MrAPPs\MrShopApi\Service\URLFragmentSerializer;
use Tools;
use Translate;
use Validate;

class CategoryBrandFacetsHandler
{
    private $module;

    private $blocklayered = false;

    public function __construct($module = null)
    {
        $this->module = $module;
        $this->blocklayered = version_compare(_PS_VERSION_, '1.7.0.0', '<') && \Module::isEnabled('blocklayered');
    }

    public function generateFacets($id_category, $id_manufacturer, $order = null)
    {
        $parameters = ['id_category' => (int) $id_category];

        if ($order !== null) {
            $parameters['order'] = $order;
        }

        $manufacturerId = (int) $id_manufacturer;
        $manufacturer = new Manufacturer($manufacturerId);
        $urlSerializer = new URLFragmentSerializer();

        if (Validate::isLoadedObject($manufacturer)) {
            if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                $manufacturerKey = $this->module->getTranslator()->trans('Brand', [], 'Modules.Facetedsearch.Shop');
                $facetFilters = [$manufacturerKey => [$manufacturerKey => $manufacturer->name]];
                $parameters['q'] = $urlSerializer->serialize($facetFilters);
            // category=X&q=Marca-Nome Marca (nome con spazi)
            } else {
                if ($this->blocklayered) {
                    require_once _PS_ROOT_DIR_.'/classes/Translate.php';
                    $manufacturerKey = Translate::getModuleTranslation('blocklayered', 'Manufacturer', 'blocklayered');
                    $manufacturerName = trim($manufacturer->name);
                    $facetFilters = [Tools::strtolower($manufacturerKey) => [$manufacturerName => Tools::strtolower(str_replace(' ', '_', $manufacturer->name))]];
                    $parameters['q'] = $urlSerializer->serialize($facetFilters);
                // category=X&q=produttore-nome_produttore (nome minuscolo con separatore _)
                } else {
                    $manufacturerKey = $this->module->l('Manufacturer', 'categorybrandfacetshandler');
                    $facetFilters = [$manufacturerKey => [$manufacturerId => $manufacturerId]];
                    $parameters['q'] = $urlSerializer->serialize($facetFilters);
                    // category=X&q=Marca-marca.id
                }
            }
        }

        return http_build_query($parameters);
    }
}
