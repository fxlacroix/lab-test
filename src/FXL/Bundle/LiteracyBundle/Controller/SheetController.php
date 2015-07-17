<?php

namespace FXL\Bundle\LiteracyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FXL\Bundle\MagicBundle\Controller\BaseController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\LiteracyBundle\Entity\Sheet;
use FXL\Bundle\LiteracyBundle\Form\SheetType;
use Symfony\Component\HttpFoundation\Response;
use FXL\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Sheet controller.
 *
 * @Route("admin/ajax/sheet")
 */
class SheetController extends Controller
{
    /**
     *
     * @Route("/{sheetId}/content", name="sheet_content")
     */
    public function getContentAction($sheetId)
    {
        $entity = $this->getDoctrine()->getRepository("FXLLiteracyBundle:Sheet")->find($sheetId);

        return new JsonResponse(array(
            "id"        =>   $entity->getId(),
            "content"   =>   $entity->getContent(),
            "note"      =>   $entity->getNote()
        ));
    }

    /**
     *
     * @Route("/{sheetId}/content/update", name="sheet_content_set")
     */
    public function updateContentAction($sheetId)
    {
        $entity = $this->getDoctrine()->getRepository("FXLLiteracyBundle:Sheet")->find($sheetId);

        $entity->setContent($this->getRequest()->request->get("content"));
        $entity->setNote($this->getRequest()->request->get("note"));

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return new JsonResponse(array());
    }

    /**
     * Lists all Sheet entities.
     *
     * @Route("/", name="sheet")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLLiteracyBundle:Sheet')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Sheet entity.
     *
     * @Route("/{id}/show", name="sheet_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Sheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sheet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Sheet entity.
     *
     * @Route("/group/{groupId}/new", name="sheet_group_new")
     * @Route("/new", name="sheet_new")
     * @Template()
     */
    public function newAction($groupId)
    {
        $entity = new Sheet();
        $entity->setGroup($this->getDoctrine()->getRepository("FXLLiteracyBundle:Group")->find($groupId));

        $form   = $this->createForm(new SheetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Sheet entity.
     *
     * @Route("/create", name="sheet_create")
     * @Method("POST")
     * @Template("FXLLiteracyBundle:Sheet:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Sheet();
        $form = $this->createForm(new SheetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sheet_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sheet entity.
     *
     * @Route("/{id}/edit", name="sheet_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Sheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sheet entity.');
        }

        $editForm = $this->createForm(new SheetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Sheet entity.
     *
     * @Route("/{id}/update", name="sheet_update")
     * @Method("POST")
     * @Template("FXLLiteracyBundle:Sheet:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Sheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sheet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SheetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sheet_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Sheet entity.
     *
     * @Route("/{id}/delete", name="sheet_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLLiteracyBundle:Sheet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sheet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sheet'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id), array(
                'csrf_protection' => false
            ))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
