<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class ScrabbleCrawler extends BaseCrawler
{
    /**
     *
     * @var string
     */
    var $url = "http://www.dcode.fr/outils/contenant.php";

    /**
     *
     * @var string
     */
    var $pattern = '<li info="1">(.*)</li>';


    var $filters = array("strtolower");

    /**
     *
     * @var array
     */
    var $postTreatment = array("arsort");


    /**
     * save word
     *
     * @param Word $word
     * @param string $name
     * @param array $results
     */
    public function saveWord($word, $name, $results) {

        $em = $this->getEntityManager();

        $word->check("scrabble");

        foreach($results as $scrabbleName){

            $scrabble = $this->getWord($scrabbleName);

            $word->addScrabble($scrabble);

            $em->persist($scrabble);
            $em->flush();

            unset($scrabble);

        }
        $em->persist($word);
        $em->flush();
    }

    public function getResponse() {

        $o = new \FXL\Component\Request\Curl($this->getRequestUrl());

        $o->setPost(array(
            "lettres"   => $this->name,
            "nblettres" => null,
            "desordre"  => 1
        ));

        return $o->createCurl()->__tostring();
    }


    /**
     *
     * @param string$name
     * @return definition object
     */
    public function crawl($name){

        $return = parent::crawl($name);

        return $this->getResponseAsArray($return);
    }
}
