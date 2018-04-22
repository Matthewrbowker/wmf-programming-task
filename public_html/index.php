<?php

// Start by loading the Composer libraries.  If they don't exist, throw an error.
if (file_exists("../vendor/autoload.php")) {
    require_once("../vendor/autoload.php");
}
else {
    die("<html><body>Please run  <pre>composer install</pre></body></html>");
}

// Additional includes, these are the modules written outside of composer
require_once("../includes/ApiInterface.php");
require_once("../includes/results.php");
require_once("../includes/template.php");

// Generate a new Template object
$template = new Template();
$error = "";

if (isset($_GET["category"]) && $_GET["category"] !== null && $_GET["category"] !== "") {
    $cat = ucfirst($_GET["category"]);

    $data = [];

    try {
        $api = new apiInterface($cat);
        $ts = new DaveChild\TextStatistics\TextStatistics();
        $results = new results($cat, $api, $ts);

        $data =$results->getResults();

        if (sizeof($data) <1) {
            $error = "No results found!";
        }
    }
    catch(Exception $e) {
        $error = "An exception has occured in this application: {$e->getMessage()}. Please check server logs for more information.";
    }

    $template->render("result.html.twig", ["category"=>$cat, "data"=>$data, "path"=>$_SERVER["PHP_SELF"], "error"=>$error]);
}
else {
    $template->render("index.html.twig");
}