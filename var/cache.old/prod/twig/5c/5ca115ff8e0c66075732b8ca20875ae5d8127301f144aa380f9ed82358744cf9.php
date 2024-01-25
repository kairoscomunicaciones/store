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

/* @Product/CatalogPage/Lists/products_table.html.twig */
class __TwigTemplate_7367b0d42a61b2bf72cbbf87148374c8d08c2d93c7834f0697b71da2bf995158 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'product_catalog_form_table_header' => [$this, 'block_product_catalog_form_table_header'],
            'product_catalog_form_table_filters' => [$this, 'block_product_catalog_form_table_filters'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 2
        return $this->loadTemplate((((twig_number_format_filter($this->env, twig_constant("_PS_VERSION_")) >= 8)) ? ("@!PrestaShop/Admin/Product/CatalogPage/Lists/products_table.html.twig") : ("PrestaShopBundle:Admin/Product/CatalogPage/Lists:products_table.html.twig")), "@Product/CatalogPage/Lists/products_table.html.twig", 2);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["ps"] = $this->loadTemplate("@PrestaShop/Admin/macros.html.twig", "@Product/CatalogPage/Lists/products_table.html.twig", 1)->unwrap();
        // line 2
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_product_catalog_form_table_header($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        if (array_key_exists("ets_pmg_product_fileds", $context)) {
            // line 5
            echo "    <tr class=\"column-headers";
            if (($context["ETS_PMN_FIXED_HEADER_PRODUCT"] ?? null)) {
                echo " fixed";
            }
            echo "\">
        <th class=\"stt\" scope=\"col\" style=\"width: 2rem\"></th>
        ";
            // line 7
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["ets_pmg_product_fileds"] ?? null));
            foreach ($context['_seq'] as $context["key"] => $context["field"]) {
                // line 8
                echo "            <th class=\"";
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "\">
                <div class=\"ets_td\">
                    ";
                // line 10
                if (( !$this->getAttribute($context["field"], "sort", [], "any", true, true) || $this->getAttribute($context["field"], "sort", []))) {
                    // line 11
                    echo "                        ";
                    echo $context["ps"]->getsortable_column_header($this->getAttribute($context["field"], "title", []), $context["key"], ($context["orderBy"] ?? null), ($context["sortOrder"] ?? null));
                    echo "
                    ";
                } else {
                    // line 13
                    echo "                        ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "title", []), "html", null, true);
                    echo "
                    ";
                }
                // line 15
                echo "                </div>
            </th>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['field'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 18
            echo "        ";
            if ((($context["has_category_filter"] ?? null) == true)) {
                // line 19
                echo "        <th scope=\"col\">
          <div class=\"ets_td\">";
                // line 20
                echo $context["ps"]->getsortable_column_header($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Position", [], "Admin.Global"), "position", ($context["orderBy"] ?? null), ($context["sortOrder"] ?? null));
                echo "</div>
        </th>
        ";
            }
            // line 23
            echo "        <th scope=\"col\" class=\"text-right ets_action_col\" style=\"width: 3rem; padding-right: 2rem\">
          <div class=\"ets_td\">";
            // line 24
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Actions", [], "Admin.Global"), "html", null, true);
            echo "</div>
        </th>
    </tr>
    ";
        } else {
            // line 28
            echo "        ";
            $this->displayParentBlock("product_catalog_form_table_header", $context, $blocks);
            echo "
    ";
        }
    }

    // line 31
    public function block_product_catalog_form_table_filters($context, array $blocks = [])
    {
        // line 32
        echo "    ";
        if (array_key_exists("ets_pmg_product_fileds", $context)) {
            // line 33
            echo "        ";
            $context["filters_disabled"] = ($context["activate_drag_and_drop"] ?? null);
            // line 34
            echo "        <tr class=\"column-filters";
            if (($context["ETS_PMN_FIXED_HEADER_PRODUCT"] ?? null)) {
                echo " fixed";
            }
            echo "\">
            <th class=\"stt\" scope=\"col\" style=\"width: 2rem\"></th>
            ";
            // line 36
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["ets_pmg_product_fileds"] ?? null));
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
            foreach ($context['_seq'] as $context["key"] => $context["field"]) {
                // line 37
                echo "                ";
                if (($context["key"] == "sav_quantity")) {
                    // line 38
                    echo "                    ";
                    if ($this->env->getExtension('PrestaShopBundle\Twig\LayoutExtension')->getConfiguration("PS_STOCK_MANAGEMENT")) {
                        // line 39
                        echo "                      <th class=\"text-center ";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\">
                        <div class=\"ets_td\">
                            ";
                        // line 41
                        $this->loadTemplate("@PrestaShop/Admin/Helpers/range_inputs.html.twig", "@Product/CatalogPage/Lists/products_table.html.twig", 41)->display(twig_array_merge($context, ["input_name" => "filter_column_sav_quantity", "min" => "-1000000", "max" => "1000000", "minLabel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Min", [], "Admin.Global"), "maxLabel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Max", [], "Admin.Global"), "value" =>                         // line 47
($context["filter_column_sav_quantity"] ?? null), "disabled" =>                         // line 48
($context["filters_disabled"] ?? null)]));
                        // line 50
                        echo "                        </div>
                      </th>
                    ";
                    } else {
                        // line 53
                        echo "                        <th></th>
                    ";
                    }
                    // line 55
                    echo "                ";
                } elseif (($context["key"] == "active")) {
                    // line 56
                    echo "                    <th id=\"product_filter_column_active\" class=\"text-center\">
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                              <select class=\"custom-select\"  name=\"filter_column_active\" ";
                    // line 59
                    if (($context["filters_disabled"] ?? null)) {
                        echo "disabled";
                    }
                    echo ">
                                <option value=\"\"></option>
                                <option value=\"1\" ";
                    // line 61
                    if ((array_key_exists("filter_column_active", $context) && (($context["filter_column_active"] ?? null) == "1"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">Active</option>
                                <option value=\"0\" ";
                    // line 62
                    if ((array_key_exists("filter_column_active", $context) && (($context["filter_column_active"] ?? null) == "0"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">Inactive</option>
                              </select>
                            </div>
                        </div>
                    </th>
                ";
                } elseif ((                // line 67
$context["key"] == "on_sale")) {
                    // line 68
                    echo "                    <th id=\"product_filter_column_on_sale\" class=\"text-center\">
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                              <select class=\"custom-select\"  name=\"filter_column_on_sale\" ";
                    // line 71
                    if (($context["filters_disabled"] ?? null)) {
                        echo "disabled";
                    }
                    echo ">
                                <option value=\"\"></option>
                                <option value=\"1\" ";
                    // line 73
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_on_sale", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_on_sale", [], "array") == "1"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">Yes</option>
                                <option value=\"0\" ";
                    // line 74
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_on_sale", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_on_sale", [], "array") == "0"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">No</option>
                              </select>
                            </div>
                        </div>
                    </th>
                ";
                } elseif ((                // line 79
$context["key"] == "is_must_have")) {
                    // line 80
                    echo "                    <th id=\"product_filter_column_is_must_have\" class=\"text-center\">
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                              <select class=\"custom-select\"  name=\"filter_column_is_must_have\" ";
                    // line 83
                    if (($context["filters_disabled"] ?? null)) {
                        echo "disabled";
                    }
                    echo ">
                                <option value=\"\"></option>
                                <option value=\"1\" ";
                    // line 85
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_is_must_have", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_is_must_have", [], "array") == "1"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">Yes</option>
                                <option value=\"0\" ";
                    // line 86
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_is_must_have", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_is_must_have", [], "array") == "0"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">No</option>
                              </select>
                            </div>
                        </div>
                    </th>
                ";
                } elseif ((                // line 91
$context["key"] == "seo_score")) {
                    // line 92
                    echo "                    <th>
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                              <select class=\"custom-select\"  name=\"filter_ets_seo_score\">
                                <option value=\"\">";
                    // line 96
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("All SEO Scores", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"bad\" ";
                    // line 97
                    if ((array_key_exists("filter_ets_seo_score", $context) && (($context["filter_ets_seo_score"] ?? null) == "bad"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("SEO: Not good", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"ok\" ";
                    // line 98
                    if ((array_key_exists("filter_ets_seo_score", $context) && (($context["filter_ets_seo_score"] ?? null) == "ok"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("SEO: Acceptable", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"good\" ";
                    // line 99
                    if ((array_key_exists("filter_ets_seo_score", $context) && (($context["filter_ets_seo_score"] ?? null) == "good"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("SEO: Excellent", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"na\" ";
                    // line 100
                    if ((array_key_exists("filter_ets_seo_score", $context) && (($context["filter_ets_seo_score"] ?? null) == "na"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("SEO: No Focus or Related key phrases", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"noindex\" ";
                    // line 101
                    if ((array_key_exists("filter_ets_seo_score", $context) && (($context["filter_ets_seo_score"] ?? null) == "noindex"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("SEO: No Index", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                              </select>
                            </div>
                        </div>
                    </th>
                ";
                } elseif ((                // line 106
$context["key"] == "readability_score")) {
                    // line 107
                    echo "                    <th>
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                              <select class=\"custom-select\"  name=\"filter_ets_seo_readability\">
                                <option value=\"\">";
                    // line 111
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("All Readability Scores", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"bad\" ";
                    // line 112
                    if ((array_key_exists("filter_ets_seo_readability", $context) && (($context["filter_ets_seo_readability"] ?? null) == "bad"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Readability: Not good", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"ok\" ";
                    // line 113
                    if ((array_key_exists("filter_ets_seo_readability", $context) && (($context["filter_ets_seo_readability"] ?? null) == "ok"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Readability: Acceptable", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                                <option value=\"good\" ";
                    // line 114
                    if ((array_key_exists("filter_ets_seo_readability", $context) && (($context["filter_ets_seo_readability"] ?? null) == "good"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Readability: Excellent", [], "Modules.Etsseo.Admin"), "html", null, true);
                    echo "</option>
                              </select>
                            </div>
                        </div>
                      </th>
                ";
                } elseif ((                // line 119
$context["key"] == "additional_delivery_times")) {
                    // line 120
                    echo "                    <th>
                        <div class=\"ets_td\">
                            <div class=\"form-select\">
                                <select class=\"custom-select\"  name=\"filter_column_additional_delivery_times\" ";
                    // line 123
                    if (($context["filters_disabled"] ?? null)) {
                        echo "disabled";
                    }
                    echo ">
                                    <option value=\"\"></option>
                                    <option value=\"0\" ";
                    // line 125
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array") == "0"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("None", [], "Modules.Etsproductmanager.Admin"), "html", null, true);
                    echo "</option>
                                    <option value=\"1\" ";
                    // line 126
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array") == "1"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Default delivery time", [], "Modules.Etsproductmanager.Admin"), "html", null, true);
                    echo "</option>
                                    <option value=\"2\" ";
                    // line 127
                    if (($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array", true, true) && ($this->getAttribute(($context["ets_filter_products"] ?? null), "filter_column_additional_delivery_times", [], "array") == "1"))) {
                        echo "selected=\"selected\"";
                    }
                    echo ">";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Specific delivery time to this product", [], "Modules.Etsproductmanager.Admin"), "html", null, true);
                    echo "</option>
                                </select>
                            </div>
                        </div>
                    </th>
                ";
                } elseif (($this->getAttribute(                // line 132
$context["field"], "filter", [], "any", true, true) && $this->getAttribute($context["field"], "filter", []))) {
                    // line 133
                    echo "                    ";
                    if (($this->getAttribute($context["field"], "type", [], "any", true, true) && ($this->getAttribute($context["field"], "type", []) == "int"))) {
                        // line 134
                        echo "                    ";
                        if ($this->getAttribute(($context["ets_filter_products"] ?? null), ("filter_column_" . $context["key"]), [], "array", true, true)) {
                            // line 135
                            echo "                        ";
                            $context["filter_column_value"] = $this->getAttribute(($context["ets_filter_products"] ?? null), ("filter_column_" . $context["key"]), [], "array");
                            // line 136
                            echo "                    ";
                        } else {
                            // line 137
                            echo "                        ";
                            $context["filter_column_value"] = "";
                            // line 138
                            echo "                    ";
                        }
                        // line 139
                        echo "                        <th class=\"";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\">
                        <div class=\"ets_td\">
                            ";
                        // line 141
                        $this->loadTemplate("@PrestaShop/Admin/Helpers/range_inputs.html.twig", "@Product/CatalogPage/Lists/products_table.html.twig", 141)->display(twig_array_merge($context, ["input_name" => ("filter_column_" .                         // line 142
$context["key"]), "min" => "-1000000", "max" => "1000000", "minLabel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Min", [], "Admin.Global"), "maxLabel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Max", [], "Admin.Global"), "value" =>                         // line 147
($context["filter_column_value"] ?? null), "disabled" =>                         // line 148
($context["filters_disabled"] ?? null)]));
                        // line 150
                        echo "                        </div>
                      </th>
                    ";
                    } else {
                        // line 153
                        echo "                        <th class=\"";
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\">
                            <div class=\"ets_td\">
                                <input
                                  type=\"text\"
                                  class=\"form-control\"
                                  placeholder=\"";
                        // line 158
                        echo twig_escape_filter($this->env, $this->getAttribute($context["field"], "filter", []), "html", null, true);
                        echo "\"
                                  name=\"filter_column_";
                        // line 159
                        echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                        echo "\"
                                  value=\"";
                        // line 160
                        if ($this->getAttribute(($context["ets_filter_products"] ?? null), ("filter_column_" . $context["key"]), [], "array", true, true)) {
                            echo twig_escape_filter($this->env, $this->getAttribute(($context["ets_filter_products"] ?? null), ("filter_column_" . $context["key"]), [], "array"), "html", null, true);
                        }
                        echo "\"
                                  ";
                        // line 161
                        if (($context["filters_disabled"] ?? null)) {
                            echo "disabled";
                        }
                        // line 162
                        echo "                                />
                            </div>
                        </th>
                    ";
                    }
                    // line 166
                    echo "                ";
                } else {
                    // line 167
                    echo "                    <th class=\"";
                    echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo "\"><div class=\"ets_td\">--</div></th>
                ";
                }
                // line 169
                echo "            ";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['field'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 170
            echo "            ";
            if ((($context["has_category_filter"] ?? null) == true)) {
                // line 171
                echo "            <th class=\"\">
                <div class=\"ets_td\">
                      ";
                // line 173
                if ( !($context["activate_drag_and_drop"] ?? null)) {
                    // line 174
                    echo "                        <input type=\"button\" class=\"btn btn-outline-secondary\" name=\"products_filter_position_asc\" value=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Reorder", [], "Admin.Actions"), "html", null, true);
                    echo "\" onclick=\"productOrderPrioritiesTable();\" />
                        ";
                } else {
                    // line 176
                    echo "                        <input type=\"button\" id=\"bulk_edition_save_keep\" class=\"btn\" onclick=\"bulkProductAction(this, 'edition');\" value=\"";
                    echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Save & refresh", [], "Admin.Actions");
                    echo "\" />
                      ";
                }
                // line 178
                echo "                </div>
            </th>
          ";
            }
            // line 181
            echo "          <th class=\"text-right ets_action_col\" style=\"width: 5rem\">
            <div class=\"ets_td\">
                <button
                  type=\"submit\"
                  class=\"btn btn-primary\"
                  name=\"products_filter_submit\"
                  title=\"";
            // line 187
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Search", [], "Admin.Actions"), "html", null, true);
            echo "\"
                >
                  <i class=\"material-icons\">search</i>
                  ";
            // line 190
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Search", [], "Admin.Actions"), "html", null, true);
            echo "
                </button>
                <button
                  type=\"reset\"
                  class=\"btn btn-link\"
                  name=\"products_filter_reset\"
                  onclick=\"productColumnFilterResetEts(\$(this).closest('tr.column-filters'));\"
                  title=\"";
            // line 197
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Reset", [], "Admin.Actions"), "html", null, true);
            echo "\"
                >
                  <i class=\"material-icons\">clear</i>
                  ";
            // line 200
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Reset", [], "Admin.Actions"), "html", null, true);
            echo "
                </button>
            </div>
            </th>
        </tr>
    ";
        } else {
            // line 206
            echo "        ";
            $this->displayParentBlock("product_catalog_form_table_filters", $context, $blocks);
            echo "
    ";
        }
    }

    public function getTemplateName()
    {
        return "@Product/CatalogPage/Lists/products_table.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  563 => 206,  554 => 200,  548 => 197,  538 => 190,  532 => 187,  524 => 181,  519 => 178,  513 => 176,  507 => 174,  505 => 173,  501 => 171,  498 => 170,  484 => 169,  478 => 167,  475 => 166,  469 => 162,  465 => 161,  459 => 160,  455 => 159,  451 => 158,  442 => 153,  437 => 150,  435 => 148,  434 => 147,  433 => 142,  432 => 141,  426 => 139,  423 => 138,  420 => 137,  417 => 136,  414 => 135,  411 => 134,  408 => 133,  406 => 132,  394 => 127,  386 => 126,  378 => 125,  371 => 123,  366 => 120,  364 => 119,  352 => 114,  344 => 113,  336 => 112,  332 => 111,  326 => 107,  324 => 106,  312 => 101,  304 => 100,  296 => 99,  288 => 98,  280 => 97,  276 => 96,  270 => 92,  268 => 91,  258 => 86,  252 => 85,  245 => 83,  240 => 80,  238 => 79,  228 => 74,  222 => 73,  215 => 71,  210 => 68,  208 => 67,  198 => 62,  192 => 61,  185 => 59,  180 => 56,  177 => 55,  173 => 53,  168 => 50,  166 => 48,  165 => 47,  164 => 41,  158 => 39,  155 => 38,  152 => 37,  135 => 36,  127 => 34,  124 => 33,  121 => 32,  118 => 31,  110 => 28,  103 => 24,  100 => 23,  94 => 20,  91 => 19,  88 => 18,  80 => 15,  74 => 13,  68 => 11,  66 => 10,  60 => 8,  56 => 7,  48 => 5,  45 => 4,  42 => 3,  38 => 2,  36 => 1,  30 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Product/CatalogPage/Lists/products_table.html.twig", "/home2/inveriti/public_html/modules/ets_productmanager/views/PrestaShop/Admin/Product/CatalogPage/Lists/products_table.html.twig");
    }
}
