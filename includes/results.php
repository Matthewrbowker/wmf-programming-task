<?php

class results
{
    private $results;
    private $api;
    private $textStatistics;

    private function loadData() {
        $data = $this->api->getData();

        foreach ($data as $key => $value) {
            $this->results[$key] = $this->textStatistics->flesch_kincaid_reading_ease($value);
        }

        arsort($this->results);

    }

    public function __construct($category, ApiInterface $api, DaveChild\TextStatistics\TextStatistics $textStatistics) {
        $this->api = $api;
        $this->textStatistics = $textStatistics;

        $this->loadData();

    }

    public function getResults() {
        return $this->results;
    }

}