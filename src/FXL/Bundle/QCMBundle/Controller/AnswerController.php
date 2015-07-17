<?php

namespace FXL\Bundle\QCMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\QCMBundle\Entity\Answer;
use FXL\Bundle\QCMBundle\Form\AnswerType;

/**
 * Answer controller.
 *
 * @Route("/admin/ajax/qcm/answer")
 */
class AnswerController extends Controller
{
    /**
     * Lists all Answer entities.
     *
     * @Route("/", name="admin_ajax_qcm_answer")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLQCMBundle:Answer')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Answer entity.
     *
     * @Route("/{id}/show", name="admin_ajax_qcm_answer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Answer entity.
     *
     * @Route("/new", name="admin_ajax_qcm_answer_new")
     * @Route("/question/{questionId}/new", name="admin_ajax_qcm_answer_question_new")
     * @Template()
     */
    public function newAction($questionId=null)
    {
        $entity = new Answer();
        if($questionId){
            $question = $this->getDoctrine()->getRepository("FXLQCMBundle:Question")->find($questionId);

            $entity->setQuestion($question);
        }
        $form   = $this->createForm(new AnswerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Answer entity.
     *
     * @Route("/create", name="admin_ajax_qcm_answer_create")
     * @Method("POST")
     * @Template("FXLQCMBundle:Answer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Answer();
        $form = $this->createForm(new AnswerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_qcm_answer_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Answer entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_qcm_answer_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm = $this->createForm(new AnswerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Answer entity.
     *
     * @Route("/{id}/update", name="admin_ajax_qcm_answer_update")
     * @Method("POST")
     * @Template("FXLQCMBundle:Answer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AnswerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_qcm_answer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Answer entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_qcm_answer_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLQCMBundle:Answer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_qcm_answer'));
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
