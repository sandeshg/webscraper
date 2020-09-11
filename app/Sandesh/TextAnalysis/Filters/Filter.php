<?php
namespace App\Sandesh\TextAnalysis\Filters;

class Filter
{

    public function all($string){
            $temp = $this->removePunctuation($string);
            $output = $this->toLowerCase($temp);
        
        return $output;
    }

    public function removePurnaBiram($str){
        return remove($str,'।');
    }

    /**
     * Just replace it with something
     */
    public function replace($str, $to_replace, $replace_with){
        return remove($str,$to_replace,replace_with);
    }

    public function toLowerCase($str)
    {
        return mb_strtolower($str);
    }


    public function removePunctuation($str){

        $replacement = "";
         
        $temp = preg_replace("/[\.,:;\?!\'\"]/", $replacement, $str);
        
        return preg_replace("/[-][^(\w)]/", $replacement, $afterRemovingPurnaBiram);

        //$str = str_replace(array('\''),'',$str);
       // return str_replace(array('.',',','?','!','\"',':',';'), '', $str);
    }

    /**
     * General Purpose function to replace supplied value
     * 
     */
    
    private function remove($str,$replace,$replaceWith=''){
        return str_replace($replace, $replaceWith, $str);
    }
}