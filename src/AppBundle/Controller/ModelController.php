<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Models;
use AppBundle\Service\FileUploader;

/**
 * Model controller.
 *
 * @Route("/show/model")
 */
class ModelController extends Controller
{
    /**
     * Lists all Model entities.
     *
     * @Route("/", name="show_model_index")
     * @Method("GET")
     */
    public function indexAction()
    {
		//$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();

		//$models = $em->getRepository('AppBundle:Models')->findAll();
		$models = $em->getRepository('AppBundle:Models')->findUserModelsOrderedByUser();

        return $this->render('model/index.html.twig', array(
            'models' => $models,
        ));
    }

    /**
     * Creates a new Models entity.
     *
     * @Route("/new", name="show_model_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
		$user = $this->getUser();// para tomar el usuario loggeado
        $model = new Models();
        $form = $this->createForm('AppBundle\Form\ModelType', $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			//$model->setUserid($user);//le paso el usuario que crea el model
			//
			// Recogemos el fichero
			$file=$form['image']->getData();

			// Sacamos la extensión del fichero
			//$ext=$file->guessExtension();

			// Le ponemos un nombre al fichero
			//$file_name=$model->getRef().".".$ext;
			$file_name = $fileUploader->upload($file, $model->getRef(), $model->getUserid()->getUsername());

			// Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
			//$file->move("uploads", $file_name);

			// Establecemos el nombre de fichero en el atributo de la entidad
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
     */
    public function editAction(Request $request, Models $model, FileUploader $fileUploader)
    {
        $model->setImage(
			new File($this->getParameter('upload_directory').'/'.$model->getUserid()->getUsername().'/'.$model->getImage())
		);
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
     */
    public function deleteAction(Request $request, Models $model)
    {
        $form = $this->createDeleteForm($model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($model);
            $em->flush();
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