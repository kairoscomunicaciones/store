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

/* @Product/CatalogPage/Blocks/filters.html.twig */
class __TwigTemplate_c5f164ba92eca2fa8c524e5c62f6e0ab55dc65af66762dd35f9332b1e263d4b7 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'product_catalog_filter_by_categories' => [$this, 'block_product_catalog_filter_by_categories'],
            'product_catalog_filter_bulk_actions' => [$this, 'block_product_catalog_filter_bulk_actions'],
            'product_catalog_filter_select_all' => [$this, 'block_product_catalog_filter_select_all'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 21
        echo "<div id=\"catalog-actions\" class=\"col order-first\">
  <div class=\"row\">
    <div class=\"col\">
      ";
        // line 24
        $this->displayBlock('product_catalog_filter_by_categories', $context, $blocks);
        // line 72
        echo "
      ";
        // line 73
        $this->displayBlock('product_catalog_filter_bulk_actions', $context, $blocks);
        // line 126
        echo "      ";
        if ((array_key_exists("ets_pmg_list_views", $context) &&  !(null === ($context["ets_pmg_list_views"] ?? null)))) {
            // line 127
            echo "          <div class=\"d-inline-block d-inline-block dropdown dropdown-clickable mr-2 ml-2\">
            <div id=\"form_view_selected2\">
              <label>";
            // line 129
            echo twig_escape_filter($this->env, ($context["ets_pmn_view_text"] ?? null), "html", null, true);
            echo "</label>
                <select name=\"id_view_selected2\" class=\"btn\" id=\"id_view_selected2\" data-href=\"";
            // line 130
            echo twig_escape_filter($this->env, ($context["ets_pmg_link_productmanager_setting"] ?? null), "html", null, true);
            echo "\">
                    ";
            // line 131
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["ets_pmg_list_views"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["view"]) {
                // line 132
                echo "                        <option data-fields=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["view"], "fields", []), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["view"], "id_ets_pmn_view", []), "html", null, true);
                echo "\"";
                if (($this->getAttribute($context["view"], "id_ets_pmn_view", []) == ($context["ets_pmg_id_view_selected"] ?? null))) {
                    echo " selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["view"], "name", []), "html", null, true);
                echo "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['view'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 134
            echo "                </select>
            </div>
          </div>
      ";
        }
        // line 138
        echo "      ";
        if (array_key_exists("ets_pmg_link_product_arrange", $context)) {
            // line 139
            echo "          <div class=\"d-inline-block\">
            <a id=\"desc-product-arrange2\" class=\"btn btn-default btn-outline-secondary ml-2\" href=\"";
            // line 140
            echo twig_escape_filter($this->env, ($context["ets_pmg_link_product_arrange"] ?? null), "html", null, true);
            echo "\">
                ";
            // line 141
            echo twig_escape_filter($this->env, ($context["Customize_product_list_text"] ?? null), "html", null, true);
            echo "
            </a>
          </div>
      ";
        }
        // line 145
        echo "    </div>
  </div>

  ";
        // line 148
        $this->displayBlock('product_catalog_filter_select_all', $context, $blocks);
        // line 166
        echo "</div>
";
    }

    // line 24
    public function block_product_catalog_filter_by_categories($context, array $blocks = [])
    {
        // line 25
        echo "        <div id=\"product_catalog_category_tree_filter\" class=\"d-inline-block dropdown dropdown-clickable mr-2\">
          <button
                  class=\"btn btn-outline-secondary dropdown-toggle\"
                  type=\"button\"
                  data-toggle=\"dropdown\"
                  aria-haspopup=\"true\"
                  aria-expanded=\"false\"
          >
              ";
        // line 33
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("categories", [], "Admin.Actions"), "html", null, true);
        echo "
              ";
        // line 34
        if ((array_key_exists("selected_category", $context) &&  !(null === ($context["selected_category"] ?? null)))) {
            // line 35
            echo "                  (";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["selected_category"] ?? null), "getName", [], "method"), "html", null, true);
            echo ")
              ";
        }
        // line 37
        echo "          </button>
          <div id=\"tree-categories\" class=\"dropdown-menu\">
            <div class=\"categories-tree-actions\">
              <a
                href=\"#\"
                name=\"product_catalog_category_tree_filter_expand\"
                onclick=\"productCategoryFilterExpand(\$('div#product_catalog_category_tree_filter'), this);\"
                id=\"product_catalog_category_tree_filter_expand\"
              >
                <i class=\"material-icons\">expand_more</i>
                  ";
        // line 47
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Expand", [], "Admin.Actions"), "html", null, true);
        echo "
              </a>
              <a
                href=\"#\"
                name=\"product_catalog_category_tree_filter_collapse\"
                onclick=\"productCategoryFilterCollapse(\$('div#product_catalog_category_tree_filter'), this);\"
                id=\"product_catalog_category_tree_filter_collapse\"
              >
                <i class=\"material-icons\">expand_less</i>
                  ";
        // line 56
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Collapse", [], "Admin.Actions"), "html", null, true);
        echo "
              </a>
              <a
                href=\"#\"
                name=\"product_catalog_category_tree_filter_reset\"
                onclick=\"productCategoryFilterReset(\$('div#product_catalog_category_tree_filter'));\"
                id=\"product_catalog_category_tree_filter_reset\"
              >
                <i class=\"material-icons\">radio_button_unchecked</i>
                  ";
        // line 65
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Unselect", [], "Admin.Actions"), "html", null, true);
        echo "
              </a>
            </div>
              ";
        // line 68
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["categories"] ?? null), 'widget');
        echo "
          </div>
        </div>
      ";
    }

    // line 73
    public function block_product_catalog_filter_bulk_actions($context, array $blocks = [])
    {
        // line 74
        echo "        <div
            class=\"d-inline-block\"
            bulkurl=\"";
        // line 76
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_bulk_action", ["action" => "activate_all"]);
        echo "\"
            massediturl=\"";
        // line 77
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_mass_edit_action", ["action" => "sort"]);
        echo "\"
            redirecturl=\"";
        // line 78
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_catalog", ["limit" => ($context["limit"] ?? null), "offset" => ($context["offset"] ?? null), "orderBy" => ($context["orderBy"] ?? null), "sortOrder" => ($context["sortOrder"] ?? null)]), "html", null, true);
        echo "\"
            redirecturlnextpage=\"";
        // line 79
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_product_catalog", ["limit" => ($context["limit"] ?? null), "offset" => (($context["offset"] ?? null) + ($context["limit"] ?? null)), "orderBy" => ($context["orderBy"] ?? null), "sortOrder" => ($context["sortOrder"] ?? null)]), "html", null, true);
        echo "\"
        >
          ";
        // line 81
        $context["buttons_action"] = [0 => ["onclick" => "bulkProductAction(this, 'activate_all');", "icon" => "radio_button_checked", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Activate selection", [], "Admin.Actions")], 1 => ["onclick" => "bulkProductAction(this, 'deactivate_all');", "icon" => "radio_button_unchecked", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Deactivate selection", [], "Admin.Actions")]];
        // line 93
        echo "
          ";
        // line 94
        $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["divider" => true], 1 => ["onclick" => "bulkProductAction(this, 'duplicate_all');", "icon" => "content_copy", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Duplicate selection", [], "Admin.Actions")]]);
        // line 103
        echo "

          ";
        // line 105
        $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["divider" => true], 1 => ["onclick" => "bulkProductAction(this, 'delete_all');", "icon" => "delete", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Delete selection", [], "Admin.Actions")]]);
        // line 114
        echo "
          ";
        // line 115
        $this->loadTemplate("@PrestaShop/Admin/Helpers/dropdown_menu.html.twig", "@Product/CatalogPage/Blocks/filters.html.twig", 115)->display(twig_array_merge($context, ["div_style" => "btn-group dropdown bulk-catalog", "button_id" => "product_bulk_menu", "disabled" => true, "menu_label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Bulk actions", [], "Admin.Global"), "buttonType" => "outline-secondary", "menu_icon" => "icon-caret-up", "items" =>         // line 122
($context["buttons_action"] ?? null)]));
        // line 124
        echo "        </div>
      ";
    }

    // line 148
    public function block_product_catalog_filter_select_all($context, array $blocks = [])
    {
        // line 149
        echo "  <div class=\"row\">
    <div class=\"col\">
      <div class=\"md-checkbox bulk_action_select_all_etsp_manage\">
        <label>
          <input
            type=\"checkbox\"
            id=\"bulk_action_select_all\"
            onclick=\"\$('#product_catalog_list').find('table td.checkbox-column input:checkbox').prop('checked', \$(this).prop('checked')); updateBulkMenu();\"
            value=\"\"
          >
          <i class=\"md-checkbox-control\"></i>
            ";
        // line 160
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Select all", [], "Admin.Actions"), "html", null, true);
        echo "
        </label>
      </div>
    </div>
  </div>
  ";
    }

    public function getTemplateName()
    {
        return "@Product/CatalogPage/Blocks/filters.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  255 => 160,  242 => 149,  239 => 148,  234 => 124,  232 => 122,  231 => 115,  228 => 114,  226 => 105,  222 => 103,  220 => 94,  217 => 93,  215 => 81,  210 => 79,  206 => 78,  202 => 77,  198 => 76,  194 => 74,  191 => 73,  183 => 68,  177 => 65,  165 => 56,  153 => 47,  141 => 37,  135 => 35,  133 => 34,  129 => 33,  119 => 25,  116 => 24,  111 => 166,  109 => 148,  104 => 145,  97 => 141,  93 => 140,  90 => 139,  87 => 138,  81 => 134,  64 => 132,  60 => 131,  56 => 130,  52 => 129,  48 => 127,  45 => 126,  43 => 73,  40 => 72,  38 => 24,  33 => 21,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Product/CatalogPage/Blocks/filters.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Product/CatalogPage/Blocks/filters.html.twig");
    }
}
