<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Renders;
use AppBundle\Entity\ActiveZones;
use AppBundle\Service\FileUploader;

/**
 * Render controller.
 *
 * @Route("/show/model/zone/render")
 * @Security("has_role('ROLE_USER')")
 */
class RenderController extends Controller
{
	/**
     * Creates a new Render entity.
     *
     * @Route("/new/{id}", name="show_model_zone_render_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request, ActiveZones $zone, FileUploader $fileUploader)
    {
		$render = new Renders();
        $form = $this->createForm('AppBundle\Form\RenderType', $render);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$file=$form['image']->getData();
			$nameFile = $zone->getModelid()->getRef().'_'.$zone->getZoneref().'_'.$render->getMaterialid()->getRef();
			$nameUser = $zone->getModelid()->getUserid()->getUsername();
			$file_name = $fileUploader->upload($file, $nameFile, $nameUser);
			$render->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
			$render->setActiveZoneid($zone);
            $em->persist($render);
            $em->flush();
			
			$this->addFlash('notice', 'Render introducido con éxito!');	

			return $this->redirectToRoute('show_model_zone_render_new', array('id' => $zone->getId()));
			//return $this->redirectToRoute('show_model_zone_show', array('id' => $zone->getId()));
        }

        return $this->render('render/new.html.twig', array(
			'id' => $zone->getId(),
            'render' => $render,
            'form' => $form->createView(),
        ));
    }
	
	/**
     * Finds and displays a Render entity.
     *
     * @Route("/{id}", name="show_model_zone_render_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Renders $render)
    {
		$deleteForm = $this->createDeleteForm($render);

        return $this->render('render/show.html.twig', array(
			'render' => $render,
            'delete_form' => $deleteForm->createView()
        ));
    }
	
	/**
     * Displays a form to edit an existing Render entity.
     *
     * @Route("/{id}/edit", name="show_model_zone_render_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Renders $render, FileUploader $fileUploader)
    {
		if ($render->getImage() != null){
			$render->setImage(
			new File($this->getParameter('upload_directory').'/'.$render->getMaterialid()->getUserid()->getUsername().'/'.$render->getImage())
			);
		}
		
        $deleteForm = $this->createDeleteForm($render);
        $editForm = $this->createForm('AppBundle\Form\RenderType', $render);
        $editForm->handleRequest($request);
		
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
			$file=$render->getImage();
			$nameFile = $render->getActiveZoneid()->getModelid()->getRef().'_'.$render->getActiveZoneid()->getZoneref().'_'.$render->getMaterialid()->getRef();
			$nameUser = $render->getMaterialid()->getUserid()->getUsername();
			$file_name = $fileUploader->upload($file, $nameFile, $nameUser);
			$render->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($render);
            $em->flush();			
			$em2 = $this->getDoctrine()->getManager();
			$zone = $em2->getRepository('AppBundle:ActiveZones')->findOneById($render->getActiveZoneid());
		
			return $this->redirectToRoute('show_model_zone_show', array('id' => $zone->getId()));
        }

        return $this->render('render/edit.html.twig', array(
            'render' => $render,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Render entity.
     *
     * @Route("/{id}", name="show_model_zone_render_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Renders $render)
    {
        $form = $this->createDeleteForm($render);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($render);
            $em->flush();
        }

		$em = $this->getDoctrine()->getManager();
        $zone = $em->getRepository('AppBundle:ActiveZones')->findOneById($render->getActiveZoneid());
		return $this->redirectToRoute('show_model_zone_show', array('id' => $zone->getId()));
    }

    /**
     * Creates a form to delete a Render entity.
     *
     * @param Render $render The Render entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Renders $render)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_model_zone_render_delete', array('id' => $render->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
