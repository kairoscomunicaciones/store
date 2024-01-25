<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__8f7489786b404c3b4208b683eca7a8b21145a41ab2356a451b618661b5ae955e */
class __TwigTemplate_7a47a90d0b65367602e54a94333c5da54892fbbe188427c32e9e2ffa58cbc349 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'extra_stylesheets' => [$this, 'block_extra_stylesheets'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
            'content_footer' => [$this, 'block_content_footer'],
            'sidebar_right' => [$this, 'block_sidebar_right'],
            'javascripts' => [$this, 'block_javascripts'],
            'extra_javascripts' => [$this, 'block_extra_javascripts'],
            'translate_javascripts' => [$this, 'block_translate_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"ve\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/img/app_icon.png\" />

<title>Administración de clientes • CapresTSJ</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminCustomers';
    var iso_user = 've';
    var lang_is_rtl = '0';
    var full_language_code = 'es-ve';
    var full_cldr_language_code = 'es-VE';
    var country_iso_code = 'VE';
    var _PS_VERSION_ = '1.7.8.7';
    var roundMode = 2;
    var youEditFieldFor = '';
        var new_order_msg = 'Se ha recibido un nuevo pedido en tu tienda.';
    var order_number_msg = 'Número de pedido: ';
    var total_msg = 'Total: ';
    var from_msg = 'Desde: ';
    var see_order_msg = 'Ver este pedido';
    var new_customer_msg = 'Un nuevo cliente se ha registrado en tu tienda.';
    var customer_name_msg = 'Nombre del cliente: ';
    var new_msg = 'Un nuevo mensaje ha sido publicado en tu tienda.';
    var see_msg = 'Leer este mensaje';
    var token = '163d749ac99e14b8c81cf5acf3871d40';
    var token_admin_orders = tokenAdminOrders = '82f0bc6d524e5179267635714e9240fe';
    var token_admin_customers = '163d749ac99e14b8c81cf5acf3871d40';
    var token_admin_customer_threads = tokenAdminCustomerThreads = '16be25d237f9e09bbdc9b2ce18ea2d7e';
    var currentIndex = 'index.php?controller=AdminCustomers';
    var employee_token = '6b731d04be31672fadf3d07d713955ea';
    var choose_language_translate = 'Selecciona el idioma:';
    var default_language = '2';
    var admin_modules_link = '/admin730/index.php/improve/modules/catalog/recommended?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw';
    var admin_notification_get_link = '/admin730/index.php/common/notifications?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj";
        // line 42
        echo "9NWT-2CSKm1Aw';
    var admin_notification_push_link = adminNotificationPushLink = '/admin730/index.php/common/notifications/ack?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw';
    var tab_modules_list = '';
    var update_success_msg = 'Actualización correcta';
    var errorLogin = 'PrestaShop no pudo iniciar sesión en Addons. Por favor verifica tus datos de acceso y tu conexión de Internet.';
    var search_product_msg = 'Buscar un producto';
  </script>

      <link href=\"/admin730/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin730/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ybc_themeconfig/css/admin.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ybc_blog_free/views/css/admin.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ybc_widget/css/admin.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ps_facebook/views/css/admin/menu.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/psxmarketingwithgoogle/views/css/admin/menu.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/prestaheroconnect/views/css/global.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/ets_onepagecheckout/views/css/list_social.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/customfields/views/css/admin.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\/admin730\\/\";
var baseDir = \"\\/\";
var changeFormLanguageUrl = \"\\/admin730\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\";
var currency = {\"iso_code\":\"VEF\",\"sign\":\"Bs.\",\"name\":\"Bol\\u00edvar venezolano (2008\\u2";
        // line 67
        echo "0132018)\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\".\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"VEF\",\"currencySymbol\":\"Bs.\",\"numberSymbols\":[\",\",\".\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"\\u00a4#,##0.00\",\"negativePattern\":\"\\u00a4-#,##0.00\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var host_mode = false;
var number_specifications = {\"symbol\":[\",\",\".\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"numberSymbols\":[\",\",\".\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var prestashop = {\"debug\":false};
var show_new_customers = \"1\";
var show_new_messages = \"1\";
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/admin730/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/admin730/themes/new-theme/public/multistore_dropdown.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/fancybox/jquery.fancybox.js\"></script>
<script type=\"text/javascript\" src=\"/js/admin.js?v=1.7.8.7\"></script>
<script type=\"text/javascript\" src=\"/admin730/themes/new-theme/public/cldr.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/tools.js?v=1.7.8.7\"></script>
<script type=\"text/javascript\" src=\"/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/admin730/themes/default/js/vendor/nv.d3.min.js\"></script>
<script type=\"text/javascript\" src=\"/modules/multiprice/views/js/multiprice.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/favico.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/ps_favic";
        // line 87
        echo "onnotificationbo.js\"></script>

  <script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: '#DF0067',
      textColor: '#FFFFFF',
      notificationGetUrl: '/admin730/index.php/common/notifications?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120000, // Refresh every 2 minutes
    });
  }
</script>
<script>
            var admin_gamification_ajax_url = \"https:\\/\\/inversionesrpz.com\\/admin730\\/index.php?controller=AdminGamification&token=217beb623e52fc81935e1700ac878f55\";
            var current_id_tab = 25;
        </script><script type=\"text/javascript\">
    var PH_CON_TRANS = {\"install\":\"Instalar\",\"delete\":\"Borrar\",\"email_required\":\"El campo de correo electr\\u00f3nico es obligatorio\",\"password_required\":\"El campo de contrase\\u00f1a es obligatorio\",\"account_invalid\":\"El correo o la contrase\\u00f1a es inv\\u00e1lido\",\"logout\":\"Cerrar sesi\\u00f3n\",\"install_from_server\":\"Instalar desde el servidor\",\"refresh_and_clear_cache\":\"Actualizar y borrar cach\\u00e9\",\"view_my_prestahero\":\"Perfil PrestaHero\",\"contact_prestahero\":\"Contactar PrestaHero\",\"connect_to_prestahero\":\"Conectar a PrestaHero\",\"upgrade\":\"Mejora\",\"install_prestahero\":\"Instalar PrestaHero\",\"install_from_prestahero\":\"Instalar\",\"buy_now\":\"Comprar Ahora\",\"purchased\":\"Comprado\",\"confirm_delete_module\":\"\\u00bfQuieres eliminar este art\\u00edculo?\",\"txt_modulelist\":\"M\\u00f3dulos y temas de PrestaHero\",\"txt_modules_to_upgrade\":\"N\\u00famero de m\\u00f3dulos para actualizar\"};
    var PH_CON_LINKS = {\"my_account\":\"https:\\/\\/prestahero.com\\/en\\/my-account\",\"contact\":\"https:\\/\\/prestahero.com\\/en\\/support\\/contact-us\"};
    var PH_CON_LOGO = '/modules/prestaheroconnect/views/img/prestahero-logo.png';
    var PH_CON_MODULE_LIST_URL = 'https://inversionesrpz.com/admin730/index.php?controller=AdminPhConListModules&token=aa5742718093e2f55a5bf412aa6d6d29';
    var P";
        // line 110
        echo "H_CON_ACCOUNT_NAME = \"\";
    var PH_CON_LINK_LOGOUT = \"https://inversionesrpz.com/admin730/index.php?controller=AdminModules&token=79d39f8b970d1b56881822a8f9bbdc96&configure=prestaheroconnect&logoutPhAccount=1\";
    var PH_CON_LINK_AJAX_MODULE = \"https://inversionesrpz.com/admin730/index.php?controller=AdminModules&token=79d39f8b970d1b56881822a8f9bbdc96&configure=prestaheroconnect\";
    var PH_CON_CONTROLLER = \"AdminCustomers\";
    var PH_CON_IS17 = 1;
    var PH_CON_MSG_ACTION = \"\";
    var PH_CON_IS_OLD_ACTION = 0;
    var PH_CON_IS_LOGGED = 0;
    var PH_CON_RELOAD_UPGRADE_COUNT = 0;
    var PH_CON_UPGRADE_COUNT = 10;
</script>

<script src=\"/modules/prestaheroconnect/views/js/global.js\" defer=\"defer\"></script>


";
        // line 125
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>";
        echo "

<body
  class=\"lang-ve admincustomers multishop-enabled\"
  data-base-url=\"/admin730/index.php\"  data-token=\"w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\">

  <header id=\"header\" class=\"d-print-none\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminDashboard&amp;token=0ec77cfb39e809499051f7102108b9a3\"></a>
      <span id=\"shop_version\">1.7.8.7</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Acceso rápido
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=9961fce337aae1c27cb64ec15e178672\"
                 data-item=\"Evaluación del catálogo\"
      >Evaluación del catálogo</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php/configure/shop/maintenance/?-EVlgWQ_s_wz_Ofrxqx9gYA&amp;token=7002077336e9d62978039088d61b525d\"
                 data-item=\"Mantenimiento - Lista\"
      >Mantenimiento - Lista</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php/improve/modules/manage?token=7002077336e9d62978039088d61b525d\"
                 data-item=\"Módulos instalados\"
      >Módulos instalados</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php/sell/catalog/categories/new?token=7002077336e9d62978039088d61b525d\"
                 data-";
        // line 160
        echo "item=\"Nueva categoría\"
      >Nueva categoría</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php/sell/catalog/products/new?token=7002077336e9d62978039088d61b525d\"
                 data-item=\"Nuevo\"
      >Nuevo</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=c38ae58ba3cb9a6c6589492b115c975a\"
                 data-item=\"Nuevo cupón de descuento\"
      >Nuevo cupón de descuento</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php?controller=AdminOrders&amp;token=82f0bc6d524e5179267635714e9240fe\"
                 data-item=\"Pedidos\"
      >Pedidos</a>
          <a class=\"dropdown-item quick-row-link\"
         href=\"https://tucarritocaprestsj.com/admin730/index.php/configure/advanced/performance?token=7002077336e9d62978039088d61b525d\"
         target=\"_blank\"         data-item=\"Rendimiento\"
      >Rendimiento</a>
        <div class=\"dropdown-divider\"></div>
          <a id=\"quick-add-link\"
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"190\"
        data-icon=\"icon-AdminParentCustomer\"
        data-method=\"add\"
        data-url=\"index.php/sell/customers/?-2CSKm1Aw\"
        data-post-link=\"https://inversionesrpz.com/admin730/index.php?controller=AdminQuickAccesses&token=993c320cbda63c835a286caa91acfc3b\"
        data-prompt-text=\"Por favor, renombre este acceso rápido:\"
        data-link=\"Clientes - Lista\"
      >
        <i class=\"material-icons\">add_circle</i>
        Añadir página actual al Acceso Rápido
      </a>
        <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminQuickAccesses&token=993c320cbda63c835a286caa91acfc3b\">
      <i class=\"material-icons\">settings</i>
      Administrar accesos rápidos
    </a";
        // line 196
        echo ">
  </div>
</div>
      </div>
      <div class=\"component\" id=\"header-search-container\">
        <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/admin730/index.php?controller=AdminSearch&amp;token=d1c8eac4b609526187b687ac0a71f7c7\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_query\" name=\"bo_query\" value=\"\" placeholder=\"Buscar (p. ej.: referencia de producto, nombre de cliente...)\" aria-label=\"Barra de búsqueda\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        toda la tienda
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"toda la tienda\" href=\"#\" data-value=\"0\" data-placeholder=\"¿Qué estás buscando?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> toda la tienda</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catálogo\" href=\"#\" data-value=\"1\" data-placeholder=\"Nombre del producto, referencia, etc.\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catálogo</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por nombre\" href=\"#\" data-value=\"2\" data-placeholder=\"Nombre\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clientes por nombre</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por dirección IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clientes por dirección IP</a>
        <a class=\"dropdown-item\" data-item=\"Pedidos\" href=\"#\" data-value=\"3\" data-placeholder=\"ID del pedido\" data-icon=\"icon-credit-card\"><i class=\"m";
        // line 219
        echo "aterial-icons\">shopping_basket</i> Pedidos</a>
        <a class=\"dropdown-item\" data-item=\"Facturas\" href=\"#\" data-value=\"4\" data-placeholder=\"Numero de Factura\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Facturas</a>
        <a class=\"dropdown-item\" data-item=\"Carritos\" href=\"#\" data-value=\"5\" data-placeholder=\"ID carrito\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Carritos</a>
        <a class=\"dropdown-item\" data-item=\"Módulos\" href=\"#\" data-value=\"7\" data-placeholder=\"Nombre del módulo\" data-icon=\"icon-puzzle-piece\"><i class=\"material-icons\">extension</i> Módulos</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">BÚSQUEDA</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$('#bo_query').one('click', function() {
    \$(this).closest('form').removeClass('collapsed');
  });
});
</script>
      </div>

      
      
                    <div class=\"component header-right-component\" id=\"header-notifications-container\">
          <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Pedidos<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
         ";
        // line 262
        echo "   <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notifications\"
              role=\"tab\"
            >
              Clientes<span id=\"_nb_new_customers_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Mensajes<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay pedidos nuevos por ahora :(<br>
              ¿Has revisado tus <strong><a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=3fd07bf27349ac2c14321f2901b27125\">carritos abandonados</a></strong>?<br>?. ¡Tu próximo pedido podría estar ocultándose allí!
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay clientes nuevos por ahora :(<br>
              ¿Se mantiene activo en las redes sociales en estos momentos?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay mensajes nuevo por ahora.<br>
              Parece que todos tus clientes están contentos :)
   ";
        // line 307
        echo "         </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"order-notification-template\">
    <a class=\"notif\" href='order_url'>
      #_id_order_ -
      de <strong>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href='customer_url'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - registrado <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href='message_url'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
        </div>
      
      <div class=\"component\" id=\"header-employee-container\">
        <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">

      <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"https://inversionesrpz.com/img/pr/default.jpg\" /></span>
      <span class=\"employee_profile\">Bienvenido de nuevo, Vicmer</span>
      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin730/index.php/configure/advanced/employees/7/edit?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\">
      <i class=\"material-icons\">edit</i>
      <span>Tu perfil</span>
    </a>
    </div>

    <p class=\"divider\"></p>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/resources/documentations?utm_source=back-office&amp;utm_medium=profile&amp;utm_campai";
        // line 356
        echo "gn=resources-en&amp;utm_content=download17\" target=\"_blank\" rel=\"noreferrer\"><i class=\"material-icons\">book</i> Recursos</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/training?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=training-en&amp;utm_content=download17\" target=\"_blank\" rel=\"noreferrer\"><i class=\"material-icons\">school</i> Formación</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/experts?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=expert-en&amp;utm_content=download17\" target=\"_blank\" rel=\"noreferrer\"><i class=\"material-icons\">person_pin_circle</i> Encontrar un Experto</a>
    <a class=\"dropdown-item\" href=\"https://addons.prestashop.com?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=addons-en&amp;utm_content=download17\" target=\"_blank\" rel=\"noreferrer\"><i class=\"material-icons\">extension</i> Marketplace de PrestaShop</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/contact?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=help-center-en&amp;utm_content=download17\" target=\"_blank\" rel=\"noreferrer\"><i class=\"material-icons\">help</i> Centro de ayuda</a>
    <p class=\"divider\"></p>
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminLogin&amp;logout=1&amp;token=d6ae38b4a908efa9774dd9b1412d2469\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Cerrar sesión</span>
    </a>
  </div>
</div>
      </div>
          </nav>
  </header>

  <nav class=\"nav-bar d-none d-print-none d-md-block\">
  <span class=\"menu-collapse\" data-toggle-url=\"/admin730/index.php/configure/advanced/employees/toggle-navigation?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\">
    <i class=\"material-icons\">chevron_left</i>
    <i class=\"material-icons\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <ul class=\"main-menu\">
       ";
        // line 380
        echo "       
                    
                    
          
            <li class=\"link-levelone\" data-submenu=\"1\" id=\"tab-AdminDashboard\">
              <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminDashboard&amp;token=0ec77cfb39e809499051f7102108b9a3\" class=\"link\" >
                <i class=\"material-icons\">trending_up</i> <span>Inicio</span>
              </a>
            </li>

          
                      
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vender</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin730/index.php/sell/orders/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-shopping_basket\">shopping_basket</i>
                      <span>
                      Pedidos
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                                <a href=\"/admin730/index.php/sell/orders/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Pedidos
         ";
        // line 419
        echo "                       </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin730/index.php/sell/orders/invoices/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Facturas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin730/index.php/sell/orders/credit-slips/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Notas de crédito
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin730/index.php/sell/orders/delivery-slips/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Albarán de entrega
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCarts&amp";
        // line 450
        echo ";token=3fd07bf27349ac2c14321f2901b27125\" class=\"link\"> Carrito de compra
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"217\" id=\"subtab-AdminOrdermanager\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminOrdermanager&amp;token=613ea2cb3011b1400afbad00de464e52\" class=\"link\"> Administración de pedidos
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin730/index.php/sell/catalog/products?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catálogo
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"10\" id=\"subtab-AdminPr";
        // line 482
        echo "oducts\">
                                <a href=\"/admin730/index.php/sell/catalog/products?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Productos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin730/index.php/sell/catalog/categories?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Categorías
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin730/index.php/sell/catalog/monitoring/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Monitoreo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminAttributesGroups&amp;token=ef01b171cd14bc21971c88cbf926da31\" class=\"link\"> Atributos y Características
                                </a>
                              </li>

                                                                                  
                              
                                              ";
        // line 513
        echo "              
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin730/index.php/sell/catalog/brands/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Marcas y Proveedores
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin730/index.php/sell/attachments/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Archivos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCartRules&amp;token=c38ae58ba3cb9a6c6589492b115c975a\" class=\"link\"> Descuentos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                                <a href=\"/admin730/index.php/sell/stocks/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Inventario
                                </a>
                              </li>

                                                                            ";
        // line 543
        echo "                                                            
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"227\" id=\"subtab-AdminProductManagerMassiveEdit\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminProductManagerMassiveEdit&amp;token=e6b35297335f0f0709da4e1db1185a37\" class=\"link\"> Editor de Productos en masa
                                </a>
                              </li>

                                                                                                                                        
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"229\" id=\"subtab-AdminManageBookingProductConfiguration\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminBookingProduct&amp;token=58e1282cc519bd86a8c112e5c9c1b8dc\" class=\"link\"> Bookings
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                                                          
                  <li class=\"link-levelone has_submenu link-active open ul-open\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                    <a href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-account_circle\">account_circle</i>
                      <span>
                      Clientes
                      </span>
                                                    <i class=\"material-icons";
        // line 571
        echo " sub-tabs-arrow\">
                                                                    keyboard_arrow_up
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo link-active\" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                                <a href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Clientes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin730/index.php/sell/addresses/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Direcciones
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCustomerThreads&amp;token=16be25d237f9e09bbdc9b2ce18ea2d7e\" class=\"link\">
                      <i class=\"material-icons mi-chat\">chat</i>
                      ";
        // line 601
        echo "<span>
                      Atención al cliente
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCustomerThreads&amp;token=16be25d237f9e09bbdc9b2ce18ea2d7e\" class=\"link\"> Atención al cliente
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin730/index.php/sell/customer-service/order-messages/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Mensaje del pedido
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminReturn&amp;token=a0e8374935a648014ee177a8735135a1\" class=\"link\"> Devoluciones de me";
        // line 629
        echo "rcancía
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                    <a href=\"/admin730/index.php/modules/metrics/legacy/stats?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-assessment\">assessment</i>
                      <span>
                      Estadísticas
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-32\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"223\" id=\"subtab-AdminMetricsLegacyStatsController\">
                                <a href=\"/admin730/index.php/modules/metrics/legacy/stats?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Estadísticas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"224\" id=\"subtab-AdminMetricsController\">
                                <a href";
        // line 662
        echo "=\"/admin730/index.php/modules/metrics?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> PrestaShop Metrics
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"250\" id=\"subtab-KbMobileAppMainTab\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminMobileAppSetting&amp;token=f91142e37e273569af067f9835c410f8\" class=\"link\">
                      <i class=\"material-icons mi-\"></i>
                      <span>
                      Knowband Mobile App
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-250\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"251\" id=\"subtab-AdminMobileAppSetting\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminMobileAppSetting&amp;token=f91142e37e273569af067f9835c410f8\" class=\"link\"> Module Configuration
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
    ";
        // line 695
        echo "                  
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"42\" id=\"tab-IMPROVE\">
                <span class=\"title\">Personalizar</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin730/index.php/improve/modules/manage?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Módulos
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"44\" id=\"subtab-AdminModulesSf\">
                                <a href=\"/admin730/index.php/improve/modules/manage?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Gestor de módulo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminParentModulesCatalog\">
                                <a href=\"/admin";
        // line 730
        echo "730/index.php/modules/addons/modules/catalog?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Catálogo de Módulos
                                </a>
                              </li>

                                                                                                                                                                                          </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"52\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin730/index.php/improve/design/themes/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                      <span>
                      Diseño
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-52\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"130\" id=\"subtab-AdminThemesParent\">
                                <a href=\"/admin730/index.php/improve/design/themes/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Tema y logotipo
                                </a>
                              </li>

                                                                                                                    ";
        // line 759
        echo "                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"140\" id=\"subtab-AdminPsMboTheme\">
                                <a href=\"/admin730/index.php/modules/addons/themes/catalog?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Catálogo de Temas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin730/index.php/improve/design/mail_theme/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Tema Email
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin730/index.php/improve/design/cms-pages/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Páginas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"58\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin730/index.php/improve/design/modules/positions/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Posiciones
                                </a>
    ";
        // line 789
        echo "                          </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"59\" id=\"subtab-AdminImages\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminImages&amp;token=665b7a48a74251e7155cdf11bea4ff62\" class=\"link\"> Ajustes de imágenes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"129\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin730/index.php/modules/link-widget/list?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Lista de enlaces
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"60\" id=\"subtab-AdminParentShipping\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCarriers&amp;token=c5222cf026c25cf965d2fad0d91858fa\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Transporte
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
              ";
        // line 821
        echo "                                              </i>
                                            </a>
                                              <ul id=\"collapse-60\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"61\" id=\"subtab-AdminCarriers\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCarriers&amp;token=c5222cf026c25cf965d2fad0d91858fa\" class=\"link\"> Transportistas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin730/index.php/improve/shipping/preferences/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Preferencias
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"63\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin730/index.php/improve/payment/payment_methods?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Métodos de pago
                      </span>
                                                    <i class=\"material-icons sub-t";
        // line 852
        echo "abs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-63\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"64\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin730/index.php/improve/payment/payment_methods?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Métodos de pago
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"65\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin730/index.php/improve/payment/preferences?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Preferencias
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"252\" id=\"subtab-AdminCODwFeePlus\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCODwFeePlus&amp;token=5be3184b2b6f80d5bf773becab408dce\" class=\"link\"> COD with Fee Plus
                                </a>
                              </li>

                                                                                  
                              
   ";
        // line 883
        echo "                                                         
                              <li class=\"link-leveltwo\" data-submenu=\"265\" id=\"subtab-AdminDefPayUsers\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminDefPayUsers&amp;token=36a56d106b63a13bac3a974771daf05b\" class=\"link\"> Deffered payments users
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"66\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin730/index.php/improve/international/localization/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      Internacional
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-66\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"67\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin730/index.php/improve/international/localization/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Localización
                                </a>
    ";
        // line 912
        echo "                          </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin730/index.php/improve/international/zones/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Ubicaciones Geográficas
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"76\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin730/index.php/improve/international/taxes/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Impuestos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminTranslations\">
                                <a href=\"/admin730/index.php/improve/international/translations/settings?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Traducciones
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"146\" id=\"subtab-Marketing\">
    ";
        // line 945
        echo "                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminPsfacebookModule&amp;token=d6d8ba61791a3919ffeec6b769d34955\" class=\"link\">
                      <i class=\"material-icons mi-campaign\">campaign</i>
                      <span>
                      Mercadeo
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-146\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"147\" id=\"subtab-AdminPsfacebookModule\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminPsfacebookModule&amp;token=d6d8ba61791a3919ffeec6b769d34955\" class=\"link\"> Facebook
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"149\" id=\"subtab-AdminPsxMktgWithGoogleModule\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminPsxMktgWithGoogleModule&amp;token=c05077461f90c6a12c25d88cbbba24ee\" class=\"link\"> Google
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
        ";
        // line 976
        echo "                                  
                    
          
            <li class=\"category-title\" data-submenu=\"80\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurar</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"81\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin730/index.php/configure/shop/preferences/preferences?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Parámetros de la tienda
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-81\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"82\" id=\"subtab-AdminParentPreferences\">
                                <a href=\"/admin730/index.php/configure/shop/preferences/preferences?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Configuración
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"85\" id=\"subtab-AdminParentOrderPreferences\">
                           ";
        // line 1010
        echo "     <a href=\"/admin730/index.php/configure/shop/order-preferences/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Configuración de Pedidos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"88\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin730/index.php/configure/shop/product-preferences/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Configuración de Productos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin730/index.php/configure/shop/customer-preferences/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Ajustes sobre clientes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"93\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin730/index.php/configure/shop/contacts/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Contacto
                                </a>
                              </li>

                                                                                  
                              
                                      ";
        // line 1040
        echo "                      
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin730/index.php/configure/shop/seo-urls/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Tráfico &amp; SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"100\" id=\"subtab-AdminParentSearchConf\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminSearchConf&amp;token=115e78367c91eff2d254bad40e533ac8\" class=\"link\"> Buscar
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"134\" id=\"subtab-AdminGamification\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminGamification&amp;token=217beb623e52fc81935e1700ac878f55\" class=\"link\"> Merchant Expertise
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"103\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin730/index.php/configure/advanced/system-information/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\">
       ";
        // line 1070
        echo "               <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                      <span>
                      Parámetros avanzados
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-103\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"104\" id=\"subtab-AdminInformation\">
                                <a href=\"/admin730/index.php/configure/advanced/system-information/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Información
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin730/index.php/configure/advanced/performance/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Rendimiento
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/admin730/index.php/configure/advanced/administration";
        // line 1099
        echo "/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Administración
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"107\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin730/index.php/configure/advanced/emails/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Dirección de correo electrónico
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"108\" id=\"subtab-AdminImport\">
                                <a href=\"/admin730/index.php/configure/advanced/import/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Importar
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"109\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin730/index.php/configure/advanced/employees/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Equipo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"113\" id=\"subtab-AdminParen";
        // line 1130
        echo "tRequestSql\">
                                <a href=\"/admin730/index.php/configure/advanced/sql-requests/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Base de datos
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"116\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin730/index.php/configure/advanced/logs/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Registros/Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"117\" id=\"subtab-AdminWebservice\">
                                <a href=\"/admin730/index.php/configure/advanced/webservice-keys/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Webservice
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminShopGroup\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminShopGroup&amp;token=ac7b7fcc04a29f2ae5b6909a68068c57\" class=\"link\"> Multitienda
                                </a>
                              </li>

                                                                                                                                        
                         ";
        // line 1160
        echo "     
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"120\" id=\"subtab-AdminFeatureFlag\">
                                <a href=\"/admin730/index.php/configure/advanced/feature-flags/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\" class=\"link\"> Características experimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"225\" id=\"subtab-AdminMenuTabs\">
                                <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminMenuTabs&amp;token=70b7c4f93fe938dc15e04563aaa407c9\" class=\"link\"> Menu
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"152\" id=\"tab-AdminYbcBlogFree\">
                <span class=\"title\">Blog</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"153\" id=\"subtab-AdminYbcBlogFreePost\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreePost&amp;token=3c242e16b50b7b81ec85397c3f593246\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Blog posts
                      </span>
                                                    <i class";
        // line 1197
        echo "=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminYbcBlogFreeCategory\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreeCategory&amp;token=44dfed429a5fdac4242dc9f93bfbe192\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Blog categories
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"155\" id=\"subtab-AdminYbcBlogFreeComment\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreeComment&amp;token=3a64e4c3fae5e7b775fb7493e093cd6a\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Blog comments
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                  ";
        // line 1229
        echo "                          </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"156\" id=\"subtab-AdminYbcBlogFreeSlider\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreeSlider&amp;token=67e7140b7ce0b57f8467cc24e8a05009\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Blog Slider
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"157\" id=\"subtab-AdminYbcBlogFreeGallery\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreeGallery&amp;token=cdf49b9bfd4c40eccc382cbad18692b8\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Blog gallery
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                       ";
        // line 1262
        echo "       
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"158\" id=\"subtab-AdminYbcBlogFreeSetting\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminYbcBlogFreeSetting&amp;token=a7bbaf1d13c021a0a33e8e7e0be83af6\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Settings
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"169\" id=\"tab-AdminPhConRoot\">
                <span class=\"title\">PrestaHero</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"170\" id=\"subtab-AdminPhConListModules\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminPhConListModules&amp;token=aa5742718093e2f55a5bf412aa6d6d29\" class=\"link\">
                      <i class=\"material-icons mi-ph-con-sidebar-icon-list-module\">ph-con-sidebar-icon-list-module</i>
                      <span>
                      PrestaHero modules
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                       ";
        // line 1299
        echo "                     </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"218\" id=\"tab-AdminGwadvancedinvoice\">
                <span class=\"title\">Advanced Invoice</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"219\" id=\"subtab-AdminGwadvancedinvoiceconfig\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminGwadvancedinvoiceconfig&amp;token=e81960b35b956d11d909c2d79021dec1\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      General Settings
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"220\" id=\"subtab-AdminGwadvancedinvoicetemplate\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminGwadvancedinvoicetemplate&amp;token=309c748470a6c1697b5b9d5d692da3bb\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Manage Templates
                      </span>
                                                    <i cl";
        // line 1337
        echo "ass=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"221\" id=\"subtab-AdminGwaicustomnumber\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminGwaicustomnumber&amp;token=db07fa478a7c5bf4e890bb691d089f7c\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Custom number
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"222\" id=\"subtab-AdminGwadvancedinvoiceabout\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminGwadvancedinvoiceabout&amp;token=0fb6349832958152f1ef64f5e13ef5f7\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      List Variables
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                              ";
        // line 1369
        echo "                              </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"244\" id=\"tab-AdminRestrictPaymentMethods\">
                <span class=\"title\">Restrict Payment Methods</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"245\" id=\"subtab-AdminRestrictPaymentMethod\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminRestrictPaymentMethod&amp;token=78a33b8e26d9401d8620468e2a6d24bd\" class=\"link\">
                      <i class=\"material-icons mi-lock\">lock</i>
                      <span>
                      Restrict Payment Methods
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"247\" id=\"tab-MrShopApi\">
                <span class=\"title\">Mobile App</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"248\" id=\"subtab-AdminMrShopApiSettings\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminMrShopApiSettings&amp;token=b8a";
        // line 1412
        echo "f2fb0bb41577a96ff76d47e2f1fef\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Settings
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"266\" id=\"tab-AdminCustomFields\">
                <span class=\"title\">Información Adicional de Pagos</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"267\" id=\"subtab-AdminFields\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminFields&amp;token=21f8c8e2361ef1410ed45a2499f6e6fd\" class=\"link\">
                      <i class=\"material-icons mi-content_paste\">content_paste</i>
                      <span>
                      Administrar Campos Personalizados
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"268\" id=\"subtab-AdminCus";
        // line 1451
        echo "tomFieldsHeadings\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCustomFieldsHeadings&amp;token=57129307fb776249a4e2f88b7d0ef82d\" class=\"link\">
                      <i class=\"material-icons mi-insert_drive_file\">insert_drive_file</i>
                      <span>
                      Campos Personalizados Encabezados
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"269\" id=\"subtab-AdminCustomFieldsSetting\">
                    <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminCustomFieldsSetting&amp;token=971f7bf09c816230bcdc27e57d193d0a\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuración de campos personalizados
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                  </ul>
  </div>
  
</nav>


<div class=\"header-toolbar d-print-none\">
        <div class=\"header-multishop header-multishop-bright\">
      <div class=\"header-multishop-top-bar\" style=\"background-color: #0000ff;\">
        <div class=\"header-multishop-center ";
        // line 1488
        echo "js-header-multishop-open-modal\">
                      <svg width=\"43px\" height=\"30px\" viewBox=\"0 0 43 30\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">
              <defs>
                  <rect id=\"path-1\" x=\"0\" y=\"0\" width=\"51\" height=\"36\"></rect>
              </defs>
              <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" opacity=\"0.599844\">
                <g id=\"storefront-24px-(1)\" transform=\"translate(-4.000000, -6.000000)\">
                  <g id=\"Shape\">
                    <mask id=\"mask-2\" fill=\"white\">
                      <use xlink:href=\"#path-1\"></use>
                    </mask>
                    <g id=\"Mask\"></g>
                    <path d=\"M46.787234,18.4344444 L44.5290073,9.20888889 C44.0558551,7.30888889 42.3783153,6 40.4211856,6 L10.5480733,6 C8.61245052,6 6.91340382,7.33 6.46175849,9.20888889 L4.20353187,18.4344444 C3.68736578,20.5877778 4.16051803,22.7833333 5.53696092,24.5144444 C5.70901628,24.7466667 5.94559241,24.9155556 6.13915469,25.1266667 L6.13915469,39.7777778 C6.13915469,42.1 8.07477751,44 10.4405387,44 L40.5502271,44 C42.9159883,44 44.8516111,42.1 44.8516111,39.7777778 L44.8516111,25.1266667 C45.0451734,24.9366667 45.2817495,24.7466667 45.4538049,24.5355556 C46.8302478,22.8044444 47.324907,20.5877778 46.787234,18.4344444 Z M40.3566648,10.2011111 L42.6148914,19.4266667 C42.8299606,20.3133333 42.6363984,21.2 42.0772184,21.8966667 C41.7761215,22.2766667 41.1309139,22.8888889 40.0555679,22.8888889 C38.7436458,22.8888889 37.603779,21.8544444 37.4532306,20.4822222 L36.2058292,10.2222222 L40.3566648,10.2011111 Z M27.6460749,10.2222222 L31.8614313,10.2222222 L33.022805,19.7644444 C33.1303396,20.5877778 32.8722566,21.4111111 32.3130766,22.0233333 C31.8399244,22.5722222 31.1517029,22.8888889 30.2699192,22.8888889 C28.8289556,22.8888889 27.6460749,21.6433333 27.6460749,20.1233333 L27.6460749,10.2222222 Z M17.9464539,19.7644444 L19.1293345,10.2222222 L23.";
        // line 1500
        echo "3446909,10.2222222 L23.3446909,20.1233333 C23.3446909,21.6433333 22.1618103,22.8888889 20.5702982,22.8888889 C19.8390629,22.8888889 19.1723484,22.5722222 18.6561823,22.0233333 C18.1185093,21.4111111 17.8604262,20.5877778 17.9464539,19.7644444 Z M8.37587439,19.4266667 L10.5480733,10.2222222 L14.7849366,10.2222222 L13.5375353,20.4822222 C13.3654799,21.8544444 12.24712,22.8888889 10.9351979,22.8888889 C9.88135881,22.8888889 9.21464428,22.2766667 8.93505432,21.8966667 C8.35436747,21.2211111 8.16080519,20.3133333 8.37587439,19.4266667 Z M10.4405387,39.7777778 L10.4405387,27.0477778 C10.6125941,27.0688889 10.7631425,27.1111111 10.9351979,27.1111111 C12.8063,27.1111111 14.5053467,26.3511111 15.752748,25.1055556 C17.0431633,26.3722222 18.7637169,27.1111111 20.7208466,27.1111111 C22.5919487,27.1111111 24.2694885,26.3511111 25.5168898,25.1477778 C26.7857981,26.3511111 28.5063517,27.1111111 30.4419746,27.1111111 C32.2485559,27.1111111 33.9691095,26.3722222 35.2595247,25.1055556 C36.5069261,26.3511111 38.2059728,27.1111111 40.0770748,27.1111111 C40.2491302,27.1111111 40.3996786,27.0688889 40.571734,27.0477778 L40.571734,39.7777778 L10.4405387,39.7777778 Z\" fill=\"white\" fill-rule=\"nonzero\" mask=\"url(#mask-2)\"></path>
                  </g>
                </g>
              </g>
            </svg>
          
          <h2 class=\"header-multishop-title\">
          CapresTSJ
          </h2>

          <button class=\"header-multishop-button\">
            <i class=\"material-icons\">expand_more</i>
          </button>
        </div>
      </div>

              <div class=\"header-multishop-right\">
          <a class=\"header-multishop-view-action\" href=\"https://tucarritocaprestsj.com/\" target=\"_blank\" rel=\"nofollow\">Ver mi tienda <i class=\"material-icons\">visibility</i></a>
        </div>
      
      <div id=\"multishop-modal\" class=\"multishop-modal multishop-modal-hidden js-multishop-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"multishop-modal\" aria-hidden=\"true\">
        <div c";
        // line 1521
        echo "lass=\"multishop-modal-dialog\" role=\"document\">
          <div class=\"multishop-modal-body\">
                        <div class=\"multishop-modal-search-container\">
              <i class=\"material-icons\">search</i>
              <input type=\"text\" class=\"form-control multishop-modal-search js-multishop-modal-search\" placeholder=\"Search shop name\" data-no-results=\"No se encontraron resultados para\" data-searching=\"Buscando por\">
            </div>
            
            <ul class=\"multishop-modal-group-list js-multishop-scrollbar\">
                <li class=\"multishop-modal-all multishop-modal-item\">
                                      <span class=\"multishop-modal-color\" style=\"background-color:#25b9d7;\"></span>
                                    <a class=\"multishop-modal-all-name\" href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw&amp;setShopContext=\">
                    <span>Todas las tiendas</span>
                  </a>
                </li>

                              <li class=\"multishop-modal-group-item multishop-modal-item\">
                  <span class=\"multishop-modal-color-container\">
                    <i class=\"material-icons\">check</i>
                    <a class=\"multishop-modal-color\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminShopGroup&amp;id_shop_group=1&amp;updateshop_group=1&amp;token=ac7b7fcc04a29f2ae5b6909a68068c57\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"top\" data-content=\"Editar color\" data-original-title=\"\" title=\"\"></a>
                  </span>
                  <a class=\"multishop-modal-group-name\" href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw&amp;setShopContext=g-1\">Grupo Default</a>
                </li>

                                  <li class=\"multishop-modal-shop-item multishop-modal-item\">
                    <div class=\"multishop-modal-item-left\">
                    <span class=\"multis";
        // line 1546
        echo "hop-modal-color-container\">
                      <i class=\"material-icons\">check</i>
                      <a class=\"multishop-modal-color\" style=\"background-color: #ff800b;\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminShop&amp;shop_id=1&amp;updateshop=1&amp;token=1c872c26c5296093a9d55a8fc35141c4\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"top\" data-content=\"Editar color\" data-original-title=\"\" title=\"\"></a>
                    </span>
                    <a class=\"multishop-modal-shop-name\" href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw&amp;setShopContext=s-1\">Inversiones RPZ</a>                    </div>
                                          <a class=\"multishop-modal-shop-view\" href=\"https://inversionesrpz.com/\" target=\"_blank\" rel=\"noreferrer\">Ver mi tienda <i class=\"material-icons\">visibility</i></a>
                                      </li>
                                  <li class=\"multishop-modal-shop-item multishop-modal-item\">
                    <div class=\"multishop-modal-item-left\">
                    <span class=\"multishop-modal-color-container multishop-modal-color-check\">
                      <i class=\"material-icons\">check</i>
                      <a class=\"multishop-modal-color\" style=\"background-color: #0000ff;\" href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminShop&amp;shop_id=8&amp;updateshop=1&amp;token=1c872c26c5296093a9d55a8fc35141c4\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"top\" data-content=\"Editar color\" data-original-title=\"\" title=\"\"></a>
                    </span>
                    <a class=\"multishop-modal-shop-name\" href=\"/admin730/index.php/sell/customers/?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw&amp;setShopContext=s-8\">CapresTSJ</a>                    </div>
                                          <a class=\"multishop-modal-shop-view\" href=\"https://tucarritocaprestsj.com/\" target=\"_blank\"";
        // line 1560
        echo " rel=\"noreferrer\">Ver mi tienda <i class=\"material-icons\">visibility</i></a>
                                      </li>
                                          </ul>
          </div>
        </div>
      </div>
    </div>

    <script src=\"/admin730/themes/new-theme/public/multistore_header.bundle.js?1.7.8.7\"></script>
  
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Clientes</li>
          
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Administración de clientes          </h1>
      

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                                                          <a
                  class=\"btn btn-primary pointer\"                  id=\"page-header-desc-configuration-add\"
                  href=\"/admin730/index.php/sell/customers/new?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\"                  title=\"Añadir nuevo cliente\"                >
                  <i class=\"material-icons\">add_circle_outline</i>                  Añadir nuevo cliente
                </a>
                                      
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Ayuda\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/admin730/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop.com%252Fve%252Fdoc%252FAdminCustomers%253Fversion%253D1.7.8.7%2526country%253Dve/Ayuda?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\"
                   id=\"product_form_open_help\"
                >
                  Ayuda
                </a>
                                    </div>
        </div>

      
    </div>
  </div>

  
  
  <div class=\"btn-floating\">
    <button class=";
        // line 1617
        echo "\"btn btn-primary collapsed\" data-toggle=\"collapse\" data-target=\".btn-floating-container\" aria-expanded=\"false\">
      <i class=\"material-icons\">add</i>
    </button>
    <div class=\"btn-floating-container collapse\">
      <div class=\"btn-floating-menu\">
        
                              <a
              class=\"btn btn-floating-item  pointer\"              id=\"page-header-desc-floating-configuration-add\"
              href=\"/admin730/index.php/sell/customers/new?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\"              title=\"Añadir nuevo cliente\"            >
              Añadir nuevo cliente
              <i class=\"material-icons\">add_circle_outline</i>            </a>
                  
                              <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Ayuda\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"/admin730/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop.com%252Fve%252Fdoc%252FAdminCustomers%253Fversion%253D1.7.8.7%2526country%253Dve/Ayuda?_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw\"
            >
              Ayuda
            </a>
                        </div>
    </div>
  </div>
  <script>
  if (undefined !== mbo) {
    mbo.initialize({
      translations: {
        'Recommended Modules and Services': 'Módulos y Servicios recomendados',
        'Close': 'Cerrar',
      },
      recommendedModulesUrl: '/admin730/index.php/modules/addons/modules/recommended?tabClassName=AdminCustomers&_token=w44tEQUDCi39KLYh3jVM5fzIOwY5Sj9NWT-2CSKm1Aw',
      shouldAttachRecommendedModulesAfterContent: 0,
      shouldAttachRecommendedModulesButton: 1,
      shouldUseLegacyTheme: 0,
    });
  }
</script>

</div>

<div id=\"main-div\">
          
      <div class=\"content-div  \">

        

                                                        
        <div class=\"row \">
          <div class=\"col-sm-12\">
            <div id=\"ajax_co";
        // line 1666
        echo "nfirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>


  ";
        // line 1669
        $this->displayBlock('content_header', $context, $blocks);
        $this->displayBlock('content', $context, $blocks);
        $this->displayBlock('content_footer', $context, $blocks);
        $this->displayBlock('sidebar_right', $context, $blocks);
        echo "

            
          </div>
        </div>

      </div>
    </div>

  <div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>¡Oh no!</h1>
  <p class=\"mt-3\">
    La versión para móviles de esta página no está disponible todavía.
  </p>
  <p class=\"mt-2\">
    Por favor, utiliza un ordenador de escritorio hasta que esta página sea adaptada para dispositivos móviles.
  </p>
  <p class=\"mt-2\">
    Gracias.
  </p>
  <a href=\"https://inversionesrpz.com/admin730/index.php?controller=AdminDashboard&amp;token=0ec77cfb39e809499051f7102108b9a3\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons\">arrow_back</i>
    Volver
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    
</div>
  

      <div class=\"bootstrap\">
      <div class=\"modal fade\" id=\"modal_addons_connect\" tabindex=\"-1\">
\t<div class=\"modal-dialog modal-md\">
\t\t<div class=\"modal-content\">
\t\t\t\t\t\t<div class=\"modal-header\">
\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
\t\t\t\t<h4 class=\"modal-title\"><i class=\"icon-puzzle-piece\"></i> <a target=\"_blank\" href=\"https://addons.prestashop.com/?utm_source=back-office&utm_medium=modules&utm_campaign=back-office-VE&utm_content=download\">PrestaShop Addons</a></h4>
\t\t\t</div>
\t\t\t
\t\t\t<div class=\"modal-body\">
\t\t\t\t\t\t<!--start addons login-->
\t\t\t<form id=\"addons_login_form\" method=\"post\" >
\t\t\t\t<div>
\t\t\t\t\t<a href=\"https://addons.prestashop.com/ve/login?email=vicmerablancou%40gmail.com&amp;firstname=Vicmer&amp;lastname=Blanco&amp;website=http%3A%2F%2Ftucarritocaprestsj.com%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-VE&amp;utm_content=download#createnow\"><img class=\"img-responsive center-block\" src=\"/admin730/themes/default/img/prestashop-addons-logo.png\" alt=\"Logo PrestaShop Addons\"/></a>
\t\t\t\t\t<h3 class=\"text-center\">Conecta tu tienda con el mercado de PrestaShop para importar automáticamente todas tus compras de Addons.</h3>
\t\t\t\t\t<hr />
\t";
        // line 1717
        echo "\t\t\t</div>
\t\t\t\t<div class=\"row\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>¿No tiene una cuenta?</h4>
\t\t\t\t\t\t<p class='text-justify'>¡Descubre el poder de PrestaShop Addons! Explora el Marketplace oficial de PrestaShop y encuentra más de 3.500 módulos y temas innovadores que optimizan las tasas de conversión, aumentan el tráfico, fidelizan a los clientes y maximizan tu productividad</p>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>Conectarme a PrestaShop Addons</h4>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-user\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"username_addons\" name=\"username_addons\" type=\"text\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-key\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"password_addons\" name=\"password_addons\" type=\"password\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<a class=\"btn btn-link float-right _blank\" href=\"//addons.prestashop.com/ve/forgot-your-password\">Olvidé mi contraseña</a>
\t\t\t\t\t\t\t<br>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div class=\"row row-padding-top\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<a class=\"btn btn-default btn-block btn-lg _blank\" href=\"https://addons.prestashop.com/ve/login?email=vicmerablancou%40gmail.com&amp;firstname=Vicmer&amp;lastname=Blanco&amp;website=http%3A%2F%2Ftucarritocaprestsj.com%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-VE&amp;utm_content=download#createnow\">
\t\t\t\t\t\t\t\tCrear una cuenta
\t\t\t\t\t\t\t\t<i class=\"icon-external-link\"></i>
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<button id=\"addons_login_b";
        // line 1757
        echo "utton\" class=\"btn btn-primary btn-block btn-lg\" type=\"submit\">
\t\t\t\t\t\t\t\t<i class=\"icon-unlock\"></i> Iniciar sesión
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div id=\"addons_loading\" class=\"help-block\"></div>

\t\t\t</form>
\t\t\t<!--end addons login-->
\t\t\t</div>


\t\t\t\t\t</div>
\t</div>
</div>

    </div>
  
";
        // line 1777
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>";
        echo "
</html>";
    }

    // line 125
    public function block_stylesheets($context, array $blocks = [])
    {
    }

    public function block_extra_stylesheets($context, array $blocks = [])
    {
    }

    // line 1669
    public function block_content_header($context, array $blocks = [])
    {
    }

    public function block_content($context, array $blocks = [])
    {
    }

    public function block_content_footer($context, array $blocks = [])
    {
    }

    public function block_sidebar_right($context, array $blocks = [])
    {
    }

    // line 1777
    public function block_javascripts($context, array $blocks = [])
    {
    }

    public function block_extra_javascripts($context, array $blocks = [])
    {
    }

    public function block_translate_javascripts($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "__string_template__8f7489786b404c3b4208b683eca7a8b21145a41ab2356a451b618661b5ae955e";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1969 => 1777,  1952 => 1669,  1943 => 125,  1934 => 1777,  1912 => 1757,  1870 => 1717,  1816 => 1669,  1811 => 1666,  1760 => 1617,  1701 => 1560,  1685 => 1546,  1658 => 1521,  1635 => 1500,  1621 => 1488,  1582 => 1451,  1541 => 1412,  1496 => 1369,  1462 => 1337,  1422 => 1299,  1383 => 1262,  1348 => 1229,  1314 => 1197,  1275 => 1160,  1243 => 1130,  1210 => 1099,  1179 => 1070,  1147 => 1040,  1115 => 1010,  1079 => 976,  1046 => 945,  1011 => 912,  980 => 883,  947 => 852,  914 => 821,  880 => 789,  848 => 759,  817 => 730,  780 => 695,  745 => 662,  710 => 629,  680 => 601,  648 => 571,  618 => 543,  586 => 513,  553 => 482,  519 => 450,  486 => 419,  445 => 380,  419 => 356,  368 => 307,  321 => 262,  276 => 219,  251 => 196,  213 => 160,  173 => 125,  156 => 110,  131 => 87,  109 => 67,  82 => 42,  39 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__8f7489786b404c3b4208b683eca7a8b21145a41ab2356a451b618661b5ae955e", "");
    }
}
