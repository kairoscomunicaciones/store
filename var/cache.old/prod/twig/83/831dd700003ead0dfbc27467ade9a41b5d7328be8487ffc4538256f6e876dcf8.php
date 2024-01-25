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

/* @Product/CatalogPage/Forms/form_edit_dropdown.html.twig */
class __TwigTemplate_3e74cda69135cf87795432727dcc54b1b8aeaf52957cf5e3cc9fae3df3209854 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<div class=\"span_change_product\" style=\"display: inline;\">
    ";
        // line 2
        $context["buttonType"] = ((array_key_exists("buttonType", $context)) ? (_twig_default_filter(($context["buttonType"] ?? null), "primary")) : ("primary"));
        // line 3
        echo "    ";
        $context["right"] = ((array_key_exists("right", $context)) ? (_twig_default_filter(($context["right"] ?? null), false)) : (false));
        // line 4
        echo "    
    <div class=\"";
        // line 5
        echo twig_escape_filter($this->env, ((array_key_exists("div_style", $context)) ? (_twig_default_filter(($context["div_style"] ?? null), "btn-group")) : ("btn-group")), "html", null, true);
        echo "\">
    
      ";
        // line 7
        if (array_key_exists("default_item", $context)) {
            // line 8
            echo "        <a onclick=\"etsEditInlineProductAction(\$(this));return false;\"
          href=\"";
            // line 9
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["default_item"] ?? null), "href", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["default_item"] ?? null), "href", []), "#")) : ("#")), "html", null, true);
            echo "\"
          title=\"";
            // line 10
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Quick edit", [], "Modules.Etsproductmanager.Admin"), "html", null, true);
            echo "\"
          class=\"btn tooltip-link product-edit\"
          ";
            // line 12
            if ((array_key_exists("disabled", $context) && (($context["disabled"] ?? null) == true))) {
                echo "disabled=\"disabled\"";
            }
            // line 13
            echo "        >
          <i class=\"ets_svg_icon\">
            <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M888 1184l116-116-152-152-116 116v56h96v96h56zm440-720q-16-16-33 1l-350 350q-17 17-1 33t33-1l350-350q17-17 1-33zm80 594v190q0 119-84.5 203.5t-203.5 84.5h-832q-119 0-203.5-84.5t-84.5-203.5v-832q0-119 84.5-203.5t203.5-84.5h832q63 0 117 25 15 7 18 23 3 17-9 29l-49 49q-14 14-32 8-23-6-45-6h-832q-66 0-113 47t-47 113v832q0 66 47 113t113 47h832q66 0 113-47t47-113v-126q0-13 9-22l64-64q15-15 35-7t20 29zm-96-738l288 288-672 672h-288v-288zm444 132l-92 92-288-288 92-92q28-28 68-28t68 28l152 152q28 28 28 68t-28 68z\"/></svg>
          </i>
          ";
            // line 17
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["default_item"] ?? null), "label", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["default_item"] ?? null), "label", []), "")) : ("")), "html", null, true);
            echo "
        </a>
      ";
        }
        // line 20
        echo "    
      <button class=\"btn btn-link dropdown-toggle dropdown-toggle-split product-edit\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        ";
        // line 22
        echo twig_escape_filter($this->env, ((array_key_exists("menu_label", $context)) ? (_twig_default_filter(($context["menu_label"] ?? null), "")) : ("")), "html", null, true);
        echo "
      </button>
    
      <div class=\"dropdown-menu";
        // line 25
        if (($context["right"] ?? null)) {
            echo " dropdown-menu-right";
        }
        echo "\" x-placement=\"bottom-start\" >
        ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["entry"]) {
            // line 27
            echo "          ";
            if (($this->getAttribute($context["entry"], "divider", [], "any", true, true) && ($this->getAttribute($context["entry"], "divider", []) == true))) {
                // line 28
                echo "            <div class=\"dropdown-divider\"></div>
          ";
            } else {
                // line 30
                echo "            <a
              class=\"dropdown-item product-edit\" href=\"";
                // line 31
                echo twig_escape_filter($this->env, (($this->getAttribute($context["entry"], "href", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute($context["entry"], "href", []), "#")) : ("#")), "html", null, true);
                echo "\"
              ";
                // line 32
                if ($this->getAttribute($context["entry"], "onclick", [], "any", true, true)) {
                    echo "onclick=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["entry"], "onclick", []), "html", null, true);
                    echo "\"";
                }
                // line 33
                echo "              ";
                if ($this->getAttribute($context["entry"], "target", [], "any", true, true)) {
                    echo "target=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["entry"], "target", []), "html", null, true);
                    echo "\"";
                }
                // line 34
                echo "            >
              ";
                // line 35
                if ($this->getAttribute($context["entry"], "icon", [])) {
                    // line 36
                    echo "                <i class=\"material-icons\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["entry"], "icon", []), "html", null, true);
                    echo "</i>
              ";
                }
                // line 38
                echo "              ";
                echo twig_escape_filter($this->env, (($this->getAttribute($context["entry"], "label", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute($context["entry"], "label", []), "")) : ("")), "html", null, true);
                echo "
            </a>
          ";
            }
            // line 41
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entry'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "      </div>
    </div>
</div>
<div class=\"group-action-update-product\" style=\"display:none\">
    <button class=\"btn btn-default\" type=\"button\" name=\"submitProductChangeInLine\"><i class=\"icon-ok\"></i> ";
        // line 46
        echo twig_escape_filter($this->env, ($context["ets_pmn_update_text"] ?? null), "html", null, true);
        echo "</button>
    <button class=\"btn btn-default cancel_product_change_link\" type=\"button\"><i class=\"icon-remove\"></i> ";
        // line 47
        echo twig_escape_filter($this->env, ($context["ets_pmn_cancel_text"] ?? null), "html", null, true);
        echo "</button>
</div>";
    }

    public function getTemplateName()
    {
        return "@Product/CatalogPage/Forms/form_edit_dropdown.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  157 => 47,  153 => 46,  147 => 42,  141 => 41,  134 => 38,  128 => 36,  126 => 35,  123 => 34,  116 => 33,  110 => 32,  106 => 31,  103 => 30,  99 => 28,  96 => 27,  92 => 26,  86 => 25,  80 => 22,  76 => 20,  70 => 17,  64 => 13,  60 => 12,  55 => 10,  51 => 9,  48 => 8,  46 => 7,  41 => 5,  38 => 4,  35 => 3,  33 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Product/CatalogPage/Forms/form_edit_dropdown.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Product/CatalogPage/Forms/form_edit_dropdown.html.twig");
    }
}
