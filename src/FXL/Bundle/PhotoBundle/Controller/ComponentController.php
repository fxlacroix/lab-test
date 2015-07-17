<?php

namespace FXL\Bundle\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ComponentController extends Controller
{
    /**
     * @Template()
     */
    public function menuAction($routeAttributes)
    {
        $menu = array(
            array(
                "label" => "Carnets de voyage",
                "routeName" => "photo_user_list",
                "patterns" => array("list"),
            ),
            /* array(
              "label"     =>  "Tirages",
              "routeName" =>  "photo_user_print",
              "patterns"  =>  array("print"),
              ), */
            array(
                "label" => "Contact",
                "routeName" => "photo_user_contact",
                "patterns" => array("contact"),
            )
        );

        $content = $this->renderView(
            'FXLMagicBundle:Common:navbar.html.twig',
            array(
                "menu" => $menu,
                "routeAttributes" => $routeAttributes,
                'userName' => 'michel-royo',
                'blogName' => 'Michel Royo',
                'items' => $menu
            )
        );

        return new Response($content);
    }
}
