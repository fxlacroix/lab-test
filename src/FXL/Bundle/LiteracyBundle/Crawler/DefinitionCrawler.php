<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class DefinitionCrawler extends BaseCrawler
{
    /**
     *
     * @var string
     */
    var $url     = "http://www.larousse.fr/dictionnaires/francais/%s";

    /**
     *
     * @var string
     */
    var $pattern = '<li class="DivisionDefinition">(.*)</li>';

    /**
     * save word
     *
     * @param Word $word
     * @param string $name
     * @param array $results
     */
    public function saveWord($word, $name, $results) {

        $em = $this->getEntityManager();

        $word->check("definition");

        $definition = new \FXL\Bundle\LiteracyBundle\Entity\Definition();
        $definition->setContent($this->getResponseAsHtmlList($results));
        $definition->setSource("larousse");

        $word->addDefinition($definition);

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

        return $return->first();
    }
}
