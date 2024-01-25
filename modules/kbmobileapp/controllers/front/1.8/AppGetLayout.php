<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 * API to get data of home page
 * called from home page in APP
 */
require_once 'AppCore.php';

class Appgetlayout extends AppCore {

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData() {
        if (Tools::getIsset('layout_id')) {
            if (empty(DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow("SELECT * FROM " . _DB_PREFIX_ . "kb_mobileapp_layouts where id_layout = " . (int) Tools::getValue('layout_id')))) {
                $this->content['install_module'] = '';
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), $this->l('Data is not available'), 'AppGetlayout'
                );
                $this->content['title'] = '';
                $this->content['elements'] = '';
            } else {
                $this->setListPagingData();

                $this->updateLanguageFileRecords();

                $this->content['install_module'] = '';
                $this->content['status'] = 'success';
                $this->content['message'] = '';
                $this->content['title'] = $this->gettitle();
                $this->content['elements'] = $this->getelementdata();
            }
        } else {
            $this->content['install_module'] = '';
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), $this->l('No Data found'), 'AppGetlayout'
            );
            $this->content['title'] = '';
            $this->content['elements'] = '';
        }

        if (Tools::getIsset('session_data')) {
            $this->getCartId();
        }

        return $this->fetchJSONContent();
    }

    /**
     * Get featured product data
     *
     * @return array featured product data
     */
    public function getFeaturedProducts($number_of_products, $image_content_mode) {
        $module_name = 'ps_featuredproducts';
        $data = array();
        if (!Module::isInstalled($module_name) || !Module::isEnabled($module_name)) {
            $this->writeLog('Featured product module is either inactive or not installed.');
            return $data;
        }
        $results = array();
        $products = array();
        $category = new Category((int) Configuration::get('HOME_FEATURED_CAT'), (int) $this->context->language->id);
        if (Validate::isLoadedObject($category)) {
            $nb = (int) Configuration::get('HOME_FEATURED_NBR');
            if (Configuration::get('HOME_FEATURED_RANDOMIZE')) {
                if (!$results = $category->getProducts(
                        (int) $this->context->language->id, 1, ($nb ? $nb : 8), null, null, false, true, true, ($nb ? $nb : 8)
                        )) {
                    $this->writeLog('No product found in featured product section.');
                    return $data;
                }
            } else {
                if (!$results = $category->getProducts(
                        (int) $this->context->language->id, 1, ($nb ? $nb : 8), 'position'
                        )) {
                    $this->writeLog('No product found in featured product section.');
                    return $data;
                }
            }

            if (!empty($results)) {
                $index = 0;
                foreach ($results as $res) {
                    $products[$index] = array(
                        'id' => $res['id_product'],
                        'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                        'name' => $res['name'],
                        'available_for_order' => $res['available_for_order'],
                        'show_price' => $res['show_price'],
                        'new_products' => (isset($res['new']) && $res['new'] == 1) ? "1" : "0",
                        'on_sale_products' => $res['on_sale'],
                        'cart_quantity' => $this->getCartQuantity($res['id_product']),
                        'has_attributes' => $this->getProductAttributesGroups($res),
                        'category_name' => 'Featured Products',
                        // changes by rishabh jain
                        'image_contentMode' => $image_content_mode,
                        // changes over
                        'ClickActivityName' => 'CategoryProductsActivity',
                        'category_id' => $res['id_category_default'],
                        'price' => $this->formatPrice($res['price_without_reduction']),
                    );
                    /* start:changes made by Aayushi to integrate Product Comments module */
                    if ($this->isProductCommentsEnable()) {
                        include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
                        include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
                        $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int) $res['id_product']);
                        if ($products[$index]['number_of_reviews'] > 0) {
                            $avg_rating = ProductComment::getAverageGrade((int) $res['id_product']);
                            $products[$index]['averagecomments'] = Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        }
                    }
                    /* end:changes made by Aayushi to integrate Product Comments module */
                    /* Changes started by rishabh jain on 3rd sep 2018
                     * Added urlencode perimeter in image link if enabled by admin
                     */
                    if (Configuration::get('KB_MOBILEAPP_URL_ENCODING') == 1) {
                        $products[$index]['src'] = $this->context->link->getImageLink(
                                urlencode($res['link_rewrite']), $res['id_image'], $this->getImageType('large')
                        );
                    } else {
                        $products[$index]['src'] = $this->context->link->getImageLink(
                                $res['link_rewrite'], $res['id_image'], $this->getImageType('large')
                        );
                    }
                    /* Changes over */

                    if (count($res['specific_prices']) > 0) {
                        $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                        if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $products[$index]['discount_percentage'] = $temp_price;
                            unset($temp_price);
                        } else {
                            if ($res['price_without_reduction']) {
                                $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                $percent = (float) ($temp_price / $res['price_without_reduction']);
                                unset($temp_price);
                            } else {
                                $percent = 0;
                            }
                            $products[$index]['discount_percentage'] = Tools::ps_round($percent);
                        }
                    } else {
                        $products[$index]['discount_price'] = '';
                        $products[$index]['discount_percentage'] = '';
                    }
                    $index++;
                }
                $data = $products;
            } else {
                $this->writeLog('No product found in featured product section.');
            }
        } else {
            $this->writeLog('Featured category is not found.');
        }
        return $data;
    }

    public function getProducts($product_list, $number_of_products, $image_content_mode) {
        $data = array();
        $results = array();
        $products = array();

        if (!Configuration::get('PS_CATALOG_MODE')) {
            if ($results = $this->getCustomProducts(
                    (int) $this->context->language->id, $product_list, 0, $number_of_products, false, null, null, false, false, $this->context
                    )) {
                if (!empty($results)) {
                    $results = Product::getProductsProperties((int) $this->context->language->id, $results);
                    $index = 0;
                    foreach ($results as $res) {
                        $products[$index] = array(
                            'id' => $res['id_product'],
                            'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                            'name' => $res['name'],
                            'available_for_order' => $res['available_for_order'],
                            'show_price' => $res['show_price'],
                            'new_products' => (isset($res['new']) && $res['new'] == 1) ? "1" : "0",
                            'on_sale_products' => $res['on_sale'],
                            'cart_quantity' => $this->getCartQuantity($res['id_product']),
                            'has_attributes' => $this->getProductAttributesGroups($res),
                            'category_name' => 'Special Products',
                            // changes by rishabh jain
                            'image_contentMode' => $image_content_mode,
                            // changes over
                            'ClickActivityName' => 'CategoryProductsActivity',
                            'category_id' => $res['id_category_default'],
                            'price' => $this->formatPrice($res['price_without_reduction']),
                            'src' => $this->context->link->getImageLink(
                                    urlencode($res['link_rewrite']), $res['id_image'], $this->getImageType('large')
                            )
                        );
                        /* start:changes made by Aayushi to integrate Product Comments module */
                        if ($this->isProductCommentsEnable()) {
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
                            $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int) $res['id_product']);
                            if ($products[$index]['number_of_reviews'] > 0) {
                                $avg_rating = ProductComment::getAverageGrade((int) $res['id_product']);
                                $products[$index]['averagecomments'] = Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                            }
                        }
                        /* end:changes made by Aayushi to integrate Product Comments module */
                        if (count($res['specific_prices']) > 0) {
                            $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                            if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                                $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                $products[$index]['discount_percentage'] = $temp_price;
                                unset($temp_price);
                            } else {
                                if ($res['price_without_reduction']) {
                                    $temp_price = (float) ($res['specific_prices']['reduction'] * 100);
                                    $percent = (float) ($temp_price / $res['price_without_reduction']);
                                    unset($temp_price);
                                } else {
                                    $percent = 0;
                                }
                                $products[$index]['discount_percentage'] = Tools::ps_round($percent);
                            }
                        } else {
                            $products[$index]['discount_price'] = '';
                            $products[$index]['discount_percentage'] = '';
                        }
                        $index++;
                    }
                    $data = $products;
                } else {
                    $this->writeLog('Special Product Module - Product not found.');
                }
            } else {
                $this->writeLog('Special product module - Not able to get special products.');
            }
        } else {
            $this->writeLog('Special product module - Catalog mode is not active.');
        }

        return $data;
    }

    public static function getCustomProducts($id_lang, $product_array, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, $beginning = false, $ending = false, Context $context = null) {
        $filters = array();
        $only_count = false;
        $start = null;
        $limit = $nb_products;
        $orderby = null;
        $orderway = null;
        $alias_where = 'p';
        $order_by = 'price';
        $order_way = 'DESC';
        $product_list = '';
        $product_list = implode(',', $product_array);
        if (version_compare(_PS_VERSION_, '1.5', '>')) {
            $alias_where = 'product_shop';
        }
        $columns = 'p.*, product_shop.*, product_shop.id_category_default, pl.*,
			MAX(image_shop.`id_image`) id_image, il.legend, m.name manufacturer_name,
			MAX(product_attribute_shop.id_product_attribute) id_product_attribute,
			DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(), INTERVAL 20 DAY)) > 0 AS new, stock.out_of_stock,
			IFNULL(stock.quantity, 0) as quantity';

        $query = 'SELECT {{COLUMNS}} FROM `' . _DB_PREFIX_ . 'product` p';
        if (isset($filters['id_category']) && (int) $filters['id_category'] > 0) {
            $query .= 'INNER JOIN `' . _DB_PREFIX_ . 'category_product` cp ON p.`id_product` = cp.`id_product`
			LEFT JOIN ' . _DB_PREFIX_ . 'category c ON (c.id_category = cp.id_category) ';
        }


        $query .= Shop::addSqlAssociation('product', 'p')
                . Product::sqlStock('p', null, false, Context::getContext()->shop) . '
			LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl
			ON (pl.id_product = p.id_product' . Shop::addSqlRestrictionOnLang('pl')
                . ' AND pl.id_lang = ' . (int) $id_lang . ')
			LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)
			' . Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
			LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image`
			AND il.`id_lang` = ' . (int) $id_lang . ')
			LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer m ON (m.id_manufacturer = p.id_manufacturer)
			LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON (p.id_product = pa.id_product)
			' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1')
                . ' WHERE p.id_product IN (' . $product_list . ') AND ' . pSQL($alias_where) . '.`active` = 1
			AND ' . pSQL($alias_where) . '.`visibility` IN ("both", "catalog") ';

        $filter_conditions = '';
        if (isset($filters['id_category']) && (int) $filters['id_category'] > 0) {
            $filter_conditions .= ' AND c.id_category = ' . (int) $filters['id_category'] . ' AND c.active = 1';
        }

        $query .= pSQL($filter_conditions);

        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $key => $filter) {
                if ($key != 'id_category') {
                    $filter_conditions .= ' AND ' . pSQL($filter);
                }
            }
        }

        $query .= ' GROUP BY ' . pSQL($alias_where) . '.id_product';

        if ($only_count) {
            $query = str_replace('{{COLUMNS}}', 'COUNT(p.id_product) as total', $query);
            $rows = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            return count($rows);
        } else {
            $query = str_replace('{{COLUMNS}}', $columns, $query);
            if ($orderby != null && $orderway != null) {
                $query .= ' ORDER BY ' . pSQL($orderby) . ' ' . pSQL($orderway);
            } else {
                $query .= ' ORDER BY ' . pSQL($alias_where) . '.id_product DESC';
            }

            if ((int) $start > 0 && (int) $limit > 0) {
                $query .= ' LIMIT ' . (int) $start . ',' . (int) $limit;
            } elseif ((int) $limit > 0) {
                $query .= ' LIMIT ' . (int) $limit;
            }

            return DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        }
    }

    /**
     * Get special product data
     *
     * @return array special product data
     */
    public function getSpecialProducts($number_of_products, $image_content_mode) {
        $data = array();

        $results = array();
        $products = array();

        if (!Configuration::get('PS_CATALOG_MODE')) {
            if ($results = Product::getPricesDrop(
                            (int) $this->context->language->id, 0, (int) $number_of_products, false, null, null, false, false, $this->context
                    )) {
                if (!empty($results)) {
                    $index = 0;
                    foreach ($results as $res) {
                        $products[$index] = array(
                            'id' => $res['id_product'],
                            'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                            'name' => $res['name'],
                            'available_for_order' => $res['available_for_order'],
                            'show_price' => $res['show_price'],
                            'new_products' => (isset($res['new']) && $res['new'] == 1) ? "1" : "0",
                            'on_sale_products' => $res['on_sale'],
                            'cart_quantity' => $this->getCartQuantity($res['id_product']),
                            'has_attributes' => $this->getProductAttributesGroups($res),
                            'category_name' => 'Special Products',
                            // changes by rishabh jain
                            'image_contentMode' => $image_content_mode,
                            // changes over
                            'ClickActivityName' => 'CategoryProductsActivity',
                            'category_id' => $res['id_category_default'],
                            'price' => $this->formatPrice($res['price_without_reduction']),
                        );
                        /* start:changes made by Aayushi to integrate Product Comments module */
                        if ($this->isProductCommentsEnable()) {
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
                            $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int) $res['id_product']);
                            if ($products[$index]['number_of_reviews'] > 0) {
                                $avg_rating = ProductComment::getAverageGrade((int) $res['id_product']);
                                $products[$index]['averagecomments'] = Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                            }
                        }
                        /* end:changes made by Aayushi to integrate Product Comments module */
                        /* Changes started by rishabh jain on 3rd sep 2018
                         * Added urlencode perimeter in image link if enabled by admin
                         */
                        if (Configuration::get('KB_MOBILEAPP_URL_ENCODING') == 1) {
                            $products[$index]['src'] = $this->context->link->getImageLink(
                                    urlencode($res['link_rewrite']), $res['id_image'], $this->getImageType('large')
                            );
                        } else {
                            $products[$index]['src'] = $this->context->link->getImageLink(
                                    $res['link_rewrite'], $res['id_image'], $this->getImageType('large')
                            );
                        }
                        /* Changes over */

                        if (count($res['specific_prices']) > 0) {
                            $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                            if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                                $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                $products[$index]['discount_percentage'] = $temp_price;
                                unset($temp_price);
                            } else {
                                if ($res['price_without_reduction']) {
                                    $temp_price = (float) ($res['specific_prices']['reduction'] * 100);
                                    $percent = (float) ($temp_price / $res['price_without_reduction']);
                                    unset($temp_price);
                                } else {
                                    $percent = 0;
                                }
                                $products[$index]['discount_percentage'] = Tools::ps_round($percent);
                            }
                        } else {
                            $products[$index]['discount_price'] = '';
                            $products[$index]['discount_percentage'] = '';
                        }
                        $index++;
                    }
                    $data = $products;
                } else {
                    $this->writeLog('Special Product Module - Product not found.');
                }
            } else {
                $this->writeLog('Special product module - Not able to get special products.');
            }
        } else {
            $this->writeLog('Special product module - Catalog mode is not active.');
        }

        return $data;
    }

    /**
     * Get Best seller products data
     *
     * @return array best seller product data
     */
    public function getBestSellerProducts($number_of_products, $image_content_mode) {
        $data = array();
//        if (!Module::isInstalled($module_name) || !Module::isEnabled($module_name)) {
//            $this->writeLog('Bestseller module is either inactive or not installed.');
//            return $data;
//        }

        $results = array();
        $products = array();
        if (!Configuration::get('PS_CATALOG_MODE')) {
            if ($results = ProductSale::getBestSalesLight(
                            (int) $this->context->language->id, 0, (int) $number_of_products
                    )) {
                if (!empty($results)) {
                    $index = 0;
                    foreach ($results as $res) {
                        $products[$index] = array(
                            'id' => $res['id_product'],
                            'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                            'name' => $res['name'],
                            'available_for_order' => $res['available_for_order'],
                            'show_price' => $res['show_price'],
                            'new_products' => (isset($res['new']) && $res['new'] == 1) ? "1" : "0",
                            'on_sale_products' => $res['on_sale'],
                            'cart_quantity' => $this->getCartQuantity($res['id_product']),
                            'has_attributes' => $this->getProductAttributesGroups($res),
                            'category_name' => 'Best Seller Products',
                            'ClickActivityName' => 'CategoryProductsActivity',
                            // changes by rishabh jain
                            'image_contentMode' => $image_content_mode,
                            // changes over
                            'category_id' => $res['id_category_default'],
                            'price' => $this->formatPrice($res['price_without_reduction']),
                        );
                        /* start:changes made by Aayushi to integrate Product Comments module */
                        if ($this->isProductCommentsEnable()) {
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
                            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
                            $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int) $res['id_product']);
                            if ($products[$index]['number_of_reviews'] > 0) {
                                $avg_rating = ProductComment::getAverageGrade((int) $res['id_product']);
                                $products[$index]['averagecomments'] = Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                            }
                        }
                        /* end:changes made by Aayushi to integrate Product Comments module */
                        /* Changes started by rishabh jain on 3rd sep 2018
                         * Added urlencode perimeter in image link if enabled by admin
                         */
                        if (Configuration::get('KB_MOBILEAPP_URL_ENCODING') == 1) {
                            $products[$index]['src'] = $this->context->link->getImageLink(
                                    urlencode($res['link_rewrite']), $res['id_image'], $this->getImageType('large')
                            );
                        } else {
                            $products[$index]['src'] = $this->context->link->getImageLink(
                                    $res['link_rewrite'], $res['id_image'], $this->getImageType('large')
                            );
                        }
                        /* Changes over */
                        if (count($res['specific_prices']) > 0) {
                            $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                            if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                                $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                $products[$index]['discount_percentage'] = $temp_price;
                                unset($temp_price);
                            } else {
                                if ($res['price_without_reduction']) {
                                    $temp_price = ((float) $res['specific_prices']['reduction'] * 100);
                                    $percent = (float) ($temp_price / $res['price_without_reduction']);
                                    unset($temp_price);
                                } else {
                                    $percent = 0;
                                }
                                $products[$index]['discount_percentage'] = Tools::ps_round($percent);
                            }
                        } else {
                            $products[$index]['discount_price'] = '';
                            $products[$index]['discount_percentage'] = '';
                        }
                        $index++;
                    }
                    $data = $products;
                } else {
                    $this->writeLog('Bestseller Module - Product not found.');
                }
            } else {
                $this->writeLog('Bestseller module - Not able to get best seller products.');
            }
        } else {
            $this->writeLog('Bestseller module - Catalog mode is not active.');
        }

        return $data;
    }

    /**
     * Get new products data
     *
     * @return array new products data
     */
    public function getNewProductsList($number_of_products, $image_content_mode) {
        $data = array();
        $results = array();
        $products = array();

        if (!Configuration::get('NEW_PRODUCTS_NBR')) {
            $this->writeLog('New Arrvial module - NBR Configuration is missing.');
            return $data;
        }

        if (!Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) {
            $this->writeLog('New Arrvial module - NB Days is missing.');
            return $data;
        }

        if ($results = Product::getNewProducts(
                        (int) $this->context->language->id, 0, (int) $number_of_products
                )) {
            if (!empty($results)) {
                $index = 0;
                foreach ($results as $res) {
                    $products[$index] = array(
                        'id' => $res['id_product'],
                        'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                        'name' => $res['name'],
                        'available_for_order' => $res['available_for_order'],
                        'show_price' => $res['show_price'],
                        'new_products' => (isset($res['new']) && $res['new'] == 1) ? "1" : "0",
                        'on_sale_products' => $res['on_sale'],
                        'cart_quantity' => $this->getCartQuantity($res['id_product']),
                        'has_attributes' => $this->getProductAttributesGroups($res),
                        'category_name' => 'New Arrivals',
                        // changes by rishabh jain
                        'image_contentMode' => $image_content_mode,
                        // changes over
                        'ClickActivityName' => 'CategoryProductsActivity',
                        'category_id' => $res['id_category_default'],
                        'price' => $this->formatPrice($res['price_without_reduction']),
                    );
                    /* start:changes made by Aayushi to integrate Product Comments module */
                    if ($this->isProductCommentsEnable()) {
                        include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
                        include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
                        $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int) $res['id_product']);
                        if ($products[$index]['number_of_reviews'] > 0) {
                            $avg_rating = ProductComment::getAverageGrade((int) $res['id_product']);
                            $products[$index]['averagecomments'] = Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        }
                    }
                    /* end:changes made by Aayushi to integrate Product Comments module */
                    /* Changes started by rishabh jain on 3rd sep 2018
                     * Added urlencode perimeter in image link if enabled by admin
                     */
                    if (Configuration::get('KB_MOBILEAPP_URL_ENCODING') == 1) {
                        $products[$index]['src'] = $this->context->link->getImageLink(
                                urlencode($res['link_rewrite']), $res['id_image'], $this->getImageType('large')
                        );
                    } else {
                        $products[$index]['src'] = $this->context->link->getImageLink(
                                $res['link_rewrite'], $res['id_image'], $this->getImageType('large')
                        );
                    }

                    if (count($res['specific_prices']) > 0) {
                        $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                        if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $products[$index]['discount_percentage'] = $temp_price;
                            unset($temp_price);
                        } else {
                            if ($res['price_without_reduction']) {
                                $temp_price = (float) ($res['specific_prices']['reduction'] * 100);
                                $percent = (float) ($temp_price / $res['price_without_reduction']);
                                unset($temp_price);
                            } else {
                                $percent = 0;
                            }
                            $products[$index]['discount_percentage'] = Tools::ps_round($percent);
                        }
                    } else {
                        $products[$index]['discount_price'] = '';
                        $products[$index]['discount_percentage'] = '';
                    }
                    $index++;
                }
                $data['products'] = $products;
            } else {
                $this->writeLog('Bestseller Module - Product not found.');
            }
        } else {
            $this->writeLog('Bestseller module - Not able to get best seller products.');
        }

        return $products;
    }

    /**
     * Get cart id of customer for cart persistance
     *
     */
    public function getCartId() {
        $cart_id = Tools::getValue('session_data', 0);
        $cart = new Cart($cart_id);
        if (!Validate::isLoadedObject($cart)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), $this->l('Unable to load cart'), 'AppGetHome'
            );
            $this->writeLog('Unable to load cart');
        } else {
            $order_id = Order::getOrderByCartId($cart->id);
            if ($order_id) {
                $this->context->cart->id_currency = $this->context->currency->id;
                $this->context->cart = new Cart();
                $this->context->cart->id_carrier = 0;
                $this->context->cart->setDeliveryOption(null);
                if ($this->context->customer->id > 0) {
                    $d_id = (int) Address::getFirstCustomerAddressId((int) ($this->context->customer->id));
                    $i_id = (int) Address::getFirstCustomerAddressId((int) ($this->context->customer->id));
                    $this->context->cart->id_address_delivery = $d_id;
                    $this->context->cart->id_address_invoice = $i_id;
                    $this->context->cart->id_customer = (int) $this->context->customer->id;
                    $this->context->cart->secure_key = $this->context->customer->secure_key;
                }
                $this->context->cart->id_currency = $this->context->currency->id;
                $this->context->cart->save();
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();
                $this->content['status'] = 'success';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), $this->l('Order created by this cart'), 'AppGetHome'
                );
                $this->content['cart_id'] = $this->context->cart->id;
                $this->writeLog('Order created by this cart');
            } else {
                $this->content['status'] = 'failure';
                $this->content['message'] = '';
            }
        }
    }

    public function gettitle() {
        $sql = 'SELECT layout_name FROM ' . _DB_PREFIX_ . 'kb_mobileapp_layouts where id_layout = ' . (int) Tools::getValue('layout_id');
        $result = Db::getInstance()->getvalue($sql);
        return unserialize($result)[Language::getIdByIso(Tools::getValue('iso_code'))];
    }

    /**
     * Get link of cms pages i.e contact us,about us and term and conditions page
     *
     */
    public function getelementdata() {
        $element_data = array();
        $layout_id = Tools::getValue('layout_id');
        if ($layout_id == '') {
            return "";
        }
        /* start:changes made by aayushi on 9th January 2020 to make MAB compatible with marketplace */
        $sql = 'Select id_component from ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_layout = ' . (int) $layout_id . ' and store_id =' . (int) $this->context->shop->id . ' order by position asc';
        /* end:changes made by aayushi on 9th January 2020 to make MAB compatible with marketplace */
        $components = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (count($components) > 0) {
            $i = 0;
            foreach ($components as $key => $comp) {
                $sql = 'Select id_component_type from ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component = ' . (int) $comp['id_component'];
                $component_type_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getvalue($sql);
                $sql = 'Select component_name from ' . _DB_PREFIX_ . 'kbmobileapp_component_types where id = ' . (int) $component_type_id;
                $component_type = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                if ($component_type == 'top_category') {
                    $category_data = array();
                    $sql = 'SELECT *  FROM ' . _DB_PREFIX_ . 'kbmobileapp_top_category
                            where id_component = ' . (int) $comp['id_component'];
                    $categories = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    /* start: changes made by aayushi on 25 april 2019 to add category heading */
                    if (!empty($categories)) {
                        $category_heading_data = Tools::unSerialize($categories[0]['category_heading']);
                    } else {
                        $category_heading_data = array();
                    }
                    $id = Language::getIdByIso(Tools::getValue('iso_code'));
                    $j = 0;
                    /* start: changes made by aayushi on 25 april 2019 to add category heading */
                    if (is_array($categories)) {
                        if (count($categories) > 0) {
                            $element_data[$i]['element_type'] = 'categories_top';
                            $element_data[$i]['data'] = array();
                            if ($this->checkSecureUrl()) {
                                $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_IMG_DIR_);
                                $http_string = 'https://';
                            } else {
                                $http_string = 'http://';
                                $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_IMG_DIR_);
                            }
                            /* changes made by vibhaas to add redirect activity in top category component */
                            $category_array = array();
                            $category_array = explode('|', $categories[0]['id_category']);
                            $layout_array = explode('|', $categories[0]['id_layout']);
                            $redirect_activity_array = explode('|', $categories[0]['redirect_activity']);
                            $image_array = array();
                            $image_array = explode('|', $categories[0]['image_url']);

                            foreach ($redirect_activity_array as $key => $value1) {
                                $data = array();
                                if ($value1 > 0) {
                                    if ($value1 == 1) {
                                        $data['target_id'] = $layout_array[$key];
                                        $data['click_target'] = 'layout';
                                        $data['image_src'] = $module_dir . 'kbmobileapp/' . $image_array[$key];
                                        $data['image_contentMode'] = $categories[0]['image_content_mode'];
                                        if (isset($category_heading_data[$j][$id]) && !empty($category_heading_data[$j][$id])) {
                                            $data['title'] = $category_heading_data[$j][$id];
                                        } else {
                                            $data['title'] = '';
                                        }
                                    } else if ($value1 == 2) {
                                        $sql = 'SELECT name  FROM ' . _DB_PREFIX_ . 'category_lang
                                            where id_category = ' . (int) $category_array[$key] . ' And id_lang = ' . (int) $this->context->language->id;
                                        $category_name = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                                        $data['target_id'] = $category_array[$key];
                                        $data['click_target'] = 'category';
                                        if ($image_array[$key] != '') {
                                            $data['image_src'] = $module_dir . 'kbmobileapp/' . $image_array[$key];
                                        } else {
                                            $link = new link;
                                            $data['image_src'] = $link->getCatImageLink($category_name, $value1);
                                            if ($data['image_src'] != '') {
                                                $data['image_src'] = $http_string . $data['image_src'];
                                            }
                                        }
                                        $data['image_contentMode'] = $categories[0]['image_content_mode'];
                                        if (isset($category_heading_data[$j][$id]) && !empty($category_heading_data[$j][$id])) {
                                            $data['title'] = $category_heading_data[$j][$id];
                                        } else {
                                            $data['title'] = '';
                                        }
                                    }
                                    $category_data[] = $data;
                                }
                                $j++;
                            }
//                            foreach ($category_array as $k => $value) {
//                                if ($k <= count($category_array) && $value > 0) {
//                                    $sql = 'SELECT name  FROM ' . _DB_PREFIX_ . 'category_lang
//                                            where id_category = '.(int)$value .' And id_lang = '.(int)$this->context->language->id;
//                                    $category_name = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
//                                    $data = array();
//                                    $data['id'] = $value;
//                                    if ($image_array[$k] != '') {
//                                        $data['image_src'] = $module_dir . 'kbmobileapp/'.$image_array[$k];
//                                    } else {
//                                        $link = new link;
//                                        $data['image_src'] = $link->getCatImageLink($category_name, $value);
//                                        if ($data['image_src'] != '') {
//                                            $data['image_src'] = $http_string.$data['image_src'];
//                                        }
//                                    }
//                                    $data['image_contentMode'] = $categories[0]['image_content_mode'];
//                                    $data['name'] = $category_name;
//                                    /*Start:changes made by aayushi on 25 april 2019 to add category heading*/
//                                    if (isset($category_heading_data[$j][$id]) && !empty($category_heading_data[$j][$id])) {
//                                        $data['title'] = $category_heading_data[$j][$id];
//                                    } else {
//                                        $data['title'] = '';
//                                    }
//                                    /*End :changes made by aayushi on 25 april 2019 to add category heading*/
//                                    $category_data[] = $data;
//                                }
//                                /*Start:changes made by aayushi on 25 april 2019 to add category heading*/
//                                $j++;
//                                /*End :changes made by aayushi on 25 april 2019 to add category heading*/
//                            }
                            unset($data);
                            $element_data[$i]['data'] = $category_data;
                            $i++;
                        }
                    }
                } elseif ($component_type == 'banner_square') {
                    $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                    $banner_heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                    $banner_heading_array = Tools::unSerialize($banner_heading_data);

                    $square_banner_data = array();
                    $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_banners where id_component =' . (int) $comp['id_component'] . " order by id";
                    $banner_data = Db::getInstance()->executeS($sql);
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $element_data[$i]['element_type'] = 'banners_square';
                            $element_data[$i]['heading'] = '';
                            if (isset($banner_heading_array[$this->context->language->id])) {
                                $element_data[$i]['heading'] = $banner_heading_array[$this->context->language->id];
                            }
                            $element_data[$i]['data'] = array();
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                /* start:changes made by aayushi  on 25 April 2019 to add banner heading */
                                $heading_of_each_banner = array();
                                $heading_of_each_banner = Tools::unserialize($bd['banner_heading']);
                                $id = Language::getIdByIso(Tools::getValue('iso_code'));
                                if (isset($heading_of_each_banner[$id]) && !empty($heading_of_each_banner[$id])) {
                                    $data['banner_heading'] = $heading_of_each_banner[$id];
                                } else {
                                    $data['banner_heading'] = '';
                                }
                                /* end:changes made by aayushi on 25 April 2019 to add banner heading */
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                } else if ($bd['redirect_activity'] == 'CMS') {
                                    $context = Context::getContext();
                                    $link = $context->link->getCMSLink((int) $bd['category_id']);
                                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                                    $data['target_id'] = $link;
                                    /* changes made by vibhaas for set target id layout id */
                                } else if ($bd['redirect_activity'] == 'layout') {
                                    $data['target_id'] = $bd['layout_id'];
                                } else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $data['src'] = $bd['image_url'];
                                $data['title'] = '';
                                $data['image_contentMode'] = $bd['image_contentMode'];
                                $square_banner_data[] = $data;
                            }
                            unset($data);
                            $element_data[$i]['data'] = $square_banner_data;
                            $i++;
                        }
                    }
                } elseif ($component_type == 'banners_grid') {
                    $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                    $banner_heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                    $banner_heading_array = Tools::unSerialize($banner_heading_data);
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_banners where id_component =' . (int) $comp['id_component'] . " order by id";
                    $banner_data = Db::getInstance()->executeS($sql);
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $element_data[$i]['element_type'] = 'banners_grid';
                            $element_data[$i]['heading'] = '';
                            if (isset($banner_heading_array[$this->context->language->id])) {
                                $element_data[$i]['heading'] = $banner_heading_array[$this->context->language->id];
                            }
                            $element_data[$i]['data'] = array();
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                /* start:changes made by aayushi  on 25 April 2019 to add banner heading */
                                $heading_of_each_banner = array();
                                $heading_of_each_banner = Tools::unserialize($bd['banner_heading']);
                                $id = Language::getIdByIso(Tools::getValue('iso_code'), false);
                                if (isset($heading_of_each_banner[$id]) && !empty($heading_of_each_banner[$id])) {
                                    $data['banner_heading'] = $heading_of_each_banner[$id];
                                } else {
                                    $data['banner_heading'] = '';
                                }
                                /* end:changes made by aayushi on 25 April 2019 to add banner heading */
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                } else if ($bd['redirect_activity'] == 'CMS') {
                                    $context = Context::getContext();
                                    $link = $context->link->getCMSLink((int) $bd['category_id']);
                                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                                    $data['target_id'] = $link;
                                } else if ($bd['redirect_activity'] == 'layout') {
                                    $data['target_id'] = $bd['layout_id'];
                                } else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $data['src'] = $bd['image_url'];
                                $data['title'] = '';
                                $data['image_contentMode'] = $bd['image_contentMode'];
                                $square_banner_data[] = $data;
                            }
                            unset($data);
                            $element_data[$i]['data'] = $square_banner_data;
                            $i++;
                        }
                    }
                } elseif ($component_type == 'banners_countdown') {
                    $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                    $banner_heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                    $banner_heading_array = Tools::unSerialize($banner_heading_data);
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_banners where id_component =' . (int) $comp['id_component'];
                    $banner_data = Db::getInstance()->executeS($sql);
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $element_data[$i]['element_type'] = 'banners_countdown';
                            $element_data[$i]['heading'] = '';
                            if (isset($banner_heading_array[$this->context->language->id])) {
                                $element_data[$i]['heading'] = $banner_heading_array[$this->context->language->id];
                            }
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                /* start:changes made by aayushi  on 25 April 2019 to add banner heading */
                                $heading_of_each_banner = array();
                                $heading_of_each_banner = Tools::unserialize($bd['banner_heading']);
                                $id = Language::getIdByIso(Tools::getValue('iso_code'));
                                if (isset($heading_of_each_banner[$id]) && !empty($heading_of_each_banner[$id])) {
                                    $data['banner_heading'] = $heading_of_each_banner[$id];
                                } else {
                                    $data['banner_heading'] = '';
                                }
                                /* end:changes made by aayushi on 25 April 2019 to add banner heading */
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                } else if ($bd['redirect_activity'] == 'CMS') {
                                    $context = Context::getContext();
                                    $link = $context->link->getCMSLink((int) $bd['category_id']);
                                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                                    $data['target_id'] = $link;
                                } else if ($bd['redirect_activity'] == 'layout') {
                                    $data['target_id'] = $bd['layout_id'];
                                } else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $data['src'] = $bd['image_url'];
                                $data['title'] = '';
                                $data['image_contentMode'] = $bd['image_contentMode'];
                                $data['upto_time'] = "" . strtotime($bd['countdown']) - time() . "";
                                $text_color = array();
                                $text_color = explode("#", $bd['text_color']);
                                $data['timer_text_color'] = $text_color[1];

                                if ($bd['is_enabled_background_color'] == 1) {
                                    $background_color = array();
                                    $background_color = explode("#", $bd['background_color']);
                                    $data['timer_background_color'] = $background_color[1];
                                } else {
                                    $data['timer_background_color'] = '';
                                }
                                if (strtotime($bd['countdown']) > time()) {
                                    $square_banner_data[] = $data;
                                }
                            }
                            $element_data[$i]['data'] = $square_banner_data;
                            $i++;
                            unset($data);
                        }
                    }
                } elseif ($component_type == 'banner_horizontal_slider') {
                    $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                    $banner_heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                    $banner_heading_array = Tools::unSerialize($banner_heading_data);
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_banners where id_component =' . (int) $comp['id_component'] . " order by id";
                    $banner_data = Db::getInstance()->executeS($sql);
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $element_data[$i]['element_type'] = 'banners_horizontal_sliding';
                            $element_data[$i]['heading'] = '';
                            if (isset($banner_heading_array[$this->context->language->id])) {
                                $element_data[$i]['heading'] = $banner_heading_array[$this->context->language->id];
                            }
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                /* start:changes made by aayushi  on 25 April 2019 to add banner heading */
                                $heading_of_each_banner = array();
                                $heading_of_each_banner = Tools::unserialize($bd['banner_heading']);
                                $id = Language::getIdByIso(Tools::getValue('iso_code'), false);
                                if (isset($heading_of_each_banner[$id]) && !empty($heading_of_each_banner[$id])) {
                                    $data['banner_heading'] = $heading_of_each_banner[$id];
                                } else {
                                    $data['banner_heading'] = '';
                                }
                                /* end:changes made by aayushi on 25 April 2019 to add banner heading */
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                } else if ($bd['redirect_activity'] == 'CMS') {
                                    $context = Context::getContext();
                                    $link = $context->link->getCMSLink((int) $bd['category_id']);
                                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                                    $data['target_id'] = $link;
                                    /* changes made by vibhaas for set target id layout id */
                                } else if ($bd['redirect_activity'] == 'layout') {
                                    $data['target_id'] = $bd['layout_id'];
                                } else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $data['src'] = $bd['image_url'];
                                $data['title'] = '';
                                $data['image_contentMode'] = $bd['image_contentMode'];
                                $square_banner_data[] = $data;
                            }
                            unset($data);
                            $element_data[$i]['data'] = $square_banner_data;
                            $i++;
                        }
                    }
                } elseif ($component_type == 'products_recent') {
                    $element_data[$i]['element_type'] = 'products_recent';
                    $element_data[$i]['heading'] = parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false), $this->l('Recent Products'), 'AppGetHome'
                    );
                    $element_data[$i]['data'] = array();
                    $i++;
                } elseif ($component_type == 'products_grid') {
                    $products = array();
                    $products = $this->getProductsComponentData($comp['id_component']);
                    if (count($products) > 0) {
                        $element_data[$i]['element_type'] = $component_type;
                        $element_data[$i]['heading'] = '';
                        $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                        $heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                        $heading_array = Tools::unSerialize($heading_data);
                        $element_data[$i]['heading'] = '';
                        if (isset($heading_array[$this->context->language->id])) {
                            $element_data[$i]['heading'] = $heading_array[$this->context->language->id];
                        }
                        $element_data[$i]['data'] = $products;
                        unset($products);
                        $i++;
                    }
                } elseif ($component_type == 'products_horizontal') {
                    $products = array();
                    $products = $this->getProductsComponentData($comp['id_component']);
                    if (count($products) > 0) {
                        $element_data[$i]['element_type'] = $component_type;
                        $element_data[$i]['heading'] = '';
                        $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                        $heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                        $heading_array = Tools::unSerialize($heading_data);
                        $element_data[$i]['heading'] = '';
                        if (isset($heading_array[$this->context->language->id])) {
                            $element_data[$i]['heading'] = $heading_array[$this->context->language->id];
                        }
                        $element_data[$i]['data'] = $products;
                        unset($products);
                        $i++;
                    }
                } elseif ($component_type == 'products_square') {
                    $products = array();
                    $products = $this->getProductsComponentData($comp['id_component']);
                    if (count($products) > 0) {
                        $element_data[$i]['element_type'] = $component_type;
                        $element_data[$i]['heading'] = '';
                        $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                        $heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                        $heading_array = Tools::unSerialize($heading_data);
                        $element_data[$i]['heading'] = '';
                        if (isset($heading_array[$this->context->language->id])) {
                            $element_data[$i]['heading'] = $heading_array[$this->context->language->id];
                        }
                        $element_data[$i]['data'] = $products;
                        unset($products);
                        $i++;
                    }
                    /* start:changes made by aayushi on 3rd January 2020 to add custom banners */
                } elseif ($component_type == 'banner_custom') {
                    $sql = 'Select component_heading from  ' . _DB_PREFIX_ . 'kb_mobileapp_layout_component where id_component =' . (int) $comp['id_component'];
                    $banner_heading_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                    $banner_heading_array = Tools::unSerialize($banner_heading_data);
                    $custom_banner_data = array();
                    $insets_array = array();
                    $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_custom_banners where id_component =' . (int) $comp['id_component'] . " order by id";
                    $banner_data = Db::getInstance()->executeS($sql);
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $element_data[$i]['element_type'] = 'banners_custom';
                            $element_data[$i]['heading'] = '';
                            $element_data[$i]['is_sliding'] = "0";
                            if (isset($banner_heading_array[$this->context->language->id])) {
                                $element_data[$i]['heading'] = $banner_heading_array[$this->context->language->id];
                            }
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                $heading_of_each_banner = array();
                                $heading_of_each_banner = Tools::unserialize($bd['banner_heading']);
                                $id = Language::getIdByIso(Tools::getValue('iso_code'), false);
                                if (isset($heading_of_each_banner[$id]) && !empty($heading_of_each_banner[$id])) {
                                    $data['banner_heading'] = $heading_of_each_banner[$id];
                                     $data['title'] = $heading_of_each_banner[$id];
                                } else {
                                    $data['banner_heading'] = '';
                                    $data['title'] = '';
                                }
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                } else if ($bd['redirect_activity'] == 'CMS') {
                                    $context = Context::getContext();
                                    $link = $context->link->getCMSLink((int) $bd['category_id']);
                                    $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                                    $data['target_id'] = $link;
                                } else if ($bd['redirect_activity'] == 'layout') {
                                    $data['target_id'] = $bd['layout_id'];
                                }
                                else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $data['src'] = $bd['image_url'];
                                
                                $data['image_contentMode'] = $bd['image_contentMode'];
                                $custom_banner_color_array = explode('#', $bd['background_color_of_banner']);
                                $data['bg_color'] = $custom_banner_color_array[1];
                                $data['banner_width'] = $bd['width'];
                                $data['banner_height'] = $bd['height'];
                                $insets_array = array(
                                    'left' => (isset($bd['left_inset']) && $bd['left_inset'] ) ? $bd['left_inset'] : "0",
                                    'right' => (isset($bd['right_inset']) && $bd['right_inset'] ) ? $bd['right_inset'] : "0",
                                    'top' => (isset($bd['top_inset']) && $bd['top_inset'] ) ? $bd['top_inset'] : "0",
                                    'bottom' => (isset($bd['bottom_inset']) && $bd['bottom_inset'] ) ? $bd['bottom_inset'] : "0",
                                );
                                $data['insets'] = $insets_array;
                                //$data['is_sliding'] = $bd['is_sliding'];
                                $custom_banner_data[] = $data;
                            }
                            unset($data);
                            $element_data[$i]['data'] = $custom_banner_data;

                            $i++;
                        }
                    }
                }
                /* end:changes made by aayushi on 3rd January 2020 to add custom banners */
            }
        }
        return $element_data;
    }

    public function getProductsComponentData($id_component) {
        $sql = 'Select * from  ' . _DB_PREFIX_ . 'kbmobileapp_product_data where id_component =' . (int) $id_component;
        $product_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
        if (count($product_data) > 0) {
            $product_type = $product_data['product_type'];
            $number_of_products = $product_data['number_of_products'];
            $image_content_mode = $product_data['image_content_mode'];
            $products = array();
            if ($product_type == 'best_seller') {
                $products = $this->getBestSellerProducts($number_of_products, $image_content_mode);
            } elseif ($product_type == 'new_products') {
                $products = $this->getNewProductsList($number_of_products, $image_content_mode);
            } elseif ($product_type == 'featured_products') {
                $products = $this->getFeaturedProducts($number_of_products, $image_content_mode);
            } elseif ($product_type == 'special_products') {
                $products = $this->getSpecialProducts($number_of_products, $image_content_mode);
            } elseif ($product_type == 'category_products') {
                $product_list = array();
                $product_list = explode(',', $product_data['category_products']);
                $products = $this->getProducts($product_list, $number_of_products, $image_content_mode);
            } elseif ($product_type == 'custom_products') {
                $product_list = array();
                $product_list = explode(',', $product_data['custom_products']);
                $products = $this->getProducts($product_list, $number_of_products, $image_content_mode);
            }
            $sliced_product = array();
            $sliced_product = array_slice($products, 0, $number_of_products);
            return $sliced_product;
        }
    }

    public function getCmsPagesLink() {
        /* start:changes made by Aayushi Agarwal on 1 DEC 2018 to allow functionality to enable cms pages */
        $data = array();
        $saved_cms = Tools::unSerialize(Configuration::get('KB_ENABLED_CMS'));
        $context = Context::getContext();
        if (!empty($saved_cms)) {
            foreach ($saved_cms as $key => $value) {
                $link = $context->link->getCMSLink((int) $value);
                $link .= (parse_url($link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
                //$sql2 = 'select * from '._DB_PREFIX_.'cms_lang where id_cms='.(int)$value;
                $sql2 = 'select * from ' . _DB_PREFIX_ . 'cms_lang where id_lang=' . (int) $this->context->language->id . ' and id_cms=' . (int) $value;
                $cms_data2 = Db::getInstance()->getRow($sql2);
                $data[$key] = array(
                    'name' => $cms_data2['meta_title'],
                    'link' => $link
                );
            }
        }
        /* end:changes made by Aayushi Agarwal on 1 DEC 2018 to allow functionality to enable cms pages */
        $this->content['cms_links'] = $data;
        $this->content['contact_us_available'] = "1";
        $contact_url = (_PS_VERSION_ >= 1.5) ? 'contact' : 'contact-form';
        $contact_link = $this->context->link->getPageLink($contact_url);
        $contact_link .= (parse_url($contact_link, PHP_URL_QUERY) ? '&' : '?') . 'content_only=1';
        $this->content['contact_us_link'] = $contact_link;
    }

    /*
     * Get Footer CMS titles
     *
     * @return array
     */

    public function getCMSTitlesFooter() {
        $context = Context::getContext();
        $footerCms = Configuration::get('FOOTER_CMS');

        if (empty($footerCms)) {
            return array();
        }

        $cmsCategories = explode('|', $footerCms);

        $content = array();

        foreach ($cmsCategories as $cmsCategory) {
            $ids = explode('_', $cmsCategory);

            if ($ids[0] == 1 && isset($ids[1])) {
                $query = $this->getBlockName($ids[1]);
                $content[$cmsCategory]['link'] = $context->link->getCMSCategoryLink((int) $ids[1], $query['link_rewrite']);
                $content[$cmsCategory]['meta_title'] = $query['name'];
            } else if ($ids[0] == 0 && isset($ids[1])) {
                $query = $this->getCMSMetaTitle($ids[1]);
                $content[$cmsCategory]['link'] = $context->link->getCMSLink((int) $ids[1], $query['link_rewrite']);
                $content[$cmsCategory]['meta_title'] = $query['meta_title'];
            }
        }
        return $content;
    }

    /*
     * Get footer block name
     *
     * @param int $id block category id
     * @return array
     */

    public function getBlockName($id) {
        $sql = 'SELECT cl.`name`, cl.`link_rewrite`
                FROM `' . _DB_PREFIX_ . 'cms_category_lang` cl
                INNER JOIN `' . _DB_PREFIX_ . 'cms_category` c
                ON (cl.`id_cms_category` = c.`id_cms_category`)
                WHERE cl.`id_cms_category` = ' . (int) $id . '
                AND (c.`active` = 1 OR c.`id_cms_category` = 1)
                AND cl.`id_lang` = ' . (int) Context::getContext()->language->id;

        return Db::getInstance()->getRow($sql);
    }

    /*
     * Function to get the cms page title
     *
     * @param int $id cms page id
     * @return array data of cms page
     */

    public static function getCMSMetaTitle($id) {
        $context = Context::getContext();
        $id_shop = (int) $context->shop->id;

        $where_shop = '';
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.12', '>=') == true && $id_shop != false) {
            $where_shop = ' AND cl.`id_shop` = ' . (int) $id_shop;
        }

        $sql = 'SELECT cl.`meta_title`, cl.`link_rewrite`
                FROM `' . _DB_PREFIX_ . 'cms_lang` cl
                INNER JOIN `' . _DB_PREFIX_ . 'cms` c
                ON (cl.`id_cms` = c.`id_cms`)
                WHERE cl.`id_cms` = ' . (int) $id . '
                AND (c.`active` = 1 OR c.`id_cms` = 1)' .
                $where_shop . '
                AND cl.`id_lang` = ' . (int) Context::getContext()->language->id;

        return Db::getInstance()->getRow($sql);
    }

    /* start:changes made by Aayushi Agarwal on 10th July 2020 to add quantity stepper on home and category page */

    /*
     * Function to check if product contain attributes
     */

    public function getProductAttributesGroups($productarray) {
        $attributes_groups = array();
        $this->product = new Product(
                $productarray['id_product'], true, $this->context->language->id, $this->context->shop->id, $this->context
        );
        $attributes_groups = $this->product->getAttributesGroups($this->context->language->id);
        if (!empty($attributes_groups) && $attributes_groups) {
            return "1";
        } else {
            return "0";
        }
    }

    /*
     * Function to find the quantity of the product in the cart
     */

    public function getCartQuantity($product_id) {
        $cart_quantity = 0;
        $summary = $this->context->cart->getSummaryDetails();
        if (!empty($summary)) {
            $cart_products = $summary['products'];
            foreach ($cart_products as $cp => $cpd) {
                if ($cpd['id_product'] == $product_id) {
                    $cart_quantity = (int) $cpd['cart_quantity'];
                }
            }
        }
        return $cart_quantity;
    }

    /* end:changes made by Aayushi Agarwal on 10th July 2020 to add quantity stepper on home and category page */
}
