<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Asset;
use FXL\Bundle\MusicBundle\Form\AssetType;

/**
 * Asset controller.
 *
 * @Route("/admin/ajax/asset")
 */
class AssetController extends Controller
{
    /**
     * Lists all Asset entities.
     *
     * @Route("/", name="admin_ajax_asset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Asset')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Asset entity.
     *
     * @Route("/{id}/show", name="admin_ajax_asset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Asset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Asset entity.
     *
     * @Route("/new", name="admin_ajax_asset_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Asset();
         $projectId = $this->getRequest()->query->get("project");

        if($projectId) {

            $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")->find($projectId);

            $entity->setProject($project);

            $entity->setType("logo");

        }
        
        $authorId = $this->getRequest()->query->get("author");

        if($authorId) {

            $author = $this->getDoctrine()->getRepository("FXLMusicBundle:Author")->find($authorId);

            $entity->setAuthor($author);

            $entity->setType("picture");

        }

        $form   = $this->createForm(new AssetType(), $entity);


        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Asset entity.
     *
     * @Route("/create", name="admin_ajax_asset_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Asset:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Asset();
        $form = $this->createForm(new AssetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_asset_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Asset entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_asset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Asset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asset entity.');
        }

        $editForm = $this->createForm(new AssetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Asset entity.
     *
     * @Route("/{id}/update", name="admin_ajax_asset_update")
     * @Method("POST")
     * @Template("FXLMusicBundle:Asset:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Asset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AssetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_asset_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Asset entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_asset_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Asset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Asset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_asset'));
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
