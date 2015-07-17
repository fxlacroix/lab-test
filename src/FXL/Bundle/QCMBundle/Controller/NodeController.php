<?php

namespace FXL\Bundle\QCMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\QCMBundle\Entity\Node;
use FXL\Bundle\QCMBundle\Form\NodeType;

/**
 * Node controller.
 *
 * @Route("/admin/ajax/qcm/node")
 */
class NodeController extends Controller
{
    /**
     * Lists all Node entities.
     *
     * @Route("/", name="admin_ajax_qcm_node")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLQCMBundle:Node')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Node entity.
     *
     * @Route("/{id}/show", name="admin_ajax_qcm_node_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Node entity.
     *
     * @Route("/qcm/{qcmId}/new", name="admin_ajax_qcm_node_qcm_new")
     * @Route("/node/{nodeId}/new", name="admin_ajax_qcm_node_node_new")
     * @Route("/new", name="admin_ajax_qcm_node_new")
     * @Template()
     */
    public function newAction($nodeId=null, $qcmId=null)
    {
        $entity = new Node();
        if($nodeId){
            $node = $this->getDoctrine()->getRepository("FXLQCMBundle:Node")->find($nodeId);

            $entity->setParent($node);
        }elseif($qcmId){
            $qcm = $this->getDoctrine()->getRepository("FXLQCMBundle:QCM")->find($qcmId);

            $entity->setQcm($qcm);
        }
        $form   = $this->createForm(new NodeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Node entity.
     *
     * @Route("/create", name="admin_ajax_qcm_node_create")
     * @Method("POST")
     * @Template("FXLQCMBundle:Node:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Node();
        $form = $this->createForm(new NodeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Node entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_qcm_node_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $editForm = $this->createForm(new NodeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Node entity.
     *
     * @Route("/{id}/update", name="admin_ajax_qcm_node_update")
     * @Method("POST")
     * @Template("FXLQCMBundle:Node:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLQCMBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NodeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_qcm_node_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Node entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_qcm_node_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLQCMBundle:Node')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Node entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_qcm_node'));
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
