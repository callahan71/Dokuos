<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Combinations;
use AppBundle\Entity\Showcases;

/**
 * Combination controller.
 *
 * @Route("/show/showcase/combination")
 * @Security("has_role('ROLE_USER')")
 */
class CombinationController extends Controller
{
	    /**
     * Creates a new Combination entity.
     *
     * @Route("/new/{id}", name="show_showcase_combination_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request, Showcases $showcase)
    {
		$combination = new Combinations();
        $form = $this->createForm('AppBundle\Form\CombinationType', $combination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$combination->setShowcaseid($showcase);
            $em->persist($combination);
            $em->flush();
			
			$this->addFlash('notice', 'Combinación introducida con éxito!');	

            //return $this->redirectToRoute('show_showcase_show', array('id' => $showcase->getId()));
			return $this->redirectToRoute('show_showcase_combination_new', array('id' => $showcase->getId()));
        }

        return $this->render('combination/new.html.twig', array(
			'id' => $showcase->getId(),
            'combination' => $combination,
            'form' => $form->createView(),
        ));
    }
	
	/**
     * Finds and displays a Showcases entity.
     *
     * @Route("/{id}", name="show_showcase_combination_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Combinations $combination)
    {
		$deleteForm = $this->createDeleteForm($combination);

        return $this->render('combination/show.html.twig', array(
            'combination' => $combination,
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
	/**
     * Displays a form to edit an existing Combination entity.
     *
     * @Route("/{id}/edit", name="show_showcase_combination_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Combinations $combination)
    {
        $deleteForm = $this->createDeleteForm($combination);
        $editForm = $this->createForm('AppBundle\Form\CombinationType', $combination);
        $editForm->handleRequest($request);
		
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($combination);
            $em->flush();			
			$em2 = $this->getDoctrine()->getManager();
			$showcase = $em2->getRepository('AppBundle:Showcases')->findOneById($combination->getShowcaseid());
		
			return $this->redirectToRoute('show_showcase_show', array('id' => $showcase->getId()));
        }

        return $this->render('combination/edit.html.twig', array(
            'combination' => $combination,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Combination entity.
     *
     * @Route("/{id}", name="show_showcase_combination_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Combinations $combination)
    {
        $form = $this->createDeleteForm($combination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($combination);
            $em->flush();
        }

		$em = $this->getDoctrine()->getManager();
        $showcase = $em->getRepository('AppBundle:Showcases')->findOneById($combination->getShowcaseid());
		return $this->redirectToRoute('show_showcase_show', array('id' => $showcase->getId()));
    }

    /**
     * Creates a form to delete a Combination entity.
     *
     * @param Combinations $combination The Combination entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Combinations $combination)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_showcase_combination_delete', array('id' => $combination->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
