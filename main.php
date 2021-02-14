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
        $linkElements = $xpath->query('//div[@class="news-list__item"] //a[contains(concat(" ", normalize-space(@class), " "), "card")]');
    
        foreach ($linkElements as $linkElement) {
            $newLinkArray = $linkElement->getAttribute('href');
            array_push($this->links, $newLinkArray);
        }
    }

    public function loopThroughLinks() {

        $articles = "{
            'source': {
                'name': '24ur',
                'url': '".$this->website."'
            },
            'articles': [";

        for($l = 0; $l < count($this->links); $l++) {
            $data = $this->fetchDataFromLinks($l);

            if($l == count($this->links) - 1) $comma = '';
            else $comma = ',';

            $articles = $articles.$data.$comma;
        }
        $articles = $articles.']}';
        echo $articles;
    }    

    public function fetchDataFromLinks($l) {
        $link = $this->website.$this->links[$l];
        $xpath = $this->setup($link);

        $title = $xpath->query('//h1[@class="article__title"]')->item(0)->nodeValue;
        $info = $xpath->query('//p[@class="article__info"]')->item(0)->nodeValue;
        $authors = $xpath->query('//a[@class="c-pointer link link--plain"]');
        $authorsNameElement = $xpath->query('//a[@class="c-pointer link link--plain"]//text()');
        $authorName = array();
        $authorsUrl = array();
        $subtitle = $xpath->query('//div[@class="article__summary"]')->item(0)->nodeValue;
        $text = $xpath->query('//onl-article-body[@class="article__body-dynamic dev-article-contents"] //span //p');
        $img = $xpath->query('//figure[@class="figure article__image figure--full"] //img')->item(0)->getAttribute('src');

        $a = explode(',', $info);
        $b = explode('|', $a[2]);
        $info = array();
        array_push($info, $a[0]);
        array_push($info, $a[1]);
        array_push($info, $b[0]);
    }
}

$scrape = new webScraping();

$xpath = $scrape->setup($scrape->website.'/novice');
$scrape->getLinks($xpath);
$scrape->loopThroughLinks();