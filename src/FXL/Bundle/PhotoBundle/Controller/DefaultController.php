<?php

namespace FXL\Bundle\PhotoBundle\Controller;

use FXL\Bundle\PhotoBundle\Form\MetaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use FXL\Bundle\PhotoBundle\Form\ContactType;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/photo/{userName}/about", name="photo_user_about")
     * @Template()
     */
    public function aboutAction($userName)
    {
        $folders = $this->getFoldersImages($userName, true);
        $em = $this->getDoctrine()->getManager();
        $meta = $em->getRepository('FXLPhotoBundle:Meta')
          ->findOneByUsername($userName);


      return array(
            'userName' => $userName,
            'folders' => $folders,
            'meta'    => $meta
        );
    }

    /**
     * @Route("/photo/{userName}/list", name="photo_user_list")
     * @Template()
     */
    public function photoAction($userName)
    {
        $folders = $this->getFoldersImages($userName, true);

        return array(
            'userName' => $userName,
            'folders' => $folders
        );
    }

    /**
     * @Route("/photo/{userName}/list/{folderName}", name="photo_user_list_folder")
     * @Template()
     */
    public function listAction($userName, $folderName)
    {
        $folders = $this->getFoldersImages($userName);
        $folder = $folders[$folderName];
        if (empty($folder)) {
            throw $this->createNotFoundException('This folder does not exist');
        }

        return array(
            'userName'      => $userName,
            'folderName'    => $folderName,
            'folder'        => $folder,
        );
    }

    /**
     * @Route("/photo/{userName}/print", name="photo_user_print")
     * @Template()
     */
    public function printAction($userName)
    {
        $folders = $this->getFoldersImages($userName);

        return array(
            'userName' => $userName,
            'folders' => $folders
        );
    }

  /**
   * @Route("/photo/{userName}/edit", name="photo_user_edit")
   * @Template()
   */
  public function editAction(Request $request, $userName)
  {
    $em = $this->getDoctrine()->getManager();
    $meta = $em->getRepository('FXLPhotoBundle:Meta')
      ->findOneByUsername($userName);

    $form = $this->get('form.factory')->create(new MetaType, $meta);

    if ($request->isMethod('POST')) {
      $form->submit($request);
      if ($form->isValid()) {

        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Présentation bien modifiée.');
      }
    }

    return array(
      'form'      => $form->createView(),
      'userName'  => $userName,
    );
  }


    /**
     * @Route("/photo/{userName}/recache", name="photo_recache")
     * @Template()
     */
    public function recacheAction($userName)
    {
        $input = new StringInput(null);
        $output = new NullOutput();

        $command = new CacheClearCommand();
        $command->setContainer($this->container);
        $command->run($input, $output);

        $url = $this->generateUrl('photo_user_list', array(
            'userName' => $userName
        ));
        return $this->redirect($url);
    }

    /**
     * @Route("/photo/{userName}/contact", name="photo_user_contact")
     * @Template()
     */
    public function contactAction($userName)
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {

                $mailer = $this->get('mailer');
                $contact = $request->get('contact');
                $message = \Swift_Message::newInstance()
                    ->setSubject($contact['subject'])
                    ->setFrom($contact['email'])
                    //->setTo('fxlacroix@gmail.com')
                    ->setSender($contact['email'])
                    ->setTo('royomichel@wanadoo.fr')
                    ->setBody($contact['message']);
                $mailer->send($message);

                $this->get('session')->getFlashBag()->set('success', 'Le message a été envoyé!');

                return new RedirectResponse($this->generateUrl('photo_user_contact', array(
                    'userName' => $userName
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'userName' => $userName
        );
    }

    /**
     * utility function @todo: put in component service
     *
     * @param type $userName
     * @throws type
     */
    public function getFoldersImages($userName, $force = false)
    {
        $folders = $this->get('session')->get('fxlphoto-' . $userName);

        $rootDir = $this->get('kernel')->getRootDir();
        $pathToImage = sprintf("%s/../web/uploads/fxlphoto/%s/photo", $rootDir, $userName);
        if (!file_exists($pathToImage)) {
            throw $this->createNotFoundException('This user does not exist');
        }

        $directories = new Finder();
        $directories->directories()->in($pathToImage)->sortByChangedTime();

        if (1 || $force || $directories->count() != count($folders)) {

            $folders = array();

            foreach ($directories as $directory) {

                $folder = new \FXL\Component\Entity\Base\Folder();
                $folder->setName($directory->getRelativePathname());

                $files = new Finder();
                $files->files()->in($directory->getRealpath())->sortByChangedTime();

                $i = 0;
                $maxTime = 0;
                foreach ($files as $key => $file) {

                    $httpImage = sprintf('/uploads/fxlphoto/%s/photo/%s/%s', $userName, $directory->getRelativePathname(), $file->getRelativePathname());
                    $folder->setPicture($httpImage);

                    $attached = new \FXL\Component\Entity\Base\Attached();
                    $attached->setId($i++);

                    $attached->setName(pathinfo(basename($file->getRelativePathname()), PATHINFO_FILENAME));
                    $attached->setPath($httpImage);
                    $folder->addFile($attached);
                    $fileCtime = filectime($rootDir . "/../web" . $httpImage);
                    if ($fileCtime > $maxTime) {
                        $maxTime = $fileCtime;
                    }
                    $attached->setCreatedAt(date("d/m/Y.", $fileCtime));

                }
                $folder->setDate(date("d/m/Y"));
                $folders[$folder->getName()] = $folder;
            }

            $this->get("session")->set("fxlphoto-" . $userName, $folders);
        }

        ksort($folders);
        return $folders;
    }

}
