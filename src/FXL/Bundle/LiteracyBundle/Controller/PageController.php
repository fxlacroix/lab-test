<?php

namespace FXL\Bundle\LiteracyBundle\Controller;

use FXL\Bundle\MagicBundle\Controller\BaseController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Literacy controller.
 *
 * @Route("/literacy")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="home_literacy")
     * @Route("/page/{page}", name="home_page")
     * @Template()
     */
    public function indexAction($page=1)
    {
        $em = $this->getDoctrine()->getManager();

        $literacies = $em->getRepository("FXLLiteracyBundle:Group")
            ->findLastGroups();

        $all = $em->getRepository("FXLLiteracyBundle:Group")
            ->findLastGroups(false);

        $adapter = new ArrayAdapter($literacies);
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
            "all"           => $all,
            "literacies"    => $literacies,
            "pager"         => $pagerfanta,
            "pagerLink"     => $pagerLink
        );
    }

     /**
     * @Route("/{slug}", name="details_literacy_node")
     * @Template()
     */
    public function detailsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $group = $em->getRepository("FXLLiteracyBundle:Group")
            ->findOneBySlug($slug);

        $breadcrumb = array(
            array(
                "name"      => "littérature",
                "routeName" => "home_literacy"
            ),
             array(
                "name"      => $group->getName(),
                "routeName" => null
            ),
        );

        $tags = array(
            array(
                "name"      => $group->getNode()->getName()
            ),
        );

        if($group->getNode()->getParent()){
            $tags[] = array(
                "name"      => $group->getNode()->getParent()->getName()
            );
        }

        $all = $em->getRepository("FXLLiteracyBundle:Group")
            ->findLastGroups(false);


        return array(
            "group"         => $group,
            "breadcrumb"    => $breadcrumb,
            "tags"          => $tags,
            "all"           => $all
        );
    }

}
