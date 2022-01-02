<?php

class WebScraping {

    private $website = 'http://24ur.com';

    function __construct() {

        $file_content = $this->file_content($this->website.'/novice');
        
        if(!$file_content) {
            echo json_encode(
                array(
                    'status' => 200,
                    'message'=> `Coudn't load `.$this->website.`/novice`
                )
            );
            return;
        }

        $links = array();
        $articles = array();
        $this->get_links($file_content, $links);

        for($i = 0; $i < count($links); $i++) {
            $res = $this->fetch_articles($i, $links);
            
            if($res != -1)
                array_push($articles, $res);
        }

        $schema = array(
            'source' => array(
                'name' => '24ur',
                'url' => $this->website
            ), 
            'articles' => $articles
        );

        echo json_encode($schema);
    }

    private function file_content($website) {
        $html = file_get_contents($website);
        $news_doc = new DOMDocument();
    
        libxml_use_internal_errors(TRUE);

        if(empty($html)) {
            error_log('Err', `Couldn't open `.$website);
            return null;
        }

        $news_doc->loadHTML($html);
        libxml_clear_errors();  
        
        return new DOMXPath($news_doc);
    }

    private function get_links($xpath, & $links) {
        $linkElements = $xpath->query('//div[@class="news-list__item"] //a[contains(concat(" ", normalize-space(@class), " "), "card")]'); 

        foreach ($linkElements as $linkElement) {
            $newLinkArray = $linkElement->getAttribute('href');
            array_push($links, $newLinkArray);
        }
    }  

    private function fetch_articles($num, $links) {

        $link = $this->website.$links[$num];
        $xpath = $this->file_content($link);

        if(!$xpath)
            return -1;

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
            if($n != '/') 
                array_push($authorName, $n);
        }

        foreach ($authors as $a) {
            $aUrl = $a->getAttribute('href');
            array_push($authorsUrl, $aUrl);
        }

        $authorSchema = array();
        for($i = 0; $i < count($authorName); $i++)
            array_push($authorSchema, array(
                'name' => $authorName[$i], 
                'url' => $this->website.$authorsUrl[$i])
            );


        foreach ($text as $a) 
            $fullText = $a->nodeValue;


        $schema = array(
            'title' => str_replace("\"","'", $title),
            'info' => array(
                'city' => $info[0],
                'date' => $info[1],
                'time' => str_replace(' ', '', $info[2])
            ),
            'authors' => $authorSchema,
            'subtitle' => str_replace("\"","'", $subtitle),
            'content' => str_replace("\"","'", $fullText),
            'urlToImage' => json_encode($imgUrl),
            'urlToArticle' => $this->website.$links[$num]
        );            

        return $schema;
    }
}

new WebScraping();