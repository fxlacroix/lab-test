<?php

namespace FXL\Bundle\ResumeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ResumeController extends Controller
{
    /**
     * @Route("/resume")
     * @Template()
     */
    public function resumeAction()
    {
        $resume = $this->getDoctrine()
            ->getManager('resume')
            ->getRepository('FXLResumeBundle:Resume')
            ->find(1);
        /*
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.groups', 'g')
            ;
        */

        return array('resume'   => $resume);
    }

}
