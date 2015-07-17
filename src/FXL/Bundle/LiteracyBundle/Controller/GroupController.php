<?php

namespace FXL\Bundle\LiteracyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FXL\Bundle\LiteracyBundle\Entity\Group;
use FXL\Bundle\LiteracyBundle\Form\GroupType;
use FXL\Component\HttpFoundation\JsonResponse;

/**
 * Group controller.
 *
 * @Route("/admin/ajax/group")
 */
class GroupController extends Controller
{
    /**
     * @Route("/{groupId}/unpublish", name="admin_ajax_group_unpublish")
     * @Template()
     */
    public function unpublishAction($groupId){

        $em = $this->getDoctrine()->getManager();

         try{
            $entity = $em->getRepository('FXLLiteracyBundle:Group')
                ->find($groupId)
                ->unpublish()
                ->persistAndFlush($em);

        }catch(Exception $e){
            return new JsonResponse(array(
                'success'   =>  false,
                'message'   =>  $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success'   =>  true,
            'published' => $entity->getPublished()
        ));
    }

    /**
     * @Route("/{groupId}/publish", name="admin_ajax_group_publish")
     * @Template()
     */
    public function publishAction($groupId){

        $em = $this->getDoctrine()->getManager();

        try{
            $entity = $em->getRepository('FXLLiteracyBundle:Group')
                ->find($groupId)
                ->publish();

            $em->persist($entity);
            $em->flush();

        }catch(Exception $e){

            return new JsonResponse(array(
                'success'   =>  false,
                'message'   =>  $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success'   =>  true,
            'published' => $entity->getPublished()
        ));

    }

    /**
     * Generate all
     *
     * @Route("/{groupId}/generate", name="admin_ajax_group_generate")
     * @Template()
     */
    public function generateAction($groupId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Group')->find($groupId);

        return new JsonResponse(array(
            "content"   =>  $entity->getSheetContent()
        ));
    }

    /**
     * Lists all Group entities.
     *
     * @Route("/", name="admin_ajax_group")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FXLLiteracyBundle:Group')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Group entity.
     *
     * @Route("/{id}/show", name="admin_ajax_group_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Group entity.
     *
     * @Route("/node/{nodeId}/new", name="admin_ajax_node_group_new")
     * @Route("/{groupId}/group/new", name="admin_ajax_group_group_new")
     * @Route("/new", name="admin_ajax_group_new")
     * @Template()
     */
    public function newAction($groupId=null, $nodeId=null)
    {
        $entity = new Group();

        if ($groupId) {

            $entity->setParent($this->getDoctrine()->getRepository("FXLLiteracyBundle:Group")->find($groupId));

        }elseif  ($nodeId) {

            $entity->setNode($this->getDoctrine()->getRepository("FXLLiteracyBundle:Node")->find($nodeId));
        }else{
            $entity->setUser($this->getUser());
        }

        $form   = $this->createForm(new GroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Group entity.
     *
     * @Route("/create", name="admin_ajax_group_create")
     * @Method("POST")
     * @Template("FXLLiteracyBundle:Group:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Group();
        $form = $this->createForm(new GroupType(), $entity);
        $form->bind($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $structure = $entity->getStructure();

            if(isset($structure)){

                $groupStructure = $this->getDoctrine()->getRepository("FXLLiteracyBundle:Sheet")
                    ->findSheetsOrdered($structure->getId());

                foreach($groupStructure as $sheetStructure){

                    $sheet = new \FXL\Bundle\LiteracyBundle\Entity\Sheet;

                    $sheet->setName($sheetStructure->getName());
                    $sheet->setDescription($sheetStructure->getDescription());
                    $sheet->setPosition($sheetStructure->getPosition());
                    $sheet->setGroup($entity);
                    $entity->addSheet($sheet);
                }
            }

            \Doctrine\Common\Util\Debug::dump($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_group_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Group entity.
     *
     * @Route("/{id}/edit", name="admin_ajax_group_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $editForm = $this->createForm(new GroupType(), $entity, array(
            "type" => "update"
        ));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Group entity.
     *
     * @Route("/{id}/update", name="admin_ajax_group_update")
     * @Method("POST")
     * @Template("FXLLiteracyBundle:Group:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FXLLiteracyBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ajax_group_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Group entity.
     *
     * @Route("/{id}/delete", name="admin_ajax_group_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FXLLiteracyBundle:Group')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Group entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ajax_group'));
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
