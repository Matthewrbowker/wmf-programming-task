<?php

class template {
    public function __construct() {
        $loader = new Twig_Loader_Filesystem('templates');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => 'cache/twig'));

    }

    public function render($filename = "error.html.twig") {
        try {

            $this->twig->render($filename, array());
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}