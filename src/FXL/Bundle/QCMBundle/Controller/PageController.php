<?php

namespace FXL\Bundle\QCMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Response;

use FXL\Bundle\QCMBundle\Entity\QCM;
use FXL\Bundle\QCMBundle\Entity\Node;
use FXL\Bundle\QCMBundle\Form\ReviewType;
use FXL\Bundle\QCMBundle\Form\PrepareType;

class PageController extends Controller
{
    /**
     * @Route("/qcm", name="fxl_qcm_index")
     * @Template()
     */
    public function indexAction($page=1)
    {
        $qcm = $this->getDoctrine()
            ->getRepository("FXLQCMBundle:QCM")
            ->findOneBy(array("published" => true), array("updatedAt" => "desc"), 0, 1);

        $qcms = $this->getDoctrine()
            ->getRepository("FXLQCMBundle:QCM")
            ->findBy(array("published" => true));

        $reviews = $this->getDoctrine()
            ->getRepository("FXLQCMBundle:Review")
            ->findBy(array(), array('updatedAt'=>'desc'), 7);

        if(null === $qcms){

            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        array_pop($qcms);

        $adapter = new ArrayAdapter($qcms);
        $qcmFanta = new Pagerfanta($adapter);
        $qcmFanta->setMaxPerPage(6);

        try {
            $qcmFanta->setCurrentPage($page);

        } catch (NotValidCurrentPageException $e) {

            throw new NotFoundHttpException();
        }

        $view = new TwitterBootstrapView();
        $qcmLink = $view->render($qcmFanta, function($page) {
            return '/qcm/page/'.$page;
        }, array(
            'proximity' => 1,
            'next_message'  => "Suivant →",
            'prev_message'  => "← Précédent",
        ));

        return array(
            "qcm"           => $qcm,
            "pager"         => $qcmFanta,
            "pagerLink"     => $qcmLink,
            'reviews'   => $reviews
        );
    }

    /**
     *
     * @Route("/qcm/{slug}/review")
     * @Route("/qcm/{slug}/exam", name="fxl_qcm_exam")
     * @Route("/qcm/{slug}/review/prepare", name="fxl_qcm_review_prepare")
     *
     * @Template()
     */
    public function prepareAction($slug = null)
    {
        $qcm  = $this->getDoctrine()
            ->getRepository("FXLQCMBundle:QCM")
            ->findOneBySlug($slug);

        if(!$qcm){
            $this->redirect($this->generateUrl('fxl_qcm_index'));
        }

        $review = new \FXL\Bundle\QCMBundle\Entity\Review();
        $review->setQcm($qcm);

        $reviewForm = $this->createForm(new PrepareType(), $review);

        if ($this->getRequest()->getMethod()=='POST') {
            $reviewForm->bind($this->getRequest());

            if($reviewForm->isValid()){

                $questions = (array) $qcm->findAllQuestions();
                shuffle($questions);

                $questions = array_slice($questions, 0, $review->getSelectionQuestion());

                foreach($questions as $question){

                    $review->addQuestion($question);
                }

                $now = new \DateTime("NOW");
                //$node->setName(" );
                $review->setQcm($qcm);


                $em = $this->getDoctrine()->getManager();
                $em->persist($review);
                $em->flush();

                return $this->redirect($this->generateUrl('fxl_qcm_review', array('slug' => $review->getSlug())));
            }
        }

        return array(
            "qcm"   => $qcm,
            "review"=> $review,
            "form"  => $reviewForm->createView()
        );
    }

    /**
     * @Route("/qcm/review/{slug}", name="fxl_qcm_review")
     * @Template()
     */
    public function reviewAction($slug = null)
    {

        $review  = $this->getDoctrine()
           ->getRepository("FXLQCMBundle:Review")
           ->findOneBySlug($slug);



        if(null === $review){
            $this->createNotFoundException();
        }

        if($review->getIsCorrected()){

            return $this->redirect($this->generateUrl("fxl_qcm_review_emend", array('slug'=>$review->getSlug())));
        }

        /*$questions = array();
        foreach($review->getQuestions() as $question){

            $answers = (array) $question->findAllAnswers();
            shuffle($answers);
            $question->setAnswers($answers);
            $questions[] = $question;
        }
        $review->setQuestions($questions);
        */
        $reviewForm = $this->createForm(new ReviewType(), $review);

        return array(
            'reviewForm'    => $reviewForm->createView(),
            'review'        => $review
        );
    }

    /**
     * correct  and send email
     *
     * @Route("/qcm/review/{slug}/emend", name="fxl_qcm_review_emend")
     * @Template()
     */
    public function emendAction($slug)
    {
        $all = $this->getRequest()->request->all();

        $review  = $this->getDoctrine()
           ->getRepository("FXLQCMBundle:Review")
           ->findOneBy(array(
               'slug'         => $slug
           ));

        if(!$review){
            $this->createNotFoundException();
        }

        if($review->getIsCorrected()){

            return $this->redirect($this->generateUrl('fxl_qcm_review_prepare', array(
                'slug' => $review->getQCM()->getSlug()
            )));
        }

        $reviewForm = $this->createForm(new ReviewType(), $review);
        $reviewForm->bind($this->getRequest());

        if($reviewForm->isValid()){

            $review = $reviewForm->getData();

            $review->setIsCorrected(true);
            $review->collectAnswer();
            $review->emend();

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);

            $em->flush();

            if($review->getEmail()){

                $this->sendResultEmail($review);
            }
        }

        return new Response();
    }

    public function sendResultEmail($review){

        $now = new \DateTime("now");
        $mailer = $this->get('mailer');
        $twig   = $this->get('twig');

        $body   = $twig->render('FXLQCMBundle:Page:resume.html.twig', array(
            'review' => $review
        ));

        $message = \Swift_Message::newInstance()

        ->setSubject("[Blogart-Magazine] " . $review->getQCM()->getName()." fait le ".$now->format("d/m/y") . " à ".$now->format("g:i"))
        ->setFrom('blogart@free.fr')
        ->setTo($review->getEmail())
        ->setBody($body)
        ->addPart($body, 'text/html');

        $mailer->send($message);
    }

}
