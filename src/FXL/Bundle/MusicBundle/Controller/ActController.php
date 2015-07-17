<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Act;
use FXL\Bundle\MusicBundle\Form\ActType;

/**
 * Act controller.
 *
 * @Route("/admin/ajax/act")
 */
class ActController extends Controller
{
    /**
     * Lists all Act entities.
     *
     * @Route("/", name="admin_ajax_act")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Act')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Act entity.
     *
     * @Route("/{id}/show", name="admin_ajax_act_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Act')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Act entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Act entity.
     *
     * @Route("/new", name="admin_ajax_act_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Act();

         $projectId = $this->getRequest()->query->get("project");

        if($projectId) {

            $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")->find($projectId);
            $entity->setProject($project);
        }

        $form   = $this->createForm(new ActType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Act entity.
     *
     * @Route("/create", name="admin_ajax_act_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Act:new.html.twig")
     */
    public function createAction(Request $request)
    {
         $entity  = new Act();

        $form = $this->createForm(new ActType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_act_edit', array('id' => $entity->getId())));
        }else{

            die($form->getErrorsAsString());
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Act entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_act_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Act')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Act entity.');
        }

        $editForm = $this->createForm(new ActType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Act entity.
     *
     * @Route("/{id}/update", name="admin_ajax_act_update")
     * @Method("POST")
     * @Template("FXLMusicBundle:Act:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Act')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Act entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ActType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_act_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Act entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_act_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Act')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Act entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_act'));
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
