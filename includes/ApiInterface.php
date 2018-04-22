<?php

use \Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;

class ApiInterface
{
    private $api;
    private $error;
    private $pages;
    private $data;

    private function retrievePages($category) {
        $this->pages = [];
        $request = new SimpleRequest("query", ["list"=>"categorymembers", "cmtype"=>"page","cmtitle"=>"Category:$category", "cmlimit"=>50]);
        try{
            $response = $this->api->getRequest( $request );
        }
        catch ( UsageException $e ) {
            echo "The api returned an error!";
        }

        foreach($response["query"]["categorymembers"] as $categorymember) {
            $this->pages[] =  $categorymember["title"];
        }

        // https://en.wikipedia.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:aabb&cmlimit=50
    }

    private function retrieveSummaries() {
        $request = new SimpleRequest("query", ["prop"=>"extracts", "exintro"=>1, "titles"=>join("|", $this->pages), "explaintext"=>1]);

        if (sizeof($this->pages) <= 0) {
            return;
        }

        $response = $this->api->getRequest($request);

        foreach($response["query"]["pages"] as $page) {
            if (!isset($page["extract"])) continue;
            $this->data[$page["title"]] =  $page["extract"];
        }

        //https://en.wikipedia.org/w/api.php?action=query&prop=extracts&exintro=1&titles=Therion|Matthew_(given_name)&explaintext
    }

    public function __construct($category, $endpoint = "http://en.wikipedia.org/w/api.php") {
        $this->api = MediawikiApi::newFromApiEndpoint( $endpoint);
        $this->error = null;

        $this->retrievePages($category);

        $this->retrieveSummaries();

    }

    public function getData() {
        return $this->data;
    }


}