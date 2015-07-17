<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class RhymeCrawler extends BaseCrawler
{

    /**
     *
     * @var string
     */
    var $url     = "http://1001rimes.com/listeperson.php?letter=%s";

    /**
     *
     * @var string
     */
    var $pattern = "width=100%><tr><td>(.*)<center>";

    /**
     *
     * @var string
     */
    var $postPattern = "<br>";

     /**
     *
     * @var array
     */
    var $filters = array("utf8_encode");

    /**
     *
     * @var array
     */
    var $postTreatment = array("arsort");

    public function getRequestUrl() {

        $o = new \FXL\Component\Literacy\Word\Syllable($this->name);

        $syllables = $o->getArraySyllables();

        $rime = array_pop($syllables);

        if($rime[strlen($rime)-1] == "e"){

            $last = array_pop($syllables);
            $rime = $last.$rime;
        }

        return sprintf($this->url, $rime);//.sprintf($this->params, $this->paramUrl);
    }


    public function crawl($name)
    {
        $results = parent::crawl($name);

        return $this->getResponseAsArray($results);

    }


    /**
     * save word
     *
     * @param Word $word
     * @param string $name
     * @param array $results
     */
    public function saveWord($word, $name, $results) {

        $em = $this->getEntityManager();

        $word->check("rhyme");

        foreach($results as $rhymeName){

            $rhyme = $this->getWord($rhymeName);

            $word->addRhyme($rhyme);

            $em->persist($rhyme);
            $em->flush();

            unset($rhyme);
        }

        $em->persist($word);
        $em->flush();
    }

}