<?php

namespace FXL\Bundle\LiteracyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Lab controller.
 *
 * @Route("admin/lab")
 */
class LabController extends Controller
{
    /**
     * @Route("/index", name="lab_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/analyse/corpus", name="lab_analyse_corpus")
     */
    public function analyseAction()
    {
         $s = $this->getRequest()->request->get("text");
         $explode = explode(" ", $s);

         $sum = array();
         foreach($explode as $word) {

             $word = strtolower($word);

             if($word != "" && strlen($word) > 6) {

                if(! isset($sum[strtolower($word)])){
                    $sum[$word] = 0;
                }

                 $sum[$word] ++;
             }

         }
        asort($sum);

        $sum = array_reverse($sum);

         foreach($sum as $key => $sumValue) {

             if($sumValue < 2){

                 unset($sum[$key]);
             }
         }

        return new JsonResponse($sum);
    }

    /**
     * @Route("/analysis/{name}", name="lab_analysis")
     */
    public function analysisAction($name="")
    {
         $s = "";/*$this->get("fxl_doc.crawler.rhyme")
            ->crawl($name);*/

        return new Response($s);
    }

    /**
     * @Route("/anagram/{name}", name="lab_anagram")
     */
    public function anagramAction($name="")
    {
         $words = $this->get("fxl_doc.crawler.anagram")
            ->crawl($name);

        return new JsonResponse((array)$words);
    }

    /**
     * @Route("/rhyme/{name}", name="lab_rhyme")
     */
    public function rhymeAction($name="")
    {
         $words = $this->get("fxl_doc.crawler.rhyme")
            ->crawl($name);

        return new JsonResponse((array)$words);
    }

    /**
     * @Route("/synonym/{name}", name="lab_synonyme")
     */
    public function synonymAction($name="")
    {
        $words = $this->get("fxl_doc.crawler.synonym")
            ->crawl($name);

        return new JsonResponse((array)$words);
    }

    /**
     * @Route("/image/{name}", name="lab_image")
     */
    public function imageAction($name="")
    {
        $s = $this->get("fxl_doc.crawler.google.image")
            ->crawl($name);

        return new Response($s);
    }

    /**
     * @Route("/definition/{name}", name="lab_definition")
     */
    public function definitionAction($name="")
    {
        $s = $this->get("fxl_doc.crawler.definition")
            ->crawl($name);

        return new Response($s);
    }

    /**
     * @Route("/scrabble/{name}", name="lab_scrabble")
     */
    public function scrabbleAction($name="")
    {
        $words =  $this->get("fxl_doc.crawler.scrabble")
            ->crawl($name);

        return new JsonResponse((array)$words);
    }

    /**
     * @Route("/phonetic/{name}", name="lab_phonetic")
     */
    public function phoneticAction($name="")
    {
        $s = $this->get("fxl_doc.crawler.phonetic")
            ->crawl($name);

        //$s = \FXL\Component\Literacy\Phonetic\Soundex::phonetic($name);

        //$o = new \FXL\Component\Literacy\Word\Syllable(strtolower($s));

        return new Response($s);
    }

    /**
     * @Route("/wikipedia/list", name="lab_wikipedia_list")
     */
    public function wikipediaListAction()
    {
        $name = $this->getRequest()->query->get("query");

        $s = $this->get("fxl_doc.crawler.wikipedia")
            ->getList($name);

        //$s = \FXL\Component\Literacy\Phonetic\Soundex::phonetic($name);

        //$o = new \FXL\Component\Literacy\Word\Syllable(strtolower($s));

        $return = array();

        if(isset($s[0]) && isset($s[1])){

            $return['query'] = $s[0];
            $return['suggestions'] = $s[1];
        }

        return new JsonResponse($return);
    }


    /**
     * @Route("/wikipedia/{formule}", name="lab_wikipedia")
     */
    public function wikipediaAction($formule="")
    {
        $s = $this->get("fxl_doc.crawler.wikipedia")
            ->getPage($formule);


        //$s = \FXL\Component\Literacy\Phonetic\Soundex::phonetic($name);

        //$o = new \FXL\Component\Literacy\Word\Syllable(strtolower($s));

//        $s = "blabla".$s;

        $result = array();

        if($s){

            $titles = preg_split("#<h2>#", $s);
            $done = false;

            foreach($titles as $key => $title){

                $sub = preg_split("#</h2>#", $title);

                if(isset($sub[0]) && isset($sub[1])){

                    $result[$key]['label'] = $sub[0];
                    $result[$key]['value'] = $sub[1];
                }
                elseif(isset($sub[0]) && !$done){

                    $result[$key]['label'] = "Préambule";
                    $result[$key]['value'] = $sub[0];
                    $done = true;
                }
            }
        }

/*        if($titles[0] != ""){
             $result[0] = $title[0];
        }*/

        return new JsonResponse($result);
    }


    /**
     * @Route("/feet/{sentence}", name="lab_feet")
     */
    public function feetAction($sentence="")
    {
        $o = new \FXL\Component\Literacy\Sentence\Sentence($sentence);

        return new Response($o->countFeet()." (".$o->getStringFeet().")");
    }

    /**
     * @Route("/syllable/{name}", name="lab_syllable")
     */
    public function syllableAction($name="")
    {
        $o = new \FXL\Component\Literacy\Word\Syllable($name);

        return new Response($o->getSyllables());
    }

}
