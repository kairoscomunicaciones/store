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

namespace MrAPPs\MrShopApi\Handler\Api;

use Context;
use Customer;

use Guest;
use MrAPPs\MrShopApi\Api\Front\AddressWS;
use MrAPPs\MrShopApi\Api\Front\AllowedCarriersCartWS;
use MrAPPs\MrShopApi\Api\Front\AppHomeWS;
use MrAPPs\MrShopApi\Api\Front\AssistanceWS;
use MrAPPs\MrShopApi\Api\Front\BestSalesWS;
use MrAPPs\MrShopApi\Api\Front\CartSummaryWS;
use MrAPPs\MrShopApi\Api\Front\CartWS;
use MrAPPs\MrShopApi\Api\Front\CategoryWS;
use MrAPPs\MrShopApi\Api\Front\CheckoutCustomFieldsWS;
use MrAPPs\MrShopApi\Api\Front\CmsWS;
use MrAPPs\MrShopApi\Api\Front\ContactsWS;
use MrAPPs\MrShopApi\Api\Front\CountryWS;
use MrAPPs\MrShopApi\Api\Front\CustomerWS;
use MrAPPs\MrShopApi\Api\Front\CustomizationWS;
use MrAPPs\MrShopApi\Api\Front\FermopointWS;
use MrAPPs\MrShopApi\Api\Front\FidelityWS;
use MrAPPs\MrShopApi\Api\Front\FileWS;
use MrAPPs\MrShopApi\Api\Front\LanguageWS;
use MrAPPs\MrShopApi\Api\Front\MailNotificationWS;
use MrAPPs\MrShopApi\Api\Front\ManufacturerProductWS;
use MrAPPs\MrShopApi\Api\Front\ManufacturerWS;
use MrAPPs\MrShopApi\Api\Front\NewProductWS;
use MrAPPs\MrShopApi\Api\Front\NotificationWS;
use MrAPPs\MrShopApi\Api\Front\OrderReturnWS;
use MrAPPs\MrShopApi\Api\Front\OrderWS;
use MrAPPs\MrShopApi\Api\Front\PaymentWS;
use MrAPPs\MrShopApi\Api\Front\PricesDropWS;
use MrAPPs\MrShopApi\Api\Front\ProductReviewWS;
use MrAPPs\MrShopApi\Api\Front\ProductWS;
use MrAPPs\MrShopApi\Api\Front\RelatedProductWS;
use MrAPPs\MrShopApi\Api\Front\RetrieveAppDataWS;
use MrAPPs\MrShopApi\Api\Front\ScratchToWinWS;
use MrAPPs\MrShopApi\Api\Front\SearchWS;
use MrAPPs\MrShopApi\Api\Front\SetupWS;
use MrAPPs\MrShopApi\Api\Front\StateWS;
use MrAPPs\MrShopApi\Api\Front\StoreWS;
use MrAPPs\MrShopApi\Api\Front\UpdateModuleWS;
use MrAPPs\MrShopApi\Api\Front\VoucherWS;
use MrAPPs\MrShopApi\Api\Front\WishlistWS;
use MrAPPs\MrShopApi\Handler\CartHandler;

class ApiRequestHandler extends AbstractApiRequestHandler
{
    public $resourceList = [
        'auth/signup' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'signup',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'auth/login' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'login',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'auth/social_login' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'socialLogin',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'auth/refresh_token' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'refreshToken',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'auth/reset_password' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'resetPassword',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'auth/edit_password' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'editPassword',
            'auth' => true,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'customers' => [
            'class' => CustomerWS::class,
            'auth' => true,
            'use_context_id' => true,
        ],
        'gdpr/delete_profile' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'deleteCustomerGdpr',
            'auth' => true,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'gdpr/export_profile' => [
            'class' => CustomerWS::class,
            'custom_class_method' => 'exportGdprProfile',
            'auth' => true,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'addresses' => [
            'class' => AddressWS::class,
            'auth' => true,
        ],
        'customizations' => [
            'class' => CustomizationWS::class,
            'auth' => false,
            'forbidden_method' => ['PUT', 'DELETE']
        ],
        'allowed_carriers' => [
            'class' => AllowedCarriersCartWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
            'auth' => true,
        ],
        'cart_summary' => [
            'class' => CartSummaryWS::class,
            'forbidden_method' => ['POST', 'PUT', 'DELETE'],
        ],
        'cart' => [
            'class' => CartWS::class,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/coupon/apply' => [
            'class' => CartWS::class,
            'custom_class_method' => 'applyCoupon',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/coupon/remove' => [
            'class' => CartWS::class,
            'custom_class_method' => 'removeCoupon',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/payment_methods' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'getPaymentMethodList',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/init_payment' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'initPayment',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_cic' => [
            'class' => PaymentWS::class,
            'custom_class_method' => 'validateCic',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_newcarnet' => [
            'class' => PaymentWS::class,
            'custom_class_method' => 'validateNewCarnet',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_paypal' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'validatePaypal',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_redsys' => [
            'class' => PaymentWS::class,
            'custom_class_method' => 'validateRedsys',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_satispay' => [
            'class' => PaymentWS::class,
            'custom_class_method' => 'validateSatispay',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_stripe' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'validateStripe',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/confirm_payment_stripe' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'confirmStripePayment',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/validate_webgateway' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'validateWebGateway',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'cart/post_payment' => [
            'class' => PaymentWS::class,
            'auth' => true,
            'custom_class_method' => 'postPayment',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'wishlist/mine' => [
            'class' => WishlistWS::class,
            'auth' => true,
            'custom_class_method' => 'getMine',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'wishlist/add_product' => [
            'class' => WishlistWS::class,
            'auth' => true,
            'custom_class_method' => 'addProduct',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'wishlist/remove_product' => [
            'class' => WishlistWS::class,
            'auth' => true,
            'custom_class_method' => 'removeProduct',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'categories' => [
            'class' => CategoryWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'content_management_system' => [
            'class' => CmsWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'countries' => [
            'class' => CountryWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'fermopoints' => [
            'class'          => FermopointWS::class,
            'auth'           => true,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'manufacturers' => [
            'class' => ManufacturerWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'manufacturer_products' => [
            'class' => ManufacturerProductWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'new_products' => [
            'class' => NewProductWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'related_products' => [
            'class' => RelatedProductWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'notifications' => [
            'class' => NotificationWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'orders' => [
            'class' => OrderWS::class,
            'auth' => true,
        ],
        'reorder' => [
            'class' => OrderWS::class,
            'auth' => true,
            'custom_class_method' => 'reorder',
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'returnable' => [
            'class' => OrderReturnWS::class,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'vouchers' => [
            'class' => VoucherWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
            'auth' => true,
        ],
        'products' => [
            'class' => ProductWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'reviews' => [
            'class' => ProductReviewWS::class,
            'auth' => false,
            'forbidden_method' => ['PUT', 'DELETE']
        ],
        'search' => [
            'class' => SearchWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'setup' => [
            'class' => SetupWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'special_offers' => [
            'class' => PricesDropWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'states' => [
            'class' => StateWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'stores' => [
            'class' => StoreWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'mail_notifications' => [
            'class' => MailNotificationWS::class,
            'forbidden_method' => ['PUT', 'GET', 'DELETE'],
        ],
        'contacts' => [
            'class' => ContactsWS::class,
            'forbidden_method' => ['PUT', 'DELETE'],
        ],
        'app_home' => [
            'class' => AppHomeWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'languages' => [
            'class' => LanguageWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'checkout_custom_fields' => [
            'class' => CheckoutCustomFieldsWS::class,
            'forbidden_method' => ['PUT', 'DELETE'],
            'auth' => true,
        ],
        'fidelity' => [
            'class' => FidelityWS::class,
            'forbidden_method' => ['PUT', 'DELETE'],
            'auth' => true,
        ],
        'init_scratch_to_win' => [
            'class' => ScratchToWinWS::class,
            'custom_class_method' => 'initScratchToWin',
            'forbidden_method' => ['PUT', 'POST','PATCH', 'DELETE'],
            'auth' => true,
        ],
        'scratch_to_win_completed' => [
            'class' => ScratchToWinWS::class,
            'custom_class_method' => 'scratchToWinCompleted',
            'forbidden_method' => ['PUT', 'POST','PATCH', 'DELETE'],
            'auth' => true,
        ],
        'retrieve_app_data' => [
            'class' => RetrieveAppDataWS::class,
            'secret_code_required' => true,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'update_modules' => [
            'class' => UpdateModuleWS::class,
            'secret_code_required' => true,
            'custom_class_method' => 'updateActiveModules',
            'forbidden_method' => ['GET', 'DELETE'],
        ],
        'update_app_data' => [
            'class' => UpdateModuleWS::class,
            'secret_code_required' => true,
            'custom_class_method' => 'updateAppData',
            'forbidden_method' => ['GET', 'DELETE'],
        ],
        'update_dl_files' => [
            'class' => UpdateModuleWS::class,
            'secret_code_required' => true,
            'custom_class_method' => 'updateDynamicLinks',
            'forbidden_method' => ['GET', 'DELETE'],
        ],
        'best_sales' => [
            'class' => BestSalesWS::class,
            'forbidden_method' => ['PUT', 'POST', 'DELETE'],
        ],
        'assistance' => [
            'class' => AssistanceWS::class,
            'forbidden_method' => ['PUT', 'DELETE']
        ]
    ];

    protected $ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'HEAD'];

    protected $resource;

    /** @var JwtHandler */
    private $jwtHandler;

    /** @var CartHandler */
    private $cartHandler;

    public function __construct($dataHandler)
    {
        parent::__construct($dataHandler);
        $this->jwtHandler = new JwtHandler($this->module, $this->dataHandler->getToken());
        $this->cartHandler = new CartHandler($this->dataHandler->getGuestId());
    }

    public function setupContext($resource = '')
    {
        parent::setupContext($resource);
        $customerId = $this->getCustomerIdFromJWT();
        $customerIsLogged = $customerId != 0;

        $context = Context::getContext();
        $context->customer = $customerIsLogged ? new Customer($customerId) : new Customer();
        $context->customer->logged = $customerIsLogged ? 1 : 0;

        if ($this->dataHandler->getGuestId()) {
            $guest = new Guest($this->dataHandler->getGuestId());
            $isCustomerGuest = (int) $guest->id_customer != 0;

            if ($customerIsLogged && ((!$customerId && $isCustomerGuest) || (int) $guest->id_customer != $customerId)) {
                $this->dataHandler->setGuestId(0);
            }

            if ($isCustomerGuest && $this->dataHandler->getGuestId() != 0) {
                $context->customer = new Customer($guest->id_customer);
                $context->customer->logged = 1;
            }
        }

        if (!in_array($resource, ['cart', 'setup'])) {
            $this->cartHandler->getCart($customerId, false, $this->dataHandler->getCurrencyId());
        }
    }

    public function getJwtHandler()
    {
        return $this->jwtHandler;
    }

    public function getCartHandler()
    {
        return $this->cartHandler;
    }

    protected function getUserId($throwErrorIfInvalidJwt)
    {
        $customerId = null;

        if ($this->jwtHandler->isAuth($throwErrorIfInvalidJwt)) {
            $customerId = $this->getCustomerIdFromJWT();
        } elseif (Context::getContext()->customer && Context::getContext()->customer->id != 0) {
            $customerId = Context::getContext()->customer->id;
        }

        return $customerId;
    }

    protected function isUserAuthorized()
    {
        $customerId = $this->getUserId(true);

        return false == empty($customerId);
    }

    public function getCustomerIdFromJWT()
    {
        $data = $this->jwtHandler->decodeToken();

        return $data['success'] ? (int) $data['data']['id'] : null;
    }
}
