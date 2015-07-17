<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Track;
use FXL\Bundle\MusicBundle\Form\TrackType;

/**
 * Track controller.
 *
 * @Route("/admin/ajax/track")
 */
class TrackController extends Controller
{
    /**
     * Lists all Track entities.
     *
     * @Route("/", name="admin_ajax_track")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Track')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Track entity.
     *
     * @Route("/{id}/show", name="admin_ajax_track_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Track')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Track entity.
     *
     * @Route("/new", name="admin_ajax_track_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Track();
        $projectId = $this->getRequest()->query->get("project");

        if($projectId) {

            $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")->find($projectId);
            $entity->setProject($project);
        }

        $form   = $this->createForm(new TrackType(), $entity, array(

        ));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Track entity.
     *
     * @Route("/create", name="admin_ajax_track_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Track:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $post = $request->request->get("fxl_musicbundle_tracktype");

        $entity  = new Track();
        $form = $this->createForm(new TrackType(), $entity);

        $form->bind($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);

            foreach($entity->getAuthors() as $author) {
                $author->addTrack($entity);
                $em->persist($author);
            }

            /*
            foreach($entity->getTags() as $tag) {
                $tag->addTrack($entity);
                $em->persist($tag);
            }*/

            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_track_edit', array('id' => $entity->getId())));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Track entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_track_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Track')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        $editForm = $this->createForm(new TrackType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Track entity.
     *
     * @Route("/{id}/update", name="admin_ajax_track_update")
     * @Method("POST")
     * @Template("FXLMusicBundle:Track:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Track')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TrackType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);

            foreach($entity->getAuthors() as $author) {
                $author->addTrack($entity);
                $em->flush();
            }
            /*
            foreach($entity->getTags() as $tag) {
                $tag->addTrack($entity);
                $em->persist($tag);
            }
            */

            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_track_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Track entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_track_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Track')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Track entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_track'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id), array(
                'csrf_protection' => false,
            ))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
