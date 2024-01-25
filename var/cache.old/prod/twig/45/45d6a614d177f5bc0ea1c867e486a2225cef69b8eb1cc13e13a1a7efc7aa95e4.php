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

/* @PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig */
class __TwigTemplate_cece47cdf5e484f810b7a7d82af349ac89eee44fc27ddede18699b7b9d3083a3 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'product_catalog_form_table_row' => [$this, 'block_product_catalog_form_table_row'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!--begin_tbody_list_product!--><tbody class=\"tbody_list_product";
        if ((array_key_exists("ETS_PMN_ENABLE_INSTANT_FILTER", $context) && (($context["ETS_PMN_ENABLE_INSTANT_FILTER"] ?? null) == 1))) {
            echo " search";
        }
        if (($context["activate_drag_and_drop"] ?? null)) {
            echo " sortable";
        }
        echo "\"
    
    last_sql=\"";
        // line 3
        echo twig_escape_filter($this->env, ($context["last_sql_query"] ?? null), "html_attr");
        echo "\"
>
";
        // line 5
        if (($this->getAttribute($this->getAttribute(($context["products"] ?? null), 0, [], "array"), "total", []) == false)) {
            // line 6
            echo "    <tr class=\"ets_no_product\">
        <td colspan=\"100%\" class=\"no-product\">
            ";
            // line 8
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("There is no result for this search. Update your filters to view other products.", [], "Admin.Catalog.Notification"), "html", null, true);
            echo "
        </td>
    </tr>
";
        } else {
            // line 12
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["products"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 13
                echo "        ";
                $this->displayBlock('product_catalog_form_table_row', $context, $blocks);
                // line 493
                echo "    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 495
        echo "</tbody><!--end_tbody_list_product!-->";
    }

    // line 13
    public function block_product_catalog_form_table_row($context, array $blocks = [])
    {
        // line 14
        echo "        ";
        if (array_key_exists("ets_pmg_product_fileds", $context)) {
            // line 15
            echo "            <tr data-uniturl=\"";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "unit_action_url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "unit_action_url", []), "#")) : ("#")), "html", null, true);
            echo "\" data-product-id=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\">
                <td class=\"checkbox-column form-group\">
                    <div class=\"ets_td\">
                        <div class=\"md-checkbox md-checkbox-inline\">
                            <label>
                                <input type=\"checkbox\" id=\"bulk_action_selected_products-";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\" name=\"bulk_action_selected_products[]\" value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\">
                                <i class=\"md-checkbox-control\"></i>
                            </label>
                        </div>
                        <input type=\"hidden\" name=\"id_product\" value=\"";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\" />
                        <input type=\"hidden\" name=\"id_tax_rules_group\" value=\"";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_tax_rules_group", []), "html", null, true);
            echo "\" id=\"id_tax_rules_group\" />
                    </div>
                </td>
                ";
            // line 28
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["ets_pmg_product_fileds"] ?? null));
            foreach ($context['_seq'] as $context["key"] => $context["field"]) {
                // line 29
                echo "                    ";
                if ($this->getAttribute($context["field"], "input", [], "any", true, true)) {
                    // line 30
                    echo "                        ";
                    $context["field_input"] = $this->getAttribute($context["field"], "input", []);
                    // line 31
                    echo "                    ";
                } else {
                    // line 32
                    echo "                        ";
                    $context["field_input"] = false;
                    // line 33
                    echo "                    ";
                }
                // line 34
                echo "                     <td class=\"column ";
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                if (($context["key"] == "sav_quantity")) {
                    echo " product-sav-quantity text-center";
                }
                echo "\" ";
                if (($context["key"] == "sav_quantity")) {
                    echo " data-product-quantity-value=\"";
                    echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "sav_quantity", []), "")) : ("")), "html", null, true);
                    echo "\"";
                }
                echo ">
                        <div class=\"ets_td\">
                        ";
                // line 36
                if ((($context["key"] == "image") || ($context["key"] == "name"))) {
                    // line 37
                    echo "                            <div class=\"";
                    if (($context["key"] == "name")) {
                        echo "span_change_product content s1";
                    } else {
                        echo "popup_change_product";
                    }
                    echo "\">
                                <a href=\"";
                    // line 38
                    echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
                    echo "#tab-step1\">";
                    echo $this->getAttribute(($context["product"] ?? null), $context["key"], [], "array");
                    echo "</a>
                            ";
                    // line 39
                    if (($context["key"] == "image")) {
                        // line 40
                        echo "                                <a class=\"btn tooltip-link product-edit-popup\" href=\"#\" title=\"";
                        echo twig_escape_filter($this->env, ($context["ets_pmn_edit_text"] ?? null), "html", null, true);
                        echo "\" onclick=\"etsGetFormPopupProduct(\$(this),'image');return false;\">
                                    <i class=\"ets_svg_icon\">
                                        <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z\"/></svg>
                                    </i>
                                </a>
                            ";
                    }
                    // line 46
                    echo "                            </div>
                        ";
                } elseif ((((                // line 47
$context["key"] == "price") || ($context["key"] == "price_final")) || ($context["key"] == "wholesale_price"))) {
                    // line 48
                    echo "                            <div class=\"span_change_product content s2\">
                                <a href=\"";
                    // line 49
                    echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
                    echo "#tab-step2\">";
                    echo $this->getAttribute(($context["product"] ?? null), $context["key"], [], "array");
                    echo "</a>
                            </div>
                        ";
                } elseif ((                // line 51
$context["key"] == "associated_file")) {
                    // line 52
                    echo "                            ";
                    if ($this->getAttribute(($context["product"] ?? null), "is_virtual", [])) {
                        // line 53
                        echo "                                <div class=\"popup_change_product\">
                                    <div class=\"content\">
                                        ";
                        // line 55
                        if (($this->getAttribute(($context["product"] ?? null), $context["key"], [], "array") != "")) {
                            // line 56
                            echo "                                            <a href=\"";
                            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "link_associated_file", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "link_associated_file", []), "")) : ("")), "html", null, true);
                            echo "\">";
                            echo $this->getAttribute(($context["product"] ?? null), $context["key"], [], "array");
                            echo "</a>
                                        ";
                        } else {
                            // line 58
                            echo "                                        --
                                        ";
                        }
                        // line 60
                        echo "                                    </div>
                                    <a class=\"btn tooltip-link product-edit-popup\" href=\"#\" title=\"";
                        // line 61
                        echo twig_escape_filter($this->env, ($context["ets_pmn_edit_text"] ?? null), "html", null, true);
                        echo "\" onclick=\"etsGetFormPopupProduct(\$(this),'associated_file');return false;\">
                                        <i class=\"ets_svg_icon\">
                                            <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z\"/></svg>
                                        </i>
                                    </a>
                                </div>
                            ";
                    } else {
                        // line 68
                        echo "                                --
                            ";
                    }
                    // line 70
                    echo "                        ";
                } elseif (($context["key"] == "sav_quantity")) {
                    // line 71
                    echo "                            ";
                    if ($this->env->getExtension('PrestaShopBundle\Twig\LayoutExtension')->getConfiguration("PS_STOCK_MANAGEMENT")) {
                        // line 72
                        echo "                                <div class=\"";
                        if (($this->getAttribute(($context["product"] ?? null), "has_attribute", []) == false)) {
                            echo "span_change_product content s3";
                        } else {
                            echo "popup_change_product";
                        }
                        echo "\">
                                    ";
                        // line 73
                        if (($this->getAttribute(($context["product"] ?? null), "has_attribute", []) == true)) {
                            // line 74
                            echo "                                        <div class=\"content\">
                                        <div class=\"content_info\">
                                    ";
                        }
                        // line 77
                        echo "                                            <a href=\"";
                        echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
                        echo "#tab-step3\">
                                                ";
                        // line 78
                        if (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true) && ($this->getAttribute(($context["product"] ?? null), "sav_quantity", []) > 0))) {
                            // line 79
                            echo "                                                    ";
                            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "sav_quantity", []), "html", null, true);
                            echo "
                                                ";
                        } else {
                            // line 81
                            echo "                                                    ";
                            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "sav_quantity", []), $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))) : ($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))), "html", null, true);
                            echo "
                                                ";
                        }
                        // line 83
                        echo "                                            </a>
                                    ";
                        // line 84
                        if (($this->getAttribute(($context["product"] ?? null), "has_attribute", []) == true)) {
                            // line 85
                            echo "                                        </div>
                                        </div>
                                        <a class=\"btn tooltip-link product-edit-popup\" href=\"#\" title=\"";
                            // line 87
                            echo twig_escape_filter($this->env, ($context["ets_pmn_edit_text"] ?? null), "html", null, true);
                            echo "\" onclick=\"etsGetFormPopupProduct(\$(this),'sav_quantity');return false;\">

                                            <i class=\"ets_svg_icon\">
                                                <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z\"/></svg>
                                            </i>
                                        </a>
                                        <a href=\"#\" class=\"btn ets_pm_viewmore\" data-more=\"";
                            // line 93
                            echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                            echo "\" data-less=\"";
                            echo twig_escape_filter($this->env, ($context["viewless"] ?? null), "html", null, true);
                            echo "\">
                                            ";
                            // line 94
                            echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                            echo "
                                        </a>
                                    ";
                        }
                        // line 97
                        echo "                                </div>
                            ";
                    } else {
                        // line 99
                        echo "                            ";
                    }
                    // line 100
                    echo "                        ";
                } elseif (((($context["key"] == "on_sale") || ($context["key"] == "is_must_have")) || ($context["key"] == "active"))) {
                    // line 101
                    echo "                            ";
                    if (((($this->getAttribute(($context["product"] ?? null), $context["key"], [], "array", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), $context["key"], [], "array"), 0)) : (0)) == 0)) {
                        // line 102
                        echo "                                <a href=\"#\" onclick=\"unitProductActionEts(this, '";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "');return false;\">
                                    <i class=\"material-icons action-disabled\">clear</i>
                                </a>
                            ";
                    } else {
                        // line 106
                        echo "                                <a href=\"#\" onclick=\"unitProductActionEts(this, '";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "'); return false;\">
                                    <i class=\"material-icons action-enabled \">check</i>
                                </a>
                            ";
                    }
                    // line 110
                    echo "                        ";
                } else {
                    // line 111
                    echo "                            ";
                    if (($this->getAttribute($context["field"], "input", [], "any", true, true) && $this->getAttribute($context["field"], "input", []))) {
                        // line 112
                        echo "                                <div class=\"span_change_product s4\" data-name=\"";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "_";
                        echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                        echo "\" data-lang=\"";
                        echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                        echo "\">
                            ";
                    } else {
                        // line 114
                        echo "                                <div class=\"popup_change_product s5\">
                            ";
                    }
                    // line 116
                    echo "                                <div class=\"content\">
                                    <div class=\"content_info\">
                                        ";
                    // line 118
                    if (($context["key"] == "extra_product_tab_15")) {
                        // line 119
                        echo "                                            <div class=\"ets_content_file\">
                                                ";
                        // line 120
                        if (( !$this->getAttribute(($context["product"] ?? null), $context["key"], [], "array") || ($this->getAttribute(($context["product"] ?? null), $context["key"], [], "array") == "0000-00-00"))) {
                            // line 121
                            echo "                                                    --
                                                ";
                        } else {
                            // line 123
                            echo "                                                    ";
                            echo $this->getAttribute(($context["product"] ?? null), $context["key"], [], "array");
                            echo "
                                                ";
                        }
                        // line 125
                        echo "                                            </div>
                                        ";
                    } else {
                        // line 126
                        echo "    
                                            ";
                        // line 127
                        if (( !$this->getAttribute(($context["product"] ?? null), $context["key"], [], "array") || ($this->getAttribute(($context["product"] ?? null), $context["key"], [], "array") == "0000-00-00"))) {
                            // line 128
                            echo "                                                --
                                            ";
                        } else {
                            // line 130
                            echo "                                                ";
                            echo $this->getAttribute(($context["product"] ?? null), $context["key"], [], "array");
                            echo "
                                            ";
                        }
                        // line 132
                        echo "                                        ";
                    }
                    echo "    
                                    </div>
                                </div>
                            ";
                    // line 135
                    if (($this->getAttribute($context["field"], "input", [], "any", true, true) && $this->getAttribute($context["field"], "input", []))) {
                        // line 136
                        echo "                                <a href=\"#\" class=\"btn ets_pm_viewmore\" data-more=\"";
                        echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                        echo "\" data-less=\"";
                        echo twig_escape_filter($this->env, ($context["viewless"] ?? null), "html", null, true);
                        echo "\">
                                        ";
                        // line 137
                        echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                        echo "
                                    </a>
                                </div>
                            ";
                    } else {
                        // line 141
                        echo "                                    ";
                        if ((((($context["key"] != "id_product") && ($context["key"] != "seo_score")) && ($context["key"] != "readability_score")) && (($context["key"] != "combinations") || $this->getAttribute(($context["product"] ?? null), "add_combination", [])))) {
                            // line 142
                            echo "                                        <a class=\"btn tooltip-link product-edit-popup\" href=\"#\" title=\"";
                            echo twig_escape_filter($this->env, ($context["ets_pmn_edit_text"] ?? null), "html", null, true);
                            echo "\" onclick=\"etsGetFormPopupProduct(\$(this),'";
                            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                            echo "');return false;\">
                                            <i class=\"ets_svg_icon\">
                                                <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z\"/></svg>
                                            </i>
                                        </a>
                                    ";
                        }
                        // line 148
                        echo "                                    <a href=\"#\" class=\"btn ets_pm_viewmore\" data-more=\"";
                        echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                        echo "\" data-less=\"";
                        echo twig_escape_filter($this->env, ($context["viewless"] ?? null), "html", null, true);
                        echo "\">
                                        ";
                        // line 149
                        echo twig_escape_filter($this->env, ($context["viewmore"] ?? null), "html", null, true);
                        echo "
                                    </a>
                                </div>
                            ";
                    }
                    // line 153
                    echo "                        ";
                }
                // line 154
                echo "                        ";
                if ((($context["field_input"] ?? null) && (($context["key"] != "sav_quantity") || ($this->getAttribute(($context["product"] ?? null), "has_attribute", []) == false)))) {
                    // line 155
                    echo "                            <div class=\"wapper-change-product ";
                    echo twig_escape_filter($this->env, $this->getAttribute(($context["field_input"] ?? null), "type", []), "html", null, true);
                    echo " ";
                    if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                        echo " popup";
                    }
                    echo "\" style=\"display: none;\">
                                ";
                    // line 156
                    if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                        // line 157
                        echo "                                    <div class=\"popup_content table\">
                                        <div class=\"popup_content_tablecell\">
                                            <div class=\"popup_content_wrap\">
                                            <span class=\"close_popup\" title=\"Close\">+</span>
                                            <div id=\"fieldset_0\" class=\"panel\">
                                                <div class=\"panel-heading\">";
                        // line 162
                        echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                        echo ": ";
                        echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "name", []), "html", null, true);
                        echo "</div>
                                            <div class=\"form-wrapper\">
                                ";
                    }
                    // line 165
                    echo "                                ";
                    if ((($this->getAttribute(($context["field_input"] ?? null), "type", []) == "select") && $this->getAttribute(($context["field_input"] ?? null), "values", []))) {
                        // line 166
                        echo "                                    <select name=\"";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\" id=\"";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\" class=\"form-control\">
                                        ";
                        // line 167
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["field_input"] ?? null), "values", []), "query", []));
                        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                            // line 168
                            echo "                                            <option value=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], $this->getAttribute($this->getAttribute(($context["field_input"] ?? null), "values", []), "id", []), [], "array"), "html", null, true);
                            echo "\" ";
                            if (($this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array") == $this->getAttribute($context["option"], $this->getAttribute($this->getAttribute(($context["field_input"] ?? null), "values", []), "id", []), [], "array"))) {
                                echo " selected=\"selected\"";
                            }
                            echo ">";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], $this->getAttribute($this->getAttribute(($context["field_input"] ?? null), "values", []), "name", []), [], "array"), "html", null, true);
                            echo "</option>
                                        ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 170
                        echo "                                    </select>
                                    ";
                        // line 171
                        if (($this->getAttribute(($context["field_input"] ?? null), "desc", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "desc", []))) {
                            // line 172
                            echo "                                        <span class=\"help-block\">
                                            ";
                            // line 173
                            echo $this->getAttribute(($context["field_input"] ?? null), "desc", []);
                            echo "
                                        </span>
                                    ";
                        }
                        // line 176
                        echo "                                ";
                    } elseif (((($this->getAttribute(($context["field_input"] ?? null), "type", []) == "text") || ($this->getAttribute(($context["field_input"] ?? null), "type", []) == "date")) || ($this->getAttribute(($context["field_input"] ?? null), "type", []) == "tags"))) {
                        // line 177
                        echo "                                    ";
                        if (($this->getAttribute(($context["field_input"] ?? null), "lang", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "lang", []))) {
                            // line 178
                            echo "                                        ";
                            if ((($context["ets_pmn_count_languages"] ?? null) > 1)) {
                                // line 179
                                echo "                                            <div class=\"form-group row\">
                                                <div class=\"translatable-field lang-";
                                // line 180
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\">
                                                    <div class=\"col-lg-11\">
                                                        <input class=\"form-control";
                                // line 182
                                if (($this->getAttribute(($context["field_input"] ?? null), "type", []) == "tags")) {
                                    echo " tagify";
                                }
                                if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                    echo " is_lang_default";
                                }
                                echo " \" id=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" name=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" value=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                                echo "\"  type=\"text\"  title=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                                echo "\" placeholder=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                                echo "\" />
                                                    </div>
                                                    <div class=\"col-lg-1\">
                                                        <div class=\"toggle_form\">
                                                            <button class=\"btn btn-default dropdown-toggle disabled\" type=\"button\" tabindex=\"-1\" data-toggle=\"dropdown\">
                                                                ";
                                // line 187
                                echo twig_escape_filter($this->env, ($context["ets_pmn_iso_lang"] ?? null), "html", null, true);
                                echo "
                                                                <i class=\"icon-caret-down\"></i>
                                                            </button>
                                                            <ul class=\"dropdown-menu\">
                                                                ";
                                // line 191
                                $context['_parent'] = $context;
                                $context['_seq'] = twig_ensure_traversable(($context["ets_pmn_languages"] ?? null));
                                foreach ($context['_seq'] as $context["_key"] => $context["lang"]) {
                                    // line 192
                                    echo "                                                                    <li>
                                                                        <a class=\"hideOtherLanguageInline\" href=\"#\" tabindex=\"-1\" data-id-lang=\"";
                                    // line 193
                                    echo twig_escape_filter($this->env, $this->getAttribute($context["lang"], "id_lang", []), "html", null, true);
                                    echo "\">";
                                    echo twig_escape_filter($this->env, $this->getAttribute($context["lang"], "name", []), "html", null, true);
                                    echo "</a>
                                                                    </li>
                                                                ";
                                }
                                $_parent = $context['_parent'];
                                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['lang'], $context['_parent'], $context['loop']);
                                $context = array_intersect_key($context, $_parent) + $_parent;
                                // line 196
                                echo "                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>
                                        ";
                            } else {
                                // line 202
                                echo "                                            <input class=\"form-control";
                                if (($this->getAttribute(($context["field_input"] ?? null), "type", []) == "tags")) {
                                    echo " tagify";
                                }
                                if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                    echo " is_lang_default";
                                }
                                echo "\" name=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" value=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                                echo "\"  type=\"text\"  title=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                                echo "\" id=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" placeholder=\"";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                                echo "\"/>
                                        ";
                            }
                            // line 204
                            echo "                                    ";
                        } else {
                            echo " 
                                        ";
                            // line 205
                            if (($this->getAttribute(($context["field_input"] ?? null), "suffix", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "suffix", []))) {
                                // line 206
                                echo "                                            <div class=\"input-group\">
                                        ";
                            }
                            // line 208
                            echo "                                            <input class=\"form-control ";
                            if (($this->getAttribute(($context["field_input"] ?? null), "type", []) == "date")) {
                                echo " datepicker";
                            }
                            if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                echo " is_lang_default";
                            }
                            echo "\" name=\"";
                            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                            echo "\" value=\"";
                            if (($this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array") != "0000-00-00")) {
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                            }
                            echo "\"  type=\"text\"  title=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                            echo "\" autocomplete=\"off\" placeholder=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                            echo "\" id=\"";
                            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                            echo "_";
                            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                            echo "_";
                            echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                            echo "\"/>
                                        ";
                            // line 209
                            if (($this->getAttribute(($context["field_input"] ?? null), "suffix", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "suffix", []))) {
                                // line 210
                                echo "                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    ";
                                // line 212
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["field_input"] ?? null), "suffix", []), "html", null, true);
                                echo "
                                                </span>
                                            </div>
                                        ";
                            }
                            // line 216
                            echo "                                        ";
                            if (($this->getAttribute(($context["field_input"] ?? null), "suffix", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "suffix", []))) {
                                // line 217
                                echo "                                            </div>
                                        ";
                            }
                            // line 218
                            echo "  
                                    ";
                        }
                        // line 220
                        echo "                                ";
                    } elseif (($this->getAttribute(($context["field_input"] ?? null), "type", []) == "textarea")) {
                        // line 221
                        echo "                                    ";
                        if (($this->getAttribute(($context["field_input"] ?? null), "lang", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "lang", []))) {
                            // line 222
                            echo "                                        ";
                            if ((($context["ets_pmn_count_languages"] ?? null) > 1)) {
                                // line 223
                                echo "                                            <div class=\"form-group row\">
                                                <div class=\"translatable-field lang-";
                                // line 224
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\">
                                                    <div class=\"col-lg-11\">
                                                        <textarea  class=\"form-control";
                                // line 226
                                if (($this->getAttribute(($context["field_input"] ?? null), "autoload_rte", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "autoload_rte", []))) {
                                    echo " ets_pmn_autoload_rte";
                                }
                                if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                    echo " is_lang_default";
                                }
                                echo " \" id=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" name=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\">";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                                echo "</textarea>
                                                    </div>
                                                    <div class=\"col-lg-1\">
                                                        <div class=\"toggle_form\">
                                                            <button class=\"btn btn-default dropdown-toggle disabled\" type=\"button\" tabindex=\"-1\" data-toggle=\"dropdown\">
                                                                ";
                                // line 231
                                echo twig_escape_filter($this->env, ($context["ets_pmn_iso_lang"] ?? null), "html", null, true);
                                echo "
                                                                <i class=\"icon-caret-down\"></i>
                                                            </button>
                                                            <ul class=\"dropdown-menu\">
                                                                ";
                                // line 235
                                $context['_parent'] = $context;
                                $context['_seq'] = twig_ensure_traversable(($context["ets_pmn_languages"] ?? null));
                                foreach ($context['_seq'] as $context["_key"] => $context["lang"]) {
                                    // line 236
                                    echo "                                                                    <li>
                                                                        <a class=\"hideOtherLanguageInline\" href=\"#\" tabindex=\"-1\" data-id-lang=\"";
                                    // line 237
                                    echo twig_escape_filter($this->env, $this->getAttribute($context["lang"], "id_lang", []), "html", null, true);
                                    echo "\">";
                                    echo twig_escape_filter($this->env, $this->getAttribute($context["lang"], "name", []), "html", null, true);
                                    echo "</a>
                                                                    </li>
                                                                ";
                                }
                                $_parent = $context['_parent'];
                                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['lang'], $context['_parent'], $context['loop']);
                                $context = array_intersect_key($context, $_parent) + $_parent;
                                // line 240
                                echo "                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>
                                        ";
                            } else {
                                // line 246
                                echo "                                            <textarea  class=\"form-control";
                                if (($this->getAttribute(($context["field_input"] ?? null), "autoload_rte", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "autoload_rte", []))) {
                                    echo " ets_pmn_autoload_rte";
                                }
                                if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                    echo " is_lang_default";
                                }
                                echo "\" name=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\" id=\"";
                                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                                echo "_";
                                echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                                echo "\">";
                                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                                echo "</textarea>
                                        ";
                            }
                            // line 248
                            echo "                                    ";
                        } else {
                            echo " 
                                        <textarea  class=\"form-control";
                            // line 249
                            if (($this->getAttribute(($context["field_input"] ?? null), "autoload_rte", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "autoload_rte", []))) {
                                echo " ets_pmn_autoload_rte";
                            }
                            echo " ";
                            if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                                echo " is_lang_default";
                            }
                            echo "\" name=\"";
                            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                            echo "\" id=\"";
                            echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                            echo "_";
                            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                            echo "_";
                            echo twig_escape_filter($this->env, ($context["ets_pmn_id_lang"] ?? null), "html", null, true);
                            echo "\">";
                            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), $this->getAttribute(($context["field_input"] ?? null), "name", []), [], "array"), "html", null, true);
                            echo "</textarea>
                                    ";
                        }
                        // line 251
                        echo "                                ";
                    }
                    // line 252
                    echo "                                 ";
                    if (($this->getAttribute(($context["field_input"] ?? null), "popup", [], "any", true, true) && $this->getAttribute(($context["field_input"] ?? null), "popup", []))) {
                        // line 253
                        echo "                                     </div>
                                        <div class=\"panel-footer\">
                                            <button class=\"btn btn-default pull-left\" type=\"button\" name=\"btnCancel\">
                                                <i class=\"process-icon-cancel svg_process-icon\">
                                                    <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z\"/></svg>
                                                </i> ";
                        // line 258
                        echo twig_escape_filter($this->env, ($context["ets_pmn_cancel_text"] ?? null), "html", null, true);
                        echo "
                                            </button>
                                            <button id=\"module_form_submit_btn\" class=\"btn btn-default pull-right\" type=\"submit\" value=\"1\" name=\"submitProductChangeInLine2\">
                                                <i class=\"process-icon-save\">
                                                    <svg viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\">
                                                        <path d=\"M512 1536h768v-384h-768v384zm896 0h128v-896q0-14-10-38.5t-20-34.5l-281-281q-10-10-34-20t-39-10v416q0 40-28 68t-68 28h-576q-40 0-68-28t-28-68v-416h-128v1280h128v-416q0-40 28-68t68-28h832q40 0 68 28t28 68v416zm-384-928v-320q0-13-9.5-22.5t-22.5-9.5h-192q-13 0-22.5 9.5t-9.5 22.5v320q0 13 9.5 22.5t22.5 9.5h192q13 0 22.5-9.5t9.5-22.5zm640 32v928q0 40-28 68t-68 28h-1344q-40 0-68-28t-28-68v-1344q0-40 28-68t68-28h928q40 0 88 20t76 48l280 280q28 28 48 76t20 88z\">
                                                    </svg>
                                                </i>
                                                ";
                        // line 266
                        echo twig_escape_filter($this->env, ($context["ets_pmn_save_text"] ?? null), "html", null, true);
                        echo "
                                            </button>
                                        </div>
                                     </div>
                                    </div>
                                    </div>
                                    </div>
                                ";
                    }
                    // line 274
                    echo "                                ";
                    if (($context["key"] == "low_stock_threshold")) {
                        // line 275
                        echo "                                    <label class=\"required\">
                                        <input id=\"form_step3_low_stock_alert\" name=\"low_stock_alert\" value=\"1\" type=\"checkbox\"";
                        // line 276
                        if ($this->getAttribute(($context["product"] ?? null), "low_stock_alert", [])) {
                            echo " checked=\"checked\"";
                        }
                        echo "/>
                                        ";
                        // line 277
                        echo twig_escape_filter($this->env, ($context["low_stock_alert_text"] ?? null), "html", null, true);
                        echo "
                                    </label>
                                ";
                    } elseif ((                    // line 279
$context["key"] == "visibility")) {
                        // line 280
                        echo "                                    <label>
                                        <input id=\"form_step6_display_options_available_for_order\" name=\"available_for_order\" value=\"1\"";
                        // line 281
                        if ($this->getAttribute(($context["product"] ?? null), "available_for_order", [])) {
                            echo " checked=\"checked\"";
                        }
                        echo " type=\"checkbox\" />
                                        ";
                        // line 282
                        echo twig_escape_filter($this->env, ($context["Available_for_order_text"] ?? null), "html", null, true);
                        echo "
                                    </label>
                                    <label ";
                        // line 284
                        if ($this->getAttribute(($context["product"] ?? null), "available_for_order", [])) {
                            echo " style=\"display:none;\"";
                        }
                        echo ">
                                        <input id=\"form_step6_display_options_show_price\" name=\"show_price\" value=\"1\"";
                        // line 285
                        if ($this->getAttribute(($context["product"] ?? null), "show_price", [])) {
                            echo " checked=\"checked\"";
                        }
                        echo " type=\"checkbox\" />
                                        ";
                        // line 286
                        echo twig_escape_filter($this->env, ($context["Show_price_text"] ?? null), "html", null, true);
                        echo "
                                    </label>
                                    <label>
                                        <input id=\"form_step6_display_options_online_only\" name=\"online_only\" value=\"1\"";
                        // line 289
                        if ($this->getAttribute(($context["product"] ?? null), "online_only", [])) {
                            echo " checked=\"checked\"";
                        }
                        echo " type=\"checkbox\" />
                                        ";
                        // line 290
                        echo twig_escape_filter($this->env, ($context["Web_only_text"] ?? null), "html", null, true);
                        echo "
                                    </label>
                                ";
                    } elseif ((                    // line 292
$context["key"] == "condition")) {
                        // line 293
                        echo "                                    <label>
                                        <input id=\"form_step6_show_condition\" name=\"show_condition\" value=\"1\"";
                        // line 294
                        if ($this->getAttribute(($context["product"] ?? null), "show_condition", [])) {
                            echo " checked=\"checked\"";
                        }
                        echo " type=\"checkbox\" />
                                        ";
                        // line 295
                        echo twig_escape_filter($this->env, ($context["Display_condition_on_product_page_text"] ?? null), "html", null, true);
                        echo "
                                    </label>
                                ";
                    }
                    // line 298
                    echo "                            </div>
                            
                        ";
                }
                // line 301
                echo "                        </div>
                     </td>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['field'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 304
            echo "                ";
            if ($this->getAttribute(($context["product"] ?? null), "position", [], "any", true, true)) {
                // line 305
                echo "                    <td ";
                if (($context["activate_drag_and_drop"] ?? null)) {
                    echo "class=\"placeholder\"";
                }
                echo " style=\"cursor: pointer; cursor: hand;\">
                        <div class=\"ets_td\">
                            ";
                // line 307
                if (($context["activate_drag_and_drop"] ?? null)) {
                    // line 308
                    echo "                                <big><big><svg style=\"width:12px;height:12px;\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 320 512\"><path d=\"M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z\"/></svg></big></big>
                            ";
                }
                // line 310
                echo "                            <span class=\"position\">";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "position", []), "html", null, true);
                echo "</span>
                            <input type=\"hidden\" name=\"mass_edit_action_sorted_products[]\" value=\"";
                // line 311
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                echo "\" />
                            <input type=\"hidden\" name=\"mass_edit_action_sorted_positions[]\" value=\"";
                // line 312
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "position", []), "html", null, true);
                echo "\" />
                        </div>
                    </td>
                ";
            }
            // line 316
            echo "                <td class=\"text-right ets_action_col\">
                    <div class=\"ets_td\">
                        <div class=\"btn-group-action\">
                            ";
            // line 319
            $context["buttons_action"] = [0 => ["href" => (($this->getAttribute(            // line 321
($context["product"] ?? null), "preview_url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "preview_url", []), "#")) : ("#")), "target" => "_blank", "icon" => "remove_red_eye", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Preview", [], "Admin.Actions")]];
            // line 327
            echo "                            ";
            $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["href" => (($this->getAttribute(            // line 329
($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "#")) : ("#")), "icon" => "mode_edit", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Edit", [], "Modules.Etsproductmanager.Admin")]]);
            // line 334
            echo "                            ";
            $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["onclick" => "unitProductAction(this, 'duplicate');", "icon" => "content_copy", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Duplicate", [], "Admin.Actions")]]);
            // line 341
            echo "            
                            ";
            // line 342
            $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["onclick" => "unitProductAction(this, 'delete');", "icon" => "delete", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Delete", [], "Admin.Actions")]]);
            // line 349
            echo "                            
                            ";
            // line 350
            $this->loadTemplate("@Product/CatalogPage/Forms/form_edit_dropdown.html.twig", "@PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig", 350)->display(twig_array_merge($context, ["button_id" => (("product_list_id_" . $this->getAttribute(            // line 351
($context["product"] ?? null), "id_product", [])) . "_menu"), "default_item" => ["href" => (($this->getAttribute(            // line 353
($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "#")) : ("#")), "icon" => "mode_edit"], "right" => true, "items" =>             // line 357
($context["buttons_action"] ?? null)]));
            // line 359
            echo "                        </div>
                    </div>
                </td>
            </tr>
        ";
        } else {
            // line 364
            echo "            <tr data-uniturl=\"";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "unit_action_url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "unit_action_url", []), "#")) : ("#")), "html", null, true);
            echo "\" data-product-id=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\">
                <td class=\"checkbox-column form-group\">
                    <div class=\"md-checkbox md-checkbox-inline\">
                        <label>
                            <input type=\"checkbox\" id=\"bulk_action_selected_products-";
            // line 368
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\"
                                   name=\"bulk_action_selected_products[]\" value=\"";
            // line 369
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\">
                            <i class=\"md-checkbox-control\"></i>
                        </label>
                    </div>
                </td>
                <td>
                    <label class=\"form-check-label\" for=\"bulk_action_selected_products-";
            // line 375
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "\">
                        ";
            // line 376
            echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
            echo "
                    </label>
                </td>
                <td>
                    <a href=\"";
            // line 380
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
            echo "#tab-step1\">";
            echo $this->getAttribute(($context["product"] ?? null), "image", []);
            echo "</a>
                </td>
                <td>
                    <a href=\"";
            // line 383
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
            echo "#tab-step1\">";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "name", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "name", []), $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))) : ($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))), "html", null, true);
            echo "</a>
                </td>
                <td>
                    ";
            // line 386
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "reference", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "reference", []), "")) : ("")), "html", null, true);
            echo "
                </td>
                <td>
                    ";
            // line 389
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "name_category", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "name_category", []), "")) : ("")), "html", null, true);
            echo "
                </td>
                <td class=\"text-center\">
                    <a href=\"";
            // line 392
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
            echo "#tab-step2\">";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "price", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "price", []), $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))) : ($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))), "html", null, true);
            echo "</a>
                </td>
    
                ";
            // line 395
            if ($this->env->getExtension('PrestaShopBundle\Twig\LayoutExtension')->getConfiguration("PS_STOCK_MANAGEMENT")) {
                // line 396
                echo "                    <td class=\"product-sav-quantity text-center\"
                        data-product-quantity-value=\"";
                // line 397
                echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "sav_quantity", []), "")) : ("")), "html", null, true);
                echo "\">
                        <a href=\"";
                // line 398
                echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "")) : ("")), "html", null, true);
                echo "#tab-step3\">
                            ";
                // line 399
                if (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true) && ($this->getAttribute(($context["product"] ?? null), "sav_quantity", []) > 0))) {
                    // line 400
                    echo "                                ";
                    echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "sav_quantity", []), "html", null, true);
                    echo "
                            ";
                } else {
                    // line 402
                    echo "                                ";
                    echo twig_escape_filter($this->env, (($this->getAttribute(($context["product"] ?? null), "sav_quantity", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "sav_quantity", []), $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))) : ($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("N/A", [], "Admin.Global"))), "html", null, true);
                    echo "
                            ";
                }
                // line 404
                echo "                        </a>
                    </td>
                ";
            } else {
                // line 407
                echo "                    <td></td>
                ";
            }
            // line 409
            echo "                <td class=\"text-center\">
                    ";
            // line 410
            if (((($this->getAttribute(($context["product"] ?? null), "active", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "active", []), 0)) : (0)) == 0)) {
                // line 411
                echo "                        <a href=\"#\" onclick=\"unitProductAction(this, 'activate'); return false;\">
                            <i class=\"material-icons action-disabled\">clear</i>
                        </a>
                    ";
            } else {
                // line 415
                echo "                        <a href=\"#\" onclick=\"unitProductAction(this, 'deactivate'); return false;\">
                            <i class=\"material-icons action-enabled \">check</i>
                        </a>
                    ";
            }
            // line 419
            echo "                </td>
                ";
            // line 420
            if ($this->getAttribute(($context["product"] ?? null), "position", [], "any", true, true)) {
                // line 421
                echo "                    <td ";
                if (($context["activate_drag_and_drop"] ?? null)) {
                    echo "class=\"placeholder\"";
                }
                // line 422
                echo "                        style=\"cursor: pointer; cursor: hand;\">
                        ";
                // line 423
                if (($context["activate_drag_and_drop"] ?? null)) {
                    // line 424
                    echo "                            <big><big><svg style=\"width:12px;height:12px;\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 320 512\"><path d=\"M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z\"/></svg></big></big>
                        ";
                }
                // line 426
                echo "                        <span class=\"position\">";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "position", []), "html", null, true);
                echo "</span>
                        <input type=\"hidden\" name=\"mass_edit_action_sorted_products[]\" value=\"";
                // line 427
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "id_product", []), "html", null, true);
                echo "\"/>
                        <input type=\"hidden\" name=\"mass_edit_action_sorted_positions[]\" value=\"";
                // line 428
                echo twig_escape_filter($this->env, $this->getAttribute(($context["product"] ?? null), "position", []), "html", null, true);
                echo "\"/>
                    </td>
                ";
            }
            // line 431
            echo "                ";
            if ((array_key_exists("ets_seo_seo_enabled", $context) && ($context["ets_seo_seo_enabled"] ?? null))) {
                // line 432
                echo "                    <td class=\"text-center\">
                        ";
                // line 433
                if (($this->getAttribute(($context["product"] ?? null), "seo_score", [], "any", true, true) && (twig_length_filter($this->env, $this->getAttribute(($context["product"] ?? null), "seo_score", [])) > 0))) {
                    // line 434
                    echo "                            ";
                    echo $this->getAttribute(($context["product"] ?? null), "seo_score", []);
                    echo "
                        ";
                } else {
                    // line 436
                    echo "                            <span class=\"text-center\">--</span>
                        ";
                }
                // line 438
                echo "                    </td>
                ";
            }
            // line 440
            echo "    
                ";
            // line 441
            if ((array_key_exists("ets_seo_readability_enabled", $context) && ($context["ets_seo_readability_enabled"] ?? null))) {
                // line 442
                echo "                    <td class=\"text-center\">
                        ";
                // line 443
                if (($this->getAttribute(($context["product"] ?? null), "readability_score", [], "any", true, true) && (twig_length_filter($this->env, $this->getAttribute(($context["product"] ?? null), "readability_score", [])) > 0))) {
                    // line 444
                    echo "                            ";
                    echo $this->getAttribute(($context["product"] ?? null), "readability_score", []);
                    echo "
                        ";
                } else {
                    // line 446
                    echo "                            <span class=\"text-center\">--</span>
                        ";
                }
                // line 448
                echo "                    </td>
                ";
            }
            // line 450
            echo "    
                <td class=\"text-right ets_action_col\">
                    <div class=\"btn-group-action\">
    
                        ";
            // line 454
            $context["buttons_action"] = [0 => ["href" => (($this->getAttribute(            // line 456
($context["product"] ?? null), "preview_url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "preview_url", []), "#")) : ("#")), "target" => "_blank", "icon" => "remove_red_eye", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Preview", [], "Admin.Actions")]];
            // line 462
            echo "    
                        ";
            // line 463
            $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["onclick" => "unitProductAction(this, 'duplicate');", "icon" => "content_copy", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Duplicate", [], "Admin.Actions")]]);
            // line 470
            echo "    
                        ";
            // line 471
            $context["buttons_action"] = twig_array_merge(($context["buttons_action"] ?? null), [0 => ["onclick" => "unitProductAction(this, 'delete');", "icon" => "delete", "label" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Delete", [], "Admin.Actions")]]);
            // line 478
            echo "    
                        ";
            // line 479
            $this->loadTemplate("@Product/CatalogPage/Forms/form_edit_dropdown.html.twig", "@PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig", 479)->display(twig_array_merge($context, ["button_id" => (("product_list_id_" . $this->getAttribute(            // line 480
($context["product"] ?? null), "id_product", [])) . "_menu"), "default_item" => ["href" => (($this->getAttribute(            // line 482
($context["product"] ?? null), "url", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["product"] ?? null), "url", []), "#")) : ("#")), "icon" => "mode_edit"], "right" => true, "items" =>             // line 486
($context["buttons_action"] ?? null)]));
            // line 488
            echo "                    </div>
                </td>
            </tr>
        ";
        }
        // line 492
        echo "        ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1263 => 492,  1257 => 488,  1255 => 486,  1254 => 482,  1253 => 480,  1252 => 479,  1249 => 478,  1247 => 471,  1244 => 470,  1242 => 463,  1239 => 462,  1237 => 456,  1236 => 454,  1230 => 450,  1226 => 448,  1222 => 446,  1216 => 444,  1214 => 443,  1211 => 442,  1209 => 441,  1206 => 440,  1202 => 438,  1198 => 436,  1192 => 434,  1190 => 433,  1187 => 432,  1184 => 431,  1178 => 428,  1174 => 427,  1169 => 426,  1165 => 424,  1163 => 423,  1160 => 422,  1155 => 421,  1153 => 420,  1150 => 419,  1144 => 415,  1138 => 411,  1136 => 410,  1133 => 409,  1129 => 407,  1124 => 404,  1118 => 402,  1112 => 400,  1110 => 399,  1106 => 398,  1102 => 397,  1099 => 396,  1097 => 395,  1089 => 392,  1083 => 389,  1077 => 386,  1069 => 383,  1061 => 380,  1054 => 376,  1050 => 375,  1041 => 369,  1037 => 368,  1027 => 364,  1020 => 359,  1018 => 357,  1017 => 353,  1016 => 351,  1015 => 350,  1012 => 349,  1010 => 342,  1007 => 341,  1004 => 334,  1002 => 329,  1000 => 327,  998 => 321,  997 => 319,  992 => 316,  985 => 312,  981 => 311,  976 => 310,  972 => 308,  970 => 307,  962 => 305,  959 => 304,  951 => 301,  946 => 298,  940 => 295,  934 => 294,  931 => 293,  929 => 292,  924 => 290,  918 => 289,  912 => 286,  906 => 285,  900 => 284,  895 => 282,  889 => 281,  886 => 280,  884 => 279,  879 => 277,  873 => 276,  870 => 275,  867 => 274,  856 => 266,  845 => 258,  838 => 253,  835 => 252,  832 => 251,  811 => 249,  806 => 248,  783 => 246,  775 => 240,  764 => 237,  761 => 236,  757 => 235,  750 => 231,  725 => 226,  720 => 224,  717 => 223,  714 => 222,  711 => 221,  708 => 220,  704 => 218,  700 => 217,  697 => 216,  690 => 212,  686 => 210,  684 => 209,  658 => 208,  654 => 206,  652 => 205,  647 => 204,  620 => 202,  612 => 196,  601 => 193,  598 => 192,  594 => 191,  587 => 187,  558 => 182,  553 => 180,  550 => 179,  547 => 178,  544 => 177,  541 => 176,  535 => 173,  532 => 172,  530 => 171,  527 => 170,  512 => 168,  508 => 167,  501 => 166,  498 => 165,  490 => 162,  483 => 157,  481 => 156,  472 => 155,  469 => 154,  466 => 153,  459 => 149,  452 => 148,  440 => 142,  437 => 141,  430 => 137,  423 => 136,  421 => 135,  414 => 132,  408 => 130,  404 => 128,  402 => 127,  399 => 126,  395 => 125,  389 => 123,  385 => 121,  383 => 120,  380 => 119,  378 => 118,  374 => 116,  370 => 114,  360 => 112,  357 => 111,  354 => 110,  346 => 106,  338 => 102,  335 => 101,  332 => 100,  329 => 99,  325 => 97,  319 => 94,  313 => 93,  304 => 87,  300 => 85,  298 => 84,  295 => 83,  289 => 81,  283 => 79,  281 => 78,  276 => 77,  271 => 74,  269 => 73,  260 => 72,  257 => 71,  254 => 70,  250 => 68,  240 => 61,  237 => 60,  233 => 58,  225 => 56,  223 => 55,  219 => 53,  216 => 52,  214 => 51,  207 => 49,  204 => 48,  202 => 47,  199 => 46,  189 => 40,  187 => 39,  181 => 38,  172 => 37,  170 => 36,  155 => 34,  152 => 33,  149 => 32,  146 => 31,  143 => 30,  140 => 29,  136 => 28,  130 => 25,  126 => 24,  117 => 20,  106 => 15,  103 => 14,  100 => 13,  96 => 495,  81 => 493,  78 => 13,  60 => 12,  53 => 8,  49 => 6,  47 => 5,  42 => 3,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Product/CatalogPage/Lists/list.html.twig");
    }
}
