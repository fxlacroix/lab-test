<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class PhoneticCrawler extends BaseCrawler
{
    /**
     *
     * @var string
     */
    var $url     = "http://fr.wiktionary.org/wiki/%s";

    /**
     *
     * @var string
     */
    var $pattern = '<span class="API" title="prononciation API">/(.*)/</span>';

    /**
     *
     * @var string
     */
    //var $postPattern = '<span class="API" title="prononciation API">/(.*)/</span>';

    /**
     *
     * @var array function
     */
    var $filters = array();

    /**
     * save word
     *
     * @param Word $word
     * @param string $name
     * @param array $results
     */
    public function saveWord($word, $name, $results) {

        $em = $this->getEntityManager();
        $word->setPhonetic($results[0]);

        $em->persist($word);
        $em->flush();

        return $word;
    }

    public function parseRequest($response) {

        $parsed = parent::parseRequest($response);

        return array(array_shift($parsed));
    }

    public function crawl($name)
    {
        $this->name = $name;

        $word = $this->getWord($name);


        if(! $word->getPhonetic()){

            $response = $this->getResponse();

            $results = $this->parseRequest($response);

            $word = $this->saveWord($word, $name, $results);
        }

        return $word->getPhonetic();
    }

    public function getResponse() {

        $o = new \FXL\Component\Request\Curl($this->getRequestUrl());

        return $o->createCurl()->__tostring();
    }
}