<?php

class webScraping {
    public $website = 'http://qlr.24ur.com';
    public $links = array();

    public function setup($website) {
    }

    public function getLinks($xpath) {
    }

    public function loopThroughLinks() {
    }    

    public function fetchDataFromLinks($l) {
    }
}

$scrape = new webScraping();

$xpath = $scrape->setup($scrape->website.'/novice');
$scrape->getLinks($xpath);
$scrape->loopThroughLinks();




