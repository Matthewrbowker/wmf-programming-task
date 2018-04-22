<?php

class results
{
    private $results;        // Final array of results we've parsed.  Key is article
                             // name, value is reading ease.

    /**
     * results constructor
     *
     * @param ApiInterface $api API Interface Object
     * @param \DaveChild\TextStatistics\TextStatistics $textStatistics Text Statistics Object
     */
    public function __construct(ApiInterface $api, DaveChild\TextStatistics\TextStatistics $textStatistics) {
        // Start by pulling the data.  Note that $data will be defined as either
        // an array or NULL.
        $data = $api->getData();

        if(sizeof($data) > 0) {
            // If $data is an array, begin parsing.
            foreach ($data as $key => $value) {
                // $key is the article title, $value is the heading.

                // Similar format, key is article title, value is the Flesch–Kincaid
                // Reading Ease as determined by the DaveChild/TextStatistics library
                $this->results[$key] = $textStatistics->flesch_kincaid_reading_ease($value);
            }
        }
        else {
            // If something goes screwy, set us up with an empty array in the results.
            $this->results = [];
        }

        // Sort the results array by value.
        asort($this->results);
    }

    /**
     * Accessor for the results.
     *
     * @return array Results
     */
    public function getResults() {
        return $this->results;
    }

}