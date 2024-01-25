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
class AdminBookingOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'WkBookingsOrders';
        $this->table = 'wk_bookings_orders';
        $this->bootstrap = true;
        $this->identifier = 'id';
        parent::__construct();

        $this->_select = 'CONCAT(c.`firstname`," ",c.`lastname`) as customer_name, ord.`reference`,
        CONCAT(a.time_from,"-", a.time_to) as time_slot';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'orders` ord ON (ord.`id_order` = a.`id_order`)';
        $this->_join .= 'LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (ord.`id_customer` = c.`id_customer`) ';
        $this->_join .= 'LEFT JOIN `' . _DB_PREFIX_ . 'wk_bookings_orders_shop` wxpfs
        ON (wxpfs.`id` = a.`id`)';
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_ALL) {
            // In case of All Shops
            $this->_select .= ',shp.`name` as wk_ps_shop_name';
            $this->_join .= 'JOIN `' . _DB_PREFIX_ . 'shop` shp ON (shp.`id_shop` = wxpfs.`id_shop`)';
        }

        $this->_join .= WkBookingProductInformation::addSqlAssociationCustom(
            'wk_bookings_orders',
            'a',
            false,
            null,
            false,
            $this->identifier
        );
        $this->_group = ' GROUP BY a.id';
        $this->fields_list = [
            'id_order' => [
                'title' => $this->l('Order ID'),
                'align' => 'center',
                'havingFilter' => true,
            ],
            'reference' => [
                'title' => $this->l('Reference'),
                'align' => 'center',
                'havingFilter' => true,
            ],
            'quantity' => [
                'title' => $this->l('Quantity'),
                'align' => 'center',
                'filter_key' => 'a!quantity',
            ],
            'customer_name' => [
                'title' => $this->l('Customer'),
                'align' => 'center',
                'havingFilter' => true,
            ],
            'booking_type' => [
                'title' => $this->l('Booking type'),
                'align' => 'center',
                'havingFilter' => true,
                'filter_key' => 'booking_type',
                'callback' => 'getBookingType',
                'type' => 'select',
                'list' => [
                    '1' => $this->l('Date range'),
                    '2' => $this->l('Time slots'),
                ],
            ],
            'date_from' => [
                'title' => $this->l('Date from'),
                'align' => 'center',
                'havingFilter' => true,
                'type' => 'date',
            ],
            'date_to' => [
                'title' => $this->l('Date to'),
                'align' => 'center',
                'havingFilter' => true,
                'type' => 'date',
                'callback' => 'checkEndDate',
            ],
            'time_slot' => [
                'title' => $this->l('Time slot'),
                'align' => 'center',
                'search' => false,
            ],
        ];
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_ALL) {
            // In case of All Shops
            $this->fields_list['wk_ps_shop_name'] = [
                'title' => $this->l('Shop'),
                'havingFilter' => true,
                'orderby' => false,
            ];
        }
        $this->addRowAction('view');
        $this->list_no_link = true;
    }

    public function initToolbar()
    {
        parent::initToolbar();
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['new'] = [
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add order'),
        ];
        if ($this->display == 'add') {
            $this->page_header_toolbar_title = $this->l('Create booking order');
        }
        parent::initPageHeaderToolbar();
    }

    public function renderForm()
    {
        if (Shop::getContext() != Shop::CONTEXT_SHOP) {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/_partials/shop_warning.tpl'
            );
        } else {
            $payment_modules = [];
            $orderStatus = [];
            foreach (PaymentModule::getInstalledPaymentModules() as $p_module) {
                $module = Module::getInstanceById((int) $p_module['id_module']);
                if (isset($module->name)) {
                    $payment_modules[] = ['name' => $module->name, 'display_name' => $module->displayName];
                }
            }
            $statuses = OrderState::getOrderStates((int) $this->context->language->id);
            asort($statuses);
            foreach ($statuses as $key => $status) {
                $orderStatus[] = ['id_order_state' => $status['id_order_state'], 'name' => $status['name']];
            }
            $this->context->smarty->assign(
                [
                    'payment_modules' => $payment_modules,
                    'order_status' => $orderStatus,
                ]
            );
            $this->fields_form = [
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ];

            $this->context->cookie->__set('wk_date_range_order', json_encode([]));
            $this->context->cookie->__set('wk_time_slot_order', json_encode([]));

            return parent::renderForm();
        }
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddwk_bookings_orders')) {
            $customerId = Tools::getValue('customer_id');
            $idCart = Tools::getValue('id_cart');
            $idAddress = Tools::getValue('id_address_delivery');
            $paymentName = Tools::getValue('payment_module_name');
            $orderStatusId = Tools::getValue('order_status_id');
            if (empty($customerId)) {
                $this->errors[] = $this->l('Please select customer');
            }
            if (!($customer = new Customer((int) $customerId, true, $this->context->language->id))
            ) {
                $this->errors[] = $this->l('Invalid customer');
            }
            if (empty($idAddress)) {
                $this->errors[] = $this->l('Please select address');
            }
            $address = new Address((int) $idAddress, true, $this->context->language->id);
            if (!$idAddress || !$address || !Validate::isLoadedObject($address)) {
                $this->errors[] = $this->l('Invalid address');
            }
            if (empty($idCart)) {
                $this->errors[] = $this->l('Please add product');
            } else {
                if (!($cart = new Cart((int) $idCart))
                ) {
                    $this->errors[] = $this->l('There is some issue while creating orders.');
                } else {
                    $bookingCart = new WkBookingsCart();
                    $products = $bookingCart->getCartInfo($idCart);
                    if (empty($products)) {
                        $this->errors[] = $this->l('Please add product');
                    }
                }
            }
            if (empty($paymentName)) {
                $this->errors[] = $this->l('Please select payment');
            }
            if (empty($orderStatusId)) {
                $this->errors[] = $this->l('Please select order status');
            }
            if (empty($this->errors)) {
                if (!Configuration::get('PS_CATALOG_MODE')) {
                    $payment_module = Module::getInstanceByName($paymentName);
                } else {
                    $payment_module = new BoOrder();
                }
                $payment_module->validateOrder(
                    (int) $idCart,
                    (int) $orderStatusId,
                    $cart->getOrderTotal(true, Cart::BOTH),
                    $payment_module->displayName,
                    null,
                    [],
                    null,
                    false,
                    $cart->secure_key,
                    null
                );
                if ($payment_module->currentOrder) {
                    Tools::redirectAdmin(self::$currentIndex . '&conf=3&token=' . $this->token);
                } else {
                    $this->errors[] = $this->l('Error on validate order');
                }
            } else {
                $this->display = 'add';
            }
        }
        parent::postProcess();
    }

    public function displayViewLink($token, $id)
    {
        $bookingOrderObj = new WkBookingsOrders((int) $id);
        if (Tools::version_compare(_PS_VERSION_, '1.7.6', '>')) {
            $wkOrderLink = $this->context->link->getAdminLink(
                'AdminOrders',
                true,
                ['vieworder' => 1, 'id_order' => (int) $bookingOrderObj->id_order],
                []
            );
        } else {
            $wkOrderLink = $this->context->link->getAdminLink('AdminOrders') .
            '&id_order=' . (int) $bookingOrderObj->id_order . '&vieworder';
        }
        $this->context->smarty->assign(
            [
                'wk_order_link' => $wkOrderLink,
            ]
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'psbooking/views/templates/admin/_partials/order_view.tpl'
        );
    }

    public function getBookingType($echo)
    {
        if ($echo == 1) {
            $return = $this->l('Date range');
        } else {
            $return = $this->l('Time slots');
        }

        return $return;
    }

    public function checkEndDate($date)
    {
        if (strtotime($date) > 0) {
            return date('m/d/Y', strtotime($date));
        }

        return '-';
    }

    public function ajaxProcessUpdateAddresses()
    {
        $data = [];
        if (($id_address_delivery = (int) Tools::getValue('id_address_delivery')) &&
            ($address_delivery = new Address((int) $id_address_delivery))) {
            $data['formated_address'] = AddressFormat::generateAddress($address_delivery, [], '<br />');
        }
        echo json_encode($data);
    }

    public function ajaxProcessGetCustomerAddress()
    {
        $idCustomer = Tools::getValue('id_customer');
        if ($idCustomer) {
            $customer = new Customer((int) $idCustomer);
            $addresses = $customer->getAddresses((int) $this->context->language->id);
            if (!empty($addresses)) {
                foreach ($addresses as &$data) {
                    $address = new Address((int) $data['id_address']);
                    $data['formated_address'] = AddressFormat::generateAddress($address, [], '<br />');
                }
                echo json_encode($addresses, true);
                exit;
            } else {
                exit(json_encode(
                    ['status' => 'failed']
                ));
            }
        } else {
            exit(json_encode(
                ['status' => 'failed']
            ));
        }
    }

    public function ajaxProcessSearchCustomer()
    {
        $searchQuery = trim(Tools::getValue('customer'));
        if ($searchQuery) {
            $customers = WkBookingsOrders::getCustomerForOrder($searchQuery);
            if ($customers) {
                echo json_encode($customers, true);
                exit;
            } else {
                exit(json_encode(
                    ['status' => 'failed', 'msg' => $this->l('No match found for entered customer name.')]
                ));
            }
        } else {
            exit(json_encode(
                ['status' => 'failed', 'msg' => $this->l('No match found for entered customer name.')]
            ));
        }
    }

    public function ajaxProcessAddBookingProduct()
    {
        $bookingType = Tools::getValue('booking_type');
        $idAddress = Tools::getValue('id_address_delivery');
        $customerId = Tools::getValue('customer_id');
        $idCart = Tools::getValue('id_cart');
        $errors = [];
        if (empty($customerId)) {
            $errors[] = $this->l('Please select customer');
        }
        if (empty($idAddress)) {
            $errors[] = $this->l('Please select address');
        }
        $priceDisplay = Group::getPriceDisplayMethod(Group::getCurrent()->id);
        if ($bookingType == 1) {
            $dateFrom = date('Y-m-d', strtotime(Tools::getValue('date_from')));
            $dateTo = date('Y-m-d', strtotime(Tools::getValue('date_to')));
            $quantity = Tools::getValue('quantity');
            $idProduct = Tools::getValue('id_product');
            $currentDate = date('Y-m-d');
            // validate values first
            if (!$idProduct) {
                $errors[] = $this->l('Product id is missing.');
            } elseif ($dateFrom == '' || !Validate::isDate($dateFrom)) {
                $errors[] = $this->l('Invalid date from.');
            } elseif ($dateTo == '' || !Validate::isDate($dateTo)) {
                $errors[] = $this->l('Invalid date to.');
            } elseif ($dateFrom < $currentDate) {
                $msg = $this->l('Date from should not be before current date.');
                $errors[] = $msg;
            } elseif (!Validate::isUnsignedInt($quantity) || !$quantity) {
                $errors[] = $this->l('Invalid quantity.');
            }
            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                if ($dateTo < $dateFrom) {
                    $errors[] = $this->l('Date to should be date after date from.');
                }
            } else {
                if ($dateTo <= $dateFrom) {
                    $errors[] = $this->l('Date to should be date after date from.');
                }
            }
            $objBookingProductInfo = new WkBookingProductInformation();
            $wkBookingOrder = new WkBookingsOrders();
            $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
            $bookedQty = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1, $idCart);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQty;
            $maxAvailableQuantity = $maxAvailableQuantity >= 0 ? $maxAvailableQuantity : 0;
            if (!$maxAvailableQuantity) {
                $e = $this->l('Required quantity for this date range not available.');
                $errors[] = $e;
            }
            $productQtyToCart = 0;
            if (!count($errors)) {
                $customer = new Customer((int) $customerId);
                $cart = new Cart(Tools::getValue('id_cart'));
                $cart->id_customer = $customerId;
                $cart->id_address_delivery = $idAddress;
                $cart->id_address_invoice = $idAddress;
                $cart->id_currency = $this->context->currency->id;
                $cart->secure_key = $customer->secure_key;
                if (Tools::getValue('id_cart')) {
                    $cart->update();
                } else {
                    $cart->add();
                }
                $cartId = $cart->id;
                // get booking product disable dates
                $objBookingDisableDates = new WkBookingProductDisabledDates();
                $wkBookingsCart = new WkBookingsCart();
                $bookingDisableDates = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateTo
                );
                if ($bookingDisableDates && count($bookingDisableDates)) {
                    $tempDateFrom = $dateFrom;
                    $bookingDateRanges = [];
                    $dateFromS = strtotime($dateFrom);
                    $dateToS = strtotime($dateTo);
                    for ($date = $dateFromS; $date <= $dateToS; $date = ($date + (24 * 60 * 60))) {
                        $currentDate = date('Y-m-d', $date);
                        $prevdate = date('Y-m-d', strtotime($currentDate) - 86400);
                        if (in_array($prevdate, $bookingDisableDates)) {
                            $tempDateFrom = $currentDate;
                        }
                        if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                            $lastDateCondition = strtotime($currentDate) == strtotime($dateTo)
                            && !in_array($currentDate, $bookingDisableDates);
                        } else {
                            $lastDateCondition = strtotime($currentDate) == strtotime($dateTo)
                            && !in_array($currentDate, $bookingDisableDates)
                            && !in_array($prevdate, $bookingDisableDates);
                        }
                        if ($lastDateCondition) {
                            $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                $idProduct,
                                $tempDateFrom,
                                $dateTo,
                                false,
                                $this->context->currency->id
                            );
                            if ($totalPrice) {
                                if (!$priceDisplay || $priceDisplay == 2) {
                                    $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                } elseif ($priceDisplay == 1) {
                                    $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                }
                            }
                            $bookingDateRanges[] = [
                                'date_from' => $tempDateFrom,
                                'date_to' => $dateTo,
                                'price' => Tools::displayPrice($productPrice),
                            ];
                            $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                $tempDateFrom,
                                $dateTo
                            );
                        } elseif (strtotime($currentDate) != strtotime($dateTo)
                            && strtotime($currentDate) != strtotime($dateFrom)
                            && !in_array($prevdate, $bookingDisableDates)
                            && in_array($currentDate, $bookingDisableDates)
                        ) {
                            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $tempDateFrom,
                                    $prevdate,
                                    false,
                                    $this->context->currency->id
                                );
                                if ($totalPrice) {
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                    }
                                }
                                $bookingDateRanges[] = [
                                    'date_from' => $tempDateFrom,
                                    'date_to' => $prevdate,
                                    'price' => Tools::displayPrice($productPrice),
                                ];
                                $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                    $tempDateFrom,
                                    $prevdate
                                );
                            } else {
                                $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                    $idProduct,
                                    $tempDateFrom,
                                    $currentDate,
                                    false,
                                    $this->context->currency->id
                                );
                                if ($totalPrice) {
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                                    }
                                }
                                $bookingDateRanges[] = [
                                    'date_from' => $tempDateFrom,
                                    'date_to' => $currentDate,
                                    'price' => Tools::displayPrice($productPrice),
                                ];
                                $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays(
                                    $tempDateFrom,
                                    $currentDate
                                );
                            }
                        }
                    }
                } else {
                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                        $idProduct,
                        $dateFrom,
                        $dateTo,
                        false,
                        $this->context->currency->id
                    );
                    if (!$priceDisplay || $priceDisplay == 2) {
                        $productPrice = $totalPrice['total_price_tax_incl'] * $quantity;
                    } elseif ($priceDisplay == 1) {
                        $productPrice = $totalPrice['total_price_tax_excl'] * $quantity;
                    }
                    $bookingDateRanges[] = [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'price' => Tools::displayPrice($productPrice),
                    ];
                    $productQtyToCart += WkBookingProductFeaturePricing::getNumberOfDays($dateFrom, $dateTo);
                }
                if (isset($bookingDateRanges) && count($bookingDateRanges)) {
                    $update_quantity = $cart->updateQty(
                        $productQtyToCart,
                        $idProduct,
                        null,
                        null,
                        'up',
                        $idAddress,
                        new Shop($cart->id_shop)
                    );
                    $goToOrder = false;
                    if ($update_quantity < 0) {
                        // not added
                    } elseif (!$update_quantity) {
                        // not added
                    } else {
                        $goToOrder = true;
                    }
                    if ($goToOrder) {
                        foreach ($bookingDateRanges as $dateRange) {
                            $dateRangeCartEntryExists = $wkBookingsCart->cartProductEntryExistsForDateRange(
                                $cartId,
                                $idProduct,
                                $dateRange['date_from'],
                                $dateRange['date_to']
                            );
                            if ($dateRangeCartEntryExists) {
                                $wkBookingsCart = new WkBookingsCart($dateRangeCartEntryExists['id']);
                                $wkBookingsCart->quantity += $quantity;
                            } else {
                                $wkBookingsCart = new WkBookingsCart();
                                $wkBookingsCart->id_cart = $cartId;
                                $wkBookingsCart->id_order = 0;
                                $wkBookingsCart->id_product = $idProduct;
                                $wkBookingsCart->booking_type = 1;
                                $wkBookingsCart->quantity = $quantity;
                                $wkBookingsCart->date_from = $dateRange['date_from'];
                                $wkBookingsCart->date_to = $dateRange['date_to'];
                                $wkBookingsCart->time_from = '';
                                $wkBookingsCart->time_to = '';
                                $wkBookingsCart->consider_last_date = Configuration::get('WK_CONSIDER_DATE_TO');
                            }
                            $wkBookingsCart->save();
                        }
                        $result['status'] = 'ok';
                        $result['id_cart'] = $cartId;
                        $orderLink = $this->context->link->getPageLink('order', null, (int) $cart->id_lang,
                            'step=3&recover_cart=' . (int) $cart->id
                            . '&token_cart=' . md5(_COOKIE_KEY_ . 'recover_cart_' . (int) $cart->id)
                        );
                        $result['order_link'] = $orderLink;
                        $cartProducts = $wkBookingsCart->getCartInfo($cartId);
                        $result['products'] = [];
                        if (!empty($cartProducts)) {
                            $objTimeSlot = new WkBookingProductTimeSlotPrices();
                            foreach ($cartProducts as $key => $cartProduct) {
                                $product = new Product((int) $cartProduct['id_product'], false, $this->context->language->id);
                                $result['products'][$key]['p_name'] = $product->name;
                                $result['products'][$key]['date_from'] = date('Y-m-d', strtotime($cartProduct['date_from']));
                                $result['products'][$key]['quantity'] = $cartProduct['quantity'];
                                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($cartProduct['id_product']);
                                $result['products'][$key]['type'] = $cartProduct['booking_type'];
                                $result['products'][$key]['id_cart_booking'] = $cartProduct['id'];

                                if ($cartProduct['booking_type'] == 1) {
                                    $result['products'][$key]['date_to'] = date('Y-m-d', strtotime($cartProduct['date_to']));
                                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                        $cartProduct['id_product'],
                                        $cartProduct['date_from'],
                                        $cartProduct['date_to'],
                                        false,
                                        $this->context->currency->id
                                    );
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'];
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'];
                                    }
                                    $result['products'][$key]['unit_price'] = Tools::displayPrice($productPrice);
                                    $result['products'][$key]['total_price'] = Tools::displayPrice($productPrice * $cartProduct['quantity']);
                                }
                                if ($cartProduct['booking_type'] == 2) {
                                    $result['products'][$key]['slot_from'] = $cartProduct['time_from'];
                                    $result['products'][$key]['slot_to'] = $cartProduct['time_to'];
                                    $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                        $cartProduct['id_product'],
                                        $cartProduct['date_from'],
                                        $cartProduct['time_from'],
                                        $cartProduct['time_to']
                                    );
                                    if (empty($slotDetails)) {
                                        $day = date('N', strtotime($cartProduct['date_from']));
                                        $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                            $cartProduct['id_product'],
                                            $day,
                                            $cartProduct['time_from'],
                                            $cartProduct['time_to']
                                        );
                                    }
                                    if ($slotDetails) {
                                        $bookingTimeSlotPrice['price_tax_excl'] = $slotDetails['price'];
                                        $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate(
                                            $cartProduct['id_product']
                                        );
                                        $bookingTimeSlotPrice['price_tax_incl'] = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                                        $bookingProductTotalArr = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                            $cartProduct['id_product'],
                                            $cartProduct['date_from'],
                                            $cartProduct['date_from'],
                                            $bookingTimeSlotPrice,
                                            $this->context->currency->id
                                        );
                                    }
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $bookingProductTotalArr['total_price_tax_incl'];
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $bookingProductTotalArr['total_price_tax_excl'];
                                    }
                                    $result['products'][$key]['unit_price'] = Tools::displayPrice($productPrice);
                                    $result['products'][$key]['total_price'] = Tools::displayPrice($productPrice * $cartProduct['quantity']);
                                }
                            }
                        }
                    }
                }
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $errors;
            }
        } elseif ($bookingType == 2) {
            $date = date('Y-m-d', strtotime(Tools::getValue('date')));
            $selectedSlots = Tools::getValue('selected_slots');
            $quantity = Tools::getValue('quantity');
            $idProduct = Tools::getValue('id_product');
            $bookingTimeSlotPrice = [];
            $bookingTimeSlotPrice['price_tax_excl'] = 0;
            // validate values first
            if (!$idProduct) {
                $errors[] = $this->l('Product id is missing.');
            }
            if ($selectedSlots) {
                foreach ($selectedSlots as $slot) {
                    if (empty($slot['quantity']) || !$slot['quantity']) {
                        $errors[] = $this->l('Invalid quantity found.');
                        break;
                    } elseif (!Validate::isInt($slot['quantity'])) {
                        $errors[] = $this->l('Invalid quantity found.');
                        break;
                    }
                }
            }
            if (!count($errors)) {
                $customer = new Customer((int) $customerId);
                $cart = new Cart(Tools::getValue('id_cart'));
                $cart->id_customer = $customerId;
                $cart->id_address_delivery = $idAddress;
                $cart->id_address_invoice = $idAddress;
                $cart->id_currency = $this->context->currency->id;
                $cart->secure_key = $customer->secure_key;
                if (Tools::getValue('id_cart')) {
                    $cart->update();
                } else {
                    $cart->add();
                }
                $cartId = $cart->id;
                $objBookingProductInfo = new WkBookingProductInformation();
                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($idProduct);
                if ($selectedSlots) {
                    $wkBookingOrder = new WkBookingsOrders();
                    $product = new Product((int) $idProduct, false, $this->context->language->id);
                    foreach ($selectedSlots as $key => $slot) {
                        $wkBookingsCart = new WkBookingsCart();
                        $objBookingSlot = new WkBookingProductTimeSlotPrices($slot['id_slot']);
                        $bookedSlotQuantity = $wkBookingOrder->getProductTimeSlotOrderedQuantity(
                            $idProduct,
                            $date,
                            $objBookingSlot->time_slot_from,
                            $objBookingSlot->time_slot_to,
                            1,
                            $cartId
                        );
                        $maxAvailableQuantity = $objBookingSlot->quantity - $bookedSlotQuantity;
                        if ($maxAvailableQuantity >= $slot['quantity']) {
                            $bookingTimeSlotPrice['price_tax_excl'] = $objBookingSlot->price;
                            $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate($idProduct);
                            $priceTaxIncl = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                            $bookingTimeSlotPrice['price_tax_incl'] = $priceTaxIncl;
                            $timeSlotFeaturePrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                $idProduct,
                                $date,
                                $date,
                                $bookingTimeSlotPrice,
                                $this->context->currency->id
                            );
                            $dateRangeCartEntryExists = $wkBookingsCart->cartProductEntryExistsForTimeSlot(
                                $cartId,
                                $idProduct,
                                $date,
                                $objBookingSlot->time_slot_from,
                                $objBookingSlot->time_slot_to
                            );
                            if ($dateRangeCartEntryExists) {
                                $wkBookingsCart = new WkBookingsCart($dateRangeCartEntryExists['id']);
                                $wkBookingsCart->quantity += $slot['quantity'];
                            } else {
                                $wkBookingsCart->id_cart = $cartId;
                                $wkBookingsCart->id_product = $idProduct;
                                $wkBookingsCart->booking_type = 2;
                                $wkBookingsCart->quantity = $slot['quantity'];
                                $wkBookingsCart->date_from = $date;
                                $wkBookingsCart->date_to = '';
                                $wkBookingsCart->time_from = $objBookingSlot->time_slot_from;
                                $wkBookingsCart->time_to = $objBookingSlot->time_slot_to;
                            }
                            $update_quantity = $cart->updateQty(
                                $slot['quantity'],
                                $idProduct,
                                null,
                                null,
                                'up',
                                $idAddress,
                                new Shop($cart->id_shop)
                            );
                            $goToOrder = false;
                            if ($update_quantity < 0) {
                                // not added
                            } elseif (!$update_quantity) {
                                // not added
                            } else {
                                $goToOrder = true;
                            }
                            if ($goToOrder) {
                                if (!$wkBookingsCart->save()) {
                                    $errors[] = $this->l('Some error occurred while saving slot cart data.');
                                }
                            } else {
                                $result['status'] = 'ko';
                                $errors[] = $this->l('Not added in cart.');
                            }
                        } else {
                            $errors[] = $this->l('Required quantity not available for slot ')
                            . $objBookingSlot->time_slot_from . ' - ' . $objBookingSlot->time_slot_to;
                        }
                        if (!count($errors)) {
                            $result['status'] = 'ok';
                            $result['id_cart'] = $cartId;
                            $orderLink = $this->context->link->getPageLink('order', null, (int) $cart->id_lang,
                                'step=3&recover_cart=' . (int) $cart->id
                                . '&token_cart=' . md5(_COOKIE_KEY_ . 'recover_cart_' . (int) $cart->id)
                            );
                            $result['order_link'] = $orderLink;
                        } else {
                            $result['status'] = 'ko';
                            $result['errors'] = $errors;
                        }
                    }
                    if (empty($errors)) {
                        $objBookingsCart = new WkBookingsCart();
                        $cartProducts = $objBookingsCart->getCartInfo($cartId);
                        $result['products'] = [];
                        if (!empty($cartProducts)) {
                            $objTimeSlot = new WkBookingProductTimeSlotPrices();
                            foreach ($cartProducts as $key => $cartProduct) {
                                $product = new Product((int) $cartProduct['id_product'], false, $this->context->language->id);
                                $result['products'][$key]['p_name'] = $product->name;
                                $result['products'][$key]['date_from'] = date('Y-m-d', strtotime($cartProduct['date_from']));
                                $result['products'][$key]['quantity'] = $cartProduct['quantity'];
                                $bookingProductInformation = $objBookingProductInfo->getBookingProductInfoByIdProduct($cartProduct['id_product']);
                                $result['products'][$key]['type'] = $cartProduct['booking_type'];
                                $result['products'][$key]['id_cart_booking'] = $cartProduct['id'];

                                if ($cartProduct['booking_type'] == 1) {
                                    $result['products'][$key]['date_to'] = date('Y-m-d', strtotime($cartProduct['date_to']));
                                    $totalPrice = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                        $cartProduct['id_product'],
                                        $cartProduct['date_from'],
                                        $cartProduct['date_to'],
                                        false,
                                        $this->context->currency->id
                                    );
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $totalPrice['total_price_tax_incl'];
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $totalPrice['total_price_tax_excl'];
                                    }
                                    $result['products'][$key]['unit_price'] = Tools::displayPrice($productPrice);
                                    $result['products'][$key]['total_price'] = Tools::displayPrice($productPrice * $cartProduct['quantity']);
                                }
                                if ($cartProduct['booking_type'] == 2) {
                                    $result['products'][$key]['slot_from'] = $cartProduct['time_from'];
                                    $result['products'][$key]['slot_to'] = $cartProduct['time_to'];
                                    $slotDetails = $objTimeSlot->getProductTimeSlotDetails(
                                        $cartProduct['id_product'],
                                        $cartProduct['date_from'],
                                        $cartProduct['time_from'],
                                        $cartProduct['time_to']
                                    );
                                    if (empty($slotDetails)) {
                                        $day = date('N', strtotime($cartProduct['date_from']));
                                        $slotDetails = $objTimeSlot->getProductTimeDayWiseSlotDetails(
                                            $cartProduct['id_product'],
                                            $day,
                                            $cartProduct['time_from'],
                                            $cartProduct['time_to']
                                        );
                                    }
                                    if ($slotDetails) {
                                        $bookingTimeSlotPrice['price_tax_excl'] = $slotDetails['price'];
                                        $taxRate = (float) WkBookingProductInformation::getAppliedProductTaxRate(
                                            $cartProduct['id_product']
                                        );
                                        $bookingTimeSlotPrice['price_tax_incl'] = $bookingTimeSlotPrice['price_tax_excl'] * ((100 + $taxRate) / 100);
                                        $bookingProductTotalArr = WkBookingProductFeaturePricing::getBookingProductTotalPrice(
                                            $cartProduct['id_product'],
                                            $cartProduct['date_from'],
                                            $cartProduct['date_from'],
                                            $bookingTimeSlotPrice,
                                            $this->context->currency->id
                                        );
                                    }
                                    if (!$priceDisplay || $priceDisplay == 2) {
                                        $productPrice = $bookingProductTotalArr['total_price_tax_incl'];
                                    } elseif ($priceDisplay == 1) {
                                        $productPrice = $bookingProductTotalArr['total_price_tax_excl'];
                                    }
                                    $result['products'][$key]['unit_price'] = Tools::displayPrice($productPrice);
                                    $result['products'][$key]['total_price'] = Tools::displayPrice($productPrice * $cartProduct['quantity']);
                                }
                            }
                        }
                    }
                }
            } else {
                $result['status'] = 'ko';
                $result['errors'] = $errors;
            }
        }
        exit(json_encode($result));
    }

    public function ajaxProcessGetProductForBooking()
    {
        $idProduct = trim(Tools::getValue('id_product'));
        $idCart = trim(Tools::getValue('id_cart'));
        $objBookingProductInformation = new WkBookingProductInformation();
        $idProduct = Tools::getValue('id_product');
        $product = new Product((int) $idProduct, false, $this->context->language->id);
        if ($bookingProductInformation = $objBookingProductInformation->getBookingProductInfoByIdProduct($idProduct)) {
            $dateFrom = date('Y-m-d', strtotime('+' . $bookingProductInformation['booking_before'] . ' hours'));
            if (Configuration::get('WK_CONSIDER_DATE_TO')) {
                $dateTo = date('Y-m-d', strtotime($dateFrom));
            } else {
                $dateTo = date('Y-m-d', strtotime('+1 day', strtotime($dateFrom)));
            }
            $diff = strtotime($dateFrom) - strtotime(date('Y-m-d'));
            // 1 day = 24 hours
            // 24 * 60 * 60 = 86400 seconds
            $availableAfter = $diff / 86400;
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
            $this->context->smarty->assign(
                [
                    'date_from' => date('d-m-Y', strtotime($dateFrom)),
                    'date_to' => date('d-m-Y', strtotime($dateTo)),
                    'available_after' => $availableAfter,
                    'idProduct' => $idProduct,
                    'product_name' => $product->name,
                    'disabledDays' => $disabledDays,
                    'disabledDates' => $disabledDates,
                    'timeSlotDays' => $timeSlotDays,
                    'timeSlotType' => $timeSlotType,
                    'selectedDates' => json_encode($selectedDates),
                ]
            );
            $wkBookingOrder = new WkBookingsOrders();
            $bkingTmSltPriceToday = false;
            $bkingTmSltPrice = false;
            $objBookingDisableDates = new WkBookingProductDisabledDates();
            $cookiesQuantity = 0;
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
                            1,
                            $idCart
                        );
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
                            } elseif ($priceDisplay == 1) {
                                $bookingTimeSlots[$key]['formated_slot_price'] = Tools::displayPrice(
                                    $ttlFeatPri['total_price_tax_excl']
                                );
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
                        } elseif ($priceDisplay == 1) {
                            $productFeaturePrice = $totalPrice['total_price_tax_excl'];
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
                    } elseif ($priceDisplay == 1) {
                        $productFeaturePrice = $totalPrice['total_price_tax_excl'];
                    }
                }
                // get disable dates info for current selected dates
                $selectedDatesDisableInfo = $objBookingDisableDates->getBookingProductDisableDatesInDateRange(
                    $idProduct,
                    $dateFrom,
                    $dateTo
                );
            }

            $bookedQuantity = $wkBookingOrder->getProductOrderedQuantityInDateRange($idProduct, $dateFrom, $dateTo, 1, $idCart);
            $maxAvailableQuantity = $bookingProductInformation['quantity'] - $bookedQuantity - $cookiesQuantity;
            if ($maxAvailableQuantity < 0) {
                $maxAvailableQuantity = 0;
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
                    'wk_google_map_key' => $googleMapKey,
                    'wk_show_map' => $bookingProductInformation['show_map'],
                ]
            );
            $displayData = $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/_partials/booking_product.tpl'
            );
            echo $displayData;
            exit;
        }
    }

    public function ajaxProcessRemoveFromCart()
    {
        $result = [];
        $objBookingsCart = new WkBookingsCart(Tools::getValue('id_cart_booking'));
        $idProduct = $objBookingsCart->id_product;
        $idCart = $objBookingsCart->id_cart;
        $cart = new Cart((int) $idCart);
        $bookingType = $objBookingsCart->booking_type;
        if ($bookingType == 1) {
            $daysCount = (int) WkBookingProductFeaturePricing::getNumberOfDays(
                $objBookingsCart->date_from,
                $objBookingsCart->date_to
            );
        } else {
            $daysCount = 1;
        }
        $quantityToReduce = ($daysCount * (int) $objBookingsCart->quantity);
        if ($cart->updateQty(
            (int) $quantityToReduce,
            (int) $idProduct,
            null,
            false,
            'down',
            0,
            null,
            true
        )) {
            if ($objBookingsCart->delete()) {
                $result['status'] = 'ok';
                $result['msg'] = $this->l('Cart product successfully updated.');
            } else {
                $result['status'] = 'failed';
                $result['msg'] = $this->l('Error while deleting product from cart.');
            }
        } else {
            $result['status'] = 'failed';
            $result['msg'] = $this->l('Error while updating cart product.');
        }
        exit(json_encode($result));
    }

    public function ajaxProcessSendMailValidateOrder()
    {
        $cart = new Cart((int) Tools::getValue('id_cart'));
        if (Validate::isLoadedObject($cart)) {
            $customer = new Customer((int) $cart->id_customer);
            if (Validate::isLoadedObject($customer)) {
                $mailVars = [
                    '{order_link}' => Context::getContext()->link->getPageLink('order', false, (int) $cart->id_lang, 'step=3&recover_cart=' . (int) $cart->id . '&token_cart=' . md5(_COOKIE_KEY_ . 'recover_cart_' . (int) $cart->id)),
                    '{firstname}' => $customer->firstname,
                    '{lastname}' => $customer->lastname,
                ];
                if (
                    Mail::Send(
                        (int) $cart->id_lang,
                        'backoffice_order',
                        Mail::l('Process the payment of your order', $cart->id_lang),
                        $mailVars,
                        $customer->email,
                        $customer->firstname . ' ' . $customer->lastname,
                        null,
                        null,
                        null,
                        null,
                        _PS_MAIL_DIR_,
                        true,
                        $cart->id_shop)
                ) {
                    exit(json_encode(['errors' => false, 'msg' => $this->l('The email was sent to your customer.')]));
                }
            }
        }
        exit(json_encode(['errors' => true, 'msg' => $this->l('Error in sending the email to your customer.')]));
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        Media::addJSDef(
            [
                'bookings_in_select_range' => $this->l('Following bookings will be created for selected date range'),
                'dateRangeText' => $this->l('Date range'),
                'priceText' => $this->l('Price'),
                'To_txt' => $this->l('To'),
                'disable_date_title' => $this->l('Bookings are unavailable on this date'),
                'no_slots_available_text' => $this->l('No slots available'),
                'for_txt' => $this->l('For'),
                'booking_one_slot_select_msg' => $this->l('One slot must be selected for booking.'),
                'considerDateToConfiguration' => Configuration::get('WK_CONSIDER_DATE_TO'),
                'wk_monday_first_day_week' => Configuration::get('WK_MONDAY_FIRST_DAY_WEEK'),
                'wkBookingCartLink' => $this->context->link->getModuleLink('psbooking', 'BookingProductCartActions'),
                'booking_order_url' => $this->context->link->getAdminLink('AdminBookingOrders'),
                'booking_product_price_plans_url' => $this->context->link->getAdminLink(
                    'AdminBookingProductPricePlansSettings'
                ),
            ]
        );

        $this->addCSS(_MODULE_DIR_ . $this->module->name . '/views/css/booking_order.css');
        $this->addJqueryPlugin(['autocomplete']);
        $this->addJS(_MODULE_DIR_ . $this->module->name . '/views/js/booking_order.js');
    }
}
