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

/* @Product/CatalogPage/Forms/form_products.html.twig */
class __TwigTemplate_47d0d88740440993729622c1935dee9db6163c8a881ad4fcfaf26ef53fbd1cba extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'product_catalog_form_table' => [$this, 'block_product_catalog_form_table'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 21
        echo "<form
  name=\"product_catalog_list\"
  id=\"product_catalog_list\"
  method=\"post\"
  action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_catalog", ["limit" => ($context["limit"] ?? null), "orderBy" => ($context["orderBy"] ?? null), "sortOrder" => ($context["sortOrder"] ?? null)]), "html", null, true);
        echo "\"
  orderingurl=\"";
        // line 26
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_catalog", ["offset" => 0, "limit" => 300, "orderBy" => "position_ordering", "sortOrder" => ($context["sortOrder"] ?? null)]), "html", null, true);
        echo "\"
  newproducturl=\"";
        // line 27
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_new");
        echo "\"
>
  <div class=\"row\">
    <div class=\"col-md-12\">
      <input type=\"hidden\" name=\"filter_category\" value=\"";
        // line 31
        echo twig_escape_filter($this->env, ((array_key_exists("filter_category", $context)) ? (_twig_default_filter(($context["filter_category"] ?? null), "")) : ("")), "html", null, true);
        echo "\" />
    </div>
  </div>

  <div class=\"row\">
    <div class=\"col-md-12\">
      ";
        // line 37
        $this->displayBlock('product_catalog_form_table', $context, $blocks);
        // line 58
        echo "    </div>
  </div>
  <!--begin_pagination_list_product!--> 
  <div class=\"pagination_list_product\">
      ";
        // line 62
        if ((($context["product_count_filtered"] ?? null) > 20)) {
            // line 63
            echo "        <div class=\"row\">
          <div class=\"col-md-12\">
    
            ";
            // line 66
            echo $this->env->getRuntime('Symfony\Bridge\Twig\Extension\HttpKernelRuntime')->renderFragment(Symfony\Bridge\Twig\Extension\HttpKernelExtension::controller("PrestaShopBundle:Admin\\Common:pagination", ["limit" =>             // line 67
($context["limit"] ?? null), "offset" =>             // line 68
($context["offset"] ?? null), "total" =>             // line 69
($context["product_count_filtered"] ?? null), "caller_route" => $this->getAttribute($this->getAttribute($this->getAttribute(            // line 70
($context["app"] ?? null), "request", []), "attributes", []), "get", [0 => "_route"], "method"), "caller_parameters" =>             // line 71
($context["pagination_parameters"] ?? null), "limit_choices" =>             // line 72
($context["pagination_limit_choices"] ?? null)]));
            // line 73
            echo "
          </div>
        </div>
      ";
        }
        // line 77
        echo "  </div> 
  <!--end_pagination_list_product!-->
</form>
";
    }

    // line 37
    public function block_product_catalog_form_table($context, array $blocks = [])
    {
        // line 38
        echo "        ";
        echo twig_include($this->env, $context, "@Product/CatalogPage/Lists/products_table.html.twig", ["limit" =>         // line 39
($context["limit"] ?? null), "orderBy" =>         // line 40
($context["orderBy"] ?? null), "offset" =>         // line 41
($context["offset"] ?? null), "sortOrder" =>         // line 42
($context["sortOrder"] ?? null), "filter_category" =>         // line 43
($context["filter_category"] ?? null), "filter_column_id_product" =>         // line 44
($context["filter_column_id_product"] ?? null), "filter_column_name" =>         // line 45
($context["filter_column_name"] ?? null), "filter_column_reference" =>         // line 46
($context["filter_column_reference"] ?? null), "filter_column_name_category" =>         // line 47
($context["filter_column_name_category"] ?? null), "filter_column_price" =>         // line 48
($context["filter_column_price"] ?? null), "filter_column_sav_quantity" =>         // line 49
($context["filter_column_sav_quantity"] ?? null), "filter_column_active" =>         // line 50
($context["filter_column_active"] ?? null), "has_category_filter" =>         // line 51
($context["has_category_filter"] ?? null), "activate_drag_and_drop" =>         // line 52
($context["activate_drag_and_drop"] ?? null), "products" =>         // line 53
($context["products"] ?? null), "last_sql" =>         // line 54
($context["last_sql"] ?? null)]);
        // line 56
        echo "
      ";
    }

    public function getTemplateName()
    {
        return "@Product/CatalogPage/Forms/form_products.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 56,  117 => 54,  116 => 53,  115 => 52,  114 => 51,  113 => 50,  112 => 49,  111 => 48,  110 => 47,  109 => 46,  108 => 45,  107 => 44,  106 => 43,  105 => 42,  104 => 41,  103 => 40,  102 => 39,  100 => 38,  97 => 37,  90 => 77,  84 => 73,  82 => 72,  81 => 71,  80 => 70,  79 => 69,  78 => 68,  77 => 67,  76 => 66,  71 => 63,  69 => 62,  63 => 58,  61 => 37,  52 => 31,  45 => 27,  41 => 26,  37 => 25,  31 => 21,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Product/CatalogPage/Forms/form_products.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Product/CatalogPage/Forms/form_products.html.twig");
    }
}
