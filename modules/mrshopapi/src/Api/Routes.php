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

namespace MrAPPs\MrShopApi\Api;

class Routes
{
    const BASE_API_PATH = 'client_mrshop/';

    const BASE_CRON_PATH = 'cron_mrshop/';

    const CURRENT = [
        // -------- AUTH ROUTES ----------
        'mrshopapi_client_login' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/login',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/login',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_refresh_auth' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/refresh_token',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/refresh_token',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_signup' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/signup',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/signup',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_social_login' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/social_login',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/social_login',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_editpassword' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/edit_password',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/edit_password',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice'
            ]
        ],
        'mrshopapi_client_resetpassword' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'auth/reset_password',
            'keywords' => [],
            'params' => [
                'entity' => 'auth/reset_password',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_products_share' => [
            'controller' => 'webserviceproductsshare',
            'rule' => 'share/products/{id_product}/{id_product_attribute}',
            'keywords' => [
                'id_product' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id_product'],
                'id_product_attribute' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id_product_attribute']
            ],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webserviceproductsshare',
            ]
        ],
        // ----------- WISHLIST ----------
        'mrshopapi_client_customerswishlist' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'wishlist/mine',
            'params' => [
                'entity' => 'wishlist/mine',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_customerswishlist_add' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'wishlist/add_product',
            'params' => [
                'entity' => 'wishlist/add_product',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_customerswishlist_remove' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'wishlist/remove_product',
            'params' => [
                'entity' => 'wishlist/remove_product',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        // ---------- CART SPECIFIC MANAGEMENT -----------
        'mrshopapi_client_applycoupon' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{id}',
            'keywords' => [
                'entity' => ['regexp' => 'cart\/coupon', 'param' => 'entity'],
                'id' => ['regexp' => 'apply', 'param' => 'id']
            ],
            'params' => [
                'entity' => 'cart/coupon/apply',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_removecoupon' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{id}',
            'keywords' => [
                'entity' => ['regexp' => 'cart\/coupon', 'param' => 'entity'],
                'id' => ['regexp' => 'remove', 'param' => 'id']
            ],
            'params' => [
                'entity' => 'cart/coupon/remove',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_paymentmethods' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/payment_methods',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/payment_methods',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_initpayment' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/init_payment',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/init_payment',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_postpayment' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/post_payment',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/post_payment',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatepaypal' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/validate_paypal',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/validate_paypal',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatestripe' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/validate_stripe',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/validate_stripe',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_confirmpaymentstripe' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/confirm_payment_stripe',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/confirm_payment_stripe',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatewebgateway' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/validate_webgateway',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/validate_webgateway',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatesatispay' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'cart/validate_satispay',
            'keywords' => [],
            'params' => [
                'entity' => 'cart/validate_satispay',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatenewcarnet' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{status}',
            'keywords' => [
                'entity' => ['regexp' => 'cart\/validate_newcarnet', 'param' => 'entity'],
                'status' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'status']
            ],
            'params' => [
                'entity' => 'cart/validate_newcarnet',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_mobilepayonweb' => [
            'controller' => 'mobilepayonwebpayment',
            'rule' => self::BASE_API_PATH.'cart/mobile_payonweb_payment',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobilepayonwebpayment',
            ]
        ],
        'mrshopapi_amazonpay' => [
            'controller' => 'mobileamazonpayment',
            'rule' => self::BASE_API_PATH.'cart/mobile_amazon_payment',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobileamazonpayment',
            ]
        ],
        'mrshopapi_amazonpay_address' => [
            'controller' => 'mobileamazonpayaddress',
            'rule' => self::BASE_API_PATH.'cart/mobile_amazon_address',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobileamazonpayaddress',
            ]
        ],
        'mrshopapi_amazonpay_cancel' => [
            'controller' => 'mobileamazonpaycancel',
            'rule' => self::BASE_API_PATH.'cart/mobile_amazon_cancel',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobileamazonpaycancel',
            ]
        ],
        'mrshopapi_monetico1fpayment' => [
            'controller' => 'mobilemonetico1fpayment',
            'rule' => self::BASE_API_PATH.'cart/mobile_monetico1f_payment',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobilemonetico1fpayment',
            ]
        ],
        'mrshopapi_webpayment' => [
            'controller' => 'order',
            'rule' => self::BASE_API_PATH.'cart/webpayment',
            'keywords' => [
            ],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'order',
            ]
        ],
        'mrshopapi_mobileigfs' => [
            'controller' => 'mobileigfspayment',
            'rule' => self::BASE_API_PATH.'cart/mobile_igfs_payment',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'mobileigfspayment',
            ]
        ],
        'mrshopapi_client_validateredsys' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{status}',
            'keywords' => [
                'entity' => ['regexp' => 'cart\/validate_redsys', 'param' => 'entity'],
                'status' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'status']
            ],
            'params' => [
                'entity' => 'cart/validate_redsys',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_validatecic' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{status}',
            'keywords' => [
                'entity' => ['regexp' => 'cart\/validate_cic', 'param' => 'entity'],
                'status' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'status']
            ],
            'params' => [
                'entity' => 'cart/validate_cic',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        // ---------- GDPR -----------
        'mrshopapi_client_gdprexportprofile' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'gdpr/export_profile',
            'keywords' => [],
            'params' => [
                'entity' => 'gdpr/export_profile',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_gdprdeleteprofile' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'gdpr/delete_profile',
            'keywords' => [],
            'params' => [
                'entity' => 'gdpr/delete_profile',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        // ---------- OTHER RESOURCES -----------
        'mrshopapi_client_reorder' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'reorder',
            'keywords' => [],
            'params' => [
                'entity' => 'reorder',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_cronjoball' => [
            'controller' => 'webservicecronjoball',
            'rule' => self::BASE_CRON_PATH.'all',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservicecronjoball',
            ]
        ],
        // ---------- CACHE -----------
        'mrshopapi_client_cache' => [
            'controller' => 'cache',
            'rule' => self::BASE_API_PATH.'remove/cache',
            'keywords' => [],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'cache',
            ]
        ],
        // ---------- REQUEST QUOTE ----------
        'mrshopapi_client_requestquote' => [
            'controller' => 'webservicerequestquote',
            'rule' => self::BASE_API_PATH.'{entity}/{product_id}',
            'keywords' => [
                'entity' => ['regexp' => 'request\/quote', 'param' => 'entity'],
                'product_id' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'product_id']
            ],
            'params' => [
                'entity' => 'request/quote',
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservicerequestquote',
            ]
        ],
        'mrshopapi_client' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}',
            'keywords' => [
                'entity' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'entity']
            ],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
        'mrshopapi_client_id' => [
            'controller' => 'webservice',
            'rule' => self::BASE_API_PATH.'{entity}/{id}',
            'keywords' => [
                'entity' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'entity'],
                'id' => ['regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id']
            ],
            'params' => [
                'fc' => 'module',
                'module' => 'mrshopapi',
                'controller' => 'webservice',
            ]
        ],
    ];

    public static function contains($resource)
    {
        foreach (self::CURRENT as $name => $route) {
            if (strpos($route['rule'], $resource)) {
                return true;
            }
        }

        return false;
    }
}
