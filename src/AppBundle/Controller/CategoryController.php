<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Categories;
use AppBundle\Form\CategoryType;
use AppBundle\Service\FileUploader;

/**
 * Categories controller.
 *
 * @Route("/show/category")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Categories entities.
     *
     * @Route("/", name="show_category_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Categories')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new Categories entity.
     *
     * @Route("/new", name="show_category_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $category = new Categories();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$file=$form['image']->getData();
			$file_name = $fileUploader->upload($file, $category->getName(), 'SET-CAT');
			$category->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('show_category_index');
			//return $this->redirectToRoute('show_category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Categories entity.
     *
     * @Route("/{id}", name="show_category_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function showAction(Categories $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Categories entity.
     *
     * @Route("/{id}/edit", name="show_category_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Categories $category, FileUploader $fileUploader)
    {
		if ($category->getImage() != null){
			$category->setImage(
				new File($this->getParameter('upload_directory').'/SET-CAT/'.$category->getImage())
			);
		}
		
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
			$file=$category->getImage();
			$file_name = $fileUploader->upload($file, $category->getName(), 'SET-CAT');
			$category->setImage($file_name);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            //return $this->redirectToRoute('show_category_edit', array('id' => $category->getId()));
			return $this->redirectToRoute('show_category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Categories entity.
     *
     * @Route("/{id}", name="show_category_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Categories $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('show_category_index');
    }

    /**
     * Creates a form to delete a Category entity.
     *
     * @param Categories $category The Categories entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categories $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
