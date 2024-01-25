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
 * API to get details of product
 */
require_once 'AppCore.php';

class AppGetProductDetails extends AppCore {

    private $product = null;
    private $has_file_field = 0;

    /**
     * This function is trigger whenever this class is called in API
     * This is abstract function in appcore
     *
     * @return json
     */
    public function getPageData() {
       
        if (!(int) Tools::getValue('product_id', 0)) {
            $this->content['product_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('Product id is missing'), 
                    'AppGetProductDetails'
                )
            );
        } else {
            $this->product = new Product(
                Tools::getValue('product_id', 0), 
                true, 
                $this->context->language->id, 
                $this->context->shop->id, 
                $this->context
            );
            if (!Validate::isLoadedObject($this->product)) {
                $this->content['product_result'] = array(
                    'status' => 'failure',
                    'message' => parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), $this->l('Product not found'), 'AppGetProductDetails'
                    )
                );
            } else {
                $this->content['product'] = $this->getProduct();
            }

            /* start:changes started by Tarun Gupta on 8/9/2020 to check the product is available for order */
            $hyperlocal_enable = Configuration::get('KB_MP_HYPERLOCAL');
            $settings = Tools::unSerialize(Configuration::get('KB_MARKETPLACE_CONFIG_HYPERLOCAL'));
            if (Configuration::get('KB_MARKETPLACE') !== false && Configuration::get('KB_MARKETPLACE') == 1 && $hyperlocal_enable && isset($settings['kbmp_enable_hyperlocal']) && $settings['kbmp_enable_hyperlocal']) {
                $this->checkHyperlocalProductAvailability();
            }
            /* end:changes started by Tarun Gupta on 8/9/2020 to check the product is available for order */
        }

        $this->content['install_module'] = '';
        return $this->fetchJSONContent();
    }

    /*
     * Function to check the product is available for order at product page
     */

    public function checkHyperlocalProductAvailability() {
        if (Tools::getValue('product_id') && Tools::getIsset('Latitude') && Tools::getIsset('Longitude')) {
            $product_id = Tools::getValue('product_id');
            /* TO integrate the app with seller product */
            $seller = KbSellerProduct::getSellerByProductId($product_id);
            if (empty($seller)) {
                $is_seller_product = 0;
                $hyperlocal_settings = Tools::unSerialize(Configuration::get('KB_MARKETPLACE_CONFIG_HYPERLOCAL'));
                if ($hyperlocal_settings['kbmp_hyperlocal_radius_unit'] == 1) {
                    $radius_unit = "M";
                } else {
                    $radius_unit = "K";
                }
                $radius = $hyperlocal_settings['kbmp_hyperlocal_radius'];
            } else {
                $is_seller_product = 1;
                $hyperlocal_settings = Tools::unserialize($seller['hyperlocal']);
                if ($hyperlocal_settings[0] == 1) {
                    $radius_unit = "M";
                } else {
                    $radius_unit = "K";
                }
                $radius = $hyperlocal_settings[1];
            }

            $customer_latitude = Tools::getValue('Latitude');
            $customer_longitude = Tools::getValue('Longitude');

            if ($is_seller_product) {
                $shippingAreas = KbSellerShippingArea::getAreaBySellerId($seller['id_seller'], 1);
            } else {
                $shippingAreas = KbSellerShippingArea::getAreaBySellerId(0, 1);
            }
            $checkinrange = 0;
            foreach ($shippingAreas as $key => $shippingArea) {
                $distance = KbSellerShippingArea::getDistance($customer_latitude, $customer_longitude, $shippingArea['latitude'], $shippingArea['longitude'], $radius_unit);
                if ($distance <= $radius) {
                    $checkinrange = 1;
                }
            }
            $products_in_cart = $this->context->cart->getProducts();
            $multi_seller_cart = 0;
            if (!empty($products_in_cart) && $checkinrange == 1) {
                foreach ($products_in_cart as $key => $product) {
                    $product_seller = KbSellerProduct::getSellerByProductId($product['id_product']);
                    if (empty($seller)) {
                        if (!empty($product_seller)) {
                            $multi_seller_cart = 1;
                        }
                    } else {
                        if (empty($product_seller) || $product_seller['id_seller'] != $seller['id_seller']) {
                            $multi_seller_cart = 1;
                        }
                    }
                }
            }
            if ($multi_seller_cart) {
                $this->content['product']['hyperlocal_product_availability'] = array(
                    'is_in_range' => "1",
                    'is_multiseller_cart' => true
                );
            } elseif (!$checkinrange) {
                $this->content['product']['hyperlocal_product_availability'] = array(
                    'is_in_range' => "0",
                    'is_multiseller_cart' => false
                );
            } else {
                $this->content['product']['hyperlocal_product_availability'] = array(
                    'is_in_range' => "1",
                    'is_multiseller_cart' => false
                );
            }
        }
    }

    /**
     * Get Product details
     *
     * @return array product data
     */
    public function getProduct() {
        $product = array();
//        $is_available_product = '0';
//        if (((int) $this->product->quantity >= (int) $this->product->minimal_quantity)  || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//            $is_available_product = '1';
//        } else {
//            if ((int) $this->product->out_of_stock == 1) {
//                $is_available_product = '1';
//            } else if ((int) $this->product->out_of_stock == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                $is_available_product = '1';
//            }
//        }
        $product['id_product'] = (String) $this->product->id;
        $product['name'] = $this->product->name;
         /**
         * Changes made to show the correct Availability Status of the product
         * @date 27-Mar-2023
         * @author Shivam Bansal 
         */
        $product['available_for_order'] = $this->getAvailabilityStatus(
            $this->product->quantity,
            $this->product->minimal_quantity,
            $this->product->out_of_stock,
            $this->product->id
        );
        //changes done by tarun to get the product attributes and cart quantity
        $product['has_attributes'] = $this->getHasProductAttributesGroups($product);
        $product['cart_quantity'] = $this->getCartQuantity($this->product->id);
        //changes over
        $product['show_price'] = $this->product->show_price;
        $product['new_products'] = (isset($this->product->new) && $this->product->new == 1) ? 1 : 0;
        $product['on_sale_products'] = (int) $this->product->on_sale;
        $product['quantity'] = (String) $this->product->quantity;
        $product['minimal_quantity'] = $this->product->minimal_quantity;
        if ($this->product->out_of_stock == 1) {
            $product['allow_out_of_stock'] = 1;
        } elseif ($this->product->out_of_stock == 0) {
            $product['allow_out_of_stock'] = 0;
        } elseif ($this->product->out_of_stock == 2) {
            $out_of_stock = Configuration::get('PS_ORDER_OUT_OF_STOCK');
            if ($out_of_stock == 1) {
                $product['allow_out_of_stock'] = 1;
            } else {
                $product['allow_out_of_stock'] = 0;
            }
        }



        $priceDisplay = Product::getTaxCalculationMethod(0); //(int)$this->context->cookie->id_customer
        if (!$priceDisplay || $priceDisplay == 2) {
            $price = $this->product->getPrice(true, false);
            $price_without_reduction = $this->product->getPriceWithoutReduct(false);
        } else {
            $price = $this->product->getPrice(false, false);
            $price_without_reduction = $this->product->getPriceWithoutReduct(true);
        }
        $disc_per = 0;
        if ($priceDisplay >= 0 && $priceDisplay <= 2) {
            if ($price_without_reduction <= 0 || !$this->product->specificPrice) {
                $product['price'] = $this->formatPrice($price);
                $product['discount_price'] = '';
                $disc_per = 0;
            } else {
                if ($this->product->specificPrice
                    && $this->product->specificPrice['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                    $disc_per = number_format($this->product->specificPrice['reduction'] * 100, 2);
                } elseif ($this->product->specificPrice
                    && $this->product->specificPrice['reduction_type'] == 'amount'
                    && $this->product->specificPrice['reduction'] > 0) {
                    $temp_price = (float) ($this->product->specificPrice['reduction'] * 100);
                    $percent = (float) ($temp_price/ $price_without_reduction);
                    $disc_per = number_format(Tools::ps_round($percent), 2);
                    unset($temp_price);
                }
                $product['price'] = $this->formatPrice($price_without_reduction);
                $product['discount_price'] = $this->formatPrice($price);
            }
        } else {
            $product['price'] = '';
            $product['discount_price'] = '';
            $disc_per = 0;
        }
        if($disc_per == 0) {
            $product['discount_percentage'] = "0";
        } else {
            $product['discount_percentage'] = (string) $disc_per .
                " " .
                parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), 
                    $this->l('% off'), 
                    'AppGetProductDetails'
                );
        }

        $product['images'] = array();
        $temp_images = $this->product->getImages((int) $this->context->language->id);
        $cover = false;
        $images = array();
        foreach ($temp_images as $image) {
            if ($image['cover']) {
                $cover = $image;
            } else {
                $images[] = $image;
            }
        }

        if ($cover) {
            $images = array_merge(array($cover), $images);
        }
        foreach ($images as $image) {
            $product['images'][]['src'] = $this->context->link->getImageLink(
                    /* Changes started by rishabh jain on 3rd sep 2018
                     * To get url encoded image link as per admin setting
                     */
                    urlencode($this->product->link_rewrite),
                    /* Changes over */ ($this->product->id . '-' . $image['id_image']), $this->getImageType('large')
            );
        }
        if (empty($images)) {
            $product['images'][]['src'] = $this->context->link->getImageLink(
                    urlencode($this->product->link_rewrite), $this->context->language->iso_code . '-default', $this->getImageType('large')
            );
        }
        $options = array();
        $combinations = array();
        $attributes = $this->getProductAttributesGroups();

        if (!empty($attributes['groups'])) {
            $index = 0;
            foreach ($attributes['groups'] as $grp_id => $grp) {
                $options[$index]['id'] = (String) $grp_id;
                $options[$index]['title'] = $grp['name'];
                $options[$index]['required'] = "1";
                $item = array();
                foreach ($grp['attributes'] as $key => $group_item) {
                    if ($grp['group_type'] == 'color') {
                        $hex_value = '';
                        if (isset($attributes['colors'][$key]['value'])) {
                            $hex_value = $attributes['colors'][$key]['value'];
                        }
                        $item[] = array(
                            'id' => (String) $key,
                            'value' => $group_item,
                            'hex_value' => $hex_value
                        );
                    } else {
                        $item[] = array(
                            'id' => (String) $key,
                            'value' => $group_item,
                            'hex_value' => ''
                        );
                    }
                }
                $options[$index]['items'] = $item;
                $index++;
            }
        }
        if (!empty($attributes['combinations'])) {
            $index = 0;
            foreach ($attributes['combinations'] as $attr_id => $attr) {
                $combinations[$index]['id_product_attribute'] = $attr_id;
                $combinations[$index]['quantity'] = $attr['quantity'];
                $combinations[$index]['price'] = $attr['price'];
                $combinations[$index]['minimal_quantity'] = $attr['minimal_quantity'];
                $attribute_list = '';
                foreach ($attr['attributes'] as $attribute_id) {
                    $attribute_list .= (int) $attribute_id . '_';
                }
                $attribute_list = rtrim($attribute_list, '_');
                $combinations[$index]['combination_code'] = $attribute_list;
                $index++;
            }
        }
        $product['combinations'] = $combinations;
        $product['options'] = $options;

        $product['description'] = preg_replace('/<iframe.*?\/iframe>/i', '', $this->product->description);
        /* start:changes made by aayushi on 1 DEC 2018 to add Short Description on product page */
        if (Configuration::get('KB_MOBILE_APP_SHORT_DESCRIPTION_SWITCH') == 1) {
            $product['short_description'] = preg_replace('/<iframe.*?\/iframe>/i', '', $this->product->description_short);
        } else {
            $product['short_description'] = '';
        }
        /* end:changes made by aayushi on 1 DEC 2018 to add Short Description on product page */
        $product_info = array();
        // Author - shivam.bansal
        // Date - 03/Feb/2023
        // Changed the condition to check whether the Manufacturer Exists with the ID specified
        if ($this->product->id_manufacturer && !empty(Manufacturer::getNameById($this->product->id_manufacturer))) {
            $product_info[] = array(
                'name' => parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false), $this->l('Brand'), 'AppGetProductDetails'
                ),
                'value' => Manufacturer::getNameById($this->product->id_manufacturer)
            );
        }

        $product_info[] = array(
            'name' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), $this->l('SKU'), 'AppGetProductDetails'
            ),
            'value' => $this->product->reference
        );
        $product_info[] = array(
            'name' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false), $this->l('Condition'), 'AppGetProductDetails'
            ),
            'value' => Tools::ucfirst($this->product->condition)
        );

        $features = $this->product->getFrontFeatures($this->context->language->id);
        if (!empty($features)) {
            foreach ($features as $f) {
                $product_info[] = array('name' => $f['name'], 'value' => $f['value']);
            }
        }
        $product['product_info'] = $product_info;
        $product['accessories'] = $this->getProductAccessories();
        /* start:changes made by Aayushi to integrate related products module */
        $product['related_products'] = $this->getRelatedProducts();
        /* end:changes made by Aayushi to integrate related products module */
        /* start:changes made by Aayushi to integrate Product Comments module */
        if ($this->isProductCommentsEnable()) {
            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
            include_once(_PS_MODULE_DIR_ . 'productcomments/ProductCommentCriterion.php');
            if ($this->context->customer->id) {
                $product['display_read_reviews'] = "1";
                $product['display_write_reviews'] = "1";
            } else {
                $product['display_read_reviews'] = "1";
                /**
                 * Used Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS'); to check whether to show write review or not
                 * TGmay2023 Review-Guest
                 * @date 05-05-2023
                 * @modifier Tanisha Gupta
                 */
                $product['display_write_reviews'] = Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS');
            }
            $product['number_of_reviews'] = ProductComment::getCommentNumber((int) (Tools::getValue('product_id')));
            if ($product['number_of_reviews'] > 0) {
                $avg_rating = ProductComment::getAverageGrade((int) Tools::getValue('product_id'));
                $product['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                $product['number_of_reviews'] = (String) $product['number_of_reviews'];
            } else {
                $product['averagecomments'] = "0";
                $product['number_of_reviews'] = (String) $product['number_of_reviews'];
            }
        } else {
            $product['display_read_reviews'] = "0";
            $product['display_write_reviews'] = "0";
            $product['averagecomments'] = "0";
            $product['number_of_reviews'] = "0";
        }
        /* end:changes made by Aayushi to integrate Product Comments module */
        $product['customization_fields'] = $this->getCustomizationFields();
        $product['pack_products'] = $this->getPackProducts();
        if ($this->has_file_field == 1) {
            $product['has_file_customization'] = '1';
            $product['customization_message'] = parent::getTranslatedTextByFileAndISO(
                            Tools::getValue('iso_code', false), $this->l('This product is not available on App as it has file customization field.'), 'AppGetProductDetails'
            );
        } else {
            $product['has_file_customization'] = '0';
            $product['customization_message'] = '';
        }
        $product['seller_info'] = array();

        //Add seller Information if Marketplace is installed and feature is enable
        $product['seller_info'] = array();
        if ($this->isMarketplaceEnable()) {

            $seller = KbSellerProduct::getSellerByProductId($this->product->id);
            if (is_array($seller) && count($seller) > 0) {
                $sellerRating = Tools::math_round(KbSellerReview::getSellerRating($seller['id_seller']), '1');
                $writeEnabled = KbSellerSetting::getSellerSettingByKey($seller['id_seller'], 'kbmp_enable_seller_review');
                $product['seller_info'][] = array(
                    'seller_id' => $seller['id_seller'],
                    'name' => $seller['title'],
                    'rating' => (String) $sellerRating,
                    'is_write_review_enabled' => $writeEnabled
                );
            }
        }

        $product['product_youtube_url'] = $this->getProductVideoURL($this->product->id);
        $product['product_attachments_array'] = $this->getProductAttachmentURLs($this->product->id);

        $product['is_in_wishlist'] = $this->isProductHasInWishlist($this->product->id);
        $link = new Link();
        $url = $link->getProductLink($product);
        $product['product_url'] = $url;

        /* start:changes started by Aayushi on 3rd July 2019 to integrate booking module */
        if ($this->isBookingCalenderEnable()) {
            require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingFacilities.php');
            require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomCategory.php');
            require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingProduct.php');
            require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingRoomType.php');
            require_once(_PS_MODULE_DIR_ . 'kbbookingcalendar/classes/KbBookingPriceRule.php');
            $product['booking_calender_data'] = $this->getBookingCalenderData();
            if ($product['booking_calender_data']['is_booking_product'] == null) {
                $product['is_booking_calender_data'] = 0;
                $product['booking_calender_data'] = (object) array();
            } else {
                $product['is_booking_calender_data'] = 1;
                $product['customization_fields'] = array();
            }
        } else {
            $product['is_booking_calender_data'] = 0;
        }
        /* end:changes started by Aayushi on 3rd July 2019 to integrate booking module */
        return $product;
    }

    /**
     * Get product youtube video URL
     *
     * @param int $id_product product id
     * @return string youtube video url
     */
    public function getProductVideoURL($id_product) {
        $get_ytdata_qry = 'select * from ' . _DB_PREFIX_ . 'kb_product_youtube_mapping
            where id_product=' . (int) $id_product;
        $yt_data = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($get_ytdata_qry);

        if (isset($yt_data['youtube_url']) && $yt_data['youtube_url'] != '') {
            return $yt_data['youtube_url'];
        } else {
            return '';
        }
    }

    /**
     * Get Virtual product attchements URLS
     *
     * @param int $id_product product id
     * @return array product attachment data
     */
    public function getProductAttachmentURLs($id_product) {
        $final_attachment_data = array();
        $attachments = Product::getAttachmentsStatic((int) $this->context->language->id, $id_product);
        $count = 0;
        foreach ($attachments as $attachment) {
            $final_attachment_data[$count]['download_link'] = $this->context->link->getPageLink('attachment', true, null, "id_attachment=" . $attachment['id_attachment']);
            $final_attachment_data[$count]['file_size'] = Tools::formatBytes($attachment['file_size'], 2);
            $final_attachment_data[$count]['description'] = $attachment['description'];
            $final_attachment_data[$count]['file_name'] = $attachment['file_name'];
            $final_attachment_data[$count]['mime'] = $attachment['mime'];
            $final_attachment_data[$count]['display_name'] = $attachment['name'];
            $count++;
        }
        return $final_attachment_data;
    }

    /**
     * Get details of product attributes groups
     *
     * @return array product attribute group data
     */
    public function getProductAttributesGroups() {
        $colors = array();
        $groups = array();
        $combinations = array();

        $attributes_groups = $this->product->getAttributesGroups($this->context->language->id);

        if (is_array($attributes_groups) && $attributes_groups) {
            foreach ($attributes_groups as $row) {
                // Color management
                if (isset($row['is_color_group']) && $row['is_color_group'] && (isset($row['attribute_color']) && $row['attribute_color']) || (file_exists(_PS_COL_IMG_DIR_ . $row['id_attribute'] . '.jpg'))) {
                    $colors[$row['id_attribute']]['value'] = $row['attribute_color'];
                    $colors[$row['id_attribute']]['name'] = $row['attribute_name'];
                    if (!isset($colors[$row['id_attribute']]['attributes_quantity'])) {
                        $colors[$row['id_attribute']]['attributes_quantity'] = 0;
                    }
                    $colors[$row['id_attribute']]['attributes_quantity'] += (int) $row['quantity'];
                }
                if (!isset($groups[$row['id_attribute_group']])) {
                    $groups[$row['id_attribute_group']] = array(
                        'group_name' => $row['group_name'],
                        'name' => $row['public_group_name'],
                        'group_type' => $row['group_type'],
                        'default' => -1,
                    );
                }

                $attr_g = $row['id_attribute_group'];
                $groups[$attr_g]['attributes'][$row['id_attribute']] = $row['attribute_name'];
                if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
                    $groups[$row['id_attribute_group']]['default'] = (int) $row['id_attribute'];
                }
                if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']])) {
                    $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
                }
                $r_attr = $row['id_attribute_group'];
                $groups[$r_attr]['attributes_quantity'][$row['id_attribute']] += (int) $row['quantity'];

                $combinations[$row['id_product_attribute']]['attributes'][] = (int) $row['id_attribute'];

                //calculate full price for combination
                $priceDisplay = Product::getTaxCalculationMethod(0); //(int)$this->context->cookie->id_customer
                if (!$priceDisplay || $priceDisplay == 2) {
                    $combination_price = $this->product->getPrice(true, $row['id_product_attribute']);
                } else {
                    $combination_price = $this->product->getPrice(false, $row['id_product_attribute']);
                }
                $combinations[$row['id_product_attribute']]['price'] = $this->formatPrice($combination_price);
                $combinations[$row['id_product_attribute']]['quantity'] = (int) $row['quantity'];
                //Start: Changes done by Tanisha on 07-Dec-2022 to send maximum quantity if product has 0 quantity and stock management is disable
                if($combinations[$row['id_product_attribute']]['quantity'] <= 0 && (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0){
                    $combinations[$row['id_product_attribute']]['quantity'] = 999;
                }
                //End here
                $combinations[$row['id_product_attribute']]['minimal_quantity'] = (int) $row['minimal_quantity'];
            }

            // wash attributes list (if some attributes are unavailables and if allowed to wash it)
            if (!Product::isAvailableWhenOutOfStock($this->product->out_of_stock) && Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0) {
                foreach ($groups as &$group) {
                    foreach ($group['attributes_quantity'] as $key => &$quantity) {
                        if ($quantity <= 0) {
                            unset($group['attributes'][$key]);
                        }
                    }
                }

                foreach ($colors as $key => $color) {
                    if ($color['attributes_quantity'] <= 0) {
                        unset($colors[$key]);
                    }
                }
            }
            foreach ($combinations as $id_product_attribute => $comb) {
                $attribute_list = '';
                foreach ($comb['attributes'] as $id_attribute) {
                    $attribute_list .= '\'' . (int) $id_attribute . '\',';
                }
                $attribute_list = rtrim($attribute_list, ',');
                $combinations[$id_product_attribute]['list'] = $attribute_list;
            }
        }

        return array(
            'groups' => $groups,
            'colors' => (count($colors)) ? $colors : false,
            'combinations' => $combinations
        );
    }

    /**
     * Get details of accessories products
     *
     * @return array product accessories information
     */
    public function getProductAccessories() {
        $accessory_products = array();
        $accessories = $this->product->getAccessories($this->context->language->id);

        if ($accessories) {
            $index = 0;
            foreach ($accessories as $accessory) {
//                $is_available = '0';
//                if (((int) $accessory['quantity'] >= (int) $accessory['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $accessory['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $accessory['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $accessory_products[$index] = array(
                    'id' => $accessory['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($accessory['id_product']),
                    'name' => $accessory['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $accessory['quantity'],
                        $accessory['minimal_quantity'],
                        $accessory['out_of_stock'],
                        $accessory['id_product']
                        ),
                    'show_price' => $accessory['show_price'],
                    'new_products' => (isset($accessory['new']) && $accessory['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $accessory['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($accessory['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($accessory),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $accessory['id_category_default'],
                    'price' => $this->formatPrice($accessory['price_without_reduction']),
                    'minimum' => (String) $accessory['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $this->getUrlEncodedImageLink($accessory['link_rewrite']),
                        $accessory['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $accessory_products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$accessory['id_product']);
                    if ($accessory_products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$accessory['id_product']);
                        $accessory_products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $accessory_products[$index]['number_of_reviews'] = (String) $accessory_products[$index]['number_of_reviews'];
                    } else {
                        $accessory_products[$index]['averagecomments'] = "0";
                        $accessory_products[$index]['number_of_reviews'] = (String) $accessory_products[$index]['number_of_reviews'];
                    }
                }
                $accessory_products[$index]['discount_price'] = $this->formatPrice($accessory['price']);
                if (!empty($accessory['specific_prices']) && count($accessory['specific_prices']) > 0) {
                    if ($accessory['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $accessory['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($accessory['price_without_reduction']) {
                            $temp_price = (float) $accessory['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $accessory['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $accessory_products[$index]['discount_percentage'] = "0";
                } else {
                    $accessory_products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
        }
        return $accessory_products;
    }

    /**
     * Get details of customzable fields of customized product
     *
     * @return array product customized data
     */
    public function getCustomizationFields() {
        $customization_fields = array();
        $customization_data = $this->product->getCustomizationFields($this->context->language->id);

        if ($customization_data && is_array($customization_data)) {
            $index = 0;
            foreach ($customization_data as $data) {
                if ($data['type'] == 1) {
                    $customization_fields[$index] = array(
                        'id_customization_field' => $data['id_customization_field'],
                        'required' => $data['required'],
                        'title' => $data['name'],
                        'type' => 'text'
                    );
                    $index++;
                } elseif ($data['type'] == 0 && $data['required'] == 1) {
                    $this->has_file_field = 1;
                }
            }
        }

        return $customization_fields;
    }

    /**
     * Get details of pack products
     *
     * @return array pick items information
     */
    public function getPackProducts() {
        $is_pack = "0";
        $pack_products = array();
        if (Pack::isPack($this->product->id)) {
            $is_pack = "1";
            $pack_items = Pack::getItemTable($this->product->id, $this->context->language->id, true);
            if ($pack_items) {
                $index = 0;
                foreach ($pack_items as $item) {
//                    $is_available = '0';
//                    if (((int) $item['quantity'] >= (int) $item['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                        $is_available = '1';
//                    } else {
//                        if ((int) $item['out_of_stock'] == 1) {
//                            $is_available = '1';
//                        } else if ((int) $item['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                            $is_available = '1';
//                        }
//                    }
                    $pack_products[$index] = array(
                        'id' => $item['id_product'],
                        'is_in_wishlist' => $this->isProductHasInWishlist($item['id_product']),
                        'name' => $item['name'],
                         /**
                         * Changes made to show the correct Availability Status of the product
                         * @date 27-Mar-2023
                         * @author Shivam Bansal 
                         */
                        'available_for_order' => $this->getAvailabilityStatus(
                            $item['quantity'],
                            $item['minimal_quantity'],
                            $item['out_of_stock'],
                            $item['id_product']
                        ),
                        'show_price' => $item['show_price'],
                        'new_products' => (isset($item['new']) && $item['new'] == 1) ? 1 : 0,
                        'on_sale_products' => (int) $item['on_sale'],
                        'cart_quantity' => $this->getCartQuantity($item['id_product']),
                        'has_attributes' => $this->getHasProductAttributesGroups($item),
                        'image_contentMode' => 'scaleAspectFit',
                        'ClickActivityName' => 'CategoryProductsActivity',
                        'category_id' => $item['id_category_default'],
                        'price' => $this->formatPrice($item['price_without_reduction']),
                        'minimum' => (String) $item['minimal_quantity'],
                        'src' => $this->context->link->getImageLink(
                            $this->getUrlEncodedImageLink($item['link_rewrite']),
                            $item['id_image'],
                            $this->getImageType('large')
                        )
                    );
                    if ($this->isProductCommentsEnable()) {
                        include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                        include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                        $pack_products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$accessory['id_product']);
                        if ($pack_products[$index]['number_of_reviews'] > 0) {
                            $avg_rating = ProductComment::getAverageGrade((int)$item['id_product']);
                            $pack_products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                            $pack_products[$index]['number_of_reviews'] = (String) $pack_products[$index]['number_of_reviews'];
                        } else {
                            $pack_products[$index]['averagecomments'] = "0";
                            $pack_products[$index]['number_of_reviews'] = (String) $pack_products[$index]['number_of_reviews'];
                        }
                    }
                    $pack_products[$index]['discount_price'] = $this->formatPrice($item['price']);
                    if (!empty($item['specific_prices']) && count($item['specific_prices']) > 0) {
                        if ($item['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                            $temp_price = (float) $item['specific_prices']['reduction'] * 100;
                            $disc_per = number_format($temp_price, 2);
                            unset($temp_price);
                        } else {
                            if ($item['price_without_reduction']) {
                                $temp_price = (float) $item['specific_prices']['reduction'] * 100;
                                $percent = (float) ($temp_price / $item['price_without_reduction']);
                                unset($temp_price);
                            } else {
                                $percent = 0;
                            }
                            $disc_per = number_format(Tools::ps_round($percent), 2);
                        }
                    } else {
                        $disc_per = 0;
                    }
                    if($disc_per == 0) {
                        $pack_products[$index]['discount_percentage'] = "0";
                    } else {
                        $pack_products[$index]['discount_percentage'] = (string) $disc_per .
                                " " .
                                parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false), 
                                    $this->l('% off'), 
                                    'AppGetProductDetails'
                                );
                    }
                    $index++;
                }
            }
        }
        return $pack_products;
    }

    /* start:changes started by Aayushi on 3rd July 2019 to integrate booking module */

    public function getRoomTypes($id_product, $id_category) {
        $room_types = array();
        $kbbooking = Module::getInstanceByName('Kbbookingcalendar');
        $hotel_rooms = KbBookingProduct::getHotelProductRoomsByID($id_product);
        foreach ($hotel_rooms as $hotel_rooms_key => $hotel_rooms_data) {
            $room_types_data = KbBookingRoomType::getAvailableRoomTypeByID($hotel_rooms_data['id_room_type']);
            $room_types[$hotel_rooms_key]['id'] = (int) $hotel_rooms_data['id_room_category'];
            $room_types[$hotel_rooms_key]['category'] = KbBookingRoomCategory::getRoomCategoryNameByID($hotel_rooms_data['id_room_category']);
            $room_types[$hotel_rooms_key]['type'] = $room_types_data['room_name'];
            $room_types[$hotel_rooms_key]['price'] = $this->formatPrice($hotel_rooms_data['price']);
            $room_images = json_decode($hotel_rooms_data['upload_images']);
            if (!empty($room_images)) {
                $room_types[$hotel_rooms_key]['image_url'] = $room_images[0]->path;
            } else {
                $room_types[$hotel_rooms_key]['image_url'] = '';
            }
        }
        return $room_types;
    }

    public function getFacilityHtml($id_booking_product) {

        $book_faci_tpl = '';
        $kbbooking = Module::getInstanceByName('Kbbookingcalendar');
        $booking_facilities = KbBookingFacilities::getFacilitiesMappedwithProduct($id_booking_product);
        //

        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }

        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $module_dir = '"' . _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = '"' . _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        $this->context->smarty->assign('kbmobileapp_dir', $module_dir);
        $this->module_url = $module_dir . 'kbbookingcalendar/';
        //$this->context->smarty->assign('root_dir' , $module_dir);
        $this->context->smarty->assign('mod_dir', $module_dir);
        $this->context->smarty->assign('mod_path', $this->module_url);
        if (!empty($booking_facilities)) {
            $this->context->smarty->assign(
                    array(
                        'booking_facilities' => $booking_facilities,
                    )
            );
            $book_faci_tpl = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'kbmobileapp' . '/views/templates/hook/product_facilities.tpl');
        }
        $book_faci_tpl = preg_replace("/[\n\r]/", "", $book_faci_tpl);
        return $book_faci_tpl;
    }

    public function getBookingCalenderData() {
        $data = array();
        $is_quantity_enabled = 0;
        $sql = 'SELECT a.* FROM ' . _DB_PREFIX_ . 'kb_booking_product' . ' as a INNER JOIN '
                . _DB_PREFIX_ . 'product p on (a.id_product=p.id_product) INNER JOIN '
                . _DB_PREFIX_ . 'product_shop ps on (a.id_product=ps.id_product AND '
                . 'ps.id_shop=' . (int) $this->context->shop->id . ') WHERE a.active=1 AND '
                . 'a.id_product=' . (int) $this->product->id;
        $product_Booking_Data = Db::getInstance()->getRow($sql);
        $kb_setting = json_decode(Configuration::get('KB_BOOKING_CALENDAR_GENERAL_SETTING'), true);
        $product_type = $product_Booking_Data['product_type'];
        $data['is_booking_calender_enable'] = 1;
        $data['is_booking_product'] = $product_Booking_Data['active'];
        $data['is_map_enable'] = $product_Booking_Data['enable_product_map'];
        $data['address'] = $product_Booking_Data['address'];
        $data['longitude'] = (float) $product_Booking_Data['longitude'];
        $data['latitude'] = (float) $product_Booking_Data['latitude'];
        $data['period_type'] = $product_Booking_Data['period_type'];
        $data['id_booking_product'] = (int) $product_Booking_Data['id_booking_product'];
        $data['service_type'] = $product_Booking_Data['service_type'];
        //die(print_r($product_Booking_Data));
        $data['map_api_key'] = $kb_setting['api_key'];
        $price_rule = array();
        $kb_db_data = json_decode(Configuration::get('KB_BOOKING_CALENDAR_GENERAL_SETTING'), true);
        if ($kb_db_data['display_price_rule']) {
            $price_rule_applicable = KbBookingPriceRule::isPriceRuleApplicable((int) $this->product->id);

            if (isset($price_rule_applicable) && !empty($price_rule_applicable)) {
                foreach ($price_rule_applicable as $price_rule_applicable_key => $rule) {
                    $message = parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false), $this->l('Discount of '), 'AppGetProductDetails'
                    );
                    if ($rule['reduction_type'] == 'percentage') {
                        $discount = $rule['reduction'] . '%';
                    } else {
                        $discount = $this->formatPrice($rule['reduction']);
                    }
                    $message = $message . $discount;
                    if ($rule['date_selection'] == 'date_range') {
                        $message.= parent::getTranslatedTextByFileAndISO(
                                        Tools::getValue('iso_code', false), $this->l(' from '), 'AppGetProductDetails'
                                ) . $rule['start_date'] . parent::getTranslatedTextByFileAndISO(
                                        Tools::getValue('iso_code', false), $this->l(' to '), 'AppGetProductDetails'
                                ) . $rule['end_date'];
                    } else {
                        $message.= parent::getTranslatedTextByFileAndISO(
                                        Tools::getValue('iso_code', false), $this->l(' on '), 'AppGetProductDetails'
                                ) . $rule['particular_date'];
                    }
                    $price_rule[$price_rule_applicable_key] = array(
                        'name' => $rule['name'],
                        'message' => $message,
                    );
                }
            }
        }
        $data['price_rule'] = $price_rule;


        if ($product_type == 'hotel_booking') {
            $kbbooking = Module::getInstanceByName('Kbbookingcalendar');
            $room_category = KbBookingRoomCategory::getAvailableRoomCategory();
            $data['product_type'] = $product_Booking_Data['product_type'];
            $data['hotel_data'] = array();
            $data['hotel_data']['is_add_to_cart_enabled'] = 0;
            if (!empty($room_category)) {
                foreach ($room_category as $room_category_key => $room_category_data) {
                    $data['hotel_data']['categories'][] = array(
                        'id_category_room_type' => (int) $room_category_data['id_booking_category'],
                        'name' => $room_category_data['name'],
                    );
                }
            } else {
                $data['hotel_data']['categories'] = array();
            }
            $is_quantity_enabled = 0;
            //$data['hotel_data']['hide_quantity_field'] = 1;
            $data['hotel_data']['star_rating'] = (int) $product_Booking_Data['star_rating'];
            $id_product = 0;
            $id_category = 0; //ask why it has been used
            $data['hotel_data']['room_types'] = $this->getRoomTypes((int) $data['id_booking_product'], (int) $id_category);
            $data['hotel_data']['facility_html'] = $this->getFacilityHtml((int) $data['id_booking_product']);
            if (empty($data['hotel_data']['facility_html'])) {
                $data['hotel_data']['is_facility_html'] = 0;
            } else {
                $data['hotel_data']['is_facility_html'] = 1;
            }
        } else if ($product_type == 'daily_rental') {
            //die(print_r($product_Booking_Data));
            $data['product_type'] = $product_Booking_Data['product_type'];
            $data['daily_rental'] = array();
            $data['daily_rental']['facility_html'] = $this->getFacilityHtml((int) $data['id_booking_product']);
            if (empty($data['daily_rental']['facility_html'])) {
                $data['daily_rental']['is_facility_html'] = 0;
            } else {
                $data['daily_rental']['is_facility_html'] = 1;
            }
            //$data['daily_rental']['hide_quantity_field'] = 1;
            $data['daily_rental']['is_add_to_cart_enabled'] = 1;
            if ($product_Booking_Data['period_type'] == "date_time") {
                $data['daily_rental']['is_time_field'] = 1;
                $is_quantity_enabled = 1;
            } else {
                $data['daily_rental']['is_time_field'] = 0;
                $is_quantity_enabled = 1;
            }
            $date_data = json_decode($product_Booking_Data['date_details'], true);

            $today_date = date("Y-m-d");
            if ((isset($date_data)) && !empty($date_data)) {
                $from_Dates_Array = array();
                $to_Dates_Array = array();
                foreach ($date_data as $data_time_slot => $date_time_data) {
                    $from_Dates_Array[] = $date_time_data['from_date'];
                    $to_Dates_Array[] = $date_time_data['to_date'];
                }
                if (min($from_Dates_Array) < $today_date) {
                    $from_date = $today_date;
                } else {
                    $from_date = min($from_Dates_Array);
                }
                $data['daily_rental']['enabled_dates'] = array(
                    'from_date' => $from_date,
                    'to_date' => max($to_Dates_Array)
                );
            } else {
                $data['daily_rental']['enabled_dates'] = array(
                    'from_date' => $today_date,
                    'to_date' => ''
                );
            }
        } else if ($product_type == 'hourly_rental') {
            $data['product_type'] = $product_Booking_Data['product_type'];
            $data['hourly_rental'] = array();
            $is_quantity_enabled = 1;
            //$data['hourly_rental']['hide_quantity_field'] = 1;
            $data['hourly_rental']['is_add_to_cart_enabled'] = 1;
            if ($product_Booking_Data['period_type'] == "date_time") {
                $data['hourly_rental']['is_time_field'] = 1;
            } else {
                $data['hourly_rental']['is_time_field'] = 1;
            }
            $date_data = json_decode($product_Booking_Data['date_details'], true);
            $today_date = date("Y-m-d");
            if ((isset($date_data)) && !empty($date_data)) {
                $from_Dates_Array = array();
                $to_Dates_Array = array();
                foreach ($date_data as $data_time_slot => $date_time_data) {
                    $from_Dates_Array[] = $date_time_data['from_date'];
                    $to_Dates_Array[] = $date_time_data['to_date'];
                }


                if (min($from_Dates_Array) < $today_date) {
                    $from_date = $today_date;
                } else {
                    $from_date = min($from_Dates_Array);
                }
                $data['hourly_rental']['enabled_dates'] = array(
                    'from_date' => $from_date,
                    'to_date' => max($to_Dates_Array)
                );
            } else {
                $data['hourly_rental']['enabled_dates'] = array(
                    'from_date' => $today_date,
                    'to_date' => ''
                );
            }
            $data['hourly_rental']['facility_html'] = $this->getFacilityHtml((int) $data['id_booking_product']);
            if (empty($data['hourly_rental']['facility_html'])) {
                $data['hourly_rental']['is_facility_html'] = 0;
            } else {
                $data['hourly_rental']['is_facility_html'] = 1;
            }
        } else if ($product_type == 'appointment') {
            $data['product_type'] = 'appointment';
            $data['appointment'] = array();
            if ($product_Booking_Data['period_type'] == "date_time") {
                $data['appointment']['is_time_field'] = 1;
                $is_quantity_enabled = 1;
            } else {
                $data['appointment']['is_time_field'] = 0;
                $is_quantity_enabled = 1;
            }
            $data['appointment']['is_facility_html'] = 0;

            //$data['appointment']['hide_quantity_field'] = 1;
            $data['appointment']['is_add_to_cart_enabled'] = 1;
            $date_data = json_decode($product_Booking_Data['date_details'], true);
            $today_date = date("Y-m-d");
            if ((isset($date_data)) && !empty($date_data)) {
                $from_Dates_Array = array();
                $to_Dates_Array = array();
                foreach ($date_data as $data_time_slot => $date_time_data) {
                    $from_Dates_Array[] = $date_time_data['from_date'];
                    $to_Dates_Array[] = $date_time_data['to_date'];
                }
                if (min($from_Dates_Array) < $today_date) {
                    $from_date = $today_date;
                } else {
                    $from_date = min($from_Dates_Array);
                }
                $data['appointment']['enabled_dates'] = array(
                    'from_date' => $from_date,
                    'to_date' => max($to_Dates_Array)
                );
            } else {
                $data['appointment']['enabled_dates'] = array(
                    'from_date' => $today_date,
                    'to_date' => ''
                );
            }
        }
        $data['is_quantity_enabled'] = $is_quantity_enabled;
        return $data;
    }

    /* end:changes started by Aayushi on 3rd July 2019 to integrate booking module */
    /* start:changes made by Aayushi to integrate related products module */

    /**
     * Get details of related products
     *
     * @return array product related information
     */
    public function getRelatedProducts() {
        $products = array();
        if ($this->isRelatedProductEnable()) {
            $config = Configuration::get('kb_related_products_settings', null, null, $this->context->shop->id);
            $values = json_decode($config);
            $related_products = array();
            $sql = 'SELECT visits FROM ' . _DB_PREFIX_ . 'kb_top_visited where id_product = ' . (int) Tools::getValue('product_id');
            $result = Db::getInstance()->getRow($sql);
            $has_products = "1";
            if (!empty($result)) {
                $sql = 'UPDATE ' . _DB_PREFIX_ . 'kb_top_visited SET visits =' . (int) ($result['visits'] + 1) . ' WHERE id_product = ' . (int) Tools::getValue('product_id');
                Db::getInstance()->execute($sql);
            } else {
                $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'kb_top_visited (`id_kb_product`, `id_product`, `visits`) VALUES (NULL, ' . (int) Tools::getValue('product_id') . ', 1)';
                Db::getInstance()->execute($sql);
            }
            $prod_obj = new Product((int) Tools::getValue('product_id'));

            $method_selected = $values->method_product_page;
            if ($method_selected == 1) {
                $products = $this->getTopVisistedProducts($config);
            } else if ($method_selected == 2) {
                $products = $this->getBestSellerProducts($config);
            } else if ($method_selected == 3) {
                $products = $this->getNewProducts($config);
            } else if ($method_selected == 4) {
                $products = $this->getUserHistoryProducts($config);
            } else if ($method_selected == 5) {
                $products = $this->getProductsWithCategory($config);
            } else if ($method_selected == 6) {
                $products = $this->getProductsOrderedWithThisProduct($config);
            } else if ($method_selected == 7) {
                $products = $this->getProductsWithFeatures($config);
            } else {
                $products = $this->getProductsWithFeaturesValues($config);
            }
            if (empty($products)) {
                $sql = "SELECT method FROM `" . _DB_PREFIX_ . "kb_positions` where page ='product' ORDER BY pos";
                $method_positions = Db::getInstance()->executeS($sql);
                foreach ($method_positions as $method_pos) {
                    if ($method_pos['method'] != $method_selected) {
                        if ($method_pos['method'] == 1) {
                            $products = $this->getTopVisistedProducts($config);
                        } else if ($method_pos['method'] == 2) {
                            $products = $this->getBestSellerProducts($config);
                        } else if ($method_pos['method'] == 3) {
                            $products = $this->getNewProducts($config);
                        } else if ($method_pos['method'] == 4) {
                            $products = $this->getUserHistoryProducts($config);
                        } else if ($method_pos['method'] == 5) {
                            $products = $this->getProductsWithCategory($config);
                        } else if ($method_pos['method'] == 6) {
                            $products = $this->getProductsOrderedWithThisProduct($config);
                        } else if ($method_pos['method'] == 7) {
                            $products = $this->getProductsWithFeatures($config);
                        } else if ($method_pos['method'] == 8) {
                            $products = $this->getProductsWithFeaturesValues($config);
                        }
                    }
                    if (!empty($products)) {
                        break;
                    }
                }
            }
        }
        return $products;
    }

    /**
     * Get new products data
     *
     * @return array new products data
     */
    public function getNewProducts($config) {
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $number_of_products = $total_prod;
        $module_name = 'blocknewproducts';
        $data = array();
        if (!Module::isInstalled($module_name) || !Module::isEnabled($module_name)) {
            $this->writeLog('New Arrvial module is either inactive or not installed.');
            return $data;
        }

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
//                    $is_available = '0';
//                    if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                        $is_available = '1';
//                    } else {
//                        if ((int) $res['out_of_stock'] == 1) {
//                            $is_available = '1';
//                        } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                            $is_available = '1';
//                        }
//                    }
                    $products[$index] = array(
                        'id' => $res['id_product'],
                        'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                        'name' => $res['name'],
                         /**
                         * Changes made to show the correct Availability Status of the product
                         * @date 27-Mar-2023
                         * @author Shivam Bansal 
                         */
                        'available_for_order' => $this->getAvailabilityStatus(
                            $res['quantity'],
                            $res['minimal_quantity'],
                            $res['out_of_stock'],
                            $res['id_product']
                        ),
                        'show_price' => $res['show_price'],
                        'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                        'on_sale_products' => (int) $res['on_sale'],
                        'cart_quantity' => $this->getCartQuantity($res['id_product']),
                        'has_attributes' => $this->getHasProductAttributesGroups($res),
                        'image_contentMode' => 'scaleAspectFit',
                        'ClickActivityName' => 'CategoryProductsActivity',
                        'category_id' => $res['id_category_default'],
                        'price' => $this->formatPrice($res['price_without_reduction']),
                        'minimum' => (String) $res['minimal_quantity'],
                        'src' => $this->context->link->getImageLink(
                            $res['link_rewrite'],
                            $res['id_image'],
                            $this->getImageType('large')
                        )
                    );
                    if ($this->isProductCommentsEnable()) {
                        include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                        include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                        $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                        if ($products[$index]['number_of_reviews'] > 0) {
                            $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                            $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                            $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                        } else {
                            $products[$index]['averagecomments'] = "0";
                            $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                        }
                    }
                    $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                    if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                        if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $disc_per = number_format($temp_price, 2);
                            unset($temp_price);
                        } else {
                            if ($res['price_without_reduction']) {
                                $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                $percent = (float) ($temp_price / $res['price_without_reduction']);
                                unset($temp_price);
                            } else {
                                $percent = 0;
                            }
                            $disc_per = number_format(Tools::ps_round($percent), 2);
                        }
                    } else {
                        $disc_per = 0;
                    }
                    if($disc_per == 0) {
                        $products[$index]['discount_percentage'] = "0";
                    } else {
                        $products[$index]['discount_percentage'] = (string) $disc_per .
                                " " .
                                parent::getTranslatedTextByFileAndISO(
                                    Tools::getValue('iso_code', false), 
                                    $this->l('% off'), 
                                    'AppGetProductDetails'
                                );
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
     * Get details of best seller products
     *
     * @return array product customized data
     */
    public function getBestSellerProducts($config) {
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $number_of_products = $total_prod;
        $module_name = 'blockbestsellers';
        $data = array();
        if (!Module::isInstalled($module_name) || !Module::isEnabled($module_name)) {
            $this->writeLog('Bestseller module is either inactive or not installed.');
            return $data;
        }

        $results = array();
        $products = array();
        if (!Configuration::get('PS_CATALOG_MODE')) {
            if ($results = ProductSale::getBestSalesLight(
                            (int) $this->context->language->id, 0, (int) $number_of_products
                    )) {
                if (!empty($results)) {
                    $index = 0;
                    foreach ($results as $res) {
                        if (Tools::getValue('product_id') != $res['id_product']) {
//                            $is_available = '0';
//                            if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                                $is_available = '1';
//                            } else {
//                                if ((int) $res['out_of_stock'] == 1) {
//                                    $is_available = '1';
//                                } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                                    $is_available = '1';
//                                }
//                            }
                            $products[$index] = array(
                                'id' => $res['id_product'],
                                'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                                'name' => $res['name'],
                                 /**
                                 * Changes made to show the correct Availability Status of the product
                                 * @date 27-Mar-2023
                                 * @author Shivam Bansal 
                                 */
                                'available_for_order' => $this->getAvailabilityStatus(
                                    $res['quantity'],
                                    $res['minimal_quantity'],
                                    $res['out_of_stock'],
                                    $res['id_product']
                                ),
                                'show_price' => $res['show_price'],
                                'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                                'on_sale_products' => (int) $res['on_sale'],
                                'cart_quantity' => $this->getCartQuantity($res['id_product']),
                                'has_attributes' => $this->getHasProductAttributesGroups($res),
                                'image_contentMode' => 'scaleAspectFit',
                                'ClickActivityName' => 'CategoryProductsActivity',
                                'category_id' => $res['id_category_default'],
                                'price' => $this->formatPrice($res['price_without_reduction']),
                                'minimum' => (String) $res['minimal_quantity'],
                                'src' => $this->context->link->getImageLink(
                                    $res['link_rewrite'],
                                    $res['id_image'],
                                    $this->getImageType('large')
                                )
                            );
                            if ($this->isProductCommentsEnable()) {
                                include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                                include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                                $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                                if ($products[$index]['number_of_reviews'] > 0) {
                                    $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                                    $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                                    $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                                } else {
                                    $products[$index]['averagecomments'] = "0";
                                    $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                                }
                            }
                            $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                            if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                                if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                                    $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                    $disc_per = number_format($temp_price, 2);
                                    unset($temp_price);
                                } else {
                                    if ($res['price_without_reduction']) {
                                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                                        $percent = (float) ($temp_price / $res['price_without_reduction']);
                                        unset($temp_price);
                                    } else {
                                        $percent = 0;
                                    }
                                    $disc_per = number_format(Tools::ps_round($percent), 2);
                                }
                            } else {
                                $disc_per = 0;
                            }
                            if($disc_per == 0) {
                                $products[$index]['discount_percentage'] = "0";
                            } else {
                                $products[$index]['discount_percentage'] = (string) $disc_per .
                                        " " .
                                        parent::getTranslatedTextByFileAndISO(
                                            Tools::getValue('iso_code', false), 
                                            $this->l('% off'), 
                                            'AppGetProductDetails'
                                        );
                            }
                            $index++;
                        }
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
     * Get details of related ordered products
     *
     * @return array related ordered products data
     */
    public function getProductsOrderedWithThisProduct($config) {
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $products_count = $total_prod;
        $products = array();
        if ($config->stock_availability == 1) {
            $sql = 'SELECT k.id_order '
                    . 'FROM ' . _DB_PREFIX_ . 'order_detail k '
                    . 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` p ON (k.product_id=p.id_product) '
                    . 'where p.id_product_attribute=0 AND p.quantity>0 AND k.product_id=' . (int) Tools::getValue('product_id') . ' '
                    . 'limit ' . (int) $products_count;
            $result = Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT id_order '
                    . 'FROM ' . _DB_PREFIX_ . 'order_detail '
                    . 'where product_id=' . (int) Tools::getValue('product_id') . ' '
                    . 'limit ' . (int) $products_count;
            $result = Db::getInstance()->executeS($sql);
        }

        $final = array();

        if (!empty($result)) {
            foreach ($result as $key => $res) {
                $order_obj = new Order($res['id_order']);
                $products = $order_obj->getProducts();
                foreach ($products as $key => $pro) {
                    if ($pro['product_id'] != Tools::getValue('product_id')) {
                        $product_available = Db::getInstance()->getValue('SELECT id_product '
                                . 'FROM ' . _DB_PREFIX_ . 'product '
                                . 'WHERE id_product = ' . (int) $pro['product_id']);
                        if (!empty($product_available)) {
                            $kb_array = array();
                            $kb_array['id_product'] = $pro['product_id'];
                            $final[] = $kb_array;
                        }
                    }
                }
            }
        }

        $data = array_column($final, 'id_product');
        $results = $this->getCustomProducts(
                (int) $this->context->language->id, $data, 0, $products_count, false, null, null, false, false, $this->context
        );
        $products = array();
        if (!empty($results)) {
            $results = Product::getProductsProperties((int) $this->context->language->id, $results);
            $index = 0;
            foreach ($results as $res) {
//                $is_available = '0';
//                if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $res['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $products[$index] = array(
                    'id' => $res['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                    'name' => $res['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $res['quantity'],
                        $res['minimal_quantity'],
                        $res['out_of_stock'],
                        $res['id_product']
                    ),
                    'show_price' => $res['show_price'],
                    'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $res['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($res['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($res),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $res['id_category_default'],
                    'price' => $this->formatPrice($res['price_without_reduction']),
                    'minimum' => (String) $res['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $res['link_rewrite'],
                        $res['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                    if ($products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                        $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    } else {
                        $products[$index]['averagecomments'] = "0";
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    }
                }
                $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                    if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($res['price_without_reduction']) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $res['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $products[$index]['discount_percentage'] = "0";
                } else {
                    $products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
        }
        return $products;
    }

    /**
     * Get details of products with same features
     *
     * @return array products with same features data
     */
    public function getProductsWithFeatures($config) {
        $product_obj = new Product((int) Tools::getValue('product_id'));
        $id_features = $product_obj->getFeatures();
        $data = array();
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $products_count = $total_prod;
        $products = array();
        foreach ($id_features as $key => $id_feature) {
            if ($config->stock_availability == 1) {
                $sql = 'SELECT DISTINCT k.id_product '
                        . 'FROM ' . _DB_PREFIX_ . 'feature_product k '
                        . 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` p ON (k.id_product=p.id_product) '
                        . 'where p.id_product_attribute=0 AND p.quantity>0 '
                        . 'AND k.id_feature_value=' . (int) $id_feature['id_feature_value'] . ' AND k.id_product !=' . (int) Tools::getValue('product_id') . ' '
                        . 'limit ' . (int) $products_count;
                $data = Db::getInstance()->executeS($sql);
            } else {
                $sql = 'SELECT DISTINCT id_product '
                        . 'FROM ' . _DB_PREFIX_ . 'feature_product '
                        . 'where id_feature_value=' . (int) $id_feature['id_feature_value'] . ' '
                        . 'AND id_product !=' . (int) Tools::getValue('product_id') . ' '
                        . 'limit ' . (int) $products_count;
                $data = Db::getInstance()->executeS($sql);
            }
        }
        $data = array_column($data, 'id_product');
        $results = $this->getCustomProducts(
                (int) $this->context->language->id, $data, 0, $products_count, false, null, null, false, false, $this->context
        );
        if (!empty($results)) {
            $results = Product::getProductsProperties((int) $this->context->language->id, $results);
            $index = 0;
            foreach ($results as $res) {
//                $is_available = '0';
//                if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $res['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $products[$index] = array(
                    'id' => $res['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                    'name' => $res['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $res['quantity'],
                        $res['minimal_quantity'],
                        $res['out_of_stock'],
                        $res['id_product']
                    ),
                    'show_price' => $res['show_price'],
                    'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $res['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($res['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($res),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $res['id_category_default'],
                    'price' => $this->formatPrice($res['price_without_reduction']),
                    'minimum' => (String) $res['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $res['link_rewrite'],
                        $res['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                    if ($products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                        $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    } else {
                        $products[$index]['averagecomments'] = "0";
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    }
                }
                $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                    if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($res['price_without_reduction']) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $res['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $products[$index]['discount_percentage'] = "0";
                } else {
                    $products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
            $data = $products;
        }
        return $data;
    }

    /*
     * Function to check if product contain attributes
     */

    public function getHasProductAttributesGroups($productarray) {
        $attributes_groups = array();
        /**
         * Made changes to fix issue wishlist value is always false even if the product is added to the wishlist.
         * Changed $this->product to $prod_obj
         * TGapr2023 product_object
         * @date 28-04-2023
         * @modifier Tanisha Gupta
         */
        $prod_obj = new Product(
            $productarray['id_product'], 
            true, 
            $this->context->language->id, 
            $this->context->shop->id, 
            $this->context
        );
        $attributes_groups = $prod_obj->getAttributesGroups($this->context->language->id);
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
        return (int) $cart_quantity;
    }

    /* end:changes made by Aayushi Agarwal on 10th July 2020 to add quantity stepper on home and category page */

    /**
     * Get top visited products data
     *
     * @return array top visited products data
     */
    public function getTopVisistedProducts($config) {
        $config = json_decode($config);
        $products_count = $config->max_products_product_page;
        $products = array();
        if ($config->stock_availability == 1) {
            $sql = "SELECT k.id_product "
                    . "FROM `" . _DB_PREFIX_ . "kb_top_visited` k "
                    . "INNER JOIN `" . _DB_PREFIX_ . "stock_available` p ON (k.id_product=p.id_product) "
                    . "WHERE p.id_product_attribute=0 "
                    . "AND p.quantity>0 AND k.id_product != " . (int) Tools::getValue('product_id') . " "
                    . "ORDER BY visits DESC " . ' '
                    . 'limit ' . (int) $products_count;
            $data = Db::getInstance()->executeS($sql);
        } else {
            $sql = "SELECT id_product "
                    . "FROM `" . _DB_PREFIX_ . "kb_top_visited` "
                    . "where id_product != " . (int) Tools::getValue('product_id') . " "
                    . "ORDER BY visits DESC " . ' '
                    . 'limit ' . (int) $products_count;
            $data = Db::getInstance()->executeS($sql);
        }
        $data = array_column($data, 'id_product');
        $results = $this->getCustomProducts(
                (int) $this->context->language->id, $data, 0, $products_count, false, null, null, false, false, $this->context
        );
        if (!empty($results)) {
            $results = Product::getProductsProperties((int) $this->context->language->id, $results);
            $index = 0;
            foreach ($results as $res) {
//                $is_available = '0';
//                if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $res['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $products[$index] = array(
                    'id' => $res['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                    'name' => $res['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $res['quantity'],
                        $res['minimal_quantity'],
                        $res['out_of_stock'],
                        $res['id_product']
                    ),
                    'show_price' => $res['show_price'],
                    'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $res['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($res['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($res),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $res['id_category_default'],
                    'price' => $this->formatPrice($res['price_without_reduction']),
                    'minimum' => (String) $res['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $res['link_rewrite'],
                        $res['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                    if ($products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                        $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    } else {
                        $products[$index]['averagecomments'] = "0";
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    }
                }
                $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                    if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($res['price_without_reduction']) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $res['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $products[$index]['discount_percentage'] = "0";
                } else {
                    $products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
            $data = $products;
        }
        return $data;
    }

    public function getProductsWithFeaturesValues($config) {
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $products_count = $total_prod;
        $product_obj = new Product((int) Tools::getValue('product_id'));
        $id_feature_values = $product_obj->getFeatures();
        $data = array();
        $products = array();
        foreach ($id_feature_values as $key => $id_feature_value) {
            if ($config->stock_availability == 1) {
                $sql = 'SELECT DISTINCT k.id_product '
                        . 'FROM ' . _DB_PREFIX_ . 'feature_product k '
                        . 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` p ON (k.id_product=p.id_product) '
                        . 'where p.id_product_attribute=0 AND p.quantity>0 '
                        . 'AND k.id_feature_value=' . (int) $id_feature_value['id_feature_value'] . ' AND k.id_product !=' . (int) Tools::getValue('product_id') . ' '
                        . 'limit ' . (int) $products_count;
                $data = Db::getInstance()->executeS($sql);
            } else {
                $sql = 'SELECT DISTINCT id_product '
                        . 'FROM ' . _DB_PREFIX_ . 'feature_product '
                        . 'where id_feature_value=' . (int) $id_feature_value['id_feature_value'] . ' '
                        . 'AND id_product !=' . (int) Tools::getValue('product_id') . ' '
                        . 'limit ' . (int) $products_count;
                $data = Db::getInstance()->executeS($sql);
            }
        }
        $data = array_column($data, 'id_product');
        $results = $this->getCustomProducts(
                (int) $this->context->language->id, $data, 0, $products_count, false, null, null, false, false, $this->context
        );
        if (!empty($results)) {
            $results = Product::getProductsProperties((int) $this->context->language->id, $results);
            $index = 0;
            foreach ($results as $res) {
//                $is_available = '0';
//                if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $res['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $products[$index] = array(
                    'id' => $res['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                    'name' => $res['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $res['quantity'],
                        $res['minimal_quantity'],
                        $res['out_of_stock'],
                        $res['id_product']
                    ),
                    'show_price' => $res['show_price'],
                    'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $res['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($res['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($res),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $res['id_category_default'],
                    'price' => $this->formatPrice($res['price_without_reduction']),
                    'minimum' => (String) $res['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $res['link_rewrite'],
                        $res['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                    if ($products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                        $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    } else {
                        $products[$index]['averagecomments'] = "0";
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    }
                }
                $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                    if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($res['price_without_reduction']) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $res['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $products[$index]['discount_percentage'] = "0";
                } else {
                    $products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
            $data = $products;
        }
        return $data;
    }

    /**
     * Get new products data
     *
     * @return array new products data
     */
    public function getUserHistoryProducts($config) {
        $config = json_decode($config);
        $total_prod = $config->max_products_product_page;
        $products_count = $total_prod;
        $module_name = 'blockviewed';
        $data = array();
        if (!Module::isInstalled($module_name) || !Module::isEnabled($module_name)) {
            return array();
        }
        return array();
        // show recent products
    }

    /**
     * Get products with same default category
     *
     * @return same category products data
     */
    public function getProductsWithCategory($config) {
        $config = json_decode($config);
        $products_count = $config->max_products_product_page;
        $product_obj = new Product((int) Tools::getValue('product_id'));
        $default_category = $product_obj->getDefaultCategory();
        $products = array();
        if ($config->stock_availability == 1) {
            $sql = 'SELECT k.id_product '
                    . 'FROM ' . _DB_PREFIX_ . 'product k '
                    . 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` p ON (k.id_product=p.id_product) '
                    . 'where p.id_product_attribute=0 AND p.quantity>0 '
                    . 'AND k.id_category_default=' . (int) $default_category . ' and k.id_product !=' . (int) Tools::getValue('product_id') . ' '
                    . 'limit ' . (int) $products_count;
            $data = Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT id_product '
                    . 'FROM ' . _DB_PREFIX_ . 'product p '
                    . 'where id_category_default=' . (int) $default_category . ' and id_product !=' . (int) Tools::getValue('product_id') . ' '
                    . 'limit ' . (int) $products_count;
            $data = Db::getInstance()->executeS($sql);
        }
        $data = array_column($data, 'id_product');
        $results = $this->getCustomProducts(
                (int) $this->context->language->id, $data, 0, $products_count, false, null, null, false, false, $this->context
        );
        if (!empty($results)) {
            $results = Product::getProductsProperties((int) $this->context->language->id, $results);
            $index = 0;
            foreach ($results as $res) {
//                $is_available = '0';
//                if (((int) $res['quantity'] >= (int) $res['minimal_quantity']) || (int) Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
//                    $is_available = '1';
//                } else {
//                    if ((int) $res['out_of_stock'] == 1) {
//                        $is_available = '1';
//                    } else if ((int) $res['out_of_stock'] == 2 && (int) Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
//                        $is_available = '1';
//                    }
//                }
                $products[$index] = array(
                    'id' => $res['id_product'],
                    'is_in_wishlist' => $this->isProductHasInWishlist($res['id_product']),
                    'name' => $res['name'],
                     /**
                     * Changes made to show the correct Availability Status of the product
                     * @date 27-Mar-2023
                     * @author Shivam Bansal 
                     */
                    'available_for_order' => $this->getAvailabilityStatus(
                        $res['quantity'],
                        $res['minimal_quantity'],
                        $res['out_of_stock'],
                        $res['id_product']
                    ),
                    'show_price' => $res['show_price'],
                    'new_products' => (isset($res['new']) && $res['new'] == 1) ? 1 : 0,
                    'on_sale_products' => (int) $res['on_sale'],
                    'cart_quantity' => $this->getCartQuantity($res['id_product']),
                    'has_attributes' => $this->getHasProductAttributesGroups($res),
                    'image_contentMode' => 'scaleAspectFit',
                    'ClickActivityName' => 'CategoryProductsActivity',
                    'category_id' => $res['id_category_default'],
                    'price' => $this->formatPrice($res['price_without_reduction']),
                    'minimum' => (String) $res['minimal_quantity'],
                    'src' => $this->context->link->getImageLink(
                        $res['link_rewrite'],
                        $res['id_image'],
                        $this->getImageType('large')
                    )
                );
                if ($this->isProductCommentsEnable()) {
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductComment.php');
                    include_once(_PS_MODULE_DIR_.'productcomments/ProductCommentCriterion.php');
                    $products[$index]['number_of_reviews'] = ProductComment::getCommentNumber((int)$res['id_product']);
                    if ($products[$index]['number_of_reviews'] > 0) {
                        $avg_rating = ProductComment::getAverageGrade((int)$res['id_product']);
                        $products[$index]['averagecomments'] = (String) Tools::ps_round($avg_rating['grade'], 1, PS_ROUND_UP);
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    } else {
                        $products[$index]['averagecomments'] = "0";
                        $products[$index]['number_of_reviews'] = (String) $products[$index]['number_of_reviews'];
                    }
                }
                $products[$index]['discount_price'] = $this->formatPrice($res['price']);
                if (!empty($res['specific_prices']) && count($res['specific_prices']) > 0) {
                    if ($res['specific_prices']['reduction_type'] == parent::PRICE_REDUCTION_TYPE_PERCENT) {
                        $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                        $disc_per = number_format($temp_price, 2);
                        unset($temp_price);
                    } else {
                        if ($res['price_without_reduction']) {
                            $temp_price = (float) $res['specific_prices']['reduction'] * 100;
                            $percent = (float) ($temp_price / $res['price_without_reduction']);
                            unset($temp_price);
                        } else {
                            $percent = 0;
                        }
                        $disc_per = number_format(Tools::ps_round($percent), 2);
                    }
                } else {
                    $disc_per = 0;
                }
                if($disc_per == 0) {
                    $products[$index]['discount_percentage'] = "0";
                } else {
                    $products[$index]['discount_percentage'] = (string) $disc_per .
                            " " .
                            parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false), 
                                $this->l('% off'), 
                                'AppGetProductDetails'
                            );
                }
                $index++;
            }
            $data = $products;
        }
        //changes end
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
        if (!empty($product_list)) {
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
                            AND ' . pSQL($alias_where) . '.`visibility` IN ("both","catalog")';

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
                    $query .= ' ORDER BY ' . pSQL($alias_where) . '.id_product asc';
                }

                if ((int) $start > 0 && (int) $limit > 0) {
                    $query .= ' LIMIT ' . (int) $start . ',' . (int) $limit;
                } elseif ((int) $limit > 0) {
                    $query .= ' LIMIT ' . (int) $limit;
                }
                return DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            }
        }
    }
    /* end:changes made by Aayushi to integrate related products module */
}
