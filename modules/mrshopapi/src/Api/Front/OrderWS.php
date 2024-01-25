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

use Carrier;
use Context;
use Currency;
use DateTime;
use Db;
use MrAPPs\MrShopApi\Api\BaseWS;
use MrAPPs\MrShopApi\Handler\Api\ResponseHandler;
use MrAPPs\MrShopApi\Handler\BookingHandler;
use MrAPPs\MrShopApi\Handler\CartHandler;
use MrAPPs\MrShopApi\Handler\CheckoutCustomFieldsHandler;
use MrAPPs\MrShopApi\Handler\CustomizationHandler;
use MrAPPs\MrShopApi\Handler\Payment\PaymentHandler;
use MrAPPs\MrShopApi\Handler\ReorderHandler;
use MrAPPs\MrShopApi\Service\ExternalModuleService;
use MrAPPs\MrShopApi\Utils\ApiUtils;
use MrAPPs\MrShopApi\Utils\ColorUtils;
use MrAPPs\MrShopApi\Utils\FermopointUtils;
use Order;
use OrderDetail;
use OrderReturn;
use ProductDownload;
use Tools;
use Validate;

class OrderWS extends BaseWS
{
    /** @var CartHandler $cartHandler */
    private $cartHandler = null;

    /** @var PaymentHandler $paymentHandler */
    private $paymentHandler = null;

    /** @var CheckoutCustomFieldsHandler $paymentHandler */
    private $customFieldsHandler = null;

    private $externalModuleService;

    private $colorUtils;

    private $bookingHandler = null;

    private $customizationHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);

        $this->cartHandler    = new CartHandler($this->dataHandler->getGuestId());
        $this->paymentHandler = new PaymentHandler($dataHandler);
        $this->bookingHandler = new BookingHandler();
        if ($this->isModuleActive('customfields')) {
            $this->customFieldsHandler = new CheckoutCustomFieldsHandler();
        }
        $this->cacheEnabled = false;
        $this->colorUtils   = new ColorUtils();
        $this->customizationHandler = new CustomizationHandler;
        $this->externalModuleService = new ExternalModuleService();
    }

    private function composeTrackingUrl($id_order, $id_carrier)
    {
        if (FermopointUtils::getFermopointCarrierId() == $id_carrier) {
            $query = '
                SELECT fp.`ticketId` as `tracking_number`, c.`url`
                FROM `'._DB_PREFIX_.'fermopoint_booking` fp
                LEFT JOIN `'._DB_PREFIX_.'order_carrier` oc ON oc.`id_order` = fp.`id_order`
                LEFT JOIN `'._DB_PREFIX_.'carrier` c ON c.`id_carrier` = oc.`id_carrier`
                WHERE fp.`id_order` = '.(int) $id_order.'
                AND c.`id_carrier` = '.(int) $id_carrier.'
                ORDER BY fp.`date_add` DESC
            ';
        } else {
            $query = '
                SELECT oc.`tracking_number`, c.`url`
                FROM `'._DB_PREFIX_.'order_carrier` oc
                LEFT JOIN `'._DB_PREFIX_.'carrier` c ON oc.`id_carrier` = c.`id_carrier`
                WHERE oc.`id_order` = '.(int) $id_order.'
                AND oc.`id_carrier` = '.(int) $id_carrier.'
                ORDER BY oc.date_add ASC
            ';
        }

        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);

        if ($row) {
            $trackingNumber = isset($row['tracking_number']) ? trim($row['tracking_number']) : '';
            $url = isset($row['url']) ? trim($row['url']) : '';

            if (Tools::strlen($trackingNumber) > 0 && Tools::strlen($url) > 0) {
                return str_replace('@', $trackingNumber, $url);
            }
        }

        return null;
    }

    private function refactorOrder(&$item, $currency = null)
    {
        unset($item['id_currency']);

        $item['tracking_url'] = $this->composeTrackingUrl($item['id'], $item['id_carrier']);

        $item['delivery_date'] = (int) $item['delivery_date'];
        $item['date_add'] = (int) $item['date_add'];

        /**
         * "_val" variant is inserted before beacase the non "_val" variant changes the original value
         */
        $item['total_paid_val']      = $this->priceFormatter->getPriceData($item['total_paid']);
        $item['total_paid']          = $this->priceFormatter->format(Tools::ps_round($item['total_paid'], 2), $currency);
        $item['total_paid_real_val'] = $this->priceFormatter->getPriceData($item['total_paid_real']);
        $item['total_paid_real']     = $this->priceFormatter->format(Tools::ps_round($item['total_paid_real'], 2), $currency);
        $item['total_shipping_val']  = $this->priceFormatter->getPriceData($item['total_shipping']);
        $item['total_shipping']      = Tools::ps_round($item['total_shipping'], 2);

        if (Tools::strpos(trim($item['current_state_color']), '#') === false) {
            $item['current_state_color'] = $this->colorUtils->convertNameToHex(Tools::strtolower(trim($item['current_state_color'])));
        }

        $item['paid'] = (bool) $item['paid'];
        $item['delivery'] = (bool) $item['delivery'];
        $item['shipped'] = (bool) $item['shipped'];

        $addressFields = ['id_address', 'id_customer', 'id_country', 'id_state', 'alias', 'address1', 'postcode', 'city', 'company', 'lastname', 'firstname', 'phone', 'vat_number', 'dni', 'other'];

        // Invoice and Delivery addresses
        $item['address_invoice'] = [];
        $item['address_delivery'] = [];
        foreach ($addressFields as $field) {
            $invoiceField = 'invoice_'.$field;
            $deliveryField = 'delivery_'.$field;

            $newField = $field == 'id_address' ? 'id' : $field;

            $item['address_invoice'][$newField] = array_key_exists($invoiceField, $item) ? $item[$invoiceField] : '';
            $item['address_delivery'][$newField] = array_key_exists($deliveryField, $item) ? $item[$deliveryField] : '';
            unset($item[$invoiceField]);
            unset($item[$deliveryField]);
        }
    }

    private function getQuery($id_customer)
    {
        $includeTaxes = ApiUtils::includeTaxes((int) $id_customer);
        $totalPaidField = $includeTaxes ? 'total_paid' : 'total_paid_tax_excl';
        $totalShippingField = $includeTaxes ? 'total_shipping' : 'total_shipping_tax_excl';

        return 'SELECT DISTINCT a.id_order AS id, a.id_currency, a.id_cart, a.module, a.payment, COALESCE(UNIX_TIMESTAMP(a.delivery_date),0) AS delivery_date,
                a.reference, a.'.pSQL($totalPaidField).' AS total_paid, a.total_paid_real, COALESCE(UNIX_TIMESTAMP(a.date_add),0) AS date_add,
                a.current_state AS id_order_state, sl.name AS current_state, 1-COALESCE(os.hidden,0) AS show_order_status,
                os.paid, os.delivery, os.shipped, os.color AS current_state_color,
                a.id_address_delivery, a.id_address_invoice, a.id_carrier, a.'.pSQL($totalShippingField).' AS total_shipping,
                    
                ai.id_address AS invoice_id_address, ai.id_customer AS invoice_id_customer, ai.id_country AS invoice_id_country,
                ai.id_state AS invoice_id_state, ai.alias AS invoice_alias, ai.address1 AS invoice_address1, ai.postcode AS invoice_postcode,
                ai.city AS invoice_city, ai.company AS invoice_company, ai.lastname AS invoice_lastname, ai.firstname AS invoice_firstname,
                ai.phone AS invoice_phone, ai.vat_number AS invoice_vat_number, ai.dni AS invoice_dni, ai.other AS invoice_other,                      

                ad.id_address AS delivery_id_address, ad.id_customer AS delivery_id_customer, ad.id_country AS delivery_id_country,
                ad.id_state AS delivery_id_state, ad.alias AS delivery_alias, ad.address1 AS delivery_address1, ad.postcode AS delivery_postcode,
                ad.city AS delivery_city, ad.company AS delivery_company, ad.lastname AS delivery_lastname, ad.firstname AS delivery_firstname,
                ad.phone AS delivery_phone, ad.vat_number AS delivery_vat_number, ad.dni AS delivery_dni, ad.other AS delivery_other
                
                FROM `'._DB_PREFIX_.'orders` a
                INNER JOIN `'._DB_PREFIX_.'order_state` os ON a.current_state=os.id_order_state
                INNER JOIN `'._DB_PREFIX_.'order_state_lang` sl ON a.current_state=sl.id_order_state
                INNER JOIN `'._DB_PREFIX_.'address` ai ON a.id_address_invoice=ai.id_address
                INNER JOIN `'._DB_PREFIX_.'address` ad ON a.id_address_delivery=ad.id_address
                WHERE a.id_customer = '.(int) $id_customer.' AND sl.id_lang='.(int) Context::getContext()->language->id;
    }

    public function getList($params, $id_customer = null)
    {
        if (array_key_exists('limit', $params)) {
            /** @deprecated please remove this "if" branch when demo app is updated (>= 1.0.5 - 12/10/2018) */
            // Retrocompatibilità (limit:"0,10")
            $tmp = explode(',', trim($params['limit']));
            $offset = count($tmp) > 1 ? (int) $tmp[0] : 0;
        } else {
            $offset = array_key_exists('offset', $params) ? (int) $params['offset'] : 0;
        }
        $limit = $this->defaultLimit;
        $query = $this->getQuery($id_customer).' ORDER BY a.date_add DESC LIMIT '.$offset.','.$limit;

        $data = $this->refactorData($query);

        /* Format prices */
        if (count($data)) {
            $currency = Currency::getCurrencyInstance((int) $data[0]['id_currency']);
            foreach ($data as &$item) {
                $this->refactorOrder($item, $currency);
            }
        }

        $this->response(true, null, $data);
    }

    public function getDetail($id, $id_customer = null)
    {
        $orderInstance = new Order((int) $id);
        $query = $this->getQuery($id_customer).'
                  AND a.id_order = '.(int) $id;

        $data = $this->refactorData($query, false);

        if ($data !== false) {
            $currency = Currency::getCurrencyInstance((int) $data['id_currency']);
            $this->refactorOrder($data, $currency);

            // ------------------------------------------------------------------------

            // Cart Rules
            $cartRules = $this->cartHandler->getCartRules($data['id_cart']);
            $data['cart_rules'] = $cartRules;

            // ------------------------------------------------------------------------

            // Note
            $notesQuery = 'SELECT a.message
                  FROM `'._DB_PREFIX_.'message` a
                  WHERE a.id_customer =  '.(int) $id_customer.'
                  AND a.id_order = '.(int) $id.'
                  AND a.private = 1 
                  ORDER BY date_add ASC 
                  LIMIT 1';

            $notes = $this->refactorData($notesQuery);
            $data['note'] = ApiUtils::isArray($notes) && count($notes) > 0 ? $notes[0]['message'] : '';

            // ------------------------------------------------------------------------

            // Payment method
            $totalShipping = (float) $data['total_shipping'];
            unset($data['total_shipping']);
            $data['payment_method'] = null;

            $paymentMethod = $this->paymentHandler->getSinglePaymentMethod($data['module']);

            if ($paymentMethod) {
                $data['payment_method'] = $paymentMethod;
            }

            // ------------------------------------------------------------------------

            // Carrier
            $isFermopoint = (int) $data['id_carrier'] == (int) FermopointUtils::getFermopointCarrierId();

            $carrier = new Carrier((int) $data['id_carrier'], (int) $this->context->language->id);
            if (Validate::isLoadedObject($carrier)) {
                $data['carrier'] = [
                    'id_carrier' => (int) $data['id_carrier'],
                    'id_reference' => (int) $carrier->id_reference,
                    'name' => $carrier->name,
                    'delay' => $carrier->delay,
                    'img' => file_exists(_PS_SHIP_IMG_DIR_.(int) $data['id_carrier'].'.jpg')
                        ? _THEME_SHIP_DIR_.(int) $data['id_carrier'].'.jpg'
                        : null,
                    'price' => $totalShipping == 0
                        ? $this->module->l('Free', 'orderws')
                        : $this->priceFormatter->format($totalShipping, $currency),
                    'is_fermopoint' => $isFermopoint
                ];
            }

            $data['fermopoint_status'] = $isFermopoint
                    ? FermopointUtils::getFermopointStatusFor($id)
                    : null;

            // ------------------------------------------------------------------------

            // Is virtual order?
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                SELECT COUNT(p.`is_virtual`) AS n
                FROM `'._DB_PREFIX_.'order_detail` od
                LEFT JOIN `'._DB_PREFIX_.'product` p ON od.`product_id` = p.`id_product`
                WHERE od.`id_order` = '.(int) $id.'
                AND p.`id_product` IS NOT NULL
                AND p.`is_virtual` = 0
            ');
            $data['is_virtual_order'] = ($result && is_array($result) && isset($result['n']) && (int) $result['n'] == 0);

            // ------------------------------------------------------------------------

            // Custom fields

            if ($this->isModuleActive('customfields') && $this->customFieldsHandler) {
                $data['custom_fields'] = $this->customFieldsHandler->getOrderFields($id);
            }

            // ------------------------------------------------------------------------

            $detailsQuery = 'SELECT a.id_order_detail AS id, a.id_order_detail, a.product_id, a.product_attribute_id AS id_product_attribute,
                a.product_name AS name, a.product_quantity AS quantity , a.unit_price_tax_incl AS unit_price,
                a.total_price_tax_incl AS total_price, o.id_currency,
                COALESCE(a.download_hash, "") AS download_hash, COALESCE(a.product_quantity_refunded, 0) AS product_quantity_refunded,
                COALESCE(a.product_quantity_return, 0) AS product_quantity_return, os.logable, a.id_customization
                  FROM `'._DB_PREFIX_.'order_detail` a
                  LEFT JOIN `'._DB_PREFIX_.'orders` o ON a.id_order = o.id_order
                  LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
                  WHERE o.id_customer =  '.(int) $id_customer.'
                  AND a.id_order = '.(int) $id; // Added join with order just for security reasons and check customer

            $details = $this->refactorData($detailsQuery);

            if (count($details)) {
                /** Format prices */
                $currency = Currency::getCurrencyInstance((int) $details[0]['id_currency']);
                $bookingProducts = [];
                OrderReturn::addReturnedQuantity($details, (int) $data['id']);
                foreach ($details as $key => &$detail) {
                    $detail['total_price'] = $this->priceFormatter->format(Tools::ps_round($detail['total_price'], 2), $currency);
                    $detail['unit_price'] = $this->priceFormatter->format(Tools::ps_round($detail['unit_price'], 2), $currency);
                    if ($detail['id_product_attribute'] === 0) {
                        $detail['id_product_attribute'] = null;
                    }

                    $idProduct = (int) $detail['product_id'];

                    $product = new \Product($idProduct);
                    $productImage = ProductBaseWS::getCoverImages($idProduct);
                    $detail['image'] = $productImage['image'];

                    // Virtual product
                    $detail['product_download_url'] = null;
                    if (Tools::strlen(trim($detail['download_hash'])) > 0 &&
                        (int) $detail['logable'] > 0 &&
                        (int) $detail['product_quantity_refunded'] == 0 &&
                        (int) $detail['product_quantity_return'] == 0
                    ) {
                        $download = new ProductDownload((int) ProductDownload::getIdFromIdProduct($idProduct));

                        if ($download && Validate::isLoadedObject($download) &&
                            Tools::strlen($download->filename) > 0 && Tools::strlen($download->display_filename) > 0) {
                            $params = sprintf('key=%s-%s', $download->filename, trim($detail['download_hash']));
                            $downloadUrl = $this->context->link->getPageLink('get-file', true, null, $params);
                            $detail['product_download_url'] = $downloadUrl;
                        }
                    }

                    /*
                     * PSBOOKING
                     * A single booking order product is explosed into many products (one for each date interval)
                     */
                    if ($this->bookingHandler->isModuleActive() && $this->bookingHandler->isBookingProduct($idProduct)) {
                        $bookingProductItems = $this->bookingHandler->getOrderItem($detail, $currency, $id);
                        if (count($bookingProductItems)) {
                            unset($details[$key]);
                            $bookingProducts = array_merge($bookingProducts, $bookingProductItems);
                        }
                    }

                    if ($this->dataHandler->isModuleActive('egbuycentimeter') && (!empty($product->unity))) {
                        $quantities = $this->externalModuleService->egbuycentimeterGetQuantities(
                            $product->id,
                            0,
                            $detail['quantity']
                        );

                        $detail['unit_increment'] = $quantities['unit_increment'];
                    } else {
                        $detail['unit_increment'] = null;
                    }

                    if (array_key_exists('qty_returned', $detail)) {
                        $detail['quantity_returned'] = (int) $detail['qty_returned'];
                    }

                    $idCustomization = !empty($detail['id_customization']) ? (int) $detail['id_customization'] : null;
                    $detail['customized_fields'] = [];
                    if ($idCustomization != false) {
                        $detail['customized_fields'] = $this->customizationHandler->getProductCustomizationValues($orderInstance->id_cart, $idProduct);
                    }

                    // Cleanup
                    unset($detail['download_hash']);
                    unset($detail['logable']);
                    unset($detail['product_quantity_refunded']);
                    unset($detail['product_quantity_return']);
                    unset($detail['id_order_detail']);
                    unset($detail['qty_returned']);
                }
            }

            if (!empty($bookingProducts)) {
                $details = array_merge($details, $bookingProducts);
            }

            $data['details'] = $details;
            $data['is_returnable'] = $orderInstance->isReturnable();

            $this->response(true, null, $data);
        } else {
            ResponseHandler::notFound($this->module->l('Order not found', 'orderws'));
        }
    }

    public function reorder($customerId)
    {
        $bodyParams = $this->dataHandler->getBodyParams();

        $idOrder = array_key_exists('id_order', $bodyParams) ? $bodyParams['id_order'] : Tools::getValue('id_order');

        $handler = new ReorderHandler($this->module, $this->context);
        $handler->reorder($idOrder, $customerId);
    }
}
