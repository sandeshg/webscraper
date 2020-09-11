<?php
namespace App\Sandesh\TextAnalysis\Analysis;

class FrequencyDistribution
{
    protected $keyValues = array();
    protected $totalTokens = null;
    protected $keysByWeight = [];

    public function __construct(array $tokens)
    {
        $this->keyValues = array_count_values($tokens);
        arsort($this->keyValues);        
        $this->totalTokens = count($tokens);
    }

    public function getTotalTokens()
    {
        return $this->totalTokens;
    }

    public function getKeys()
    {
        return array_keys($this->keyValues);
    }

    public function getKeyValuesByFrequency()
    {
        return $this->keyValues;
    }

    public function getWeightPerToken()
    {
        return 1 / $this->getTotalTokens();
    }

    public function getKeyValuesByWeight()
    {
        if(empty($this->keysByWeight)) {
            $weightPerToken = $this->getWeightPerToken();
            //make a copy of the array
            $keyValuesByWeight = $this->keyValues;
            array_walk($keyValuesByWeight, function(&$value, $key, $weightPerToken) {
                $value /= $weightPerToken;
            }, $this->totalTokens);
            
            $this->keysByWeight = $keyValuesByWeight;
        }
        return $this->keysByWeight;
    }

    public function __destruct() 
    {
        unset($this->keyValues);
        unset($this->totalTokens);
        unset($this->keysByWeight);
    }
}