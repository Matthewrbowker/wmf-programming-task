<?php

class template {
    private $twig;

    public function __construct() {
        $loader = new Twig_Loader_Filesystem(getcwd() . '/../templates');
        $this->twig = new Twig_Environment($loader, array());

    }

    public function render($filename = "error.html.twig", $array = []) {
        try {

            echo $this->twig->render($filename, $array);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}