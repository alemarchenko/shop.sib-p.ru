<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectStemmer
 * realize word stemmer alhorithm
 * @category module wtc.easydirect CreateBanners
 */
class CEDirectStemmer
{
    private $vowel = "";
    private $regexPerfectiveGerunds = array();
    private $regexAdjective = "";
    private $regexParticiple = array();
    private $regexReflexives = "";
    private $regexVerb = array();
    private $regexNoun = "";
    private $regexSuperlative = "";
    private $regexDerivational = "";
    private $regexI = "";
    private $regexNN = "";
    private $regexSoftSign = "";

    private $replaceNN="";
    private $endingsAYA="";    
    
    private $regexPredlogs="";
    
    private $word = '';
    private $RV = 0;
    private $R2 = 0;
    
    private $encoding="UTF-8";
    
    /**
     * constructor SET main params from Lang File
     */
    public function __construct() {
        $this->encoding=(EDIRECT_UTFSITE?"UTF-8":"cp1251");
        
        IncludeModuleLangFile(__FILE__);
        $this->vowel = GetMessage('EDIRECT_CSTEMMER_vowel');
        $this->regexPerfectiveGerunds = array(
            GetMessage('EDIRECT_CSTEMMER_regexPerfectiveGerunds_1'),
            GetMessage('EDIRECT_CSTEMMER_regexPerfectiveGerunds_2')
        );
        $this->regexAdjective = GetMessage('EDIRECT_CSTEMMER_regexAdjective');
        $this->regexParticiple = array(
            GetMessage('EDIRECT_CSTEMMER_regexParticiple_1'),
            GetMessage('EDIRECT_CSTEMMER_regexParticiple_2')
        );
        $this->regexReflexives = GetMessage('EDIRECT_CSTEMMER_regexReflexives');
        $this->regexVerb = array(
            GetMessage('EDIRECT_CSTEMMER_regexVerb_1'),
            GetMessage('EDIRECT_CSTEMMER_regexVerb_2')
        );
        $this->regexNoun = GetMessage('EDIRECT_CSTEMMER_regexNoun');
        $this->regexSuperlative = GetMessage('EDIRECT_CSTEMMER_regexSuperlative');
        $this->regexDerivational = GetMessage('EDIRECT_CSTEMMER_regexDerivational');
        $this->regexI = GetMessage('EDIRECT_CSTEMMER_regexI');
        $this->regexNN = GetMessage('EDIRECT_CSTEMMER_regexNN');
        $this->regexSoftSign =GetMessage('EDIRECT_CSTEMMER_regexSoftSign');
        
        $this->replaceNN=GetMessage('EDIRECT_CSTEMMER_replaceNN');
        $this->endingsAYA=GetMessage('EDIRECT_CSTEMMER_endingsAYA');
        
        $this->regexPredlogs=GetMessage('EDIRECT_CSTEMMER_regexPredlogs');
    }    
    
    /**
     * get differen word in array between two prases, return all words not found in compare Phrases
     * 
	 * @param string $mainPhrase       main phrase
	 * @param string or array $comparePhrase      compare Phrases
	 * @return array     all words not found in compare Phrases
     */
    public function getDifferentWords($mainPhrase,$comparePhrase)
    {
        $arDifferent=array();
        if(!is_array($comparePhrase)) $comparePhrase=array($comparePhrase);
        
        $mainPhrase=$this->getStringWords($mainPhrase);        
        foreach ($comparePhrase as $phrase) {
            $phrase=$this->getStringWords($phrase);
            foreach ($phrase as $searchWord) {
                $find=0;
                foreach ($mainPhrase as $wordMain) {
                    if($wordMain["WORDBASE"]==$searchWord["WORDBASE"]){
                        $find=1;
                        break;
                    }
                }
                if(!$find) $arDifferent[]=$searchWord["WORD"];
            }
        }
        
        return array_unique($arDifferent);
    }
    
    /**
     * get array of words by Phrase with WORDBASE passed through stemmer
     *
     * @param string $str       phrase
     * @return array     array(array("WORDBASE","WORD"),...)
     */    
    private function getStringWords($str)
    {
        $arReturn=array();
        $ar=explode(" ",$str);
        foreach ($ar as $val){
            $val=trim($val);
            if(strlen($val)<1) continue;
            //if predlog
            if(preg_match("/".$this->regexPredlogs."/",$val)) continue;
            $arReturn[]=array(
                "WORDBASE"=>$this->getWordBase($val),
                "WORD"=>$val
            );
        }        
        return $arReturn;
    }

    /**
     * get WORDBASE passed through stemmer
     *
     * @param string $word       word
     * @return string     WORDBASE
     */    
    public function getWordBase($word)
    {
        $this->word = $word;
        $this->findRegions();
        //Step 1
        //find PERFECTIVE GERUND. Delete if isset
        if (!$this->removeEndings($this->regexPerfectiveGerunds, $this->RV)) {
            //find to delete REFLEXIVE
            $this->removeEndings($this->regexReflexives, $this->RV);
            //find to delete ADJECTIVAL, VERB, NOUN
            if (!($this->removeEndings(
                    array(
                        $this->regexParticiple[0] . $this->regexAdjective,
                        $this->regexParticiple[1] . $this->regexAdjective
                    ),
                    $this->RV
                ) || $this->removeEndings($this->regexAdjective, $this->RV))
            ) {
                if (!$this->removeEndings($this->regexVerb, $this->RV)) {
                    $this->removeEndings($this->regexNoun, $this->RV);
                }
            }
        }
        //Step 2
        $this->removeEndings($this->regexI, $this->RV);
        //find to delete DERIVATIONAL
        $this->removeEndings($this->regexDerivational, $this->R2);
        if ($this->removeEndings($this->regexNN, $this->RV)) {
            $this->word .= $this->replaceNN;
        }
        //find to delete SUPERLATIVE
        $this->removeEndings($this->regexSuperlative, $this->RV);
        $this->removeEndings($this->regexSoftSign, $this->RV);

        return $this->word;
    }

    /**
     * remove endings by regex
     *
     * @param string $regex    
     * @param string $region   
     * @return bolean
     */    
    public function removeEndings($regex, $region)
    {
        $prefix = mb_substr($this->word, 0, $region, $this->encoding);
        $word   = mb_substr($this->word,strlen($prefix),$this->encoding);
        if (is_array($regex)) {
            if (preg_match('/.+'. $this->endingsAYA . $regex[0] . '/', $word)) {
                $this->word = $prefix . preg_replace('/' . $regex[0] . '/', '', $word);
                return true;
            }
            $regex = $regex[1];
        }
        if (preg_match('/.+' . $regex . '/', $word)) {
            $this->word = $prefix . preg_replace('/' . $regex . '/', '', $word);
            return true;
        }

        return false;
    }

    private function findRegions()
    {
        $state = 0;
        $wordLength = mb_strlen($this->word, $this->encoding);
        for ($i = 1; $i < $wordLength; $i++) {
            $prevChar = mb_substr($this->word, $i - 1, 1, $this->encoding);
            $char     = mb_substr($this->word, $i, 1, $this->encoding);
            switch ($state) {
                case 0:
                    if ($this->isVowel($char)) {
                        $this->RV = $i + 1;
                        $state    = 1;
                    }
                    break;
                case 1:
                    if ($this->isVowel($prevChar) && !$this->isVowel($char)) {
                        $state    = 2;
                    }
                    break;
                case 2:
                    if ($this->isVowel($prevChar) && !$this->isVowel($char)) {
                        $this->R2 = $i + 1;
                        return;
                    }
                    break;
            }
        }
    }

    private function isVowel($char)
    {
        return (mb_substr($this->vowel, $char,null,$this->encoding) !== false);
    }
}
?>