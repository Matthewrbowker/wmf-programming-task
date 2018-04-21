<?php

if (file_exists("../vendor/autoload.php")) {
    require_once("../vendor/autoload.php");
}
else {
    die("<html><body>Please run  <pre>composer install</pre></body></html>");
}

require("../includes/template.php");
require("../includes/results.php");
require("../includes/ApiInterface.php");

$template = new Template();

if (isset($_GET["category"])) {
    $cat = $_GET["category"];
    $template->render("result.html.twig", ["category"=>$cat]);

    $results = new results($cat);
}
else {
    $template->render("index.html.twig");
}