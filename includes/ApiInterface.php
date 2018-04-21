<?php

class ApiInterface
{
    private $baseURL;
    private $error;

    public function __construct($baseURL = "https://en.wikipedia.org/w/api.php") {
        $this->baseURL = $baseURL;
        $this->error = null;

    }

}