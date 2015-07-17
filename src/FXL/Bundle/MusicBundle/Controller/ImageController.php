<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use FXL\Bundle\MusicBundle\HttpFoundation\JsonResponse;
/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends Controller
{

    /**
     * @Template()
     * @Route("/logo/project/{projectId}/random", name="image_logo_project")
     * @Route("/logo/project/{projectId}/track/{trackId}/random", name="image_logo_track")
    */
    public function logoRandomAction($projectId=null, $trackId=null) {

        $m = new \Memcache();
        $m->connect("127.0.0.1");

        $track = $project = null;

        if($trackId){

            $nomenclature = "fxlmusic_assets_track_".$trackId;

            $track = $this->getDoctrine()->getEntityManager()
                ->getRepository("FXLMusicBundle:Track")
                ->find($trackId);

            if(! $track){

              $nomenclature = "fxlmusic_assets_project_".$projectId;

              $project = $this->getDoctrine()->getEntityManager()
                    ->getRepository("FXLMusicBundle:Project")
                    ->find($projectId);

            }

        }elseif($projectId) {

            $nomenclature = "fxlmusic_assets_project_".$projectId;

            $project = $this->getDoctrine()->getEntityManager()
                ->getRepository("FXLMusicBundle:Project")
                ->find($projectId);
        }



        $assets = $m->get($nomenclature);

        if(false === $assets){


            $assets = $this->getDoctrine()->getEntityManager()
                ->getRepository("FXLMusicBundle:Asset")
                ->findAllResursive($project, $track);


            $m->set($nomenclature, $assets, 0, 600);
        }

        if(! $assets) return new Response();

        $rand = array_rand($assets);

        $path = $this->get('kernel')->getRootDir() . '/../web/';
        $path .= $assets[$rand]['path'];

        $extension = mime_content_type($path);
        $response = new Response(file_get_contents($path));
        $response->headers->set('Content-Type', $extension);

        return $response;
    }

}
