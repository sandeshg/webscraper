<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sandesh\TextAnalysis\Tokenizers\Tokenizer;
use App\Sandesh\TextAnalysis\Analysis\FrequencyDistribution as fq;
use App\Sandesh\TextAnalysis\Filters\Filter;
use App\Sandesh\TextAnalysis\Scrapers\WebScraper as ws;

class TestsController extends Controller
{
    //

    public function index(){



        $this->debug($this->scrap(),1);


        //$this->token();
        //$this->frequency();
        
        $string = "President Bidya Devi Bhandari has administered oath of office and secrecy to  two new ministers in the Communist Party of Nepal (CPN)-led government. 

        Those newly sworn in as ministers are Mrs. Upendra Yadav (Minister for Health and Population) and Mohammed Ishtiyak Rai (Minister for Urban Development). 
        
        The two are from the Federal Socialist Forum Nepal (FSFN). The President appointed them to the posts as per the Article 76 (9) of the Nepal's constitution. Prior to this, portfolios of both ministers were held by Prime Minister KP Sharma Oli himself.";
        echo $string;
        $this->debug($this->tokenizeSentence($string),1);


        $scraped = $this->scrap();
        $this->debug($scraped); 

        $tokenizer = new Tokenizer();
        $filter = new Filter();
        //$this->debug($scraped[0][0],1);
        $output = $tokenizer->tokenize($filter->removePunctuation($scraped[0]));
        $fq = new fq($output);
        $this->debug($fq->getKeyValuesByFrequency(),1);
        //$this->debug($scraped[0],1);
    }

    protected function scrap(){
       
        /* $url = "https://www.onlinekhabar.com/2018/06/683082";
        $css_selector = "div.ok-single-content p";
        $css_selector2 = "span.updated_date";
        $thing_to_scrape = "text";

        $ws = new ws($url,$css_selector,$thing_to_scrape);
        //$this->debug($ws->doScrap(),1);
        $espPruner=[];
        $espPruner = [
            ['css_selector'=>'h1.inside_head','type'=>'_text'],
            ['css_selector'=>'div.ok-single-content p','type'=>'_text']
            
        ];
        return $ws->doScrapMultiple($espPruner); */
        


        /* $url = "https://www.onlinekhabar.com/content/news";
        $css_selector = "#sing_left #archlist .news_loop h2 a";
        $thing_to_scrape = "_href";
        $ws = new ws($url,$css_selector,$thing_to_scrape);
         return $ws->doScrap();  */


        //  $url = "https://news.ycombinator.com/news";
        //  $css_selector = "table.itemlist tr.athing td.title a.storylink";
        //  $paginateString = "?p=";
        //  $end = 2;
        //  $to_scrap = array('_text','href');

          $url = "https://smartdoko.com/category/smart-phones?sort=pop&brand=samsung";
          $css_selector = "div.features_items div.col-sm-4 div.product-image-wrapper div.productinfo p.title a";
          $paginateString = "&offset=";
          $end = 15;
          $incrementBy = 15;
          $to_scrap = array('_text','href');

        //  $url = "https://www.onlinekhabar.com/content/opinion";
        //  $css_selector = "div.news_loop h2 a";
        //  $paginateString = "/page/";
        //  $end = 4;
        //  $to_scrap = array('_text','href');
         
 
         $scrap = new ws($url,$css_selector,$to_scrap);
         $output = $scrap->getLinksWithPagination($paginateString,$end,$incrementBy);
         return $output;

    }
    
    protected function frequency(){
        $token = $this->token();
        //$this->debug($token,1);
        $fq = new fq($token);
        $this->debug($fq->getKeyValuesByFrequency());
    }
    /**
     * Token Testing function
    */
    protected function token(){
        $tokenizer = new Tokenizer();
        $string = "President Bidya Devi Bhandari has administered oath of office and secrecy to  two new ministers in the Communist Party of Nepal (CPN)-led government. 

        Those newly sworn in as ministers are Upendra Yadav (Minister for Health and Population) and Mohammed Ishtiyak Rai (Minister for Urban Development). 
        
        The two are from the Federal Socialist Forum Nepal (FSFN). The President appointed them to the posts as per the Article 76 (9) of the Nepal's constitution. Prior to this, portfolios of both ministers were held by Prime Minister KP Sharma Oli himself.";

        //$this->debug(str_replace(array('.','?','!',',','\\"'),'',$string),1);
        $filter = new Filter();
        //$this->debug($filter->all($string),1);
        $output = $tokenizer->tokenize($filter->all($string));
        return $output;
        //$this->debug($output);


    }


    protected function tokenizeSentence($string) 
    {
        
        
        $before_regexes = array('/(?:(?:[\'\"„][\.!?…][\'\"”]\s)|(?:[^\.]\s[A-Z]\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s[A-Z]\.\s)|(?:\bApr\.\s)|(?:\bAug\.\s)|(?:\bBros\.\s)|(?:\bCo\.\s)|(?:\bCorp\.\s)|(?:\bDec\.\s)|(?:\bDist\.\s)|(?:\bFeb\.\s)|(?:\bInc\.\s)|(?:\bJan\.\s)|(?:\bJul\.\s)|(?:\bJun\.\s)|(?:\bMar\.\s)|(?:\bNov\.\s)|(?:\bOct\.\s)|(?:\bPh\.?D\.\s)|(?:\bSept?\.\s)|(?:\b\p{Lu}\.\p{Lu}\.\s)|(?:\b\p{Lu}\.\s\p{Lu}\.\s)|(?:\bcf\.\s)|(?:\be\.g\.\s)|(?:\besp\.\s)|(?:\bet\b\s\bal\.\s)|(?:\bvs\.\s)|(?:\p{Ps}[!?]+\p{Pe} ))\Z/su',
            '/(?:(?:[\.\s]\p{L}{1,2}\.\s))\Z/su',
            '/(?:(?:[\[\(]*\.\.\.[\]\)]* ))\Z/su',
            '/(?:(?:\b(?:pp|[Vv]iz|i\.?\s*e|[Vvol]|[Rr]col|maj|Lt|[Ff]ig|[Ff]igs|[Vv]iz|[Vv]ols|[Aa]pprox|[Ii]ncl|Pres|[Dd]ept|min|max|[Gg]ovt|lb|ft|c\.?\s*f|vs)\.\s))\Z/su',
            '/(?:(?:\b[Ee]tc\.\s))\Z/su',
            '/(?:(?:[\.!?…]+\p{Pe} )|(?:[\[\(]*…[\]\)]* ))\Z/su',
            '/(?:(?:\b\p{L}\.))\Z/su',
            '/(?:(?:\b\p{L}\.\s))\Z/su',
            '/(?:(?:\b[Ff]igs?\.\s)|(?:\b[nN]o\.\s))\Z/su',
            '/(?:(?:[\"”\']\s*))\Z/su',
            '/(?:(?:[\.!?…][\x{00BB}\x{2019}\x{201D}\x{203A}\"\'\p{Pe}\x{0002}]*\s)|(?:\r?\n))\Z/su',
            '/(?:(?:[\.!?…][\'\"\x{00BB}\x{2019}\x{201D}\x{203A}\p{Pe}\x{0002}]*))\Z/su',
            '/(?:(?:\s\p{L}[\.!?…]\s))\Z/su');
        $after_regexes = array('/\A(?:)/su',
            '/\A(?:[\p{N}\p{Ll}])/su',
            '/\A(?:[^\p{Lu}])/su',
            '/\A(?:[^\p{Lu}]|I)/su',
            '/\A(?:[^p{Lu}])/su',
            '/\A(?:\p{Ll})/su',
            '/\A(?:\p{L}\.)/su',
            '/\A(?:\p{L}\.\s)/su',
            '/\A(?:\p{N})/su',
            '/\A(?:\s*\p{Ll})/su',
            '/\A(?:)/su',
            '/\A(?:\p{Lu}[^\p{Lu}])/su',
            '/\A(?:\p{Lu}\p{Ll})/su');
        $is_sentence_boundary = array(false, false, false, false, false, false, false, false, false, false, true, true, true);
        $count = count($is_sentence_boundary);
        $sentences = [];
        $sentence = '';
        $before = '';
        $after = substr($string, 0, 10);
        $string = substr($string, 10);
        while($string != '') 
        {
            for($i = 0; $i < $count; $i++) 
            {
                if(preg_match($before_regexes[$i], $before) && preg_match($after_regexes[$i], $after)) {
                    if($is_sentence_boundary[$i]) {
                        $sentences[] = $sentence;
                        $sentence = '';
                    }
                    break;
                }
            }
            $first_from_text = $string[0];
            $string = substr($string, 1);
            $first_from_after = $after[0];
            $after = substr($after, 1);
            $before .= $first_from_after;
            $sentence .= $first_from_after;
            $after .= $first_from_text;
        }
        if(!empty($sentence) && !empty($after)) {
            $sentences[] = $sentence.$after;
        }
        // perform some cleanup, and re-index the array
        return array_values(array_filter(array_map('trim',$sentences)));
    }
}
