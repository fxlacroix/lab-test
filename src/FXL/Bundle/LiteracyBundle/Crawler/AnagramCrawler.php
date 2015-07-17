<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class AnagramCrawler extends BaseCrawler
{

    /**
     *
     * @var string
     */
    var $url     = "http://1001anagrammes.com/anagramme.php?letter=%s&bouton=Ok";

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
    var $filters = array("utf8_encode", "strip_tags");

    /**
     *
     * @var array
     */
    var $postTreatment = array("rsort");


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

        $word->check("anagram");

        foreach($results as $anagramName){

            $anagram = $this->getWord($anagramName);

            $word->addAnagram($anagram);

            $em->persist($anagram);
            $em->flush();

            unset($anagram);
        }

        $em->persist($word);
        $em->flush();
    }

}