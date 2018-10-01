<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Users;
use AppBundle\Form\UserType;
use AppBundle\Service\FileUploader;

/**
 * User controller.
 *
 * @Route("/show/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="show_user_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:Users')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="show_user_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $user = new Users();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			//Creamos directorio con el nombre del usuario
			mkdir($this->getParameter('upload_directory').'/'.$user->getUsername(), 0707);
			
			$file=$form['image']->getData();
			$file_name = $fileUploader->upload($file, $user->getUsername(), $user->getUsername());
			$user->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);			
            $em->flush();
			
            return $this->redirectToRoute('show_user_index');
			//return $this->redirectToRoute('show_user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="show_user_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="show_user_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Users $user, FileUploader $fileUploader)
    {
		if ($user->getImage() != null){
			$user->setImage(
				new File($this->getParameter('upload_directory').'/'.$user->getUsername().'/'.$user->getImage())
			);
		}
		
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
			$file=$user->getImage();
			$file_name = $fileUploader->upload($file, $user->getUsername(), $user->getUsername());
			$user->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

			return $this->redirectToRoute('show_user_index');            
			//return $this->redirectToRoute('show_user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="show_user_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
			
			// Para borrar el directorio. 
			// Da error si no esta vacio
			//rmdir($this->getParameter('upload_directory').'/'.$user->getUsername());
        }

        return $this->redirectToRoute('show_user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
