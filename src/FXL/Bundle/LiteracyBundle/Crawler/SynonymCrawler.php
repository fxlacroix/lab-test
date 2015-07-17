<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class SynonymCrawler extends BaseCrawler
{
    /**
     *
     * @var string
     */
    var $url     = "http://dico.isc.cnrs.fr/dico/fr/chercher?r=%s&msend=Envoyer";

    /**
     *
     * @var string
     */
    var $pattern = "A HREF=chercher\?r=([^>]*)";

    /**
     *
     * @var array function
     */
    var $filters = array("rawurldecode", "utf8_encode");

    /**
     *
     * @var array
     */
    var $postTreatment = array("arsort");

    public function getRequestUrl() {

        $this->name = utf8_decode($this->name);

        return parent::getRequestUrl();
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

        $word->check("synonym");

        foreach($results as $synonymName){

            $synonym = $this->getWord($synonymName);
            $word->addSynonym($synonym);

            $em->persist($synonym);
            $em->flush();

            unset($synonym);
        }

        $em->persist($word);
        $em->flush();

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