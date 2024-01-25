<?php return array (
  'name' => 'giftshop',
  'display_name' => 'giftshop',
  'version' => '1.0.6',
  'theme_key' => '1449850c8f201422734dbb1c82befbf3',
  'author' => 
  array (
    'name' => 'ETS-Soft',
    'email' => 'pub@prestashop.com',
    'url' => 'http://www.prestashop.com',
  ),
  'meta' => 
  array (
    'compatibility' => 
    array (
      'from' => '1.7.0.0',
      'to' => NULL,
    ),
    'available_layouts' => 
    array (
      'layout-full-width' => 
      array (
        'name' => 'Full Width',
        'description' => 'No side columns, ideal for distraction-free pages such as product pages.',
      ),
      'layout-both-columns' => 
      array (
        'name' => 'Three Columns',
        'description' => 'One large central column and 2 side columns.',
      ),
      'layout-left-column' => 
      array (
        'name' => 'Two Columns, small left column',
        'description' => 'Two columns with a small left column',
      ),
      'layout-right-column' => 
      array (
        'name' => 'Two Columns, small right column',
        'description' => 'Two columns with a small right column',
      ),
    ),
  ),
  'assets' => NULL,
  'dependencies' => 
  array (
    'modules' => 
    array (
      0 => 'ets_multilayerslider',
      1 => 'sekeywords',
      2 => 'productcomments',
      3 => 'ets_reviewticker',
      4 => 'ets_purchasetogether',
      5 => 'ps_specials',
      6 => 'ps_categoryproducts',
      7 => 'ps_bestsellers',
      8 => 'ps_newproducts',
      9 => 'ps_sharebuttons',
      10 => 'pleasewait',
      11 => 'ybc_newsletter',
      12 => 'ybc_productimagehover',
      13 => 'ybc_themeconfig',
      14 => 'ybc_widget',
      15 => 'ybc_specificprices',
      16 => 'ybc_blog_free',
      17 => 'ybc_manufacturer',
      18 => 'ps_featuredproducts',
      19 => 'blockwishlist',
      20 => 'ets_mailchimpsync',
      21 => 'ps_shoppingcart',
      22 => 'ets_megamenu',
    ),
  ),
  'global_settings' => 
  array (
    'configuration' => 
    array (
      'PS_IMAGE_QUALITY' => 'png',
    ),
    'modules' => 
    array (
      'to_enable' => 
      array (
        0 => 'ps_linklist',
        1 => 'ps_categoryproducts',
        2 => 'ps_bestsellers',
        3 => 'ps_specials',
        4 => 'ps_newproducts',
      ),
      'to_reset' => 
      array (
        0 => 'ybc_widget',
        1 => 'ets_megamenu',
        2 => 'ets_multilayerslider',
        3 => 'ets_mailchimpsync',
      ),
    ),
    'hooks' => 
    array (
      'modules_to_hook' => 
      array (
        'blogCategoriesBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogFeaturedPostsBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogGalleryBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogNewsBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogPopularPostsBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogSearchBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogSidebar' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogSlidersBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'blogTagsBlock' => 
        array (
          0 => 'ybc_blog_free',
        ),
        'displayCrossSellingShoppingCart' => 
        array (
          0 => 'ps_featuredproducts',
        ),
        'displayCustomerAccount' => 
        array (
          0 => 'blockwishlist',
        ),
        'displayFooter' => 
        array (
          0 => 'ps_contactinfo',
          1 => 'ps_customeraccountlinks',
          2 => 'ps_linklist',
          3 => 'ybc_themeconfig',
          4 => 'ets_reviewticker',
          5 => 'ybc_newsletter',
          6 => 'ets_purchasetogether',
          7 => 'ybc_widget',
          8 => 'ybc_blog_free',
        ),
        'displayFooterBefore' => 
        array (
          0 => 'pleasewait',
          1 => 'ps_emailsubscription',
          2 => 'ps_socialfollow',
        ),
        'displayFooterProduct' => 
        array (
          0 => 'ps_categoryproducts',
          1 => 'ets_purchasetogether',
        ),
        'displayHome' => 
        array (
          0 => 'ets_multilayerslider',
          1 => 'ybc_widget',
          2 => 'ybc_blog_free',
          3 => 'ps_featuredproducts',
          4 => 'ps_newproducts',
          5 => 'ps_bestsellers',
          6 => 'ps_specials',
          7 => 'ybc_manufacturer',
        ),
        'displayLeftColumn' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'ps_categorytree',
          2 => 'ybc_widget',
          3 => 'ybc_blog_free',
        ),
        'displayLeftColumnProduct' => 
        array (
          0 => 'ets_purchasetogether',
        ),
        'displayMegaMenu' => 
        array (
          0 => 'ets_megamenu',
        ),
        'displayMultiLayerSlide' => 
        array (
          0 => 'ets_multilayerslider',
        ),
        'displayMyAccountBlock' => 
        array (
          0 => 'blockwishlist',
        ),
        'displayNav' => 
        array (
          0 => 'ybc_widget',
        ),
        'displayNav1' => 
        array (
          0 => 'ps_languageselector',
          1 => 'ps_currencyselector',
        ),
        'displayNav2' => 
        array (
          0 => 'ps_customersignin',
          1 => 'blockwishlist',
          2 => 'ps_shoppingcart',
        ),
        'displayOrderConfirmation2' => 
        array (
          0 => 'ps_featuredproducts',
        ),
        'displayPaymentReturn' => 
        array (
          0 => 'ps_checkpayment',
          1 => 'ps_wirepayment',
        ),
        'displayProductAdditionalInfo' => 
        array (
          0 => 'ps_sharebuttons',
          1 => 'blockwishlist',
          2 => 'ets_purchasetogether',
        ),
        'displayProductButtons' => 
        array (
          0 => 'ps_sharebuttons',
        ),
        'displayProductListFunctionalButtons' => 
        array (
          0 => 'blockwishlist',
        ),
        'displayReassurance' => 
        array (
          0 => 'blockreassurance',
        ),
        'displayRightColumn' => 
        array (
          0 => 'blockwishlist',
          1 => 'ybc_widget',
        ),
        'displaySearch' => 
        array (
          0 => 'ps_searchbar',
        ),
        'displayTop' => 
        array (
          0 => 'ps_searchbar',
          1 => 'ets_megamenu',
          2 => 'ets_purchasetogether',
          3 => 'ybc_widget',
        ),
        'displayTopColumn' => 
        array (
          0 => 'ybc_widget',
          1 => 'ybc_manufacturer',
        ),
        'NewsletterCustom' => 
        array (
          0 => 'ybc_newsletter',
        ),
        'paymentOptions' => 
        array (
          0 => 'ps_checkpayment',
          1 => 'ps_wirepayment',
        ),
        'productcustom' => 
        array (
          0 => 'blockwishlist',
        ),
        'productImageHover' => 
        array (
          0 => 'ybc_productimagehover',
        ),
        'productSearchProvider' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'search' => 
        array (
          0 => 'statssearch',
        ),
        'tabHome' => 
        array (
          0 => 'ps_featuredproducts',
          1 => 'ps_newproducts',
          2 => 'ps_specials',
          3 => 'ps_bestsellers',
        ),
        'tabHomeContent' => 
        array (
          0 => 'ps_featuredproducts',
          1 => 'ps_newproducts',
          2 => 'ps_specials',
          3 => 'ps_bestsellers',
        ),
        'ybcBlockSocial' => 
        array (
          0 => 'ybc_themeconfig',
        ),
        'ybcCopyright' => 
        array (
          0 => 'ybc_themeconfig',
        ),
        'ybcCustom1' => 
        array (
          0 => 'ybc_widget',
        ),
        'ybcCustom2' => 
        array (
          0 => 'ybc_widget',
        ),
        'ybcCustom3' => 
        array (
          0 => 'ybc_widget',
        ),
        'ybccustom4' => 
        array (
          0 => 'ybc_widget',
          1 => 'ybc_manufacturer',
        ),
        'ybcLayoutUpdate' => 
        array (
          0 => 'ybc_themeconfig',
        ),
        'displayMMItemMenu' => 
        array (
          0 => 'ets_megamenu',
        ),
        'displayMMItemColumn' => 
        array (
          0 => 'ets_megamenu',
        ),
        'displayMMItemBlock' => 
        array (
          0 => 'ets_megamenu',
        ),
        'displayBlock' => 
        array (
          0 => 'ets_megamenu',
        ),
        'displayBackOfficeHeader' => 
        array (
          0 => 'ybc_themeconfig',
          1 => 'ybc_manufacturer',
          2 => 'productcomments',
          3 => 'ets_megamenu',
          4 => 'ets_reviewticker',
          5 => 'ets_mailchimpsync',
          6 => 'welcome',
          7 => 'ets_purchasetogether',
          8 => 'ybc_blog_free',
          9 => 'ybc_widget',
          10 => 'ybc_newsletter',
          11 => 'ets_multilayerslider',
          12 => NULL,
        ),
      ),
    ),
    'image_types' => 
    array (
      'cart_default' => 
      array (
        'width' => 130,
        'height' => 130,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'small_default' => 
      array (
        'width' => 100,
        'height' => 100,
        'scope' => 
        array (
          0 => 'products',
          1 => 'categories',
          2 => 'manufacturers',
          3 => 'suppliers',
        ),
      ),
      'medium_default' => 
      array (
        'width' => 452,
        'height' => 452,
        'scope' => 
        array (
          0 => 'products',
          1 => 'manufacturers',
          2 => 'suppliers',
        ),
      ),
      'home_default' => 
      array (
        'width' => 450,
        'height' => 450,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'large_default' => 
      array (
        'width' => 800,
        'height' => 800,
        'scope' => 
        array (
          0 => 'products',
          1 => 'manufacturers',
          2 => 'suppliers',
        ),
      ),
      'category_default' => 
      array (
        'width' => 850,
        'height' => 250,
        'scope' => 
        array (
          0 => 'categories',
        ),
      ),
      'stores_default' => 
      array (
        'width' => 170,
        'height' => 115,
        'scope' => 
        array (
          0 => 'stores',
        ),
      ),
    ),
  ),
  'theme_settings' => 
  array (
    'default_layout' => 'layout-full-width',
    'layouts' => 
    array (
      'category' => 'layout-left-column',
      'best-sales' => 'layout-left-column',
      'new-products' => 'layout-left-column',
      'prices-drop' => 'layout-left-column',
      'search' => 'layout-left-column',
      'contact' => 'layout-full-width',
    ),
  ),
);
