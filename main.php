<?php

class webScraping {
    public $website = 'http://qlr.24ur.com';
    public $links = array();

    public function setup($website) {
        $html = file_get_contents($website);
        $news_doc = new DOMDocument();
    
        libxml_use_internal_errors(TRUE);
        if(empty($html)) die;
    
        $news_doc->loadHTML($html);
        libxml_clear_errors();
    
        return new DOMXPath($news_doc);
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




