<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\View\TwitterBootstrapView;

class PageController extends Controller
{
   /**
    *  @Route("/music", name="home_music")
     * @Route("/page/{page}", name="home_page")
     * @Template()
     */
    public function indexAction($page=1)
    {
        $random = null;

        $m = new \Memcache();
        $m->connect("127.0.0.1");

        $lastDocuments = $m->get("fxlmusic_lastDocuments");

        if(false === $lastDocuments){

            $lastDocuments = $this->getDoctrine()->getManager()
                ->getRepository('FXLMusicBundle:Document')
                ->findLastDocuments(10);
            $m->set("fxlmusic_lastDocuments", $lastDocuments, 0, 600);

        }

        $firstTitle = $lastDocuments[0]['track']['project']['id'];


        $dateDoc = $lastDocuments[0]['createdAt'];
        $now = new \DateTime("now");

        $interval = $now->diff($dateDoc);
        $day = $interval->format('%d');

        if($day > 15){

             $randomAll = $this->getDoctrine()->getEntityManager()
                ->getRepository('FXLMusicBundle:Document')
                ->findAll();

             $random = $randomAll[rand(0, count($randomAll) - 1)];

             $randomTitle = $random->getTrack()->getProject()->getId();
        }


        $lastProjects = $m->get("fxlmusic_lastProjects");

        if(false === $lastProjects){

            if($random){
                $firstTitle = $randomTitle;
            }

            $lastProjects = $this->getDoctrine()->getEntityManager()
                ->getRepository('FXLMusicBundle:Project')
                ->findLastProjects($firstTitle);

            $m->set("fxlmusic_lastProjects", $lastProjects, 0, 600);
        }



        $adapter = new ArrayAdapter($lastProjects);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(6);

        try {
            $pagerfanta->setCurrentPage($page);

        } catch (NotValidCurrentPageException $e) {

            throw new NotFoundHttpException();
        }

        $view = new TwitterBootstrapView();
        $pagerLink = $view->render($pagerfanta, function($page) {
            return '/page/'.$page;
        }, array(
            'proximity' => 1,
            'next_message'  => "Suivant →",
            'prev_message'  => "← Précédent",
        ));


        return array(
            "lastDocuments"  => $lastDocuments,
            "random"         => $random,
            "pager"          => $pagerfanta,
            "pagerLink"      => $pagerLink
        );
    }


     /**
     * @Route("/project/{slug}", name="fxl_music_project")
     * @Template()
     */
    public function projectAction($slug)
    {
        $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")
            ->findOneBySlug($slug);

        $maxVersion = $this->getDoctrine()->getEntityManager()
            ->getRepository('FXLMusicBundle:Project')
            ->getMaxVersion($project);

        return array(
            'project'    =>  $project,
            'maxVersion' => array_shift($maxVersion)
        );
    }

     /**
     * @Route("/projectbook/{slug}", name="project_book")
     * @Template()
     */
    public function projectBookAction($slug)
    {
        $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")
            ->findOneBySlug($slug);

        return array(
            'project'    =>  $project
        );
    }

    /**
     * @Route("/project/{slugProject}/track/{slugTrack}", name="fxl_music_track")
     * @Template()
     */
    public function trackAction($slugProject, $slugTrack)
    {
        $track = $this->getDoctrine()->getRepository("FXLMusicBundle:Track")
            ->findOneBySlug($slugTrack);

        return array(
            'track'     => $track
        );
    }


    /**
     * @Route("/rss", name="rss")
     * @Template()
     */
    public function rssAction()
    {
        $lastDocuments = $this->getDoctrine()->getEntityManager()
            ->getRepository('FXLMusicBundle:Document')
            ->findLastDocuments(10);

        $lastGroups = $this->getDoctrine()->getRepository("FXLLiteracy:Group")
            ->findBy(array(
                "published" => true
              ), array(
                "updatedAt"=>"desc"
              ));

        $lastGroups = array_slice($lastGroups, 0, 10);

        //$this->headers->set('Content-Type', 'application/rss+xml; charset=UTF-8');
        //'Content-Type' => 'application/rss+xml'

        $response = $this->render('FXLMusicBundle:Page:rss.html.twig', array(
            'documents' => $lastDocuments,
            'groups' => $lastGroups,
        ));

        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
        //return array( 'documents' => $lastDocuments);
    }

    /**
     * @Route("/labo", name="laboratory")
     * @Template()
     */
    public function laboAction()
    {
        return array(
        );
    }


    /**
     * @Route("/blog", name="blog")
     * @Template()
     */
    public function blogAction()
    {
        return array(

        );
    }


    public function error404Action()
    {

        return $this->redirect($this->generateUrl('fxl_home'));
    }

}
