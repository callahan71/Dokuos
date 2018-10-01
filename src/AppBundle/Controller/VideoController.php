<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Videos;
use AppBundle\Entity\Users; //probar
use AppBundle\Form\VideoType;

/**
 * Videos controller.
 *
 * @Route("/show/video")
 * @Security("has_role('ROLE_USER')")
 */
class VideoController extends Controller
{
    /**
     * Lists all Videos entities.
     *
     * @Route("/", name="show_video_index")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
		$whatRole = $this->get('security.authorization_checker');
		
		$user = $this->getUser();// para tomar el usuario loggeado
        $em = $this->getDoctrine()->getManager();
		
		if ($whatRole->isGranted('ROLE_ADMIN')){
			$videos = $em->getRepository('AppBundle:Videos')->findAll();
		}else
		{
			$videos = $em->getRepository('AppBundle:Videos')->findUserVideosOrderedByName($user);
		}		

        return $this->render('video/index.html.twig', array(
            'videos' => $videos,
        ));
    }

    /**
     * Creates a new Videos entity.
     *
     * @Route("/new", name="show_video_new")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
		$whatRole = $this->get('security.authorization_checker');
		
		$user = $this->getUser();// para tomar el usuario loggeado
        $video = new Videos();
        $form = $this->createForm('AppBundle\Form\VideoType', $video, ['role' => $this->getUser()->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if (!$whatRole->isGranted('ROLE_ADMIN')){
				$video->setUserid($user);//le paso el usuario que crea el video
			}			
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('show_video_show', array('id' => $video->getId()));
        }

        return $this->render('video/new.html.twig', array(
            'video' => $video,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Videos entity.
     *
     * @Route("/{id}", name="show_video_show")
     * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Videos $video)
    {
        $deleteForm = $this->createDeleteForm($video);

        return $this->render('video/show.html.twig', array(
            'video' => $video,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Videos entity.
     *
     * @Route("/{id}/edit", name="show_video_edit")
     * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Videos $video)
    {
        $deleteForm = $this->createDeleteForm($video);
        $editForm = $this->createForm('AppBundle\Form\VideoType', $video, ['role' => $this->getUser()->getRoles()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            //return $this->redirectToRoute('show_video_edit', array('id' => $video->getId()));
			return $this->redirectToRoute('show_video_index');
        }

        return $this->render('video/edit.html.twig', array(
            'video' => $video,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Deletes a Videos entity.
     *
     * @Route("/{id}", name="show_video_delete")
     * @Method("DELETE")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Videos $video)
    {
		$whatRole = $this->get('security.authorization_checker');
        $form = $this->createDeleteForm($video);
		$form->handleRequest($request);
		if (($this->getUser() == $video->getUserid()) || $whatRole->isGranted('ROLE_ADMIN')) {
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($video);
				$em->flush();
			}
			$this->addFlash('notice', 'Borrado correcto!');			
        } else {
			$this->addFlash('notice', 'No se puede borrar. No eres el propietario ni administrador.');	
		}
		return $this->redirectToRoute('show_video_index');
    }

    /**
     * Creates a form to delete a Video entity.
     *
     * @param Videos $video The Videos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Videos $video)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('show_video_delete', array('id' => $video->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
