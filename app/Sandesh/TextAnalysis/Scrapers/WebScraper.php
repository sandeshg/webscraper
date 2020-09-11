<?php
namespace App\Sandesh\TextAnalysis\Scrapers;
use Goutte\Client;

class WebScraper
{
    protected $url;
    protected $css_selector;
    protected $to_scrape;

    function __construct($url,$css_selector,$to_scrape=['_text', 'href']) {	
        $this->url = $url;
        $this->css_selector = $css_selector;
        $this->to_scrape = $to_scrape;

    }
    
    public function doScrap($method = 'GET'){
        $this->scraper($method);
    }

    
    /**
     *   Scrap page with pagination
     *   @Usages
     *   $url = "https://news.ycombinator.com/news";
     *    $css_selector = "table.itemlist tr.athing td.title a.storylink";
     *    $thing_to_scrape = ['_text', 'href'];
     *    $paginateString = "?p=";
     *    $end = 3;
     *    $scrap = new ws($url,$css_selector,$thing_to_scrape);
     *    $output = $scrap->getLinksWithPagination($paginateString,$end);
     * 
     */
    public function getLinksWithPagination(String $format,int $end, int $incrementBy){

        $paginatedUrl = $this->url.$format;

        $start = 1;
        settype($start, "int");
        try{
            $output[] = $this->scraper();
            do {
                $this->url=$paginatedUrl.$start*$incrementBy;
                $output[] = $this->scraper();
                $start++;
            }while( $start*$incrementBy <= $end );

        }catch(\Exception $e){
            return $e->getMessage();
        }
       return $output;
    }
    

    public function getSinglePageContent($espPruner=[]){
        $output = [];
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        if(!empty($espPruner)){
            foreach($espPruner as $pruner){
                $temp = $crawler->filter($pruner->selector)->extract($pruner->type);
                $output[$pruner->name] = implode(" ",$temp);
            }
        }else{
            $content = $crawler->filter($this->css_selector)->extract($this->to_scrape);
            $output = implode(" ",$content);
        }
        return $output;
    }

    /**
     *   Scrap Content for single page
     *   @Usages
     *   url = "https://www.onlinekhabar.com/2018/06/683082";
     *   $css_selector = "div.ok-single-content p";
     *   $css_selector2 = "span.updated_date";
     *   $thing_to_scrape = ['_text', 'href'];
     *   $ws = new ws($url,$css_selector,$thing_to_scrape);
     *   $espPruner = [
     *       ['css_selector'=>'h1.inside_head','type'=>'_text'],
     *       ['css_selector'=>'div.ok-single-content p','type'=>'_text']
     *   ];
     *   $output =  $ws->doScrapMultiple($espPruner);
     * 
     */
    public function doScrapMultiple($espPruner=[]){
        $output = [];
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        if(!empty($espPruner)){
            //check if array has required keys
            $template = array('css_selector', 'type');
            if (array_intersect_key($template, array_keys($espPruner)) == $template) {
                foreach($espPruner as $pruner){
                    $content = $crawler->filter($pruner['css_selector'])->extract($pruner['type']);
                    $output[] = implode(" ",$content);
                }
            }else{
                return $output;
            }
        }else{
            $content = $crawler->filter($this->css_selector)->extract($this->to_scrape);
            $output = implode(" ",$content);
        }
        return $output;
    }

    

    private function scraper($method = 'GET')
    {
        $client = new Client();
        $crawler = $client->request($method, $this->url);
        $content = $crawler->filter($this->css_selector)->extract($this->to_scrape);
        return $content;
    }
}
