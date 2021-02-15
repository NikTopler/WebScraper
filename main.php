<?php

class webScraping {
    public $website = 'http://24ur.com';
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

        $articles = '{
            "source": {
                "name": "24ur",
                "url": "'.$this->website.'"
            },
            "articles": [';

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
        $img = $xpath->query('//figure[@class="figure article__image figure--full"] //img');
        $imgUrl = array();
        if($img->item(0) != null && !empty($img->item(0))) 
            for($j = 0; $j < count($img); $j++) 
                array_push($imgUrl, $img[$j]->getAttribute('src'));

        $a = explode(',', $info);
        $b = explode('|', $a[2]);
        $info = array();
        array_push($info, $a[0]);
        array_push($info, $a[1]);
        array_push($info, $b[0]);
      
        foreach ($authorsNameElement as $name) {
           $n = $name->nodeValue;
           if($n != '/') array_push($authorName, $n);
        }

        foreach ($authors as $a) {
            $aUrl = $a->getAttribute('href');
            array_push($authorsUrl, $aUrl);
        }

        for($i = 0; $i < count($authorName); $i++) {
            $authorSchema = '{
                "name": "'.$authorName[$i].'",
                "url": "'.$this->website.$authorsUrl[$i].'"
            }';
        }

        foreach ($text as $a) { $fullText = $a->nodeValue; }

        $schema = '{
            "title": "'.str_replace("\"","'", $title).'",
            "info": {
                "city": "'.$info[0].'",
                "date": "'.$info[1].'",
                "time": "'.str_replace(' ', '', $info[2]).'"
            },
            "authors": '.$authorSchema.',
            "subtitle": "'.str_replace("\"","'", $subtitle).'",
            "content": "'.str_replace("\"","'", $fullText).'",
            "urlToImage": '.json_encode($imgUrl).',
            "urlToArticle": "'.$this->website.$this->links[$l].'"
        }';

        return $schema;
    }
}

$scrape = new webScraping();

$xpath = $scrape->setup($scrape->website.'/novice');
$scrape->getLinks($xpath);
$scrape->loopThroughLinks();