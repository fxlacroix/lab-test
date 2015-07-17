<?php

namespace FXL\Bundle\LiteracyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Folder controller.
 *
 * @Route("admin/folder")
 */
class FolderController extends Controller
{
    /**
     * @Route("/index", name="lab_folder_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return array(
            "nodes" => $user->getNodes()
        );
    }

    /**
     * @Route("/sheet", name="lab_folder_sheet")
     * @Template()
     */
    public function sheetAction($groupId)
    {
        $user = $this->getUser();

        $sheets = $this->getRepository("FXLLiteracyBundle:Sheet")->findSheetsOrdered($groupId);

        return array(
            "sheets" => $sheets
        );
    }
   /**
     * @Route("/list/level/{level}", name="lab_folder_list")
     * @Route("/list", name="lab_folder_list")
     * @Template()
     */
    public function listAction($level=0)
    {
        $user = $this->getUser();

        return array(
            "nodes" => $user->getNodes(),
            "level" => $level
        );
    }

    /**
     * @Route("/group", name="lab_folder_group")
     * @Template()
     */
    public function groupAction()
    {
        return array(
            "nodes" => $user->getNodes()
        );
    }
}
