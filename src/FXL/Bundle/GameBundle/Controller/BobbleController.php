<?php

namespace FXL\Bundle\GameBundle\Controller;

use FXL\Bundle\GameBundle\Entity\Maze;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BobbleController extends Controller
{
    /**
     * @Route("/bobble")
     * @Template()
     */
    public function indexAction()
    {
        $maze = new Maze(20, 20);
        $maze->generate();

        return array(
            'maze' => $maze
        );
    }
}
