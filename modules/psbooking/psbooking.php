<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.txt
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
*/
if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;
use PrestaShop\PrestaShop\Adapter\Order\OrderPresenter;

include_once dirname(__FILE__) . '/../psbooking/classes/WkBookingRequiredClasses.php';

class PsBooking extends Module
{
    public function __construct()
    {
        $this->name = 'psbooking';
        $this->tab = 'front_office_features';
        $this->version = '5.5.1';
        $this->author = 'Webkul';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        parent::__construct();
        $this->displayName = $this->l('Booking and reservation system');
        $this->description = $this->l('Online booking and reservation system');
    }

    private function postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            $WK_CONSIDER_DATE_TO = Configuration::get('WK_CONSIDER_DATE_TO');
            if (isset($WK_CONSIDER_DATE_TO)
                && (Tools::getValue('WK_CONSIDER_DATE_TO') != $WK_CONSIDER_DATE_TO)
            ) {
                $objBookingsCart = new WkBookingsCart();
                if (!$objBookingsCart->deleteCurrentCustomerCarts($this->context->shop->id)) {
                    $this->context->controller->errors[] = $this->l('Some issue occurred while deleting cart.');
                }
            }
            if (!Validate::isMessage(trim(Tools::getValue('WK_BOOKING_GEOLOCATION_API_KEY')))) {
                $this->context->controller->errors[] = $this->l('Invalid google map API key.');
            }
        }
        if (!count($this->context->controller->errors)) {
            Configuration::updateValue('WK_CONSIDER_DATE_TO', Tools::getValue('WK_CONSIDER_DATE_TO'));
            Configuration::updateValue('WK_FEATURE_PRICE_RULES_SHOW', Tools::getValue('WK_FEATURE_PRICE_RULES_SHOW'));
            Configuration::updateValue('WK_MONDAY_FIRST_DAY_WEEK', Tools::getValue('WK_MONDAY_FIRST_DAY_WEEK'));
            Configuration::updateValue(
                'WK_BOOKING_PRODUCTS_DISPLAY_LINK',
                Tools::getValue('WK_BOOKING_PRODUCTS_DISPLAY_LINK')
            );
            Configuration::updateValue(
                'WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT',
                Tools::getValue('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')
            );
            Configuration::updateValue(
                'WK_BOOKING_GEOLOCATION_API_KEY',
                Tools::getValue('WK_BOOKING_GEOLOCATION_API_KEY')
            );
            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&tab_module=' .
                $this->tab . '&module_name=' . $this->name . '&conf=4'
            );
        }
    }

    public function getContent()
    {
        $this->_html = '';
        if (Tools::isSubmit('btnSubmit')) {
            $this->postProcess();
        }
        $this->_html .= $this->renderForm();
        Media::addJsDef([
            'wkModuleAddonKey' => $this->module_key,
            'wkModuleAddonsId' => 8764,
            'wkModuleTechName' => $this->name,
            'wkModuleDoc' => file_exists(_PS_MODULE_DIR_ . $this->name . '/doc_en.pdf'),
        ]);
        $this->context->controller->addJs('https://prestashop.webkul.com/crossselling/wkcrossselling.min.js?t=' . time());

        return $this->_html;
    }

    public function renderForm()
    {
        $apiCreateLink = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'psbooking/views/templates/admin/_partials/create_api.tpl'
        );
        $objOrderCurrency = new Currency($this->context->currency->id);
        $this->context->smarty->assign(
            [
                'wk_strike_price' => Tools::displayPrice(
                    100,
                    $objOrderCurrency
                ),
            ]
        );
        $regularPriceDesc = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'psbooking/views/templates/admin/_partials/booking_config_regular_price.tpl'
        );
        // Get default language
        $defaultLang = (int) Configuration::get('PS_LANG_DEFAULT');
        $fieldsForm = [];
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('General'),
                'icon' => 'icon-cogs',
            ],
            'input' => [
                [
                    'type' => 'switch',
                    'label' => $this->l('Consider price for \'date to\''),
                    'name' => 'WK_CONSIDER_DATE_TO',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                    'desc' => $this->l('If yes, last date price will be added for the booking product. ')
                    . $this->l('Otherwise booking will not be considered for the last date. '),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Display booking price rules to customers'),
                    'name' => 'WK_FEATURE_PRICE_RULES_SHOW',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Display booking products link'),
                    'name' => 'WK_BOOKING_PRODUCTS_DISPLAY_LINK',
                    'is_bool' => true,
                    'desc' => $this->l('If yes, booking products link will display in header navigation bar.'),
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Display regular price after discount to customers'),
                    'name' => 'WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT',
                    'is_bool' => true,
                    'desc' => $regularPriceDesc,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Is monday the first day of the week?'),
                    'name' => 'WK_MONDAY_FIRST_DAY_WEEK',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                    'desc' => $this->l('It works only for front-office calendar'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Google map API key'),
                    'name' => 'WK_BOOKING_GEOLOCATION_API_KEY',
                    'desc' => $this->l('Unique API key for google map.') . $apiCreateLink,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        $helper = new HelperForm();
        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&tab_module=' .
        $this->tab . '&module_name=' . $this->name;
        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->submit_action = 'btnSubmit';
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;
        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;
        // $this->fields_form = array();
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm($fieldsForm);
    }

    public function getConfigFieldsValues()
    {
        $configVars = [
            'WK_CONSIDER_DATE_TO' => Tools::getValue('WK_CONSIDER_DATE_TO', Configuration::get('WK_CONSIDER_DATE_TO')),
            'WK_FEATURE_PRICE_RULES_SHOW' => Tools::getValue(
                'WK_FEATURE_PRICE_RULES_SHOW',
                Configuration::get('WK_FEATURE_PRICE_RULES_SHOW')
            ),
            'WK_MONDAY_FIRST_DAY_WEEK' => Tools::getValue(
                'WK_MONDAY_FIRST_DAY_WEEK',
                Configuration::get('WK_MONDAY_FIRST_DAY_WEEK')
            ),
            'WK_BOOKING_PRODUCTS_DISPLAY_LINK' => Tools::getValue(
                'WK_BOOKING_PRODUCTS_DISPLAY_LINK',
                Configuration::get('WK_BOOKING_PRODUCTS_DISPLAY_LINK')
            ),
            'WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT' => Tools::getValue(
                'WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT',
                Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT')
            ),
            'WK_BOOKING_GEOLOCATION_API_KEY' => Tools::getValue(
                'WK_BOOKING_GEOLOCATION_API_KEY',
                Configuration::get('WK_BOOKING_GEOLOCATION_API_KEY')
            ),
        ];

        return $configVars;
    }

    public function hookDisplayHeader()
    {
        if (isset($this->context->cart->id) && $this->context->cart->id) {
            $wkBookingsCart = new WkBookingsCart();
            $cartBData = $wkBookingsCart->getCartInfo($this->context->cart->id);
            if ($cartBData) {
                foreach ($cartBData as $booking) {
                    /* To remove room from cart before today's date */
                    $objWkBookingsCart = new WkBookingsCart($booking['id']);
                    $idProduct = $booking['id_product'];
                    $bookingType = $objWkBookingsCart->booking_type;
                    if (strtotime($booking['date_from']) < strtotime(date('Y-m-d'))) {
                        if ($bookingType == 1) {
                            $daysCount = (int) WkBookingProductFeaturePricing::getNumberOfDays(
                                $objWkBookingsCart->date_from,
                                $objWkBookingsCart->date_to
                            );
                        } else {
                            $daysCount = 1;
                        }

                        $quantityToReduce = ($daysCount * (int) $objWkBookingsCart->quantity);
                        if ($this->context->cart->updateQty(
                            (int) $quantityToReduce,
                            (int) $idProduct,
                            null,
                            false,
                            'down',
                            0,
                            null,
                            true
                        )) {
                            if (!$objWkBookingsCart->delete()) {
                                $e = $this->l('Error while deleting product from cart.');
                                $this->context->controller->errors[] = $e;
                            }
                        } else {
                            $this->context->controller->errors[] = $this->l('Error while updating cart quantity.');
                        }
                    }
                    /* To remove bookings from cart if product is deleted */
                    $product = new Product($idProduct);
                    if (!Validate::isLoadedObject($product)) {
                        if (!$wkBookingsCart->deleteBookingProductCartByIdProductIdCart(
                            $idProduct,
                            $this->context->cart->id
                        )) {
                            $this->context->controller->errors[] = $this->l('Error while deleting product from cart.');
                        }
                    } else {
                        /* To remove bookings from cart if time slots are deleted */
                        if ($bookingType == 2) {
                            $objTimeSlotPrices = new WkBookingProductTimeSlotPrices();
                            $slotDetails = $objTimeSlotPrices->getProductTimeSlotDetails(
                                $idProduct,
                                $objWkBookingsCart->date_from,
                                $objWkBookingsCart->time_from,
                                $objWkBookingsCart->time_to
                            );
                            if (empty($slotDetails)) {
                                $day = date('N', strtotime($objWkBookingsCart->date_from));
                                $slotDetails = $objTimeSlotPrices->getProductTimeDayWiseSlotDetails(
                                    $idProduct,
                                    $day,
                                    $objWkBookingsCart->time_from,
                                    $objWkBookingsCart->time_to
                                );
                            }
                            if (!$slotDetails) {
                                if ($this->context->cart->updateQty(
                                    (int) $objWkBookingsCart->quantity,
                                    (int) $idProduct,
                                    null,
                                    false,
                                    'down',
                                    0,
                                    null,
                                    true
                                )) {
                                    if (!$objWkBookingsCart->delete()) {
                                        $e = $this->l('Error while deleting booking from cart.');
                                        $this->context->controller->errors[] = $e;
                                    }
                                } else {
                                    $e = $this->l('Error while updating cart quantity.');
                                    $this->context->controller->errors[] = $e;
                                }
                            }
                        }
                    }
                    // To remove if product is added in disable days and date
                    $objBookingDisableDates = new WkBookingProductDisabledDates();
                    $bookingDisableDatesInfo = $objBookingDisableDates->getBookingProductDisableDatesInfoFormatted(
                        $idProduct
                    );
                    if ($bookingDisableDatesInfo) {
                        $this->removeProductForDisableDaysAndDate(
                            $bookingDisableDatesInfo,
                            $bookingType,
                            $objWkBookingsCart,
                            $idProduct
                        );
                    }
                }
            }
        }
        // disable the feature price plans which date range has been expired
        if ('product' == Tools::getValue('controller')) {
            if ($productFeaturePlans = WkBookingProductFeaturePricing::getFeaturePriceByIdProduct(
                Tools::getValue('id_product')
            )) {
                $currentDateTime = strtotime(date('Y-m-d'));
                foreach ($productFeaturePlans as $plan) {
                    $planDateFrom = strtotime($plan['date_from']);
                    $planDateTo = strtotime($plan['date_to']);
                    if ($plan['date_selection_type'] == 2) {
                        $condition = ($currentDateTime > $planDateFrom);
                    } else {
                        $condition = ($currentDateTime > $planDateFrom && $currentDateTime > $planDateTo);
                    }
                    if ($condition) {
                        $objFeaturePrice = new WkBookingProductFeaturePricing($plan['id']);
                        $objFeaturePrice->active = 0;
                        $objFeaturePrice->save();
                    }
                }
            }
        }
    }

    public function removeProductForDisableDaysAndDate(
        $bookingDisableDatesInfo,
        $bookingType,
        $objWkBookingsCart,
        $idProduct
    ) {
        if (isset($bookingDisableDatesInfo['disabledDays'])
        && !empty($bookingDisableDatesInfo['disabledDays'])) {
            if ($bookingType == 1) {
                $totalDaySeconds = 24 * 60 * 60;
                if ($objWkBookingsCart->consider_last_date) {
                    $cToDate = strtotime($objWkBookingsCart->date_to);
                } else {
                    $cToDate = strtotime($objWkBookingsCart->date_to) - $totalDaySeconds;
                }
                $dateFrom = strtotime($objWkBookingsCart->date_from);
                for ($date = $dateFrom; $date <= $cToDate; $date = ($date + $totalDaySeconds)) {
                    $day = date('w', $date);
                    if (in_array($day, $bookingDisableDatesInfo['disabledDays'])) {
                        $daysCount = (int) WkBookingProductFeaturePricing::getNumberOfDays(
                            $objWkBookingsCart->date_from,
                            $objWkBookingsCart->date_to
                        );
                        $quantityToReduce = ($daysCount * (int) $objWkBookingsCart->quantity);
                        if ($this->context->cart->updateQty(
                            (int) $quantityToReduce,
                            (int) $idProduct,
                            null,
                            false,
                            'down',
                            0,
                            null,
                            true
                        )) {
                            if (!$objWkBookingsCart->delete()) {
                                $error = $this->l('Error while deleting booking from cart.');
                                $this->context->controller->errors[] = $error;
                            } else {
                                break;
                            }
                        } else {
                            $error = $this->l('Error while updating cart quantity.');
                            $this->context->controller->errors[] = $error;
                        }
                    }
                }
            }
            if ($bookingType == 2) {
                $dateFromT = strtotime($objWkBookingsCart->date_from);
                $day = date('w', $dateFromT);
                if (in_array($day, $bookingDisableDatesInfo['disabledDays'])) {
                    if ($this->context->cart->updateQty(
                        (int) $objWkBookingsCart->quantity,
                        (int) $idProduct,
                        null,
                        false,
                        'down',
                        0,
                        null,
                        true
                    )) {
                        if (!$objWkBookingsCart->delete()) {
                            $error = $this->l('Error while deleting booking from cart.');
                            $this->context->controller->errors[] = $error;
                        }
                    } else {
                        $this->context->controller->errors[] = $this->l('Error while updating cart quantity.');
                    }
                }
            }
        }
        if (isset($bookingDisableDatesInfo['disabledDates'])
        && !empty($bookingDisableDatesInfo['disabledDates'])) {
            if ($bookingType == 1) {
                $totalDaySeconds = 24 * 60 * 60;
                if ($objWkBookingsCart->consider_last_date) {
                    $cToDate = strtotime($objWkBookingsCart->date_to);
                } else {
                    $cToDate = strtotime($objWkBookingsCart->date_to) - $totalDaySeconds;
                }
                $dateFrom = strtotime($objWkBookingsCart->date_from);
                foreach ($bookingDisableDatesInfo['disabledDates'] as $disabledDates) {
                    $disabledate = strtotime($disabledDates);
                    for ($date = $dateFrom; $date <= $cToDate; $date = ($date + $totalDaySeconds)) {
                        if ($date == $disabledate) {
                            $daysCount = (int) WkBookingProductFeaturePricing::getNumberOfDays(
                                $objWkBookingsCart->date_from,
                                $objWkBookingsCart->date_to
                            );
                            $quantityToReduce = ($daysCount * (int) $objWkBookingsCart->quantity);
                            if ($this->context->cart->updateQty(
                                (int) $quantityToReduce,
                                (int) $idProduct,
                                null,
                                false,
                                'down',
                                0,
                                null,
                                true
                            )) {
                                if (!$objWkBookingsCart->delete()) {
                                    $error = $this->l('Error while deleting booking from cart.');
                                    $this->context->controller->errors[] = $error;
                                } else {
                                    break;
                                }
                            } else {
                                $error = $this->l('Error while updating cart quantity.');
                                $this->context->controller->errors[] = $error;
                            }
                        }
                    }
                }
            }
            if ($bookingType == 2) {
                foreach ($bookingDisableDatesInfo['disabledDates'] as $disabledDates) {
                    $dateFromT = strtotime($objWkBookingsCart->date_from);
                    $disabledate = strtotime($disabledDates);
                    if ($dateFromT == $disabledate) {
                        if ($this->context->cart->updateQty(
                            (int) $objWkBookingsCart->quantity,
                            (int) $idProduct,
                            null,
                            false,
                            'down',
                            0,
                            null,
                            true
                        )) {
                            if (!$objWkBookingsCart->delete()) {
                                $e = $this->l('Error while deleting booking from cart.');
                                $this->context->controller->errors[] = $e;
                            }
                        } else {
                            $this->context->controller->errors[] = $this->l('Error while updating cart quantity.');
                        }
                    }
                }
            }
        }
    }

    public function cartOverridedTpl()
    {
        $presenter = new CartPresenter();
        $isBookingProductAvailable = 0;
        $presentedCart = $presenter->present($this->context->cart, true);
        if ($presentedCart) {
            $bookingProductInfo = new WkBookingProductInformation();
            $wkBookingsCart = new WkBookingsCart();
            foreach ($presentedCart['products'] as $key => $product) {
                $idProduct = $product['id_product'];
                $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($idProduct);

                if ($isBookingProduct) {
                    $bookingProCartInfo = $wkBookingsCart->getBookingProductCartInfo(
                        $idProduct,
                        $this->context->cart->id
                    );
                    $isBookingProductAvailable = 1;
                    if ($bookingProCartInfo) {
                        foreach ($bookingProCartInfo as $keyP => $cartB) {
                            if ($cartB['booking_type'] == 1) {
                                $numDays = WkBookingProductFeaturePricing::getNumberOfDays(
                                    $cartB['date_from'],
                                    $cartB['date_to']
                                );
                                $totalQty = $cartB['quantity'] * $numDays;
                                $bookingProCartInfo[$keyP]['totalQty'] = $totalQty;
                                $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $cartB['date_from'],
                                    $cartB['date_to'],
                                    false,
                                    $this->context->currency->id
                                );
                                $bookingProCartInfo[$keyP]['totalPriceTE'] = Tools::displayPrice(
                                    (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_excl'])
                                );
                                $bookingProCartInfo[$keyP]['totalPriceTI'] = Tools::displayPrice(
                                    (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_incl'])
                                );
                            } elseif ($cartB['booking_type'] == 2) {
                                $bkingTmSltPrice = false;
                                $objTimeSlot = new WkBookingProductTimeSlotPrices();
                                $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                    $idProduct,
                                    $cartB['date_from'],
                                    $cartB['time_from'],
                                    $cartB['time_to']
                                );
                                if (empty($slotDetails)) {
                                    $day = date('N', strtotime($cartB['date_from']));
                                    $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                        $idProduct,
                                        $day,
                                        $cartB['time_from'],
                                        $cartB['time_to']
                                    );
                                }

                                if ($slotDetails) {
                                    $bkingTmSltPrice['price_tax_excl'] = $slotDetails['price'];
                                    $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate(
                                        $idProduct
                                    );
                                    $per = ((100 + $taxRate) / 100);
                                    $bkingTmSltPrice['price_tax_incl'] = $bkingTmSltPrice['price_tax_excl'] * $per;
                                    $bookingProCartInfo[$keyP]['totalQty'] = $cartB['quantity'];
                                    $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                        $idProduct,
                                        $cartB['date_from'],
                                        $cartB['date_from'],
                                        $bkingTmSltPrice,
                                        $this->context->currency->id
                                    );
                                }
                            }

                            $bookingProCartInfo[$keyP]['totalPriceTE'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_excl'])
                            );
                            $bookingProCartInfo[$keyP]['totalPriceTI'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_incl'])
                            );
                            $bookingProCartInfo[$keyP]['unit_feature_price_tax_excl_formated'] = Tools::displayPrice(
                                (float) $ttlPriBkingProd['total_price_tax_excl']
                            );
                            $bookingProCartInfo[$keyP]['unit_feature_price_tax_incl_formated'] = Tools::displayPrice(
                                (float) $ttlPriBkingProd['total_price_tax_incl']
                            );
                        }
                        $presentedCart['products'][$key]['isBookingProduct'] = 1;
                        $presentedCart['products'][$key]['booking_product_data'] = $bookingProCartInfo;
                    }
                }
            }
        }
        $this->context->smarty->assign(
            [
                'presentedCart' => $presentedCart,
            ]
        );

        return $isBookingProductAvailable;
    }

    public function orderConfirmationTpl($idOrder)
    {
        $order = new Order($idOrder);
        $orderPresenter = new OrderPresenter();
        $presentedOrder = $orderPresenter->present($order);
        if ($presentedOrder) {
            $orderProducts = $presentedOrder['products'];
            $bookingProductInfo = new WkBookingProductInformation();
            $wkBookingsOrders = new WkBookingsOrders();
            foreach ($orderProducts as $key => $product) {
                $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($product['id_product']);
                if ($isBookingProduct) {
                    $bkingPOrrInf = $wkBookingsOrders->getBookingProductOrderInfo(
                        $product['id_product'],
                        $idOrder
                    );
                    if ($bkingPOrrInf) {
                        foreach ($bkingPOrrInf as $keyP => $cartB) {
                            $noOfDays = WkBookingProductFeaturePricing::getNumberOfDays(
                                $cartB['date_from'],
                                $cartB['date_to']
                            );
                            $bkingPOrrInf[$keyP]['totalQty'] = $cartB['quantity'] * $noOfDays;
                            $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                $product['id_product'],
                                $cartB['date_from'],
                                $cartB['date_to']
                            );
                            $bkingPOrrInf[$keyP]['totalPriceTE'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_excl'])
                            );
                            $bkingPOrrInf[$keyP]['totalPriceTI'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_incl'])
                            );
                            $bkingPOrrInf[$keyP]['product_real_price_tax_excl_formated'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $cartB['product_real_price_tax_excl'])
                            );
                            $bkingPOrrInf[$keyP]['total_range_feature_price_tax_excl_formated'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_excl'])
                            );
                            $bkingPOrrInf[$keyP]['total_range_feature_price_tax_incl_formated'] = Tools::displayPrice(
                                (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_incl'])
                            );
                            $bkingPOrrInf[$keyP]['unit_feature_price_tax_excl_formated'] = Tools::displayPrice(
                                (float) $cartB['range_feature_price_tax_excl']
                            );
                            $bkingPOrrInf[$keyP]['unit_feature_price_tax_incl_formated'] = Tools::displayPrice(
                                (float) $cartB['range_feature_price_tax_incl']
                            );
                        }
                        $orderProducts[$key]['isBookingProduct'] = 1;
                        $orderProducts[$key]['booking_product_data'] = $bkingPOrrInf;
                    }
                }
            }
            $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
            $this->context->smarty->assign(
                [
                    'orderProducts' => $orderProducts,
                    'subtotals' => $presentedOrder['subtotals'],
                    'totals' => $presentedOrder['totals'],
                    'labels' => $presentedOrder['labels'],
                    'add_product_link' => false,
                    'order_confirmation_template_file' => _PS_THEME_DIR_ . 'templates/checkout/order-confirmation.tpl',
                    'priceDisplay' => $priceDisplay,
                ]
            );
        }
    }

    public function hookDisplayOverrideTemplate($params)
    {
        if ('customer/history' == $params['template_file']) {
            $orders = [];
            $customerOrders = Order::getCustomerOrders($this->context->customer->id);
            $orderPresenter = new OrderPresenter();
            foreach ($customerOrders as $customerOrder) {
                $order = new Order((int) $customerOrder['id_order']);
                $orders[$customerOrder['id_order']] = $orderPresenter->present($order);
            }
            if ($orders) {
                foreach ($orders as &$order) {
                    if ($orderProducts = $order['products']) {
                        $bookingProductInfo = new WkBookingProductInformation();
                        foreach ($orderProducts as $product) {
                            $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct(
                                $product['id_product']
                            );
                            if ($isBookingProduct) {
                                $order['bookingProductExists'] = 1;
                            }
                        }
                    }
                }
                $this->context->smarty->assign('orders', $orders);

                return 'module:psbooking/views/templates/hook/historyOverrided.tpl';
            }
        }
        if ('checkout/cart' == $params['template_file']
        || 'checkout/_partials/cart-detailed' == $params['template_file']) {
            $isBookingProductAvailable = $this->cartOverridedTpl();
            if ($isBookingProductAvailable) {
                $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                $this->context->smarty->assign(
                    [
                        'priceDisplay' => $priceDisplay,
                        'cart_template_file' => _PS_THEME_DIR_ . 'templates/checkout/cart.tpl',
                    ]
                );
                if ('checkout/cart' == $params['template_file']) {
                    return 'module:psbooking/views/templates/hook/cartCheckoutOverrided.tpl';
                }
                if ('checkout/_partials/cart-detailed' == $params['template_file']) {
                    return 'module:psbooking/views/templates/hook/cart-detailed-overrided.tpl';
                }
            }
        }
        if ('checkout/order-confirmation' == $params['template_file']) {
            $idOrder = $params['controller']->id_order;
            $this->orderConfirmationTpl($idOrder);

            return 'module:psbooking/views/templates/hook/checkoutOrderConfirmationOverrided.tpl';
        }
        if ('customer/order-detail' == $params['template_file']) {
            $idOrder = Tools::getValue('id_order');
            $order = new Order($idOrder);
            if (Validate::isLoadedObject($order) && $order->id_customer == $this->context->customer->id) {
                $orderDetails = (new OrderPresenter())->present($order);
                if ($orderDetails) {
                    $orderProducts = $orderDetails['products'];
                    if ($orderProducts) {
                        $bookingProductInfo = new WkBookingProductInformation();
                        $wkBookingsOrders = new WkBookingsOrders();
                        $objOrderCurrency = new Currency($order->id_currency);
                        $bookingProductExists = 0;
                        foreach ($orderProducts as $key => $product) {
                            $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct(
                                $product['id_product']
                            );
                            if ($isBookingProduct) {
                                $bkingPOrrInf = $wkBookingsOrders->getBookingProductOrderInfo(
                                    $product['id_product'],
                                    $idOrder
                                );
                                if ($bkingPOrrInf) {
                                    foreach ($bkingPOrrInf as $keyP => $cartB) {
                                        $noOfDays = WkBookingProductFeaturePricing::getNumberOfDays(
                                            $cartB['date_from'],
                                            $cartB['date_to']
                                        );
                                        $bkingPOrrInf[$keyP]['totalQty'] = $cartB['quantity'] * $noOfDays;
                                        $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                            $product['id_product'],
                                            $cartB['date_from'],
                                            $cartB['date_to'],
                                            false,
                                            $order->id_currency
                                        );
                                        $bkingPOrrInf[$keyP]['totalPriceTE'] = Tools::displayPrice(
                                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_excl']),
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['totalPriceTI'] = Tools::displayPrice(
                                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_incl']),
                                            $objOrderCurrency
                                        );
                                        $pri = (float) ($cartB['quantity'] * $cartB['product_real_price_tax_excl']);
                                        $rlP = Tools::displayPrice(
                                            $pri,
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['product_real_price_tax_excl_formated'] = $rlP;
                                        $tRFPTE = Tools::displayPrice(
                                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_excl']),
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['total_range_feature_price_tax_excl_formated'] = $tRFPTE;
                                        $tRFPTI = Tools::displayPrice(
                                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_incl']),
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['total_range_feature_price_tax_incl_formated'] = $tRFPTI;
                                        $rFPE = Tools::displayPrice(
                                            (float) $cartB['range_feature_price_tax_excl'],
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['unit_feature_price_tax_excl_formated'] = $rFPE;
                                        $rFPI = Tools::displayPrice(
                                            (float) $cartB['range_feature_price_tax_incl'],
                                            $objOrderCurrency
                                        );
                                        $bkingPOrrInf[$keyP]['unit_feature_price_tax_incl_formated'] = $rFPI;
                                    }
                                    $orderProducts[$key]['isBookingProduct'] = 1;
                                    $orderProducts[$key]['booking_product_data'] = $bkingPOrrInf;
                                    $bookingProductExists = 1;
                                }
                            }
                        }
                        if ($bookingProductExists) {
                            $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                            $this->context->smarty->assign(
                                [
                                    'bookingProductExists' => $bookingProductExists,
                                    'priceDisplay' => $priceDisplay,
                                    'orderProducts' => $orderProducts,
                                    'order_details_template_file' => _PS_THEME_DIR_ .
                                    'templates/customer/order-detail.tpl',
                                ]
                            );

                            return 'module:psbooking/views/templates/hook/frontOrderDetailsOverrided.tpl';
                        }
                    }
                }
            }
        }
        if ('catalog/_partials/quickview' == $params['template_file']) {
            $idProduct = Tools::getValue('id_product');
            $controller = Tools::getValue('controller');
            if ('product' == $controller || 'index' == $controller || 'category' == $controller) {
                if (isset($idProduct) && $idProduct) {
                    $objBookingProductInformation = new WkBookingProductInformation();
                    $bookingProduct = $objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct);
                    if ($bookingProduct) {
                        // Data to show Disables dates (Disable dates/slots tab)
                        $objBookingDisableDates = new WkBookingProductDisabledDates();
                        // get booking product disable dates
                        $disableDatesInfo = $objBookingDisableDates->getBookingProductDisableDatesInfoFormatted(
                            $idProduct
                        );
                        $disabledDays = 0;
                        $disabledDates = 0;

                        if ($disableDatesInfo) {
                            if (isset($disableDatesInfo['disabledDays']) && $disableDatesInfo['disabledDays']) {
                                $disabledDays = $disableDatesInfo['disabledDays'];
                            }
                            if (isset($disableDatesInfo['disabledDates']) && $disableDatesInfo['disabledDates']) {
                                $disabledDates = $disableDatesInfo['disabledDates'];
                            }
                        }
                        // Data to show Disables dates (Disable dates/slots tab)
                        $objTimeSlots = new WkBookingProductTimeSlotPrices();
                        $selectedDates = $objTimeSlots->getProductTimeSlotsSelectedDates($idProduct);
                        $timeSlotType = $objTimeSlots->checkTimeSlotType($idProduct);
                        $timeSlotDays = '';
                        if ($timeSlotType == 1) {
                            $timeSlotDays = $objTimeSlots->getTimeSlotDays($idProduct);
                        }
                        $availableDateFrom = date('Y-m-d', strtotime('+' . $bookingProduct['booking_before'] . ' hours'));
                        $todayDate = date('Y-m-d');
                        $diff = strtotime($availableDateFrom) - strtotime($todayDate);
                        // 1 day = 24 hours
                        // 24 * 60 * 60 = 86400 seconds
                        $availableAfter = $diff / 86400;
                        Media::addJsDefL('wk_booking_show_map', $bookingProduct['show_map']);
                        Media::addJsDefL('wk_booking_address', $bookingProduct['address']);
                        Media::addJsDefL('wk_booking_latitude', $bookingProduct['latitude']);
                        Media::addJsDefL('wk_booking_longitude', $bookingProduct['longitude']);
                        $this->context->smarty->assign(
                            [
                                'disabledDays' => $disabledDays,
                                'disabledDates' => $disabledDates,
                                'timeSlotType' => $timeSlotType,
                                'availableAfter' => $availableAfter,
                                'timeSlotDays' => $timeSlotDays,
                                'wk_booking_show_map' => $bookingProduct['show_map'],
                                'wk_booking_address' => $bookingProduct['address'],
                                'wk_booking_latitude' => $bookingProduct['latitude'],
                                'wk_booking_longitude' => $bookingProduct['longitude'],
                                'selectedDates' => json_encode($selectedDates),
                                'isBookingProduct' => 1,
                                'moduleDir' => _PS_MODULE_DIR_ . 'psbooking',
                            ]
                        );

                        return 'module:psbooking/views/templates/hook/productQuickReviewOverrided.tpl';
                    }
                }
            }
        }
        if ('checkout/checkout' == $params['template_file']) {
            $presenter = new CartPresenter();
            $isBookingProductAvailable = 0;
            $presentedCart = $presenter->present($this->context->cart, true);
            if ($presentedCart) {
                $bookingProductInfo = new WkBookingProductInformation();
                $wkBookingsCart = new WkBookingsCart();
                foreach ($presentedCart['products'] as $key => $product) {
                    $idProduct = $product['id_product'];
                    $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                    if ($isBookingProduct) {
                        $bookingProCartInfo = $wkBookingsCart->getBookingProductCartInfo(
                            $idProduct,
                            $this->context->cart->id
                        );
                        $isBookingProductAvailable = 1;
                        if ($bookingProCartInfo) {
                            $totalPriceTE = 0;
                            foreach ($bookingProCartInfo as $keyP => $cartB) {
                                if ($cartB['booking_type'] == 1) {
                                    $noOfDays = WkBookingProductFeaturePricing::getNumberOfDays(
                                        $cartB['date_from'],
                                        $cartB['date_to']
                                    );
                                    $bookingProCartInfo[$keyP]['totalQty'] = $cartB['quantity'] * $noOfDays;
                                    $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                        $idProduct,
                                        $cartB['date_from'],
                                        $cartB['date_to'],
                                        false,
                                        $this->context->currency->id
                                    );
                                    $tlPrTxEx = $ttlPriBkingProd['total_price_tax_excl'];
                                    $totalPriceTE += ($cartB['quantity'] * $tlPrTxEx);
                                } elseif ($cartB['booking_type'] == 2) {
                                    $bkingTmSltPrice = false;
                                    $objTimeSlot = new WkBookingProductTimeSlotPrices();
                                    $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                        $idProduct,
                                        $cartB['date_from'],
                                        $cartB['time_from'],
                                        $cartB['time_to']
                                    );
                                    if (empty($slotDetails)) {
                                        $day = date('N', strtotime($cartB['date_from']));
                                        $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                            $idProduct,
                                            $day,
                                            $cartB['time_from'],
                                            $cartB['time_to']
                                        );
                                    }
                                    if ($slotDetails) {
                                        $bkingTmSltPrice['price_tax_excl'] = $slotDetails['price'];
                                        $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate(
                                            $idProduct
                                        );
                                        $per = ((100 + $taxRate) / 100);
                                        $bkingTmSltPrice['price_tax_incl'] = $bkingTmSltPrice['price_tax_excl'] * $per;
                                        $bookingProCartInfo[$keyP]['totalQty'] = $cartB['quantity'];
                                        $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                            $idProduct,
                                            $cartB['date_from'],
                                            $cartB['date_from'],
                                            $bkingTmSltPrice,
                                            $this->context->currency->id
                                        );
                                        $tlPrTxEx = $ttlPriBkingProd['total_price_tax_excl'];
                                        $totalPriceTE += ($cartB['quantity'] * $tlPrTxEx);
                                    }
                                }
                            }
                            $presentedCart['products'][$key]['isBookingProduct'] = 1;
                            $presentedCart['products'][$key]['total_price_tax_excl'] = $totalPriceTE;
                            $presentedCart['products'][$key]['total_price_tax_excl_formatted'] = Tools::displayPrice(
                                $totalPriceTE
                            );
                        }
                    }
                }
            }
            if ($isBookingProductAvailable) {
                $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                $this->context->smarty->assign(
                    [
                        'priceDisplay' => $priceDisplay,
                        'cart' => $presentedCart,
                        'checkout_template_file' => _PS_THEME_DIR_ . 'templates/checkout/checkout.tpl',
                    ]
                );

                return 'module:psbooking/views/templates/hook/checkoutCheckoutOverrided.tpl';
            }
        }
    }

    // DisplayProductButtons is changed in hookDisplayProductAdditionalInfo in new versions.
    public function hookDisplayProductAdditionalInfo()
    {
        return $this->hookDisplayProductButtons();
    }

    public function hookDisplayProductButtons()
    {
        $objBookingProductInformation = new WkBookingProductInformation();
        $idProduct = Tools::getValue('id_product');
        if ($bookingProductInformation = $objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct, true)) {
            $dateFrom = date('Y-m-d', strtotime('+' . $bookingProductInformation['booking_before'] . ' hours'));
            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                $dateTo = date('Y-m-d', strtotime($dateFrom));
            } else {
                $dateTo = date('Y-m-d', strtotime('+1 day', strtotime($dateFrom)));
            }
            $this->context->smarty->assign(
                [
                    'date_from' => date('d-m-Y', strtotime($dateFrom)),
                    'date_to' => date('d-m-Y', strtotime($dateTo)),
                ]
            );
            $wkBookingOrder = new WkBookingsOrders();
            $bkingTmSltPriceToday = false;
            $bkingTmSltPrice = false;
            $objBookingDisableDates = new WkBookingProductDisabledDates();
            if ($bookingProductInformation['booking_type'] == 2) {
                $objTimeSlots = new WkBookingProductTimeSlotPrices();
                $bookingTimeSlots = $objTimeSlots->getProductTimeSlotsOnDate($idProduct, $dateFrom, true, 1, false);
                if (empty($bookingTimeSlots)) {
                    $bookingTimeSlots = $objTimeSlots->getProductTimeSlotsOnDate($idProduct, $dateFrom, true, 1, true);
                }
                if ($bookingTimeSlots) {
                    $flag = 0;
                    $totalSlotsQty = 0;
                    foreach ($bookingTimeSlots as $key => $timeSlot) {
                        $bookedSlotQuantity = $wkBookingOrder->getProductTimeSlotOrderedQuantity(
                            $idProduct,
                            $dateFrom,
                            $timeSlot['time_slot_from'],
                            $timeSlot['time_slot_to'],
                            1
                        );
                        // $availQty = $bookingProductInformation['quantity'] - $bookedSlotQuantity;
                        $availQty = $timeSlot['quantity'] - $bookedSlotQuantity;
                        $bookingTimeSlots[$key]['available_qty'] = ($availQty < 0) ? 0 : $availQty;
                        $bookingTimeSlots[$key]['price_tax_excl'] = $timeSlot['price'];
                        $totalSlotsQty += $bookingProductInformation['quantity'] - $bookedSlotQuantity;
                        $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                        $bookingTimeSlots[$key]['price_tax_incl'] = $timeSlot['price'] * ((100 + $taxRate) / 100);
                        $bkingTmSltPrice['price_tax_excl'] = $bookingTimeSlots[$key]['price_tax_excl'];
                        $bkingTmSltPrice['price_tax_incl'] = $bookingTimeSlots[$key]['price_tax_incl'];

                        if ($flag == 0 && $bookingTimeSlots[$key]['available_qty']) {
                            $bookingTimeSlots[$key]['checked'] = 1;
                            $bkingTmSltPriceToday['price_tax_excl'] = $bookingTimeSlots[$key]['price_tax_excl'];
                            $bkingTmSltPriceToday['price_tax_incl'] = $bookingTimeSlots[$key]['price_tax_incl'];
                            $flag = 1;
                        } else {
                            $bookingTimeSlots[$key]['checked'] = 0;
                        }
                        $ttlFeatPri = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                            $idProduct,
                            $dateFrom,
                            $dateFrom,
                            $bkingTmSltPrice,
                            $this->context->currency->id
                        );
                        if ($ttlFeatPri) {
                            $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                            if (!$priceDisplay || $priceDisplay == 2) {
                                $bookingTimeSlots[$key]['formated_slot_price'] = Tools::displayPrice(
                                    $ttlFeatPri['total_price_tax_incl']
                                );
                                if ($ttlFeatPri['have_price_rule']) {
                                    $bookingTimeSlots[$key]['formated_slot_price_regular'] = Tools::displayPrice(
                                        $ttlFeatPri['regular_total_price_tax_incl']
                                    );
                                }
                            } elseif ($priceDisplay == 1) {
                                $bookingTimeSlots[$key]['formated_slot_price'] = Tools::displayPrice(
                                    $ttlFeatPri['total_price_tax_excl']
                                );
                                if ($ttlFeatPri['have_price_rule']) {
                                    $bookingTimeSlots[$key]['formated_slot_price_regular'] = Tools::displayPrice(
                                        $ttlFeatPri['regular_total_price_tax_excl']
                                    );
                                }
                            }
                        }
                    }
                    if ($flag == 0 && !$bkingTmSltPriceToday) {
                        $bkingTmSltPriceToday['price_tax_excl'] = 0;
                        $bkingTmSltPriceToday['price_tax_incl'] = 0;
                    }
                    $this->context->smarty->assign('totalSlotsQty', $totalSlotsQty);
                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                        $idProduct,
                        $dateFrom,
                        $dateFrom,
                        $bkingTmSltPriceToday,
                        $this->context->currency->id
                    );
                    if ($totalPrice) {
                        $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                        if (!$priceDisplay || $priceDisplay == 2) {
                            $productFeaturePrice = $totalPrice['total_price_tax_incl'];
                            if ($totalPrice['have_price_rule']) {
                                $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_incl'];
                                $this->context->smarty->assign(
                                    [
                                        'productFeaturePriceRegular' => Tools::displayPrice(
                                            $productFeaturePriceRegular
                                        ),
                                    ]
                                );
                            }
                        } elseif ($priceDisplay == 1) {
                            $productFeaturePrice = $totalPrice['total_price_tax_excl'];
                            if ($totalPrice['have_price_rule']) {
                                $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_excl'];
                                $this->context->smarty->assign(
                                    [
                                        'productFeaturePriceRegular' => Tools::displayPrice(
                                            $productFeaturePriceRegular
                                        ),
                                    ]
                                );
                            }
                        }
                    }
                } else {
                    $productFeaturePrice = 0;
                }
                // get disable dates info for current selected dates
                $selectedDatesDisableInfo = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateFrom
                );
                $this->context->smarty->assign('bookingTimeSlots', $bookingTimeSlots);
            } else {
                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                    $idProduct,
                    $dateFrom,
                    $dateTo,
                    $bkingTmSltPriceToday,
                    $this->context->currency->id
                );
                if ($totalPrice) {
                    $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productFeaturePrice = $totalPrice['total_price_tax_incl'];
                        if ($totalPrice['have_price_rule']) {
                            $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_incl'];
                            $this->context->smarty->assign(
                                [
                                    'productFeaturePriceRegular' => Tools::displayPrice(
                                        $productFeaturePriceRegular
                                    ),
                                ]
                            );
                        }
                    } elseif ($priceDisplay == 1) {
                        $productFeaturePrice = $totalPrice['total_price_tax_excl'];
                        if ($totalPrice['have_price_rule']) {
                            $productFeaturePriceRegular = $totalPrice['regular_total_price_tax_excl'];
                            $this->context->smarty->assign(
                                [
                                    'productFeaturePriceRegular' => Tools::displayPrice(
                                        $productFeaturePriceRegular
                                    ),
                                ]
                            );
                        }
                    }
                }
                // get disable dates info for current selected dates
                $selectedDatesDisableInfo = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateTo
                );
            }
            $bookedQuantity = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQuantity;
            if ($maxAvailableQuantity <= 0) {
                $maxAvailableQuantity = 0;
                $productFeaturePrice = 0;
                $productFeaturePriceRegular = 0;
            }
            $bookingPricePlans = WkBookingProductFeaturePricing::getActiveFeaturePricesByIdProduct($idProduct);
            if ($bookingPricePlans) {
                foreach ($bookingPricePlans as &$plan) {
                    $plan['impact_value_formated'] = Tools::displayPrice(
                        Tools::convertPrice($plan['impact_value'])
                    );
                }
            }
            // Get featurePrice priority
            $featurePricePriority = Configuration::get('WK_PRODUCT_FEATURE_PRICING_PRIORITY');
            $featurePricePriority = explode(';', $featurePricePriority);
            foreach ($featurePricePriority as $key => $priority) {
                if ($priority == 'date_range') {
                    $featurePricePriority[$key] = $this->l('For date range');
                } elseif ($priority == 'specific_date') {
                    $featurePricePriority[$key] = $this->l('For specific date');
                } elseif ($priority == 'special_day') {
                    $featurePricePriority[$key] = $this->l('For special days');
                }
            }
            $googleMapKey = '';
            if (!empty(Configuration::get('WK_BOOKING_GEOLOCATION_API_KEY'))) {
                $googleMapKey = Configuration::get('WK_BOOKING_GEOLOCATION_API_KEY');
            }
            $this->context->smarty->assign(
                [
                    'selectedDatesDisabled' => $selectedDatesDisableInfo ? 1 : 0,
                    'featurePricePriority' => $featurePricePriority,
                    'maxAvailableQuantity' => $maxAvailableQuantity,
                    'bookingPricePlans' => $bookingPricePlans,
                    'bookingProductInformation' => $bookingProductInformation,
                    'productFeaturePrice' => Tools::displayPrice(
                        $productFeaturePrice
                    ),
                    'module_dir' => _MODULE_DIR_,
                    'show_feature_price_rules' => Configuration::get('WK_FEATURE_PRICE_RULES_SHOW'),
                    'show_regular_price_after_discount' => Configuration::get('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT'),
                    'wk_google_map_key' => $googleMapKey,
                    'wk_show_map' => $bookingProductInformation['show_map'],
                ]
            );

            return $this->fetch('module:psbooking/views/templates/hook/customerBookingInterface.tpl');
        }
    }

    public function hookActionFrontControllerSetMedia()
    {
        $idProduct = Tools::getValue('id_product');
        $controller = Tools::getValue('controller');
        if (empty($controller)) {
            $controller = $this->context->controller->php_self;
        }
        if ('product' == $controller
            || 'index' == $controller
            || 'category' == $controller
            || 'cart' == $controller
            || 'order' == $controller
            || 'bookingproduct' == $controller
        ) {
            $jsDef = [];
            if ($idProduct) {
                $objBookingProductInfo = new WkBookingProductInformation();
                $bookingProduct = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                if ($bookingProduct) {
                    // Data to show Disables dates (Disable dates/slots tab)
                    $objBookingDisableDates = new WkBookingProductDisabledDates();
                    // get booking product disable dates
                    $bookingDisableDatesInfo = $objBookingDisableDates->getBookingProductDisableDatesInfoFormatted(
                        $idProduct
                    );
                    if ($bookingDisableDatesInfo) {
                        if (isset($bookingDisableDatesInfo['disabledDays'])) {
                            $jsDef['disabledDays'] = $bookingDisableDatesInfo['disabledDays'];
                        }
                        if (isset($bookingDisableDatesInfo['disabledDates'])) {
                            $jsDef['disabledDates'] = $bookingDisableDatesInfo['disabledDates'];
                        }
                    }
                    // Data to show Disables dates (Disable dates/slots tab)
                    $objTimeSlots = new WkBookingProductTimeSlotPrices();
                    $selectedDates = $objTimeSlots->getProductTimeSlotsSelectedDates($idProduct);
                    $timeSlotType = $objTimeSlots->checkTimeSlotType($idProduct);
                    if ($timeSlotType == 1) {
                        $timeSlotDays = $objTimeSlots->getTimeSlotDays($idProduct);
                        $jsDef['timeSlotDays'] = $timeSlotDays;
                    }
                    $availableDateFrom = date('Y-m-d', strtotime('+' . $bookingProduct['booking_before'] . ' hours'));
                    $todayDate = date('Y-m-d');
                    $diff = strtotime($availableDateFrom) - strtotime($todayDate);
                    // 1 day = 24 hours
                    // 24 * 60 * 60 = 86400 seconds
                    $availableAfter = $diff / 86400;
                    Media::addJsDefL('selectedDatesJson', json_encode($selectedDates));
                    Media::addJsDefL('available_after', $availableAfter);
                    Media::addJsDefL('timeSlotType', $timeSlotType);
                    Media::addJsDefL('wk_booking_show_map', $bookingProduct['show_map']);
                    Media::addJsDefL('wk_booking_address', $bookingProduct['address']);
                    Media::addJsDefL('wk_booking_latitude', $bookingProduct['latitude']);
                    Media::addJsDefL('wk_booking_longitude', $bookingProduct['longitude']);
                }
            }
            Media::addJsDefL('disable_date_title', $this->l('Bookings are unavailable on this date'));
            Media::addJsDefL(
                'bookings_in_select_range_label',
                $this->l('Following bookings will be created for selected date range')
            );
            Media::addJsDefL(
                'booking_one_slot_select_msg',
                $this->l('At least one slot must be selected for booking.')
            );
            Media::addJsDefL('no_slots_available_text', $this->l('No slots available'));
            Media::addJsDefL('total_price_text', $this->l('Total price'));
            Media::addJsDefL('dateText', $this->l('Date selected'));
            Media::addJsDefL('dateRangeText', $this->l('Date range'));
            Media::addJsDefL('priceText', $this->l('Price'));
            Media::addJsDefL('To_txt', $this->l('To'));
            Media::addJsDefL('for_txt', $this->l('for'));
            Media::addJsDefL('qtyText', $this->l('quantity'));
            Media::addJsDefL('invalidQtyErr', $this->l('Invalid quantity.'));
            Media::addJsDefL('slot_booked_text', $this->l('Slot booked!'));
            Media::addJsDefL('slot_max_text', $this->l('Max'));
            Media::addJsDefL('get_directions_text', $this->l('Get directions'));

            $jsDef['wkBookingCartLink'] = $this->context->link->getModuleLink('psbooking', 'BookingProductCartActions');
            $jsDef['considerDateToConfiguration'] = Configuration::get('WK_CONSIDER_DATE_TO');
            $jsDef['wk_monday_first_day_week'] = Configuration::get('WK_MONDAY_FIRST_DAY_WEEK');
            $jsDef['wk_static_token'] = Tools::getToken(false);
            Media::addJsDef($jsDef);
            $this->context->controller->registerJavascript(
                'datepicker-i18n.js',
                'js/jquery/ui/i18n/jquery-ui-i18n.js',
                ['position' => 'bottom', 'priority' => 999]
            );
            $this->context->controller->addJqueryPlugin('growl', null, false);
            $this->context->controller->registerStylesheet('growl-css', 'js/jquery/plugins/growl/jquery.growl.css');

            $this->context->controller->registerJavascript(
                'module-customerBookingInterface-js',
                'modules/' . $this->name . '/views/js/customerBookingInterface.js',
                ['position' => 'bottom', 'priority' => 999]
            );
            $this->context->controller->addJqueryUI(['ui.slider', 'ui.datepicker']);
        }
    }

    public function hookActionValidateOrder($data)
    {
        $cart = $data['cart'];
        $order = $data['order'];
        $idOrder = $order->id;
        $cartProducts = $cart->getProducts();
        $bookingProductInfo = new WkBookingProductInformation();
        $wkBookingsCart = new WkBookingsCart();
        $paidProPri = [];
        foreach ($cartProducts as $product) {
            $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($product['id_product']);
            if ($isBookingProduct) {
                $bookingProCartInfo = $wkBookingsCart->getBookingProductCartInfo($product['id_product'], $cart->id);
                if ($bookingProCartInfo) {
                    foreach ($bookingProCartInfo as $keyP => $cartBPro) {
                        $idProduct = $cartBPro['id_product'];
                        $productPriceTI = Product::getPriceStatic((int) $idProduct, true);
                        $productPriceTE = Product::getPriceStatic((int) $idProduct, false);
                        if ($cartBPro['booking_type'] == 2) {
                            $bkingTmSltPrice = false;
                            $objTimeSlot = new WkBookingProductTimeSlotPrices();
                            $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                $idProduct,
                                $cartBPro['date_from'],
                                $cartBPro['time_from'],
                                $cartBPro['time_to']
                            );
                            if (empty($slotDetails)) {
                                $day = date('N', strtotime($cartBPro['date_from']));
                                $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                    $idProduct,
                                    $day,
                                    $cartBPro['time_from'],
                                    $cartBPro['time_to']
                                );
                            }
                            if ($slotDetails) {
                                $bkingTmSltPrice['price_tax_excl'] = $slotDetails['price'];
                                $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                                $priceTaxIncl = $bkingTmSltPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                                $bkingTmSltPrice['price_tax_incl'] = $priceTaxIncl;
                                $bookingProCartInfo[$keyP]['totalQty'] = $cartBPro['quantity'];
                                $ttlFeatPri = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $cartBPro['date_from'],
                                    $cartBPro['date_from'],
                                    $bkingTmSltPrice
                                );
                            }
                        } elseif ($cartBPro['booking_type'] == 1) {
                            $ttlFeatPri = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                $idProduct,
                                $cartBPro['date_from'],
                                $cartBPro['date_to'],
                                false
                            );
                        }
                        // create array of product price differences for creating specific prices
                        if (isset($paidProPri[$cartBPro['id_product']])) {
                            $priceTI = $ttlFeatPri['total_price_tax_incl'] * $cartBPro['quantity'];
                            $paidProPri[$cartBPro['id_product']]['paid_total_product_price_ti'] += $priceTI;
                            $priceTE = $ttlFeatPri['total_price_tax_excl'] * $cartBPro['quantity'];
                            $paidProPri[$cartBPro['id_product']]['paid_total_product_price_te'] += $priceTE;
                        } else {
                            $priceTI = $ttlFeatPri['total_price_tax_incl'] * $cartBPro['quantity'];
                            $paidProPri[$cartBPro['id_product']]['paid_total_product_price_ti'] = $priceTI;
                            $priceTE = $ttlFeatPri['total_price_tax_excl'] * $cartBPro['quantity'];
                            $paidProPri[$cartBPro['id_product']]['paid_total_product_price_te'] = $priceTE;
                        }

                        // enter the bookings ptoducts order information in our booking order table
                        $wkBookingsOrders = new WkBookingsOrders();
                        $wkBookingsOrders->id_cart = $cartBPro['id_cart'];
                        $wkBookingsOrders->id_order = $order->id;
                        $wkBookingsOrders->id_product = $cartBPro['id_product'];
                        $wkBookingsOrders->quantity = $cartBPro['quantity'];
                        $wkBookingsOrders->booking_type = $cartBPro['booking_type'];
                        $wkBookingsOrders->date_from = $cartBPro['date_from'];
                        $wkBookingsOrders->date_to = $cartBPro['date_to'];
                        $wkBookingsOrders->time_from = $cartBPro['time_from'];
                        $wkBookingsOrders->time_to = $cartBPro['time_to'];
                        $wkBookingsOrders->consider_last_date = $cartBPro['consider_last_date'];
                        $wkBookingsOrders->product_real_price_tax_excl = $productPriceTE;
                        $wkBookingsOrders->product_real_price_tax_incl = $productPriceTI;
                        $wkBookingsOrders->range_feature_price_tax_incl = Tools::ps_round(
                            $ttlFeatPri['total_price_tax_incl'],
                            6
                        );
                        $wkBookingsOrders->range_feature_price_tax_excl = Tools::ps_round(
                            $ttlFeatPri['total_price_tax_excl'],
                            6
                        );
                        $wkBookingsOrders->total_order_tax_excl = $order->total_paid_tax_excl;
                        $wkBookingsOrders->total_order_tax_incl = $order->total_paid_tax_incl;
                        if (!$wkBookingsOrders->save()) {
                            error_log(
                                date('[Y-m-d H:i e] ') . 'WkBookingsOrders save Error : Error occured while making entry
                                with the details :: cartBookingProduct = ' . $cartBPro . PHP_EOL .
                                'totalFeaturePriceArray = ' . $ttlFeatPri . PHP_EOL,
                                3,
                                _PS_MODULE_DIR_ . 'psbooking/error.log'
                            );
                        }
                    }
                }
            }
        }
        // change the order details product price info as paid by cusstomer after applying feature prices
        if (count($paidProPri)) {
            $wkBookingsOrders = new WkBookingsOrders();
            foreach ($paidProPri as $id_product => $productPrice) {
                $orderProductDetails = $wkBookingsOrders->getOrderDetailsProductInfo($idOrder, $id_product);
                if ($orderProductDetails) {
                    if ($orderProductDetails['total_price_tax_incl'] != $productPrice['paid_total_product_price_ti']) {
                        $fieldsToUpdate = [];

                        $fieldsToUpdate['total_price_tax_incl'] = $productPrice['paid_total_product_price_ti'];
                        $fieldsToUpdate['total_price_tax_excl'] = $productPrice['paid_total_product_price_te'];
                        $productQty = $orderProductDetails['product_quantity'];

                        $fieldsToUpdate['unit_price_tax_incl'] = Tools::ps_round(
                            $productPrice['paid_total_product_price_ti'] / $productQty,
                            6
                        );
                        $fieldsToUpdate['unit_price_tax_excl'] = Tools::ps_round(
                            $productPrice['paid_total_product_price_te'] / $productQty,
                            6
                        );
                        if (!$wkBookingsOrders->updatePsOrderDetailsColumns($idOrder, $id_product, $fieldsToUpdate)) {
                            error_log(
                                date('[Y-m-d H:i e] ') . 'actionValidateOrder : Error occured while updating product
                                prices in order_detail (feature prices) for id_product : ' . $id_product . PHP_EOL .
                                'id_order = ' . $idOrder . PHP_EOL . 'fieldsToUpdate = ' . $fieldsToUpdate,
                                3,
                                _PS_MODULE_DIR_ . 'psbooking/error.log'
                            );
                        }
                    }
                }
            }
        }
    }

    // * admin display booking product orders details.
    public function hookDisplayAdminOrder()
    {
        $idOrder = Tools::getValue('id_order');
        $order = new Order($idOrder);
        $orderProducts = $order->getProducts();
        $bookingProductInfo = new WkBookingProductInformation();
        $wkBookingsOrders = new WkBookingsOrders();
        foreach ($orderProducts as $key => &$product) {
            if ($product['image'] != null) {
                $imageName = 'product_mini_' . (int) $product['product_id'] .
                (isset($product['product_attribute_id']) ? '_' . (int) $product['product_attribute_id'] : '') . '.jpg';

                // generate image cache, only for back office
                $product['image_tag'] = ImageManager::thumbnail(
                    _PS_IMG_DIR_ . 'p/' . $product['image']->getExistingImgPath() . '.jpg',
                    $imageName,
                    45,
                    'jpg'
                );
                if (file_exists(_PS_TMP_IMG_DIR_ . $imageName)) {
                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $imageName);
                } else {
                    $product['image_size'] = false;
                }
            }
            $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($product['product_id']);
            if ($isBookingProduct) {
                $bkingPOrrInf = $wkBookingsOrders->getBookingProductOrderInfo(
                    $product['product_id'],
                    $idOrder
                );
                $objOrderCurrency = new Currency($order->id_currency);
                if ($bkingPOrrInf) {
                    foreach ($bkingPOrrInf as $keyP => $cartB) {
                        $noOfDays = WkBookingProductFeaturePricing::getNumberOfDays(
                            $cartB['date_from'],
                            $cartB['date_to']
                        );
                        $bkingPOrrInf[$keyP]['totalQty'] = $cartB['quantity'] * $noOfDays;
                        $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                            $product['id_product'],
                            $cartB['date_from'],
                            $cartB['date_to']
                        );
                        $bkingPOrrInf[$keyP]['totalPriceTE'] = Tools::displayPrice(
                            (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_excl']),
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['totalPriceTI'] = Tools::displayPrice(
                            (float) ($cartB['quantity'] * $ttlPriBkingProd['total_price_tax_incl']),
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['product_real_price_tax_excl_formated'] = Tools::displayPrice(
                            (float) ($cartB['quantity'] * $cartB['product_real_price_tax_excl']),
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['total_range_feature_price_tax_excl_formated'] = Tools::displayPrice(
                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_excl']),
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['total_range_feature_price_tax_incl_formated'] = Tools::displayPrice(
                            (float) ($cartB['quantity'] * $cartB['range_feature_price_tax_incl']),
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['unit_feature_price_tax_excl_formated'] = Tools::displayPrice(
                            (float) $cartB['range_feature_price_tax_excl'],
                            $objOrderCurrency
                        );
                        $bkingPOrrInf[$keyP]['unit_feature_price_tax_incl_formated'] = Tools::displayPrice(
                            (float) $cartB['range_feature_price_tax_incl'],
                            $objOrderCurrency
                        );
                    }
                    $orderProducts[$key]['isBookingProduct'] = 1;
                    $orderProducts[$key]['booking_product_data'] = $bkingPOrrInf;
                }
            }
        }
        $this->context->smarty->assign('orderProducts', $orderProducts);

        return $this->display(__FILE__, 'adminBookingProductOrderDetails.tpl');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['smarty']->template_resource == 'module:ps_shoppingcart/modal.tpl') {
            $lastProductAdded = $this->context->cart->getLastProduct();
            $idProduct = $lastProductAdded['id_product'];
            $bookingProductInfo = new WkBookingProductInformation();
            $isBookingProduct = $bookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
            if ($isBookingProduct) {
                $wkBookingsCart = new WkBookingsCart();
                $bookingProCartInfo = $wkBookingsCart->getCartInfoByProduct($idProduct, $this->context->cart->id);
                if ($bookingProCartInfo) {
                    foreach ($bookingProCartInfo as $key => $product) {
                        if ($product['booking_type'] == 1) {
                            $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                $idProduct,
                                $bookingProCartInfo[$key]['date_from'],
                                $product['date_to']
                            );
                            $bookingProCartInfo[$key]['totalPriceTE'] = Tools::displayPrice(
                                (float) ($product['quantity'] * $ttlPriBkingProd['total_price_tax_excl'])
                            );
                            $bookingProCartInfo[$key]['totalPriceTI'] = Tools::displayPrice(
                                (float) ($product['quantity'] * $ttlPriBkingProd['total_price_tax_incl'])
                            );
                        } elseif ($product['booking_type'] == 2) {
                            $bkingTmSltPrice = false;
                            $objTimeSlot = new WkBookingProductTimeSlotPrices();
                            $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                $idProduct,
                                $product['date_from'],
                                $product['time_from'],
                                $product['time_to']
                            );
                            if (empty($slotDetails)) {
                                $day = date('N', strtotime($product['date_from']));
                                $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                    $idProduct,
                                    $day,
                                    $product['time_from'],
                                    $product['time_to']
                                );
                            }
                            if ($slotDetails) {
                                $bkingTmSltPrice['price_tax_excl'] = $slotDetails['price'];

                                $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                                $priceTaxIncl = $bkingTmSltPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                                $bkingTmSltPrice['price_tax_incl'] = $priceTaxIncl;
                                $ttlPriBkingProd = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $product['date_from'],
                                    $product['date_from'],
                                    $bkingTmSltPrice
                                );
                            }
                        }
                        $bookingProCartInfo[$key]['totalPriceTE'] = Tools::displayPrice(
                            (float) ($product['quantity'] * $ttlPriBkingProd['total_price_tax_excl'])
                        );
                        $bookingProCartInfo[$key]['totalPriceTI'] = Tools::displayPrice(
                            (float) ($product['quantity'] * $ttlPriBkingProd['total_price_tax_incl'])
                        );
                    }
                    $this->context->smarty->assign('bookingProductCartInfo', $bookingProCartInfo);

                    return $this->fetch('module:psbooking/views/templates/hook/cartPopUpBookingInfo.tpl');
                }
            }
        }
    }

    public function hookActionProductDelete($params)
    {
        $idProduct = $params['id_product'];
        $objBookingProductInformation = new WkBookingProductInformation();
        if ($objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct)) {
            $objProductFeaturePricing = new WkBookingProductFeaturePricing();
            if (!$objProductFeaturePricing->deleteFeaturePricePlansByIdProduct($idProduct)) {
                $e = $this->l('Some error occurred while deleting booking price rules of this product.');
                $this->context->controller->errors[] = $e;
            }
            $wkTimeSlotPrices = new WkBookingProductTimeSlotPrices();
            if (!$wkTimeSlotPrices->deleteBookingProductTimeSlots($idProduct)) {
                $e = $this->l('Some error occurred while deleting time slots
                info of this product.');
                $this->context->controller->errors[] = $e;
            }
            if (!$objBookingProductInformation->deleteBookingProductByIdProduct($idProduct)) {
                $this->l('Some error occurred while deleting booking product info of this product.');
                $this->context->controller->errors[] = $e;
            }
        }
    }

    public function hookActionProductUpdate($params)
    {
        if (isset($params['id_product']) && $idProduct = $params['id_product']) {
            $objBookingProductInformation = new WkBookingProductInformation();
            if ($bookingProduct = $objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct)) {
                if ($bookingProduct['id'] && $bookingProduct['id']) {
                    $objProduct = new Product($idProduct);
                    if (!$objProduct->is_virtual) {
                        $objProduct->is_virtual = 1;
                        $objProduct->save();
                    }
                    $objBookingProductInformation = new WkBookingProductInformation($bookingProduct['id']);
                    $objBookingProductInformation->active = $objProduct->active;
                    $objBookingProductInformation->save();
                }
            }
        }
    }

    public function hookActionObjectLanguageAddAfter($params)
    {
        if ($params['object']->id) {
            $this->createMailLangDirectoryWithFiles($params['object']->id);
        }
    }

    /**
     * [hookActionAdminProductsListingFieldsModifier - show custom filed on product list in admin product controller]
     */
    public function hookActionAdminProductsListingFieldsModifier($list)
    {
        if (isset($list['sql_select'])) {
            $list['sql_select']['booking_type'] = [
                'table' => 'bpi',
                'field' => 'booking_type',
                'filtering' => ' %s ',
            ];
        }
        if (isset($list['sql_table'])) {
            $list['sql_table']['bpi'] = [
                'table' => 'wk_booking_product_info',
                'join' => 'LEFT JOIN',
                'on' => 'bpi.`id_product` = p.`id_product`',
            ];
        }
    }

    public function callInstallTab()
    {
        $this->installTab('AdminManageBookingProductConfiguration', 'Bookings', 'AdminCatalog');
        $this->installTab(
            'AdminBookingProduct',
            'Products',
            'AdminManageBookingProductConfiguration'
        );
        $this->installTab(
            'AdminBookingProductPricePlansSettings',
            'Price rules',
            'AdminManageBookingProductConfiguration'
        );
        $this->installTab(
            'AdminBookingOrders',
            'Orders',
            'AdminManageBookingProductConfiguration'
        );

        return true;
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $idOrder = $params['id_order'];
        $currentState = $params['newOrderStatus']->id;
        if ($currentState == 6) {
            WkBookingsOrders::updateOrderCancelled($idOrder);
        }
    }

    public function installTab($className, $tabName, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        if ($tabParentName) {
            $tab->id_parent = (int) Tab::getIdFromClassName($tabParentName);
        } else {
            $tab->id_parent = 0;
        }
        $tab->module = $this->name;

        return $tab->add();
    }

    public function registerModuleHooks()
    {
        return $this->registerHook(
            [
                'actionFrontControllerSetMedia',
                'displayProductButtons',
                'displayOverrideTemplate',
                'actionValidateOrder',
                'displayAdminOrder',
                'displayProductPriceBlock',
                'displayHeader',
                'actionProductDelete',
                'displayProductAdditionalInfo',
                'actionProductUpdate',
                'actionObjectLanguageAddAfter',
                'actionAdminProductsListingFieldsModifier',
                'actionOrderStatusPostUpdate',
                'displayNav1',
            ]
        );
    }

    /**
     * Display booking products link on navigation bar.
     *
     * @return html An link with text booking product on shop name
     */
    public function hookDisplayNav1()
    {
        if (Configuration::get('WK_BOOKING_PRODUCTS_DISPLAY_LINK')) {
            $this->context->smarty->assign('wk_nav_link', 1);

            return $this->displayBookingOrdersLink();
        }
    }

    public function displayBookingOrdersLink()
    {
        $this->context->smarty->assign(
            'bookingproductlink',
            $this->context->link->getModuleLink(
                'psbooking',
                'bookingproduct'
            )
        );

        return $this->fetch('module:' . $this->name . '/views/templates/hook/bookingproduct_link.tpl');
    }

    public function install()
    {
        $objModuleDb = new WkPsBookingDb();
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        if (!parent::install()
            || !$this->registerModuleHooks()
            || !$objModuleDb->createTables()
            || !$this->callInstallTab()
            || !Configuration::updateValue('WK_CONSIDER_DATE_TO', 0)
            || !Configuration::updateValue('WK_FEATURE_PRICE_RULES_SHOW', 1)
            || !Configuration::updateValue('WK_MONDAY_FIRST_DAY_WEEK', 0)
            || !Configuration::updateValue('WK_BOOKING_PRODUCTS_DISPLAY_LINK', 1)
            || !Configuration::updateValue('WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT', 0)
            || !Configuration::updateValue(
                'WK_PRODUCT_FEATURE_PRICING_PRIORITY',
                'specific_date;special_day;date_range'
            )
            || !$this->createMailLangDirectoryWithFiles()
        ) {
            return false;
        }

        return true;
    }

    public function uninstallTab()
    {
        $moduleTabs = Tab::getCollectionFromModule($this->name);
        if (!empty($moduleTabs)) {
            foreach ($moduleTabs as $moduleTab) {
                if (!$moduleTab->delete()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function deleteConfigVars()
    {
        $config_keys = [
            'WK_CONSIDER_DATE_TO',
            'WK_PRODUCT_FEATURE_PRICING_PRIORITY',
            'WK_FEATURE_PRICE_RULES_SHOW',
            'WK_MONDAY_FIRST_DAY_WEEK',
            'WK_BOOKING_PRODUCTS_DISPLAY_LINK',
            'WK_BOOKING_DISPLAY_REGULAR_PRICE_AFTER_DISCOUNT',
            'WK_BOOKING_GEOLOCATION_API_KEY',
        ];
        foreach ($config_keys as $key) {
            if (!Configuration::deleteByName($key)) {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        $objModuleDb = new WkPsBookingDb();
        if (!parent::uninstall()
            || !$objModuleDb->dropTables()
            || !$this->deleteConfigVars()
            || !$this->uninstallTab()
        ) {
            return false;
        }

        return true;
    }

    // Ps all imported language's Mail directory will be created with all files in module's mails folder
    private function createMailLangDirectoryWithFiles($idLang = 0)
    {
        if ($idLang) {
            if ($language = new Language($idLang)) {
                $langISO = $language->iso_code;
                // Ignore 'en' directory because we already have this in our module folder
                if ($langISO != 'en') {
                    $this->createModuleMailDir($langISO);
                }
            }
        } else {
            if ($allLanguages = Language::getLanguages(false, $this->context->shop->id)) {
                foreach ($allLanguages as $language) {
                    $langISO = $language['iso_code'];
                    // Ignore 'en' directory because we already have this in our module folder
                    if ($langISO != 'en') {
                        $this->createModuleMailDir($langISO);
                    }
                }
            }
        }

        return true;
    }

    private function createModuleMailDir($langIso)
    {
        $moduleMailDir = _PS_MODULE_DIR_ . $this->name . '/views/templates/hook/mails/';
        // create lang dir if not exist in module mails directory
        if (!file_exists($moduleMailDir . $langIso)) {
            @mkdir($moduleMailDir . $langIso, 0777, true);
        }
        // Now if lang dir is exist or created by above code
        if (is_dir($moduleMailDir . $langIso)) {
            $mailEnDir = _PS_MODULE_DIR_ . $this->name . '/views/templates/hook/mails/en/';
            if (is_dir($mailEnDir)) {
                if ($allFiles = scandir($mailEnDir)) {
                    foreach ($allFiles as $fileName) {
                        if ($fileName != '.' && $fileName != '..') {
                            $source = $mailEnDir . $fileName;
                            $destination = $moduleMailDir . $langIso . '/' . $fileName;
                            // if file not exist in desti directory then create that file
                            if (!file_exists($destination) && file_exists($source)) {
                                Tools::copy($source, $destination);
                            }
                        }
                    }
                }
            }
        }

        return true;
    }
}
