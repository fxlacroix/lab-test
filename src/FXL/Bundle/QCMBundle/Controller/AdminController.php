<?php

namespace FXL\Bundle\QCMBundle\Controller;

use FXL\Component\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController
{
     /**
     * refresh the tree
     *
     * @Route("/admin/ajax/qcm/tree/refresh", name="admin_ajax_qcm_tree_refresh")
     */
    public function refreshAction()
    {
        $entities = $this->getRepository("FXLQCMBundle:QCM")->findByUser($this->getUser());

        return $this->render("FXLMagicBundle:Component:node.html.twig", array(
            "entities"   => $entities,
            "level"      => 0
        ));
    }

    /**
     * @Route("/admin/qcm", name="fxl_admin_qcm_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

     /**
     * @Route("/admin/tree")
     * @Template()
     */
    public function treeAction()
    {



        return array();
    }
}
