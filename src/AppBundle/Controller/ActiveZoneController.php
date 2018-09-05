<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ActiveZones;
use AppBundle\Entity\Models;

/**
 * ActiveZone controller.
 *
 * @Route("/show/model/zone")
 */
class ActiveZoneController extends Controller
{
	/**
     * Creates a new ActiveZone entity.
     *
     * @Route("/new/{id}", name="show_model_zone_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Models $model)
    {
		$zone = new ActiveZones();
        $form = $this->createForm('AppBundle\Form\ActiveZoneType', $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$zone->setModelid($model);
            $em->persist($zone);
            $em->flush();

            return $this->redirectToRoute('show_model_show', array('id' => $model->getId()));
        }

        return $this->render('zone/new.html.twig', array(
            'zone' => $zone,
            'form' => $form->createView(),
        ));
    }
	
	/**
     * Finds and displays a ActiveZone entity.
     *
     * @Route("/{id}", name="show_model_zone_show")
     * @Method("GET")
     */
    public function showAction(ActiveZones $zone)
    {
		$deleteForm = $this->createDeleteForm($zone);
		
		$em = $this->getDoctrine()->getManager();

		$renders = $em->getRepository('AppBundle:Renders')->findRendersOrderedByMaterial($zone);

        return $this->render('zone/show.html.twig', array(
            'zone' => $zone,
			'renders' => $renders,
            'delete_form' => $deleteForm->createView()
        ));
    }
	
	/**
     * Displays a form to edit an existing ActiveZone entity.
     *
     * @Route("/{id}/edit", name="show_model_zone_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ActiveZones $zone)
    {
        $deleteForm = $this->createDeleteForm($zone);
        $editForm = $this->createForm('AppBundle\Form\ActiveZoneType', $zone);
        $editForm->handleRequest($request);
		
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($zone);
            $em->flush();			
			$em2 = $this->getDoctrine()->getManager();
			$model = $em2->getRepository('AppBundle:Models')->findOneById($zone->getModelid());
		
			return $this->redirectToRoute('show_model_show', array('id' => $model->getId()));
        }

        return $this->render('zone/edit.html.twig', array(
            'zone' => $zone,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ActiveZone entity.
     *
     * @Route("/{id}", name="show_model_zone_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActiveZones $zone)
    {
        $form = $this->createDeleteForm($zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($zone);
            $em->flush();
        }

		$em = $this->getDoctrine()->getManager();
        $model = $em->getRepository('AppBundle:Models')->findOneById($zone->getModelid());
		return $this->redirectToRoute('show_model_show', array('id' => $model->getId()));
    }

    /**
     * Creates a form to delete a ActiveZone entity.
     *
     * @param ActiveZones $zone The ActiveZones entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActiveZones $zone)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_model_zone_delete', array('id' => $zone->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
