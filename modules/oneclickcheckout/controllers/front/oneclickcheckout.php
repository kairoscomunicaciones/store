<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer tohttp://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2015 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 */

class OneclickcheckoutOneclickcheckoutModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $qty = Tools::getValue('qty');
        $id_product = Tools::getValue('id');
        $id_product_attribute = 0;
        if (Tools::getIsset('group')) {
            $id_product_attribute = (int)Product::getIdProductAttributesByIdAttributes(
                $id_product,
                (array)Tools::getValue('group')
            );
        }
        if ($this->context->cart->id == null) {
            $this->context->cookie->old_cart_id = 0;
            $this->context->cookie->on_order_page_set = 'yes';
            if ($this->context->cookie->id_guest) {
                $guest = new Guest($this->context->cookie->id_guest);
                $this->context->cart->mobile_theme = $guest->mobile_theme;
            }
            $this->context->cart->add();
            if ($this->context->cart->id) {
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
            }
            $this->context->cart->updateQty($qty, $id_product, $id_product_attribute);
            $this->context->cart->save();
            $this->context->cookie->new_cart_id = $this->context->cart->id;
            $link = $this->getFinalCheckoutURL();
            echo $link;
            die;
        } else {
            $this->context->cookie->old_cart_id = $this->context->cart->id;
            $this->context->cookie->on_order_page_set = 'yes';
            $this->context->cart = new Cart();
            $this->context->cart->id_lang = $this->context->language->id;
            $this->context->cart->id_currency = $this->context->currency->id;
            $this->context->cart->add();
            $this->context->cart->updateQty((int) $qty, (int) $id_product, (int) $id_product_attribute);
            if ($this->context->cookie->id_guest) {
                $guest = new Guest($this->context->cookie->id_guest);
                $this->context->cart->mobile_theme = $guest->mobile_theme;
            }
            $this->context->cart->save();
            if ($this->context->cart->id) {
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
            }
            $this->context->cookie->new_cart_id = $this->context->cart->id;
            $link = $this->getFinalCheckoutURL();
            echo $link;
            die;
        }
    }
    
    private function getFinalCheckoutURL()
    {
        return $this->context->link->getPageLink(
            'order',
            null,
            $this->context->language->id,
            array(),
            false,
            null,
            true
        );
    }
}
