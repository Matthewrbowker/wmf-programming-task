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

// Generate a new twig object.
$loader = new Twig_Loader_Filesystem(getcwd() . '/../templates');
$twig = new Twig_Environment($loader, array());

// Error string.  Defaulting to an empty string, if populated a red box is displayed
// on the template.
$error = "";

if (
    // Check to see if the category is set and a usable value.
    isset($_GET["category"]) &&
    $_GET["category"] !== null &&
    $_GET["category"] !== "") {

    // Categories are uppercase first character, like everything else MediaWiki
    $cat = ucfirst($_GET["category"]);

    // If we put Category: in the web form, filter it.
    $cat = str_replace("Category:", "", $cat);

    // Define the array, so we don't get E_WARNINGs down the line
    $data = [];

    try {
        // Wrap the declarations in a try, because they can fail when declared

        // Biggest performance hit right here, this is where the API queries.
        $api = new apiInterface($cat);
        $ts = new DaveChild\TextStatistics\TextStatistics();
        $results = new results($api, $ts);

        // Our other big lifter.  This method pulls the data and gives us a properly
        // formatted array.
        $data =$results->getResults();

        // Just in case we don't get anything else out, give an easy to read error.
        if (sizeof($data) <1) {
            $error = "No results found!";
        }
    }
    catch(Exception $e) {
        // If any of the above doesn't go smoothly,
        $error = "An exception has occurred in this application: {$e->getMessage()}. Please check server logs for more information.";
    }

    // Render the twig template.
    try {
        echo $twig->render(
            "result.html.twig",
            [
                "category"=>$cat,
                "data"=>$data,
                "path"=>$_SERVER["PHP_SELF"],
                "error"=>$error
            ]
        );
    }
    catch (Exception $e) {
        // Well at this point, we're as broken as we can be.  Print a message and exit.
        die($e->getMessage());
    }
}
else {
    // Category parameter not set, so we render the form via Twig.
    try {
        echo $twig->render("index.html.twig");
    }
    catch (Exception $e) {
        // Well at this point, we're as broken as we can be.  Print a message and exit.
        die($e->getMessage());
    }
}