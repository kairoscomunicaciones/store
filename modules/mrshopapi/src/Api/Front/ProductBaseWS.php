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

use Address;
use Category;
use Combination;
use Configuration;
use Context;
use Customization;
use CustomizationField;
use Db;
use Manufacturer;
use Module;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\CustomizationHandler;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Product;
use Shop;
use StockAvailable;
use Tools;
use Validate;

class ProductBaseWS extends BaseWS
{
    protected $settings;

    protected $cachedWishlistProducts;

    private $externalModuleService;

    private $customizationHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->cacheTime = 60;
        $this->cacheUserGroups = true;
        $this->cachedWishlistProducts = null;
        $this->externalModuleService = new ExternalModuleService();
        $this->customizationHandler = new CustomizationHandler();

        $this->allowCacheParameters(['limit', 'page', 'order', 'q']);

        if ($this->isModuleActive('mrshopblockwishlist')) {
            require_once _PS_MODULE_DIR_.'mrshopblockwishlist/classes/MrShopWishList.php';
        }

        $this->settings = [
            'catalog_mode' => ApiUtils::isCatalogMode(),
            'stock_management_enabled' => Configuration::get('PS_STOCK_MANAGEMENT'),
            'show_prices' => ApiUtils::showPricesForCurrentGroup(),
            'last_remaining_items' => Configuration::get('PS_LAST_QTIES'),
        ];

        $this->setGETParamsIfNeeded();

        if (Tools::getIsset('limit')) {
            $limit = (int) Tools::getValue('limit');
            $this->defaultLimit = $limit ?: $this->defaultLimit;
        }
    }

    private function setGETParamsIfNeeded()
    {
        if (Module::isEnabled('nrtthemecustomizer')) {
            $themeParams = json_decode(Configuration::get('opThemect'), true);

            if ((int) $themeParams['category_product_infinite'] == 1 || (int) $themeParams['category_product_infinite'] == 2) {
                if (!Tools::getIsset('infinite')) {
                    $_GET['infinite'] = 1;
                }

                if (!Tools::getIsset('from-xhr')) {
                    $_GET['from-xhr'] = 1;
                }
            }
        }
    }

    public function customResponse(&$cachedResponse, $id_customer = null)
    {
        if (is_array($cachedResponse) && array_key_exists('result', $cachedResponse)) {
            $result = &$cachedResponse['result'];
            $showWishlist = $this->isModuleActive('mrshopblockwishlist') && $id_customer !== null;

            foreach ($result as &$item) {
                if ($showWishlist) {
                    $item['wishlist'] = $this->isProductOnWishlist((int) $item['id'], $id_customer);
                }

                $product = new Product((int) $item['id']);

                if (!($this->settings['show_prices'] && $product->show_price)) {
                    $item['full_price'] = '';
                    $item['discounted_price'] = '';
                }
            }
        }
    }

    private function hasAttributes($productId)
    {
        if (!Combination::isFeatureActive()) {
            return false;
        }

        $count = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            '
            SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'product_attribute` pa
            '.Shop::addSqlAssociation('product_attribute', 'pa').'
            WHERE pa.`id_product` = '.(int) $productId
        );

        return !!$count;
    }

    public static function getCoverImages($productId)
    {
        $result = Product::getCover($productId);
        if ($result && isset($result['id_image'])) {
            $imageId = $result['id_image'];

            return [
                'id_default_image' => $imageId,
                'image' => self::getProductImageUrl(['id_default_image' => $imageId], null, BaseWS::getImageFormattedName('medium')),
                'image_large' => self::getProductImageUrl(['id_default_image' => $imageId], null, BaseWS::getImageFormattedName('large'))
            ];
        } else {
            return [
                'id_default_image' => null,
                'image' => null,
                'image_large' => null
            ];
        }
    }

    public function refactorFacets($facetsData)
    {
        $facets = [];

        if ($facetsData != null) {
            foreach ($facetsData as $facet) {
                $refactoredFacet = $this->refactorFacet($facet);

                if ($refactoredFacet != null) {
                    $facets[] = $refactoredFacet;
                }
            }
        }

        return $facets;
    }

    /**
     *
     * @return array
     */
    public function refactorSortOrder($sortsData, $selectedSort)
    {
        $sortsOrder = [];
        if ($sortsData != null) {
            $encodedSelectedSort = $selectedSort->getEntity().'.'.$selectedSort->getField().'.'.$selectedSort->getDirection();
            foreach ($sortsData as $sortData) {
                $encodedOrder = $sortData->getEntity().'.'.$sortData->getField().'.'.$sortData->getDirection();
                $selected = $encodedOrder == $encodedSelectedSort;

                $sortsOrder[] = [
                    'label' => $sortData->getLabel(),
                    'encoded_order' => $encodedOrder,
                    'selected' => $selected,
                ];
            }
        }

        return $sortsOrder;
    }

    /** @var \PrestaShop\PrestaShop\Core\Product\Search\Facet $facet */
    private function refactorFacet($facet)
    {
        $filters = [];

        /** @var \PrestaShop\PrestaShop\Core\Product\Search\Filter $filter */
        foreach ($facet->getFilters() as $filter) {
            if (
                Configuration::get('PS_CATALOG_MODE') && !Configuration::get('PS_CATALOG_MODE_WITH_PRICES') &&
                $filter->getType() == 'price'
            ) {
                continue;
            }

            if (ApiUtils::isArray($filter->getValue())) {
                $value = '';

                foreach ($filter->getValue() as $item) {
                    $value .= $item.'-';
                }
            } else {
                $value = $filter->getValue();
            }

            $isActive = $filter->isActive();

            switch ($filter->getType()) {
                case 'price':
                    $symbol = $facet->getProperty('unit');
                    $base = $facet->getLabel().'-'.urlencode($symbol);
                    $encodedFacets = $base.'-{min}-{max}';
                    $min = $facet->getProperty('min');
                    $max = $facet->getProperty('max');

                    if ($max > 0) {
                        $selectedFacets = (string) $filter->getNextEncodedFacets();
                        if (empty($selectedFacets)) {
                            $selectedFacets = $encodedFacets;
                        } else {
                            $selectedFacets = urlencode($selectedFacets).'/'.$encodedFacets;
                        }

                        $values = $filter->getValue();
                        if (empty($values)) {
                            $currentMin = $min;
                            $currentMax = $max;
                        } else {
                            $currentMin = (float) $values[0];
                            $currentMax = (float) $values[1];

                            if ($currentMin < $min) {
                                $currentMin = $min;
                            }

                            if ($currentMax > $max) {
                                $currentMax = $max;
                            }
                        }

                        $filters[] = [
                            'id' => 'price',
                            'label' => $filter->getLabel(),
                            'products_quantity' => null,
                            'symbol' => '€',
                            'min' => $min,
                            'max' => $max,
                            'current_min' => $currentMin,
                            'current_max' => $currentMax,
                            'encoded_facets' => $selectedFacets
                        ];
                    }

                    break;
                default:
                    $products_quantity = (int) $filter->getMagnitude();
                    if ($products_quantity <= 0) {
                        continue;
                    }

                    $encoded_facets = urlencode($filter->getNextEncodedFacets());
                    $filters[] = [
                        'id' => $filter->getLabel().'-'.$value,
                        'label' => $filter->getLabel(),
                        'products_quantity' => $products_quantity,
                        'selected' => !empty($isActive),
                        'encoded_facets' => $encoded_facets,
                    ];

                    break;
            }
        }

        if (count($filters) > 0) {
            return [
                'id' => $facet->getLabel(),
                'label' => $facet->getLabel(),
                'type' => $facet->getType(),
                'input_type' => $facet->getWidgetType(),
                'filters' => $filters,
            ];
        } else {
            return null;
        }
    }

    public function refactorProductResponse($products, $id_customer = null)
    {
        $output = [];

        $showWishlist = $id_customer !== null && $this->isModuleActive('mrshopblockwishlist');

        if ($products != null) {
            foreach ($products as $item) {
                $idProduct = (int) $item['id_product'];
                $product = new Product($idProduct);
                $showAddToCart = $item['show_price'];

                $categoryName = null;

                if (isset($item['category_name'])) {
                    $categoryName = $item['category_name'];
                } else {
                    $category = new Category($item['id_category_default']);
                    $categoryName = $category->getName(Context::getContext()->language->id);
                }

                $manufacturer = new Manufacturer($item['id_manufacturer']);

                $manufacturerName = null;
                if (Validate::isLoadedObject($manufacturer)) {
                    $manufacturerName = $manufacturer->name;
                }

                if ($this->customizationHandler->hasCustomization($product)) {
                    $showAddToCart = false;
                }

                if ((int) $item['quantity'] == 0) {
                    $showAddToCart = false;
                }

                $product_reviews = null;
                if ($this->dataHandler->isModuleActive('productcomments')) {
                    try {
                        $averageGrade = $this->externalModuleService->productcommentsGetAverageGrade($product);
                    } catch (\Exception $exception) {
                        $averageGrade = null;
                    }

                    if ($averageGrade) {
                        $exclude = [];

                        if ($id_customer != null) {
                            $exclude[] = 'customer_name';
                        }

                        $product_reviews = [
                            'allow_guests' => (bool) Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS'),
                            'comments_anonymisation' => (bool) Configuration::get('PRODUCT_COMMENTS_ANONYMISATION'),
                            'allow_votes' => (bool) Configuration::get('PRODUCT_COMMENTS_USEFULNESS'),
                            'averageRating' => $averageGrade['averageRating'],
                            'nbComments' => $averageGrade['nbComments']
                        ];
                    }
                }

                $data = [
                    'id' => $idProduct,
                    'id_category_default' => $item['id_category_default'],
                    'category_name' => isset($categoryName) && !empty($categoryName) ? $categoryName : '',
                    'name' => $item['name'],
                    'link_rewrite' => $item['link_rewrite'],
                    'quantity_available' => (int) $item['quantity'],
                    'reference' => $item['reference'],
                    'on_sale' => $item['on_sale'],
                    'id_manufacturer' => $item['id_manufacturer'],
                    'manufacturer_name' => $manufacturerName,
                    'show_price' => $item['show_price'],
                    'show_add_to_cart' => $showAddToCart,
                    'product_reviews' => $product_reviews
                ];

                $data = array_merge($data, ProductBaseWS::getCoverImages($idProduct));

                $output[] = $this->refactorProduct($data, $id_customer, $showWishlist);
            }
        }

        return $output;
    }

    public function getCustomizationFieldIds($productId)
    {
        if (!Customization::isFeatureActive()) {
            return [];
        }

        $results = Db::getInstance()->executeS(
            '
            SELECT `id_customization_field`
            FROM `'._DB_PREFIX_.'customization_field`
            WHERE `id_product` = '.(int) $productId.(version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? ' AND is_deleted = 0' : "")
        );

        return array_map(function ($result) {
            return (int) $result['id_customization_field'];
        }, $results);
    }

    public function getProductCustomizationFields($productId)
    {
        $customizationFieldIds = $this->getCustomizationFieldIds($productId);
        $output = [];

        foreach ($customizationFieldIds as $fieldId) {
            $customizationField = new CustomizationField((int) $fieldId);

            $type = $customizationField->type == Product::CUSTOMIZE_FILE ? 'file' : 'text';
            $customizationName = $customizationField->name[$this->context->language->id];
            if (!$customizationName) {
                switch ($type) {
                    case "file":
                        $customizationName = $this->module->l('Choose your image', 'productbasews');

                        break;
                    case "text":
                        $customizationName = $this->module->l('Insert your custom text', 'productbasews');

                        break;
                }
            }
            $id = $type.'_'.$customizationField->id;
            $output[] = [
                'id' => $id,
                'type' => $type,
                'name' => $customizationName,
                'required' => (bool) $customizationField->required
            ];
        }

        return $output;
    }

    public function refactorProduct($item, $id_customer = null, $showWishlist = true)
    {
        $item = ApiUtils::refactorRow($item, $this->dataHandler->getApiVersion());

        $productId = (int) $item['id'];
        $item['id'] = $productId;
        $product = new Product($productId);

        if (!array_key_exists('image', $item)) {
            $item['image'] = BaseWS::getProductImageUrl($item, null, BaseWS::getImageFormattedName('medium'));
            $item['image_large'] = BaseWS::getProductImageUrl($item, null, BaseWS::getImageFormattedName('large'));
        }

        if ($item['show_price'] && $this->settings['show_prices']) {
            $fullPrice = $this->fullPriceCalculation($item, $id_customer);
            $discountedPrice = $this->discountedPriceCalculation($item, $id_customer);

            if (!$fullPrice) {
                $fullPrice  = '';
            }

            if (!$discountedPrice) {
                $discountedPrice = '-';
            }

            /* Price calculation */
            $item['full_price'] = $this->priceFormatter->format($fullPrice, $this->getCurrencyId());
            $item['discounted_price'] = $this->priceFormatter->format($discountedPrice, $this->getCurrencyId());
            $item['discounted_price_val'] = $this->priceFormatter->getPriceData($discountedPrice, $this->getCurrencyId());

            /*
            *  egbuycentimeter
            */
            if ($this->isModuleActive('egbuycentimeter') && (isset($product->unity) && !empty($product->unity))) {
                $prices = $this->externalModuleService->egbuycentimeterCalculatePrices($discountedPrice);
                $item['discounted_price'] = $prices['discounted_price'];
                $item['discounted_price_val'] = $prices['discounted_price_val'];
                $item['full_price'] = $prices['full_price'];
            }
        } else {
            $item['full_price'] = '';
            $item['discounted_price'] = '';
        }

        unset($item['show_price']);

        if ($showWishlist) {
            $item['wishlist'] = $this->isProductOnWishlist((int) $item['id'], $id_customer);
        }

        if ($this->dataHandler->getApiVersion() <= 10) {
            $item['quantity_available'] = (int) $item['quantity_available'] > $this->dataHandler->getMaxProductsShown() ? $this->dataHandler->getMaxProductsShown() : $item['quantity_available'];
        }

        $item['has_attributes'] = $this->hasAttributes($productId);

        if ($item['has_attributes']) {
            $minimumQuantity = 0;
        } else {
            $minimumQuantity = (int) $product->minimal_quantity;
        }

        $item['minimum_quantity'] = $minimumQuantity;

        if (array_key_exists('ean', $item) && empty($item['ean'])) {
            $item['ean'] = null;
        }

        unset($item['id_default_image']);

        $allowOosp = (bool) Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock($item['id']));

        $item['availability_color'] = $this->getAvailabilityInfo($item, $allowOosp, $item['quantity_available'], 1, true);

        return $item;
    }

    public function fullPriceCalculation($item, $id_customer = null)
    {
        $product = new Product($item['id']);

        return $product->getPriceWithoutReduct(
            !ApiUtils::includeTaxes($id_customer),
            array_key_exists('id_product_attribute', $item) ? $item['id_product_attribute'] : null,
            2
        );
    }

    public function discountedPriceCalculation($item, $id_customer = null)
    {
        $specificPrice = null;

        return Product::getPriceStatic(
            $item['id'],
            ApiUtils::includeTaxes($id_customer),
            array_key_exists('id_product_attribute', $item) ? $item['id_product_attribute'] : null,
            2,
            null,
            null,
            true,
            1,
            false,
            $id_customer,
            null,
            null,
            $specificPrice,
            true,
            true,
            null,
            true,
            null
        );
    }

    protected function isProductOnWishlist($id_product, $id_customer)
    {
        if ($id_customer !== null && $this->isModuleActive('mrshopblockwishlist')) {
            $products = [];

            if ($this->cachedWishlistProducts) {
                $products = $this->cachedWishlistProducts;
            } else {
                $wishlist = call_user_func('MrShopWishList::getDefault', $id_customer);

                if (!empty($wishlist)) {
                    $id_wishlist = $wishlist[0]['id_wishlist'];
                    $products = call_user_func(
                        'MrShopWishList::getProductByIdCustomer',
                        (int) $id_wishlist,
                        (int) $id_customer,
                        Context::getContext()->language->id
                    );
                    $this->cachedWishlistProducts = $products;
                }
            }

            if (count($products) > 0) {
                foreach ($products as $product) {
                    if ((int) $product['id_product'] === (int) $id_product) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    protected function isPurchasable(array $product, $allowOop, $quantityAvailable, $minimumQuantity)
    {
        $shouldEnable = !$this->settings['catalog_mode'] && (bool) $product['available_for_order'];

        if (
            $shouldEnable
            && $this->settings['stock_management_enabled']
            && !$allowOop
            && ($quantityAvailable <= 0
                || $quantityAvailable - $minimumQuantity < 0)
        ) {
            $shouldEnable = false;
        }

        /*
         * RpBlockByCountry
         * If cart shipping address check if product is allowed for country
         */
        if ($shouldEnable && Module::isEnabled('rpblockbycountry')) {
            $cartHandler = new CartHandler($this->dataHandler->getGuestId());
            $cart = $cartHandler->getCart(Context::getContext()->customer->id);
            $shippingAddress = new Address((int) $cart->id_address_delivery);
            if ($shippingAddress) {
                $product = (array) new Product($product['id'], false, Context::getContext()->language->id);
                $product['id_product'] = $product['id'];
                $response = $cartHandler->areProductsAllowedForCountry([$product], '');
                $shouldEnable = $response['success'];
            }
        }

        return $shouldEnable;
    }

    protected function showMailNotification(array $product, $allowOop, $quantityAvailable, $minimumQuantity)
    {
        return !$this->settings['catalog_mode']
            && $this->isModuleActive('mrshopmailnotifications')
            && (bool) $product['available_for_order']
            && !$allowOop
            && $quantityAvailable < $minimumQuantity;
    }

    public function getManufacturerImageLink($idManufacturer, $type = null)
    {
        $idManufacturer = (int) $idManufacturer;

        if (file_exists(_PS_MANU_IMG_DIR_.$idManufacturer.(empty($type) ? '.jpg' : '-'.$type.'.jpg'))) {
            $uriPath = _THEME_MANU_DIR_.$idManufacturer.(empty($type) ? '.jpg' : '-'.$type.'.jpg');
        } elseif (!empty($type) && file_exists(_PS_MANU_IMG_DIR_.$idManufacturer.'.jpg')) { // !empty($type) because if is empty, is already tested
            $uriPath = _THEME_MANU_DIR_.$idManufacturer.'.jpg';
        } elseif (file_exists(_PS_MANU_IMG_DIR_.$this->context->language->iso_code.(empty($type) ? '.jpg' : '-default-'.$type.'.jpg'))) {
            $uriPath = _THEME_MANU_DIR_.$this->context->language->iso_code.(empty($type) ? '.jpg' : '-default-'.$type.'.jpg');
        } else {
            $uriPath = _THEME_MANU_DIR_.$this->context->language->iso_code.'.jpg';
        }

        return $this->context->link->protocol_content.Tools::getMediaServer($uriPath).$uriPath;
    }

    public function getProductAttributeCombination($id_product_attribute, $id_lang)
    {
        $sql = '
			SELECT al.`name` AS attribute_name, agl.`public_name` AS group_name
			FROM `'._DB_PREFIX_.'product_attribute_combination` pac
			LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
			LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
			LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` 
			AND al.`id_lang` = '.(int) $id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` 
			AND agl.`id_lang` = '.(int) $id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
			'.Shop::addSqlAssociation('product_attribute', 'pa').'
			WHERE pac.`id_product_attribute` = '.(int) $id_product_attribute;

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    private function containsImage($images, $currentImage)
    {
        foreach ($images as $filteredImage) {
            if ((int) $filteredImage['id_image'] == (int) $currentImage['id_image']) {
                return true;
            }
        }

        return false;
    }

    private function mergeImageData($image, $productData)
    {
        return array_merge(
            $image,
            [
                'image_large' => BaseWS::getProductImageUrl(
                    $productData,
                    $image['id_image'],
                    BaseWS::getImageFormattedName('large')
                ),
                'image_medium' => BaseWS::getProductImageUrl(
                    $productData,
                    $image['id_image'],
                    BaseWS::getImageFormattedName('medium')
                )
            ]
        );
    }

    public function loadProductImages($id_product, $id_product_attribute, $productData)
    {
        $query = '
        SELECT DISTINCT
         a.id_image, 
         IFNULL(image_shop.cover, 0) AS cover, 
         pai.id_product_attribute
        FROM `'._DB_PREFIX_.'image` a 
        '.Shop::addSqlAssociation('image', 'a').'
         INNER JOIN `'._DB_PREFIX_.'image_lang` al
            ON (a.`id_image` = al.`id_image` AND al.id_lang = '.(int) Context::getContext()->language->id.')
        LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai
            ON (a.id_image = pai.id_image)
        WHERE a.id_product = '.(int) $id_product
            .' ORDER BY position';

        $images = $this->refactorData($query);

        $filteredImages = [];
        $filteredCombinationImages = [];

        $hasCombinations = $id_product_attribute !== false;

        foreach ($images as $image) {
            $image['cover'] = (int) $image['cover'] > 0;
            $isCombination = $hasCombinations && (int) $image['id_product_attribute'] == (int) $id_product_attribute;

            if ($isCombination) {
                if (!$this->containsImage($filteredCombinationImages, $image)) {
                    $filteredCombinationImages[] = $this->mergeImageData($image, $productData);
                }
            } else {
                if (!$this->containsImage($filteredImages, $image)) {
                    $filteredImages[] = $this->mergeImageData($image, $productData);
                }
            }
        }

        if (count($filteredCombinationImages) > 0) {
            return $filteredCombinationImages;
        }

        if (count($filteredImages) == 0) {
            $filteredImages[] = [
                'id_image' => 0,
                'image_large' => BaseWS::getProductImageUrl(
                    [],
                    null,
                    BaseWS::getImageFormattedName('large')
                ),
                'image_medium' => BaseWS::getProductImageUrl(
                    [],
                    null,
                    BaseWS::getImageFormattedName('medium')
                ),
            ];
        }

        return $filteredImages;
    }

    public function loadProductAttributes($id_product)
    {
        $query = '
        SELECT DISTINCT
         a.id_attribute, 
         a.id_attribute_group,
         IFNULL(pa.default_on,0) AS default_on,
         a.color,
         al.name as attribute, 
         agl.public_name as group_name,
         pa.id_product_attribute
        FROM `'._DB_PREFIX_.'attribute` a
        LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al
            ON (a.`id_attribute` = al.`id_attribute` AND al.id_lang = '.(int) Context::getContext()->language->id.')
        LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl
            ON (a.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.(int) Context::getContext()->language->id.')
        LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac
            ON (a.id_attribute = pac.id_attribute)
        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
            ON (pac.id_product_attribute = pa.id_product_attribute)
          '.Shop::addSqlAssociation('product_attribute', 'pa').'
         WHERE pa.id_product = '.(int) $id_product;

        $attributes = $this->refactorData($query);

        $groups = [];
        foreach ($attributes as $attribute) {
            $founded = false;
            foreach ($groups as &$group) {
                if ($group['id_group'] == $attribute['id_attribute_group']) {
                    $item = [
                        'id' => $attribute['id_attribute'],
                        'name' => $attribute['attribute'],
                        'id_product_attribute' => $attribute['id_product_attribute'],
                        'is_default' => $attribute['default_on'] > 0,
                    ];

                    if ($attribute['color'] !== '' && $attribute['color'] !== null) {
                        $item['color'] = $attribute['color'];
                    }

                    $group['attributes'][] = $item;

                    $founded = true;

                    break;
                }
            }

            if (!$founded) {
                $item = [
                    'id' => $attribute['id_attribute'],
                    'name' => $attribute['attribute'],
                    'id_product_attribute' => $attribute['id_product_attribute'],
                    'is_default' => $attribute['default_on'] > 0,
                ];

                if ($attribute['color'] !== '' && $attribute['color'] !== null) {
                    $item['color'] = $attribute['color'];
                }

                $groups[] = [
                    'id_group' => $attribute['id_attribute_group'],
                    'name' => $attribute['group_name'],
                    'attributes' => [$item],
                ];
            }
        }

        return $groups;
    }

    protected function getAvailabilityInfo(array $product, $allowOosp, $quantityAvailable, $minimumQuantity, $onlyColor = false)
    {
        $data = [
            'availability_title' => null,
            'availability_date' => null,
            'availability' => null,
            'availability_color' => '#7ed322',
        ];

        $translations = [
            'available' => $this->module->l('Available', 'productbasews'),
            'not_available' => $this->module->l('Not available', 'productbasews'),
            'last_items_stock' => $this->module->l('Last items in stock', 'productbasews'),
            'not_enough_items' => $this->module->l('There are not enough products in stock', 'productbasews'),
            'product_different_options' => $this->module->l('Product available with different options', 'productbasews'),
        ];

        $showPrice = $this->settings['show_prices'];
        $showAvailability = $showPrice && $this->settings['stock_management_enabled'];

        if (!array_key_exists('available_date', $product) || '0000-00-00' == $product['available_date']) {
            $product['available_date'] = null;
        }

        if (!array_key_exists('available_now', $product) || '0000-00-00' == $product['available_now']) {
            $product['available_now'] = null;
        }

        if (!array_key_exists('available_later', $product) || '0000-00-00' == $product['available_later']) {
            $product['available_later'] = null;
        }

        if ($showAvailability) {
            if ($quantityAvailable - $minimumQuantity >= 0) {
                $data['availability_date'] = $product['available_date'];

                if ($quantityAvailable < $this->settings['last_remaining_items']) {
                    $data['availability_title'] = $translations['last_items_stock'];
                    $data['availability'] = 'last_remaining_items';
                    $data['availability_color'] = '#ec8710';
                } else {
                    $availableNow = $product['available_now'];
                    $data['availability_title'] = $availableNow && !empty($availableNow[$this->context->language->id]) ? $availableNow[$this->context->language->id] : Configuration::get('PS_LABEL_IN_STOCK_PRODUCTS', $this->context->language->id);
                    $data['availability'] = 'available';

                    if (empty($data['availability_title'])) {
                        $data['availability_title'] = $translations['available'];
                    }
                }
            } elseif ($allowOosp) {
                $availableLater = $product['available_later'];
                $data['availability_title'] = $availableLater && !empty($availableLater[$this->context->language->id]) ? $availableLater[$this->context->language->id] : Configuration::get('PS_LABEL_OOS_PRODUCTS_BOA', $this->context->language->id);
                $data['availability_date'] = $product['available_date'];
                $data['availability'] = 'available';

                if (empty($data['availability_title'])) {
                    $data['availability_title'] = $translations['available'];
                }
            } elseif ($minimumQuantity > 0 && $quantityAvailable >= 0) {
                $data['availability_title'] = $translations['not_enough_items'];
                $data['availability'] = 'unavailable';
                $data['availability_color'] = '#fe3724';
            } elseif (!empty($product['quantity_all_versions']) && $product['quantity_all_versions'] > 0) {
                $data['availability_title'] = $translations['product_different_options'];
                $data['availability_date'] = $product['available_date'];
                $data['availability'] = 'unavailable';
                $data['availability_color'] = '#fe3724';
            } else {
                $data['availability_title'] = Configuration::get('PS_LABEL_OOS_PRODUCTS_BOD', $this->context->language->id);
                $data['availability_date'] = $product['available_date'];
                $data['availability'] = 'unavailable';
                $data['availability_color'] = '#fe3724';

                if (empty($data['availability_title'])) {
                    $data['availability_title'] = $translations['not_available'];
                }
            }
        }

        if (!empty($data['availability_date']) && $data['availability_date'] != null) {
            if (version_compare(_PS_VERSION_, '8.0.0', '>=')) {
                $data['availability_date'] = Tools::displayDate($data['availability_date'], false);
            } else {
                $data['availability_date'] = Tools::displayDate($data['availability_date'], $this->context->language->id, false);
            }
        }

        if ($onlyColor) {
            return $data['availability_color'];
        }

        return $data;
    }
}
