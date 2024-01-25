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

/* @PrestaShop/Admin/Common/pagination.html.twig */
class __TwigTemplate_450f38d26132473435540a70b9f7cec3d6179bbab8a825c6775c49238778b6aa extends \Twig\Template
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
        // line 21
        echo "<div class=\"row justify-content-center\">
    <ul class=\"pagination mb-0\">
        <li class=\"page-item ";
        // line 23
        if (($context["first_url"] ?? null)) {
        } else {
            echo "disabled";
        }
        echo "\">
            <a class=\"page-link\" ";
        // line 24
        if (($context["first_url"] ?? null)) {
            echo "href=\"";
            echo twig_escape_filter($this->env, ($context["first_url"] ?? null), "html", null, true);
            if ((array_key_exists("orderBy", $context) && ($context["orderBy"] ?? null))) {
                echo "&orderBy=";
                echo twig_escape_filter($this->env, ($context["orderBy"] ?? null), "html", null, true);
            }
            if ((array_key_exists("sortOrder", $context) && ($context["sortOrder"] ?? null))) {
                echo "&sortOrder=";
                echo twig_escape_filter($this->env, ($context["sortOrder"] ?? null), "html", null, true);
            }
            echo "\"";
        } else {
            echo "nohref";
        }
        echo ">1</a>
        </li>
        <li class=\"page-item previous ";
        // line 26
        if (($context["previous_url"] ?? null)) {
        } else {
            echo "disabled";
        }
        echo "\">
            <a class=\"page-link\" aria-label=\"Previous\" ";
        // line 27
        if (($context["previous_url"] ?? null)) {
            echo "href=\"";
            echo twig_escape_filter($this->env, ($context["previous_url"] ?? null), "html", null, true);
            if ((array_key_exists("orderBy", $context) && ($context["orderBy"] ?? null))) {
                echo "&orderBy=";
                echo twig_escape_filter($this->env, ($context["orderBy"] ?? null), "html", null, true);
            }
            if ((array_key_exists("sortOrder", $context) && ($context["sortOrder"] ?? null))) {
                echo "&sortOrder=";
                echo twig_escape_filter($this->env, ($context["sortOrder"] ?? null), "html", null, true);
            }
            echo "\"";
        } else {
            echo "nohref";
        }
        echo "><span class=\"sr-only\">Previous</span></a>
        </li>
        <li class=\"page-item active\">
            <input name=\"";
        // line 30
        if (((array_key_exists("ETS_PMN_ENABLE_INSTANT_FILTER", $context) && ($context["ETS_PMN_ENABLE_INSTANT_FILTER"] ?? null)) && ( !array_key_exists("omd_current_controller", $context) || (($context["omd_current_controller"] ?? null) != "AdminOrders")))) {
            echo "paginator_product_jump_page";
        } elseif ((array_key_exists("ETS_ODM_ENABLE_INSTANT_FILTER", $context) && ($context["ETS_ODM_ENABLE_INSTANT_FILTER"] ?? null))) {
            echo "paginator_order_jump_page";
        } else {
            echo "paginator_jump_page";
        }
        if ((array_key_exists("orderBy", $context) && ($context["orderBy"] ?? null))) {
            echo "&orderBy=";
            echo twig_escape_filter($this->env, ($context["orderBy"] ?? null), "html", null, true);
        }
        if ((array_key_exists("sortOrder", $context) && ($context["sortOrder"] ?? null))) {
            echo "&sortOrder=";
            echo twig_escape_filter($this->env, ($context["sortOrder"] ?? null), "html", null, true);
        }
        echo "\" class=\"jump-to-page\" type=\"text\" value=\"";
        echo twig_escape_filter($this->env, ($context["current_page"] ?? null), "html", null, true);
        echo "\" psurl=\"";
        echo twig_escape_filter($this->env, ($context["jump_page_url"] ?? null), "html", null, true);
        echo "\" psmax=\"";
        echo twig_escape_filter($this->env, ($context["page_count"] ?? null), "html", null, true);
        echo "\" pslimit=\"";
        echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
        echo "\" />
        </li>
        <li class=\"page-item next ";
        // line 32
        if ((array_key_exists("next_url", $context) && (($context["next_url"] ?? null) != false))) {
        } else {
            echo "disabled";
        }
        echo "\">
            <a class=\"page-link\" aria-label=\"Next\" id=\"pagination_next_url\" ";
        // line 33
        if ((array_key_exists("next_url", $context) && (($context["next_url"] ?? null) != false))) {
            echo "href=\"";
            echo twig_escape_filter($this->env, ($context["next_url"] ?? null), "html", null, true);
            if ((array_key_exists("orderBy", $context) && ($context["orderBy"] ?? null))) {
                echo "&orderBy=";
                echo twig_escape_filter($this->env, ($context["orderBy"] ?? null), "html", null, true);
            }
            if ((array_key_exists("sortOrder", $context) && ($context["sortOrder"] ?? null))) {
                echo "&sortOrder=";
                echo twig_escape_filter($this->env, ($context["sortOrder"] ?? null), "html", null, true);
            }
            echo "\"";
        } else {
            echo "nohref";
        }
        echo "><span class=\"sr-only\">Next</span></a>
        </li>
        <li class=\"page-item ";
        // line 35
        if ((array_key_exists("last_url", $context) && (($context["last_url"] ?? null) != false))) {
        } else {
            echo "disabled";
        }
        echo "\">
            <a class=\"page-link\" ";
        // line 36
        if ((array_key_exists("last_url", $context) && (($context["last_url"] ?? null) != false))) {
            echo "href=\"";
            echo twig_escape_filter($this->env, ($context["last_url"] ?? null), "html", null, true);
            if ((array_key_exists("orderBy", $context) && ($context["orderBy"] ?? null))) {
                echo "&orderBy=";
                echo twig_escape_filter($this->env, ($context["orderBy"] ?? null), "html", null, true);
            }
            if ((array_key_exists("sortOrder", $context) && ($context["sortOrder"] ?? null))) {
                echo "&sortOrder=";
                echo twig_escape_filter($this->env, ($context["sortOrder"] ?? null), "html", null, true);
            }
            echo "\"";
        } else {
            echo "nohref";
        }
        echo ">";
        echo twig_escape_filter($this->env, ($context["page_count"] ?? null), "html", null, true);
        echo "</a>
        </li>
    </ul>
  <div class=\"mx-3\">
    <label class=\"col-form-label\">
      ";
        // line 41
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Viewing %from%-%to% on %total% (page %current_page% / %page_count%)", ["%from%" => (        // line 42
($context["from"] ?? null) + 1), "%to%" => min((        // line 43
($context["to"] ?? null) + 1), ($context["total"] ?? null)), "%total%" =>         // line 44
($context["total"] ?? null), "%current_page%" =>         // line 45
($context["current_page"] ?? null), "%page_count%" =>         // line 46
($context["page_count"] ?? null)], "Admin.Catalog.Feature"), "html", null, true);
        // line 47
        echo "
    </label>
  </div>
  <div class=\"mx-3\">
    <div class=\"form-group row mb-0\">
      <label for=\"paginator_select_page_limit\" class=\"col-form-label mr-2\">";
        // line 52
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Items per page:", [], "Admin.Catalog.Feature"), "html", null, true);
        echo "</label>
      <div>
        ";
        // line 54
        if (((array_key_exists("ETS_PMN_ENABLE_INSTANT_FILTER", $context) && ($context["ETS_PMN_ENABLE_INSTANT_FILTER"] ?? null)) && ( !array_key_exists("omd_current_controller", $context) || (($context["omd_current_controller"] ?? null) != "AdminOrders")))) {
            // line 55
            echo "            <select name=\"paginator_product_select_page_limit\" id=\"paginator_product_select_page_limit\" psurl=\"";
            echo twig_escape_filter($this->env, ($context["changeLimitUrl"] ?? null), "html", null, true);
            echo "\" class=\"pagination-link custom-select\">
                  ";
            // line 56
            if (!twig_in_filter(($context["limit"] ?? null), ($context["limit_choices"] ?? null))) {
                // line 57
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "\" selected=\"selected\">";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "</option>
                  ";
            }
            // line 59
            echo "                  ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["limit_choices"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["limit_choice"]) {
                // line 60
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "\" ";
                if ((($context["limit"] ?? null) == $context["limit_choice"])) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "</option>
                  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['limit_choice'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 62
            echo "            </select>
        ";
        } elseif ((        // line 63
array_key_exists("ETS_ODM_ENABLE_INSTANT_FILTER", $context) && ($context["ETS_ODM_ENABLE_INSTANT_FILTER"] ?? null))) {
            // line 64
            echo "            <select name=\"paginator_order_select_page_limit\" id=\"paginator_order_select_page_limit\" psurl=\"";
            echo twig_escape_filter($this->env, ($context["changeLimitUrl"] ?? null), "html", null, true);
            echo "\" class=\"pagination-link custom-select\">
                ";
            // line 65
            if (!twig_in_filter(($context["limit"] ?? null), ($context["limit_choices"] ?? null))) {
                // line 66
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "\" selected=\"selected\">";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "</option>
                ";
            }
            // line 68
            echo "                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["limit_choices"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["limit_choice"]) {
                // line 69
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "\" ";
                if ((($context["limit"] ?? null) == $context["limit_choice"])) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "</option>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['limit_choice'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 71
            echo "            </select>
        ";
        } else {
            // line 73
            echo "            <select name=\"paginator_select_page_limit\" id=\"paginator_select_page_limit\" psurl=\"";
            echo twig_escape_filter($this->env, ($context["changeLimitUrl"] ?? null), "html", null, true);
            echo "\" class=\"pagination-link custom-select\"";
            if ((array_key_exists("ETS_PMN_ENABLE_INSTANT_FILTER", $context) && ($context["ETS_PMN_ENABLE_INSTANT_FILTER"] ?? null))) {
                echo " style=\"display:none\"";
            }
            echo ">
                  ";
            // line 74
            if (!twig_in_filter(($context["limit"] ?? null), ($context["limit_choices"] ?? null))) {
                // line 75
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "\" selected=\"selected\">";
                echo twig_escape_filter($this->env, ($context["limit"] ?? null), "html", null, true);
                echo "</option>
                  ";
            }
            // line 77
            echo "                  ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["limit_choices"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["limit_choice"]) {
                // line 78
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "\" ";
                if ((($context["limit"] ?? null) == $context["limit_choice"])) {
                    echo "selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $context["limit_choice"], "html", null, true);
                echo "</option>
                  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['limit_choice'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 80
            echo "            </select>
        ";
        }
        // line 82
        echo "      </div>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Common/pagination.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  314 => 82,  310 => 80,  295 => 78,  290 => 77,  282 => 75,  280 => 74,  271 => 73,  267 => 71,  252 => 69,  247 => 68,  239 => 66,  237 => 65,  232 => 64,  230 => 63,  227 => 62,  212 => 60,  207 => 59,  199 => 57,  197 => 56,  192 => 55,  190 => 54,  185 => 52,  178 => 47,  176 => 46,  175 => 45,  174 => 44,  173 => 43,  172 => 42,  171 => 41,  147 => 36,  140 => 35,  121 => 33,  114 => 32,  87 => 30,  67 => 27,  60 => 26,  41 => 24,  34 => 23,  30 => 21,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Common/pagination.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Common/pagination.html.twig");
    }
}
