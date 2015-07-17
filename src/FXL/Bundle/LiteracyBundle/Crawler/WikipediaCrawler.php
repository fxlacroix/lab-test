<?php
namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class WikipediaCrawler extends BaseCrawler
{



    public function getList($name){

        $o = new \FXL\Component\Request\Curl("http://fr.wikipedia.org/w/api.php?action=opensearch&search=".urlencode(utf8_decode($name)));

        return json_decode($o->createCurl());

    }

    public function getPage($formule){

        $wikipedia = $this->getRepository("FXLLiteracyBundle:Wikipedia")->findByName($formule);
        $return = null;

       if( null !== $wikipedia && $wikipedia instanceof \FXL\Bundle\LiteracyBundle\Entity\Wikipedia){

            $return = $wikipedia->getDefinition();
       }else {

            $o = new \FXL\Component\Request\Curl("http://fr.wikipedia.org/w/api.php?action=query&titles=".urlencode(utf8_decode($formule))."&format=json&prop=extracts&redirects=true");
            $o = json_decode($o->createCurl());

            if(isset($o->query) && isset($o->query->pages)) {


                $tmp1 = (array) $o->query->pages;
                $tmp2 = (array) array_shift($tmp1);

                if(isset($tmp2['extract'])){

                    $return = $tmp2['extract'];
                    $wikipedia = new \FXL\Bundle\LiteracyBundle\Entity\Wikipedia;
                    $wikipedia->setName($formule);
                    $wikipedia->setPageId($tmp2['pageid']);
                    $wikipedia->setDefinition($return);
                    $this->em->persist($wikipedia);
                    $this->em->flush();
                }

           }

        }
        return $return;

    }
}
