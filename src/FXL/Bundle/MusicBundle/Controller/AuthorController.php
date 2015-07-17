<?php

namespace FXL\Bundle\MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\MusicBundle\Entity\Author;
use FXL\Bundle\MusicBundle\Form\AuthorType;
use FXL\Bundle\MusicBundle\HttpFoundation\JsonResponse;

/**
 * Author controller.
 *
 * @Route("/admin/ajax/author")
 */
class AuthorController extends Controller
{
    /**
     *
     * @param collection $page_content
     * @return null
     */
    public function parseWikipedia($page_content){

        foreach((array)json_decode($page_content) as $query) {
            foreach((array)$query->pages as $page) {
                return $page;

            }
        }

        return null;
    }

    /**
     * Lists all Author entities.
     *
     * @Route("/", name="admin_ajax_author")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLMusicBundle:Author')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Author entity.
     *
     * @Route("/", name="admin_ajax_author_create")
     * @Method("POST")
     * @Template("FXLMusicBundle:Author:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Author();
        $form = $this->createForm(new AuthorType(), $entity);
        $form->bind($request);

        //\Doctrine\Common\Util\Debug::dump($entity);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);

            /*
            foreach($entity->getTracks() as $track) {
                $track->addAuthor($entity);
                $em->persist($track);
            }*/

            $em->flush();

            return new JsonResponse(array(
                "success"   =>  true,
                "data"      => array(
                    "value" => $entity->getId(),
                    "text"  => $entity->getName()
                )
            ));


            return $this->redirect($this->generateUrl('admin_ajax_author_edit', array('id' => $entity->getId())));
        }else{

            die($form->getErrorsAsString());
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Author entity.
     *
     * @Route("/new", name="admin_ajax_author_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Author();

        $projectId = $this->getRequest()->query->get("project");

        $form   = $this->createForm(new AuthorType(), $entity, array(
            "project"   =>  $projectId
        ));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Author entity.
     *
     * @Route("/{id}", name="admin_ajax_author_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Author')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Author entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Author entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_author_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $projectId = $this->getRequest()->query->get("project");

        $entity = $em->getRepository('FXLMusicBundle:Author')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Author entity.');
        }

        $editForm = $this->createForm(new AuthorType(), $entity, array(
            "project"   =>  $projectId
        ));

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Author entity.
     *
     * @Route("/{id}", name="admin_ajax_author_update")
     * @Method("PUT")
     * @Template("FXLMusicBundle:Author:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLMusicBundle:Author')->find($id);

        //$oldTrack = clone($entity->getTracks());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Author entity.');
        }

        // @TODO understand why not binding my relation
        //$postedTrack = $_POST["fxl_musicbundle_authortype"]['tracks'];
        //unset($_POST["fxl_musicbundle_authortype"]['tracks']);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AuthorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);

           /* foreach($entity->getTracks() as $track) {

                $entity->addTrack($track);
            }

            foreach($oldTrack as $track){

                $entity->addTrack($track);
                $em->persist($track);
            }
*/
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_author_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Unlink an Author entity.
     *
     * @Route("/{id}/unlink", name="admin_ajax_author_unlink")
     * @Method("POST")
     */
    public function unlinkAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {

             $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('FXLMusicBundle:Author')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Author entity.');
            }

            if(isset($_GET['project'])) {

                $project = $em->getRepository('FXLMusicBundle:Project')->find($_GET['project']);
                $project->getAuthors()->removeElement($entity);
                $em->persist($project);
            }

            if(isset($_GET['track'])) {
                $track = $em->getRepository('FXLMusicBundle:Track')->find($_GET['track']);
                $track->getAuthors()->removeElement($entity);
                $em->persist($track);
            }

            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_author'));
    }

    /**
     * Deletes a Author entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_author_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLMusicBundle:Author')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Author entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_author'));
    }

    /**
     * Creates a form to delete a Author entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
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
