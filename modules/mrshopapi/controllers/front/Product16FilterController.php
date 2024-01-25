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
require_once _PS_MODULE_DIR_.'mrshopapi/src/Service/URLFragmentSerializer.php';

class Product16FilterController
{
    protected $category;

    protected $context;

    protected $module;

    public function __construct($module)
    {
        $id_category = (int) Tools::getValue('id_category');

        if ($id_category == 0) {
            $id_category = Configuration::get('PS_HOME_CATEGORY');
        }

        $this->context = Context::getContext();

        $this->category = new Category(
            $id_category,
            $this->context->language->id
        );

        $this->module = $module;
    }

    private function getLegacyPrefix($entity, $field)
    {
        if ($entity === 'product') {
            if ($field === 'name') {
                return 'pl.';
            } elseif ($field === 'position') {
                return 'cp.';
            } elseif ($field === 'manufacturer_name') {
                return 'm.';
            } else {
                return 'p.';
            }
        } elseif ($entity === 'manufacturer') {
            return 'm.';
        }
    }

    private function toLegacyOrderBy($prefix = false, $entity, $field)
    {
        if ($prefix) {
            return $this->getLegacyPrefix($entity, $field).($entity == 'product' && $field == 'manufacturer_name' ? 'name' : $field);
        } elseif ($entity === 'manufacturer' && $field === 'name') {
            return 'manufacturer_name';
        } else {
            return bqSQL($field);
        }
    }

    private function getSortingData($paramValue)
    {
        list($entity, $field, $direction) = explode('.', $paramValue);

        return [
            'entity' => $entity,
            'field' => $field,
            'direction' => trim($direction),
        ];
    }

    private function getFacets($filtersBlock)
    {
        $facets = [];

        $selectedFilters = $filtersBlock['selected_filters'];

        foreach ($filtersBlock['filters'] as $filter) {
            if ($filter['type'] !== 'price') {
                $facet = [
                    'id' => trim($filter['id_key'] > 0 ? $filter['id_key'] : $filter['type']).'-',
                    'label' => $filter['name'],
                    'type' => $filter['type'],
                    'input_type' => 'checkboxes',
                    'filters' => [],
                ];

                $selectedGroup = array_key_exists($filter['type'], $selectedFilters) ? $selectedFilters[$filter['type']] : [];

                foreach ($filter['values'] as $key => $value) {
                    if (is_array($value) && array_key_exists('name', $value) && (array_key_exists('encodedFacet', $value) || array_key_exists('link', $value))) {
                        $encodedFacet = array_key_exists('encodedFacet', $value) ?
                            $value['encodedFacet'] :
                            Tools::substr($value['link'], strpos($value['link'], '#') + 1, Tools::strlen($value['link']) - 1);

                        if ($facet['type'] == 'quantity') {
                            $isSelected = !empty($selectedGroup) && $key == (int) $selectedGroup[0];
                        } else {
                            $isSelected = array_key_exists($key, $selectedGroup);
                        }

                        $facet['filters'][] = [
                            'id' => trim(str_replace(' ', '', $value['name'].'-'.$key)),
                            'label' => $value['name'],
                            'products_quantity' => isset($value['nbr']) ? (int) $value['nbr'] : 0,
                            'selected' => $isSelected,
                            'encoded_facets' => $encodedFacet,
                        ];
                    }
                }

                if (count($facet['filters']) > 0) {
                    $facets[] = $facet;
                }
            }
        }

        return $facets;
    }

    private function getSortOrdersTemp($module)
    {
        return [
            'name' => [
                'asc' => $module->l('Name, A to Z', 'product16filtercontroller'),
                'desc' => $module->l('Name, Z to A', 'product16filtercontroller'),
            ],
            'price' => [
                'asc' => $module->l('Price, lowest first', 'product16filtercontroller'),
                'desc' => $module->l('Price, highest first', 'product16filtercontroller'),
            ],
            'date_add' => [
                'asc' => $module->l('Date add, oldest first', 'product16filtercontroller'),
                'desc' => $module->l('Date add, recent first', 'product16filtercontroller'),
            ],
            'date_upd' => [
                'asc' => $module->l('Date edit, oldest first', 'product16filtercontroller'),
                'desc' => $module->l('Date edit, recent first', 'product16filtercontroller'),
            ],
            'manufacturer_name' => [
                'asc' => $module->l('Brand, A to Z', 'product16filtercontroller'),
                'desc' => $module->l('Brand, Z to A', 'product16filtercontroller'),
            ],
            'quantity' => [
                'asc' => $module->l('Quantity ascending', 'product16filtercontroller'),
                'desc' => $module->l('Quantity descending', 'product16filtercontroller'),
            ],
            'reference' => [
                'asc' => $module->l('Reference, A to Z', 'product16filtercontroller'),
                'desc' => $module->l('Reference, Z to A', 'product16filtercontroller'),
            ],
        ];
    }

    private function getSortOrders($module)
    {
        $sortOrdersTemp = $this->getSortOrdersTemp($module);

        $sortOrders = [];

        foreach ($sortOrdersTemp as $fieldName => $directions) {
            foreach ($directions as $direction => $label) {
                $encodedOrder = 'product.'.$fieldName.'.'.$direction;
                $sortOrders[$encodedOrder] = [
                    'label' => $label,
                    'encoded_order' => $encodedOrder,
                    'selected' => false,
                ];
            }
        }

        return $sortOrders;
    }

    public function getResponse($onlyResult = false, $resultsPerPage = 10)
    {
        if (Module::isEnabled('blocklayered')) {
            /** @var BlockLayered $module */
            $module = Module::getInstanceByName('blocklayered');

            $propProducts = new ReflectionProperty('BlockLayered', 'products');
            $propProducts->setAccessible(true);
            $propProducts->setValue($module, null);

            $this->context->cookie->nb_item_per_page = $resultsPerPage;

            $orderValue = Tools::getValue('order');

            if ($orderValue !== false) {
                $sortData = $this->getSortingData($orderValue);
                $orderIndexes = [
                    'name' => 0,
                    'price' => 1,
                    'date_add' => 2,
                    'date_upd' => 3,
                    'manufacturer_name' => 5,
                    'quantity' => 6,
                    'reference' => 7,
                ];
                $_POST['orderby'] = $orderIndexes[$sortData['field']];
                $_POST['orderway'] = $sortData['direction'];
            } else {
                $_POST['orderby'] = 2;
                $_POST['orderway'] = 'desc';
            }

            $propReader = function &($object, $property) {
                $value = &Closure::bind(function &() use ($property) {
                    return $this->$property;
                }, $object, $object)->__invoke();

                return $value;
            };

            $methodReader = function &($object, $method) {
                $value = &Closure::bind(function &() use ($method) {
                    $result = $this->$method();

                    return $result;
                }, $object, $object)->__invoke();

                return $value;
            };

            $page = &$propReader($module, 'page');
            $page = max((int) Tools::getValue('page'), 1);

            if (Tools::getIsset('q')) {
                $_GET['selected_filters'] = Tools::getValue('q');
            }
            $selectedFilters = $methodReader($module, 'getSelectedFilters');

            if (!$selectedFilters) {
                $selectedFilters = ['category' => []];
            }
            if (Tools::getIsset('id_category')) {
                if (!isset($selectedFilters['category'])) {
                    $selectedFilters['category'] = [];
                }

                // removing the category if is added by the blocklayered module (with wrong key)
                foreach ($selectedFilters['category'] as $key => $value) {
                    if ((int) $value == (int) Tools::getValue('id_category')) {
                        unset($selectedFilters['category'][$key]);
                    }
                }

                // only if there isn't another category selected with the "q" parameter
                if (count($selectedFilters['category']) == 0) {
                    $selectedFilters['category'][(int) Tools::getValue('id_category')] = (int) Tools::getValue('id_category');
                }
            }

            if (!$onlyResult) {
                $filtersBlock = $module->getFilterBlock($selectedFilters);
                $sortOrders = $this->getSortOrders($module);
                if (array_key_exists(Tools::strtolower($orderValue), $sortOrders)) {
                    $sortOrders[Tools::strtolower($orderValue)]['selected'] = true;
                }
            }

            $home_category = Configuration::get('PS_HOME_CATEGORY');
            $id_parent = (int) Tools::getValue('id_category', Tools::getValue('id_category_layered', $home_category));

            if ($id_parent == $home_category) {
                $alias_where = 'product_shop';
                $nb_day_new_product = (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20);

                if (version_compare(_PS_VERSION_, '1.6.1', '>=') === true) {
                    $baseQuery = '
                    FROM '._DB_PREFIX_.'product p
                   '.Shop::addSqlAssociation('product', 'p').(Combination::isFeatureActive() ?
                            ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
                           ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $this->context->shop->id.')' : '').'
                    LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product'.Shop::addSqlRestrictionOnLang('pl').' AND pl.id_lang = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                           ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int) $this->context->shop->id.')
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (m.id_manufacturer = p.id_manufacturer)
                   '.Product::sqlStock('p', 0).'
                    WHERE '.pSQL($alias_where).'.`active` = 1 AND '.pSQL($alias_where).'.`visibility` IN ("both", "catalog") ';

                    $resultsQuery = 'SELECT
                       p.*,
                       product_shop.*,
                       '.pSQL($alias_where).'.id_category_default,
                       pl.*,
                       image_shop.`id_image` id_image,
                       il.legend,
                       m.name manufacturer_name,
                       '.(Combination::isFeatureActive() ? 'product_attribute_shop.id_product_attribute id_product_attribute,' : '').'
                       DATEDIFF('.pSQL($alias_where).'.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00", INTERVAL '.(int) $nb_day_new_product.' DAY)) > 0 AS new,
                       stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity'.(Combination::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity' : '').
                        $baseQuery.
                        'ORDER BY '.Tools::getProductsOrder(
                            'by',
                            Tools::getValue('orderby'),
                            true
                        ).' '.Tools::getProductsOrder('way', Tools::getValue('orderway')).
                        ' LIMIT '.(((int) $page - 1) * $resultsPerPage.','.(int) $resultsPerPage);

                    // Count
                    $countQuery = 'SELECT COUNT(*) AS n '.$baseQuery;

                    $cntResult = Db::getInstance()->executeS($countQuery, true, false);
                    $count = is_array($cntResult) && count($cntResult) > 0 && array_key_exists(
                        'n',
                        $cntResult[0]
                    ) ? (int) $cntResult[0]['n'] : 0;
                } else {
                    $countQuery = '
                    SELECT
                    p.*,
                    product_shop.*,
                    '.pSQL($alias_where).'.id_category_default,
                    pl.*,
                    MAX(image_shop.`id_image`) id_image,
                    il.legend,
                    m.name manufacturer_name,
                    '.(Combination::isFeatureActive() ? 'MAX(product_attribute_shop.id_product_attribute) id_product_attribute,' : '').'
                    DATEDIFF('.pSQL($alias_where).'.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00", INTERVAL '.(int) $nb_day_new_product.' DAY)) > 0 AS new,
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity'.(Combination::isFeatureActive() ? ', MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity' : '').
                        ' FROM '._DB_PREFIX_.'product p
                    '.Shop::addSqlAssociation('product', 'p').
                        (Combination::isFeatureActive() ?
                            'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
                    '.Shop::addSqlAssociation(
                                'product_attribute',
                                'pa',
                                false,
                                'product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $this->context->shop->id
                            ) : '').'
                    LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product'.Shop::addSqlRestrictionOnLang('pl').' AND pl.id_lang = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'image` i  ON (i.`id_product` = p.`id_product`)'.
                        Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (m.id_manufacturer = p.id_manufacturer)
                    '.Product::sqlStock('p', 0).'
                    WHERE '.pSQL($alias_where).'.`active` = 1 AND '.pSQL($alias_where).'.`visibility` IN ("both", "catalog")
                    GROUP BY product_shop.id_product ';

                    $resultsQuery = $countQuery.
                        'ORDER BY '.Tools::getProductsOrder(
                            'by',
                            Tools::getValue('orderby'),
                            true
                        ).' '.Tools::getProductsOrder('way', Tools::getValue('orderway')).
                        ' LIMIT '.(((int) $page - 1) * $resultsPerPage.','.(int) $resultsPerPage);

                    // Count
                    $cntResult = Db::getInstance()->executeS($countQuery, true, false);
                    $count = is_array($cntResult) ? count($cntResult) : 0;
                }

                $products = Db::getInstance()->executeS($resultsQuery, true, false);

                if (Tools::getProductsOrder('by', Tools::getValue('orderby'), true) == 'p.price') {
                    Tools::orderbyPrice($products, Tools::getProductsOrder('way', Tools::getValue('orderway')));
                }

                $facets = null;
            } else {
                $products = $module->getProductByFilters($selectedFilters);
                if (!$onlyResult) {
                    $facets = $this->getFacets($filtersBlock);
                    $count = (int) $propReader($module, 'nbr_products');
                }
            }

            if ($onlyResult) {
                $response = ['result' => $products];
            } else {
                $response = [
                    'facets' => $facets,
                    'sort_orders' => array_values($sortOrders),
                    'count' => $count,
                    'result' => $products,
                ];
            }

            return $response;
        } else {
            return $this->getNoModuleResponse($onlyResult, $resultsPerPage);
        }
    }

    private function getNoModuleResponse($onlyResult = false, $resultsPerPage = 10)
    {
        // ------------------- PAGINATION -------------
        $page = max((int) Tools::getValue('page'), 1);

        // ------------------- CATEGORY ---------------
        $home_category = Configuration::get('PS_HOME_CATEGORY');
        $id_parent = (int) Tools::getValue('id_category', Tools::getValue('id_category_layered', $home_category));

        // ------------------- SORT -------------------
        $orderBy = 'date_add';
        $orderWay = 'DESC';

        $orderValue = Tools::getValue('order');
        if ($orderValue !== false) {
            $sortData = $this->getSortingData($orderValue);
            $orderBy = $sortData['field'];
            $orderWay = $sortData['direction'];
        }
        $sortOrders = $this->getSortOrders($this->module);

        if (array_key_exists(Tools::strtolower($orderValue), $sortOrders)) {
            $sortOrders[Tools::strtolower($orderValue)]['selected'] = true;
        }

        // ------------------- FILTERS -------------------
        $selectedFilters = $this->getSelectedFilters();

        $brands = $this->getBrandsByFilters($selectedFilters, $id_parent);

        $manufacturersIds = array_key_exists('manufacturer', $selectedFilters) && is_array($selectedFilters['manufacturer']) ? array_keys($selectedFilters['manufacturer']) : [];

        foreach ($manufacturersIds as $manufacturerId) {
            $founded = false;
            foreach ($brands as $brand) {
                if ($brand['id_manufacturer'] == $manufacturerId) {
                    $founded = true;

                    break;
                }
            }

            if (!$founded) {
                unset($selectedFilters['manufacturer'][$manufacturerId]);
            }
        }

        $filtersBlock = [
            'selected_filters' => $selectedFilters,
            'filters' => [
                [
                    'id_key' => 0,
                    'type' => 'manufacturer',
                    'name' => $this->module->l('Manufacturer', 'product16filtercontroller'),
                    'values' => $this->refactorBrands($selectedFilters, $brands),
                ],
                [
                    'id_key' => 0,
                    'type' => 'category',
                    'name' => $this->module->l('Categories', 'product16filtercontroller'),
                    'values' => $this->getCategories($selectedFilters, $id_parent),
                ],
            ],
        ];

        $facets = $this->getFacets($filtersBlock);

        $manufacturers = [];
        $categories = [];

        if (isset($selectedFilters['category']) && is_array($selectedFilters['category'])) {
            foreach ($selectedFilters['category'] as $key => $value) {
                $categories[] = (int) $key;
            }
        }

        if (isset($selectedFilters['manufacturer']) && is_array($selectedFilters['manufacturer'])) {
            foreach ($selectedFilters['manufacturer'] as $key => $value) {
                $manufacturers[] = (int) $key;
            }
        }

        // ------------------- QUERY -------------------

        $alias_where = 'product_shop';
        $nb_day_new_product = (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20);

        if (version_compare(_PS_VERSION_, '1.6.1', '>=') === true) {
            $baseQuery = '
                    FROM '._DB_PREFIX_.'product p
                   '.Shop::addSqlAssociation('product', 'p').(Combination::isFeatureActive() ?
                    ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
                           ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $this->context->shop->id.')' : '').'
                    LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product'.Shop::addSqlRestrictionOnLang('pl').' AND pl.id_lang = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                           ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int) $this->context->shop->id.')
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (m.id_manufacturer = p.id_manufacturer)
                   '.Product::sqlStock('p', 0).'
                    WHERE '.$alias_where.'.`active` = 1 AND '.$alias_where.'.`visibility` IN ("both", "catalog") ';

            // New where filter

            $baseQuery .= ' AND p.id_product IN (SELECT DISTINCT id_product FROM `'._DB_PREFIX_.'category_product` WHERE id_category ='.(int) $id_parent.') ';

            if (count($manufacturers) > 0) {
                $baseQuery .= ' AND p.id_manufacturer IN ('.implode(' ,', $manufacturers).')';
            }

            if (count($categories) > 0) {
                $baseQuery .= ' AND p.id_product IN (SELECT DISTINCT id_product FROM `'._DB_PREFIX_.'category_product` WHERE id_category IN ('.implode(' ,', $categories).')) ';
            }

            $resultsQuery = 'SELECT
                       p.*,
                       product_shop.*,
                       '.pSQL($alias_where).'.id_category_default,
                       pl.*,
                       image_shop.`id_image` AS id_image,
                       il.legend,
                       m.name AS manufacturer_name,
                       '.(Combination::isFeatureActive() ? 'product_attribute_shop.id_product_attribute AS id_product_attribute,' : '').'
                       DATEDIFF('.pSQL($alias_where).'.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00", INTERVAL '.(int) $nb_day_new_product.' DAY)) > 0 AS new,
                       stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity'.(Combination::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity' : '').
                $baseQuery.
                ' ORDER BY '.$this->toLegacyOrderBy(true, 'product', $orderBy).' '.(strtolower($orderWay)=="desc"?"DESC":"ASC").
                ' LIMIT '.(((int) $page - 1) * $resultsPerPage.','.(int) $resultsPerPage);

            // Count
            $countQuery = 'SELECT COUNT(*) AS n '.$baseQuery;

            $cntResult = Db::getInstance()->executeS($countQuery, true, false);
            $count = is_array($cntResult) && count($cntResult) > 0 && array_key_exists(
                'n',
                $cntResult[0]
            ) ? (int) $cntResult[0]['n'] : 0;
        } else {
            $countQuery = '
                    SELECT
                    p.*,
                    product_shop.*,
                    '.pSQL($alias_where).'.id_category_default,
                    pl.*,
                    MAX(image_shop.`id_image`) id_image,
                    il.legend,
                    m.name manufacturer_name,
                    '.(Combination::isFeatureActive() ? 'MAX(product_attribute_shop.id_product_attribute) id_product_attribute,' : '').'
                    DATEDIFF('.pSQL($alias_where).'.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00", INTERVAL '.(int) $nb_day_new_product.' DAY)) > 0 AS new,
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity'.(Combination::isFeatureActive() ? ', MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity' : '').
                ' FROM '._DB_PREFIX_.'product p
                    '.Shop::addSqlAssociation('product', 'p').
                (Combination::isFeatureActive() ?
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
                    '.Shop::addSqlAssociation(
                        'product_attribute',
                        'pa',
                        false,
                        'product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int) $this->context->shop->id
                    ) : '').'
                    LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product'.Shop::addSqlRestrictionOnLang('pl').' AND pl.id_lang = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'image` i  ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $this->context->cookie->id_lang.')
                    LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (m.id_manufacturer = p.id_manufacturer)
                    '.Product::sqlStock('p', 0).'
                    WHERE '.pSQL($alias_where).'.`active` = 1 AND '.pSQL($alias_where).'.`visibility` IN ("both", "catalog")';

            // New where filter

            if ($manufacturers) {
                $countQuery .= ' AND p.id_manufacturer IN ('.implode(',', $manufacturers).')';
            }

            if (count($categories) > 0) {
                $countQuery .= ' AND p.id_product IN (SELECT DISTINCT id_product FROM `'._DB_PREFIX_.'category_product` WHERE id_category IN ('.implode(' ,', $categories).')) ';
            }

            $countQuery .= ' GROUP BY product_shop.id_product ';

            $resultsQuery = $countQuery.
                ' ORDER BY p.'.bqSQL($orderBy).' '.(strtolower($orderWay)=="desc"?"DESC":"ASC").
                ' LIMIT '.(((int) $page - 1) * $resultsPerPage.','.(int) $resultsPerPage);

            // Count
            $cntResult = Db::getInstance()->executeS($countQuery, true, false);
            $count = is_array($cntResult) ? count($cntResult) : 0;
        }

        $products = Db::getInstance()->executeS($resultsQuery, true, false);

        if ($orderBy == 'price') {
            Tools::orderbyPrice($products, $orderWay);
        }

        // ------------------- RESPONSE -----------------

        $response = [
            'facets' => $facets,
            'sort_orders' => array_values($sortOrders),
            'count' => $count,
            'result' => $products,
        ];

        return $response;
    }

    private function getCategories($selectedFilters = [], $id_parent)
    {
        $data = [];
        $categories = Category::getChildren($id_parent, $this->context->language->id, true, $this->context->shop->id);

        foreach ($categories as $category) {
            $data[$category['id_category']] = [
                'encodedFacet' => $this->composeEncodedFacet($selectedFilters, 'category', $category['id_category']),
                'name' => $category['name'],
                'nbr' => 0,
            ];
        }

        return $data;
    }

    private function getBrandsByFilters($selectedFilters, $id_category)
    {
        $categories = [];
        if (array_key_exists('category', $selectedFilters) && count($selectedFilters['category']) > 0) {
            foreach ($selectedFilters['category'] as $categoryId) {
                $categories[] = (int) $categoryId;
            }
        } else {
            $categories[] = (int) $id_category;
        }

        $query = 'SELECT DISTINCT m.id_manufacturer, m.name FROM '._DB_PREFIX_.'manufacturer m
                  LEFT JOIN '._DB_PREFIX_.'product p ON m.id_manufacturer = p.id_manufacturer 
                  LEFT JOIN '._DB_PREFIX_.'category_product cp ON cp.id_product = p.id_product 
                  WHERE cp.id_category IN ('.implode(',', $categories).') AND m.active = 1 ORDER BY m.name';

        return Db::getInstance()->executeS($query, true, false);
    }

    private function refactorBrands($selectedFilters, $manufacturers)
    {
        $data = [];

        foreach ($manufacturers as $manufacturer) {
            $data[$manufacturer['id_manufacturer']] = [
                'encodedFacet' => $this->composeEncodedFacet($selectedFilters, 'manufacturer', $manufacturer['id_manufacturer']),
                'name' => $manufacturer['name'],
                'nbr' => 0,
            ];
        }

        return $data;
    }

    private function getSelectedFilters()
    {
        $data = [];
        $queryParams = Tools::getValue('q');
        if ($queryParams) {
            $categoryData = [];
            $manufacturerData = [];

            $boom = explode('/', $queryParams);
            foreach ($boom as $item) {
                if (strpos($item, $this->module->l('Categories', 'product16filtercontroller')) !== false) {
                    $categories = explode('-', $item);
                    array_shift($categories);
                    foreach ($categories as $categoryId) {
                        $categoryData[$categoryId] = $categoryId;
                    }
                } elseif (strpos($item, $this->module->l('Manufacturer', 'product16filtercontroller')) !== false) {
                    $brands = explode('-', $item);
                    array_shift($brands);
                    foreach ($brands as $brandId) {
                        $manufacturerData[$brandId] = $brandId;
                    }
                }
            }

            $data = [
                'category' => $categoryData,
                'manufacturer' => $manufacturerData,
            ];
        }

        return $data;
    }

    private function composeEncodedFacet($selectedFilters = [], $type, $name)
    {
        if (count($selectedFilters) > 0) {
            return $this->composeFacet($selectedFilters, $type, $name);
        } else {
            if ($type === 'category') {
                return $this->module->l('Categories', 'product16filtercontroller').'-'.$name;
            } elseif ($type === 'manufacturer') {
                return $this->module->l('Manufacturer', 'product16filtercontroller').'-'.$name;
            }
        }
    }

    private function composeFacet($selectedFilters = [], $type, $name)
    {
        $type_prefix = $type === 'category' ? $this->module->l('Categories', 'product16filtercontroller') : $this->module->l('Manufacturer', 'product16filtercontroller');
        $_type = $type === 'category' ? 'manufacturer' : 'category';
        $_type_prefix = $_type === 'category' ? $this->module->l('Categories', 'product16filtercontroller') : $this->module->l('Manufacturer', 'product16filtercontroller');

        $facet = '';

        if (count($selectedFilters[$_type]) > 0) {
            $facet .= $_type_prefix;
            foreach ($selectedFilters[$_type] as $key => $value) {
                $facet .= '-'.$value;
            }
        }

        if (count($selectedFilters[$type]) > 0) {
            if (Tools::strlen($facet) > 0) {
                $facet .= '/';
            }
            $facet .= $type_prefix;
            foreach ($selectedFilters[$type] as $key => $value) {
                $facet .= '-'.$value;
            }
        }

        if (substr_count($facet, $name) > 0) {
            $facet = str_replace($name, '', $facet);
            $facet = $this->cleanFacet($facet);
        } else {
            if (strpos($facet, $type_prefix) === false) {
                $facet .= '/'.$type_prefix;
            }
            $facet .= '-'.$name;
        }

        if (Tools::substr($facet, -Tools::strlen($type_prefix)) === $type_prefix) {
            $facet = str_replace($type_prefix, '', $facet);
        }

        return $this->cleanFacet($facet);
    }

    private function cleanFacet($string)
    {
        if (Tools::substr($string, -1) === '/') {
            $string = Tools::substr($string, 0, -1);
        }

        if (Tools::substr($string, -1) === '-') {
            $string = Tools::substr($string, 0, -1);
        }

        $string = str_replace('//', '/', $string);
        $string = str_replace('--', '-', $string);

        return $string;
    }
}
