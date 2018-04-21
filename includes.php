<?php

if (file_exists("vendor/autoload.php")) {
    require("vendor/autoload.php");
}
else {
    die("<html><body>Please run <pre>composer install</pre></body></html>");
}

require("includes/template.php");