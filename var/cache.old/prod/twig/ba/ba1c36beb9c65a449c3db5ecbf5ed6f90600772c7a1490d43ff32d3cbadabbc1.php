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

/* @Modules/rkradditionalcustomerformfields/views/admin/aditional_fields.twig */
class __TwigTemplate_b5da226228acdd295a4985c191669fbfeeb7b42d83d980a131e9a574e0ca1cac extends \Twig\Template
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
        // line 6
        echo "
<div class=\"col\">
<div class=\"card\">
    <h3 class=\"card-header\">
        <i class=\"material-icons\">person</i>
        ";
        // line 11
        echo twig_escape_filter($this->env, ($context["title"] ?? null));
        echo "
    </h3>

    <div class=\"card-body\">

        ";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["campos"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["campo"]) {
            // line 17
            echo "        <div class=\"row mb-1\">
            <div class=\"col-4 text-right\">
                ";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($context["campo"], "label", [], "array"));
            echo "
            </div>
            <div class=\"col-8\">
                ";
            // line 22
            if (twig_test_empty($this->getAttribute($context["campo"], "value", [], "array"))) {
                echo " -
                ";
            } else {
                // line 24
                echo "                ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["campo"], "value", [], "array"));
                echo "
                ";
            }
            // line 26
            echo "            </div>
        </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['campo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "
    </div>
</div>
</div>";
    }

    public function getTemplateName()
    {
        return "@Modules/rkradditionalcustomerformfields/views/admin/aditional_fields.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 29,  70 => 26,  64 => 24,  59 => 22,  53 => 19,  49 => 17,  45 => 16,  37 => 11,  30 => 6,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@Modules/rkradditionalcustomerformfields/views/admin/aditional_fields.twig", "/home2/inveriti/public_html/modules/rkradditionalcustomerformfields/views/admin/aditional_fields.twig");
    }
}
