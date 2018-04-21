<?php

/* base.html.twig */
class __TwigTemplate_748fbb844faac2979ed73bed5ffb6f840ce413b57b68872ff4b9c8ebabd6631a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<HTML>
<HEAD>
    <TITLE>

    </TITLE>
</HEAD>
<BODY>
";
        // line 8
        $this->displayBlock('body', $context, $blocks);
        // line 11
        echo "</BODY>
</HTML>";
    }

    // line 8
    public function block_body($context, array $blocks = array())
    {
        // line 9
        echo "
";
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 9,  36 => 8,  31 => 11,  29 => 8,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/wmf-programming-task/templates/base.html.twig");
    }
}
