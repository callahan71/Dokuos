<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Materials;

/**
 * Material controller.
 *
 * @Route("/show/material")
 * @Security("has_role('ROLE_USER')")
 */
class MaterialController extends Controller
{
    /**
     * Lists all Material entities.
     *
     * @Route("/", name="show_material_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
		$whatRole = $this->get('security.authorization_checker');
		
		$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();
		
		if ($whatRole->isGranted('ROLE_ADMIN')){
			$materials = $em->getRepository('AppBundle:Materials')->findAll();
		}else
		{
			$materials = $em->getRepository('AppBundle:Materials')->findUserMaterialsOrderedByRef($user);// para buscar los materials del usuario
		}			

        return $this->render('material/index.html.twig', array(
            'materials' => $materials,
        ));
    }

    /**
     * Creates a new Materials entity.
     *
     * @Route("/new", name="show_material_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
		$whatRole = $this->get('security.authorization_checker');
		
		$user = $this->getUser();// para tomar el usuario loggeado
        $material = new Materials();
        $form = $this->createForm('AppBundle\Form\MaterialType', $material, ['role' => $this->getUser()->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if (!$whatRole->isGranted('ROLE_ADMIN')){
				$material->setUserid($user);//le paso el usuario que crea el material
			}
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();
			
			$this->addFlash('notice', 'Material introducido con éxito!');	

            return $this->redirectToRoute('show_material_new');
			//return $this->redirectToRoute('show_material_show', array('id' => $material->getId()));
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
	 * @Security("has_role('ROLE_USER')")
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
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Materials $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $editForm = $this->createForm('AppBundle\Form\MaterialType', $material, ['role' => $this->getUser()->getRoles()]);
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
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Materials $material)
    {
		$whatRole = $this->get('security.authorization_checker');
        $form = $this->createDeleteForm($material);
        $form->handleRequest($request);

		if (($this->getUser() == $material->getUserid()) || $whatRole->isGranted('ROLE_ADMIN')) {
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($material);
				$em->flush();
			}
			$this->addFlash('notice', 'Borrado correcto!');		
		} else {
			$this->addFlash('notice', 'No se puede borrar. No eres el propietario ni administrador.');	
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
