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

namespace MrAPPs\MrShopApi\Api\Front;

use Category;
use Combination;
use Configuration;
use Context;
use Db;
use DbQuery;
use Group;
use Manufacturer;
use Module;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\BookingHandler;
use MrAPPs\MrShopApi\Handler\Search\CategoryProductHandler;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\CustomFormUtils;
use Pack;
use Product;
use Product16FilterController;
use Shop;
use SpecificPrice;
use StockAvailable;
use Tools;
use Validate;

class ProductWS extends ProductBaseWS
{
    private $bookingHandler = null;

    private $fidelityHandler = null;

    private $externalModuleService;

    public function __construct($dataHandler, $useCache = true)
    {
        parent::__construct($dataHandler);
        $this->cacheEnabled = (bool) $useCache;
        $this->cacheAllowedMethods = ['list'];
        $this->bookingHandler = new BookingHandler();
        $this->fidelityHandler = ApiUtils::getFidelityHandler();
        $this->externalModuleService = new ExternalModuleService();

        $this->allowCacheParameters(['filter', 'id_category', 'resultsPerPage', 'home']);
    }

    private function checkCategoryAccess($id_category = null, $id_customer = null)
    {
        $category = new Category((int) $id_category);

        return !Validate::isLoadedObject($category) || $category->checkAccess($id_customer);
    }

    private function getPS17List($id_customer, $id_category, $onlyResult = false)
    {
        $category = new Category((int) $id_category);
        $handler = new CategoryProductHandler($category);

        $searchVariables = $handler->getProductSearchVariablesResults($this->defaultLimit);
        $result = $searchVariables['result'];
        $selectedSort = $result->getCurrentSortOrder();

        $facetsData = $result->getFacetCollection() != null ? $result->getFacetCollection()->getFacets() : null;

        $products = $searchVariables['products'];
        if ($onlyResult) {
            return [
                'result' => $this->refactorProductResponse($products, $id_customer),
            ];
        } else {
            $facets = $this->refactorFacets($facetsData);

            $sortsData = $result->getAvailableSortOrders();
            $sortsOrder = $this->refactorSortOrder($sortsData,$selectedSort);

            $response = [
                'facets' => $facets,
                'sort_orders' => $sortsOrder,
                'count' => (int) $result->getTotalProductsCount(),
                'result' => $this->refactorProductResponse($products, $id_customer),
            ];

            if ($id_category) {
                $category = new Category($id_category, $this->context->language->id);
                $image_url = '';
                $categoryDesc = strip_tags(preg_replace('/<br\s?\/?>/i', "\r\n", $category->description), '');
                if ($category->id_image) {
                    $image_url = $this->context->link->getCatImageLink($category->link_rewrite, (int) $category->id_image);
                }

                if (!empty($image_url) || !empty($categoryDesc)) {
                    $response['category'] = [
                        'image_url' => $image_url,
                        'description' => empty($categoryDesc) ? null : $categoryDesc,
                    ];
                }
            }

            return $response;
        }
    }

    private function getPS16List($id_customer, $onlyResult = false)
    {
        $searchController = new Product16FilterController($this->module);
        $response = $searchController->getResponse($onlyResult, $this->defaultLimit);

        $response['result'] = $this->refactorProductResponse($response['result'], $id_customer);

        return $response;
    }

    public function getCategoryProducts($id_category, $params, $id_customer = null, $resultsPerPage = 10)
    {
        try {
            // Categoria non accessibile
            if (!isset($params['fromAdmin']) && !$this->checkCategoryAccess($id_category, $id_customer)) {
                $response = [
                    'facets' => [],
                    'sort_orders' => [],
                    'count' => 0,
                    'result' => [],
                ];
            } else {
                $_GET['id_category'] = $id_category;
                $_GET['resultsPerPage'] = (int) $resultsPerPage;
                $_GET['page'] = 1;

                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                    require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/ProductFilterController.php';
                    $response = $this->getPS17List($id_customer, $id_category);
                } else {
                    require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/Product16FilterController.php';
                    $response = $this->getPS16List($id_customer);
                }
            }

            return $response;
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $this->module->l(
                'There was an error while retrieving data',
                'ApiUtils'
            )];
        }
    }

    public function getList($params, $id_customer = null, $echoResponse = true)
    {
        try {
            if (!isset($params['id_category'])) {
                $params['id_category'] = (int) Configuration::get('PS_HOME_CATEGORY');
            }

            if (!isset($params['id_category']) || (!isset($params['fromAdmin']) && !$this->checkCategoryAccess((int) $params['id_category'], $id_customer))) {
                $response = [
                    'facets' => [],
                    'sort_orders' => [],
                    'count' => 0,
                    'result' => [],
                ];
            } else {
                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                    require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/ProductFilterController.php';
                    $response = $this->getPS17List($id_customer, $params['id_category']);
                } else {
                    require_once _PS_MODULE_DIR_.'mrshopapi/controllers/front/Product16FilterController.php';
                    $response = $this->getPS16List($id_customer);
                }
            }

            return $this->response(true, null, $response, $echoResponse);
        } catch (\Exception $exception) {
            $this->response(false, $this->module->l('There was an error while retrieving data', 'productws'));
        }
    }

    public function getDetail($id, $id_customer = null)
    {
        $filter = Tools::getIsset('filter') ? Tools::getValue('filter') : [];

        if (array_key_exists('id_product_attribute', $filter) && (int) $filter['id_product_attribute'] != 0) {
            $id_product_attribute = (int) $filter['id_product_attribute'];
        } else {
            $id_product_attribute = false;
        }

        $query = $this->composeGetDetailQuery($id, $id_product_attribute);

        $data = $this->refactorData($query);

        if ($data !== false && count($data) > 0) {
            $this->response(true, null, $this->refactorProductDetail($data, $id_product_attribute, $id_customer));
        } else {
            ResponseHandler::badRequest('Product not found');
        }
    }

    public function refactorProductDetail($data, $id_product_attribute, $id_customer = null)
    {
        $attributes = [];

        foreach ($data as $item) {
            if (array_key_exists('id_product_attribute', $item)) {
                $attributes[] = (int) $item['id_product_attribute'];
            }
        }

        $output = $data[0];

        $output['id_product_attribute'] = $id_product_attribute !== false ? $id_product_attribute : null;

        if ($id_customer !== null) {
            if ($this->isModuleActive('mrshopblockwishlist')) {
                $output['wishlist'] = $this->isProductOnWishlist($output['id'], (int) $id_customer);
            }
        }

        if (empty($output['ean'])) {
            $output['ean'] = null;
        }

        if (empty($output['isbn'])) {
            $output['isbn'] = null;
        }

        $product = new Product((int) $output['id']);
        $defaultIdProductAttribute = $product->getDefaultIdProductAttribute();

        $combinationEnabled = Combination::isFeatureActive();

        $minimumQuantity = (int) $product->minimal_quantity;

        if ($combinationEnabled) {
            if ($output['id_product_attribute'] == null) {
                $attributes = $this->getProductAttributeCombination(
                    $defaultIdProductAttribute,
                    $this->context->language->id
                );

                if ($defaultIdProductAttribute > 0) {
                    $output['id_product_attribute'] = $defaultIdProductAttribute;

                    $attributeCombinations = $product->getAttributeCombinations($this->context->language->id);

                    $combination = new Combination($defaultIdProductAttribute);
                    $minimumQuantity = (int) $combination->minimal_quantity;

                    foreach ($attributeCombinations as $attributeCombination) {
                        if (array_key_exists(
                            'id_product_attribute',
                            $attributeCombination
                        ) && (int) $attributeCombination['id_product_attribute'] === (int) $defaultIdProductAttribute) {
                            $output['quantity_available'] = $attributeCombination['quantity'];

                            break;
                        }
                    }
                }
            } else {
                $attributes = $this->getProductAttributeCombination(
                    $output['id_product_attribute'],
                    $this->context->language->id
                );

                $combination = new Combination($output['id_product_attribute']);
                $minimumQuantity = (int) $combination->minimal_quantity;
            }
        }

        $output['attributes_title'] = '';
        $output['attributes_description'] = '';

        if ($attributes) {
            $titles = [];
            $descriptions = [];
            foreach ($attributes as $row) {
                $titles[] = $row['group_name'];
                $descriptions[] = $row['attribute_name'];
                $output['attributes_description'] .= $row['attribute_name'].', ';
            }
            $output['attributes_title'] = implode(" - ", $titles);
            $output['attributes_description'] = implode(", ", $descriptions);
        }

        $output['images'] = $this->loadProductImages($output['id'], $output['id_product_attribute'], $output);

        unset($output['link_rewrite']);

        $output['absolute_url'] = $this->context->link->getProductLink(
            $product,
            null,
            null,
            null,
            null,
            null,
            (int) $output['id_product_attribute']
        );

        $attributes = $this->loadProductAttributes($output['id']);

        $output['attributes'] = $attributes;

        $idLang = (int) Context::getContext()->language->id;
        $data = Product::getAttachmentsStatic($idLang, $output['id']);
        $attachments = [];
        foreach ($data as $attachment) {
            $attachments[] = [
                'id' => $attachment['id_attachment'],
                'name' => $attachment['name'],
                'url' => $this->context->link->getPageLink('attachment', null, $idLang, ['id_attachment' => $attachment['id_attachment']])
            ];
        }
        $output['attachments'] = $attachments;

        unset($output['id_image']);

        $showPrices = false;
        $output['full_price'] = '';
        $output['discounted_price'] = '';
        $output['discounted_price_suffix'] = '';

        $quantityAvailable = (int) $output['quantity_available'];

        if ($quantityAvailable <= 0) {
            $output['stock_quantity'] = $this->module->l('No products in stock', 'productws');
        } elseif ($quantityAvailable == 1) {
            $output['stock_quantity'] = $this->module->l('1 product in stock', 'productws');
        } else {
            $output['stock_quantity'] = sprintf(
                $this->module->l('%s products in stock', 'productws'),
                $quantityAvailable
            );
        }

        if ($product->show_price && ApiUtils::showPricesForCurrentGroup()) {
            $showPrices = true;

            $fullPrice = $this->fullPriceCalculation($item, $id_customer);
            $discountedPrice = $this->discountedPriceCalculation($item, $id_customer);

            if (!$fullPrice) {
                $fullPrice  = '';
            }

            if (!$discountedPrice) {
                $discountedPrice = '-';
            }
            /* Price calculation */
            $output['full_price'] = $this->priceFormatter->format($fullPrice, $this->getCurrencyId());
            $output['discounted_price'] = $this->priceFormatter->format($discountedPrice, $this->getCurrencyId());
            $output['discounted_price_val'] = $this->priceFormatter->getPriceData($discountedPrice);

            $unitPrice = $product->unit_price;
            if (null === $unitPrice) {
                $unitPrice = ($product->unit_price_ratio != 0 ? $product->price / $product->unit_price_ratio : 0);
            }

            $output['unit_price'] = $unitPrice > 0
                ? $this->priceFormatter->format($unitPrice, $this->getCurrencyId()).' '.$product->unity
                : null;

            /*
            *  egbuycentimeter
            */
            $output['unit_increment'] = null;
            if ($this->isModuleActive('egbuycentimeter') && (isset($product->unity) && !empty($product->unity))) {
                $quantities = $this->externalModuleService->egbuycentimeterGetQuantities(
                    $product->id,
                    0,
                    0,
                    $quantityAvailable,
                    $output['unit_increment']
                );
                $prices = $this->externalModuleService->egbuycentimeterCalculatePrices($discountedPrice);

                $output['unit_increment'] = $quantities['unit_increment'];
                $output['unit_price'] = $quantities['unit_price'];
                $output['discounted_price'] = $prices['discounted_price'];
                $output['discounted_price_val'] = $prices['discounted_price_val'];
                $output['full_price'] = $prices['full_price'];
            }

            /** Tax */
            $taxEnabled = (bool) Configuration::get('PS_TAX');
            $displayTaxLabel = $this->context->country != null ? (bool) $this->context->country->display_tax_label : false;

            if ($taxEnabled && $displayTaxLabel) {
                /** 0 tax incl - 1 tax excl */
                $taxDisplayMethod = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                if ($taxDisplayMethod) {
                    $output['discounted_price_suffix'] = '('.$this->module->l('tax excl.', 'productws').')';
                } else {
                    $output['discounted_price_suffix'] = '('.$this->module->l('tax incl.', 'productws').')';
                }
            }

            /** Quantity discounts table */
            $quantityDiscounts = SpecificPrice::getQuantityDiscounts(
                $output['id'],
                $this->context->shop->id,
                $this->getCurrencyId(),
                $this->context->country->id,
                Group::getCurrent()->id,
                $output['id_product_attribute'],
                false,
                $id_customer
            );
            if (count($quantityDiscounts) > 0) {
                $output['quantity_discounts'] = [];

                foreach ($quantityDiscounts as $discount) {
                    $item = [];
                    $item['quantity'] = (int) $discount['from_quantity'];

                    $totalPrice = $item['quantity'] * $fullPrice;
                    $totalPriceDiscounted = 0;

                    if ($discount['reduction_type'] === 'percentage') {
                        $discountValue = ((float) $discount['reduction']) * 100;
                        $item['discount'] = $discountValue.'%';

                        $unitPriceDiscounted = $fullPrice - ($discountValue * $fullPrice / 100);
                        $totalPriceDiscounted = $unitPriceDiscounted * $item['quantity'];
                    } elseif ($discount['reduction_type'] === 'amount') {
                        $discountValue = (float) $discount['reduction'];
                        $item['discount'] = $this->priceFormatter->format($discountValue, $this->getCurrencyId());
                        $item['discount_val'] = $this->priceFormatter->getPriceData($discountValue, $this->getCurrencyId());

                        $unitPriceDiscounted = $fullPrice - $discountValue;
                        $totalPriceDiscounted = $unitPriceDiscounted * $item['quantity'];
                    }

                    $item['save'] = $this->priceFormatter->format(
                        ($totalPrice - $totalPriceDiscounted),
                        $this->getCurrencyId()
                    );

                    $item['save_val'] = $this->priceFormatter->getPriceData(
                        ($totalPrice - $totalPriceDiscounted),
                        $this->getCurrencyId()
                    );

                    $output['quantity_discounts'][] = $item;
                }
            }
        }

        /** Manufacturer */
        $manufacturer = new Manufacturer($product->id_manufacturer);
        $manufacturerName = null;
        $manufacturerImage = null;
        if (Validate::isLoadedObject($manufacturer)) {
            $manufacturerName = $manufacturer->name;
            $manufacturerImage = $this->getManufacturerImageLink(
                $manufacturer->id,
                $this->getImageFormattedName('small')
            );
        }

        $output['manufacturer_name'] = $manufacturerName;
        $output['manufacturer_image'] = $manufacturerImage;

        $apiVersion = $this->dataHandler->getApiVersion();
        $cart = Context::getContext()->cart;
        $quantityAlreadyInCart = $cart->containsProduct((int) $product->id, (int) $output['id_product_attribute']);
        if ($quantityAlreadyInCart !== false) {
            $inCart = (int) $quantityAlreadyInCart['quantity'];
            $newQt = (int) ($minimumQuantity - $inCart);
            if ($newQt <= 0) {
                if ($apiVersion <= 10) {
                    $minimumQuantity = 1;
                } else {
                    $minimumQuantity = $minimumQuantity;
                }
            }

            $quantityAvailable -= $inCart;
        }

        $output['minimum_quantity'] = $minimumQuantity;
        if ($this->isModuleActive('egbuycentimeter') && (isset($product->unity) && !empty($product->unity))) {
            if ($this->dataHandler->getApiVersion() > 11) {
                $quantities = $this->externalModuleService->egbuycentimeterGetQuantities(
                    $product->id,
                    (int) $output['minimum_quantity']
                );
                $output['minimum_quantity'] = $quantities['minimum_quantity'];
            }
        }

        $allowOosp = (bool) Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock($product->id));

        $productArray = (array) $product;

        $output['is_purchasable'] = $showPrices && $this->isPurchasable(
            $productArray,
            $allowOosp,
            $quantityAvailable,
            $minimumQuantity
        );

        if ($this->dataHandler->getApiVersion() <= 10) {
            if ($output['is_purchasable'] && $allowOosp) {
                $output['quantity_available'] = $this->dataHandler->getMaxProductsShown();
            } else {
                $output['quantity_available'] = $quantityAvailable > $this->dataHandler->getMaxProductsShown() ? $this->dataHandler->getMaxProductsShown() : $quantityAvailable;
            }
        } else {
            if ($output['is_purchasable'] && $allowOosp) {
                $output['quantity_available'] = -1;
            } else {
                $output['quantity_available'] = $quantityAvailable;
            }
        }

        if ($showPrices && $this->showMailNotification(
            $productArray,
            $allowOosp,
            $quantityAvailable,
            $minimumQuantity
        )) {
            if ($id_customer != null) {
                $mailNotificationPath = _PS_MODULE_DIR_.'mrshopmailnotifications/MrshopMailNotification.php';

                if (file_exists($mailNotificationPath)) {
                    require_once $mailNotificationPath;
                }

                $output['mailnotification_requested'] = (bool) call_user_func(
                    'MrshopMailNotification::customerHasNotification',
                    $id_customer,
                    $product->id,
                    $output['id_product_attribute'],
                    $this->context->shop->id,
                    $this->context->language->id
                );
            } else {
                $output['mailnotification_requested'] = false;
            }

            $output['show_mail_notification'] = true;
        } else {
            $output['show_mail_notification'] = false;
        }

        $availabilityInfo = $this->getAvailabilityInfo($productArray, $allowOosp, $quantityAvailable, $minimumQuantity);
        $output += $availabilityInfo;

        $features = Product::getFrontFeaturesStatic((int) $this->context->language->id, (int) $product->id);

        foreach ($features as &$feature) {
            $idFeature = $feature['id_feature'];

            $feature['id'] = (int) $idFeature;
            unset($feature['id_feature']);

            $feature['position'] = isset($feature['position']) ? (int) $feature['position'] : 0;
        }

        $output['features'] = $features;

        /* Pack */
        if (Pack::isPack($output['id'])) {
            $output['pack_products'] = [];

            $packedProducts = self::getItems($output['id'], (int) $this->context->language->id);

            foreach ($packedProducts as $product) {
                $item = [];

                $tmpProduct = (array) $product;

                $item['id'] = (int) $tmpProduct['id'];
                $item['id_product_attribute'] = (int) $tmpProduct['id_pack_product_attribute'];
                $item['name'] = $tmpProduct['name'];
                $item['quantity'] = (int) $tmpProduct['pack_quantity'];
                $item['attributes'] = isset($tmpProduct['combinations']) ? $tmpProduct['combinations'] : [];

                $idProduct = (int) $item['id'];
                $productImage = ProductBaseWS::getCoverImages($idProduct);
                $item['image'] = $productImage['image'];

                $output['pack_products'][] = $item;
            }
        }

        /*
         *  EUROARTIGIANA
         */
        if ($this->isModuleActive('euroartigiana')) {
            $onlyQuotes = $this->getQuoteFields($output['id']);
            // Euroartigiana - quote management
            $output['quote_fields'] = $onlyQuotes;

            $isOnlyQuote = count($onlyQuotes) > 0;
            $output['only_quote'] = $isOnlyQuote;

            if ($isOnlyQuote) {
                $output['stock_quantity'] = '';
            }
        }

        /*
         *  PSBOOKING
         *  Add booking data
         */
        if ($this->bookingHandler->isModuleActive()) {
            $bookingData = $this->bookingHandler->getBookingData($product->id);
            if (!empty($bookingData)) {
                $output['booking'] = $bookingData;
                if (isset($bookingData['quantity_available'])) {
                    $quantityAvailable = $bookingData['quantity_available'];
                    $output['quantity_available'] = $quantityAvailable;
                    if ($quantityAvailable <= 0) {
                        $output['stock_quantity'] = $this->module->l('No products in stock', 'productws');
                        $output['is_purchasable'] = false;
                    } elseif ($quantityAvailable == 1) {
                        $output['stock_quantity'] = $this->module->l('1 product in stock', 'productws');
                    } else {
                        $output['stock_quantity'] = sprintf(
                            $this->module->l('%s products in stock', 'productws'),
                            $quantityAvailable
                        );
                    }
                    $availabilityInfo = $this->getAvailabilityInfo(
                        $productArray,
                        false,
                        $quantityAvailable,
                        $output['minimum_quantity']
                    );
                    $output = array_merge($output, $availabilityInfo);
                }
            }
        }

        /* Descriptions refactoring * */
        $output['description'] = ApiUtils::replaceUnsupportedTextViewTag($output['description']);
        $output['description_short'] = ApiUtils::replaceUnsupportedTextViewTag($output['description_short']);

        /*
         *  AIO_REWARDS
         *  Add fidelity data
         */
        if ($this->fidelityHandler->isModuleActive()) {
            $fidelityData = $this->fidelityHandler->getProductFidelityData($product->id, $id_product_attribute);
            if (!empty($fidelityData)) {
                $output['fidelity'] = $fidelityData;
            }
        }

        /*
         * MINPURCHASE
         * Override quantities and set unit increment
         */
        if ($this->dataHandler->isModuleActive('minpurchase')) {
            $quantities = $this->externalModuleService->minpurchaseGetQuantities($product->id);
            foreach ($quantities as $key => $val) {
                if ($val != null) {
                    $output[$key] = $val;
                }
            }
        }

        /* Customization */
        $output['customization_fields'] = [];
        if (!empty($output['customizable'] && (int) $output['customizable'] > 0)) {
            $output['customization_fields'] = $this->getProductCustomizationFields($output['id']);
        }

        /* Product reviews */
        $output['product_reviews'] = null;
        if ($this->dataHandler->isModuleActive('productcomments')) {
            try {
                $criterions = $this->externalModuleService->productcommentsGetCriteria($product);
                $averageGrade = $this->externalModuleService->productcommentsGetAverageGrade($product);
            } catch (\Exception $exception) {
                return $output;
            }

            $exclude = [];

            if ($id_customer != null) {
                $exclude[] = 'customer_name';
            }

            $output['product_reviews'] = [
                'allow_guests' => (bool) Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS'),
                'comments_anonymisation' => (bool) Configuration::get('PRODUCT_COMMENTS_ANONYMISATION'),
                'allow_votes' => (bool) Configuration::get('PRODUCT_COMMENTS_USEFULNESS'),
                'averageRating' => $averageGrade['averageRating'],
                'nbComments' => $averageGrade['nbComments'],
                'fields' => CustomFormUtils::getProductReviewFields($criterions, $exclude)
            ];
        }

        return $output;
    }

    // Euroartigiana - quote management
    protected function getQuoteFields($id_product)
    {
        $module = Module::getInstanceByName('euroartigiana');
        if (Validate::isLoadedObject($module) && $module->isOnlyQuote($id_product)) {
            return $module->getQuoteFields();
        }

        return [];
    }

    public static function getItems($id_product, $id_lang)
    {
        if (!Pack::isFeatureActive()) {
            return [];
        }

        $result = Db::getInstance()->executeS('SELECT id_product_item, id_product_attribute_item, quantity FROM `'._DB_PREFIX_.'pack` where id_product_pack = '.(int) $id_product);
        $array_result = [];
        foreach ($result as $row) {
            $p = new Product($row['id_product_item'], false, $id_lang);
            $p->loadStockData();
            $p->pack_quantity = $row['quantity'];
            $p->id_pack_product_attribute = (isset($row['id_product_attribute_item']) && $row['id_product_attribute_item'] ? $row['id_product_attribute_item'] : 0);
            if (isset($row['id_product_attribute_item']) && $row['id_product_attribute_item']) {
                $sql = 'SELECT agl.`name` AS group_name, al.`name` AS attribute_name
					FROM `'._DB_PREFIX_.'product_attribute` pa
					'.Shop::addSqlAssociation('product_attribute', 'pa').'
					LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
					LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
					LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
					LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int) Context::getContext()->language->id.')
					LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int) Context::getContext()->language->id.')
					WHERE pa.`id_product_attribute` = '.(int) $row['id_product_attribute_item'].'
					GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
					ORDER BY pa.`id_product_attribute`';

                $combinations = Db::getInstance()->executeS($sql);
                $attributeNames = '';
                foreach ($combinations as $k => $combination) {
                    $attributeNames .= $combination['attribute_name'];
                    if ($k < (count($combinations) - 1)) {
                        $attributeNames .= ', ';
                    }
                }

                $p->combinations = $attributeNames;
            } else {
                $p->combinations = '';
            }
            $array_result[] = $p;
        }

        return $array_result;
    }

    private function composeGetDetailQuery($id, $id_product_attribute = false): DbQuery
    {
        // $query =
        //     'SELECT DISTINCT
        //           a.id_product AS id,
        //           a.ean13 AS ean,' .
        //     (version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 'a.isbn' : 'NULL') . ' AS isbn, ' .
        //     'a.on_sale,
        //           a.id_category_default,
        //           cl.name AS category_name,
        //           al.name, al.description, al.description_short, al.link_rewrite,
        //           IFNULL(st.quantity, 0) AS quantity_available,
        //           a.reference,
        //           a.id_manufacturer' . ($id_product_attribute !== false ? ',pa.id_product_attribute' : '') . ',
        //           a.customizable' .
        //     ' FROM `' . _DB_PREFIX_ . 'product` a
        //           LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` al ON a.id_product = al.id_product
        //           LEFT JOIN  `' . _DB_PREFIX_ . 'category_product` cp ON a.id_product = cp.id_product
        //           LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON a.id_category_default = cl.id_category' . '
        //           LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` st ON a.id_product = st.id_product' .
        //     ($id_product_attribute !== false ? ' INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON a.id_product = pa.id_product' : '') .
        //     ' WHERE a.id_product = ' . (int) $id . ' AND a.active = 1 ' . ($id_product_attribute !== false ? ' AND st.id_product_attribute = ' . (int) $id_product_attribute .
        //         ' AND pa.id_product_attribute = ' . (int) $id_product_attribute : ' AND st.id_product_attribute = 0 ')
        //     . ' AND al.id_lang = ' . (int) Context::getContext()->language->id . ' AND st.id_shop = ' . (int) Context::getContext()->shop->id;

        $select = 'DISTINCT a.id_product AS id,
                    a.ean13 AS ean,'.
                    (version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 'a.isbn' : 'NULL').' AS isbn, '.
                    'a.on_sale,
                    a.id_category_default,
                    cl.name AS category_name,
                    al.name, al.description, al.description_short, al.link_rewrite,
                    IFNULL(st.quantity, 0) AS quantity_available,
                    a.reference,
                    a.id_manufacturer'.($id_product_attribute !== false ? ',pa.id_product_attribute' : '').',
                    a.customizable';

        $query = new DbQuery();
        $query
            ->select($select)
            ->from('product', 'a')
            ->leftJoin('product_lang', 'al', 'a.id_product = al.id_product')
            ->leftJoin('category_product', 'cp', 'a.id_product = cp.id_product')
            ->leftJoin('category_lang', 'cl', 'a.id_category_default = cl.id_category')
            ->leftJoin('stock_available', 'st', 'a.id_product = st.id_product')
            ->where('a.id_product = '.(int) $id)
            ->where('a.active = 1')
            ->where('al.id_lang = '.(int) Context::getContext()->language->id);

        if ($id_product_attribute !== false) {
            $query
                ->innerJoin('product_attribute', 'pa', 'a.id_product = pa.id_product')
                ->where('st.id_product_attribute = '.(int) $id_product_attribute)
                ->where('pa.id_product_attribute = '.(int) $id_product_attribute);
        } else {
            $query->where('st.id_product_attribute = 0');
        }

        $query = StockAvailable::addSqlShopRestriction($query, (int) Context::getContext()->shop->id, 'st');

        return $query;
    }
}
