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

if (isset($_GET["category"]) && $_GET["category"] !== null) {
    $cat = $_GET["category"];

    $cat = htmlentities($cat);

    try {

        $api = new apiInterface($cat);
        $ts = new DaveChild\TextStatistics\TextStatistics();
        $results = new results($cat, $api, $ts);
    }
    catch(Exception $e) {
        die("<html><body>{$e->getMessage()}<br />Please check server logs for more information.</body></html>");
    }

    $data =$results->getResults();

    $template->render("result.html.twig", ["category"=>$cat, "data"=>$data, "path"=>$_SERVER["PHP_SELF"]]);
}
else {
    $template->render("index.html.twig");
}