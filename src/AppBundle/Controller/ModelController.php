<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Models;
use AppBundle\Service\FileUploader;

/**
 * Model controller.
 *
 * @Route("/show/model")
 * @Security("has_role('ROLE_USER')")
 */
class ModelController extends Controller
{
    /**
     * Lists all Model entities.
     *
     * @Route("/", name="show_model_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
		$whatRole = $this->get('security.authorization_checker');
		$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();

		if ($whatRole->isGranted('ROLE_ADMIN')){
			$models = $em->getRepository('AppBundle:Models')->findUserModelsOrderedByUser();
		}else
		{
			$models = $em->getRepository('AppBundle:Models')->findModelsByUser($user);
		}
		//$models = $em->getRepository('AppBundle:Models')->findAll();
		//$models = $em->getRepository('AppBundle:Models')->findUserModelsOrderedByUser();

        return $this->render('model/index.html.twig', array(
            'models' => $models,
        ));
    }

    /**
     * Creates a new Models entity.
     *
     * @Route("/new", name="show_model_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $model = new Models();
        $form = $this->createForm('AppBundle\Form\ModelType', $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$file=$form['image']->getData();
			$file_name = $fileUploader->upload($file, $model->getRef(), $model->getUserid()->getUsername());
			$model->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($model);
            $em->flush();

            return $this->redirectToRoute('show_model_show', array('id' => $model->getId()));
        }

        return $this->render('model/new.html.twig', array(
            'model' => $model,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Models entity.
     *
     * @Route("/{id}", name="show_model_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Models $model)
    {
        $deleteForm = $this->createDeleteForm($model);
		
		$em = $this->getDoctrine()->getManager();

		$zones = $em->getRepository('AppBundle:ActiveZones')->findModelZonesOrderedByRef($model);	

        return $this->render('model/show.html.twig', array(
            'model' => $model,
			'zones' => $zones,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Models entity.
     *
     * @Route("/{id}/edit", name="show_model_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Models $model, FileUploader $fileUploader)
    {
		if ($model->getImage() != null){
			$model->setImage(
				new File($this->getParameter('upload_directory').'/'.$model->getUserid()->getUsername().'/'.$model->getImage())
			);
		}
		$deleteForm = $this->createDeleteForm($model);
        $editForm = $this->createForm('AppBundle\Form\ModelType', $model);
        $editForm->handleRequest($request);
		
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
			$file=$model->getImage();
			$file_name = $fileUploader->upload($file, $model->getRef(), $model->getUserid()->getUsername());
			$model->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($model);
            $em->flush();

			return $this->redirectToRoute('show_model_index');
        }
		
        return $this->render('model/edit.html.twig', array(
            'model' => $model,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Models entity.
     *
     * @Route("/{id}", name="show_model_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Models $model)
    {
        $form = $this->createDeleteForm($model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($model);
            $em->flush();
			//borra la imagen del directorio
			unlink($this->getParameter('upload_directory').'/'.$model->getUserid().'/'.$model->getImage());
        }

        return $this->redirectToRoute('show_model_index');
    }

    /**
     * Creates a form to delete a Model entity.
     *
     * @param Models $model The Models entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Models $model)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_model_delete', array('id' => $model->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}