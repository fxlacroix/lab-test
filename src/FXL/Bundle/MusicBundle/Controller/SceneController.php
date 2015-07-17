<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Scene;
use FXL\Bundle\MusicBundle\Form\SceneType;

/**
 * Scene controller.
 *
 * @Route("/admin/ajax/scene")
 */
class SceneController extends Controller
{
    /**
     * Lists all Scene entities.
     *
     * @Route("/", name="admin_ajax_scene")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Scene')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Scene entity.
     *
     * @Route("/{id}/show", name="admin_ajax_scene_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Scene')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scene entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Scene entity.
     *
     * @Route("/new", name="admin_ajax_scene_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Scene();
        $form   = $this->createForm(new SceneType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Scene entity.
     *
     * @Route("/create", name="admin_ajax_scene_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Scene:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Scene();



        $actId = $this->getRequest()->query->get("act");

        if($actId) {

            $act = $this->getDoctrine()->getRepository("FXLMusicBundle:Act")->find($actId);
            $entity->setAct($act);
        }

        $form = $this->createForm(new SceneType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_scene_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Scene entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_scene_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Scene')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scene entity.');
        }

        $editForm = $this->createForm(new SceneType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Scene entity.
     *
     * @Route("/{id}/update", name="admin_ajax_scene_update")
     * @Method("POST")
     * @Template("FXLMusicBundle:Scene:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Scene')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scene entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SceneType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_scene_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Scene entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_scene_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Scene')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Scene entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_scene'));
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
