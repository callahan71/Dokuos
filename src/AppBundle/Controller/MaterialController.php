<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Materials;

/**
 * Material controller.
 *
 * @Route("/show/material")
 */
class MaterialController extends Controller
{
    /**
     * Lists all Material entities.
     *
     * @Route("/", name="show_material_index")
     * @Method("GET")
     */
    public function indexAction()
    {
		$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();

		$materials = $em->getRepository('AppBundle:Materials')->findUserMaterialsOrderedByRef($user);// para buscar los materials del usuario

        return $this->render('material/index.html.twig', array(
            'materials' => $materials,
        ));
    }

    /**
     * Creates a new Materials entity.
     *
     * @Route("/new", name="show_material_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
		$user = $this->getUser();// para tomar el usuario loggeado
        $material = new Materials();
        $form = $this->createForm('AppBundle\Form\MaterialType', $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$material->setUserid($user);//le paso el usuario que crea el material
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('show_material_show', array('id' => $material->getId()));
        }

        return $this->render('material/new.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Materials entity.
     *
     * @Route("/{id}", name="show_material_show")
     * @Method("GET")
     */
    public function showAction(Materials $material)
    {
        $deleteForm = $this->createDeleteForm($material);

        return $this->render('material/show.html.twig', array(
            'material' => $material,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Materials entity.
     *
     * @Route("/{id}/edit", name="show_material_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Materials $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $editForm = $this->createForm('AppBundle\Form\MaterialType', $material);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

			return $this->redirectToRoute('show_material_index');
        }

        return $this->render('material/edit.html.twig', array(
            'material' => $material,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Materials entity.
     *
     * @Route("/{id}", name="show_material_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Materials $material)
    {
        $form = $this->createDeleteForm($material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('show_material_index');
    }

    /**
     * Creates a form to delete a Material entity.
     *
     * @param Materials $material The Materials entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Materials $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_material_delete', array('id' => $material->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
