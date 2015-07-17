<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Tag;
use FXL\Bundle\MusicBundle\Entity\Project;
use FXL\Bundle\MusicBundle\Form\TagType;
use FXL\Bundle\MusicBundle\Form\ProjectType;

/**
 * Tag controller.
 *
 * @Route("/admin/ajax/tag")
 */
class TagController extends Controller
{
    /**
     * Lists all Tag entities.
     *
     * @Route("/", name="admin_ajax_tag")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Tag')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Tag entity.
     *
     * @Route("/{id}/show", name="admin_ajax_tag_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     * @Route("/new", name="admin_ajax_tag_new")
     * @Template()
     */
    public function newAction()
    {
        $projectId = $this->getRequest()->query->get("project");
        $entity = new Tag();

        if($projectId) {

            $project = $this->getDoctrine()->getRepository("FXLMusicBundle:Project")->find($projectId);
            $entity->addProject($project);
        }


        $form   = $this->createForm(new TagType(), $entity);

        return array(
            'entity'       => $entity,
            'form'         => $form->createView(),
        );
    }

    /**
     * Creates a new Tag entity.
     *
     * @Route("/create", name="admin_ajax_tag_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Tag:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Tag();
        $form = $this->createForm(new TagType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_tag_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_tag_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm = $this->createForm(new TagType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tag entity.
     *
     * @Route("/{id}/update", name="admin_ajax_tag_update")
     * @Method("POST")
     * @Template("FXLMusicBundle:Tag:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TagType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_tag_edit', array('id' => $id)));
        }
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tag entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_tag_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Tag')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tag entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_tag'));
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
