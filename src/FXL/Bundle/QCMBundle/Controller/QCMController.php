<?php

namespace FXL\Bundle\QCMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\QCMBundle\Entity\QCM;
use FXL\Bundle\QCMBundle\Form\QCMType;

/**
 * QCM controller.
 *
 * @Route("/admin/ajax/qcm/qcm")
 */
class QCMController extends Controller
{
    /**
     * Lists all QCM entities.
     *
     * @Route("/", name="admin_ajax_qcm")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLQCMBundle:QCM')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Finds and displays a QCM entity.
     *
     * @Route("/{id}/show", name="admin_ajax_qcm_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:QCM')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QCM entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new QCM entity.
     *
     * @Route("/new", name="admin_ajax_qcm_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new QCM();
        $form   = $this->createForm(new QCMType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new QCM entity.
     *
     * @Route("/create", name="admin_ajax_qcm_create")
     * @Method("POST")
     * @Template("FXLQCMBundle:QCM:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new QCM();
        $form = $this->createForm(new QCMType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setUser($this->getUser());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_qcm_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing QCM entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_qcm_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:QCM')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QCM entity.');
        }

        $editForm = $this->createForm(new QCMType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing QCM entity.
     *
     * @Route("/{id}/update", name="admin_ajax_qcm_update")
     * @Method("POST")
     * @Template("FXLQCMBundle:QCM:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:QCM')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QCM entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QCMType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_qcm_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a QCM entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_qcm_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLQCMBundle:QCM')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find QCM entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_qcm'));
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
