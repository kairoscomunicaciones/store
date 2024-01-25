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

/* @PrestaShop/Admin/Improve/International/Currency/Blocks/form.html.twig */
class __TwigTemplate_6fc866dd7ec80b412cac00c275e9204d9708f9cfdf2192173def2c2371c6bba9 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'form' => [$this, 'block_form'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 25
        echo "
";
        // line 26
        $this->env->getRuntime("Symfony\\Component\\Form\\FormRenderer")->setTheme(($context["currencyForm"] ?? null), [0 => "PrestaShopBundle:Admin/TwigTemplateForm:prestashop_ui_kit.html.twig"], true);
        // line 27
        echo "
";
        // line 28
        $this->displayBlock('form', $context, $blocks);
    }

    public function block_form($context, array $blocks = [])
    {
        // line 29
        echo "  ";
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["currencyForm"] ?? null), 'form_start', ["attr" => ["id" => "currency_form", "data-reference-url" => twig_replace_filter($this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_currencies_get_reference_data", ["currencyIsoCode" => "CURRENCY_ISO_CODE"]), ["/CURRENCY_ISO_CODE" => "{/id}"]), "data-languages" => twig_jsonencode_filter(        // line 33
($context["languages"] ?? null)), "data-translations" => twig_jsonencode_filter(["step.symbol" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("1. Enter symbol", [], "Admin.International.Feature"), "step.format" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("2. Choose format", [], "Admin.International.Feature"), "modal.title" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Customize symbol and format", [], "Admin.International.Feature"), "modal.apply" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Apply", [], "Admin.Actions"), "modal.cancel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Cancel", [], "Admin.Actions"), "modal.close" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Close", [], "Admin.Actions"), "list.language" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Language", [], "Admin.Global"), "list.example" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Example", [], "Admin.Global"), "list.edit" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Edit symbol / format", [], "Admin.International.Feature"), "list.reset" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Reset", [], "Admin.Actions"), "list.reset.success" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Your symbol and format customizations have been successfully reset for this language.", [], "Admin.Notifications.Success"), "list.example.format" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("%price%", [], "Admin.International.Feature"), "modal.restore.title" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Are you sure you want to restore default settings?", [], "Admin.International.Feature"), "modal.restore.apply" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Restore", [], "Admin.Actions"), "modal.restore.cancel" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Cancel", [], "Admin.Actions"), "modal.restore.body" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Restoring your currency's default settings will delete all the customizations you made before. Parameters will look just the same as when the currency is freshly imported.", [], "Admin.International.Feature")])]]);
        // line 53
        echo "

    <div class=\"card card-currency\">
      <div class=\"card-header\">
        ";
        // line 57
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Currencies", [], "Admin.Global"), "html", null, true);
        echo "
      </div>
      <div class=\"card-block row\">
        <div class=\"card-text\">
          ";
        // line 61
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["currencyForm"] ?? null), 'errors');
        echo "

          ";
        // line 63
        if ( !$this->getAttribute($this->getAttribute($this->getAttribute(($context["currencyForm"] ?? null), "vars", [], "any", false, true), "data", [], "any", false, true), "id", [], "any", true, true)) {
            // line 64
            echo "
            ";
            // line 65
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($this->getAttribute(($context["currencyForm"] ?? null), "selected_iso_code", []), 'row');
            echo "
            <div class=\"form-group row type-checkbox \">
              <label for=\"currency_unofficial\" class=\"form-control-label \">
                ";
            // line 68
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("or", [], "Admin.Global"), "html", null, true);
            echo "
              </label>
              ";
            // line 70
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($this->getAttribute(($context["currencyForm"] ?? null), "unofficial", []), 'widget');
            echo "
            </div>
          ";
        }
        // line 73
        echo "
          ";
        // line 74
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($this->getAttribute(($context["currencyForm"] ?? null), "names", []), 'row');
        echo "

          ";
        // line 76
        $context["symbolsClass"] = ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["currencyForm"] ?? null), "symbols", []), "vars", []), "errors", []))) ? ("") : ("d-none"));
        // line 77
        echo "          ";
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($this->getAttribute(($context["currencyForm"] ?? null), "symbols", []), 'row', ["row_attr" => ["class" => ($context["symbolsClass"] ?? null)]]);
        echo "
          ";
        // line 78
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["currencyForm"] ?? null), 'widget');
        echo "
        </div>
      </div>

      <div class=\"card-footer\">
        <div class=\"card-footer-left\">
          <a href=\"";
        // line 84
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_currencies_index");
        echo "\" class=\"btn btn-outline-secondary\">
            ";
        // line 85
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Cancel", [], "Admin.Actions"), "html", null, true);
        echo "
          </a>

          ";
        // line 88
        if (($this->getAttribute($this->getAttribute($this->getAttribute(($context["currencyForm"] ?? null), "vars", [], "any", false, true), "data", [], "any", false, true), "id", [], "any", true, true) &&  !$this->getAttribute($this->getAttribute($this->getAttribute(($context["currencyForm"] ?? null), "vars", []), "data", []), "unofficial", []))) {
            // line 89
            echo "            <button type=\"button\" id=\"currency_reset_default_settings\" class=\"btn btn-outline-primary card-currency-reset\">
              <i class=\"material-icons\">refresh</i>
              ";
            // line 91
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Restore default settings", [], "Admin.International.Feature"), "html", null, true);
            echo "
            </button>
          ";
        }
        // line 94
        echo "        </div>

        <button type=\"submit\" class=\"btn btn-primary\" id=\"save-button\">";
        // line 96
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Save", [], "Admin.Actions"), "html", null, true);
        echo "</button>
      </div>
    </div>
  ";
        // line 99
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["currencyForm"] ?? null), 'form_end');
        echo "

  ";
        // line 101
        if ((array_key_exists("languageDataError", $context) && ($context["languageDataError"] ?? null))) {
            // line 102
            echo "    <div class=\"card\">
      <div class=\"card-body\">
        <div class=\"alert alert-danger\">
          ";
            // line 105
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Could not display symbol and format customization:", [], "Admin.International.Notification"), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, ($context["languageDataError"] ?? null), "html", null, true);
            echo "
        </div>
      </div>
    </div>
  ";
        } else {
            // line 110
            echo "    <!-- Modal -->
    <div class=\"modal fade\" id=\"currency_loading_data_modal\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" data-backdrop=\"static\">
      <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">";
            // line 115
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Loading currency data", [], "Admin.International.Feature"), "html", null, true);
            echo "</h5>
          </div>
          <div class=\"modal-body\">
            ";
            // line 118
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Please wait while currency data is being loaded", [], "Admin.International.Feature"), "html", null, true);
            echo "
          </div>
          <div class=\"modal-footer\">
            <button type=\"button\" class=\"btn btn-secondary spinner\">";
            // line 121
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Close", [], "Admin.Actions"), "html", null, true);
            echo "</button>
          </div>
        </div>
      </div>
    </div>
  ";
        }
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/International/Currency/Blocks/form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  186 => 121,  180 => 118,  174 => 115,  167 => 110,  157 => 105,  152 => 102,  150 => 101,  145 => 99,  139 => 96,  135 => 94,  129 => 91,  125 => 89,  123 => 88,  117 => 85,  113 => 84,  104 => 78,  99 => 77,  97 => 76,  92 => 74,  89 => 73,  83 => 70,  78 => 68,  72 => 65,  69 => 64,  67 => 63,  62 => 61,  55 => 57,  49 => 53,  47 => 33,  45 => 29,  39 => 28,  36 => 27,  34 => 26,  31 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/International/Currency/Blocks/form.html.twig", "/home2/inveriti/public_html/src/PrestaShopBundle/Resources/views/Admin/Improve/International/Currency/Blocks/form.html.twig");
    }
}
