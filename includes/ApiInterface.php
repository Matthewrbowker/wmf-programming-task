<?php

use \Mediawiki\Api\MediawikiApi;
use \Mediawiki\Api\SimpleRequest;

/**
 * Class ApiInterface
 *
 * This class acts as a wrapper for all of the API calls
 */
class ApiInterface
{
    private $api;   // MediawikiApi Object
    private $pages; // Array of page names, extracted from the category
    private $data;  // Array of pages with values being summaries.


    /**
     * Retrieves a page list using [[API:Categorymembers]]
     *
     * @param string $category Category we're retrieving
     * @throws Exception If something goes wrong
     */
    private function retrievePages($category) {
        // Initialize the $this->pages array.
        $this->pages = [];

        // Prepare the request.  This is a MediawikiA\Api\SimpleRequest object,
        // and generates a URL similar to:
        // https://en.wikipedia.org/w/api.php?action=query&list=categorymembers&cmnamespace=0&cmtype=page&cmtitle=Category:Testing&cmlimit=50
        $request = new SimpleRequest(
            "query",
            [
                "list"=>"categorymembers",
                "cmnamespace"=>0,
                "cmtype"=>"page",
                "cmtitle"=>"Category:$category",
                "cmlimit"=>50
            ]
        );

        try{
            // Try the request against MediaWiki.  If it doesn't work, handle the
            // exception.
            $response = $this->api->getRequest( $request );
        }
        catch ( Exception $e ) {
            throw new Exception("The api returned an error: {$e->getMessage()}!");
        }

        foreach($response["query"]["categorymembers"] as $categorymember) {
            // For each of the page titles, append it to the array.
            $this->pages[] =  $categorymember["title"];
        }
    }


    /**
     * Retrieves the edit summaries using [[Extension:TextExtracts#API]]
     */
    private function retrieveSummaries() {

        // If our page array is empty, it's not worth continuing.  Exit
        if (sizeof($this->pages) <= 0) {
            return;
        }

        // Chunk the array into 20-page pieces while perserving the keys.
        // This is necessary because we have a 20 page limit on the API.
        $pagesSplit = array_chunk($this->pages, 20, true);

        foreach($pagesSplit as $titles) {
            // For each group of 20 pages, join the titles together and build a request
            $titlesString = join("|", $titles);
            $queryParameters = [
                "prop"=>"extracts",
                "exintro"=>1,
                "titles"=>$titlesString,
                "explaintext"=>1];
            // This constructs a URL similar to:
            //https://en.wikipedia.org/w/api.php?action=query&prop=extracts&exintro=1&titles=A|B&explaintext=1
            $request = new SimpleRequest("query", $queryParameters);

            try{
                // Try the request against MediaWiki.  If it doesn't work, handle the
                // exception.
                $response = $this->api->getRequest($request);
            }
            catch ( Exception $e ) {
                throw new Exception("The api returned an error: {$e->getMessage()}!");
            }

            // Now, iterate over the responses and add them to our data array.
            foreach($response["query"]["pages"] as $page) {
                // If there is no extract, disregard the page entirely.
                if (!isset($page["extract"])) continue;

                // Key is title, value is the actual extract.
                $this->data[$page["title"]] =  $page["extract"];
            }

        }
    }

    /**
     * ApiInterface constructor.
     * @param string $category Category we're using\
     * @param string $endpoint API Endpoint URL.
     * @throws Exception
     */
    public function __construct($category, $endpoint = "http://en.wikipedia.org/w/api.php") {
        $this->api = MediawikiApi::newFromApiEndpoint( $endpoint);

        // Handle if someone put Category: in the web form
        $category = str_replace("Category:", "", $category);

        try {
            // Attempt to retrieve the information and store it.
            $this->retrievePages($category);

            $this->retrieveSummaries();
        }
        catch (Exception $e) {
            // It broke, pass it up.
            throw $e;
        }

    }

    /**
     * Accessor for the data
     *
     * @return array|null Data we've discovered
     */
    public function getData() {
        return $this->data;
    }


}