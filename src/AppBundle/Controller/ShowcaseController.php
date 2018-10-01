<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Showcases;

/**
 * Showcase controller.
 *
 * @Route("/show/showcase")
 * @Security("has_role('ROLE_USER')")
 */
class ShowcaseController extends Controller
{
    /**
     * Lists all Showcase entities.
     *
     * @Route("/", name="show_showcase_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
		$whatRole = $this->get('security.authorization_checker');
		$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();

		if ($whatRole->isGranted('ROLE_ADMIN')){
			$showcases = $em->getRepository('AppBundle:Showcases')->findUserShowcasesOrderedByUser();
		}else
		{
			$showcases = $em->getRepository('AppBundle:Showcases')->findShowcasesByUser($user);
		}
		//$showcases = $em->getRepository('AppBundle:Showcases')->findAll();
		//$showcases = $em->getRepository('AppBundle:Showcases')->findUserShowcasesOrderedByUser();

        return $this->render('showcase/index.html.twig', array(
            'showcases' => $showcases,
        ));
    }

    /**
     * Creates a new Showcases entity.
     *
     * @Route("/new", name="show_showcase_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $showcase = new Showcases();
        $form = $this->createForm('AppBundle\Form\ShowcaseType', $showcase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($showcase);
            $em->flush();

            return $this->redirectToRoute('show_showcase_show', array('id' => $showcase->getId()));
        }

        return $this->render('showcase/new.html.twig', array(
            'showcase' => $showcase,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Showcases entity.
     *
     * @Route("/{id}", name="show_showcase_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Showcases $showcase)
    {
        $deleteForm = $this->createDeleteForm($showcase);
		
		$em = $this->getDoctrine()->getManager();

		$combinations = $em->getRepository('AppBundle:Combinations')->findShowcaseCombinationsOrderedByKey($showcase);		

        return $this->render('showcase/show.html.twig', array(
            'showcase' => $showcase,
			'combinations' => $combinations,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Showcases entity.
     *
     * @Route("/{id}/edit", name="show_showcase_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Showcases $showcase)
    {
        $deleteForm = $this->createDeleteForm($showcase);
        $editForm = $this->createForm('AppBundle\Form\ShowcaseType', $showcase);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($showcase);
            $em->flush();

			return $this->redirectToRoute('show_showcase_index');
        }

        return $this->render('showcase/edit.html.twig', array(
            'showcase' => $showcase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Showcases entity.
     *
     * @Route("/{id}", name="show_showcase_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Showcases $showcase)
    {
        $form = $this->createDeleteForm($showcase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($showcase);
            $em->flush();
        }

        return $this->redirectToRoute('show_showcase_index');
    }

    /**
     * Creates a form to delete a Showcase entity.
     *
     * @param Showcases $showcase The Showcases entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Showcases $showcase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_showcase_delete', array('id' => $showcase->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
